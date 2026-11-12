<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class ServiceCard extends BaseWidget {
	public function get_name() {
		return 'owp-service-card';
	}

	public function get_title() {
		return esc_html__( 'Service Card', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	protected function register_controls() {
		$this->content_section();
		$this->field( 'thumbnail', esc_html__( 'Thumbnail', 'omni-widget-pack' ), Controls_Manager::MEDIA );
		$this->field( 'heading', esc_html__( 'Title', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Service title', 'omni-widget-pack' ) );
		$this->field( 'subtitle', esc_html__( 'Subtitle or Tag', 'omni-widget-pack' ) );
		$this->field( 'description', esc_html__( 'Description', 'omni-widget-pack' ), Controls_Manager::TEXTAREA );
		$this->field( 'action_text', esc_html__( 'Action Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Learn More', 'omni-widget-pack' ) );
		$this->field( 'action_url', esc_html__( 'Action Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '' ) );
		$this->end_controls_section();
		$this->start_controls_section( 'appearance', array(
			'label' => esc_html__( 'Appearance', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		$this->field( 'surface', esc_html__( 'Background', 'omni-widget-pack' ), Controls_Manager::SELECT, 'white', array(
			'options' => array( 'white' => esc_html__( 'White', 'omni-widget-pack' ), 'tint' => esc_html__( 'Tint', 'omni-widget-pack' ) ),
		) );
		$this->field( 'accent', esc_html__( 'Tag Color', 'omni-widget-pack' ), Controls_Manager::SELECT, 'blue', array(
			'options' => array( 'blue' => esc_html__( 'Blue', 'omni-widget-pack' ), 'red' => esc_html__( 'Red', 'omni-widget-pack' ) ),
		) );
		$this->end_controls_section();
		$this->typography_section( array(
			'heading' => array( esc_html__( 'Title', 'omni-widget-pack' ), '.owp-service-card .owp-widget-title' ),
			'subtitle' => array( esc_html__( 'Tag', 'omni-widget-pack' ), '.owp-service-card__tag' ),
			'body' => array( esc_html__( 'Description', 'omni-widget-pack' ), '.owp-service-card__description' ),
			'action' => array( esc_html__( 'Action', 'omni-widget-pack' ), '.owp-service-card .owp-action' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<article class="owp-service-card owp-widget<?php echo 'tint' === $settings['surface'] ? ' owp-service-card--tint' : ''; ?>">
			<?php $this->image( $settings['thumbnail'], 'medium_large', 'owp-service-card__image' ); ?>
			<div class="owp-service-card__copy">
				<?php if ( $settings['subtitle'] ) : ?>
					<p class="owp-service-card__tag<?php echo 'red' === $settings['accent'] ? ' owp-service-card__tag--red' : ''; ?>"><?php echo esc_html( $settings['subtitle'] ); ?></p>
				<?php endif; ?>
				<?php if ( $settings['heading'] ) : ?><h3 class="owp-widget-title"><?php echo esc_html( $settings['heading'] ); ?></h3><?php endif; ?>
				<?php if ( $settings['description'] ) : ?><p class="owp-service-card__description"><?php echo nl2br( esc_html( $settings['description'] ) ); ?></p><?php endif; ?>
				<?php $this->link( $settings['action_text'], $settings['action_url'], 'owp-action owp-action--primary' ); ?>
			</div>
		</article>
		<?php
	}
}
