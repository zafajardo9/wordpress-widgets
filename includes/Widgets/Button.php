<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Omni\WidgetPack\ButtonStyles;

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
		$this->field( 'label', esc_html__( 'Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Talk to Us', 'omni-widget-pack' ), array(
			'description' => esc_html__( 'For Icon Only, the label describes the action to screen readers.', 'omni-widget-pack' ),
		) );
		$this->field( 'url', esc_html__( 'Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '#contact' ) );
		$this->field( 'variant', esc_html__( 'Variant', 'omni-widget-pack' ), Controls_Manager::SELECT, 'primary', array(
			'options' => ButtonStyles::variants() + array(
				'custom' => __( 'Custom (inherits Primary)', 'omni-widget-pack' ),
				'icon' => __( 'Icon Only', 'omni-widget-pack' ),
			),
		) );
		$this->field( 'icon', esc_html__( 'Icon', 'omni-widget-pack' ), Controls_Manager::ICONS, null, array(
			'description' => esc_html__( 'Text Link and Icon Only use an arrow when no icon is selected.', 'omni-widget-pack' ),
		) );
		$this->field( 'icon_position', esc_html__( 'Icon Position', 'omni-widget-pack' ), Controls_Manager::SELECT, 'after', array(
			'options' => array(
				'before' => esc_html__( 'Before Text', 'omni-widget-pack' ),
				'after' => esc_html__( 'After Text', 'omni-widget-pack' ),
			),
			'condition' => array( 'variant!' => 'icon' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'button_style', array(
			'label' => esc_html__( 'Button', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		$this->add_control( 'background_color', array(
			'label' => esc_html__( 'Background Color', 'omni-widget-pack' ),
			'type' => Controls_Manager::COLOR,
			'condition' => array( 'variant!' => 'text' ),
			'selectors' => array( '{{WRAPPER}} a.owp-button' => '--owp-action-bg: {{VALUE}};' ),
		) );
		$this->add_control( 'text_color', array(
			'label' => esc_html__( 'Text Color', 'omni-widget-pack' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} a.owp-button' => '--owp-action-fg: {{VALUE}};' ),
		) );
		$this->add_responsive_control( 'radius', array(
			'label' => esc_html__( 'Border Radius', 'omni-widget-pack' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors' => array( '{{WRAPPER}} .owp-action' => 'border-radius: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'icon_gap', array(
			'label' => esc_html__( 'Icon Spacing', 'omni-widget-pack' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range' => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
			'condition' => array( 'variant!' => 'icon' ),
			'selectors' => array( '{{WRAPPER}} a.owp-button' => 'gap: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_control( 'underline_thickness', array(
			'label' => esc_html__( 'Underline Thickness', 'omni-widget-pack' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range' => array( 'px' => array( 'min' => 0, 'max' => 8 ) ),
			'condition' => array( 'variant' => 'text' ),
			'selectors' => array( '{{WRAPPER}} a.owp-button' => '--owp-action-underline: {{SIZE}}{{UNIT}};' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'button_hover', array(
			'label' => esc_html__( 'Hover Animation', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		$this->add_control( 'hover_effect', array(
			'label' => esc_html__( 'Effect', 'omni-widget-pack' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'fade',
			'options' => array(
				'none' => esc_html__( 'None', 'omni-widget-pack' ),
				'fade' => esc_html__( 'Color Fade', 'omni-widget-pack' ),
				'lift' => esc_html__( 'Lift', 'omni-widget-pack' ),
				'grow' => esc_html__( 'Grow', 'omni-widget-pack' ),
				'shrink' => esc_html__( 'Shrink', 'omni-widget-pack' ),
			),
			'prefix_class' => 'owp-button-hover-',
		) );
		$this->add_control( 'hover_duration', array(
			'label' => esc_html__( 'Duration (ms)', 'omni-widget-pack' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 0,
			'max' => 2000,
			'step' => 10,
			'default' => 160,
			'condition' => array( 'hover_effect!' => 'none' ),
			'selectors' => array( '{{WRAPPER}} a.owp-button' => '--owp-button-hover-duration: {{VALUE}}ms;' ),
		) );
		$this->add_control( 'hover_easing', array(
			'label' => esc_html__( 'Easing', 'omni-widget-pack' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'ease',
			'options' => array(
				'ease' => esc_html__( 'Ease', 'omni-widget-pack' ),
				'ease-in' => esc_html__( 'Ease In', 'omni-widget-pack' ),
				'ease-out' => esc_html__( 'Ease Out', 'omni-widget-pack' ),
				'ease-in-out' => esc_html__( 'Ease In and Out', 'omni-widget-pack' ),
				'linear' => esc_html__( 'Linear', 'omni-widget-pack' ),
			),
			'condition' => array( 'hover_effect!' => 'none' ),
			'selectors' => array( '{{WRAPPER}} a.owp-button' => '--owp-button-hover-easing: {{VALUE}};' ),
		) );
		$this->end_controls_section();

		$this->typography_section( array(
			'button' => array( esc_html__( 'Button', 'omni-widget-pack' ), '.owp-action' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$variant = $settings['variant'] ?? 'primary';
		if ( ! in_array( $variant, array( 'custom', 'icon' ), true ) && ! isset( ButtonStyles::variants()[ $variant ] ) ) {
			$variant = 'primary';
		}

		$icon = $settings['icon'] ?? array();
		$icon_only = 'icon' === $variant;
		if ( $icon_only && empty( $icon['value'] ) ) {
			$icon = array( 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' );
		}
		$class = 'owp-button owp-action owp-action--' . $variant;
		if ( ! $icon_only && 'before' === ( $settings['icon_position'] ?? '' ) ) {
			$class .= ' owp-button--icon-before';
		}
		$this->link( $settings['label'], $settings['url'], $class, 'text' === $variant && empty( $icon['value'] ), $icon, false, ! $icon_only );
	}
}
