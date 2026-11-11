=== Duran Widget Pack for Elementor ===
Contributors: duranfilepino
Tags: elementor, elementor addon, widgets, components, page builder
Requires at least: 6.2
Tested up to: 6.8
Requires PHP: 7.4
Requires Plugins: elementor
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

One plugin, many widgets: a growing pack of custom Elementor widgets for building reusable components on any site.

== Description ==

Duran Widget Pack is a single Elementor add-on that registers many custom widgets under
one **Duran Widget Pack** category in the Elementor panel. Install it once and reuse the
same components across every site instead of rebuilding them or bundling them into a
theme.

Included widgets:

* **Info Card** — icon, title, description, and an optional link, with full style controls.

Every widget is a self-contained component with Elementor content and style controls.
The pack is designed to grow: new widgets are added to the same plugin rather than
shipped as separate add-ons.

== Installation ==

1. Install and activate Elementor.
2. Upload the plugin folder to `/wp-content/plugins/duran-widget-pack`, or install the
   ZIP through **Plugins → Add New → Upload Plugin**.
3. Activate **Duran Widget Pack for Elementor**.
4. Edit any page with Elementor and look for the **Duran Widget Pack** category.

== Frequently Asked Questions ==

= Does this work without Elementor? =

No. The plugin requires Elementor 3.5 or newer and stays inactive otherwise, showing an
administrator notice.

= Will my widgets survive a theme change? =

Yes. Widgets are provided by this plugin, not by a theme, so switching themes keeps them
available.

== Changelog ==

= 0.1.0 =
* Initial release.
* Plugin foundation, shared widget base class, and pack category.
* Added the Info Card widget.
