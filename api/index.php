<?php
/**
 * REST API – Aplikasi Antrian
 * Base URL: /api/
 *
 * Endpoints:
 *  GET    /api/antrian            → Status antrian
 *  POST   /api/antrian/enqueue    → Tambah nasabah baru
 *  POST   /api/antrian/dequeue    → Teller proses nomor antrian
 *  POST   /api/antrian/call       → Teller panggil nomor berikutnya
 *  POST   /api/antrian/recall     → Teller ulangi panggilan
 *  POST   /api/antrian/reset      → Reset counter ke 1
 *  GET    /api/nasabah            → Daftar semua nasabah
 *  GET    /api/nasabah/{no}       → Data nasabah per nomor antrian
 *  DELETE /api/nasabah            → Hapus semua data nasabah
 *  GET    /api/teller             → Status semua teller
 */

// ─── CORS & Headers ───────────────────────────────────────────────────────────
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ─── Config ───────────────────────────────────────────────────────────────────
define('DATA_DIR',     __DIR__ . '/../data/');
define('ANTRIAN_FILE', DATA_DIR . 'antrian.json');
define('NASABAH_FILE', DATA_DIR . 'nasabah.json');
define('TELLER_FILE',  DATA_DIR . 'teller.json');
define('COUNTER_FILE', DATA_DIR . 'data.txt');

// ─── Helpers ──────────────────────────────────────────────────────────────────

/** Kirim response JSON */
function respond(bool $success, $data = null, string $message = '', int $code = 200): void
{
    http_response_code($code);
    $body = ['success' => $success];
    if ($message !== '')        $body['message'] = $message;
    if ($data !== null)         $body['data']    = $data;
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/** Baca file JSON, kembalikan array */
function readJson(string $file): array
{
    if (!file_exists($file)) return [];
    $content = file_get_contents($file);
    return json_decode($content, true) ?? [];
}

/** Tulis array ke file JSON secara atomic */
function writeJson(string $file, array $data): bool
{
    $tmp = $file . '.tmp';
    $ok  = file_put_contents($tmp, json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX);
    if ($ok === false) return false;
    return rename($tmp, $file);
}

/** Baca counter nomor antrian */
function readCounter(): int
{
    if (!file_exists(COUNTER_FILE)) return 1;
    return (int) trim(file_get_contents(COUNTER_FILE));
}

/** Tulis counter */
function writeCounter(int $value): void
{
    file_put_contents(COUNTER_FILE, $value, LOCK_EX);
}

/** Ambil body JSON dari request */
function requestBody(): array
{
    $raw = file_get_contents('php://input');
    if (!$raw) return $_POST;
    $json = json_decode($raw, true);
    return is_array($json) ? $json : $_POST;
}

/** Sanitasi input string */
function clean(?string $val): string
{
    return htmlspecialchars_decode(strip_tags(trim((string) $val)), ENT_QUOTES);
}

// ─── Router ───────────────────────────────────────────────────────────────────

$method = $_SERVER['REQUEST_METHOD'];

// Ambil path relatif terhadap /api/
$requestUri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir   = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path        = '/' . trim(substr($requestUri, strlen($scriptDir)), '/');

// Normalisasi trailing slash
$path = rtrim($path, '/') ?: '/';

// ─── Routes ───────────────────────────────────────────────────────────────────

/* ------------------------------------------------------------------ ANTRIAN */

// GET /antrian – Status antrian
if ($method === 'GET' && $path === '/antrian') {
    $dt      = readJson(ANTRIAN_FILE);
    $counter = readCounter();
    respond(true, [
        'jumlah'         => (int) ($dt['jumlah'] ?? 0),
        'sisa'           =>  (int) ($dt['sisa']   ?? 0),
        'nomor_terakhir' => $counter,
        'antrian'        => array_values($dt['data'] ?? []),
    ]);
}

// POST /antrian/enqueue – Tambah nasabah baru ke antrian
if ($method === 'POST' && $path === '/antrian/enqueue') {
    $body = requestBody();
    $nik  = clean($body['nik']  ?? '');
    $nama = clean($body['nama'] ?? '');

    if ($nik === '' || $nama === '') {
        respond(false, null, 'Field nik dan nama wajib diisi.', 422);
    }

    // Update antrian.json
    $dt          = readJson(ANTRIAN_FILE);
    $no_antrian  = (int) ($dt['jumlah'] ?? 0) + 1;
    $timestamp   = date('Y-m-d H:i:s');
    $record      = [$no_antrian, $nik, $nama, '', $timestamp];

    $dt['data'][] = $record;
    $dt['jumlah'] = $no_antrian;
    $dt['sisa']   = count($dt['data']);
    writeJson(ANTRIAN_FILE, $dt);

    // Update nasabah.json
    $dtn          = readJson(NASABAH_FILE);
    $dtn['data'][] = $record;
    $dtn['jumlah'] = $no_antrian;
    writeJson(NASABAH_FILE, $dtn);

    respond(true, [
        'no_antrian' => $no_antrian,
        'nik'        => $nik,
        'nama'       => $nama,
        'timestamp'  => $timestamp,
    ], 'Antrian berhasil ditambahkan.', 201);
}

// POST /antrian/dequeue – Teller proses (hapus) nomor antrian
if ($method === 'POST' && $path === '/antrian/dequeue') {
    $body       = requestBody();
    $no_antrian = (int) ($body['no_antrian'] ?? 0);

    if ($no_antrian <= 0) {
        respond(false, null, 'Field no_antrian wajib diisi.', 422);
    }

    $dt    = readJson(ANTRIAN_FILE);
    $index = $no_antrian - 1;

    if (!isset($dt['data'][$index])) {
        respond(false, null, "Nomor antrian {$no_antrian} tidak ditemukan.", 404);
    }

    unset($dt['data'][$index]);
    $dt['data'] = array_values($dt['data']);
    $dt['sisa'] = count($dt['data']);
    writeJson(ANTRIAN_FILE, $dt);

    respond(true, [
        'sisa'   => $dt['sisa'],
        'jumlah' => (int) ($dt['jumlah'] ?? 0),
    ], "Nomor antrian {$no_antrian} berhasil diproses.");
}

// POST /antrian/call – Teller panggil nomor berikutnya
if ($method === 'POST' && $path === '/antrian/call') {
    $body   = requestBody();
    $teller = (int) ($body['teller'] ?? 1);
    $counter = readCounter();

    respond(true, [
        'no_antrian' => $counter,
        'teller'     => $teller,
        'action'     => 'call',
        'message_ws' => "call:teller{$teller}:{$teller}",
    ], "Teller {$teller} memanggil nomor antrian {$counter}.");
}

// POST /antrian/recall – Teller ulangi panggilan
if ($method === 'POST' && $path === '/antrian/recall') {
    $body       = requestBody();
    $teller     = (int)    ($body['teller']     ?? 1);
    $no_antrian = (int)    ($body['no_antrian'] ?? readCounter());

    respond(true, [
        'no_antrian' => $no_antrian,
        'teller'     => $teller,
        'action'     => 'recall',
        'message_ws' => "recall:teller{$teller}:{$teller}:{$no_antrian}",
    ], "Teller {$teller} mengulang panggilan nomor {$no_antrian}.");
}

// POST /antrian/reset – Reset counter antrian ke 1
if ($method === 'POST' && $path === '/antrian/reset') {
    writeCounter(1);
    respond(true, ['nomor_terakhir' => 1], 'Counter antrian berhasil direset ke 1.');
}

/* ------------------------------------------------------------------ NASABAH */

// GET /nasabah – Daftar semua nasabah
if ($method === 'GET' && $path === '/nasabah') {
    $dtn    = readJson(NASABAH_FILE);
    $mapped = array_map(function ($item) {
        return [
            'no_antrian' => $item[0] ?? null,
            'nik'        => $item[1] ?? null,
            'nama'       => $item[2] ?? null,
            'timestamp'  => $item[4] ?? null,
        ];
    }, $dtn['data'] ?? []);

    respond(true, [
        'jumlah'  => (int) ($dtn['jumlah'] ?? 0),
        'nasabah' => array_values($mapped),
    ]);
}

// GET /nasabah/{no} – Data nasabah berdasarkan nomor antrian
if ($method === 'GET' && preg_match('#^/nasabah/(\d+)$#', $path, $m)) {
    $no  = (int) $m[1];
    $dtn = readJson(NASABAH_FILE);

    $found = null;
    foreach (($dtn['data'] ?? []) as $item) {
        if ((int) ($item[0] ?? 0) === $no) {
            $found = [
                'no_antrian' => $item[0],
                'nik'        => $item[1],
                'nama'       => $item[2],
                'timestamp'  => $item[4] ?? null,
            ];
            break;
        }
    }

    if (!$found) {
        respond(false, null, "Nasabah dengan nomor antrian {$no} tidak ditemukan.", 404);
    }
    respond(true, $found);
}

// DELETE /nasabah – Hapus semua data nasabah & antrian
if ($method === 'DELETE' && $path === '/nasabah') {
    $emptyNasabah = ['data' => [], 'jumlah' => 0];
    $emptyAntrian = ['data' => [], 'jumlah' => 0, 'sisa' => 0];
    writeJson(NASABAH_FILE, $emptyNasabah);
    writeJson(ANTRIAN_FILE, $emptyAntrian);
    respond(true, null, 'Semua data nasabah dan antrian berhasil dihapus.');
}

/* ------------------------------------------------------------------ TELLER */

// GET /teller – Status semua teller
if ($method === 'GET' && $path === '/teller') {
    $dt = readJson(TELLER_FILE);
    respond(true, $dt);
}

/* ----------------------------------------------------------------- 404 CATCH */
respond(false, null, "Endpoint tidak ditemukan: [{$method}] {$path}", 404);
