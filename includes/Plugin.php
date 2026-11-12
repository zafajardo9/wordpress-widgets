<?php
/**
 * Plugin bootstrap: requirements, widget registry, and Elementor hooks.
 *
 * @package OmniWidgetPack
 */

namespace Omni\WidgetPack;

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
	const CATEGORY = 'omni-widget-pack';

	/**
	 * Shared frontend stylesheet handle.
	 */
	const STYLE_HANDLE = 'omni-widget-pack';
	const INTERACTION_HANDLE = 'omni-widget-pack-interactions';

	/**
	 * The widget registry.
	 *
	 * This is the only list that needs to know a widget exists. Each class
	 * lives in includes/Widgets/ and extends BaseWidget.
	 *
	 * @var string[]
	 */
	private $widgets = array(
		Widgets\Button::class,
		Widgets\Navbar::class,
		Widgets\Footer::class,
		Widgets\Hero::class,
		Widgets\CtaBanner::class,
		Widgets\ServiceCard::class,
		Widgets\FeatureCard::class,
		Widgets\Accordion::class,
		Widgets\InfoCard::class,
		Widgets\LegacyInfoCard::class,
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
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_editor_script' ) );
		add_action( 'admin_notices', array( $this, 'render_requirements_notice' ) );
	}

	/**
	 * Load translations from /languages when present.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'omni-widget-pack',
			false,
			dirname( plugin_basename( OWP_FILE ) ) . '/languages'
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

		if ( $this->supports_nested_elements() ) {
			$widgets_manager->register( new Widgets\SiteContainer() );
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
				'title' => esc_html__( 'Omni Widget Pack', 'omni-widget-pack' ),
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
			OWP_URL . 'assets/css/omni-widget-pack.css',
			array(),
			OWP_VERSION
		);
	}

	public function register_scripts() {
		wp_register_script(
			self::INTERACTION_HANDLE,
			OWP_URL . 'assets/js/omni-interactions.js',
			array( 'elementor-frontend' ),
			OWP_VERSION,
			true
		);
	}

	public function enqueue_editor_script() {
		if ( $this->supports_nested_elements() ) {
			wp_enqueue_script(
				'omni-widget-pack-editor',
				OWP_URL . 'assets/js/omni-editor.js',
				array( 'elementor-editor', 'nested-elements' ),
				OWP_VERSION,
				true
			);
		}
	}

	private function supports_nested_elements() {
		return $this->is_elementor_compatible()
			&& version_compare( ELEMENTOR_VERSION, '3.16.0', '>=' )
			&& \Elementor\Plugin::$instance->modules_manager->get_modules( 'nested-elements' )
			&& class_exists( \Elementor\Modules\NestedElements\Base\Widget_Nested_Base::class );
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
				esc_html__( '%1$s requires Elementor %2$s or newer. Please update Elementor to use this widget pack.', 'omni-widget-pack' ),
				'<strong>Omni Widget Pack</strong>',
				esc_html( self::MIN_ELEMENTOR_VERSION )
			);
		} else {
			$message = sprintf(
				/* translators: %s: plugin name. */
				esc_html__( '%s requires Elementor to be installed and activated.', 'omni-widget-pack' ),
				'<strong>Omni Widget Pack</strong>'
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
