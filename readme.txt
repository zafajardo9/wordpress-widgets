=== Omni Widget Pack for Elementor ===
Contributors: omniwidgetpack
Tags: elementor, elementor addon, widgets, components, page builder
Requires at least: 6.2
Tested up to: 6.8
Requires PHP: 7.4
Requires Plugins: elementor
Stable tag: 0.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

One plugin, many widgets: a growing pack of custom Elementor widgets for building reusable components on any site.

== Description ==

Omni Widget Pack is a single Elementor add-on that registers many custom widgets under
one **Omni Widget Pack** category in the Elementor panel. Install it once and reuse the
same components across every site instead of rebuilding them or bundling them into a
theme.

Included widgets:

* Site Container with responsive gutters, nested widgets, and shared typography.
* Navigation Bar with a mobile drawer and Universal Footer with a dynamic copyright year.
* Hero Section with service milestones and CTA Banner with a cover photo and badge.
* Service Card, Feature Card, and the original Info Card.
* Accordion / FAQ with optional structured data and a standalone Button with four variants.

Every widget is a self-contained component with Elementor content and style controls.
The pack is designed to grow: new widgets are added to the same plugin rather than
shipped as separate add-ons.

== Installation ==

1. Install and activate Elementor.
2. Upload the plugin folder to `/wp-content/plugins/omni-widget-pack`, or install the
   ZIP through **Plugins → Add New → Upload Plugin**.
3. Activate **Omni Widget Pack for Elementor**.
4. Edit any page with Elementor and look for the **Omni Widget Pack** category.

== Frequently Asked Questions ==

= Does this work without Elementor? =

No. The plugin requires Elementor 3.5 or newer and stays inactive otherwise, showing an
administrator notice. Site Container requires Elementor 3.16 or newer with Flexbox
Containers and Nested Elements enabled.

= Will my widgets survive a theme change? =

Yes. Widgets are provided by this plugin, not by a theme, so switching themes keeps them
available.

== Changelog ==

= 0.2.0 =
* Adds the Omni layout, navigation, conversion, card, button, and FAQ widgets.
* Preserves rendering for existing Duran Info Cards.

= 0.1.0 =
* Initial release with the shared widget foundation and Info Card.
