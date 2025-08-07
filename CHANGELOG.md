# Changelog

## [1.1.1] - 2024-01-XX - Editor Fix 🔧

### 🐛 Correction Critique
- **Liste de méta-champs vide** : Correction du problème où aucun méta-champ n'apparaissait dans l'éditeur Elementor
- **Initialisation forcée** : Les méta-champs sont maintenant initialisés automatiquement même dans le contexte de l'éditeur
- **Fallback sans traduction** : Méthode de secours utilisant les labels anglais si les fonctions de traduction ne sont pas disponibles
- **Debug amélioré** : Ajout de logs pour diagnostiquer les problèmes d'initialisation

### 🔧 Améliorations Techniques
- **MetaFieldsManager::getMetaFields()** : Initialisation forcée si le tableau est vide
- **MetaFieldsManager::getMetaField()** : Même logique d'initialisation forcée
- **initializeMetaFieldsWithoutTranslation()** : Nouvelle méthode de fallback
- **WcMetaWidget** : Gestion d'erreur avec message informatif si aucun champ trouvé

### 📚 Documentation
- **TROUBLESHOOTING.md** : Guide de dépannage complet
- Tests de validation pour confirmer la correction

## [1.1.0] - 2024-01-XX - Custom Attributes Support 🎯

### ✨ Nouvelles Fonctionnalités
- **Custom Attribute Field** : Nouveau type de champ pour afficher n'importe quel attribut ou méta-champ custom
- **Attribute Key Control** : Contrôle de saisie pour spécifier la clé d'attribut (ex: `pa_color`, `_custom_field`)
- **Support Complet des Attributs** : Gestion des attributs produits taxonomiques et non-taxonomiques
- **Meta Fields Custom** : Support des champs méta personnalisés de plugins tiers

### 🔧 Améliorations Techniques
- **MetaFieldsManager** : Ajout du type `custom_attribute` et méthodes `getCustomAttributeValue()`, `getTaxonomyAttributeValue()`
- **WcMetaWidget** : Nouveau contrôle `custom_attribute_key` avec validation conditionnelle
- **Helper Methods** : `getAvailableProductAttributes()` et `getCommonCustomMetaFields()` pour la référence

### 📚 Documentation
- **CUSTOM_ATTRIBUTES.md** : Guide complet d'utilisation des attributs custom
- **README.md** : Mise à jour avec les nouvelles fonctionnalités
- **Tests** : Ajout de tests unitaires pour les attributs custom

### 🎨 Interface Utilisateur
- **Contrôle Conditionnel** : Le champ "Attribute Key" s'affiche uniquement pour le type "Custom Attribute"
- **Placeholder Informatif** : Guide l'utilisateur avec des exemples (pa_color, _custom_field)
- **Preview Enhanced** : Support du type custom_attribute dans la prévisualisation Elementor

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
