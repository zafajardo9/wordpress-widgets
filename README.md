# Omni Widget Pack for Elementor

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

The widgets appear in the **Omni Widget Pack** category in Elementor. They share a
stylesheet with the brand tokens and expose Elementor controls for content and
typography.

## Requirements

- WordPress 6.2+
- PHP 7.4+
- Elementor **3.5+** for standalone widgets
- Elementor **3.16+** with Flexbox Containers and Nested Elements enabled for Site Container

## Installation

1. Copy or clone this repository into `wp-content/plugins/omni-widget-pack`.
2. Activate **Elementor**.
3. Activate **Omni Widget Pack for Elementor**.

For distribution, zip the repository root (it already contains the plugin headers).

## Previewing the widgets

Open `demo.html` in a browser from the plugin folder to see sample content for all twelve
widgets. In WordPress, open **Omni Widgets** in the admin sidebar to see which widgets
are available in Elementor and open the same demo.

## Current widgets

| Widget | Slug | Description |
| --- | --- | --- |
| Site Container | `owp-container` | Nested layout shell with responsive gutters and shared typography. |
| Button | `owp-button` | Primary, Secondary, Accent, Ghost, Text Link, Custom, and Icon Only variants. |
| Navigation Bar | `owp-navbar` | Brand, navigation, action, and responsive menu drawer. |
| Universal Footer | `owp-footer` | Four columns, contact details, social links, and an automatic copyright year. |
| Hero Section | `owp-hero` | Headline, two actions, and optional service milestones. |
| Service Milestones | `owp-service-milestones` | Glowing connector, white-bordered markers, and editable labels and icons. |
| CTA Banner | `owp-cta-banner` | Cover photo, badge, highlighted heading, and action. |
| Service Card | `owp-service-card` | Thumbnail, title, tag, description, and action. |
| Feature Card | `owp-feature-card` | Icon, title, and description with a bottom divider. |
| Accordion / FAQ | `owp-accordion` | Single or multiple expansion and optional FAQ JSON-LD. |
| Our Team Carousel | `owp-team-carousel` | Selectable team cards with a featured portrait and rich-text profile. |
| Info Card | `owp-info-card` | Icon, title, description, and optional link. |

## Shared button styles

In WordPress, open **Omni Widgets → Button Styles** to set the shared background
and text colors. Clear a field to restore the preset. In Elementor, select a
variant under the Button widget's **Content** tab. **Style → Button** overrides
colors and radius for the selected widget. The Custom variant starts with
Primary's colors and supports its own overrides.

## Using Site Container

1. Drag **Site Container** from the Omni Widget Pack category into a full-width section.
2. Drop widgets inside its **Section Content** container. Elementor manages the inner
   container and its children.
3. Use **Layout** to set maximum width and spacing. Standard gutters are 80 px on
   desktop, 40 px on tablet, and 20 px on mobile. The default content maximum is
   1280 px, which leaves 80 px on each side at 1440 px.
4. In **Style → Appearance**, set Base Typography and Heading Font Family. Omni
   widgets inherit these settings by default. Use each widget’s own typography
   controls to override specific elements.

On an existing Duran installation, activate **Omni Widget Pack for Elementor** after
installing it. Pages saved with the `duran-info-card` widget continue to render with
their saved content.

## Project structure

```text
omni-widget-pack.php           Plugin header, constants, namespace autoloader
includes/
  Plugin.php                   Bootstrap, Elementor hooks, widget registry, requirements notice
  Widgets/
    BaseWidget.php             Shared category, keywords, stylesheet dependency
    SiteContainer.php          Nested Elementor layout shell
    InfoCard.php               Original widget and reference implementation
assets/
  css/
    omni-widget-pack.css       Brand tokens and shared widget styles
  js/
    omni-editor.js             Site Container editor registration
    omni-interactions.js       Mobile navigation and accordion interactions
languages/                     Translation files (optional)
readme.txt                     WordPress.org-style readme
```

## Adding a new widget

**1. Create the class** in `includes/Widgets/`, extending `BaseWidget`:

```php
<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Testimonial extends BaseWidget {

	public function get_name() {
		return 'owp-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Testimonial', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'omni-widget-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'quote',
			array(
				'label'   => esc_html__( 'Quote', 'omni-widget-pack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'A great product.', 'omni-widget-pack' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<blockquote class="owp-testimonial owp-widget">
			<?php echo esc_html( $settings['quote'] ); ?>
		</blockquote>
		<?php
	}
}
```

**2. Add the class** to the widget registry in `includes/Plugin.php`.

**3. Add baseline styles** to `assets/css/omni-widget-pack.css` if the widget needs them
beyond what Elementor controls provide.

## Conventions

- **Prefix everything.** PHP class names live under `Omni\WidgetPack`, widget slugs start
  with `owp-`, CSS classes start with `owp-`, and the text domain is
  `omni-widget-pack`.
- **Style controls over hard-coded CSS.** Use Elementor controls for colors, spacing,
  and typography; keep CSS for structure and defaults.
- **Escape output.** Use `esc_html()`, `esc_attr()`, or `wp_kses_post()` in `render()`.
- **Cache-busting is automatic** through `OWP_VERSION` in the stylesheet handle.
- **Accessibility first.** Decorative icons get `aria-hidden="true"`; interactive
  elements must be real links or buttons.

## License

GPL-2.0-or-later. See <https://www.gnu.org/licenses/gpl-2.0.html>.
