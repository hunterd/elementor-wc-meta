# Changelog

## [1.0.1] - 2024-01-XX - Translation Fix 🌐

### 🐛 Corrections
- **Translation Loading** : Correction de l'erreur "textdomain loaded too early"
- **I18nServiceProvider** : Nouveau service provider pour gérer les traductions
- **Hook Timing** : Chargement du textdomain au hook 'init' au lieu de 'plugins_loaded'
- **MetaFieldsManager** : Initialisation différée des champs avec traductions

### 🔧 Technique
- Ajout de `I18nServiceProvider` pour le chargement des traductions
- Modification de `MetaFieldsManager::initializeMetaFields()` vers hook 'init'
- Ordre des service providers optimisé (I18n en premier)

## [1.0.0] - 2024-01-XX - Production Release 🚀

### ✨ Nouveautés
- **Widget Elementor "WC Meta Field"** : Affichage granulaire des métadonnées WooCommerce
- **Support de 10+ types de champs** : Catégories, tags, prix, SKU, stock, dimensions, poids, etc.
- **Contrôles avancés** : Sélection produit, type de champ, style d'affichage
- **Architecture moderne** : Service Providers, conteneur IoC, PSR-4 autoloading
- **Build système** : Vite.js avec minification et optimisations production

### 🛡️ Compatibilité
- **HPOS (High-Performance Order Storage)** : Compatibilité complète avec WooCommerce 8.0+
- **Custom Order Tables** : Support natif
- **WooCommerce Blocks** : Compatible Cart & Checkout Blocks
- **Product Block Editor** : Intégration complète

### 🔧 Technique
- **Service Providers** :
  - `AssetServiceProvider` : Gestion des assets Vite.js
  - `ElementorServiceProvider` : Intégration widgets Elementor
  - `HposCompatibilityServiceProvider` : Compatibilité HPOS
  - `WooCommerceServiceProvider` : Intégration WooCommerce

### 📋 Prérequis
- **PHP** : 8.0+
- **WordPress** : 6.0+
- **Elementor** : 3.0+
- **WooCommerce** : 7.0+
- **Node.js** : 18+ (pour le développement)
- **Composer** : 2.0+ (pour les dépendances)

### 🚀 Déploiement
- **Production ready** : Scripts de build automatisés
- **Validation** : Tests de compatibilité HPOS inclus
- **Documentation** : Guide complet d'installation et utilisation
