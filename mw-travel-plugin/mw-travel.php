<?php
/**
 * Plugin Name: MW Travel
 * Plugin URI: https://mataramweb.com
 * Description: Plugin untuk Custom Post Type MW Travel dengan itinerary, gallery, include/exclude untuk travel agents. Compatible dengan Astra Theme.
 * Version: 1.0.0
 * Author: Mataram Web
 * Author URI: https://mataramweb.com
 * Text Domain: mw-travel
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('MW_TRAVEL_VERSION', '1.0.0');
define('MW_TRAVEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MW_TRAVEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MW_TRAVEL_PLUGIN_FILE', __FILE__);

/**
 * Main MW Travel Plugin Class
 */
class MW_Travel_Plugin {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance of this class
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        require_once MW_TRAVEL_PLUGIN_DIR . 'includes/class-custom-post-type.php';
        require_once MW_TRAVEL_PLUGIN_DIR . 'includes/class-meta-boxes.php';
        require_once MW_TRAVEL_PLUGIN_DIR . 'includes/class-taxonomy.php';
        require_once MW_TRAVEL_PLUGIN_DIR . 'includes/class-reviews.php';
        require_once MW_TRAVEL_PLUGIN_DIR . 'includes/template-functions.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Template loading
        add_filter('template_include', array($this, 'load_custom_templates'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize custom post type
        new MW_Travel_Custom_Post_Type();
        
        // Initialize meta boxes
        new MW_Travel_Meta_Boxes();
        
        // Initialize taxonomy
        new MW_Travel_Taxonomy();
        
        // Load text domain
        load_plugin_textdomain('mw-travel', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        if (is_singular('mw_travel') || is_post_type_archive('mw_travel')) {
            wp_enqueue_style(
                'mw-travel-frontend',
                MW_TRAVEL_PLUGIN_URL . 'assets/css/frontend.css',
                array(),
                MW_TRAVEL_VERSION
            );
            
            wp_enqueue_script(
                'mw-travel-frontend',
                MW_TRAVEL_PLUGIN_URL . 'assets/js/frontend.js',
                array('jquery'),
                MW_TRAVEL_VERSION,
                true
            );
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        global $post_type;
        
        if (('post.php' === $hook || 'post-new.php' === $hook) && 'mw_travel' === $post_type) {
            wp_enqueue_media();
            
            wp_enqueue_style(
                'mw-travel-admin',
                MW_TRAVEL_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                MW_TRAVEL_VERSION
            );
            
            wp_enqueue_script(
                'mw-travel-admin',
                MW_TRAVEL_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'jquery-ui-sortable'),
                MW_TRAVEL_VERSION,
                true
            );
            
            wp_localize_script('mw-travel-admin', 'mwTravelAdmin', array(
                'confirmDelete' => __('Apakah Anda yakin ingin menghapus item ini?', 'mw-travel'),
                'nonce' => wp_create_nonce('mw_travel_nonce')
            ));
        }
    }
    
    /**
     * Load custom templates
     */
    public function load_custom_templates($template) {
        if (is_singular('mw_travel')) {
            $custom_template = MW_TRAVEL_PLUGIN_DIR . 'templates/single-mw_travel.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        
        if (is_post_type_archive('mw_travel')) {
            $custom_template = MW_TRAVEL_PLUGIN_DIR . 'templates/archive-mw_travel.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        
        return $template;
    }
}

/**
 * Initialize the plugin
 */
function mw_travel_plugin_init() {
    return MW_Travel_Plugin::get_instance();
}

// Start the plugin
mw_travel_plugin_init();

/**
 * Activation hook
 */
register_activation_hook(__FILE__, 'mw_travel_activate');
function mw_travel_activate() {
    // Register post type and taxonomy
    require_once MW_TRAVEL_PLUGIN_DIR . 'includes/class-custom-post-type.php';
    require_once MW_TRAVEL_PLUGIN_DIR . 'includes/class-taxonomy.php';
    
    $cpt = new MW_Travel_Custom_Post_Type();
    $tax = new MW_Travel_Taxonomy();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Deactivation hook
 */
register_deactivation_hook(__FILE__, 'mw_travel_deactivate');
function mw_travel_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
