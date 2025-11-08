# Gallery Upload Fix - Testing Guide

## 🔧 Perbaikan yang Dilakukan:

### 1. Updated admin.js
**Perubahan:**
- ✅ Added console.log debugging
- ✅ Added wp.media availability check
- ✅ Added error handling with try-catch
- ✅ Better thumbnail URL detection
- ✅ Library type filter (images only)
- ✅ Improved ID filtering (remove empty strings)
- ✅ Better error messages

### 2. Updated mw-travel.php
**Perubahan:**
- ✅ Added 'media-upload' dependency
- ✅ Added 'media-views' dependency
- ✅ Added media text to localization
- ✅ Better comments for clarity

---

## 🧪 Cara Testing Gallery Upload:

### Step 1: Install Plugin
```
1. Delete plugin lama
2. Upload plugin baru
3. Activate
4. Settings > Permalinks > Save
```

### Step 2: Test Gallery Upload
```
1. MW Travel > Tambah Baru
2. Scroll ke meta box "Gallery"
3. Klik tombol "Tambah Gambar"
4. Media Library harus terbuka
```

### Step 3: Verify Upload Works
```
1. Select beberapa gambar (multiple)
2. Klik "Tambahkan ke Gallery"
3. Gambar harus muncul di grid
4. Each image harus punya X button
```

### Step 4: Test Remove
```
1. Klik X pada salah satu gambar
2. Gambar harus hilang dengan fade animation
3. Hidden field harus update
```

### Step 5: Save & Verify
```
1. Publish/Update post
2. Refresh page
3. Gallery images harus masih ada
4. Order harus sama
```

---

## 🐛 Troubleshooting:

### Issue 1: Button Tidak Respond
**Check:**
- Browser console (F12)
- Look for error messages
- Verify jQuery loaded

**Expected Console Output:**
```
MW Travel Admin JS loaded
wp.media available: true
Gallery button clicked
Gallery frame created
Gallery frame opened
```

### Issue 2: Media Library Tidak Muncul
**Possible Causes:**
- wp.media not loaded
- JavaScript conflict
- Theme issues

**Solution:**
```javascript
// Check in browser console:
console.log(typeof wp);
console.log(typeof wp.media);

// Should output:
// "object"
// "function"
```

### Issue 3: Gambar Tidak Muncul Setelah Select
**Check Console For:**
```
Images selected
Processing attachment: [ID]
Added image to gallery: [ID]
Updated gallery IDs: [1,2,3,4]
```

**If no output:**
- Selection callback not firing
- JavaScript error
- Check browser console

### Issue 4: Gambar Hilang Setelah Save
**Possible Causes:**
- Hidden field not updating
- Nonce verification issue
- Save function issue

**Check:**
```
1. Before save, inspect hidden field:
   - Right click > Inspect
   - Find: <input id="mw_travel_gallery">
   - Value should be: "1,2,3,4"

2. After save, check post meta:
   - Via phpMyAdmin
   - Table: wp_postmeta
   - meta_key: _mw_travel_gallery
   - Should have comma-separated IDs
```

---

## ✅ Debug Checklist:

### Before Testing:
- [ ] Plugin version: 2.0.0 (updated)
- [ ] WordPress version: 5.0+
- [ ] Browser: Chrome/Firefox (latest)
- [ ] No JavaScript errors in console
- [ ] Theme: Astra or similar

### During Testing:
- [ ] "Tambah Gambar" button visible
- [ ] Button clickable
- [ ] Media Library opens
- [ ] Can select multiple images
- [ ] Images appear in grid
- [ ] X button works
- [ ] Save works
- [ ] Images persist after save

### After Save:
- [ ] Refresh page
- [ ] Gallery still there
- [ ] Can add more images
- [ ] Can remove images
- [ ] Frontend carousel works

---

## 📊 Expected Behavior:

### Gallery Meta Box:
```
┌─────────────────────────────────┐
│ Gallery                         │
├─────────────────────────────────┤
│                                 │
│ [IMG] [IMG] [IMG] [IMG]         │
│   X     X     X     X           │
│                                 │
│ [+ Tambah Gambar]               │
│                                 │
│ Upload atau pilih gambar dari   │
│ media library                   │
└─────────────────────────────────┘
```

### When Button Clicked:
```
┌────────────────────────────────┐
│ Media Library Modal            │
├────────────────────────────────┤
│                                │
│ Upload Files | Media Library   │
│                                │
│ [IMG] [IMG] [IMG] [IMG]        │
│ [IMG] [IMG] [IMG] [IMG]        │
│                                │
│          [Select 3 items]      │
│   [Tambahkan ke Gallery]       │
└────────────────────────────────┘
```

### After Selection:
```
Images appear in grid with:
- Thumbnail preview
- Data-id attribute
- Remove button (X)
- Hidden field updated
```

---

## 🔍 Manual Debugging:

### Enable Debug Mode:
Edit wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', true);
```

### Check JavaScript Loading:
View page source, search for:
```html
<script src="...mw-travel-plugin/assets/js/admin.js"></script>
```

Should be loaded AFTER:
```html
<script src=".../wp-includes/js/media-upload.min.js"></script>
<script src=".../wp-includes/js/media-views.min.js"></script>
```

### Check wp.media:
In browser console:
```javascript
// Test wp.media availability
console.log(wp.media);

// Try creating frame manually
var frame = wp.media({
    title: 'Test',
    multiple: true
});
frame.open();
```

---

## 💡 Common Fixes:

### Fix 1: Clear Cache
```
1. Browser cache: Ctrl+Shift+Delete
2. WordPress cache: Via plugin
3. Server cache: Via cPanel
4. CDN cache: If using Cloudflare
```

### Fix 2: Disable Conflicting Plugins
```
Temporary deactivate:
- Other gallery plugins
- Page builders
- Optimization plugins
```

### Fix 3: Switch to Default Theme
```
Test with Twenty Twenty-Four:
1. Activate default theme
2. Test gallery upload
3. If works: theme issue
4. If not: plugin issue
```

### Fix 4: Re-enqueue Scripts
```php
// Add to theme functions.php temporarily
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_media();
}, 999);
```

---

## 📞 Still Having Issues?

### Collect This Info:
1. WordPress version
2. PHP version
3. Browser console errors (screenshot)
4. Network tab (check if JS loaded)
5. Any JavaScript errors
6. Active plugins list
7. Current theme

### Test in Safe Mode:
1. Disable all plugins except MW Travel
2. Switch to default theme
3. Test again
4. Re-enable one by one to find conflict

---

## ✅ Success Indicators:

**Working Correctly When:**
- ✅ Button opens Media Library
- ✅ Can select multiple images
- ✅ Images appear in grid immediately
- ✅ X button removes images
- ✅ Save persists images
- ✅ No console errors
- ✅ Frontend carousel shows images

---

**Version:** 2.0.0 (Gallery Upload Fixed)  
**File:** /app/mw-travel-plugin.zip (42KB)  
**Last Update:** Nov 8, 2024
