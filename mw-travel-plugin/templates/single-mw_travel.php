<?php
/**
 * Single Template for MW Travel
 * Compatible with Astra Theme
 */

get_header();

// Output Schema markup
if (function_exists('mw_travel_output_schema')) {
    mw_travel_output_schema();
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php
        while (have_posts()) :
            the_post();
            
            $price = mw_travel_get_price();
            $duration = mw_travel_get_duration();
            $location = mw_travel_get_location();
            $categories = get_the_terms(get_the_ID(), 'mw_travel_category');
            $gallery_ids = mw_travel_get_gallery();
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('mw-travel-single'); ?>>
                
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <?php if ($categories && !is_wp_error($categories)) : ?>
                        <div class="mw-travel-categories">
                            <?php foreach ($categories as $category) : ?>
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-link">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="mw-travel-featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Gallery Carousel below Featured Image -->
                <?php if (!empty($gallery_ids)) : ?>
                    <?php mw_travel_display_gallery(); ?>
                <?php endif; ?>
                
                <!-- Travel Details -->
                <div class="mw-travel-details-section">
                    <?php mw_travel_display_details(); ?>
                </div>
                
                <!-- Content -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <!-- Itinerary Accordion -->
                <?php mw_travel_display_itinerary(); ?>
                
                <!-- Include/Exclude -->
                <?php mw_travel_display_include_exclude(); ?>
                
                <!-- WordPress Comments (for Reviews) -->
                <?php
                if (comments_open() || get_comments_number()) :
                    ?>
                    <div id="comments" class="mw-travel-comments-section">
                        <h3><?php _e('Ulasan & Komentar', 'mw-travel'); ?></h3>
                        <?php comments_template(); ?>
                    </div>
                    <?php
                endif;
                ?>
                
                <!-- Contact/Booking Section -->
                <div class="mw-travel-booking-section">
                    <h3><?php _e('Tertarik dengan paket ini?', 'mw-travel'); ?></h3>
                    <p><?php _e('Hubungi kami untuk informasi lebih lanjut dan booking.', 'mw-travel'); ?></p>
                    <div class="booking-buttons">
                        <?php
                        $whatsapp_text = urlencode('Halo, saya tertarik dengan paket ' . get_the_title() . '. ' . get_permalink());
                        ?>
                        <a href="https://wa.me/?text=<?php echo $whatsapp_text; ?>" class="button whatsapp-button" target="_blank">
                            <?php _e('Hubungi via WhatsApp', 'mw-travel'); ?>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode('Paket Travel: ' . get_the_title()); ?>&body=<?php echo $whatsapp_text; ?>" class="button email-button">
                            <?php _e('Kirim via Email', 'mw-travel'); ?>
                        </a>
                    </div>
                </div>
                
            </article>
            
            <?php
            // Related travel packages
            if ($categories && !is_wp_error($categories)) {
                $category_ids = wp_list_pluck($categories, 'term_id');
                
                $related_args = array(
                    'post_type' => 'mw_travel',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'mw_travel_category',
                            'field' => 'term_id',
                            'terms' => $category_ids,
                        ),
                    ),
                );
                
                $related_query = new WP_Query($related_args);
                
                if ($related_query->have_posts()) :
                    ?>
                    <div class="mw-related-travel">
                        <h3><?php _e('Paket Travel Lainnya', 'mw-travel'); ?></h3>
                        <div class="related-travel-grid">
                            <?php
                            while ($related_query->have_posts()) :
                                $related_query->the_post();
                                $rel_price = mw_travel_get_price();
                                $rel_duration = mw_travel_get_duration();
                                ?>
                                <div class="related-travel-item">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                    <?php endif; ?>
                                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <?php if ($rel_duration || $rel_price) : ?>
                                        <div class="related-meta">
                                            <?php if ($rel_duration) : ?>
                                                <span><?php echo esc_html($rel_duration); ?></span>
                                            <?php endif; ?>
                                            <?php if ($rel_price) : ?>
                                                <span class="price"><?php echo esc_html($rel_price); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <?php
                endif;
            }
            ?>
            
        <?php endwhile; ?>
        
    </main>
</div>

<?php
get_sidebar();
get_footer();
