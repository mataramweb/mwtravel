<?php
/**
 * Template Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get travel price
 */
function mw_travel_get_price($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_mw_travel_price', true);
}

/**
 * Get travel duration
 */
function mw_travel_get_duration($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_mw_travel_duration', true);
}

/**
 * Get travel location
 */
function mw_travel_get_location($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_mw_travel_location', true);
}

/**
 * Get travel itinerary
 */
function mw_travel_get_itinerary($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $itinerary = get_post_meta($post_id, '_mw_travel_itinerary', true);
    return !empty($itinerary) ? $itinerary : array();
}

/**
 * Get travel gallery
 */
function mw_travel_get_gallery($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $gallery = get_post_meta($post_id, '_mw_travel_gallery', true);
    $gallery_ids = !empty($gallery) ? explode(',', $gallery) : array();
    return $gallery_ids;
}

/**
 * Get travel include items
 */
function mw_travel_get_include($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $include = get_post_meta($post_id, '_mw_travel_include', true);
    return !empty($include) ? explode('\n', $include) : array();
}

/**
 * Get travel exclude items
 */
function mw_travel_get_exclude($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $exclude = get_post_meta($post_id, '_mw_travel_exclude', true);
    return !empty($exclude) ? explode('\n', $exclude) : array();
}

/**
 * Display travel details
 */
function mw_travel_display_details() {
    $price = mw_travel_get_price();
    $duration = mw_travel_get_duration();
    $location = mw_travel_get_location();
    
    if (!$price && !$duration && !$location) {
        return;
    }
    ?>
    <div class="mw-travel-details">
        <?php if ($price) : ?>
            <div class="mw-travel-detail-item price">
                <span class="label"><?php _e('Harga:', 'mw-travel'); ?></span>
                <span class="value"><?php echo esc_html($price); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($duration) : ?>
            <div class="mw-travel-detail-item duration">
                <span class="label"><?php _e('Durasi:', 'mw-travel'); ?></span>
                <span class="value"><?php echo esc_html($duration); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($location) : ?>
            <div class="mw-travel-detail-item location">
                <span class="label"><?php _e('Lokasi:', 'mw-travel'); ?></span>
                <span class="value"><?php echo esc_html($location); ?></span>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display travel itinerary
 */
function mw_travel_display_itinerary() {
    $itinerary = mw_travel_get_itinerary();
    
    if (empty($itinerary)) {
        return;
    }
    ?>
    <div class="mw-travel-itinerary">
        <h3><?php _e('Itinerary', 'mw-travel'); ?></h3>
        <div class="mw-accordion-itinerary">
            <?php foreach ($itinerary as $index => $day) : ?>
                <div class="accordion-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="accordion-header">
                        <span class="day-number"><?php echo esc_html($day['day_number']); ?></span>
                        <h4 class="day-title"><?php echo esc_html($day['title']); ?></h4>
                        <span class="accordion-icon"></span>
                    </div>
                    <div class="accordion-content" style="<?php echo $index === 0 ? 'display: block;' : ''; ?>">
                        <?php if (!empty($day['description'])) : ?>
                            <div class="day-description">
                                <?php echo wpautop(esc_html($day['description'])); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($day['activities'])) : ?>
                            <div class="day-activities">
                                <strong><?php _e('Aktivitas:', 'mw-travel'); ?></strong>
                                <?php echo wpautop(esc_html($day['activities'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Display travel gallery
 */
function mw_travel_display_gallery() {
    $gallery_ids = mw_travel_get_gallery();
    
    if (empty($gallery_ids)) {
        return;
    }
    ?>
    <div class="mw-travel-gallery">
        <h3><?php _e('Gallery', 'mw-travel'); ?></h3>
        <div class="gallery-grid">
            <?php foreach ($gallery_ids as $image_id) : ?>
                <?php if ($image_id) : ?>
                    <?php $image = wp_get_attachment_image_src($image_id, 'large'); ?>
                    <?php if ($image) : ?>
                        <div class="gallery-item">
                            <a href="<?php echo esc_url(wp_get_attachment_url($image_id)); ?>" data-lightbox="travel-gallery">
                                <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', true)); ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Display include/exclude lists
 */
function mw_travel_display_include_exclude() {
    $include = mw_travel_get_include();
    $exclude = mw_travel_get_exclude();
    
    if (empty($include) && empty($exclude)) {
        return;
    }
    ?>
    <div class="mw-travel-include-exclude">
        <?php if (!empty($include)) : ?>
            <div class="mw-travel-include">
                <h3><?php _e('Yang Termasuk', 'mw-travel'); ?></h3>
                <ul>
                    <?php foreach ($include as $item) : ?>
                        <?php if (trim($item)) : ?>
                            <li><?php echo esc_html($item); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($exclude)) : ?>
            <div class="mw-travel-exclude">
                <h3><?php _e('Yang Tidak Termasuk', 'mw-travel'); ?></h3>
                <ul>
                    <?php foreach ($exclude as $item) : ?>
                        <?php if (trim($item)) : ?>
                            <li><?php echo esc_html($item); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
