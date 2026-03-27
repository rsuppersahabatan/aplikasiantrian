<?php
/**
 * Membaca .env dan menyediakan config sebagai JS variables.
 * Include via: <script src="/appantrian/config.php"></script>
 */
header('Content-Type: application/javascript; charset=utf-8');
header('Cache-Control: no-cache');

$envFile = __DIR__ . '/.env';
$env = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
}

$wsPort = json_encode($env['WS_PORT'] ?? '8080');
$wsUrl  = json_encode($env['WS_URL']  ?? 'ws://localhost');

echo <<<JS
var APP_CONFIG = {
  WS_PORT: {$wsPort},
  WS_URL: {$wsUrl},
  wsFullUrl: function() {
    return 'ws://' + window.location.hostname + ':' + this.WS_PORT;
  }
};
JS;
