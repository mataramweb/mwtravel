# MW Travel Plugin - Quick Reference

## 📁 Struktur File Plugin

```
mw-travel-plugin/
├── mw-travel.php                          # File utama plugin
├── README.txt                             # Documentation untuk WordPress.org
├── INSTALLATION.md                        # Panduan instalasi lengkap
│
├── includes/                              # Core functionality
│   ├── class-custom-post-type.php        # Register Custom Post Type
│   ├── class-taxonomy.php                # Register Custom Taxonomy
│   ├── class-meta-boxes.php              # Meta boxes untuk admin
│   └── template-functions.php            # Helper functions
│
├── templates/                             # Template files
│   ├── archive-mw_travel.php             # Archive page template
│   └── single-mw_travel.php              # Single post template
│
└── assets/                                # CSS & JS files
    ├── css/
    │   ├── admin.css                     # Admin styling
    │   └── frontend.css                  # Frontend styling
    └── js/
        ├── admin.js                      # Admin scripts
        └── frontend.js                   # Frontend scripts
```

## 🔑 Key Information

### Custom Post Type
- **Slug**: `mw_travel`
- **Archive URL**: `/travel/`
- **Supports**: title, editor, thumbnail, excerpt, author

### Taxonomy
- **Slug**: `mw_travel_category`
- **URL**: `/kategori-travel/`
- **Hierarchical**: Yes

### Meta Keys
```php
_mw_travel_price       // string - Harga paket
_mw_travel_duration    // string - Durasi perjalanan
_mw_travel_location    // string - Lokasi tujuan
_mw_travel_itinerary   // array - Itinerary data
_mw_travel_gallery     // string - Comma-separated image IDs
_mw_travel_include     // string - Newline-separated include items
_mw_travel_exclude     // string - Newline-separated exclude items
```

## 🎯 Template Functions

### Get Functions
```php
mw_travel_get_price($post_id)        // Returns: string
mw_travel_get_duration($post_id)     // Returns: string
mw_travel_get_location($post_id)     // Returns: string
mw_travel_get_itinerary($post_id)    // Returns: array
mw_travel_get_gallery($post_id)      // Returns: array of image IDs
mw_travel_get_include($post_id)      // Returns: array
mw_travel_get_exclude($post_id)      // Returns: array
```

### Display Functions
```php
mw_travel_display_details()          // Displays price, duration, location
mw_travel_display_itinerary()        // Displays full itinerary
mw_travel_display_gallery()          // Displays gallery grid
mw_travel_display_include_exclude()  // Displays include/exclude lists
```

## 📊 Itinerary Data Structure

```php
array(
    array(
        'day_number' => '1',
        'title' => 'Tiba di Bali',
        'description' => 'Penjemputan di bandara...',
        'activities' => 'Check-in hotel\nMakan malam\nIstirahat'
    ),
    array(
        'day_number' => '2',
        'title' => 'Wisata Ubud',
        'description' => 'Kunjungi wisata Ubud...',
        'activities' => 'Monkey Forest\nTegalalang Rice Terrace\nUbud Palace'
    ),
    // ... more days
)
```

## 🎨 CSS Classes

### Archive Page
```css
.mw-travel-archive-grid      // Grid container
.mw-travel-card              // Individual card
.mw-travel-thumbnail         // Image container
.mw-travel-content           // Content wrapper
.mw-travel-categories        // Category badges
.category-badge              // Single badge
.mw-travel-location          // Location display
.mw-travel-meta              // Meta information
.mw-travel-button            // Call-to-action button
```

### Single Page
```css
.mw-travel-single            // Article wrapper
.mw-travel-featured-image    // Featured image
.mw-travel-details-section   // Details wrapper
.mw-travel-details           // Details grid
.mw-travel-itinerary         // Itinerary section
.mw-itinerary-day            // Single day
.day-number                  // Day number badge
.mw-travel-gallery           // Gallery section
.gallery-grid                // Gallery container
.mw-travel-include-exclude   // Include/exclude wrapper
.mw-travel-booking-section   // Booking CTA section
.mw-related-travel           // Related posts
```

## 🔌 WordPress Hooks

### Actions
```php
// After plugin initialization
do_action('mw_travel_init');

// Before saving meta boxes
do_action('mw_travel_before_save_meta', $post_id);

// After saving meta boxes
do_action('mw_travel_after_save_meta', $post_id);
```

### Filters
```php
// Filter post type arguments
apply_filters('mw_travel_post_type_args', $args);

// Filter taxonomy arguments
apply_filters('mw_travel_taxonomy_args', $args);

// Filter template path
apply_filters('mw_travel_template_path', $template);
```

## 🚀 Usage Examples

### Custom Query
```php
<?php
$args = array(
    'post_type' => 'mw_travel',
    'posts_per_page' => 6,
    'orderby' => 'date',
    'order' => 'DESC'
);

$travel_query = new WP_Query($args);

if ($travel_query->have_posts()) :
    while ($travel_query->have_posts()) : $travel_query->the_post();
        ?>
        <div class="travel-item">
            <h3><?php the_title(); ?></h3>
            <p><?php echo mw_travel_get_location(); ?></p>
            <span><?php echo mw_travel_get_price(); ?></span>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
endif;
?>
```

### Display in Theme
```php
<?php
// In your theme template
if (function_exists('mw_travel_display_details')) {
    mw_travel_display_details();
}

if (function_exists('mw_travel_display_itinerary')) {
    mw_travel_display_itinerary();
}
?>
```

### Custom Taxonomy Query
```php
<?php
$args = array(
    'post_type' => 'mw_travel',
    'tax_query' => array(
        array(
            'taxonomy' => 'mw_travel_category',
            'field' => 'slug',
            'terms' => 'beach-island',
        ),
    ),
);

$beach_travels = new WP_Query($args);
?>
```

## ⚙️ Admin Customization

### Add Custom Meta Box
```php
add_action('add_meta_boxes', 'my_custom_travel_meta');
function my_custom_travel_meta() {
    add_meta_box(
        'my_custom_box',
        'Custom Information',
        'my_custom_box_callback',
        'mw_travel',
        'side'
    );
}

function my_custom_box_callback($post) {
    // Your custom fields
}
```

### Modify Columns
```php
add_filter('manage_mw_travel_posts_columns', 'my_travel_columns');
function my_travel_columns($columns) {
    $columns['price'] = 'Harga';
    $columns['duration'] = 'Durasi';
    return $columns;
}

add_action('manage_mw_travel_posts_custom_column', 'my_travel_column_content', 10, 2);
function my_travel_column_content($column, $post_id) {
    if ($column === 'price') {
        echo mw_travel_get_price($post_id);
    }
    if ($column === 'duration') {
        echo mw_travel_get_duration($post_id);
    }
}
```

## 📱 Responsive Breakpoints

```css
/* Desktop: Default styles */

/* Tablet: <= 768px */
@media (max-width: 768px) {
    .mw-travel-archive-grid {
        grid-template-columns: 1fr;
    }
}

/* Mobile: <= 480px */
@media (max-width: 480px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
```

## 🔒 Security Features

- Nonce verification untuk semua form submissions
- Capability checks untuk edit permissions
- Data sanitization untuk semua inputs
- Escaping untuk semua outputs
- CSRF protection

## 🌐 Multilingual Support

Plugin ini translation-ready dengan text domain `mw-travel`.

Untuk menambahkan bahasa baru:
1. Buat folder `languages/` di dalam plugin
2. Generate .pot file
3. Translate ke bahasa yang diinginkan
4. Save sebagai `mw-travel-id_ID.po` dan `mw-travel-id_ID.mo`

---

**Version**: 1.0.0  
**Requires**: WordPress 5.0+, PHP 7.4+  
**Compatible**: Astra Theme, Most WordPress themes  
**License**: GPLv2 or later
