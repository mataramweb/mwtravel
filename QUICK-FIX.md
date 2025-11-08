# MW Travel Plugin - Quick Fix untuk Critical Error

## 🚨 Critical Error Saat Instalasi

Jika Anda mengalami **"There has been a critical error on this website"** saat install plugin, ikuti langkah berikut:

---

## ✅ Solusi Cepat (5 Menit)

### Step 1: Check Requirement Minimum

**Pastikan server Anda memenuhi:**
- PHP: 7.4 atau lebih tinggi
- WordPress: 5.0 atau lebih tinggi
- Memory: 128MB minimum (256MB recommended)

**Cara Check PHP Version:**
1. Login cPanel
2. Cari "Select PHP Version"
3. Lihat versi yang aktif
4. Jika < 7.4, upgrade dulu

---

### Step 2: Install via FTP (Safer Method)

**Jangan install via WordPress Admin**, gunakan FTP:

1. **Extract ZIP file** di komputer Anda
2. **Upload folder `mw-travel-plugin`** via FTP ke:
   ```
   /wp-content/plugins/
   ```
3. **Create database table manually** via phpMyAdmin:
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
   **PENTING**: Ganti `wp_` dengan table prefix WordPress Anda!

4. **Flush Permalinks:**
   - Login WordPress Admin
   - Go to **Settings > Permalinks**
   - Klik **Save Changes** (jangan ubah apapun)

5. **Activate Plugin:**
   - Go to **Plugins**
   - Find "MW Travel"
   - Click **Activate**

---

### Step 3: Enable Debug Mode (Jika Masih Error)

Edit file `wp-config.php`, tambahkan sebelum `/* That's all, stop editing! */`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('WP_MEMORY_LIMIT', '256M');
```

Coba install lagi, kemudian check error di:
```
/wp-content/debug.log
```

---

### Step 4: Deactivate Conflicting Plugins

Plugin yang sering conflict:
- Gallery plugins lain
- Slider plugins (Revolution Slider, etc)
- Review/Rating plugins
- Custom post type plugins

**Cara test:**
1. Deactivate semua plugin kecuali MW Travel
2. Activate MW Travel
3. Jika berhasil, activate plugins lain satu per satu
4. Identify mana yang conflict

---

## 🔧 Advanced Fix (Jika Masih Gagal)

### Option 1: Increase PHP Limits

Edit `.htaccess` atau `php.ini`:

**.htaccess:**
```
php_value memory_limit 256M
php_value max_execution_time 300
php_value post_max_size 64M
php_value upload_max_filesize 64M
```

**php.ini:**
```
memory_limit = 256M
max_execution_time = 300
post_max_size = 64M
upload_max_filesize = 64M
```

### Option 2: Switch PHP Version

Via cPanel:
1. "Select PHP Version"
2. Pilih PHP 8.0 atau 8.1
3. Klik "Apply"
4. Try install again

### Option 3: Fresh WordPress Install Test

Test di fresh WordPress install untuk isolate masalah:
1. Create subdomain (test.yourdomain.com)
2. Install fresh WordPress
3. Install plugin
4. Jika works: masalah ada di main site (theme/plugins conflict)
5. Jika gagal: masalah ada di server requirements

---

## 📋 Pre-Installation Checklist

Sebelum install, pastikan:

- [ ] PHP Version >= 7.4
- [ ] WordPress Version >= 5.0
- [ ] Memory limit >= 256MB
- [ ] No conflicting plugins active
- [ ] Backup database & files
- [ ] Test on staging first (if possible)

---

## 🆘 Masih Gagal?

### Quick Contact:

**Email:** support@mataramweb.com

**Include informasi:**
1. WordPress version
2. PHP version
3. Active theme
4. List of active plugins
5. Error message dari debug.log
6. Screenshot error (jika ada)

---

## 💡 Alternative: Minimal Version Install

Jika terus gagal, gunakan versi minimal tanpa reviews:

**Manual Edit - Disable Reviews Temporarily:**

Edit file `mw-travel.php`, comment line:
```php
// require_once MW_TRAVEL_PLUGIN_DIR . 'includes/class-reviews.php';
// new MW_Travel_Reviews();
```

Edit file `templates/single-mw_travel.php`, comment:
```php
// <?php if (function_exists('mw_travel_display_reviews')) : ?>
//     <?php mw_travel_display_reviews(); ?>
// <?php endif; ?>
```

Kemudian install. Setelah berhasil, bisa uncomment dan update.

---

## ✅ Verification Steps (Setelah Install Berhasil)

1. **Check Menu:**
   - "MW Travel" menu muncul di sidebar

2. **Check Permalink:**
   - Visit: `yoursite.com/tour/`
   - Tidak 404

3. **Create Test Post:**
   - Add New Travel
   - Isi semua field
   - Publish
   - View post
   - Check semua section muncul

4. **Check Frontend:**
   - Gallery carousel berfungsi
   - Accordion berfungsi
   - Styling correct

---

**Plugin Version:** 2.0.0  
**Last Updated:** November 2024  
**Support:** support@mataramweb.com
