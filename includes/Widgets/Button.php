<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class Button extends BaseWidget {
	public function get_name() {
		return 'owp-button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	protected function register_controls() {
		$this->content_section();
		$this->field( 'label', esc_html__( 'Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Talk to Us', 'omni-widget-pack' ) );
		$this->field( 'url', esc_html__( 'Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '#contact' ) );
		$this->field( 'variant', esc_html__( 'Variant', 'omni-widget-pack' ), Controls_Manager::SELECT, 'primary', array(
			'options' => array(
				'primary' => esc_html__( 'Primary (Yellow)', 'omni-widget-pack' ),
				'secondary' => esc_html__( 'Secondary (Blue)', 'omni-widget-pack' ),
				'accent' => esc_html__( 'Accent (Red)', 'omni-widget-pack' ),
				'text' => esc_html__( 'Text Link with Arrow', 'omni-widget-pack' ),
			),
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'button_style', array(
			'label' => esc_html__( 'Button', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		$this->add_responsive_control( 'radius', array(
			'label' => esc_html__( 'Border Radius', 'omni-widget-pack' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors' => array( '{{WRAPPER}} .owp-action' => 'border-radius: {{SIZE}}{{UNIT}};' ),
		) );
		$this->end_controls_section();
		$this->typography_section( array(
			'button' => array( esc_html__( 'Button', 'omni-widget-pack' ), '.owp-action' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$variant = $settings['variant'] ?? 'primary';
		if ( ! in_array( $variant, array( 'primary', 'secondary', 'accent', 'text' ), true ) ) {
			$variant = 'primary';
		}

		$this->link( $settings['label'], $settings['url'], 'owp-button owp-action owp-action--' . $variant, 'text' === $variant );
	}
}
