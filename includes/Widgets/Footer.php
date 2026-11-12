<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class Footer extends BaseWidget {
	public function get_name() {
		return 'owp-footer';
	}

	public function get_title() {
		return esc_html__( 'Universal Footer', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-footer';
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	protected function register_controls() {
		$this->content_section();
		$this->field( 'logo', esc_html__( 'Brand Logo', 'omni-widget-pack' ), Controls_Manager::MEDIA );
		$this->field( 'brand', esc_html__( 'Brand Name', 'omni-widget-pack' ) );
		$this->field( 'summary', esc_html__( 'Company Summary', 'omni-widget-pack' ), Controls_Manager::TEXTAREA );
		$this->link_repeater( 'links', esc_html__( 'Company Links', 'omni-widget-pack' ), array(
			array( 'label' => esc_html__( 'Services', 'omni-widget-pack' ), 'url' => array( 'url' => '#services' ) ),
			array( 'label' => esc_html__( 'About', 'omni-widget-pack' ), 'url' => array( 'url' => '#about' ) ),
		) );
		$this->field( 'location', esc_html__( 'Location', 'omni-widget-pack' ) );
		$this->field( 'phone', esc_html__( 'Phone', 'omni-widget-pack' ) );
		$this->field( 'email', esc_html__( 'Email', 'omni-widget-pack' ) );
		$this->field( 'action_text', esc_html__( 'Action Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Contact Us', 'omni-widget-pack' ) );
		$this->field( 'action_url', esc_html__( 'Action Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '#contact' ) );
		$this->link_repeater( 'socials', esc_html__( 'Social Links', 'omni-widget-pack' ), array(), true );
		$this->field( 'copyright', esc_html__( 'Copyright Owner', 'omni-widget-pack' ), Controls_Manager::TEXT, '', array(
			'description' => esc_html__( 'Defaults to the brand name. The year is added automatically.', 'omni-widget-pack' ),
		) );
		$this->field( 'surface', esc_html__( 'Background', 'omni-widget-pack' ), Controls_Manager::SELECT, 'white', array(
			'options' => array( 'white' => esc_html__( 'White', 'omni-widget-pack' ), 'blue' => esc_html__( 'Blue', 'omni-widget-pack' ) ),
		) );
		$this->end_controls_section();
		$this->typography_section( array(
			'body' => array( esc_html__( 'Body', 'omni-widget-pack' ), '.owp-footer' ),
			'heading' => array( esc_html__( 'Headings', 'omni-widget-pack' ), '.owp-footer .owp-widget-heading' ),
			'action' => array( esc_html__( 'Action', 'omni-widget-pack' ), '.owp-footer .owp-action' ),
		) );
	}

	private function contact_icon( $type ) {
		$paths = array(
			'location' => 'M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0ZM12 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z',
			'phone' => 'M5 3h4l2 5-3 2a13 13 0 0 0 6 6l2-3 5 2v4a2 2 0 0 1-2 2C10 21 3 14 3 5a2 2 0 0 1 2-2Z',
			'email' => 'M3 5h18v14H3zM3 6l9 7 9-7',
		);
		?>
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
			<path d="<?php echo esc_attr( $paths[ $type ] ); ?>"></path>
		</svg>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$brand = $settings['brand'] ?: get_bloginfo( 'name' );
		$phone = preg_replace( '/[^0-9+]/', '', $settings['phone'] );
		$email = sanitize_email( $settings['email'] );
		?>
		<footer class="owp-footer owp-widget<?php echo 'blue' === $settings['surface'] ? ' owp-footer--blue' : ''; ?>">
			<div class="owp-footer__grid">
				<div class="owp-footer__brand">
					<?php if ( ! empty( $settings['logo']['id'] ) || ! empty( $settings['logo']['url'] ) ) :
						$this->image( $settings['logo'], 'medium' );
					else : ?>
						<p class="owp-widget-heading"><?php echo esc_html( $brand ); ?></p>
					<?php endif; ?>
					<?php if ( $settings['summary'] ) : ?><p><?php echo nl2br( esc_html( $settings['summary'] ) ); ?></p><?php endif; ?>
				</div>
				<nav aria-label="<?php esc_attr_e( 'Company links', 'omni-widget-pack' ); ?>">
					<h2 class="owp-widget-heading"><?php esc_html_e( 'Company', 'omni-widget-pack' ); ?></h2>
					<ul>
						<?php foreach ( $settings['links'] ?? array() as $item ) :
							if ( empty( $item['label'] ) || ! esc_url( $item['url']['url'] ?? '' ) ) {
								continue;
							}
							?>
							<li><?php $this->link( $item['label'], $item['url'] ); ?></li>
						<?php endforeach; ?>
					</ul>
				</nav>
				<div>
					<h2 class="owp-widget-heading"><?php esc_html_e( 'Contact', 'omni-widget-pack' ); ?></h2>
					<address><ul>
						<?php if ( $settings['location'] ) : ?><li><?php $this->contact_icon( 'location' ); ?><?php echo esc_html( $settings['location'] ); ?></li><?php endif; ?>
						<?php if ( $phone ) : ?><li><?php $this->contact_icon( 'phone' ); ?><?php $this->link( $settings['phone'], array( 'url' => 'tel:' . $phone ) ); ?></li><?php endif; ?>
						<?php if ( $email ) : ?><li><?php $this->contact_icon( 'email' ); ?><?php $this->link( $email, array( 'url' => 'mailto:' . $email ) ); ?></li><?php endif; ?>
					</ul></address>
				</div>
				<div>
					<h2 class="owp-widget-heading"><?php esc_html_e( 'Get in Touch', 'omni-widget-pack' ); ?></h2>
					<?php $this->link( $settings['action_text'], $settings['action_url'], 'owp-action owp-action--primary' ); ?>
					<div class="owp-footer__socials">
						<?php foreach ( $settings['socials'] ?? array() as $social ) {
							$this->link( $social['label'] ?? '', $social['url'] ?? array(), 'owp-footer__social', false, $social['icon'] ?? array() );
						} ?>
					</div>
				</div>
			</div>
			<div class="owp-footer__bottom">
				<?php printf(
					/* translators: %1$s is the current year, %2$s is the copyright owner. */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'omni-widget-pack' ),
					esc_html( wp_date( 'Y' ) ),
					esc_html( $settings['copyright'] ?: $brand )
				); ?>
			</div>
		</footer>
		<?php
	}
}
