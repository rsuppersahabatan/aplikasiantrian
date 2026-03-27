// JavaScript Functions ( Statti Template )

$(document).ready(function () {
  
  /* --- Open Section --- */
  $(".section").click(function () {
    $(this).addClass("section-expand");
    $(".switch-section").show(250);
    $(".section-close").show(250);
    $(".switch-section").addClass("switch-section-open");
  })
  
  /* --- Close Section --- */
  $(".section-close").click(function () {
    $(".section").removeClass("section-expand");
    $(".switch-section").hide(250);
    $(".section-close").hide(250);
    $(".section-about i, .section-services i, .section-folio i, .section-blog i, .section-contact i").removeClass("active");
  })
  
  /* --- Side Menu --- */
  $(".switch-section").click(function () {
    $(this).toggleClass("switch-section-open");
  })
  
  /* --- Switch Section --- */
  $(".section-about").click(function () {
    $(".section").removeClass("section-expand");
    $("#about").addClass("section-expand");
  })
  $("#about").click(function () {
    $(".section-about i").toggleClass("active");
  })

  $(".section-services").click(function () {
    $(".section").removeClass("section-expand");
    $("#services").addClass("section-expand");
  })
  $("#services").click(function () {
    $(".section-services i").toggleClass("active");
  })

  $(".section-folio").click(function () {
    $(".section").removeClass("section-expand");
    $("#folio").addClass("section-expand");
  })
  $("#folio").click(function () {
    $(".section-folio i").toggleClass("active");
  })

  $(".section-blog").click(function () {
    $(".section").removeClass("section-expand");
    $("#blog").addClass("section-expand");
  })
  $("#blog").click(function () {
    $(".section-blog i").toggleClass("active");
  })

  $(".section-contact").click(function () {
    $(".section").removeClass("section-expand");
    $("#contact").addClass("section-expand");
  })
  $("#contact").click(function () {
    $(".section-contact i").toggleClass("active");
  })

  /* --- Active Filter Menu --- */
  $(".switch-section a i, .filter a").click(function (e) {
    $(".switch-section a i, .filter a").removeClass("active");
    $(this).addClass("active");
    e.preventDefault();
  });
  
  /* --- Masonry --- */
  
  $("#folio, .section-folio, #blog, .section-blog").on("click",function(){

  var $container = $(".masonry");
  $container.imagesLoaded(function () {
    $container.isotope({
      itemSelector: ".item",
    });
  });
  $("#folio-filters a, #blog-filters a").click(function () {
    var selector = $(this).attr("data-filter");
    $container.isotope({
      filter: selector
    });
    return false;
  });

  });
  
  /* --- Item Description --- */
  $(".item").click(function () {
    $(this).toggleClass("open");
  })
  
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

  $("#contact, .section-contact").on("click",function(){

  /* --- Google Map --- */
  var mapOptions = {
    center: new google.maps.LatLng(40.751126,-73.993399),
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("map-canvas"),mapOptions);
  
  var image = "img/marker.png";
  var marker = new google.maps.Marker({
    position: mapOptions.center,
    map: map,
    icon: image
  });

  });

  /* --- Swap color --- */
  $('.change-white, .change-black, .change-multicolor').click(function () {
    $('#swap-color').attr('href','css/color/'+$(this).data('color')+'.css');
    return false;
  });

    /* --- Form Pendaftaran Calon Mahasiswa Baru --*/
    $( "#form_cama" ).submit(function( event ) {
        // Stop form from submitting normally
        event.preventDefault();

        // Get some values from elements on the page:
        var $form = $( this ), url = $form.attr( "action" );

        // Send the data using post
        $.post( url, $(this).serialize(), function( data ){
        if(data.success == true){
                $( "#form_cama").find('input, textarea, button').prop('disabled', true);

                $("#alert-daftar").append('<div class="alert alert-block alert-success fade in">' +
                    '<button type="button" class="close alert-close" data-dismiss="alert" >&times;</button>' +
                    '<strong>Pendaftaran Calon Mahasiswa Berhasil</strong>' +
                    '<p id="msg-status">'+data.msg+'</p>' +
                    '<p><a href="#" id="btn-tutup" class="btn btn-danger alert-close">Tutup</a></p>' +
                    '</div>');

                $("#btn-tutup, .alert-close").click(function(){
                    $(".alert").alert('close');
                });

                $('.alert').bind('closed', function () {
                    // do something…
                    $("#form_cama").trigger('reset');
                    $("#form_cama").find('input, textarea, button').prop('disabled', false);
                    $(".section-close").trigger("click");
                });

        }else{
            $("#alert-daftar").append('<div class="alert alert-block alert-danger fade in">' +
                '<button type="button" class="close alert-close" data-dismiss="alert" >&times;</button>' +
                '<strong>Pendaftaran Calon Mahasiswa Gagal</strong>' +
                '<p id="msg-status">'+data.msg+'</p>' +
                '<p id="msg-status">Silakan coba kembali</p>' +
                '<p><a href="#" id="btn-tutup" class="btn btn-danger alert-close">Tutup</a></p>' +
                '</div>');

            $("#btn-tutup, .alert-close").click(function(){
                $(".alert").alert('close');
            });
        }

        }, "json");
    });


    



});

/* --- Flex Slider --- */
$(window).load(function() {
    $(".flexslider").flexslider({
        animation: "slide",
        animationLoop: true,
        itemWidth: 300,
        itemMargin: 0,
        prevText: "<i class='icon-angle-left'></i>",
        nextText: "<i class='icon-angle-right'></i>",
    });
});