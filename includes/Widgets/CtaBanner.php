<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class CtaBanner extends BaseWidget {
	public function get_name() {
		return 'owp-cta-banner';
	}

	public function get_title() {
		return esc_html__( 'CTA Banner', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	protected function register_controls() {
		$this->content_section();
		$this->field( 'photo', esc_html__( 'Cover Photo', 'omni-widget-pack' ), Controls_Manager::MEDIA );
		$this->field( 'badge', esc_html__( 'Badge', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Ready to move forward?', 'omni-widget-pack' ) );
		$this->field( 'badge_color', esc_html__( 'Badge Color', 'omni-widget-pack' ), Controls_Manager::SELECT, 'red', array(
			'options' => array( 'red' => esc_html__( 'Red', 'omni-widget-pack' ), 'yellow' => esc_html__( 'Yellow', 'omni-widget-pack' ) ),
			'condition' => array( 'badge!' => '' ),
		) );
		$this->field( 'heading', esc_html__( 'Headline', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Build your business with', 'omni-widget-pack' ) );
		$this->field( 'highlight', esc_html__( 'Highlighted Text', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'confidence', 'omni-widget-pack' ) );
		$this->field( 'description', esc_html__( 'Description', 'omni-widget-pack' ), Controls_Manager::TEXTAREA );
		$this->field( 'action_text', esc_html__( 'Action Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Talk to Us', 'omni-widget-pack' ) );
		$this->field( 'action_url', esc_html__( 'Action Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '#contact' ) );
		$this->end_controls_section();
		$this->typography_section( array(
			'heading' => array( esc_html__( 'Heading', 'omni-widget-pack' ), '.owp-cta-banner .owp-widget-heading' ),
			'body' => array( esc_html__( 'Description', 'omni-widget-pack' ), '.owp-cta-banner__description' ),
			'badge' => array( esc_html__( 'Badge', 'omni-widget-pack' ), '.owp-cta-banner .owp-badge' ),
			'action' => array( esc_html__( 'Action', 'omni-widget-pack' ), '.owp-cta-banner .owp-action' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$has_photo = ! empty( $settings['photo']['id'] ) || esc_url( $settings['photo']['url'] ?? '' );
		?>
		<section class="owp-cta-banner owp-widget<?php echo $has_photo ? ' owp-cta-banner--split' : ''; ?>">
			<?php if ( $has_photo ) : ?>
				<div class="owp-cta-banner__photo"><?php $this->image( $settings['photo'], 'large' ); ?></div>
			<?php endif; ?>
			<div class="owp-cta-banner__copy">
				<?php if ( $settings['badge'] ) : ?>
					<span class="owp-badge<?php echo 'red' === $settings['badge_color'] ? ' owp-badge--red' : ''; ?>"><?php echo esc_html( $settings['badge'] ); ?></span>
				<?php endif; ?>
				<?php if ( $settings['heading'] || $settings['highlight'] ) : ?>
					<h2 class="owp-widget-heading">
						<?php echo esc_html( $settings['heading'] ); ?>
						<?php if ( $settings['highlight'] ) : ?><span><?php echo esc_html( $settings['highlight'] ); ?></span><?php endif; ?>
					</h2>
				<?php endif; ?>
				<?php if ( $settings['description'] ) : ?><p class="owp-cta-banner__description"><?php echo nl2br( esc_html( $settings['description'] ) ); ?></p><?php endif; ?>
				<?php $this->link( $settings['action_text'], $settings['action_url'], 'owp-action owp-action--primary' ); ?>
			</div>
		</section>
		<?php
	}
}
