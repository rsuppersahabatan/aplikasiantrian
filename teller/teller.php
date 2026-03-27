<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Panggilan Nomor Antrian</title>

  <!-- Stylesheet -->
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/jquery-ui-1.10.3.custom.css" />

  <link rel="stylesheet" type="text/css" href="../assets/css/splash/bootstrap.3.min.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/select2.min.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/bootstrap-responsive.min.css" />

  <link rel="stylesheet" type="text/css" href="../assets/css/splash/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/style.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/animations.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/superslides.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/flexslider.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/masonry.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/fancybox.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/splash/color/multicolor.css" id="swap-color" />

  <style type="text/css">
    #outdiv
      {
        width:100%;
        
        border: dashed #FFF;
        border-width: 2px;
      }
    #v{
        width:100%;
        
      }
    .snap{
            width:100%;
            border: dashed #AD0;
            border-width: 2px;
            margin-bottom: 5px;
      } 
    .snap img{
        width: 100%;
        margin: 0 auto;
      }
  </style>

</head>
<body>

<!-- Logo -->
<div class="logo"><img src="../images/bank_abc.png" alt="Logo" width="150" />
  <div id="map-canvas-home"></div>
</div>

<!-- Slider -->
<div id="slides">
  <ul class="slides-container">
    <!-- Slider Single Item -->
    <li><a href="#"><img src="../images/Teller-Line.jpg" alt="Backgriound 1" /></a>
        <div class="slides-detail">
            <h1>Aplikasi <span>Pelayanan</span> Antrian</h1>
            <span class="lead">Bank <strong>ABC</strong></span>
        </div>
    </li>
    <li><a href="#"><img src="../images/Waiting-Area.jpg" alt="Backgriound 2" /></a>
        <div class="slides-detail">
            <h1>Gunakan <span>Kamera</span></h1>
            <span class="lead">Untuk memindai <strong>QR Code </strong></span>
        </div>
    </li>
  </ul>  
</div>
<?php 
$teller = (isset($_GET['teller'])) ? $_GET['teller'] : 1;
$nmteller = (isset($_GET['nmteller'])) ? $_GET['nmteller'] : "NOD16";
if($teller == 1)
  $color = "#2A69B0";
elseif($teller == 2)
  $color = "#669";
elseif($teller == 3)
  $color = "#FC0";

?>

        


<!-- About Section -->
<div id="services" class="section" style="background-color: <?=$color;?>;">
    <div class="section-title"><i class="icon-user"></i>
        <strong>Loket <?=$teller;?></strong>
        <p>Teller : <span><?=$nmteller;?></span></p>
    </div>


  <!-- About Content -->
  <div class="container">
    <hr />

    <div class="row">
      
      <div class="col-xs-8 col-md-6">

        <div class="section-title" style="margin-top: 0px; margin-bottom: 35px;"><p>Data <span>Antrian</span></p></div>
        <div class="alert alert-danger hidden" id="alert" style="margin-bottom: 50px; width: 100%;"></div>
        <form action="../qrcode/generate.php" id="form_antrian" method="post" class="contact-form form-horizontal">
          <input type="hidden" name="pics" id="pics">
          <fieldset>
            <div class="form-group">
                <label class="control-label col-md-2" for="inputNo">No. Antrian</label>
                <div class="col-md-10">
                    <input type="number" id="inputNo" class="form-control" autocomplete="off" required name="antrian" placeholder="Nomor Antrian">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2" for="inputNik">No. KTP</label>
                <div class="col-md-10">
                    <input type="number" id="inputNik" class="form-control" autocomplete="off" required name="nik" placeholder="Nomor KTP">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2" for="inputNama">Nama</label>
                <div class="col-md-10">
                    <input name="nama" class="form-control" id="inputNama" required autocomplete="off" type="text" placeholder="Nama" />
                    <textarea id="result"></textarea>
                </div>
            </div>

            <div class="form-group">    
                <div class="col-md-offset-2 col-md-6">
                    <button type="button" id="btn" onclick="dequeue()" style="width: 100%;" class="btn btn-default btn-large">
                            <i class="icon-flag"></i> Prosess</button>
                                     
                </div>
            </div>


          </fieldset>
        </form>
      </div>
      <div class="col-xs-4 col-md-3">
        <div class="snap hidden">
            <img src="" class="img-responsive hidden">
        </div>
        
        <div id="outdiv"></div>

        <canvas id="qr-canvas" style="display:none;"></canvas>

      </div>

      <div class="col-xs-12 col-md-3">
        <div class="section-title" style="margin-top: 0px; margin-bottom: 35px;"><p>Nomor <span>Antrian</span></p></div>
        <h1 class="main-title" style="background-color: #F33;"><span id="calling">Ready</span></h1>

        <form class="contact-form">
          <fieldset>                        
            <div class="form-group">
                <button type="button" id="btncall" onclick="call();" style="width: 100%;" class="btn btn-large"><i class="icon-play"></i> Panggilan Selanjutnya</button>
                <button type="button" id="btnvoice" style="width: 100%;" class="btn btn-large hidden"><i class="icon-bullhorn"></i> Sedang Memanggil</button>
            </div>
            <div class="form-group">    
                <button type="button" id="btnrecall" onclick="recall()" style="width: 100%;" class="btn btn-default btn-large"><i class="icon-refresh"></i> Ulangi Panggilan</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>  
    <div class="row">
      <div class="col-md-12">
        
      </div>
    </div>
  </div>
</div>

<div id="folio" class="section" style="background-color: #00CCCC;">
    <div class="section-title"><i class="icon-cog"></i><strong>Pengaturan</strong>
        <p>Antrian <span>(Queue)</span></p>
    </div>

    <!-- Form PMB-->
    <div class="container">
        <hr />
        <div class="row">
            <div class="col-xs-6 col-md-6 text-left">
                <h3>Jumlah Antrian  : <span id="jum_antrian">5</span></h3>
                <h3>Sisa Antrian  : <span id="sisa_antrian">2</span> </h3>
                <h3>Antrian Terakhir : <span id="nomor_antrian">2</span> </h3>
            </div>
            <div class="col-xs-3 col-md-3">
                <form method="post" class="contact-form form-horizontal">
                    <div class="form-group">   
                        <button type="button" id="btn" onclick="hapusdata()" style="width: 100%;" class="btn btn-default btn-large">
                            <i class="icon-trash"></i> Hapus Data Nasabah
                        </button>
                        <p class="text-center margin-bottom">Menghapus seluruh data nasabah yang telah mengambil nomor antrian</p>
                    </div>
                </form>
                
            </div>
            <div class="col-xs-3 col-md-3">
                <form method="post" class="contact-form form-horizontal">
                  <input type="hidden" name="pics" id="pics">
                  <fieldset>
                    <div class="form-group">   
                        <button type="button" id="btn" onclick="resetantrian()" style="width: 100%;" class="btn btn-default btn-large">
                            <i class="icon-refresh"></i> Reset Antrian
                        </button>
                        <p class="text-center margin-bottom">Atur ulang posisi antrian ke nomor urut <b>1</b></p>
                    </div>
                  </fieldset>
                </form>
            </div>
            
        </div>

        
    </div>
</div>

<div id="blog" class="section" style="background-color: #d35400;">
    <div class="section-title"><i class="icon-smile"></i><strong>About</strong>
        <p>This <span>Program</span></p>
    </div>

    <!-- Form PMB-->
    <div class="container">
        <hr />
        <p class="text-center margin-bottom">Program/aplikasi ini dibangun sebagai salah satu Tugas Algoritma dan Struktur Data kelas MIF2016, Magister Teknik Informatika di <a href="http://ilkom.unsri.ac.id/" target="_blank">Fakultas Ilmu Komputer</a>, Universitas Sriwijaya. Tujuan aplikasi ini dibuat adalah untuk menerapkan proses Queue pada lingkungan industri, salah satunya adalah Sistem Antrian pada Bank.</p>
        <p class="text-center margin-bottom">Program yang dibuat adalah berbasis web, berjalan diatas protocol <a href="https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol" target="_blank">http</a> dan <a href="https://en.wikipedia.org/wiki/WebSocket" target="_blank">ws (websocket)</a> sehingga proses transaksi data lebih dinamis. Bahasa pemrograman yang digunakan adalah <a href="http://php.net/manual/en/intro-whatis.php" target="_blank">PHP</a> dengan sentuhan <a href="https://jquery.com/" target="_blank">JQuery</a> dan <a href="http://www.getbootstrap.com/" target="_blank">Bootstrap</a> agar tampilan aplikasi menjadi lebih menarik. Program dibangun dalam jangka waktu 14 Hari. Sesuai spesifikasi system, program mampu meng-generate <a href="https://en.wikipedia.org/wiki/QR_code" target="_blank">QRCode</a> dan memindainya kembali dengan Kamera yang terpasang pada perangkat keras.</p>
        <h1 class="main-title">Our <span>Team</span></h1>

        <div class="flexslider">
            <!-- About Profile Carousel -->
            <ul class="thumbnails slides">

                <!-- About Profile Detail -->
                <li>
                <div class="thumbnail">
                <h3>Nur <span>Rachmat</span></h3>
                <img src="../images/team/nr.jpg" alt="Nur Rachmat" class="thumb" />
                <div class="thumbnail-detail">
                  <h5><span>NIM : </span>09042681620003</h5>
                  <p>Email : <span>rachmat.nur91@mdp.ac.id</span><br/>
                  Phone : <span>+62 852 6739 3319</span></p>
                </div>
                <div class="social-links">
                    <a href="mailto:rachmat.nur91@mdp.ac.id" class="btn"><i class="icon-google-plus"></i></a>
                </div>
                <!-- <div class="social-links">
                    <a href="mailto:rachmat.nur91@mdp.ac.id" class="btn"><i class="icon-google-plus"></i></a>
                </div> -->
                </div>
                </li>

                <!-- About Profile Detail -->
                <li>
                <div class="thumbnail">
                <h3>Orissa <span>Octaria</span></h3>
                <img src="../images/team/oo.jpg" alt="Orissa Octaria" class="thumb" />
                <div class="thumbnail-detail">
                  <h5><span>NIM : </span>09042681620002</h5>
                  <p>Email : <span>orissa.octaria@mdp.ac.id</span><br/>
                  Phone : <span>+62 812 8346 6678</span></p>
                </div>
                <div class="social-links">
                    <a href="mailto:orissa.octaria@mdp.ac.id" class="btn"><i class="icon-google-plus"></i></a>
                </div>
                </div>
                </li>

                <!-- About Profile Detail -->
                <li>
                <div class="thumbnail">
                <h3>Dwi <span>Meilitasari T.</span></h3>
                <img src="../images/team/dmt.jpg" alt="Dwi Meilitasari Tarigan" class="thumb" />
                <div class="thumbnail-detail">

                  <h5><span>NIM : </span>09042681620006</h5>
                  <p>Email : <span>dwimeylitasaritarigan@gmail.com</span><br/>
                  Phone : <span>+62 821 7543 0080</span></p>
                </div>
                <div class="social-links">
                    <a href="mailto:dwimeylitasaritarigan@gmail.com" class="btn"><i class="icon-google-plus"></i></a>
                </div>
                </div>
                </li>

            </ul>
        </div>
    </div>
</div>



<!--</div>-->
<a href="#" class="section-close">&times;</a>

<!-- Scripts -->
<script type="text/javascript" src="../assets/js/splash/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="../assets/js/splash/jquery-ui.js"></script>

<script type="text/javascript" src="../assets/js/splash/select2.full.js"></script>
<script type="text/javascript" src="../assets/js/splash/bootstrap.min.js"></script>

<script type="text/javascript" src="../assets/js/splash/jquery.animate-enhanced.min.js"></script>
<script type="text/javascript" src="../assets/js/splash/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="../assets/js/splash/jquery.superslides.min.js"></script>
<script type="text/javascript" src="../assets/js/splash/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="../assets/js/splash/jquery.masonry.min.js"></script>
<script type="text/javascript" src="../assets/js/splash/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="../assets/js/splash/jquery.nicescroll.min.js"></script>
<script type="text/javascript" src="../assets/js/splash/bootstrap3-typeahead.js"></script>
<script type="text/javascript" src="../assets/js/splash/modernizr.min.js"></script>
<script type="text/javascript" src="../assets/js/splash/glfx.min.js"></script>
<script type="text/javascript" src="../assets/js/webqr/llqrcode.js"></script>
<script type="text/javascript" src="../assets/js/webqr/webqr.js"></script>

<script>
var teller = "<?=$teller;?>"
  var conn = new WebSocket('ws://'+ window.location.hostname+':8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
        init();
    };

    conn.onmessage = function(e) {
        console.log(e.data);
        var msg = e.data.split(":");
        if(teller ==  msg[2]){
            if(msg[0] == 'datanasabah'){
                var img = document.querySelector('.snap img');
                $(".snap").removeClass("hidden");
                $(img).attr('src', "data:image/webp;"+msg[3])
                    .removeClass('hidden');
            }else{
                $("#calling").html(msg[1]);
                if(msg[0] == "callbackteller"+teller){
                    $("#nomor_antrian").html(msg[1]);
                }
            }
        }

        if(msg[2]=="call" || msg[2]=="recall"){
            $("#btncall").addClass('hidden');
            $("#btnrecall").addClass('hidden');
            $("#btnvoice").removeClass('hidden');
            $("#btnvoice").html("<i class=\"icon-bullhorn\"></i> Teller " +msg[3] + " Memanggil");
            if(msg[2]=="call"){
                $("#nomor_antrian").html(msg[1]);
            }
        }

        if(e.data == "next"){
            $("#btncall").removeClass('hidden');
            $("#btnrecall").removeClass('hidden');
            $("#btnvoice").addClass('hidden');
        }

        if(msg[0] == "sisa"){
            var ant = msg[1].split("/")
            $("#jum_antrian").html(ant[1]);
            $("#sisa_antrian").html(ant[0]);
        }

        if(msg[0] == "resetantrian"){
            $("#nomor_antrian").html(msg[1]);
        }
        
        //document.getElementById('count').innerHTML = "You Receive : " + event.data;
    };

    conn.onclose = function() {
        $("#calling").html("Teller <?=$teller;?> Off");
        $("#alert").html("Tidak dapat membuka Teller <?=$teller;?>, koneksi ke server terputus.")
                    .removeClass('hidden');

    };

    function call(){
        conn.send('call:teller<?=$teller;?>:<?=$teller;?>');
        $("#btncall").addClass('hidden');
        $("#btnrecall").addClass('hidden');
        $("#btnvoice").removeClass('hidden');
        $("#btnvoice").html("<i class=\"icon-bullhorn\"></i> Sedang Memanggil");
    }
    function recall(){
        var calling = $("#calling").html();
        conn.send('recall:teller<?=$teller;?>:<?=$teller;?>:'+calling);

        $("#btncall").addClass('hidden');
        $("#btnrecall").addClass('hidden');
        $("#btnvoice").removeClass('hidden');
        $("#btnvoice").html("<i class=\"icon-bullhorn\"></i> Sedang Memanggil");
    }

    function init(){
        conn.send('teller<?=$teller;?>');
    }

    function dequeue(){
        load();
        var no_antri = $("#inputNo").val();
        if(no_antri != ''){
            conn.send('dequeue:'+no_antri);
        }
        
        $("#result").val("-scanning-");
    
        $("#inputNo").val('');
        $("#inputNik").val('');
        $("#inputNama").val('');

        var img = document.querySelector('.snap img');
        $(".snap").addClass("hidden");
        $(img).attr('src', '')
                .addClass('hidden');

    }

    function getdatanasabah(){
        var no = $("#inputNo").val();
        conn.send('getnasabah:teller<?=$teller;?>:<?=$teller;?>:'+no);
    }

    function hapusdata(){
        conn.send('resetnasabah');
        $("#jum_antrian").html("0");
        $("#sisa_antrian").html("0");
        $("#nomor_antrian").html("1");
    }

    function resetantrian(){
        conn.send('resetantrian');
        $("#nomor_antrian").html("1");
    }
</script>

<script type="text/javascript">
load();
var base_url = window.location.protocol + "//" + window.location.host + "/";
$(document).ready(function () {
    $("#result").change(function(){
        
    });


    $(".section-about i").toggleClass("active");
    
    /* --- Open Section --- */
    $(".section").click(function () {
    $(".section-close").show(250);
        $(this).addClass("section-expand");
        //$(".switch-section").show(250);        
        //$(".switch-section").addClass("switch-section-open");
    });
  
    /* --- Close Section --- */
    $(".section-close").click(function () { 
        $(".section").removeClass("section-expand");
        //$(".switch-section").hide(250);
        $(".section-close").hide(250);
        //$(".section-about i, .section-services i, .section-folio i, .section-blog i, .section-contact i").removeClass("active");
    
    $("#panelpmb").hide();
    $("#panelpilih").show();

        $("#panelnodaftar").show(25);
        $("#panelreg").hide(25);

    });
  
    /* --- Side Menu --- */
    $(".switch-section").click(function () {
        $(this).toggleClass("switch-section-open");
    });

    /* --- Switch Section --- */
    $(".section-about").click(function () {
        $(".section").removeClass("section-expand");
        $("#about").addClass("section-expand");
    });
    
    $("#about").click(function () {
        $(".section-about i").toggleClass("active");
    });

    $(".section-services").click(function () {
        $(".section").removeClass("section-expand");
        $("#services").addClass("section-expand");
    });
    
    $("#services").click(function () {
        $(".section-services i").toggleClass("active");
    });

    $(".section-folio").click(function () {
        $(".section").removeClass("section-expand");
        $("#folio").addClass("section-expand");
    $("#btnstmik").trigger("focus");
    });
    
    $("#folio").click(function () {
        //$(".section-folio i").toggleClass("active");
      $(".section").removeClass("section-expand");
        $("#folio").addClass("section-expand");
      $("#btnstmik").trigger("focus");
    });

    $(".section-blog").click(function () {
        $(".section").removeClass("section-expand");
        $("#blog").addClass("section-expand");
        $("#alt-ambildata").alert('close');
    });

    $("#blog").click(function () {
        //$(".section-blog i").toggleClass("active");
        $(".section").removeClass("section-expand");
        $("#blog").addClass("section-expand");
        $("#alt-ambildata").alert('close');
    });

    $(".section-contact").click(function () {
        $(".section").removeClass("section-expand");
        $("#contact").addClass("section-expand");

    });
    
    
    $("#contact").click(function () {
        $(".section-contact i").toggleClass("active");
    });

    /* --- Active Filter Menu --- */
    $(".switch-section a i, .filter a").click(function (e) {
        $(".switch-section a i, .filter a").removeClass("active");
        $(this).addClass("active");
        e.preventDefault();
    });

    /* --- Masonry --- */


    /* --- Item Description --- */
    $(".item").click(function () {
        $(this).toggleClass("open");
    });

    /* --- Fancybox --- */
    $(".view-fancybox").fancybox({
        openEffect: 'elastic',
        closeEffect: 'elastic',
        next: '<i class="icon-smile"></i>',
        prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
    });

    /* --- NiceScroll --- */
    $(".section").niceScroll();

    /* --- Slider --- */
    $('#slides').superslides({
        slide_easing: 'easeInOutCubic',
        slide_speed: 800,
        play: 4000,
        pagination: true,
        hashchange: true,
        scrollable: true
    });

    $("#contact, .section-contact").on("click", function () {

        step1();
        $(video).removeClass("hidden");
        video.play();
        $(img).attr('src', '');
        $(img).addClass('hidden');
        $(cetakan).html('');

    });

    /* --- Swap color --- */
    $('.change-white, .change-black, .change-multicolor').click(function () {
        $('#swap-color').attr('href', 'css/color/' + $(this).data('color') + '.css');
        return false;
    });

    /*=============================================================================================-==*/
    $("#form_antrian").submit(function (event) {
        // Stop form from submitting normally
        event.preventDefault();

        step2();
        changeStep(2);
        var vimg =  $(img).attr("src");
        $("#pics").val(vimg);

        // Get some values from elements on the page:
        var $form = $(this), url = $form.attr("action");

        // Send the data using post
        $.post(url, $(this).serialize(), function (data) {
            $("#cetakan").html(data);

            $("#inputNik").val("");
            $("#inputNama").val("");

        }, "html");
        
    });

    /*===============================================================================================*/

    

});

/* --- Flex Slider --- */
$(window).load(function () {
  $(".section-about i").toggleClass("active");
    $(".flexslider").flexslider({
        animation: "slide",
        animationLoop: true,
        itemWidth: 300,
        itemMargin: 0,
        prevText: "<i class='icon-angle-left'></i>",
        nextText: "<i class='icon-angle-right'></i>"
    });
});


</script>

</body>
</html>