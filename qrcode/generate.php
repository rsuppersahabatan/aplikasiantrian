<?php
if(isset($_POST))
{
	
	$antrian = file_get_contents("../data/antrian.json", TRUE);
	$dt = json_decode($antrian, TRUE);
	$no_antrian =  $dt['jumlah'] +1;
	$nik 	= htmlspecialchars_decode(strip_tags(trim($_POST['nik'])), ENT_QUOTES);
	$nama 	= htmlspecialchars_decode(strip_tags(trim($_POST['nama'])), ENT_QUOTES);

	$pics 	= $_POST['pics'];
	$data 	= array($no_antrian, $nik, $nama, $pics, date("Y-m-d H:is"));

	if(is_array($dt['data'])){
		array_push($dt['data'], $data);
	}else{
		$dt['data'][] = $data;
	}
	
	$dt['jumlah'] = $no_antrian;

	$fp = fopen("../data/antrian.json","w");
	fputs($fp, json_encode($dt));
	fclose($fp);
	//$qr->text(json_encode($data));

	$nasabah = file_get_contents("../data/nasabah.json", TRUE);
	$dtn = json_decode($nasabah, TRUE);
	$dtn['jumlah'] = $no_antrian;
	if(is_array($dtn['data'])){
		array_push($dtn['data'], $data);
	}else{
		$dtn['data'][] = $data;
	}
	$fp = fopen("../data/nasabah.json","w");
	//unset($dt['sisa']);
	fputs($fp, json_encode($dtn));
	fclose($fp);
	
	echo "<div style='background-color:white; text-align:center; padding:20px 5px;'>";
	echo "<p>Nama : ".$nama."</p>";
	echo "<p>No. KTP : ".$nik."</p>";
	echo '<p><img width="40%" border="0" src="../qrcode/generate_code.php?nik='.$nik.'&nama='.urlencode($nama).'&no='.$no_antrian.'" /></p>'; 
	//echo "<p><img width='40%' src='".$pics."' border='0'/></p>";
	echo "<h4 style='color:#000;'>Nomor Antrian</h4>";
	echo "<h1 style='color:#000;'>".$no_antrian."</h1>";
	echo "</div>";
	
}
?>