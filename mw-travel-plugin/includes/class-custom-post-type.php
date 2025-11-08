<?php
/**
 * Custom Post Type Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MW_Travel_Custom_Post_Type {
    
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
            'name'                  => _x('MW Travel', 'Post type general name', 'mw-travel'),
            'singular_name'         => _x('Travel', 'Post type singular name', 'mw-travel'),
            'menu_name'             => _x('MW Travel', 'Admin Menu text', 'mw-travel'),
            'name_admin_bar'        => _x('Travel', 'Add New on Toolbar', 'mw-travel'),
            'add_new'               => __('Tambah Baru', 'mw-travel'),
            'add_new_item'          => __('Tambah Travel Baru', 'mw-travel'),
            'new_item'              => __('Travel Baru', 'mw-travel'),
            'edit_item'             => __('Edit Travel', 'mw-travel'),
            'view_item'             => __('Lihat Travel', 'mw-travel'),
            'all_items'             => __('Semua Travel', 'mw-travel'),
            'search_items'          => __('Cari Travel', 'mw-travel'),
            'parent_item_colon'     => __('Parent Travel:', 'mw-travel'),
            'not_found'             => __('Travel tidak ditemukan.', 'mw-travel'),
            'not_found_in_trash'    => __('Travel tidak ditemukan di Trash.', 'mw-travel'),
            'featured_image'        => _x('Featured Image Travel', 'Overrides the "Featured Image" phrase', 'mw-travel'),
            'set_featured_image'    => _x('Set featured image', 'Overrides the "Set featured image" phrase', 'mw-travel'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase', 'mw-travel'),
            'use_featured_image'    => _x('Use as featured image', 'Overrides the "Use as featured image" phrase', 'mw-travel'),
            'archives'              => _x('Travel archives', 'The post type archive label', 'mw-travel'),
            'insert_into_item'      => _x('Insert into travel', 'Overrides the "Insert into post" phrase', 'mw-travel'),
            'uploaded_to_this_item' => _x('Uploaded to this travel', 'Overrides the "Uploaded to this post" phrase', 'mw-travel'),
            'filter_items_list'     => _x('Filter travel list', 'Screen reader text for the filter links', 'mw-travel'),
            'items_list_navigation' => _x('Travel list navigation', 'Screen reader text for the pagination', 'mw-travel'),
            'items_list'            => _x('Travel list', 'Screen reader text for the items list', 'mw-travel'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'travel'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-palmtree',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
            'show_in_rest'       => true,
        );
        
        register_post_type('mw_travel', $args);
    }
    
    /**
     * Change title placeholder
     */
    public function change_title_placeholder($title) {
        $screen = get_current_screen();
        
        if ('mw_travel' === $screen->post_type) {
            $title = __('Masukkan nama paket travel', 'mw-travel');
        }
        
        return $title;
    }
}
