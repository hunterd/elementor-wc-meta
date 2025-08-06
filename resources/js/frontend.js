/**
 * Frontend JavaScript for Elementor WC Meta plugin
 */

(function($) {
    'use strict';

    // Frontend functionality
    const ElementorWcMetaFrontend = {
        
        init() {
            this.bindEvents();
            this.initWidgets();
        },
        
        bindEvents() {
            // Handle AJAX product loading (for infinite scroll, filters, etc.)
            $(document).on('updated_wc_div', this.onProductsUpdated.bind(this));
            
            // Handle Elementor frontend re-initialization
            $(window).on('elementor/frontend/init', this.onElementorInit.bind(this));
        },
        
        initWidgets() {
            // Initialize all WC Meta widgets on the page
            $('.elementor-widget-wc-meta').each((index, element) => {
                this.initWidget($(element));
            });
        },
        
        initWidget($widget) {
            const $metaElement = $widget.find('.elementor-wc-meta');
            
            if ($metaElement.length) {
                this.enhanceMetaElement($metaElement);
            }
        },
        
        enhanceMetaElement($element) {
            // Add additional functionality to meta elements
            this.addLinkHandlers($element);
            this.addAnimations($element);
            this.addTooltips($element);
        },
        
        addLinkHandlers($element) {
            // Handle category/tag links if they exist
            $element.find('a').on('click', function(e) {
                const $link = $(this);
                
                // Add custom tracking or behavior if needed
                // For example, track clicks for analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click', {
                        'event_category': 'WC Meta',
                        'event_label': $link.text()
                    });
                }
            });
        },
        
        addAnimations($element) {
            // Add entrance animations if element is in viewport
            if (this.isInViewport($element[0])) {
                $element.addClass('elementor-wc-meta-visible');
            } else {
                // Use Intersection Observer for lazy loading animations
                this.observeElement($element[0]);
            }
        },
        
        addTooltips($element) {
            // Add tooltips for truncated content
            if ($element.data('tooltip')) {
                $element.attr('title', $element.data('tooltip'));
            }
        },
        
        isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },
        
        observeElement(element) {
            // Create intersection observer for animations
            if (!this.observer) {
                this.observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            $(entry.target).addClass('elementor-wc-meta-visible');
                            this.observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '50px'
                });
            }
            
            this.observer.observe(element);
        },
        
        onProductsUpdated() {
            // Re-initialize widgets when products are updated via AJAX
            this.initWidgets();
        },
        
        onElementorInit() {
            // Handle Elementor frontend initialization
            if (typeof elementorFrontend !== 'undefined') {
                // Add custom handlers for Elementor frontend
                elementorFrontend.hooks.addAction('frontend/element_ready/wc-meta.default', ($scope) => {
                    this.initWidget($scope);
                });
            }
        },
        
        // Utility methods
        debounce(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },
        
        throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
    };

    // Initialize when document is ready
    $(document).ready(() => {
        ElementorWcMetaFrontend.init();
    });

    // Also initialize when window loads (for some edge cases)
    $(window).on('load', () => {
        ElementorWcMetaFrontend.initWidgets();
    });

    // Make it globally available
    window.ElementorWcMetaFrontend = ElementorWcMetaFrontend;

})(jQuery);
