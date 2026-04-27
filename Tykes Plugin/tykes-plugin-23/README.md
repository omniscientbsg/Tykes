# Tykes Design System — Plugin Documentation v2.0

A premium Elementor widget library + global header/footer override built for
Tykes Early Years. Architecturally similar to Elementor Pro / Crocoblock Jet plugins.

---

## 📁 Folder Structure

```
tykes-plugin/
├── tykes-plugin.php                     ← Main bootstrap (singleton, autoloader)
│
├── admin/
│   ├── class-settings-page.php          ← WordPress Settings → Tykes Settings
│   ├── admin.css                        ← Premium settings page styles
│   └── admin.js                         ← Conditional fields + live colour preview
│
├── includes/
│   ├── class-header-footer.php          ← Global header/footer override system
│   ├── class-full-width.php             ← Full-width Elementor enforcement
│   ├── class-widget-registry.php        ← Central widget map + naming system
│   └── class-widget-base-tykes.php      ← Abstract base (shared deep controls)
│
├── widgets/
│   ├── class-widget-tykes-header.php           → "Tykes Header"
│   ├── class-widget-tykes-footer.php           → "Tykes Footer"
│   ├── class-widget-tykes-cta.php              → "Tykes CTA"
│   ├── class-widget-tykes-difference-hero.php  → "Tykes Difference Hero"
│   └── class-widget-tykes-difference-features.php → "Tykes Difference Features"
│
└── assets/
    ├── css/tykes-ds.css                 ← All design tokens + original class names
    └── js/tykes-ds.js                   ← Scroll, drawer, popup, focus trap
```

---

## 🎛️ Settings Panel

**Location:** WordPress Dashboard → Settings → 🎓 Tykes Settings

| Setting | Options | Description |
|---|---|---|
| Header Source | Widget / Elementor Template / WP Menu | How the global header renders |
| Header Template | Dropdown of Elementor library items | Used when source = Template |
| WordPress Menu | Dropdown of nav menus | Used when source = Menu |
| Footer Source | Widget / Elementor Template | How the global footer renders |
| Footer Template | Dropdown of Elementor library items | Used when source = Template |
| Force Full-Width | Toggle | Removes Elementor container constraints |
| Disable Hello Header | Toggle | Hides Hello Elementor's native header |
| Disable Hello Footer | Toggle | Hides Hello Elementor's native footer |
| Brand Primary | Colour picker | Overrides `--p` CSS variable |
| Brand Gold | Colour picker | Overrides `--gold` CSS variable |
| Brand Teal | Colour picker | Overrides `--teal` CSS variable |

---

## 🧠 Naming Convention

### Global widgets → `"Tykes {Role}"`
- `tykes-header` → **Tykes Header**
- `tykes-footer` → **Tykes Footer**
- `tykes-cta`    → **Tykes CTA**

### Page-specific widgets → `"Tykes {Page} {Section}"`
- `tykes-difference-hero`     → **Tykes Difference Hero**
- `tykes-difference-features` → **Tykes Difference Features**

All names are declared in `Widget_Registry::$widget_map`. No other file
needs to change when you add a widget.

---

## ➕ Adding a New Widget

### Step 1 — Register in the widget map

Open `includes/class-widget-registry.php` and add to `$widget_map`:

```php
'tykes-curriculum-hero' => [
    'class' => 'Tykes_DS\Widget_Tykes_Curriculum_Hero',
    'file'  => 'class-widget-tykes-curriculum-hero.php',
    'title' => __( 'Tykes Curriculum Hero', 'tykes-ds' ),
    'icon'  => 'eicon-banner',
],
```

### Step 2 — Create the widget class

Create `widgets/class-widget-tykes-curriculum-hero.php`:

```php
<?php
namespace Tykes_DS;
defined('ABSPATH') || exit;

class Widget_Tykes_Curriculum_Hero extends Widget_Base_Tykes {

    public function get_name(): string  { return 'tykes-curriculum-hero'; }
    public function get_title(): string { return esc_html__('Tykes Curriculum Hero', 'tykes-ds'); }
    public function get_icon(): string  { return 'eicon-banner'; }

    protected function register_controls(): void {
        // 1. Content controls
        $this->start_controls_section('sec_content', [
            'label' => __('Content', 'tykes-ds'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);
        // ... your controls ...
        $this->end_controls_section();

        // 2. Style controls (use shared helpers)
        $this->add_section_spacing_controls('{{WRAPPER}} .your-section');
        $this->add_section_background_controls('{{WRAPPER}} .your-section');
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        // ... your HTML ...
    }
}
```

That's it. The autoloader and registry handle everything else.

---

## 🌐 Global Header/Footer System

### How it works

1. `Header_Footer::render_header()` fires on `wp_body_open` (priority 5)
2. `Header_Footer::render_footer()` fires on `wp_footer` (priority 5)
3. The render function checks the Settings Panel and dispatches to:
   - **Widget** → calls `Widget_Tykes_Header::render_standalone()`
   - **Elementor Template** → calls `\Elementor\Plugin::instance()->frontend->get_builder_content_for_display()`
   - **WordPress Menu** → renders a minimal nav wrapper with `wp_nav_menu()`

### Hello Elementor suppression

When "Disable Hello Header/Footer" is enabled:
- Filters `hello_elementor_header_template` / `hello_elementor_footer_template` to `false`
- Injects a CSS `display:none` rule as a hard fallback

### Standalone render

Every widget that can be used as a global header/footer implements
`render_standalone()` (via `Widget_Base_Tykes`). It calls `render()` with
the widget's default settings, bypassing Elementor's editor context.

---

## 📐 Full-Width System

`Full_Width` class does five things:

1. Calls `add_theme_support()` for Elementor compatibility
2. Filters `elementor/page_templates/canvas/conditions` to allow canvas globally
3. Sets `_wp_page_template = elementor_canvas` on Elementor page save (if unset)
4. Outputs inline CSS removing container padding from Tykes widget wrappers
5. Removes Hello Elementor's `padding-top` body offset

---

## 🎨 CSS Architecture

All CSS lives in `assets/css/tykes-ds.css`.

Design tokens are CSS custom properties on `:root`:

```css
--p:    #8257bd;   /* primary purple */
--pd:   #6d46a8;   /* primary dark */
--gold: #fdbc02;   /* gold accent */
--teal: #05a28d;   /* teal accent */
```

When brand colours are changed in Settings, `Header_Footer::output_brand_css_vars()`
outputs an override `<style>` block on `wp_head` that overwrites only the changed variables.

**All original HTML class names are preserved exactly** — `.curr-hero`,
`.diff-feat`, `.hb-badge`, etc. — so the existing CSS applies without
modification to any markup.

---

## 🔒 Security

- All output escaping via `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses()`
- Settings sanitised via `sanitize_hex_color()`, `absint()`, `in_array()` checks
- `ABSPATH` guard on every file
- `current_user_can('manage_options')` check before settings page renders
- Nonce passed to frontend JS via `wp_localize_script` for future AJAX use

---

## ✅ Requirements

| Requirement | Version |
|---|---|
| WordPress | 6.2+ |
| PHP | 7.4+ |
| Elementor (free) | 3.18.0+ |
| Theme | Hello Elementor (recommended) |
