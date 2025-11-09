<?php
/**
 * Archive Template for MW Transport
 * Grid layout yang menarik
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php if (have_posts()) : ?>
            
            <header class="page-header">
                <h1 class="page-title"><?php _e('Transport & Rental', 'mw-travel'); ?></h1>
                <p class="archive-description"><?php _e('Pilih kendaraan sesuai kebutuhan perjalanan Anda', 'mw-travel'); ?></p>
            </header>
            
            <div class="mw-transport-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    
                    $price = get_post_meta(get_the_ID(), '_mw_transport_price', true);
                    $specifications = get_post_meta(get_the_ID(), '_mw_transport_specifications', true);
                    ?>\n                    \n                    <article id="post-<?php the_ID(); ?>" <?php post_class('mw-transport-card'); ?>>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="mw-transport-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large'); ?>
                                </a>
                                <?php if ($price) : ?>
                                    <div class="transport-price-badge">
                                        <?php echo esc_html($price); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mw-transport-content">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <?php if (has_excerpt()) : ?>
                                <div class="entry-summary">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($specifications) && is_array($specifications)) : ?>
                                <div class="transport-specs-preview">
                                    <ul>
                                        <?php 
                                        $count = 0;
                                        foreach ($specifications as $spec) : 
                                            if ($count >= 3) break;
                                            if (!empty($spec['title'])) :
                                        ?>
                                            <li>
                                                <span class="spec-icon">✓</span>
                                                <strong><?php echo esc_html($spec['title']); ?>:</strong>
                                                <?php echo esc_html(wp_trim_words($spec['value'], 5)); ?>
                                            </li>
                                        <?php 
                                            $count++;
                                            endif;
                                        endforeach; 
                                        ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="transport-button">
                                <?php _e('Lihat Detail & Pesan', 'mw-travel'); ?>
                                <span class="dashicons dashicons-arrow-right-alt2"></span>
                            </a>
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Sebelumnya', 'mw-travel'),
                'next_text' => __('Selanjutnya &raquo;', 'mw-travel'),
            ));
            ?>
            
        <?php else : ?>
            
            <div class="no-results">
                <h2><?php _e('Tidak ada transport ditemukan', 'mw-travel'); ?></h2>
                <p><?php _e('Maaf, belum ada kendaraan yang tersedia saat ini.', 'mw-travel'); ?></p>
            </div>
            
        <?php endif; ?>
        
    </main>
</div>

<?php
get_sidebar();
get_footer();
