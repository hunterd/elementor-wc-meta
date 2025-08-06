/**
 * Elementor Editor JavaScript for WC Meta plugin
 */

(function($) {
    'use strict';

    // Editor functionality
    const ElementorWcMetaEditor = {
        
        init() {
            // Initialize when Elementor is loaded
            if (typeof elementor !== 'undefined') {
                this.bindEvents();
                this.initPanelView();
            } else {
                // Wait for Elementor to load
                $(window).on('elementor:init', () => {
                    this.bindEvents();
                    this.initPanelView();
                });
            }
        },
        
        bindEvents() {
            // Listen to widget control changes
            elementor.hooks.addAction('panel/open_editor/widget/wc-meta', this.onWidgetEdit.bind(this));
            
            // Listen to preview updates
            elementor.hooks.addAction('panel/open_editor/widget', this.onAnyWidgetEdit.bind(this));
        },
        
        initPanelView() {
            // Extend panel view for custom functionality
            if (elementor.modules && elementor.modules.controls) {
                this.initCustomControls();
            }
        },
        
        onWidgetEdit(panel, model, view) {
            // Handle when WC Meta widget is being edited
            this.updateMetaFieldOptions(view);
            this.bindControlEvents(view);
        },
        
        onAnyWidgetEdit(panel, model, view) {
            // Handle any widget edit to check for WC Meta widget
            if (model.get('widgetType') === 'wc-meta') {
                this.onWidgetEdit(panel, model, view);
            }
        },
        
        updateMetaFieldOptions(view) {
            // Update meta field options based on current context
            const metaFieldControl = view.children.findByIndex('meta_field');
            
            if (metaFieldControl) {
                // You can dynamically update options here if needed
                // For example, based on current product type or user permissions
                this.refreshMetaFieldControl(metaFieldControl);
            }
        },
        
        refreshMetaFieldControl(control) {
            // Refresh the meta field control options
            const $select = control.$el.find('select');
            
            // Add loading state
            $select.addClass('elementor-control-loading');
            
            // Simulate async loading (replace with actual AJAX if needed)
            setTimeout(() => {
                $select.removeClass('elementor-control-loading');
                
                // Trigger change to update dependent controls
                $select.trigger('change');
            }, 100);
        },
        
        bindControlEvents(view) {
            // Bind events for control interactions
            this.bindMetaFieldChange(view);
            this.bindLabelToggle(view);
        },
        
        bindMetaFieldChange(view) {
            const metaFieldControl = view.children.findByIndex('meta_field');
            const limitControl = view.children.findByIndex('limit');
            const separatorControl = view.children.findByIndex('separator');
            
            if (metaFieldControl) {
                metaFieldControl.ui.select.on('change', () => {
                    const selectedField = metaFieldControl.getControlValue();
                    
                    // Update visibility of dependent controls
                    this.updateDependentControls(view, selectedField);
                    
                    // Update preview immediately
                    view.model.renderRemoteServer();
                });
            }
        },
        
        bindLabelToggle(view) {
            const showLabelControl = view.children.findByIndex('show_label');
            const customLabelControl = view.children.findByIndex('custom_label');
            
            if (showLabelControl && customLabelControl) {
                showLabelControl.ui.input.on('change', () => {
                    const showLabel = showLabelControl.getControlValue();
                    
                    // Toggle custom label visibility
                    if (showLabel === 'yes') {
                        customLabelControl.$el.show();
                    } else {
                        customLabelControl.$el.hide();
                    }
                });
            }
        },
        
        updateDependentControls(view, selectedField) {
            // Fields that don't support limits
            const fieldsWithoutLimits = [
                'product_price',
                'product_regular_price', 
                'product_sale_price',
                'product_sku',
                'product_stock_status',
                'product_weight',
                'product_dimensions'
            ];
            
            // Fields that support separators
            const fieldsWithSeparators = [
                'product_categories',
                'product_tags',
                'product_attributes'
            ];
            
            const limitControl = view.children.findByIndex('limit');
            const separatorControl = view.children.findByIndex('separator');
            
            // Show/hide limit control
            if (limitControl) {
                if (fieldsWithoutLimits.includes(selectedField)) {
                    limitControl.$el.hide();
                } else {
                    limitControl.$el.show();
                }
            }
            
            // Show/hide separator control
            if (separatorControl) {
                if (fieldsWithSeparators.includes(selectedField)) {
                    separatorControl.$el.show();
                } else {
                    separatorControl.$el.hide();
                }
            }
        },
        
        initCustomControls() {
            // Initialize any custom control types if needed
            // This can be extended for more complex controls
        }
    };

    // Initialize when document is ready
    $(document).ready(() => {
        ElementorWcMetaEditor.init();
    });

    // Make it globally available
    window.ElementorWcMetaEditor = ElementorWcMetaEditor;

})(jQuery);
