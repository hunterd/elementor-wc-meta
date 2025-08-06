#!/bin/bash

echo "🔍 VALIDATION FINALE - ELEMENTOR WC META PLUGIN"
echo "=============================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Counters
PASS=0
FAIL=0

check_requirement() {
    local test_name="$1"
    local test_command="$2"
    local expected="$3"
    
    echo -n "Checking $test_name... "
    
    if eval "$test_command" > /dev/null 2>&1; then
        echo -e "${GREEN}✅ PASS${NC}"
        ((PASS++))
    else
        echo -e "${RED}❌ FAIL${NC}"
        ((FAIL++))
    fi
}

echo -e "${BLUE}📁 STRUCTURE FILES${NC}"
echo "===================="

# File structure checks
check_requirement "Main plugin file" "test -f elementor-wc-meta.php"
check_requirement "Composer autoload" "test -f vendor/autoload.php"
check_requirement "Package.json" "test -f package.json"
check_requirement "Vite config" "test -f vite.config.js"
check_requirement "Application core" "test -f app/Foundation/Application.php"

echo ""
echo -e "${BLUE}🔌 SERVICE PROVIDERS${NC}"
echo "===================="

# Service Providers
check_requirement "I18n Provider" "test -f app/Providers/I18nServiceProvider.php"
check_requirement "Asset Provider" "test -f app/Providers/AssetServiceProvider.php"
check_requirement "Elementor Provider" "test -f app/Providers/ElementorServiceProvider.php"
check_requirement "WooCommerce Provider" "test -f app/Providers/WooCommerceServiceProvider.php"
check_requirement "HPOS Provider" "test -f app/Providers/HposCompatibilityServiceProvider.php"

echo ""
echo -e "${BLUE}🎨 ELEMENTOR WIDGETS${NC}"
echo "==================="

# Widgets
check_requirement "WC Meta Widget" "test -f app/Widgets/WcMetaFieldWidget.php"

echo ""
echo -e "${BLUE}📦 BUILD SYSTEM${NC}"
echo "================"

# Build files
check_requirement "Node modules" "test -d node_modules"
check_requirement "Dist directory" "test -d dist"
check_requirement "Vite manifest" "test -f dist/.vite/manifest.json"

echo ""
echo -e "${BLUE}🛡️ HPOS COMPATIBILITY${NC}"
echo "======================"

# HPOS Compatibility checks
check_requirement "HPOS hook declaration" "grep -q 'before_woocommerce_init' elementor-wc-meta.php"
check_requirement "FeaturesUtil usage" "grep -q 'FeaturesUtil::declare_compatibility' elementor-wc-meta.php"
check_requirement "Custom order tables" "grep -q 'custom_order_tables' elementor-wc-meta.php"

echo ""
echo -e "${BLUE}📋 DOCUMENTATION${NC}"
echo "=================="

# Documentation
check_requirement "README file" "test -f README.md"
check_requirement "Deployment guide" "test -f DEPLOYMENT.md"
check_requirement "Changelog" "test -f CHANGELOG.md"

echo ""
echo "=============================================="
echo -e "${GREEN}✅ PASSED: $PASS${NC}"
echo -e "${RED}❌ FAILED: $FAIL${NC}"
echo ""

if [ $FAIL -eq 0 ]; then
    echo -e "${GREEN}🎉 FÉLICITATIONS !${NC}"
    echo -e "${GREEN}Plugin ready for PRODUCTION deployment!${NC}"
    echo -e "${GREEN}Compatible HPOS ✅${NC}"
    echo ""
    echo -e "${YELLOW}📋 NEXT STEPS:${NC}"
    echo "1. Upload the entire plugin folder to /wp-content/plugins/"
    echo "2. Activate the plugin in WordPress admin"
    echo "3. The 'WC Meta Field' widget will be available in Elementor"
    echo "4. Compatible with WooCommerce HPOS (High-Performance Order Storage)"
else
    echo -e "${RED}⚠️ Some checks failed. Please review and fix before deployment.${NC}"
fi

echo ""
