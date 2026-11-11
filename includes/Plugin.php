<?php
/**
 * Plugin bootstrap: requirements, widget registry, and Elementor hooks.
 *
 * @package DuranWidgetPack
 */

namespace Duran\WidgetPack;

defined( 'ABSPATH' ) || exit;

/**
 * Wires the widget pack into Elementor.
 *
 * The pack is deliberately single-purpose: one plugin that registers many
 * widgets. New widgets are added to the registry below and nowhere else.
 */
final class Plugin {

	/**
	 * Minimum Elementor version required by the APIs this plugin uses.
	 */
	const MIN_ELEMENTOR_VERSION = '3.5.0';

	/**
	 * Elementor category that groups every widget in this pack.
	 */
	const CATEGORY = 'duran-widget-pack';

	/**
	 * Shared frontend stylesheet handle.
	 */
	const STYLE_HANDLE = 'duran-widget-pack';

	/**
	 * The widget registry.
	 *
	 * This is the only list that needs to know a widget exists. Each class
	 * lives in includes/Widgets/ and extends BaseWidget.
	 *
	 * @var string[]
	 */
	private $widgets = array(
		Widgets\InfoCard::class,
	);

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Retrieve the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register hooks.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
		add_action( 'admin_notices', array( $this, 'render_requirements_notice' ) );
	}

	/**
	 * Load translations from /languages when present.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'duran-widget-pack',
			false,
			dirname( plugin_basename( DURAN_WP_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register every widget in the pack with Elementor.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		if ( ! $this->is_elementor_compatible() ) {
			return;
		}

		foreach ( $this->widgets as $widget_class ) {
			$widgets_manager->register( new $widget_class() );
		}
	}

	/**
	 * Add the pack category so all widgets sit together in the Elementor panel.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			self::CATEGORY,
			array(
				'title' => esc_html__( 'Duran Widget Pack', 'duran-widget-pack' ),
				'icon'  => 'eicon-plug',
			)
		);
	}

	/**
	 * Register the shared stylesheet used by every widget in the pack.
	 */
	public function register_styles() {
		wp_register_style(
			self::STYLE_HANDLE,
			DURAN_WP_URL . 'assets/css/duran-widget-pack.css',
			array(),
			DURAN_WP_VERSION
		);
	}

	/**
	 * Tell administrators when Elementor is missing or too old.
	 */
	public function render_requirements_notice() {
		if ( $this->is_elementor_compatible() || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$message = sprintf(
				/* translators: 1: plugin name, 2: required Elementor version. */
				esc_html__( '%1$s requires Elementor %2$s or newer. Please update Elementor to use this widget pack.', 'duran-widget-pack' ),
				'<strong>Duran Widget Pack</strong>',
				esc_html( self::MIN_ELEMENTOR_VERSION )
			);
		} else {
			$message = sprintf(
				/* translators: %s: plugin name. */
				esc_html__( '%s requires Elementor to be installed and activated.', 'duran-widget-pack' ),
				'<strong>Duran Widget Pack</strong>'
			);
		}

		printf( '<div class="notice notice-warning"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Whether the active Elementor version supports the APIs used here.
	 *
	 * @return bool
	 */
	private function is_elementor_compatible() {
		return defined( 'ELEMENTOR_VERSION' )
			&& version_compare( ELEMENTOR_VERSION, self::MIN_ELEMENTOR_VERSION, '>=' );
	}
}
