/**
 * Tykes Accordion / Toggle Handler
 * Supports custom toggle sections and form toggles
 * EXCLUDES Elementor accordions/tabs - let Elementor handle its own widgets
 */

(function() {
  'use strict';

  class TykesAccordion {
    constructor() {
      this.init();
    }

    init() {
      // Wait for DOM to load
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => this.setup());
      } else {
        this.setup();
      }

      // Listen for posts/pages loaded dynamically
      document.addEventListener('post-load', () => this.setup());
    }

    setup() {
      // DO NOT bind Elementor accordions or tabs - let Elementor handle them
      this.bindCustomToggles();
      this.bindFormSections();
    }

    /**
     * Handle custom toggle elements (non-Elementor)
     */
    bindCustomToggles() {
      const toggles = document.querySelectorAll(
        '[class*="toggle-header"]:not(.elementor-accordion-title):not(.elementor-tab-title):not(.ptab),' +
        '[class*="toggle-btn"]:not(.ptab),' +
        '[data-toggle]:not(.elementor-accordion-title):not(.ptab),' +
        '.form-section-title:not(.ptab),' +
        '[class*="accordion-header"]:not(.elementor-accordion-title):not(.elementor-tab-title):not(.ptab)'
      );

      toggles.forEach(toggle => {
        if (toggle.dataset.toggleBound === 'true') return;

        toggle.dataset.toggleBound = 'true';
        toggle.style.cursor = 'pointer';
        toggle.addEventListener('click', (e) => this.handleToggleClick(e));
      });
    }

    /**
     * Handle form section toggles (e.g., "How we can help you")
     * NOTE: If these are Elementor tabs or my custom tabs, they are skipped
     */
    bindFormSections() {
      const sections = document.querySelectorAll(
        '.form-section:not(.elementor-tabs):not(.purpose-tabs),' +
        '[class*="form-option"]:not(.elementor-tabs):not(.purpose-tabs),' +
        '.contact-form-section:not(.elementor-tabs):not(.purpose-tabs)'
      );

      sections.forEach(section => {
        // Skip Elementor widgets or custom tab containers
        if (section.closest('.elementor-widget-tabs') || section.closest('.elementor-tabs') || section.closest('.purpose-tabs')) {
          return;
        }

        const header = section.querySelector(
          '.form-section-title, ' +
          '[class*="section-title"]:not(.elementor-tab-title):not(.ptab), ' +
          'h3:not(.ptab), ' +
          'button:not(.elementor-tab-title):not(.ptab)'
        );

        if (!header || header.dataset.sectionBound === 'true') return;

        header.dataset.sectionBound = 'true';
        header.style.cursor = 'pointer';
        header.addEventListener('click', (e) => this.handleSectionClick(e, section));
      });
    }

    /**
     * Handle custom toggle clicks
     */
    handleToggleClick(e) {
      const btn = e.currentTarget;
      const contentSelector = btn.dataset.toggle || 
                              btn.dataset.target ||
                              btn.getAttribute('aria-controls');

      if (!contentSelector) {
        // Try to find adjacent content element
        const content = btn.nextElementSibling;
        if (content && this.isContentElement(content)) {
          this.toggleContent(btn, content);
        }
        return;
      }

      const content = document.querySelector(contentSelector) ||
                     document.getElementById(contentSelector);

      if (content) {
        this.toggleContent(btn, content);
      }
    }

    /**
     * Handle form section toggle
     */
    handleSectionClick(e, section) {
      const header = e.currentTarget;
      const content = section.querySelector(
        '.form-section-content, ' +
        '[class*="section-content"]:not(.elementor-tab-content), ' +
        'form, ' +
        '.form-group'
      );

      if (!content) return;

      // Close other sections in parent
      const parent = section.parentElement;
      if (parent) {
        parent.querySelectorAll('.form-section').forEach(s => {
          if (s !== section) {
            const h = s.querySelector('h3, button, [class*="title"]');
            const c = s.querySelector('[class*="content"], form');
            if (h) h.classList.remove('active');
            if (c) {
              c.classList.remove('active', 'open');
              c.style.display = 'none';
            }
          }
        });
      }

      this.toggleContent(header, content);
    }

    /**
     * Toggle content visibility with proper state management
     */
    toggleContent(header, content) {
      const isActive = header.classList.contains('active');

      header.classList.toggle('active');
      content.classList.toggle('active');
      content.classList.toggle('open');

      if (isActive) {
        content.style.display = 'none';
        header.setAttribute('aria-expanded', 'false');
      } else {
        content.style.display = 'block';
        header.setAttribute('aria-expanded', 'true');
      }

      // Dispatch custom event for third-party integrations
      content.dispatchEvent(
        new CustomEvent('tykesToggled', {
          bubbles: true,
          detail: { isOpen: !isActive }
        })
      );
    }

    /**
     * Check if element is a content container
     */
    isContentElement(el) {
      const classList = el.className;
      return /content|body|section|form/.test(classList);
    }
  }

  // Initialize on page load
  const accordion = new TykesAccordion();

  // Expose to global scope for manual control
  window.tykesAccordion = accordion;
})();

