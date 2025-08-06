#!/bin/bash

# Production Build Script for Elementor WC Meta Plugin
# This script prepares the plugin for production deployment

set -e

echo "🚀 Preparing Elementor WC Meta for production..."

# Check if we're in the right directory
if [ ! -f "elementor-wc-meta.php" ]; then
    echo "❌ Error: Please run this script from the plugin root directory"
    exit 1
fi

# 1. Clean previous builds
echo "🧹 Cleaning previous builds..."
rm -rf public/dist/*
rm -rf vendor/
rm -rf node_modules/

# 2. Install PHP dependencies (production only)
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm ci --only=production

# 4. Build assets
echo "🔨 Building production assets..."
npm run build

# 5. Create production package
echo "📋 Creating production package..."
PLUGIN_NAME="elementor-wc-meta"
VERSION=$(grep "Version:" elementor-wc-meta.php | sed 's/.*Version: *\([0-9\.]*\).*/\1/')
PACKAGE_NAME="${PLUGIN_NAME}-${VERSION}"

# Create temporary directory for packaging
mkdir -p "../${PACKAGE_NAME}"

# Copy necessary files for production
echo "📁 Copying production files..."
rsync -av \
    --exclude='.git*' \
    --exclude='node_modules' \
    --exclude='tests' \
    --exclude='*.md' \
    --exclude='package*.json' \
    --exclude='vite.config.js' \
    --exclude='phpstan.neon' \
    --exclude='resources' \
    --exclude='.vscode' \
    --exclude='composer.lock' \
    ./ "../${PACKAGE_NAME}/"

# 6. Validate production package
echo "✅ Validating production package..."
cd "../${PACKAGE_NAME}"

# Check critical files
REQUIRED_FILES=(
    "elementor-wc-meta.php"
    "composer.json"
    "app/Foundation/Application.php"
    "vendor/autoload.php"
    "public/dist/.vite/manifest.json"
)

for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        echo "❌ Missing required file: $file"
        exit 1
    fi
done

# Check if assets are built
if [ ! -d "public/dist/css" ] || [ ! -d "public/dist/js" ]; then
    echo "❌ Assets not properly built"
    exit 1
fi

# 7. Create ZIP package
echo "📦 Creating ZIP package..."
cd ..
zip -r "${PACKAGE_NAME}.zip" "${PACKAGE_NAME}" -x "*.DS_Store" "*/Thumbs.db"

# 8. Cleanup
rm -rf "${PACKAGE_NAME}"

echo "✅ Production package created: ${PACKAGE_NAME}.zip"
echo ""
echo "📋 Production checklist completed:"
echo "   ✅ PHP dependencies installed (production only)"
echo "   ✅ Assets built and optimized"
echo "   ✅ Development files excluded"
echo "   ✅ Package validated"
echo "   ✅ ZIP archive created"
echo ""
echo "🚀 Ready for deployment!"

# Return to original directory
cd "${PLUGIN_NAME}"
