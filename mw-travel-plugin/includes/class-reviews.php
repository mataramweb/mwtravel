<?php
/**
 * Reviews & Rating Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MW_Travel_Reviews {
    
    private $table_name;
    
    /**
     * Constructor
     */
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'mw_travel_reviews';
        
        add_action('wp_ajax_mw_travel_submit_review', array($this, 'submit_review'));
        add_action('template_redirect', array($this, 'handle_review_submission'));
    }
    
    /**
     * Create database table
     */
    public function create_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            rating int(1) NOT NULL,
            review_text text,
            status varchar(20) DEFAULT 'approved',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY post_id (post_id),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Handle review submission via form
     */
    public function handle_review_submission() {
        if (!isset($_POST['mw_travel_review_submit']) || !isset($_POST['mw_travel_review_nonce'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['mw_travel_review_nonce'], 'mw_travel_review_action')) {
            wp_die(__('Security check failed', 'mw-travel'));
        }
        
        if (!is_user_logged_in()) {
            wp_die(__('You must be logged in to submit a review', 'mw-travel'));
        }
        
        $post_id = intval($_POST['post_id']);
        $rating = intval($_POST['rating']);
        $review_text = sanitize_textarea_field($_POST['review_text']);
        
        if ($rating < 1 || $rating > 5) {
            wp_die(__('Invalid rating', 'mw-travel'));
        }
        
        $this->add_review($post_id, get_current_user_id(), $rating, $review_text);
        
        wp_redirect(get_permalink($post_id) . '#reviews');
        exit;
    }
    
    /**
     * Add a review
     */
    public function add_review($post_id, $user_id, $rating, $review_text = '') {
        global $wpdb;
        
        // Check if user already reviewed this post
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$this->table_name} WHERE post_id = %d AND user_id = %d",
            $post_id,
            $user_id
        ));
        
        if ($existing) {
            // Update existing review
            $wpdb->update(
                $this->table_name,
                array(
                    'rating' => $rating,
                    'review_text' => $review_text,
                    'created_at' => current_time('mysql')
                ),
                array(
                    'id' => $existing
                ),
                array('%d', '%s', '%s'),
                array('%d')
            );
        } else {
            // Insert new review
            $wpdb->insert(
                $this->table_name,
                array(
                    'post_id' => $post_id,
                    'user_id' => $user_id,
                    'rating' => $rating,
                    'review_text' => $review_text,
                    'status' => 'approved'
                ),
                array('%d', '%d', '%d', '%s', '%s')
            );
        }
        
        // Clear cache
        wp_cache_delete('mw_travel_reviews_' . $post_id);
        wp_cache_delete('mw_travel_rating_' . $post_id);
    }
    
    /**
     * Get reviews for a post
     */
    public function get_reviews($post_id, $status = 'approved') {
        global $wpdb;
        
        $cache_key = 'mw_travel_reviews_' . $post_id;
        $reviews = wp_cache_get($cache_key);
        
        if (false === $reviews) {
            $reviews = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE post_id = %d AND status = %s ORDER BY created_at DESC",
                $post_id,
                $status
            ));
            
            wp_cache_set($cache_key, $reviews, '', 3600);
        }
        
        return $reviews;
    }
    
    /**
     * Get average rating for a post
     */
    public function get_average_rating($post_id) {
        global $wpdb;
        
        $cache_key = 'mw_travel_rating_' . $post_id;
        $rating = wp_cache_get($cache_key);
        
        if (false === $rating) {
            $rating = $wpdb->get_row($wpdb->prepare(
                "SELECT AVG(rating) as average, COUNT(*) as count FROM {$this->table_name} WHERE post_id = %d AND status = 'approved'",
                $post_id
            ));
            
            wp_cache_set($cache_key, $rating, '', 3600);
        }
        
        return $rating;
    }
    
    /**
     * Get rating distribution
     */
    public function get_rating_distribution($post_id) {
        global $wpdb;
        
        $distribution = array(5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0);
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT rating, COUNT(*) as count FROM {$this->table_name} WHERE post_id = %d AND status = 'approved' GROUP BY rating",
            $post_id
        ));
        
        foreach ($results as $row) {
            $distribution[$row->rating] = $row->count;
        }
        
        return $distribution;
    }
    
    /**
     * Check if user has reviewed
     */
    public function user_has_reviewed($post_id, $user_id) {
        global $wpdb;
        
        $review = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$this->table_name} WHERE post_id = %d AND user_id = %d",
            $post_id,
            $user_id
        ));
        
        return !empty($review);
    }
}
