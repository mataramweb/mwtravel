/**
 * MW Travel Frontend JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Initialize Slick Slider for Gallery Carousel
        if ($('.gallery-carousel').length) {
            $('.gallery-carousel').slick({
                dots: true,
                infinite: true,
                speed: 500,
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false
                        }
                    }
                ]
            });
        }
        
        // Itinerary Accordion
        $('.accordion-header').on('click', function() {
            const $item = $(this).closest('.accordion-item');
            const $content = $item.find('.accordion-content');
            const isActive = $item.hasClass('active');
            
            // Close all accordion items
            $('.accordion-item').removeClass('active');
            $('.accordion-content').slideUp(300);
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                $item.addClass('active');
                $content.slideDown(300);
            }
        });
        
        // Transport Specifications Accordion
        $('.spec-header').on('click', function() {
            const $item = $(this).closest('.spec-item');
            const $content = $item.find('.spec-content');
            const isActive = $item.hasClass('active');
            
            // Close all spec items
            $('.spec-item').removeClass('active');
            $('.spec-content').slideUp(300);
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                $item.addClass('active');
                $content.slideDown(300);
            }
        });
        
        // Star Rating Hover Effect (for display)
        $('.star-rating-input label').on('mouseenter', function() {
            const $this = $(this);
            $this.addClass('hover');
            $this.nextAll('label').addClass('hover');
        }).on('mouseleave', function() {
            $('.star-rating-input label').removeClass('hover');
        });
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
        
        // Add animation on scroll for sections
        function animateOnScroll() {
            $('.mw-travel-itinerary, .mw-travel-include-exclude, .mw-travel-reviews-section').each(function() {
                const elementTop = $(this).offset().top;
                const viewportBottom = $(window).scrollTop() + $(window).height();
                
                if (elementTop < viewportBottom - 100) {
                    $(this).addClass('animated fadeIn');
                }
            });
        }
        
        // Run on load and scroll
        animateOnScroll();
        $(window).on('scroll', animateOnScroll);
        
    });
    
})(jQuery);
