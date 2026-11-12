<?php
/**
 * Plugin Name:       Omni Widget Pack for Elementor
 * Plugin URI:        https://github.com/omni-widget-pack/omni-widget-pack
 * Description:       One plugin, many widgets. A growing pack of custom Elementor widgets and components you can reuse on any site.
 * Version:           0.2.1
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * Author:            Omni Widget Pack Team
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       omni-widget-pack
 * Domain Path:       /languages
 *
 * @package OmniWidgetPack
 */

defined( 'ABSPATH' ) || exit;

define( 'OWP_VERSION', '0.2.1' );
define( 'OWP_FILE', __FILE__ );
define( 'OWP_PATH', plugin_dir_path( __FILE__ ) );
define( 'OWP_URL', plugin_dir_url( __FILE__ ) );

/**
 * Map the Omni\WidgetPack namespace to the includes/ directory.
 *
 * Example: Omni\WidgetPack\Widgets\InfoCard -> includes/Widgets/InfoCard.php
 *
 * Keeping this mapping means a new widget only needs a class file and one
 * entry in the registry inside includes/Plugin.php.
 */
spl_autoload_register(
	static function ( $class ) {
		$prefix = 'Omni\\WidgetPack\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$file     = OWP_PATH . 'includes/' . str_replace( '\\', '/', $relative ) . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
);

add_action( 'plugins_loaded', array( '\Omni\WidgetPack\Plugin', 'instance' ) );
