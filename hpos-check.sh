#!/bin/bash

echo "🔍 Vérification de la compatibilité HPOS pour Elementor WC Meta..."
echo ""

# Vérifier les déclarations de compatibilité dans le fichier principal
echo "📋 Vérification du fichier principal..."
if grep -q "before_woocommerce_init" elementor-wc-meta.php; then
    echo "✅ Hook before_woocommerce_init trouvé"
else
    echo "❌ Hook before_woocommerce_init manquant"
fi

if grep -q "FeaturesUtil::declare_compatibility" elementor-wc-meta.php; then
    echo "✅ Déclaration de compatibilité FeaturesUtil trouvée"
else
    echo "❌ Déclaration de compatibilité FeaturesUtil manquante"
fi

if grep -q "custom_order_tables" elementor-wc-meta.php; then
    echo "✅ Compatibilité custom_order_tables déclarée"
else
    echo "❌ Compatibilité custom_order_tables manquante"
fi

if grep -q "Requires Plugins: woocommerce" elementor-wc-meta.php; then
    echo "✅ Dépendance WooCommerce déclarée dans l'en-tête"
else
    echo "⚠️  Dépendance WooCommerce devrait être déclarée dans l'en-tête"
fi

# Vérifier la présence du service provider HPOS
echo ""
echo "📋 Vérification du Service Provider HPOS..."
if [ -f "app/Providers/HposCompatibilityServiceProvider.php" ]; then
    echo "✅ HposCompatibilityServiceProvider présent"
else
    echo "❌ HposCompatibilityServiceProvider manquant"
fi

# Vérifier l'enregistrement du service provider
echo ""
echo "📋 Vérification de l'enregistrement du Service Provider..."
if grep -q "HposCompatibilityServiceProvider" app/Foundation/Application.php; then
    echo "✅ HposCompatibilityServiceProvider enregistré dans Application.php"
else
    echo "❌ HposCompatibilityServiceProvider non enregistré dans Application.php"
fi

# Vérifier les assets compilés
echo ""
echo "📋 Vérification des assets..."
if [ -f "public/dist/.vite/manifest.json" ]; then
    echo "✅ Manifest Vite présent"
else
    echo "❌ Manifest Vite manquant - exécuter 'npm run build'"
fi

if [ -d "public/dist/css" ] && [ "$(ls -A public/dist/css 2>/dev/null)" ]; then
    echo "✅ Fichiers CSS compilés présents"
else
    echo "❌ Fichiers CSS manquants"
fi

if [ -d "public/dist/js" ] && [ "$(ls -A public/dist/js 2>/dev/null)" ]; then
    echo "✅ Fichiers JavaScript compilés présents"
else
    echo "❌ Fichiers JavaScript manquants"
fi

# Vérifier l'autoloader Composer
echo ""
echo "📋 Vérification de Composer..."
if [ -f "vendor/autoload.php" ]; then
    echo "✅ Autoloader Composer présent"
else
    echo "❌ Autoloader Composer manquant - exécuter 'composer install'"
fi

echo ""
echo "=========================================="
echo "📊 RÉSUMÉ DE COMPATIBILITÉ HPOS"
echo "=========================================="

# Compter les succès et échecs
success_count=$(grep -c "✅" <<< "$(bash hpos-check.sh 2>&1)" || echo "0")
error_count=$(grep -c "❌" <<< "$(bash hpos-check.sh 2>&1)" || echo "0")

if [ "$error_count" -eq 0 ]; then
    echo "🎉 EXCELLENT ! Plugin entièrement compatible HPOS"
    echo "✅ Votre plugin peut être déployé avec WooCommerce HPOS activé"
else
    echo "⚠️  ATTENTION : $error_count problème(s) détecté(s)"
    echo "❌ Corrigez les erreurs ci-dessus avant d'utiliser avec HPOS"
fi

echo "=========================================="
