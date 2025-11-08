/**
 * MW Travel Admin JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Itinerary functionality
        let itineraryIndex = $('#mw-itinerary-list .mw-itinerary-item').length;
        
        // Add new itinerary day
        $('#mw-add-itinerary').on('click', function(e) {
            e.preventDefault();
            
            const template = $('#mw-itinerary-template').html();
            const newItem = template.replace(/{{INDEX}}/g, itineraryIndex);
            
            $('#mw-itinerary-list').append(newItem);
            itineraryIndex++;
        });
        
        // Remove itinerary day
        $(document).on('click', '.mw-remove-itinerary', function(e) {
            e.preventDefault();
            
            if (confirm(mwTravelAdmin.confirmDelete)) {
                $(this).closest('.mw-itinerary-item').fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
        
        // Update day number display
        $(document).on('input', '.day-number-input', function() {
            const value = $(this).val();
            $(this).closest('.mw-itinerary-item').find('.day-number-display').text(value);
        });
        
        // Make itinerary sortable
        if ($.fn.sortable) {
            $('#mw-itinerary-list').sortable({
                handle: '.mw-itinerary-handle',
                placeholder: 'mw-itinerary-placeholder',
                tolerance: 'pointer',
                cursor: 'move',
                opacity: 0.7
            });
        }
        
        // Gallery functionality
        let galleryFrame;
        
        $('#mw-add-gallery').on('click', function(e) {
            e.preventDefault();
            
            // If the media frame already exists, reopen it.
            if (galleryFrame) {
                galleryFrame.open();
                return;
            }
            
            // Create the media frame.
            galleryFrame = wp.media({
                title: 'Pilih atau Upload Gambar',
                button: {
                    text: 'Tambahkan ke Gallery'
                },
                multiple: true
            });
            
            // When images are selected, run a callback.
            galleryFrame.on('select', function() {
                const selection = galleryFrame.state().get('selection');
                const currentIds = $('#mw_travel_gallery').val();
                const idsArray = currentIds ? currentIds.split(',') : [];
                
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    
                    // Add ID to array if not already present
                    if (idsArray.indexOf(attachment.id.toString()) === -1) {
                        idsArray.push(attachment.id);
                        
                        // Add thumbnail to container
                        const imgHtml = '<div class="mw-gallery-item" data-id="' + attachment.id + '">' +
                            '<img src="' + (attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" alt="">' +
                            '<button type="button" class="mw-remove-gallery-item">&times;</button>' +
                            '</div>';
                        
                        $('#mw-gallery-container').append(imgHtml);
                    }
                });
                
                // Update hidden field
                $('#mw_travel_gallery').val(idsArray.join(','));
            });
            
            // Finally, open the modal.
            galleryFrame.open();
        });
        
        // Remove gallery item
        $(document).on('click', '.mw-remove-gallery-item', function(e) {
            e.preventDefault();
            
            const $item = $(this).closest('.mw-gallery-item');
            const imageId = $item.data('id');
            
            // Remove from display
            $item.fadeOut(300, function() {
                $(this).remove();
                
                // Update hidden field
                const currentIds = $('#mw_travel_gallery').val();
                const idsArray = currentIds.split(',');
                const newIds = idsArray.filter(function(id) {
                    return id != imageId;
                });
                
                $('#mw_travel_gallery').val(newIds.join(','));
            });
        });
        
        // Include list functionality
        $('.mw-add-include').on('click', function(e) {
            e.preventDefault();
            
            const itemHtml = '<div class="mw-list-item">' +
                '<input type="text" name="mw_travel_include[]" class="widefat" placeholder="Masukkan item yang termasuk">' +
                '<button type="button" class="button mw-remove-list-item">' +
                '<span class="dashicons dashicons-no"></span>' +
                '</button>' +
                '</div>';
            
            $('#mw-include-list').append(itemHtml);
        });
        
        // Exclude list functionality
        $('.mw-add-exclude').on('click', function(e) {
            e.preventDefault();
            
            const itemHtml = '<div class="mw-list-item">' +
                '<input type="text" name="mw_travel_exclude[]" class="widefat" placeholder="Masukkan item yang tidak termasuk">' +
                '<button type="button" class="button mw-remove-list-item">' +
                '<span class="dashicons dashicons-no"></span>' +
                '</button>' +
                '</div>';
            
            $('#mw-exclude-list').append(itemHtml);
        });
        
        // Remove list item
        $(document).on('click', '.mw-remove-list-item', function(e) {
            e.preventDefault();
            
            $(this).closest('.mw-list-item').fadeOut(300, function() {
                $(this).remove();
            });
        });
        
    });
    
})(jQuery);
