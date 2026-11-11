# Duran Widget Pack for Elementor

**One plugin. Many widgets.**

A WordPress plugin that ships a growing pack of custom **Elementor widgets** — reusable
components and elements you can drop into any site instead of rebuilding the same
sections page after page.

> This repository is a **plugin**, not a theme, even though the folder is named
> `wordpress-widget-theme`. The thing being built is an Elementor add-on.

---

## Project goal

Build and maintain **one** WordPress plugin that registers **many** custom Elementor
widgets. The plugin is the product; widgets are added to it over time instead of being
shipped as separate plugins or copy-pasted between themes.

Every widget in the pack:

- appears in a single **Duran Widget Pack** category in the Elementor panel,
- is a self-contained component (info card, testimonial, pricing table, …),
- shares one base class and one stylesheet,
- is registered from one central list.

### What success looks like

1. A designer can open Elementor, find the **Duran Widget Pack** category, and drag any
   pack widget onto a page — on any site where the plugin is installed.
2. Adding a new widget never requires editing the main plugin file, the base class, or
   any existing widget.
3. Widgets are styled from Elementor controls, with the shared stylesheet providing only
   sane defaults.
4. If Elementor is missing or too old, the plugin stays inert and tells the
   administrator why.

### What this project is not

| Not this | Why |
| --- | --- |
| A WordPress theme | It must survive theme changes; it is a plugin. |
| A page builder | Elementor is the builder; this pack extends it. |
| A collection of unrelated plugins | One install, one settings surface, one update path. |
| A “kit” bundled inside a theme | Widgets must be portable across sites. |

---

## Requirements

- WordPress 6.2+
- PHP 7.4+
- Elementor **3.5+** (the `elementor/widgets/register` API is required)

## Installation

1. Copy or clone this repository into `wp-content/plugins/duran-widget-pack`.
2. Activate **Elementor**.
3. Activate **Duran Widget Pack for Elementor**.

For distribution, zip the repository root (it already contains the plugin headers).

## Current widgets

| Widget | Slug | Description |
| --- | --- | --- |
| Info Card | `duran-info-card` | Icon, title, description, and an optional link, fully styleable. |

`Info Card` is also the reference implementation to copy when starting a new widget.

---

## Project structure

```text
duran-widget-pack.php          Plugin header, constants, namespace autoloader
includes/
  Plugin.php                   Bootstrap, Elementor hooks, widget registry, requirements notice
  Widgets/
    BaseWidget.php             Shared category, keywords, stylesheet dependency
    InfoCard.php               First widget + reference implementation
assets/
  css/
    duran-widget-pack.css      Shared baseline styles for all pack widgets
languages/                     Translation files (optional)
readme.txt                     WordPress.org-style readme
```

### How the goal is enforced in code

- **One plugin** — a single plugin header, a single bootstrap (`includes/Plugin.php`).
- **Many widgets** — one registry array in `Plugin::$widgets`; every entry is a class
  under `includes/Widgets/`.
- **One category** — `Plugin::CATEGORY` is applied through `BaseWidget::get_categories()`.
- **One stylesheet** — `Plugin::STYLE_HANDLE` is returned by `BaseWidget::get_style_depends()`.
- **Scales without boilerplate** — the autoloader maps `Duran\WidgetPack\Widgets\Foo` to
  `includes/Widgets/Foo.php`, so new widgets need no `require` statements.

---

## Adding a new widget

**1. Create the class** in `includes/Widgets/`, extending `BaseWidget`:

```php
<?php
namespace Duran\WidgetPack\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Testimonial extends BaseWidget {

	public function get_name() {
		return 'duran-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Testimonial', 'duran-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'duran-widget-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'quote',
			array(
				'label'   => esc_html__( 'Quote', 'duran-widget-pack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'A great product.', 'duran-widget-pack' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<blockquote class="duran-testimonial">
			<?php echo esc_html( $settings['quote'] ); ?>
		</blockquote>
		<?php
	}
}
```

**2. Register it** in `includes/Plugin.php`:

```php
private $widgets = array(
	Widgets\InfoCard::class,
	Widgets\Testimonial::class,
);
```

**3. Add baseline styles** to `assets/css/duran-widget-pack.css` if the widget needs them
beyond what Elementor controls provide.

That's the whole flow — no changes to the main plugin file or existing widgets.

---

## Conventions

- **Prefix everything.** PHP class names live under `Duran\WidgetPack`, widget slugs start
  with `duran-`, CSS classes start with `duran-`, and the text domain is
  `duran-widget-pack`.
- **Style controls over hard-coded CSS.** Use Elementor controls for colors, spacing,
  and typography; keep CSS for structure and defaults.
- **Escape output.** Use `esc_html()`, `esc_attr()`, or `wp_kses_post()` in `render()`.
- **Cache-busting is automatic** through `DURAN_WP_VERSION` in the stylesheet handle.
- **Accessibility first.** Decorative icons get `aria-hidden="true"`; interactive
  elements must be real links or buttons.

## Roadmap

Widgets planned for the pack, roughly in order:

- [x] Info Card
- [ ] Testimonial / Review
- [ ] Pricing Table
- [ ] Call-to-Action Banner
- [ ] Team Member
- [ ] Logo / Brand Grid
- [ ] Stats Counter
- [ ] Tabs and Accordion
- [ ] Post / CPT Loop card
- [ ] Global pack settings (shared colors and typography)

## License

GPL-2.0-or-later. See <https://www.gnu.org/licenses/gpl-2.0.html>.
