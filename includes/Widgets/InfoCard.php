<?php
/**
 * Info Card widget: icon, title, description, and an optional link.
 *
 * This widget doubles as the reference implementation for new pack widgets.
 *
 * @package DuranWidgetPack
 */

namespace Duran\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * A small reusable card for services, features, and benefits.
 */
class InfoCard extends BaseWidget {

	/**
	 * Unique widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'duran-info-card';
	}

	/**
	 * Label shown in the Elementor panel.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Info Card', 'duran-widget-pack' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-info-box';
	}

	/**
	 * Extra search keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array_merge( parent::get_keywords(), array( 'info', 'card', 'box', 'feature' ) );
	}

	/**
	 * Register content and style controls.
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Content tab controls.
	 */
	private function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'duran-widget-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'duran-widget-pack' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'duran-widget-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Card title', 'duran-widget-pack' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'duran-widget-pack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Describe this card in a sentence or two.', 'duran-widget-pack' ),
				'rows'    => 5,
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'duran-widget-pack' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'default'     => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'     => esc_html__( 'Link text', 'duran-widget-pack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Learn more', 'duran-widget-pack' ),
				'condition' => array( 'link[url]!' => '' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style tab controls.
	 */
	private function register_style_controls() {
		$this->register_card_style_controls();
		$this->register_icon_style_controls();
		$this->register_title_style_controls();
		$this->register_description_style_controls();
		$this->register_link_style_controls();
	}

	/**
	 * Card container styles.
	 */
	private function register_card_style_controls() {
		$this->start_controls_section(
			'section_card_style',
			array(
				'label' => esc_html__( 'Card', 'duran-widget-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'card_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .duran-info-card',
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'duran-widget-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .duran-info-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .duran-info-card',
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'duran-widget-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .duran-info-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .duran-info-card',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Icon styles.
	 */
	private function register_icon_style_controls() {
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'     => esc_html__( 'Icon', 'duran-widget-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'icon[value]!' => '' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'duran-widget-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .duran-info-card__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .duran-info-card__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => esc_html__( 'Size', 'duran-widget-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 12,
						'max' => 120,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .duran-info-card__icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .duran-info-card__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Title styles.
	 */
	private function register_title_style_controls() {
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Title', 'duran-widget-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'title!' => '' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'duran-widget-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .duran-info-card__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .duran-info-card__title',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Description styles.
	 */
	private function register_description_style_controls() {
		$this->start_controls_section(
			'section_description_style',
			array(
				'label'     => esc_html__( 'Description', 'duran-widget-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'description!' => '' ),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Color', 'duran-widget-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .duran-info-card__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .duran-info-card__description',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Link styles.
	 */
	private function register_link_style_controls() {
		$this->start_controls_section(
			'section_link_style',
			array(
				'label'     => esc_html__( 'Link', 'duran-widget-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'link[url]!' => '' ),
			)
		);

		$this->start_controls_tabs( 'link_colors' );

		$this->start_controls_tab(
			'link_colors_normal',
			array(
				'label' => esc_html__( 'Normal', 'duran-widget-pack' ),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Color', 'duran-widget-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .duran-info-card__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_colors_hover',
			array(
				'label' => esc_html__( 'Hover', 'duran-widget-pack' ),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'duran-widget-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .duran-info-card__link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .duran-info-card__link',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'card', 'class', 'duran-info-card' );

		$has_link = ! empty( $settings['link']['url'] );

		if ( $has_link ) {
			$this->add_link_attributes( 'link', $settings['link'] );
			$this->add_render_attribute( 'link', 'class', 'duran-info-card__link' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'card' ); ?>>
			<?php if ( ! empty( $settings['icon']['value'] ) ) : ?>
				<span class="duran-info-card__icon" aria-hidden="true">
					<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
				</span>
			<?php endif; ?>

			<?php if ( ! empty( $settings['title'] ) ) : ?>
				<h3 class="duran-info-card__title"><?php echo esc_html( $settings['title'] ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $settings['description'] ) ) : ?>
				<p class="duran-info-card__description"><?php echo nl2br( esc_html( $settings['description'] ) ); ?></p>
			<?php endif; ?>

			<?php if ( $has_link && ! empty( $settings['link_text'] ) ) : ?>
				<a <?php $this->print_render_attribute_string( 'link' ); ?>><?php echo esc_html( $settings['link_text'] ); ?></a>
			<?php endif; ?>
		</div>
		<?php
	}
}
