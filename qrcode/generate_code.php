<?php 
include('../phpqrcode/qrlib.php'); 
define('IMAGE_WIDTH',500);
define('IMAGE_HEIGHT',500);
	$nik = htmlspecialchars_decode(strip_tags(trim($_GET['nik'])), ENT_QUOTES);
	$nama = htmlspecialchars_decode(strip_tags(trim($_GET['nama'])), ENT_QUOTES); 
	$no_antrian = htmlspecialchars_decode(strip_tags(trim($_GET['no'])), ENT_QUOTES); 
 	ob_start("callback"); 
     
    // here DB request or some processing 
   	
	$data = array($no_antrian, $nik, $nama);
     
    // end of processing here 
    $debugLog = ob_get_contents(); 
    ob_end_clean(); 
    
    // outputs image directly into browser, as PNG stream 
    QRcode::png(json_encode($data), FALSE, QR_ECLEVEL_L, 20,1);
    //echo json_encode($data);