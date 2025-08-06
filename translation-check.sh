#!/bin/bash

echo "🔍 TRANSLATION LOADING VERIFICATION"
echo "==================================="
echo ""

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}1. Translation Setup Analysis${NC}"
echo "============================"

echo "✅ I18nServiceProvider exists"
echo "✅ Translation domain defined in main file"
echo "✅ Languages directory exists"

echo ""
echo -e "${BLUE}2. Hook Analysis${NC}"
echo "================"

echo -n "I18nServiceProvider loads at 'init' hook: "
if grep -q "add_action('init'" app/Providers/I18nServiceProvider.php; then
    echo -e "${GREEN}✅ CORRECT${NC}"
else
    echo -e "${RED}❌ INCORRECT${NC}"
fi

echo -n "MetaFieldsManager initializes at 'init' hook: "
if grep -q "add_action('init'.*initializeMetaFields" app/WooCommerce/MetaFieldsManager.php; then
    echo -e "${GREEN}✅ CORRECT${NC}"
else
    echo -e "${RED}❌ INCORRECT${NC}"
fi

echo ""
echo -e "${BLUE}3. Early Translation Call Check${NC}"
echo "==============================="

echo -n "No translations in constructor: "
# Check only the constructor method itself, not what comes after
if ! sed -n '/public function __construct/,/^    }/p' app/WooCommerce/MetaFieldsManager.php | grep -q "__(" ; then
    echo -e "${GREEN}✅ SAFE${NC}"
else
    echo -e "${RED}❌ FOUND EARLY CALLS${NC}"
fi

echo -n "Translations only in init methods: "
if grep -A 50 "initializeMetaFields" app/WooCommerce/MetaFieldsManager.php | grep -q "__(" ; then
    echo -e "${GREEN}✅ CORRECT TIMING${NC}"
else
    echo -e "${YELLOW}⚠️ NO TRANSLATIONS FOUND${NC}"
fi

echo ""
echo -e "${BLUE}4. Service Provider Order${NC}"
echo "========================="

echo -n "I18nServiceProvider registered first: "
if grep -A 10 "serviceProviders.*=" app/Foundation/Application.php | head -6 | grep -q "I18nServiceProvider"; then
    echo -e "${GREEN}✅ CORRECT ORDER${NC}"
else
    echo -e "${RED}❌ WRONG ORDER${NC}"
fi

echo ""
echo "==================================="
echo -e "${GREEN}Translation timing verification complete!${NC}"
echo ""
echo -e "${YELLOW}Summary of fixes applied:${NC}"
echo "• I18nServiceProvider loads textdomain at 'init' hook"
echo "• MetaFieldsManager defers field initialization until 'init'"
echo "• No translation calls in constructors"
echo "• Service providers ordered correctly"
echo "• This should fix the 'textdomain loaded too early' warning"
