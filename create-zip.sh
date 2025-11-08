#!/bin/bash

# Script untuk membuat ZIP file plugin MW Travel
# Usage: bash create-zip.sh

echo "========================================="
echo "MW Travel Plugin - Create ZIP"
echo "========================================="
echo ""

# Navigate to /app directory
cd /app

# Remove old zip if exists
if [ -f "mw-travel-plugin.zip" ]; then
    echo "Menghapus ZIP lama..."
    rm mw-travel-plugin.zip
fi

# Create new zip
echo "Membuat ZIP file baru..."
zip -r mw-travel-plugin.zip mw-travel-plugin/ \
    -x "*.git*" \
    -x "*node_modules*" \
    -x "*.DS_Store*" \
    -x "*__MACOSX*"

# Check if zip was created successfully
if [ -f "mw-travel-plugin.zip" ]; then
    echo ""
    echo "✅ SUCCESS!"
    echo "========================================="
    echo "ZIP file berhasil dibuat:"
    echo "Location: /app/mw-travel-plugin.zip"
    echo ""
    echo "Size: $(du -h mw-travel-plugin.zip | cut -f1)"
    echo "========================================="
    echo ""
    echo "Langkah selanjutnya:"
    echo "1. Download file mw-travel-plugin.zip"
    echo "2. Login ke WordPress Admin"
    echo "3. Pergi ke Plugins > Add New > Upload Plugin"
    echo "4. Upload file ZIP dan aktifkan"
    echo ""
else
    echo ""
    echo "❌ ERROR: Gagal membuat ZIP file"
    echo ""
fi
