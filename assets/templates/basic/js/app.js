'use strict';

$( document ).ready(function() {
  //preloader
  $(".preloader").delay(300).animate({
    "opacity" : "0"
    }, 300, function() {
    $(".preloader").css("display","none");
  });
});

// mobile menu js
$(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on("click", function() {
  const element = $(this).parent("li");
  if (element.hasClass("open")) {
    element.removeClass("open");
    element.find("li").removeClass("open");
  }
  else {
    element.addClass("open");
    element.siblings("li").removeClass("open");
    element.siblings("li").find("li").removeClass("open");
  }
});

// with short level
$('[data-countdown]').each(function() {
  var $this = $(this), finalDate = $(this).data('countdown');
  $this.countdown(finalDate).on('update.countdown', function(event) {
    var format = '%D days %H hr : %M mn : %S sec';
    $(this).html(event.strftime(format));
  }).on('finish.countdown', function(event) {
    var expireData = $(this).data('title');
    $(this).html(expireData).parent().addClass('disabled');
  });
});

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})


new WOW().init();

$('.sidebar-open-btn').on('click', function(){
  $('.sidebar').addClass('active');
});

$('.sidebar-close-btn').on('click', function(){
  $('.sidebar').removeClass('active');
});

// category-slider js 

$('.category-slider').slick({
  infinite: true,
  slidesToShow: 5,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  prevArrow: '<div class="prev"><i class="las la-long-arrow-alt-left"></i></div>',
  nextArrow: '<div class="next"><i class="las la-long-arrow-alt-right"></i></div>',
  autoplay: true,
  cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
  speed: 1000,
  autoplaySpeed: 1000,
  responsive: [
    {
      breakpoint: 1400,
      settings: {
        slidesToShow: 4,
      }
    },
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 3,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 3,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 3,
      }
    },
    {
      breakpoint: 400,
      settings: {
        slidesToShow: 2,
      }
    }
  ],
  rtl: lang === 'ar' ? true : false // Set rtl to true if lang is 'ar', otherwise set it to false
});



// today-deal-slider js 
$('.today-deal-slider').slick({
  infinite: true,
  slidesToShow: 4,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  prevArrow: '<div class="prev"><i class="las la-angle-left"></i></div>',
  nextArrow: '<div class="next"><i class="las la-angle-right"></i></div>',
  autoplay: true,
  cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
  speed: 1000,
  autoplaySpeed: 1000,
  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 3,
      }
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 1,
      }
    }
  ],
  rtl: lang === 'ar' ? true : false // Set rtl to true if lang is 'ar', otherwise set it to false
});

// store-slider js 
$('.store-slider').slick({
  infinite: true,
  slidesToShow: 5,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  prevArrow: '<div class="prev"><i class="las la-angle-left"></i></div>',
  nextArrow: '<div class="next"><i class="las la-angle-right"></i></div>',
  autoplay: true,
  cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
  speed: 1000,
  autoplaySpeed: 1000,
  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 4,
      }
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 3,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 2,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
      }
    }
  ],
  rtl: lang === 'ar' ? true : false // Set rtl to true if lang is 'ar', otherwise set it to false
});


// testimonial-slider js 
$('.testimonial-slider').slick({
  infinite: true,
  slidesToShow: 2,
  slidesToScroll: 1,
  dots: false,
  arrows: false,
  autoplay: true,
  cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
  speed: 2000,
  autoplaySpeed: 1000,
  responsive: [
    {
      breakpoint: 1400,
      settings: {
        slidesToShow: 2,
      }
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 1,
      }
    }
  ],
  rtl: lang === 'ar' ? true : false // Set rtl to true if lang is 'ar', otherwise set it to false
});

if($(window).width() < 992) {
  $(".promo-wrapper").appendTo("#ad-append");
}