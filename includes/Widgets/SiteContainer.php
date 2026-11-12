<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Modules\NestedElements\Base\Widget_Nested_Base;
use Omni\WidgetPack\Plugin;

defined( 'ABSPATH' ) || exit;

final class SiteContainer extends Widget_Nested_Base {
	public function get_name() {
		return 'owp-container';
	}

	public function get_title() {
		return esc_html__( 'Site Container', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-container';
	}

	public function get_categories() {
		return array( Plugin::CATEGORY );
	}

	public function get_style_depends() {
		return array( Plugin::STYLE_HANDLE );
	}

	protected function get_default_children_elements() {
		return array( array(
			'elType' => 'container',
			'settings' => array(
				'_title' => esc_html__( 'Section Content', 'omni-widget-pack' ),
				'content_width' => 'full',
				'padding' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
			),
			'elements' => array(),
		) );
	}

	protected function get_default_repeater_title_setting_key() {
		return '';
	}

	protected function get_default_children_placeholder_selector() {
		return '.owp-container__inner';
	}

	protected function register_controls() {
		$shell = '{{WRAPPER}} > .owp-container, {{WRAPPER}} > .elementor-widget-container > .owp-container';
		$this->start_controls_section( 'layout', array( 'label' => esc_html__( 'Layout', 'omni-widget-pack' ) ) );

		$widths = array( '100%' => esc_html__( 'Full Width', 'omni-widget-pack' ) );
		foreach ( array( 1140, 1200, 1280, 1320, 1440 ) as $width ) {
			$widths[ $width . 'px' ] = $width . ' px';
		}
		$this->add_control( 'content_width', array(
			'label' => esc_html__( 'Content Max Width', 'omni-widget-pack' ),
			'type' => Controls_Manager::SELECT,
			'default' => '1280px',
			'options' => $widths,
			'selectors' => array( $shell => '--owp-content-width: {{VALUE}};' ),
		) );
		$this->add_control( 'deadzone', array(
			'label' => esc_html__( 'Horizontal Gutters', 'omni-widget-pack' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'standard',
			'options' => array(
				'standard' => esc_html__( 'Standard', 'omni-widget-pack' ),
				'compact' => esc_html__( 'Compact', 'omni-widget-pack' ),
				'custom' => esc_html__( 'Custom', 'omni-widget-pack' ),
			),
			'prefix_class' => 'owp-gutter-',
		) );
		$this->add_responsive_control( 'custom_gutter', array(
			'label' => esc_html__( 'Custom Gutter', 'omni-widget-pack' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem', '%' ),
			'range' => array( 'px' => array( 'min' => 0, 'max' => 160 ) ),
			'condition' => array( 'deadzone' => 'custom' ),
			'selectors' => array( $shell => 'padding-inline: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_control( 'vertical_padding', array(
			'label' => esc_html__( 'Vertical Padding', 'omni-widget-pack' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'standard',
			'options' => array(
				'standard' => esc_html__( 'Standard', 'omni-widget-pack' ),
				'compact' => esc_html__( 'Compact', 'omni-widget-pack' ),
				'none' => esc_html__( 'None', 'omni-widget-pack' ),
			),
			'prefix_class' => 'owp-vertical-',
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'appearance', array(
			'label' => esc_html__( 'Appearance', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		$this->add_control( 'surface', array(
			'label' => esc_html__( 'Background Preset', 'omni-widget-pack' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'white',
			'options' => array(
				'white' => esc_html__( 'White', 'omni-widget-pack' ),
				'blue' => esc_html__( 'Blue', 'omni-widget-pack' ),
				'tint' => esc_html__( 'Tint', 'omni-widget-pack' ),
			),
			'prefix_class' => 'owp-surface-',
		) );
		$this->add_group_control( Group_Control_Background::get_type(), array(
			'name' => 'background',
			'types' => array( 'classic', 'gradient' ),
			'selector' => $shell,
		) );
		$this->add_control( 'text_color', array(
			'label' => esc_html__( 'Text Color', 'omni-widget-pack' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => array( $shell => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name' => 'base_typography',
			'label' => esc_html__( 'Base Typography', 'omni-widget-pack' ),
			'selector' => $shell,
			'fields_options' => array(
				'line_height' => array(
					'size_units' => array( 'em', 'px', 'rem' ),
					'selectors' => array( '{{SELECTOR}}' => '--owp-base-line-height: {{SIZE}}{{UNIT}};' ),
				),
			),
		) );
		$this->add_control( 'heading_font', array(
			'label' => esc_html__( 'Heading Font Family', 'omni-widget-pack' ),
			'type' => Controls_Manager::FONT,
			'selectors' => array( $shell => '--owp-heading-font: "{{VALUE}}";' ),
		) );
		$this->end_controls_section();
	}

	protected function render() {
		?>
		<div class="owp-container"><div class="owp-container__inner">
			<?php foreach ( $this->get_children() as $child ) {
				$child->print_element();
			} ?>
		</div></div>
		<?php
	}

	protected function content_template() {
		?><div class="owp-container"><div class="owp-container__inner"></div></div><?php
	}
}
