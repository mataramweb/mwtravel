# MW Travel Plugin v2.0 - Update Log

## 🆕 Fitur Baru yang Ditambahkan

### 1. ✅ Gallery Carousel dengan Slick Slider
- **Lokasi**: Di bawah Featured Image
- **Fitur**:
  - Responsive carousel dengan 3 slide di desktop
  - Auto-play dengan interval 3 detik
  - Navigation arrows dan dots
  - Mobile-friendly (1 slide di mobile)
  - Smooth transitions

### 2. ✅ Itinerary Accordion
- **Fitur**:
  - Collapse/Expand dengan smooth animation
  - Hanya 1 item aktif pada satu waktu
  - Day 1 terbuka by default
  - Visual feedback dengan warna biru saat aktif
  - Day number badge dengan desain circular

### 3. ✅ Rating & Review System
- **Database**: Tabel baru `wp_mw_travel_reviews`
- **Fitur Rating**:
  - Star rating 1-5 bintang
  - Average rating display
  - Review count
  - User must login untuk submit review
  - Satu user hanya bisa review sekali per paket
  
- **Fitur Review**:
  - Review text (optional)
  - User avatar display
  - Review timestamp
  - Reviewer name display
  
### 4. ✅ Schema.org Product Markup
- **Type**: Product schema untuk SEO
- **Data yang di-markup**:
  - Product name
  - Description
  - Image
  - Price (IDR)
  - Location
  - Duration
  - Aggregate rating
  - Individual reviews
  - Availability status

### 5. ✅ Permalink "tour"
- **Sebelumnya**: `yoursite.com/travel/`
- **Sekarang**: `yoursite.com/tour/`
- **Kategori**: `yoursite.com/tour-category/`

### 6. ✅ Astra Theme Compatibility
- Menggunakan Astra container layout structure
- Compatible dengan Astra customizer settings
- Responsive design mengikuti Astra breakpoints
- Support untuk boxed dan full-width layouts

---

## 📦 File Baru

1. **includes/class-reviews.php** - Review system class
2. **Updated**: All frontend files untuk support fitur baru

---

## 🔄 Perubahan dari Versi Sebelumnya

### Database
- **Tabel Baru**: `wp_mw_travel_reviews`
  ```sql
  - id (bigint)
  - post_id (bigint)
  - user_id (bigint)
  - rating (int 1-5)
  - review_text (text)
  - status (varchar)
  - created_at (datetime)
  ```

### Custom Post Type
- **Slug**: `travel` → `tour`
- **Archive**: `/travel/` → `/tour/`
- **Support**: Added `comments` support

### Taxonomy
- **Slug**: `kategori-travel` → `tour-category`

### Templates
- Gallery: Grid → Slick Carousel
- Itinerary: Expandable list → Accordion
- Added: Review section
- Added: Schema markup output

### Assets
- **CSS**: 
  - Added carousel styles
  - Added accordion styles
  - Added review form styles
  - Added rating stars styles
  
- **JavaScript**:
  - Slick Slider initialization
  - Accordion functionality
  - Star rating interactions

---

## 🚀 Cara Install Update

### Method 1: Fresh Install (Recommended)
1. Deactivate dan delete plugin lama
2. Upload plugin baru (v2.0)
3. Activate
4. **PENTING**: Go to Settings > Permalinks dan klik "Save Changes" untuk flush rewrite rules

### Method 2: Update via FTP
1. Backup folder plugin lama
2. Replace dengan folder plugin baru
3. Go to Settings > Permalinks dan klik "Save Changes"

---

## ⚙️ Penggunaan Fitur Baru

### Rating & Review
**Untuk Pengunjung**:
1. Login ke WordPress
2. Buka halaman detail paket tour
3. Scroll ke section "Rating & Reviews"
4. Pilih rating (1-5 bintang)
5. Tulis review (optional)
6. Klik "Submit Review"

**Untuk Admin**:
- Review otomatis approved
- Data review tersimpan di database
- View reviews via phpMyAdmin jika perlu moderasi manual

### Gallery Carousel
**Auto-configured**:
- Gallery otomatis jadi carousel jika ada gambar
- Tidak perlu setting tambahan
- Responsive otomatis

### Itinerary Accordion
**Auto-configured**:
- Itinerary otomatis jadi accordion
- Day 1 terbuka by default
- Click header untuk toggle

---

## 🎨 Customization

### Warna Theme
Edit di `assets/css/frontend.css`:
```css
/* Primary color */
#0073aa → your color

/* Gradient (Booking section) */
#667eea, #764ba2 → your colors
```

### Carousel Settings
Edit di `assets/js/frontend.js`:
```javascript
slidesToShow: 3,  // Number of slides
autoplaySpeed: 3000,  // Auto-play speed
```

### Accordion Behavior
Default: Hanya 1 aktif
Jika ingin multiple aktif, edit di `frontend.js`:
```javascript
// Comment out:
// $('.accordion-item').removeClass('active');
// $('.accordion-content').slideUp(300);
```

---

## 🐛 Known Issues & Solutions

### Issue 1: Carousel tidak muncul
**Solution**:
- Clear browser cache
- Check console untuk errors
- Pastikan jQuery loaded

### Issue 2: Permalink 404
**Solution**:
- Go to Settings > Permalinks
- Click "Save Changes" (don't change anything)
- Flush rewrite rules

### Issue 3: Reviews tidak tersimpan
**Solution**:
- Check database table created
- Verify user logged in
- Check PHP error logs

---

## 📊 Database Schema

### Table: wp_mw_travel_reviews
```sql
CREATE TABLE wp_mw_travel_reviews (
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
);
```

---

## 🔗 API Reference

### Review Functions
```php
// Get reviews instance
$reviews = new MW_Travel_Reviews();

// Get reviews for a post
$reviews_list = $reviews->get_reviews($post_id);

// Get average rating
$rating = $reviews->get_average_rating($post_id);

// Check if user reviewed
$has_reviewed = $reviews->user_has_reviewed($post_id, $user_id);

// Add review programmatically
$reviews->add_review($post_id, $user_id, $rating, $review_text);
```

### Display Functions
```php
// Display reviews section
mw_travel_display_reviews();

// Get star rating HTML
$stars = mw_travel_get_star_rating($rating);

// Output schema markup
mw_travel_output_schema();
```

---

## 📝 Changelog

### Version 2.0.0 (Current)
- ✅ Added Slick Slider carousel for gallery
- ✅ Added accordion for itinerary
- ✅ Added rating & review system
- ✅ Added Schema.org Product markup
- ✅ Changed permalink to "tour"
- ✅ Improved Astra theme compatibility
- ✅ Responsive improvements

### Version 1.0.0
- Initial release
- Basic custom post type
- Gallery grid
- Basic templates

---

## 🔐 Security

### Review System Security
- ✅ Nonce verification
- ✅ User capability checks
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ Data sanitization
- ✅ One review per user per post

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Check documentation ini dulu
2. Check browser console untuk errors
3. Check PHP error logs
4. Contact support: support@mataramweb.com

---

**Plugin Version**: 2.0.0  
**WordPress Required**: 5.0+  
**PHP Required**: 7.4+  
**Tested with**: WordPress 6.4, Astra Theme 4.x
