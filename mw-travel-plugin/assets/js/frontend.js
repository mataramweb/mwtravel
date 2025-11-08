/**
 * MW Travel Frontend JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Simple lightbox for gallery (optional - can be replaced with a proper lightbox plugin)
        $('.gallery-item a').on('click', function(e) {
            // If you want to use a lightbox plugin, integrate it here
            // For now, images open in a new tab by default
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
        
    });
    
})(jQuery);
