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
		$this->field( 'photo', esc_html__( 'Cover Photo', 'omni-widget-pack' ), Controls_Manager::MEDIA, array( 'url' => OWP_URL . 'assets/images/cta-team.jpg' ) );
		$this->field( 'badge', esc_html__( 'Eyebrow', 'omni-widget-pack' ), Controls_Manager::WYSIWYG, esc_html__( 'Ready to move forward?', 'omni-widget-pack' ) );
		$this->field( 'badge_color', esc_html__( 'Eyebrow Color', 'omni-widget-pack' ), Controls_Manager::SELECT, 'yellow', array(
			'options' => array( 'red' => esc_html__( 'Red', 'omni-widget-pack' ), 'yellow' => esc_html__( 'Yellow', 'omni-widget-pack' ) ),
			'condition' => array( 'badge!' => '' ),
		) );
		$this->field( 'heading', esc_html__( 'Headline', 'omni-widget-pack' ), Controls_Manager::WYSIWYG, __( "Build your business\nwith", 'omni-widget-pack' ) );
		$this->field( 'highlight', esc_html__( 'Highlighted Text', 'omni-widget-pack' ), Controls_Manager::WYSIWYG, esc_html__( 'confidence.', 'omni-widget-pack' ) );
		$this->field( 'description', esc_html__( 'Description', 'omni-widget-pack' ), Controls_Manager::WYSIWYG, esc_html__( 'You focus on your business. FilePino supports the corporate infrastructure behind it.', 'omni-widget-pack' ) );
		$this->field( 'action_text', esc_html__( 'Action Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Talk to Us', 'omni-widget-pack' ) );
		$this->field( 'action_url', esc_html__( 'Action Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '#contact' ) );
		$this->field( 'supporting_note', esc_html__( 'Note Below Button', 'omni-widget-pack' ), Controls_Manager::WYSIWYG, esc_html__( 'Let’s find the right support for your business.', 'omni-widget-pack' ) );
		$this->end_controls_section();
		$this->typography_section( array(
			'heading' => array( esc_html__( 'Heading', 'omni-widget-pack' ), '.owp-cta-banner .owp-widget-heading' ),
			'body' => array( esc_html__( 'Description', 'omni-widget-pack' ), '.owp-cta-banner__description' ),
			'badge' => array( esc_html__( 'Eyebrow', 'omni-widget-pack' ), '.owp-cta-banner__eyebrow' ),
			'action' => array( esc_html__( 'Action', 'omni-widget-pack' ), '.owp-cta-banner .owp-action' ),
			'note' => array( esc_html__( 'Note', 'omni-widget-pack' ), '.owp-cta-banner__note' ),
		) );
	}

	private function inline_text( $text ) {
		// Keep editor paragraphs and their styles valid inside the banner heading.
		$text = preg_replace( '~</p>\s*(?=<p[\s>])~i', '</p><br>', $text );
		$text = preg_replace( '~<(/?)p(?=[\s>])~i', '<$1span', $text );
		if ( $text === wp_strip_all_tags( $text ) ) {
			$text = nl2br( esc_html( $text ) );
		}
		return wp_kses( $text, array(
			'a' => array( 'href' => true, 'style' => true ),
			'br' => array(),
			'span' => array( 'style' => true ),
			'strong' => array( 'style' => true ),
			'b' => array( 'style' => true ),
			'em' => array( 'style' => true ),
			'i' => array( 'style' => true ),
			'u' => array( 'style' => true ),
			's' => array( 'style' => true ),
			'sub' => array(),
			'sup' => array(),
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
					<span class="owp-cta-banner__eyebrow<?php echo 'red' === $settings['badge_color'] ? ' owp-cta-banner__eyebrow--red' : ''; ?>"><?php echo $this->inline_text( $settings['badge'] ); ?></span>
				<?php endif; ?>
				<?php if ( $settings['heading'] || $settings['highlight'] ) : ?>
					<h2 class="owp-widget-heading">
						<?php echo $this->inline_text( $settings['heading'] ); ?>
						<?php if ( $settings['highlight'] ) : ?><span class="owp-cta-banner__highlight"><?php echo $this->inline_text( $settings['highlight'] ); ?></span><?php endif; ?>
					</h2>
				<?php endif; ?>
				<?php if ( $settings['description'] ) : ?><div class="owp-cta-banner__description"><?php echo wp_kses_post( wpautop( $settings['description'] ) ); ?></div><?php endif; ?>
				<?php $this->link( $settings['action_text'], $settings['action_url'], 'owp-action owp-action--primary' ); ?>
				<?php if ( ! empty( $settings['supporting_note'] ) ) : ?><div class="owp-cta-banner__note"><?php echo wp_kses_post( wpautop( $settings['supporting_note'] ) ); ?></div><?php endif; ?>
			</div>
		</section>
		<?php
	}
}
