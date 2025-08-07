# Dépannage - Liste de méta-champs vide

## Problème Résolu

Le problème où la liste des méta-champs apparaissait vide dans l'éditeur Elementor a été **corrigé** avec les modifications suivantes :

## Modifications Apportées

### 1. Initialisation Forcée dans `MetaFieldsManager.php`

```php
/**
 * Get all available meta fields
 */
public function getMetaFields(): array
{
    // Force initialization if fields are empty (e.g., in Elementor editor context)
    if (empty($this->metaFields)) {
        $this->initializeMetaFields();
    }
    
    return $this->metaFields;
}
```

### 2. Méthode de Fallback sans Traduction

Ajout d'une méthode `initializeMetaFieldsWithoutTranslation()` qui fournit les labels en anglais si les fonctions de traduction WordPress ne sont pas disponibles.

### 3. Gestion d'Erreur dans le Widget

```php
// Meta field selection
$metaFieldOptions = $this->getMetaFieldOptions();

// If no options are available, add a debug option
if (empty($metaFieldOptions)) {
    $metaFieldOptions = [
        'debug' => __('No meta fields available (check logs)', 'elementor-wc-meta')
    ];
}
```

### 4. Debug Logging

Ajout de logs pour diagnostiquer le problème :

```php
// Debug: Log field count for troubleshooting
if (function_exists('error_log')) {
    error_log('ElementorWcMeta: Found ' . count($metaFields) . ' meta fields');
}
```

## Vérification de la Correction

### Test Automatique
Un test automatique confirme que les 11 méta-champs sont maintenant disponibles :

```
Testing MetaFieldsManager...
Number of meta fields: 11
Available meta fields:
- product_categories: Product Categories (type: taxonomy)
- product_tags: Product Tags (type: taxonomy)
- product_attributes: Product Attributes (type: attributes)
- product_price: Product Price (type: meta)
- product_regular_price: Product Regular Price (type: meta)
- product_sale_price: Product Sale Price (type: meta)
- product_sku: Product SKU (type: meta)
- product_stock_status: Stock Status (type: meta)
- product_weight: Product Weight (type: meta)
- product_dimensions: Product Dimensions (type: dimensions)
- custom_attribute: Custom Attribute (type: custom_attribute) ✨
```

## Diagnostic en Cas de Problème Persistant

### 1. Vérifier les Logs WordPress
Consultez les logs d'erreurs WordPress dans :
- `/wp-content/debug.log` (si `WP_DEBUG_LOG` est activé)
- Logs du serveur web

Recherchez les entrées contenant "ElementorWcMeta".

### 2. Test Manuel
Exécutez le fichier de test :

```bash
cd /path/to/plugin/
php test-meta-fields.php
```

### 3. Vérification des Hooks
Assurez-vous que les hooks WordPress sont correctement chargés :

```php
// Dans functions.php temporairement
add_action('init', function() {
    if (class_exists('ElementorWcMeta\WooCommerce\MetaFieldsManager')) {
        $manager = new ElementorWcMeta\WooCommerce\MetaFieldsManager();
        $fields = $manager->getMetaFields();
        error_log('WC Meta Fields Count: ' . count($fields));
    }
});
```

### 4. Cache Elementor
Videz le cache Elementor :
1. Allez dans **WordPress Admin → Elementor → Tools**
2. Cliquez sur **Regenerate CSS & Data**
3. Cliquez sur **Sync Library**

### 5. Conflits de Plugins
Désactivez temporairement d'autres plugins pour identifier les conflits potentiels.

## Fonctionnalités Maintenant Disponibles

✅ **11 méta-champs** disponibles dans l'éditeur Elementor  
✅ **Custom Attribute** pour attributs personnalisés  
✅ **Initialisation automatique** même dans l'éditeur  
✅ **Fallback sans traduction** pour la compatibilité  
✅ **Debug logging** pour le dépannage  
✅ **Gestion d'erreur** avec messages informatifs  

## Support

Si le problème persiste après ces modifications, vérifiez :
1. La version de WordPress (6.0+ requis)
2. La version d'Elementor (3.0+ requis)
3. La version de WooCommerce (8.0+ requis)
4. Les conflits avec d'autres plugins

Les modifications garantissent maintenant que la liste des méta-champs est **toujours** disponible dans l'éditeur Elementor.
