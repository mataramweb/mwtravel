# MW Travel Plugin - Panduan Instalasi & Penggunaan

## 📦 Instalasi

### Metode 1: Upload via WordPress Admin (Recommended)

1. **Compress folder plugin** menjadi ZIP file:
   ```bash
   cd /app
   zip -r mw-travel-plugin.zip mw-travel-plugin/
   ```

2. **Login ke WordPress Admin**
   - Buka dashboard WordPress Anda

3. **Upload Plugin**
   - Pergi ke **Plugins > Add New**
   - Klik **Upload Plugin**
   - Pilih file `mw-travel-plugin.zip`
   - Klik **Install Now**

4. **Aktifkan Plugin**
   - Setelah instalasi selesai, klik **Activate Plugin**

### Metode 2: Upload Manual via FTP/cPanel

1. **Upload folder plugin**
   - Upload seluruh folder `mw-travel-plugin` ke direktori:
   ```
   /wp-content/plugins/
   ```

2. **Aktifkan Plugin**
   - Login ke WordPress Admin
   - Pergi ke **Plugins**
   - Cari **MW Travel** dan klik **Activate**

---

## 🚀 Cara Menggunakan

### Membuat Paket Travel Baru

1. **Buka Menu MW Travel**
   - Di sidebar WordPress admin, klik **MW Travel > Tambah Baru**

2. **Isi Informasi Dasar**
   - **Judul**: Nama paket travel (contoh: "Paket Tour Bali 4D3N")
   - **Deskripsi**: Deskripsi lengkap paket di editor utama
   - **Featured Image**: Upload gambar utama paket

3. **Isi Detail Travel**
   - **Harga**: Contoh: "Rp 5.000.000"
   - **Durasi**: Contoh: "4 Hari 3 Malam"
   - **Lokasi**: Contoh: "Bali, Indonesia"

4. **Buat Itinerary**
   - Klik tombol **Tambah Hari**
   - Isi untuk setiap hari:
     - **Nomor Hari**: 1, 2, 3, dst
     - **Judul**: Contoh: "Tiba di Bali"
     - **Deskripsi**: Deskripsi singkat hari tersebut
     - **Aktivitas**: List aktivitas yang dilakukan
   - Ulangi untuk hari-hari berikutnya
   - Anda bisa drag & drop untuk mengatur urutan

5. **Upload Gallery**
   - Klik tombol **Tambah Gambar**
   - Pilih multiple images dari media library atau upload baru
   - Gambar akan ditampilkan dalam grid
   - Klik (X) untuk menghapus gambar

6. **Isi Yang Termasuk (Include)**
   - Klik **Tambah Item**
   - Isi item yang termasuk dalam paket
   - Contoh:
     - Hotel bintang 4
     - Makan 3x sehari
     - Tiket masuk objek wisata
     - Tour guide profesional

7. **Isi Yang Tidak Termasuk (Exclude)**
   - Klik **Tambah Item**
   - Isi item yang tidak termasuk
   - Contoh:
     - Tiket pesawat
     - Pengeluaran pribadi
     - Asuransi perjalanan
     - Tips untuk guide

8. **Pilih Kategori**
   - Di sidebar kanan, pilih kategori travel yang sesuai
   - Kategori default yang tersedia:
     - Beach & Island
     - Mountain & Nature
     - City Tour
     - Cultural Tour
     - Adventure
     - Religious Tour
     - Honeymoon

9. **Publish**
   - Klik tombol **Publish** untuk menerbitkan paket

---

## 📋 Mengelola Kategori

### Tambah Kategori Baru

1. Pergi ke **MW Travel > Kategori**
2. Isi:
   - **Name**: Nama kategori
   - **Slug**: URL-friendly name (otomatis)
   - **Description**: Deskripsi kategori
3. Klik **Add New Kategori**

### Edit Kategori

1. Pergi ke **MW Travel > Kategori**
2. Hover pada kategori yang ingin diedit
3. Klik **Edit**
4. Update informasi
5. Klik **Update**

---

## 🎨 Menampilkan Paket Travel

### Archive Page

Semua paket travel otomatis ditampilkan di:
```
https://yoursite.com/travel/
```

Filter berdasarkan kategori:
```
https://yoursite.com/kategori-travel/beach-island/
```

### Single Page

Setiap paket travel memiliki halaman detail sendiri:
```
https://yoursite.com/travel/nama-paket-travel/
```

### Menampilkan di Homepage atau Page Lain

Gunakan shortcode atau block editor WordPress untuk menampilkan post type MW Travel.

Atau gunakan query loop manual di template theme:

```php
<?php
$args = array(
    'post_type' => 'mw_travel',
    'posts_per_page' => 6,
    'tax_query' => array(
        array(
            'taxonomy' => 'mw_travel_category',
            'field' => 'slug',
            'terms' => 'beach-island',
        ),
    ),
);

$travel_query = new WP_Query($args);

if ($travel_query->have_posts()) :
    while ($travel_query->have_posts()) : $travel_query->the_post();
        // Display travel post
        the_title();
        the_excerpt();
    endwhile;
    wp_reset_postdata();
endif;
?>
```

---

## 🛠️ Customisasi Template

### Override Template di Theme

Untuk customize tampilan, copy file template ke theme Anda:

1. **Copy file template**:
   ```
   /wp-content/plugins/mw-travel-plugin/templates/archive-mw_travel.php
   /wp-content/plugins/mw-travel-plugin/templates/single-mw_travel.php
   ```

2. **Paste ke theme**:
   ```
   /wp-content/themes/your-theme/archive-mw_travel.php
   /wp-content/themes/your-theme/single-mw_travel.php
   ```

3. **Edit sesuai kebutuhan**

### Template Tags yang Tersedia

Gunakan function berikut di template theme Anda:

```php
// Get data
$price = mw_travel_get_price();
$duration = mw_travel_get_duration();
$location = mw_travel_get_location();
$itinerary = mw_travel_get_itinerary();
$gallery = mw_travel_get_gallery();
$include = mw_travel_get_include();
$exclude = mw_travel_get_exclude();

// Display sections
mw_travel_display_details();
mw_travel_display_itinerary();
mw_travel_display_gallery();
mw_travel_display_include_exclude();
```

---

## 🎯 Tips & Best Practices

### 1. Optimasi Gambar
- Upload gambar dengan ukuran optimal (max 1920px width)
- Gunakan format WebP untuk performa lebih baik
- Compress gambar sebelum upload

### 2. SEO Friendly
- Gunakan judul yang deskriptif
- Isi excerpt dengan summary menarik
- Tambahkan alt text pada featured image
- Gunakan kategori yang relevan

### 3. Konten Berkualitas
- Buat itinerary yang detail dan jelas
- Gunakan bullet points untuk aktivitas
- Cantumkan harga yang jelas dan update
- Include durasi yang akurat

### 4. Gallery
- Upload minimal 5-10 gambar untuk setiap paket
- Gunakan gambar berkualitas tinggi
- Variasi gambar (destinasi, hotel, aktivitas, dll)

### 5. Include/Exclude
- Buat list yang comprehensive
- Jelaskan dengan detail apa saja yang termasuk
- Transparansi untuk yang tidak termasuk

---

## 🔧 Troubleshooting

### Rewrite Rules Tidak Berfungsi

Jika URL /travel/ tidak berfungsi:

1. Pergi ke **Settings > Permalinks**
2. Klik **Save Changes** (tidak perlu mengubah apapun)
3. Ini akan flush rewrite rules

### Gallery Tidak Muncul

Pastikan:
- Gambar sudah di-upload ke media library
- Browser cache sudah di-clear
- Theme support post thumbnails

### Template Tidak Sesuai

Jika tampilan tidak sesuai dengan Astra theme:
- Pastikan Astra theme sudah aktif
- Check container settings di Astra
- Override template di child theme jika perlu

---

## 📞 Support

Untuk pertanyaan dan support, hubungi:
- Website: https://mataramweb.com
- Email: support@mataramweb.com

---

## 📝 Changelog

### Version 1.0.0
- Initial release
- Custom Post Type MW Travel
- Itinerary builder with drag & drop
- Gallery management
- Include/Exclude lists
- Custom taxonomy
- Archive dan single templates
- Compatible dengan Astra Theme

---

**Selamat menggunakan MW Travel Plugin! 🚀**
