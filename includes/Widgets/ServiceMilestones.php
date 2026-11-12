<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

final class ServiceMilestones extends BaseWidget {
	public function get_name() {
		return 'owp-service-milestones';
	}

	public function get_title() {
		return esc_html__( 'Service Milestones', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	protected function register_controls() {
		$this->content_section();
		$item = new Repeater();
		$item->add_control( 'label', array(
			'label' => esc_html__( 'Label', 'omni-widget-pack' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
		) );
		$item->add_control( 'icon', array(
			'label' => esc_html__( 'Icon', 'omni-widget-pack' ),
			'type' => Controls_Manager::ICONS,
		) );

		$defaults = array();
		foreach ( array(
			'file-alt' => esc_html__( 'Establishment & Registration', 'omni-widget-pack' ),
			'cog' => esc_html__( 'Operations', 'omni-widget-pack' ),
			'shield-alt' => esc_html__( 'Compliance', 'omni-widget-pack' ),
			'user-friends' => esc_html__( 'People & HR', 'omni-widget-pack' ),
			'chart-line' => esc_html__( 'Sustainable Growth', 'omni-widget-pack' ),
		) as $icon => $label ) {
			$defaults[] = array(
				'label' => $label,
				'icon' => array( 'value' => 'fas fa-' . $icon, 'library' => 'fa-solid' ),
			);
		}
		$this->field( 'milestones', esc_html__( 'Milestones', 'omni-widget-pack' ), Controls_Manager::REPEATER, $defaults, array(
			'fields' => $item->get_controls(),
			'title_field' => '{{ label }}',
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'milestone_style', array(
			'label' => esc_html__( 'Markers and Glow', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		$selector = '{{WRAPPER}} .owp-milestones';
		foreach ( array(
			'marker_fill' => esc_html__( 'Marker Fill', 'omni-widget-pack' ),
			'marker_border' => esc_html__( 'Marker Border', 'omni-widget-pack' ),
			'icon_color' => esc_html__( 'Icon Color', 'omni-widget-pack' ),
			'label_color' => esc_html__( 'Label Color', 'omni-widget-pack' ),
			'connector_color' => esc_html__( 'Connector Color', 'omni-widget-pack' ),
			'glow_color' => esc_html__( 'Glow Color', 'omni-widget-pack' ),
		) as $key => $label ) {
			$this->add_control( $key, array(
				'label' => $label,
				'type' => Controls_Manager::COLOR,
				'selectors' => array( $selector => '--owp-milestone-' . str_replace( '_', '-', $key ) . ': {{VALUE}};' ),
			) );
		}
		$this->add_responsive_control( 'marker_size', array(
			'label' => esc_html__( 'Marker Size', 'omni-widget-pack' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range' => array( 'px' => array( 'min' => 28, 'max' => 100 ) ),
			'selectors' => array( $selector => '--owp-milestone-size: {{SIZE}}{{UNIT}};' ),
		) );
		$this->end_controls_section();

		$this->typography_section( array(
			'label' => array( esc_html__( 'Milestone Labels', 'omni-widget-pack' ), '.owp-milestones__label' ),
		) );
	}

	protected function render() {
		$items = $this->get_settings_for_display( 'milestones' );
		if ( empty( $items ) ) {
			return;
		}
		?>
		<ol class="owp-milestones owp-widget" aria-label="<?php esc_attr_e( 'Service milestones', 'omni-widget-pack' ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<li class="owp-milestones__item">
					<span class="owp-milestones__marker" aria-hidden="true">
						<?php Icons_Manager::render_icon( $item['icon'] ?? array(), array( 'aria-hidden' => 'true' ) ); ?>
					</span>
					<span class="owp-milestones__label"><?php echo esc_html( $item['label'] ?? '' ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php
	}
}
