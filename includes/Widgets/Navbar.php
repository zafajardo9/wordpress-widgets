<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Omni\WidgetPack\Plugin;

defined( 'ABSPATH' ) || exit;

final class Navbar extends BaseWidget {
	public function get_name() {
		return 'owp-navbar';
	}

	public function get_title() {
		return esc_html__( 'Navigation Bar', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_script_depends() {
		return array( Plugin::INTERACTION_HANDLE );
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	protected function register_controls() {
		$this->content_section();
		$this->field( 'logo', esc_html__( 'Brand Logo', 'omni-widget-pack' ), Controls_Manager::MEDIA );
		$this->field( 'brand', esc_html__( 'Brand Name', 'omni-widget-pack' ), Controls_Manager::TEXT, '', array(
			'description' => esc_html__( 'Defaults to the WordPress site name.', 'omni-widget-pack' ),
		) );
		$this->link_repeater( 'items', esc_html__( 'Navigation Links', 'omni-widget-pack' ), array(
			array( 'label' => esc_html__( 'Home', 'omni-widget-pack' ), 'url' => array( 'url' => home_url( '/' ) ) ),
			array( 'label' => esc_html__( 'Services', 'omni-widget-pack' ), 'url' => array( 'url' => '#services' ) ),
			array( 'label' => esc_html__( 'About', 'omni-widget-pack' ), 'url' => array( 'url' => '#about' ) ),
		) );
		$this->field( 'action_text', esc_html__( 'Action Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Talk to Us', 'omni-widget-pack' ) );
		$this->field( 'action_url', esc_html__( 'Action Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '#contact' ) );
		$this->field( 'sticky', esc_html__( 'Sticky Frosted Header', 'omni-widget-pack' ), Controls_Manager::SWITCHER, '', array( 'prefix_class' => 'owp-navbar-sticky-' ) );
		$this->end_controls_section();
		$this->typography_section( array(
			'nav' => array( esc_html__( 'Navigation', 'omni-widget-pack' ), '.owp-nav-link' ),
			'action' => array( esc_html__( 'Action', 'omni-widget-pack' ), '.owp-navbar .owp-action' ),
		) );
	}

	private function is_current_link( $url ) {
		if ( '' === $url || '#' === $url[0] || wp_parse_url( $url, PHP_URL_FRAGMENT ) ) {
			return false;
		}
		$host = wp_parse_url( $url, PHP_URL_HOST );
		if ( $host && strtolower( $host ) !== strtolower( wp_parse_url( home_url(), PHP_URL_HOST ) ) ) {
			return false;
		}
		// Menu links may include a query (for example, on sites using plain permalinks).
		$request = wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' );
		$path = wp_parse_url( $url, PHP_URL_PATH ) ?? '/';
		$query = wp_parse_url( $url, PHP_URL_QUERY );
		return untrailingslashit( $path ) === untrailingslashit( wp_parse_url( $request, PHP_URL_PATH ) ?? '/' )
			&& ( $query ?? '' ) === ( wp_parse_url( $request, PHP_URL_QUERY ) ?? '' );
	}

	private function render_links( $settings ) {
		?>
		<ul class="owp-nav-links">
			<?php foreach ( $settings['items'] ?? array() as $item ) :
				$link = $item['url'] ?? array();
				if ( empty( $item['label'] ) || ! esc_url( $link['url'] ?? '' ) ) {
					continue;
				}
				?>
				<li><?php $this->link( $item['label'], $link, 'owp-nav-link', false, array(), $this->is_current_link( $link['url'] ) ); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php $this->link( $settings['action_text'], $settings['action_url'], 'owp-action owp-action--primary' );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$brand = $settings['brand'] ?: get_bloginfo( 'name' );
		$dialog_id = wp_unique_id( 'owp-menu-' );
		$menu_label = esc_attr__( 'Main menu', 'omni-widget-pack' );
		?>
		<header class="owp-navbar owp-widget">
			<a class="owp-navbar__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $brand ); ?>">
				<?php if ( ! empty( $settings['logo']['id'] ) || ! empty( $settings['logo']['url'] ) ) :
					$this->image( $settings['logo'], 'medium' );
				else :
					echo esc_html( $brand );
				endif; ?>
			</a>
			<nav class="owp-navbar__desktop" aria-label="<?php echo $menu_label; ?>"><?php $this->render_links( $settings ); ?></nav>
			<button class="owp-navbar__open" type="button" aria-haspopup="dialog" aria-controls="<?php echo esc_attr( $dialog_id ); ?>" aria-expanded="false" hidden>
				<?php esc_html_e( 'Menu', 'omni-widget-pack' ); ?>
			</button>
			<dialog class="owp-navbar__drawer" id="<?php echo esc_attr( $dialog_id ); ?>" aria-label="<?php echo $menu_label; ?>">
				<form method="dialog"><button class="owp-navbar__close" type="submit"><?php esc_html_e( 'Close menu', 'omni-widget-pack' ); ?></button></form>
				<nav aria-label="<?php echo $menu_label; ?>"><?php $this->render_links( $settings ); ?></nav>
			</dialog>
		</header>
		<?php
	}
}
