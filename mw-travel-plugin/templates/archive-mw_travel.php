<?php
/**
 * Archive Template for MW Travel
 * Compatible with Astra Theme
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php if (have_posts()) : ?>
            
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    if (is_tax('mw_travel_category')) {
                        single_term_title();
                    } else {
                        _e('Paket Tour', 'mw-travel');
                    }
                    ?>
                </h1>
                <?php
                if (is_tax('mw_travel_category')) {
                    the_archive_description('<div class="taxonomy-description">', '</div>');
                }
                ?>
            </header>
            
            <div class="mw-travel-archive-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    
                    $price = mw_travel_get_price();
                    $duration = mw_travel_get_duration();
                    $location = mw_travel_get_location();
                    $categories = get_the_terms(get_the_ID(), 'mw_travel_category');
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('mw-travel-card'); ?>>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="mw-travel-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large'); ?>
                                </a>
                                <?php if ($categories && !is_wp_error($categories)) : ?>
                                    <div class="mw-travel-categories">
                                        <?php foreach ($categories as $category) : ?>
                                            <span class="category-badge"><?php echo esc_html($category->name); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mw-travel-content">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <?php if ($location) : ?>
                                <div class="mw-travel-location">
                                    <span class="dashicons dashicons-location"></span>
                                    <?php echo esc_html($location); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="mw-travel-meta">
                                <?php if ($duration) : ?>
                                    <div class="meta-item duration">
                                        <span class="dashicons dashicons-clock"></span>
                                        <?php echo esc_html($duration); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($price) : ?>
                                    <div class="meta-item price">
                                        <strong><?php echo esc_html($price); ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="mw-travel-button">
                                <?php _e('Lihat Detail', 'mw-travel'); ?>
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
                <h2><?php _e('Tidak ada paket travel ditemukan', 'mw-travel'); ?></h2>
                <p><?php _e('Maaf, belum ada paket travel yang tersedia saat ini.', 'mw-travel'); ?></p>
            </div>
            
        <?php endif; ?>
        
    </main>
</div>

<?php
get_sidebar();
get_footer();
