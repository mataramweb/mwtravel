/**
 * MW Travel Admin JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Debug log
        console.log('MW Travel Admin JS loaded');
        console.log('wp.media available:', typeof wp !== 'undefined' && typeof wp.media !== 'undefined');
        
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
            
            console.log('Gallery button clicked');
            
            // Check if wp.media is available
            if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
                alert('WordPress Media Library tidak tersedia. Pastikan Anda berada di halaman edit post.');
                console.error('wp.media is not defined');
                return;
            }
            
            // If the media frame already exists, reopen it.
            if (galleryFrame) {
                galleryFrame.open();
                return;
            }
            
            // Create the media frame.
            try {
                galleryFrame = wp.media({
                    title: 'Pilih atau Upload Gambar',
                    button: {
                        text: 'Tambahkan ke Gallery'
                    },
                    multiple: true,
                    library: {
                        type: 'image'
                    }
                });
                
                console.log('Gallery frame created');
                
                // When images are selected, run a callback.
                galleryFrame.on('select', function() {
                    console.log('Images selected');
                    
                    const selection = galleryFrame.state().get('selection');
                    const currentIds = $('#mw_travel_gallery').val();
                    const idsArray = currentIds ? currentIds.split(',').filter(id => id !== '') : [];
                    
                    console.log('Current IDs:', idsArray);
                    
                    selection.map(function(attachment) {
                        attachment = attachment.toJSON();
                        
                        console.log('Processing attachment:', attachment.id);
                        
                        // Add ID to array if not already present
                        if (idsArray.indexOf(attachment.id.toString()) === -1) {
                            idsArray.push(attachment.id);
                            
                            // Get thumbnail URL
                            let thumbUrl = attachment.url;
                            if (attachment.sizes && attachment.sizes.thumbnail) {
                                thumbUrl = attachment.sizes.thumbnail.url;
                            } else if (attachment.sizes && attachment.sizes.medium) {
                                thumbUrl = attachment.sizes.medium.url;
                            }
                            
                            // Add thumbnail to container
                            const imgHtml = '<div class="mw-gallery-item" data-id="' + attachment.id + '">' +
                                '<img src="' + thumbUrl + '" alt="">' +
                                '<button type="button" class="mw-remove-gallery-item">&times;</button>' +
                                '</div>';
                            
                            $('#mw-gallery-container').append(imgHtml);
                            console.log('Added image to gallery:', attachment.id);
                        }
                    });
                    
                    // Update hidden field
                    $('#mw_travel_gallery').val(idsArray.join(','));
                    console.log('Updated gallery IDs:', idsArray.join(','));
                });
                
                // Finally, open the modal.
                galleryFrame.open();
                console.log('Gallery frame opened');
                
            } catch (error) {
                console.error('Error creating gallery frame:', error);
                alert('Terjadi error: ' + error.message);
            }
        });
        
        // Remove gallery item
        $(document).on('click', '.mw-remove-gallery-item', function(e) {
            e.preventDefault();
            
            console.log('Remove gallery item clicked');
            
            const $item = $(this).closest('.mw-gallery-item');
            const imageId = $item.data('id');
            
            console.log('Removing image ID:', imageId);
            
            // Remove from display
            $item.fadeOut(300, function() {
                $(this).remove();
                
                // Update hidden field
                const currentIds = $('#mw_travel_gallery').val();
                const idsArray = currentIds ? currentIds.split(',') : [];
                const newIds = idsArray.filter(function(id) {
                    return id != '' && id != imageId;
                });
                
                $('#mw_travel_gallery').val(newIds.join(','));
                console.log('Updated gallery IDs after removal:', newIds.join(','));
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
