/*jslint browser: true, sloppy: true, vars: true*/
/*global $, jQuery*/
// JavaScript Functions
var base_url = window.location.protocol + "//" + window.location.host + "/";
var a_kota = '';
var b_kota = '';
var kampus = '';
var pil1 = '';
var pil2 = '';
$(document).ready(function () {
    var video = document.querySelector('video');
    var canvas = document.querySelector('.snap canvas');
    var img = document.querySelector('.snap img');

    var pictureWidth = video.width;
    var pictureHeight = video.height;

    var fxCanvas = null;
    var texture = null;

    function checkRequirements() {
        var deferred = new $.Deferred();

        //Check if getUserMedia is available
        if (!Modernizr.getusermedia) {
            deferred.reject('Your browser doesn\'t support getUserMedia (according to Modernizr).');
        }

        //Check if WebGL is available
        if (Modernizr.webgl) {
            try {
                //setup glfx.js
                fxCanvas = fx.canvas();
            } catch (e) {
                deferred.reject('Sorry, glfx.js failed to initialize. WebGL issues?');
            }
        } else {
            deferred.reject('Your browser doesn\'t support WebGL (according to Modernizr).');
        }

        deferred.resolve();

        return deferred.promise();
    }

    function searchForRearCamera() {
        var deferred = new $.Deferred();

        //MediaStreamTrack.getSources seams to be supported only by Chrome
        if (MediaStreamTrack && MediaStreamTrack.getSources) {
            MediaStreamTrack.getSources(function (sources) {
                var rearCameraIds = sources.filter(function (source) {
                    return (source.kind === 'video' && source.facing === 'environment');
                }).map(function (source) {
                    return source.id;
                });

                if (rearCameraIds.length) {
                    deferred.resolve(rearCameraIds[0]);
                } else {
                    deferred.resolve(null);
                }
            });
        } else {
            deferred.resolve(null);
        }

        return deferred.promise();
    }

    function setupVideo(rearCameraId) {
        var deferred = new $.Deferred();
        var getUserMedia = Modernizr.prefixed('getUserMedia', navigator);
        var videoSettings = {
            video: {
                optional: [
                    {
                        width: {min: pictureWidth}
                    },
                    {
                        height: {min: pictureHeight}
                    }
                ]
            }
        };

        //if rear camera is available - use it
        if (rearCameraId) {
            videoSettings.video.optional.push({
                sourceId: rearCameraId
            });
        }

        getUserMedia(videoSettings, function (stream) {
            //Setup the video stream
            video.src = window.URL.createObjectURL(stream);

            window.stream = stream;

            video.addEventListener("loadedmetadata", function (e) {
                //get video width and height as it might be different than we requested
                pictureWidth = this.videoWidth;
                pictureHeight = this.videoHeight;

                if (!pictureWidth && !pictureHeight) {
                    //firefox fails to deliver info about video size on time (issue #926753), we have to wait
                    var waitingForSize = setInterval(function () {
                        if (video.videoWidth && video.videoHeight) {
                            pictureWidth = video.videoWidth;
                            pictureHeight = video.videoHeight;

                            clearInterval(waitingForSize);
                            deferred.resolve();
                        }
                    }, 100);
                } else {
                    deferred.resolve();
                }
            }, false);
        }, function () {
            deferred.reject('There is no access to your camera, have you denied it?');
        });

        return deferred.promise();
    }

    function step1() {
        checkRequirements()
            .then(searchForRearCamera)
            .then(setupVideo)
            .done(function () {
                //Enable the 'take picture' button
                $('#takePicture').removeAttr('disabled');
                //Hide the 'enable the camera' info
                $('.snap figure').removeClass('not-ready');
            })
            .fail(function (error) {
                showError(error);
            });
    }

    function step2() {
        

        //setup canvas
        canvas.width = pictureWidth;
        canvas.height = pictureHeight;

        var ctx = canvas.getContext('2d');

        //draw picture from video on canvas
        ctx.drawImage(video, 0, 0);

        //modify the picture using glfx.js filters
        /*texture = fxCanvas.texture(canvas);
        fxCanvas.draw(texture)
            .hueSaturation(-1, -1)//grayscale
            .unsharpMask(20, 2)
            .brightnessContrast(0.2, 0.9)
            .update();

        window.texture = texture;
        window.fxCanvas = fxCanvas;*/

        $(img)
            //setup the crop utility
            /*.one('load', function () {
                if (!$(img).data().Jcrop) {
                    $(img).Jcrop({
                        onSelect: function () {
                            //Enable the 'done' button
                            $('#adjust').removeAttr('disabled');
                        }
                    });
                } else {
                    //update crop tool (it creates copies of <img> that we have to update manually)
                    $('.jcrop-holder img').attr('src', fxCanvas.toDataURL());
                }
            })*/
            //show output from glfx.js
            //.attr('src', fxCanvas.toDataURL());
            .attr('src', canvas.toDataURL('image/webp'))
            .removeClass('hidden');
    }

    

    function showError(text) {
        $('.alert').show().find('span').text(text);
    }

    function changeStep(step) {
        if (step === 1) {
            video.play();
        } else {
            $(video).addClass("hidden");
            video.pause();
        }

        /*$('body').attr('class', 'step' + step);
        $('.nav li.active').removeClass('active');
        $('.nav li:eq(' + (step - 1) + ')').removeClass('disabled').addClass('active');*/
    }
  
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

    $("#folio, .section-folio, #blog, .section-blog").on("click", function () {

        var $container = $(".masonry");
        $container.imagesLoaded(function () {
            $container.isotope({
                itemSelector: ".item"
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
    $(".flexslider").flexslider({
        animation: "slide",
        animationLoop: true,
        itemWidth: 300,
        itemMargin: 0,
        prevText: "<i class='icon-angle-left'></i>",
        nextText: "<i class='icon-angle-right'></i>"
    });
});

/*Function On Click Events*/
function ijazah(val) {
    if (val=="Ada") {
        $("#othnijazah").show(25).prop('disabled', false);
        $("#onem").show(25).prop('disabled', false);
        $("#lbl_onem").show(25);
    }else if (val=="Tidak"){
        $("#othnijazah").hide(25).prop('disabled', true);
        $("#onem").hide(25).prop('disabled', true);
        $("#lbl_onem").hide(25);
    }
}

function tdkaktif(val) {
    if (val == "Transisi") {
        $("#piljt").hide(25);
        $("#piljtt").hide(25);
        $("#ket_jalur").show(25);
        $("#otransisi").prop('disabled',false).show(25);
        $("#otprodi").prop('disabled', false).show(25);
    }else if (val=="JT"){
        $("#piljt").show(25);
        $("#piljtt").hide(25);
        $("#ket_jalur").show(25);
        $("#otransisi").prop('disabled',true).hide(25);
        $("#otprodi").prop('disabled', true).hide(25);
    }else if (val=="JTT"){
        $("#piljt").hide(25);
        $("#piljtt").show(25);
        $("#ket_jalur").show(25);
        $("#otransisi").prop('disabled',true).hide(25);
        $("#otprodi").prop('disabled', true).hide(25);
    }else if (val=="JK"){
        $("#piljt").hide(25);
        $("#piljtt").hide(25);
        $("#ket_jalur").hide(25);
        $("#otransisi").prop('disabled',true).hide(25);
        $("#otprodi").prop('disabled', true).hide(25);
    }

}


$(document).ready(function () {
    

});

