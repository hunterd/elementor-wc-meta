#!/bin/bash

echo "🔍 VITE CONFIGURATION VERIFICATION"
echo "=================================="
echo ""

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}📁 Build Directory Structure${NC}"
echo "============================"

if [ -d "dist" ]; then
    echo -e "${GREEN}✅ /dist/ directory exists${NC}"
    
    if [ -f "dist/.vite/manifest.json" ]; then
        echo -e "${GREEN}✅ Vite manifest found${NC}"
    else
        echo -e "${RED}❌ Vite manifest missing${NC}"
    fi
    
    if [ -d "dist/css" ]; then
        echo -e "${GREEN}✅ CSS directory exists${NC}"
        CSS_COUNT=$(find dist/css -name "*.css" | wc -l)
        echo "   → $CSS_COUNT CSS files found"
    else
        echo -e "${RED}❌ CSS directory missing${NC}"
    fi
    
    if [ -d "dist/js" ]; then
        echo -e "${GREEN}✅ JS directory exists${NC}"
        JS_COUNT=$(find dist/js -name "*.js" | wc -l)
        echo "   → $JS_COUNT JS files found"
    else
        echo -e "${RED}❌ JS directory missing${NC}"
    fi
    
else
    echo -e "${RED}❌ /dist/ directory missing${NC}"
fi

echo ""
echo -e "${BLUE}⚙️ Vite Configuration${NC}"
echo "===================="

if grep -q "publicDir: false" vite.config.js; then
    echo -e "${GREEN}✅ publicDir correctly disabled${NC}"
else
    echo -e "${YELLOW}⚠️ publicDir setting not found${NC}"
fi

if grep -q "outDir: 'dist'" vite.config.js; then
    echo -e "${GREEN}✅ outDir correctly set to 'dist'${NC}"
else
    echo -e "${RED}❌ outDir not correctly configured${NC}"
fi

echo ""
echo -e "${BLUE}🚫 Cleanup Check${NC}"
echo "================"

if [ -d "public/dist" ]; then
    echo -e "${RED}❌ Old public/dist directory still exists${NC}"
    echo "   → Run: rm -rf public/dist"
else
    echo -e "${GREEN}✅ Old public/dist directory cleaned up${NC}"
fi

echo ""
echo -e "${BLUE}📋 Asset Manager Paths${NC}"
echo "======================"

if grep -q "dist/\.vite/manifest\.json" app/Assets/AssetManager.php; then
    echo -e "${GREEN}✅ AssetManager uses correct manifest path${NC}"
else
    echo -e "${RED}❌ AssetManager manifest path needs update${NC}"
fi

if grep -q "dist/" app/Assets/AssetManager.php; then
    echo -e "${GREEN}✅ AssetManager uses correct asset paths${NC}"
else
    echo -e "${RED}❌ AssetManager asset paths need update${NC}"
fi

echo ""
echo "=================================="
echo -e "${GREEN}Configuration verification complete!${NC}"
