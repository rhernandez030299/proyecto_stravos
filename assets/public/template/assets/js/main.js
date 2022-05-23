"use strict";
jQuery(document).ready(function ($) {

//for Preloader

    $(window).load(function () {
        $("#loading").fadeOut(500);
    });

    var accordion = function() {
        $('.ubea-accordion-heading').on('click', function(event){
            console.log("asd")
            var $this = $(this);

            $this.closest('.ubea-accordion').find('.ubea-accordion-content').slideToggle(400, 'easeInOutExpo');
            if ($this.closest('.ubea-accordion').hasClass('active')) {
                $this.closest('.ubea-accordion').removeClass('active');
            } else {
                $this.closest('.ubea-accordion').addClass('active');
            }
            event.preventDefault();
        });
    };

    $(function(){
        accordion();
    });


    /*---------------------------------------------*
     * Mobile menu
     ---------------------------------------------*/
    $('#navbar-menu').find('a[href*=#]:not([href=#])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: (target.offset().top - 80)
                }, 1000);
                if ($('.navbar-toggle').css('display') != 'none') {
                    $(this).parents('.container').find(".navbar-toggle").trigger("click");
                }
                return false;
            }
        }
    });



    /*---------------------------------------------*
     * WOW
     ---------------------------------------------*/

    var wow = new WOW({
        mobile: false // trigger animations on mobile devices (default is true)
    });
    wow.init();

// magnificPopup

    $('.popup-img').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });

    $('.video-link').magnificPopup({
        type: 'iframe'
    });



// slick slider active Home Page Tow
    $(".hello_slid").slick({
        dots: true,
        infinite: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: "<i class='icon icon-chevron-left nextprevleft'></i>",
        nextArrow: "<i class='icon icon-chevron-right nextprevright'></i>",
        autoplay: true,
        autoplaySpeed: 2000
    });
    
    
    
    $(".business_items").slick({
        dots: true,
        infinite: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: "<i class='icon icon-chevron-left nextprevleft'></i>",
        nextArrow: "<i class='icon icon-chevron-right nextprevright'></i>",
        autoplay: true,
        autoplaySpeed: 2000
    });




//---------------------------------------------
// Scroll Up 
//---------------------------------------------

    $('.scrollup').click(function () {
        $("html, body").animate({scrollTop: 0}, 1000);
        return false;
    });


    //End

});



