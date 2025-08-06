/**
 * Admin JavaScript for Elementor WC Meta plugin
 */

(function($) {
    'use strict';

    // Admin functionality
    const ElementorWcMetaAdmin = {
        
        init() {
            this.bindEvents();
            this.initComponents();
        },
        
        bindEvents() {
            // Handle plugin activation notices
            $(document).on('click', '.elementor-wc-meta-notice .notice-dismiss', this.dismissNotice);
            
            // Handle settings form
            $(document).on('submit', '#elementor-wc-meta-settings', this.saveSettings);
        },
        
        initComponents() {
            // Initialize any admin components
            this.initTooltips();
        },
        
        initTooltips() {
            $('[data-tooltip]').each(function() {
                const $element = $(this);
                const tooltipText = $element.data('tooltip');
                
                $element.attr('title', tooltipText);
            });
        },
        
        dismissNotice(e) {
            const $notice = $(e.target).closest('.notice');
            $notice.fadeOut(300, function() {
                $(this).remove();
            });
        },
        
        saveSettings(e) {
            e.preventDefault();
            
            const $form = $(e.target);
            const $submitButton = $form.find('input[type="submit"]');
            const originalText = $submitButton.val();
            
            // Show loading state
            $submitButton.val('Saving...').prop('disabled', true);
            
            // Prepare form data
            const formData = new FormData($form[0]);
            formData.append('action', 'elementor_wc_meta_save_settings');
            formData.append('nonce', elementorWcMetaAdmin.nonce);
            
            // Send AJAX request
            $.ajax({
                url: elementorWcMetaAdmin.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.showNotice('Settings saved successfully!', 'success');
                    } else {
                        this.showNotice(response.data || 'Error saving settings.', 'error');
                    }
                },
                error: () => {
                    this.showNotice('Network error. Please try again.', 'error');
                },
                complete: () => {
                    // Restore button state
                    $submitButton.val(originalText).prop('disabled', false);
                }
            });
        },
        
        showNotice(message, type = 'info') {
            const $notice = $(`
                <div class="notice notice-${type} is-dismissible">
                    <p>${message}</p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            `);
            
            // Insert notice after page title
            $('.wrap h1').after($notice);
            
            // Auto-hide success notices
            if (type === 'success') {
                setTimeout(() => {
                    $notice.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(() => {
        ElementorWcMetaAdmin.init();
    });

    // Make it globally available
    window.ElementorWcMetaAdmin = ElementorWcMetaAdmin;

})(jQuery);
