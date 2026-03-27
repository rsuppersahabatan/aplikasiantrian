<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>Pengambilan Nomor Antrian</title>

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
  .snap video{
    width : 100%;
    margin: 0 auto;
  }
  .snap canvas{
    width : 100%;
    margin: 0 auto;
  }
  .snap img{
    width : 100%;
    margin: 0 auto;
  }
  .snap figure.not-ready video {
    border: solid gray 1px;
    width: 100%;
    height: 300px;
  }

  .snap figure.not-ready {
      position: relative;
  }

  .snap figure.not-ready:after {
      content: 'Please enable the camera.';
      position: absolute;
      left: 50%;
      top: 50%;
      margin-left: -85px;
      margin-top: -10px;
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
        <h1>Siapkan <span>KTP</span> anda</h1>
        <span class="lead">Masukkan <strong>Nomor KTP</strong> dan <strong>Nama Lengkap</strong> (Sesuai KTP)</span>
      </div>
    </li>
  </ul>

  <!-- Slider Navigation -->
  <!-- <div class="slides-navigation">
      <a href="#" class="prev"><i class="icon-angle-left"></i></a>
      <a href="#" class="next"><i class="icon-angle-right"></i></a>
  </div> -->
</div>




<!-- Contact Section -->
<div id="contact" class="section">
    <div class="section-title"><i class="icon-edit"></i><strong>Ambil</strong>
        <p>Nomor <span>Antrian</span></p>
    </div>
    <!-- Contact Content -->
    <div class="container">
        <hr/>
        
        <div class="row">
          <div class="col-md-6">
            <form action="../qrcode/generate.php" id="form_antrian" method="post" class="contact-form form-horizontal">
              <input type="hidden" name="pics" id="pics">
              <fieldset>
                <div class="form-group">
                    <label class="control-label col-md-2" for="inputNik">No. KTP</label>
                    <div class="col-md-10">
                        <input type="number" id="inputNik" class="form-control" maxlength="16" autocomplete="off" required name="nik" placeholder="Masukkan No. KTP Anda">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2" for="inputNama">Nama</label>
                    <div class="col-md-10">
                        <input name="nama" class="form-control" id="inputNama" required autocomplete="off" type="text" placeholder="Masukkan Nama Anda" />
                    </div>
                </div>

                
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <button type="submit" name="simpan" class="btn btn-large">Cetak No. Antrian</button>
                    </div>
                </div>
              </fieldset>
            </form>
          </div>
          <div class="col-md-3">
            <div class="alert alert-danger hidden"><strong>Oops!</strong> <span></span></div>
            <figure class="snap">
                <video autoplay></video>
            </figure>

            <figure class="snap">
                <canvas style="display:none"></canvas>
                <img src=""/>
            </figure>

          </div>
          <div class="col-md-3">
              <div id="cetakan">
              </div>
          </div>
            <!-- <div class="span12">
                <img src="img/brosur1.jpg" class="img-responsive">
                <img src="img/brosur2.jpg" class="img-responsive">
            </div> -->
        </div>
    </div>
</div>


<!-- Switch Section -->
<!--<div class="switch-section"><i class="icon-reorder icon-label"></i>
  <p class="switch-section-cont">-->
      <!--<a href="Statti.html#" class="section-about"><i class="icon-smile"></i></a>
      <a href="Statti.html#" class="section-services"><i class="icon-cog"></i></a>-->
      <!--<a href="#" class="section-folio"><i class="icon-pencil"></i></a>
      <a href="#" class="section-blog"><i class="icon-edit"></i></a>
      <a href="#" class="section-contact"><i class="icon-picture"></i></a>
  </p>-->
  <!--<div class="change-white" data-color="white"></div>
  <div class="change-black" data-color="black"></div>
  <div class="change-multicolor" data-color="multicolor"></div>-->
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

<script type="text/javascript" src="../assets/js/splash/functions.js"></script>

</body>
</html>