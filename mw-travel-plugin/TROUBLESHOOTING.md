# MW Travel Plugin - Troubleshooting Guide

## 🚨 Masalah Umum dan Solusi

### Problem 1: "There has been a critical error on this website"

**Penyebab Umum:**
- PHP version terlalu lama (< 7.4)
- Memory limit terlalu kecil
- Conflict dengan plugin lain
- Database table creation gagal

**Solusi:**

#### Step 1: Check PHP Version
```
Minimum PHP: 7.4
Recommended: 8.0+
```

Cara check:
- WordPress Admin > Tools > Site Health > Info > Server
- Atau via cPanel: PHP Version

#### Step 2: Increase PHP Memory Limit
Edit file `wp-config.php`, tambahkan sebelum `/* That's all, stop editing! */`:
```php
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
```

#### Step 3: Enable WordPress Debug Mode
Edit `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Kemudian check error di `/wp-content/debug.log`

#### Step 4: Deactivate Other Plugins
1. Rename folder `/wp-content/plugins/` via FTP menjadi `/wp-content/plugins-old/`
2. Create folder baru `/wp-content/plugins/`
3. Upload hanya MW Travel plugin
4. Activate dan test
5. Jika berhasil, activate plugins lain satu per satu

#### Step 5: Manual Database Table Creation
Jika error masih ada, create table manual via phpMyAdmin:

```sql
CREATE TABLE IF NOT EXISTS wp_mw_travel_reviews (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    post_id bigint(20) NOT NULL,
    user_id bigint(20) NOT NULL,
    rating int(1) NOT NULL,
    review_text text,
    status varchar(20) DEFAULT 'approved',
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY post_id (post_id),
    KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Note**: Ganti `wp_` dengan table prefix WordPress Anda

---

### Problem 2: Permalink 404 Not Found

**Gejala**: 
- Archive page `/tour/` shows 404
- Single tour page shows 404

**Solusi:**
1. Go to **Settings > Permalinks**
2. Klik **Save Changes** (don't change anything)
3. Clear browser cache
4. Test lagi

Jika masih 404:
```php
// Add to theme's functions.php temporarily
add_action('init', function() {
    flush_rewrite_rules();
});
```
Visit any page, then remove code above.

---

### Problem 3: Gallery Carousel Tidak Muncul

**Gejala**: 
- Gallery tidak tampil
- Atau tampil sebagai list biasa

**Solusi:**

#### Check 1: jQuery Conflict
Add to `wp-config.php`:
```php
define('CONCATENATE_SCRIPTS', false);
```

#### Check 2: JavaScript Errors
- Buka browser console (F12)
- Look for errors
- Jika ada error "$ is not defined":
  - Theme might be loading jQuery in footer
  - Or jQuery conflict

#### Check 3: Slick Slider Not Loading
View page source, check if loaded:
```html
<!-- Should see: -->
<link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
```

Manual fix - Add to theme's `functions.php`:
```php
add_action('wp_enqueue_scripts', function() {
    if (is_singular('mw_travel')) {
        wp_deregister_script('slick-slider');
        wp_enqueue_script('slick-slider', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
    }
}, 100);
```

---

### Problem 4: Accordion Tidak Berfungsi

**Gejala**: 
- Itinerary tidak collapse/expand
- Semua hari terbuka semua

**Solusi:**

#### Check JavaScript
Browser console (F12), check for errors.

#### Manual Fix
Add to theme's custom JS:
```javascript
jQuery(document).ready(function($) {
    $('.accordion-header').on('click', function() {
        var $item = $(this).closest('.accordion-item');
        var $content = $item.find('.accordion-content');
        var isActive = $item.hasClass('active');
        
        $('.accordion-item').removeClass('active');
        $('.accordion-content').slideUp(300);
        
        if (!isActive) {
            $item.addClass('active');
            $content.slideDown(300);
        }
    });
});
```

---

### Problem 5: Reviews Tidak Tersimpan

**Gejala**: 
- Form submit tapi tidak ada yang tersimpan
- Atau page refresh tapi review tidak muncul

**Solusi:**

#### Check 1: User Login Status
Reviews HARUS login. Check:
```php
if (!is_user_logged_in()) {
    // Won't work
}
```

#### Check 2: Database Table
Via phpMyAdmin, check table `wp_mw_travel_reviews` exists.

#### Check 3: Nonce Verification
Disable nonce temporarily for testing:
Comment out in `/includes/class-reviews.php`:
```php
// if (!wp_verify_nonce($_POST['mw_travel_review_nonce'], 'mw_travel_review_action')) {
//     wp_die(__('Security check failed', 'mw-travel'));
// }
```

#### Check 4: PHP Errors
Check `/wp-content/debug.log` for errors.

---

### Problem 6: Schema Markup Tidak Muncul

**Gejala**: 
- Google Rich Results Test shows no schema
- View source tidak ada JSON-LD

**Solusi:**

#### Check Function Called
In `/templates/single-mw_travel.php`, verify:
```php
if (function_exists('mw_travel_output_schema')) {
    mw_travel_output_schema();
}
```

#### Validate Schema
Use: https://validator.schema.org/
Paste your tour page URL

#### Common Issues:
- Missing required fields
- Invalid JSON format
- Wrong schema type

---

### Problem 7: Style Tidak Sesuai (Astra Theme)

**Gejala**: 
- Layout broken
- Spacing tidak pas
- Container too wide/narrow

**Solusi:**

#### Check Astra Container Settings
Go to: **Appearance > Customize > Global > Container**
- Width: 1200px (recommended)
- Layout: Content Boxed / Full Width

#### Override in Theme
Add to theme's `style.css`:
```css
.mw-travel-single {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}
```

---

## 🔧 Advanced Troubleshooting

### Enable Full Error Reporting

Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', true);
@ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### Clear All Caches

1. **WordPress Cache**: Via plugin (WP Super Cache, W3 Total Cache)
2. **Browser Cache**: Ctrl+Shift+Delete (Chrome/Firefox)
3. **Server Cache**: 
   - Via cPanel: Clear OPcache
   - Or SSH: `service php-fpm restart`
4. **CDN Cache**: If using Cloudflare, purge cache

### Database Cleanup

If plugin messed up database:
```sql
-- Delete all plugin data
DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = 'mw_travel');
DELETE FROM wp_posts WHERE post_type = 'mw_travel';
DELETE FROM wp_term_relationships WHERE term_taxonomy_id IN (SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE taxonomy = 'mw_travel_category');
DELETE FROM wp_term_taxonomy WHERE taxonomy = 'mw_travel_category';
DROP TABLE IF EXISTS wp_mw_travel_reviews;
```

### Safe Mode Installation

1. Upload plugin via FTP (not via WordPress)
2. Don't activate immediately
3. Via phpMyAdmin, create table manually (SQL above)
4. Go to Permalinks > Save
5. Then activate plugin

---

## 📞 Getting Help

### Before Contacting Support:

**Collect This Information:**
1. WordPress version
2. PHP version  
3. Active theme name & version
4. Other active plugins list
5. Content of `/wp-content/debug.log`
6. Browser console errors (F12)
7. Steps to reproduce the error

### Temporary Disable Features

To isolate issues, comment out in `mw-travel.php`:

**Disable Reviews:**
```php
// new MW_Travel_Reviews();
```

**Disable Templates:**
```php
// add_filter('template_include', array($this, 'load_custom_templates'));
```

**Disable Frontend Scripts:**
```php
// add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
```

---

## ✅ Prevention Tips

1. **Always backup** before installing/updating plugins
2. **Test on staging** before production
3. **Keep WordPress updated**
4. **Keep PHP version current** (8.0+)
5. **Use quality hosting** (adequate resources)
6. **Monitor error logs** regularly
7. **Disable conflicting plugins** (other carousel/gallery plugins)

---

## 🆘 Emergency: Complete Removal

If all else fails and you need to completely remove plugin:

### Via FTP:
1. Delete `/wp-content/plugins/mw-travel-plugin/`
2. That's it! WordPress auto-deactivates

### Via Database:
```sql
-- Remove all traces
DELETE FROM wp_options WHERE option_name LIKE '%mw_travel%';
DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = 'mw_travel');
DELETE FROM wp_posts WHERE post_type = 'mw_travel';
DROP TABLE IF EXISTS wp_mw_travel_reviews;
```

### Clear Rewrite Rules:
Go to **Settings > Permalinks > Save**

---

**Still Having Issues?**
Contact: support@mataramweb.com

Include:
- Description of problem
- Screenshots of error
- Debug log content
- System info (WP version, PHP version, theme)
