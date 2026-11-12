<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || exit;

final class FeatureCard extends BaseWidget {
	public function get_name() {
		return 'owp-feature-card';
	}

	public function get_title() {
		return esc_html__( 'Feature Card', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-icon-box';
	}

	protected function register_controls() {
		$this->content_section();
		$this->field( 'icon', esc_html__( 'Icon', 'omni-widget-pack' ), Controls_Manager::ICONS, array( 'value' => 'fas fa-check', 'library' => 'fa-solid' ) );
		$this->field( 'heading', esc_html__( 'Title', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Feature title', 'omni-widget-pack' ) );
		$this->field( 'description', esc_html__( 'Description', 'omni-widget-pack' ), Controls_Manager::TEXTAREA );
		$this->end_controls_section();
		$this->start_controls_section( 'appearance', array(
			'label' => esc_html__( 'Appearance', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		$this->field( 'accent', esc_html__( 'Icon Color', 'omni-widget-pack' ), Controls_Manager::SELECT, 'blue', array(
			'options' => array( 'blue' => esc_html__( 'Blue', 'omni-widget-pack' ), 'red' => esc_html__( 'Red', 'omni-widget-pack' ) ),
		) );
		$this->end_controls_section();
		$this->typography_section( array(
			'heading' => array( esc_html__( 'Title', 'omni-widget-pack' ), '.owp-feature-card .owp-widget-title' ),
			'body' => array( esc_html__( 'Description', 'omni-widget-pack' ), '.owp-feature-card__description' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<article class="owp-feature-card owp-widget<?php echo 'red' === $settings['accent'] ? ' owp-feature-card--red' : ''; ?>">
			<?php if ( ! empty( $settings['icon']['value'] ) ) : ?>
				<span class="owp-feature-card__icon" aria-hidden="true"><?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
			<?php endif; ?>
			<?php if ( $settings['heading'] ) : ?><h3 class="owp-widget-title"><?php echo esc_html( $settings['heading'] ); ?></h3><?php endif; ?>
			<?php if ( $settings['description'] ) : ?><p class="owp-feature-card__description"><?php echo nl2br( esc_html( $settings['description'] ) ); ?></p><?php endif; ?>
		</article>
		<?php
	}
}
