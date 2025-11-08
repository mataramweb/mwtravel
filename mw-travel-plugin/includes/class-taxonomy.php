<?php
/**
 * Taxonomy Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MW_Travel_Taxonomy {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_taxonomy'));
    }
    
    /**
     * Register Custom Taxonomy
     */
    public function register_taxonomy() {
        $labels = array(
            'name'                       => _x('Kategori Travel', 'taxonomy general name', 'mw-travel'),
            'singular_name'              => _x('Kategori', 'taxonomy singular name', 'mw-travel'),
            'search_items'               => __('Cari Kategori', 'mw-travel'),
            'popular_items'              => __('Kategori Populer', 'mw-travel'),
            'all_items'                  => __('Semua Kategori', 'mw-travel'),
            'parent_item'                => __('Parent Kategori', 'mw-travel'),
            'parent_item_colon'          => __('Parent Kategori:', 'mw-travel'),
            'edit_item'                  => __('Edit Kategori', 'mw-travel'),
            'update_item'                => __('Update Kategori', 'mw-travel'),
            'add_new_item'               => __('Tambah Kategori Baru', 'mw-travel'),
            'new_item_name'              => __('Nama Kategori Baru', 'mw-travel'),
            'separate_items_with_commas' => __('Pisahkan kategori dengan koma', 'mw-travel'),
            'add_or_remove_items'        => __('Tambah atau hapus kategori', 'mw-travel'),
            'choose_from_most_used'      => __('Pilih dari kategori yang sering digunakan', 'mw-travel'),
            'not_found'                  => __('Kategori tidak ditemukan.', 'mw-travel'),
            'menu_name'                  => __('Kategori', 'mw-travel'),
        );
        
        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'kategori-travel'),
            'show_in_rest'          => true,
        );
        
        register_taxonomy('mw_travel_category', array('mw_travel'), $args);
        
        // Add default categories
        $this->add_default_categories();
    }
    
    /**
     * Add default categories on first activation
     */
    private function add_default_categories() {
        $default_categories = array(
            'Beach & Island' => 'Paket wisata pantai dan pulau',
            'Mountain & Nature' => 'Paket wisata gunung dan alam',
            'City Tour' => 'Paket wisata kota',
            'Cultural Tour' => 'Paket wisata budaya',
            'Adventure' => 'Paket wisata petualangan',
            'Religious Tour' => 'Paket wisata religi',
            'Honeymoon' => 'Paket wisata bulan madu',
        );
        
        foreach ($default_categories as $name => $description) {
            if (!term_exists($name, 'mw_travel_category')) {
                wp_insert_term(
                    $name,
                    'mw_travel_category',
                    array(
                        'description' => $description,
                        'slug' => sanitize_title($name),
                    )
                );
            }
        }
    }
}
