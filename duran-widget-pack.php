<?php
/**
 * Plugin Name:       Duran Widget Pack for Elementor
 * Plugin URI:        https://github.com/duranfilepino/duran-widget-pack
 * Description:       One plugin, many widgets. A growing pack of custom Elementor widgets and components you can reuse on any site.
 * Version:           0.1.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * Author:            Duran File Pino
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       duran-widget-pack
 * Domain Path:       /languages
 *
 * @package DuranWidgetPack
 */

defined( 'ABSPATH' ) || exit;

define( 'DURAN_WP_VERSION', '0.1.0' );
define( 'DURAN_WP_FILE', __FILE__ );
define( 'DURAN_WP_PATH', plugin_dir_path( __FILE__ ) );
define( 'DURAN_WP_URL', plugin_dir_url( __FILE__ ) );

/**
 * Map the Duran\WidgetPack namespace to the includes/ directory.
 *
 * Example: Duran\WidgetPack\Widgets\InfoCard -> includes/Widgets/InfoCard.php
 *
 * Keeping this mapping means a new widget only needs a class file and one
 * entry in the registry inside includes/Plugin.php.
 */
spl_autoload_register(
	static function ( $class ) {
		$prefix = 'Duran\\WidgetPack\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$file     = DURAN_WP_PATH . 'includes/' . str_replace( '\\', '/', $relative ) . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
);

add_action( 'plugins_loaded', array( '\Duran\WidgetPack\Plugin', 'instance' ) );
