<?php
/**
 * Transport Meta Boxes Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MW_Transport_Meta_Boxes {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_mw_transport', array($this, 'save_meta_boxes'), 10, 2);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Harga Sewa
        add_meta_box(
            'mw_transport_price',
            __('Harga Sewa', 'mw-travel'),
            array($this, 'render_price_meta_box'),
            'mw_transport',
            'side',
            'high'
        );
        
        // Spesifikasi (Repeatable)
        add_meta_box(
            'mw_transport_specifications',
            __('Spesifikasi & Detail', 'mw-travel'),
            array($this, 'render_specifications_meta_box'),
            'mw_transport',
            'normal',
            'high'
        );
        
        // Yang Termasuk
        add_meta_box(
            'mw_transport_include',
            __('Yang Termasuk (Include)', 'mw-travel'),
            array($this, 'render_include_meta_box'),
            'mw_transport',
            'normal',
            'default'
        );
    }
    
    /**
     * Render Price Meta Box
     */
    public function render_price_meta_box($post) {
        wp_nonce_field('mw_transport_price_nonce', 'mw_transport_price_nonce');
        
        $price = get_post_meta($post->ID, '_mw_transport_price', true);
        ?>
        <div class="mw-transport-price-wrapper">
            <p>
                <label for="mw_transport_price"><strong><?php _e('Harga Sewa:', 'mw-travel'); ?></strong></label><br>
                <input type="text" id="mw_transport_price" name="mw_transport_price" value="<?php echo esc_attr($price); ?>" class="widefat" placeholder="Contoh: Rp 500.000/hari">
                <span class="description"><?php _e('Masukkan harga sewa utama', 'mw-travel'); ?></span>
            </p>
        </div>
        <?php
    }
    
    /**
     * Render Specifications Meta Box (Repeatable Accordion)
     */
    public function render_specifications_meta_box($post) {
        wp_nonce_field('mw_transport_specifications_nonce', 'mw_transport_specifications_nonce');
        
        $specifications = get_post_meta($post->ID, '_mw_transport_specifications', true);
        $specifications = !empty($specifications) ? $specifications : array();
        ?>
        <div class="mw-transport-specifications-wrapper">
            <p class="description"><?php _e('Tambahkan spesifikasi dan detail kendaraan. User bisa menambah field sendiri.', 'mw-travel'); ?></p>
            <div id="mw-specifications-list">
                <?php
                if (!empty($specifications)) {
                    foreach ($specifications as $index => $spec) {
                        $this->render_specification_item($index, $spec);
                    }
                }
                ?>
            </div>
            <button type="button" class="button button-primary" id="mw-add-specification">
                <span class="dashicons dashicons-plus-alt"></span> <?php _e('Tambah Spesifikasi', 'mw-travel'); ?>
            </button>
        </div>
        
        <script type="text/html" id="mw-specification-template">
            <?php $this->render_specification_item('{{INDEX}}'); ?>
        </script>
        <?php
    }
    
    /**
     * Render single specification item
     */
    private function render_specification_item($index, $data = array()) {
        $title = isset($data['title']) ? $data['title'] : '';
        $value = isset($data['value']) ? $data['value'] : '';
        ?>
        <div class="mw-specification-item" data-index="<?php echo esc_attr($index); ?>">
            <div class="mw-specification-header">
                <span class="mw-specification-handle dashicons dashicons-menu"></span>
                <h4><?php _e('Spesifikasi', 'mw-travel'); ?> <span class="spec-title-display"><?php echo esc_html($title); ?></span></h4>
                <button type="button" class="button mw-remove-specification">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
            <div class="mw-specification-content">
                <p>
                    <label><strong><?php _e('Nama Field:', 'mw-travel'); ?></strong></label>
                    <input type="text" name="mw_transport_specifications[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($title); ?>" class="widefat spec-title-input" placeholder="Contoh: Kapasitas, Tahun, BBM, dll">
                </p>
                <p>
                    <label><strong><?php _e('Value/Deskripsi:', 'mw-travel'); ?></strong></label>
                    <textarea name="mw_transport_specifications[<?php echo esc_attr($index); ?>][value]" class="widefat" rows="3" placeholder="Contoh: 7 penumpang, AC, Audio System"><?php echo esc_textarea($value); ?></textarea>
                </p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Include Meta Box
     */
    public function render_include_meta_box($post) {
        wp_nonce_field('mw_transport_include_nonce', 'mw_transport_include_nonce');
        
        $include = get_post_meta($post->ID, '_mw_transport_include', true);
        $include_items = !empty($include) ? explode("\n", $include) : array();
        ?>
        <div class="mw-transport-list-wrapper">
            <div id="mw-transport-include-list">
                <?php
                if (!empty($include_items)) {
                    foreach ($include_items as $index => $item) {
                        if (trim($item)) {
                            ?>
                            <div class="mw-list-item">
                                <input type="text" name="mw_transport_include[]" value="<?php echo esc_attr($item); ?>" class="widefat">
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
            <button type="button" class="button button-secondary mw-add-transport-include">
                <span class="dashicons dashicons-plus"></span> <?php _e('Tambah Item', 'mw-travel'); ?>
            </button>
            <p class="description"><?php _e('Contoh: Driver, BBM, Parkir, Tol, dll', 'mw-travel'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Save meta boxes data
     */
    public function save_meta_boxes($post_id, $post) {
        // Check nonces
        if (!isset($_POST['mw_transport_price_nonce']) || !wp_verify_nonce($_POST['mw_transport_price_nonce'], 'mw_transport_price_nonce')) {
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
        
        // Save price
        if (isset($_POST['mw_transport_price'])) {
            update_post_meta($post_id, '_mw_transport_price', sanitize_text_field($_POST['mw_transport_price']));
        }
        
        // Save specifications
        if (isset($_POST['mw_transport_specifications']) && is_array($_POST['mw_transport_specifications'])) {
            $specifications = array();
            foreach ($_POST['mw_transport_specifications'] as $spec) {
                $specifications[] = array(
                    'title' => sanitize_text_field($spec['title']),
                    'value' => sanitize_textarea_field($spec['value']),
                );
            }
            update_post_meta($post_id, '_mw_transport_specifications', $specifications);
        } else {
            delete_post_meta($post_id, '_mw_transport_specifications');
        }
        
        // Save include
        if (isset($_POST['mw_transport_include']) && is_array($_POST['mw_transport_include'])) {
            $include = array_filter(array_map('sanitize_text_field', $_POST['mw_transport_include']));
            update_post_meta($post_id, '_mw_transport_include', implode("\n", $include));
        } else {
            delete_post_meta($post_id, '_mw_transport_include');
        }
    }
}
