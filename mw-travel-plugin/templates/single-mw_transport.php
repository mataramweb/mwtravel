<?php
/**
 * Single Template for MW Transport
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php
        while (have_posts()) :
            the_post();
            
            $price = get_post_meta(get_the_ID(), '_mw_transport_price', true);
            $specifications = get_post_meta(get_the_ID(), '_mw_transport_specifications', true);
            $include = get_post_meta(get_the_ID(), '_mw_transport_include', true);
            $include_items = !empty($include) ? explode("\\n", $include) : array();
            ?>\n            \n            <article id="post-<?php the_ID(); ?>" <?php post_class('mw-transport-single'); ?>>
                
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php if ($price) : ?>
                        <div class="transport-price-highlight">
                            <span class="price-label"><?php _e('Harga Sewa:', 'mw-travel'); ?></span>
                            <span class="price-value"><?php echo esc_html($price); ?></span>
                        </div>
                    <?php endif; ?>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="mw-transport-featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Content -->\n                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <!-- Specifications Accordion -->
                <?php if (!empty($specifications) && is_array($specifications)) : ?>
                    <div class="mw-transport-specifications">
                        <h3><?php _e('Spesifikasi & Detail', 'mw-travel'); ?></h3>
                        <div class="mw-specs-accordion">
                            <?php foreach ($specifications as $index => $spec) : ?>
                                <?php if (!empty($spec['title'])) : ?>
                                    <div class="spec-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <div class="spec-header">
                                            <h4 class="spec-title"><?php echo esc_html($spec['title']); ?></h4>
                                            <span class="spec-icon"></span>
                                        </div>
                                        <div class="spec-content" style="<?php echo $index === 0 ? 'display: block;' : ''; ?>">
                                            <?php echo wpautop(esc_html($spec['value'])); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Include -->
                <?php if (!empty($include_items)) : ?>
                    <div class="mw-transport-include">
                        <h3><?php _e('Yang Termasuk dalam Sewa', 'mw-travel'); ?></h3>
                        <ul class="include-list">
                            <?php foreach ($include_items as $item) : ?>
                                <?php if (trim($item)) : ?>
                                    <li><?php echo esc_html($item); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Contact/Booking Section -->
                <div class="mw-transport-booking-section">
                    <h3><?php _e('Tertarik Menyewa?', 'mw-travel'); ?></h3>
                    <p><?php _e('Hubungi kami untuk reservasi dan informasi lebih lanjut.', 'mw-travel'); ?></p>
                    <div class="booking-buttons">
                        <?php
                        $whatsapp_text = urlencode('Halo, saya tertarik menyewa ' . get_the_title() . '. ' . get_permalink());
                        ?>
                        <a href="https://wa.me/?text=<?php echo $whatsapp_text; ?>" class="button whatsapp-button" target="_blank">
                            <?php _e('Hubungi via WhatsApp', 'mw-travel'); ?>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode('Sewa Transport: ' . get_the_title()); ?>&body=<?php echo $whatsapp_text; ?>" class="button email-button">
                            <?php _e('Kirim via Email', 'mw-travel'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Comments -->
                <?php
                if (comments_open() || get_comments_number()) :
                    ?>
                    <div id="comments" class="mw-transport-comments-section">
                        <h3><?php _e('Ulasan & Komentar', 'mw-travel'); ?></h3>
                        <?php comments_template(); ?>
                    </div>
                    <?php
                endif;
                ?>
                
            </article>
            
            <?php
            // Related transport
            $related_args = array(
                'post_type' => 'mw_transport',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
            );
            
            $related_query = new WP_Query($related_args);
            
            if ($related_query->have_posts()) :
                ?>
                <div class="mw-related-transport">
                    <h3><?php _e('Transport Lainnya', 'mw-travel'); ?></h3>
                    <div class="related-transport-grid">
                        <?php
                        while ($related_query->have_posts()) :
                            $related_query->the_post();
                            $rel_price = get_post_meta(get_the_ID(), '_mw_transport_price', true);
                            ?>
                            <div class="related-transport-item">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                <?php endif; ?>
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <?php if ($rel_price) : ?>
                                    <div class="related-price">
                                        <?php echo esc_html($rel_price); ?>
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
            ?>
            
        <?php endwhile; ?>
        
    </main>
</div>

<?php
get_sidebar();
get_footer();
