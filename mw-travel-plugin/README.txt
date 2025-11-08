=== MW Travel ===
Contributors: Mataram Web
Tags: travel, tour, custom post type, itinerary, travel agency
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin WordPress untuk Custom Post Type MW Travel dengan fitur itinerary, gallery, include/exclude untuk travel agents. Compatible dengan Astra Theme.

== Description ==

MW Travel (Mataram Web Travel) adalah plugin WordPress yang dirancang khusus untuk travel agents dan tour operators. Plugin ini menyediakan Custom Post Type yang lengkap dengan fitur-fitur:

= Fitur Utama =

* **Custom Post Type "MW Travel"** - Kelola paket travel dengan mudah
* **Detail Travel** - Harga, durasi, dan lokasi
* **Itinerary Builder** - Buat itinerary harian dengan drag & drop
* **Gallery** - Upload multiple images untuk setiap paket
* **Include/Exclude Lists** - Daftar fasilitas yang termasuk dan tidak termasuk
* **Custom Taxonomy** - Kategorisasi paket travel (Beach, Mountain, City Tour, dll)
* **Template Bawaan** - Archive dan single post template yang responsive
* **Compatible dengan Astra Theme** - Terintegrasi sempurna dengan Astra

= Cara Menggunakan =

1. Setelah plugin diaktifkan, akan muncul menu "MW Travel" di admin sidebar
2. Klik "Tambah Baru" untuk membuat paket travel baru
3. Isi detail travel: judul, deskripsi, harga, durasi, lokasi
4. Tambahkan itinerary dengan klik "Tambah Hari"
5. Upload gambar ke gallery
6. Isi list yang termasuk dan tidak termasuk
7. Pilih kategori travel
8. Publish!

= Template Tags =

Plugin ini menyediakan template tags yang dapat digunakan dalam theme:

* `mw_travel_get_price()` - Mendapatkan harga
* `mw_travel_get_duration()` - Mendapatkan durasi
* `mw_travel_get_location()` - Mendapatkan lokasi
* `mw_travel_get_itinerary()` - Mendapatkan array itinerary
* `mw_travel_get_gallery()` - Mendapatkan array gallery images
* `mw_travel_get_include()` - Mendapatkan array include items
* `mw_travel_get_exclude()` - Mendapatkan array exclude items
* `mw_travel_display_details()` - Menampilkan detail travel
* `mw_travel_display_itinerary()` - Menampilkan itinerary
* `mw_travel_display_gallery()` - Menampilkan gallery
* `mw_travel_display_include_exclude()` - Menampilkan include/exclude

== Installation ==

= Instalasi Otomatis =

1. Login ke WordPress Admin
2. Pergi ke Plugins > Add New
3. Upload file zip plugin
4. Klik "Install Now"
5. Aktifkan plugin

= Instalasi Manual =

1. Upload folder `mw-travel-plugin` ke direktori `/wp-content/plugins/`
2. Aktifkan plugin melalui menu 'Plugins' di WordPress
3. Mulai tambahkan paket travel dari menu "MW Travel"

= Konfigurasi =

Tidak ada konfigurasi khusus yang diperlukan. Plugin siap digunakan setelah aktivasi.

== Frequently Asked Questions ==

= Apakah plugin ini compatible dengan theme saya? =

Plugin ini dirancang untuk compatible dengan Astra Theme, tetapi juga dapat bekerja dengan theme WordPress lainnya. Template bawaan menggunakan struktur WordPress standar.

= Bagaimana cara mengubah tampilan template? =

Anda dapat meng-copy file template dari `mw-travel-plugin/templates/` ke folder theme Anda dan melakukan customisasi di sana.

= Apakah saya bisa menambah field custom? =

Ya, Anda dapat menambahkan custom fields dengan menggunakan hook WordPress yang tersedia.

= Bagaimana cara menampilkan paket travel di homepage? =

Gunakan query loop WordPress standar dengan post_type 'mw_travel', atau gunakan widget/block editor untuk menampilkan post type ini.

== Screenshots ==

1. Admin interface untuk menambah paket travel baru
2. Itinerary builder dengan drag & drop
3. Gallery uploader
4. Archive page menampilkan semua paket travel
5. Single travel page dengan detail lengkap

== Changelog ==

= 1.0.0 =
* Initial release
* Custom Post Type MW Travel
* Itinerary builder
* Gallery management
* Include/Exclude lists
* Custom taxonomy
* Archive dan single templates
* Compatible dengan Astra Theme

== Upgrade Notice ==

= 1.0.0 =
Rilis pertama plugin MW Travel.

== Additional Info ==

Plugin ini dibuat oleh Mataram Web untuk memudahkan travel agents dalam mengelola paket travel mereka di WordPress.

Untuk support dan informasi lebih lanjut, silakan kunjungi website kami.

== Credits ==

* Developed by: Mataram Web
* Compatible with: WordPress 5.0+, PHP 7.4+
* Tested with: Astra Theme

== Technical Details ==

**Custom Post Type Slug:** `mw_travel`
**Taxonomy Slug:** `mw_travel_category`
**Archive URL:** `yoursite.com/travel/`
**Category URL:** `yoursite.com/kategori-travel/category-name/`

**Meta Keys:**
* `_mw_travel_price` - Harga paket
* `_mw_travel_duration` - Durasi perjalanan
* `_mw_travel_location` - Lokasi tujuan
* `_mw_travel_itinerary` - Array itinerary
* `_mw_travel_gallery` - Comma-separated image IDs
* `_mw_travel_include` - Newline-separated include items
* `_mw_travel_exclude` - Newline-separated exclude items
