<?php
/**
 * Meta Boxes Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MW_Travel_Meta_Boxes {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_mw_travel', array($this, 'save_meta_boxes'), 10, 2);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Travel Details
        add_meta_box(
            'mw_travel_details',
            __('Detail Travel', 'mw-travel'),
            array($this, 'render_details_meta_box'),
            'mw_travel',
            'normal',
            'high'
        );
        
        // Itinerary
        add_meta_box(
            'mw_travel_itinerary',
            __('Itinerary', 'mw-travel'),
            array($this, 'render_itinerary_meta_box'),
            'mw_travel',
            'normal',
            'high'
        );
        
        // Gallery
        add_meta_box(
            'mw_travel_gallery',
            __('Gallery', 'mw-travel'),
            array($this, 'render_gallery_meta_box'),
            'mw_travel',
            'normal',
            'default'
        );
        
        // Include
        add_meta_box(
            'mw_travel_include',
            __('Yang Termasuk (Include)', 'mw-travel'),
            array($this, 'render_include_meta_box'),
            'mw_travel',
            'side',
            'default'
        );
        
        // Exclude
        add_meta_box(
            'mw_travel_exclude',
            __('Yang Tidak Termasuk (Exclude)', 'mw-travel'),
            array($this, 'render_exclude_meta_box'),
            'mw_travel',
            'side',
            'default'
        );
    }
    
    /**
     * Render Travel Details Meta Box
     */
    public function render_details_meta_box($post) {
        wp_nonce_field('mw_travel_details_nonce', 'mw_travel_details_nonce');
        
        $price = get_post_meta($post->ID, '_mw_travel_price', true);
        $duration = get_post_meta($post->ID, '_mw_travel_duration', true);
        $location = get_post_meta($post->ID, '_mw_travel_location', true);
        ?>
        <div class="mw-travel-details-wrapper">
            <p>
                <label for="mw_travel_price"><strong><?php _e('Harga:', 'mw-travel'); ?></strong></label><br>
                <input type="text" id="mw_travel_price" name="mw_travel_price" value="<?php echo esc_attr($price); ?>" class="widefat" placeholder="Contoh: Rp 5.000.000">
                <span class="description"><?php _e('Masukkan harga paket travel', 'mw-travel'); ?></span>
            </p>
            
            <p>
                <label for="mw_travel_duration"><strong><?php _e('Durasi:', 'mw-travel'); ?></strong></label><br>
                <input type="text" id="mw_travel_duration" name="mw_travel_duration" value="<?php echo esc_attr($duration); ?>" class="widefat" placeholder="Contoh: 3 Hari 2 Malam">
                <span class="description"><?php _e('Masukkan durasi perjalanan', 'mw-travel'); ?></span>
            </p>
            
            <p>
                <label for="mw_travel_location"><strong><?php _e('Lokasi:', 'mw-travel'); ?></strong></label><br>
                <input type="text" id="mw_travel_location" name="mw_travel_location" value="<?php echo esc_attr($location); ?>" class="widefat" placeholder="Contoh: Bali, Indonesia">
                <span class="description"><?php _e('Masukkan lokasi tujuan', 'mw-travel'); ?></span>
            </p>
        </div>
        <?php
    }
    
    /**
     * Render Itinerary Meta Box
     */
    public function render_itinerary_meta_box($post) {
        wp_nonce_field('mw_travel_itinerary_nonce', 'mw_travel_itinerary_nonce');
        
        $itinerary = get_post_meta($post->ID, '_mw_travel_itinerary', true);
        $itinerary = !empty($itinerary) ? $itinerary : array();
        ?>
        <div class="mw-travel-itinerary-wrapper">
            <div id="mw-itinerary-list">
                <?php
                if (!empty($itinerary)) {
                    foreach ($itinerary as $index => $day) {
                        $this->render_itinerary_item($index, $day);
                    }
                }
                ?>
            </div>
            <button type="button" class="button button-primary" id="mw-add-itinerary">
                <span class="dashicons dashicons-plus-alt"></span> <?php _e('Tambah Hari', 'mw-travel'); ?>
            </button>
        </div>
        
        <script type="text/html" id="mw-itinerary-template">
            <?php $this->render_itinerary_item('{{INDEX}}'); ?>
        </script>
        <?php
    }
    
    /**
     * Render single itinerary item
     */
    private function render_itinerary_item($index, $data = array()) {
        $day_number = isset($data['day_number']) ? $data['day_number'] : '';
        $title = isset($data['title']) ? $data['title'] : '';
        $description = isset($data['description']) ? $data['description'] : '';
        $activities = isset($data['activities']) ? $data['activities'] : '';
        ?>
        <div class="mw-itinerary-item" data-index="<?php echo esc_attr($index); ?>">
            <div class="mw-itinerary-header">
                <span class="mw-itinerary-handle dashicons dashicons-menu"></span>
                <h4><?php _e('Hari', 'mw-travel'); ?> <span class="day-number-display"><?php echo esc_html($day_number); ?></span></h4>
                <button type="button" class="button mw-remove-itinerary">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
            <div class="mw-itinerary-content">
                <p>
                    <label><strong><?php _e('Nomor Hari:', 'mw-travel'); ?></strong></label>
                    <input type="number" name="mw_travel_itinerary[<?php echo esc_attr($index); ?>][day_number]" value="<?php echo esc_attr($day_number); ?>" class="small-text day-number-input" min="1">
                </p>
                <p>
                    <label><strong><?php _e('Judul:', 'mw-travel'); ?></strong></label>
                    <input type="text" name="mw_travel_itinerary[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($title); ?>" class="widefat" placeholder="Contoh: Tiba di Bali">
                </p>
                <p>
                    <label><strong><?php _e('Deskripsi:', 'mw-travel'); ?></strong></label>
                    <textarea name="mw_travel_itinerary[<?php echo esc_attr($index); ?>][description]" class="widefat" rows="3" placeholder="Deskripsi singkat untuk hari ini"><?php echo esc_textarea($description); ?></textarea>
                </p>
                <p>
                    <label><strong><?php _e('Aktivitas:', 'mw-travel'); ?></strong></label>
                    <textarea name="mw_travel_itinerary[<?php echo esc_attr($index); ?>][activities]" class="widefat" rows="4" placeholder="Aktivitas yang dilakukan (pisahkan dengan enter)"><?php echo esc_textarea($activities); ?></textarea>
                </p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Gallery Meta Box
     */
    public function render_gallery_meta_box($post) {
        wp_nonce_field('mw_travel_gallery_nonce', 'mw_travel_gallery_nonce');
        
        $gallery = get_post_meta($post->ID, '_mw_travel_gallery', true);
        $gallery_ids = !empty($gallery) ? explode(',', $gallery) : array();
        ?>
        <div class="mw-travel-gallery-wrapper">
            <div id="mw-gallery-container">
                <?php
                if (!empty($gallery_ids)) {
                    foreach ($gallery_ids as $image_id) {
                        if ($image_id) {
                            $image = wp_get_attachment_image_src($image_id, 'thumbnail');
                            if ($image) {
                                ?>
                                <div class="mw-gallery-item" data-id="<?php echo esc_attr($image_id); ?>">
                                    <img src="<?php echo esc_url($image[0]); ?>" alt="">
                                    <button type="button" class="mw-remove-gallery-item">&times;</button>
                                </div>
                                <?php
                            }
                        }
                    }
                }
                ?>
            </div>
            <input type="hidden" id="mw_travel_gallery" name="mw_travel_gallery" value="<?php echo esc_attr($gallery); ?>">
            <button type="button" class="button button-primary" id="mw-add-gallery">
                <span class="dashicons dashicons-format-gallery"></span> <?php _e('Tambah Gambar', 'mw-travel'); ?>
            </button>
            <p class="description"><?php _e('Upload atau pilih gambar dari media library', 'mw-travel'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Render Include Meta Box
     */
    public function render_include_meta_box($post) {
        wp_nonce_field('mw_travel_include_nonce', 'mw_travel_include_nonce');
        
        $include = get_post_meta($post->ID, '_mw_travel_include', true);
        $include_items = !empty($include) ? explode('\n', $include) : array();
        ?>
        <div class="mw-travel-list-wrapper">
            <div id="mw-include-list">
                <?php
                if (!empty($include_items)) {
                    foreach ($include_items as $index => $item) {
                        if (trim($item)) {
                            ?>
                            <div class="mw-list-item">
                                <input type="text" name="mw_travel_include[]" value="<?php echo esc_attr($item); ?>" class="widefat">
                                <button type="button" class="button mw-remove-list-item">
                                    <span class="dashicons dashicons-no"></span>
                                </button>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <button type="button" class="button button-secondary mw-add-include">
                <span class="dashicons dashicons-plus"></span> <?php _e('Tambah Item', 'mw-travel'); ?>
            </button>
            <p class="description"><?php _e('Contoh: Hotel bintang 4, Makan 3x sehari, Tiket masuk, dll', 'mw-travel'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Render Exclude Meta Box
     */
    public function render_exclude_meta_box($post) {
        wp_nonce_field('mw_travel_exclude_nonce', 'mw_travel_exclude_nonce');
        
        $exclude = get_post_meta($post->ID, '_mw_travel_exclude', true);
        $exclude_items = !empty($exclude) ? explode('\n', $exclude) : array();
        ?>
        <div class="mw-travel-list-wrapper">
            <div id="mw-exclude-list">
                <?php
                if (!empty($exclude_items)) {
                    foreach ($exclude_items as $index => $item) {
                        if (trim($item)) {
                            ?>
                            <div class="mw-list-item">
                                <input type="text" name="mw_travel_exclude[]" value="<?php echo esc_attr($item); ?>" class="widefat">
                                <button type="button" class="button mw-remove-list-item">
                                    <span class="dashicons dashicons-no"></span>
                                </button>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <button type="button" class="button button-secondary mw-add-exclude">
                <span class="dashicons dashicons-plus"></span> <?php _e('Tambah Item', 'mw-travel'); ?>
            </button>
            <p class="description"><?php _e('Contoh: Tiket pesawat, Pengeluaran pribadi, Asuransi, dll', 'mw-travel'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Save meta boxes data
     */
    public function save_meta_boxes($post_id, $post) {
        // Check nonces
        if (!isset($_POST['mw_travel_details_nonce']) || !wp_verify_nonce($_POST['mw_travel_details_nonce'], 'mw_travel_details_nonce')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save travel details
        if (isset($_POST['mw_travel_price'])) {
            update_post_meta($post_id, '_mw_travel_price', sanitize_text_field($_POST['mw_travel_price']));
        }
        
        if (isset($_POST['mw_travel_duration'])) {
            update_post_meta($post_id, '_mw_travel_duration', sanitize_text_field($_POST['mw_travel_duration']));
        }
        
        if (isset($_POST['mw_travel_location'])) {
            update_post_meta($post_id, '_mw_travel_location', sanitize_text_field($_POST['mw_travel_location']));
        }
        
        // Save itinerary
        if (isset($_POST['mw_travel_itinerary']) && is_array($_POST['mw_travel_itinerary'])) {
            $itinerary = array();
            foreach ($_POST['mw_travel_itinerary'] as $day) {
                $itinerary[] = array(
                    'day_number' => sanitize_text_field($day['day_number']),
                    'title' => sanitize_text_field($day['title']),
                    'description' => sanitize_textarea_field($day['description']),
                    'activities' => sanitize_textarea_field($day['activities']),
                );
            }
            update_post_meta($post_id, '_mw_travel_itinerary', $itinerary);
        } else {
            delete_post_meta($post_id, '_mw_travel_itinerary');
        }
        
        // Save gallery
        if (isset($_POST['mw_travel_gallery'])) {
            update_post_meta($post_id, '_mw_travel_gallery', sanitize_text_field($_POST['mw_travel_gallery']));
        }
        
        // Save include
        if (isset($_POST['mw_travel_include']) && is_array($_POST['mw_travel_include'])) {
            $include = array_filter(array_map('sanitize_text_field', $_POST['mw_travel_include']));
            update_post_meta($post_id, '_mw_travel_include', implode('\n', $include));
        } else {
            delete_post_meta($post_id, '_mw_travel_include');
        }
        
        // Save exclude
        if (isset($_POST['mw_travel_exclude']) && is_array($_POST['mw_travel_exclude'])) {
            $exclude = array_filter(array_map('sanitize_text_field', $_POST['mw_travel_exclude']));
            update_post_meta($post_id, '_mw_travel_exclude', implode('\n', $exclude));
        } else {
            delete_post_meta($post_id, '_mw_travel_exclude');
        }
    }
}
