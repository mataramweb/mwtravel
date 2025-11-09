<?php
/**
 * Transport Custom Post Type Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MW_Transport_Post_Type {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_filter('enter_title_here', array($this, 'change_title_placeholder'));
    }
    
    /**
     * Register Custom Post Type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Transport', 'Post type general name', 'mw-travel'),
            'singular_name'         => _x('Transport', 'Post type singular name', 'mw-travel'),
            'menu_name'             => _x('Transport', 'Admin Menu text', 'mw-travel'),
            'name_admin_bar'        => _x('Transport', 'Add New on Toolbar', 'mw-travel'),
            'add_new'               => __('Tambah Baru', 'mw-travel'),
            'add_new_item'          => __('Tambah Transport Baru', 'mw-travel'),
            'new_item'              => __('Transport Baru', 'mw-travel'),
            'edit_item'             => __('Edit Transport', 'mw-travel'),
            'view_item'             => __('Lihat Transport', 'mw-travel'),
            'all_items'             => __('Semua Transport', 'mw-travel'),
            'search_items'          => __('Cari Transport', 'mw-travel'),
            'not_found'             => __('Transport tidak ditemukan.', 'mw-travel'),
            'not_found_in_trash'    => __('Transport tidak ditemukan di Trash.', 'mw-travel'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'transport'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 6,
            'menu_icon'          => 'dashicons-car',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'show_in_rest'       => true,
        );
        
        register_post_type('mw_transport', $args);
    }
    
    /**
     * Change title placeholder
     */
    public function change_title_placeholder($title) {
        $screen = get_current_screen();
        
        if ('mw_transport' === $screen->post_type) {
            $title = __('Masukkan nama kendaraan/transport', 'mw-travel');
        }
        
        return $title;
    }
}
