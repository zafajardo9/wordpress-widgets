<?php
/**
 * Shared base class for every widget in the pack.
 *
 * @package OmniWidgetPack
 */

namespace Omni\WidgetPack\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Applies pack-wide defaults so individual widgets stay focused on their own
 * fields and markup.
 */
abstract class BaseWidget extends Widget_Base {

	/**
	 * Group every pack widget under its own Elementor category.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( \Omni\WidgetPack\Plugin::CATEGORY );
	}

	/**
	 * Common search keywords for the Elementor panel.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'omni', 'pack', 'component', 'widget' );
	}

	/**
	 * Load the pack stylesheet whenever a pack widget is rendered.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( \Omni\WidgetPack\Plugin::STYLE_HANDLE );
	}

	protected function content_section() {
		$this->start_controls_section(
			'content',
			array( 'label' => esc_html__( 'Content', 'omni-widget-pack' ) )
		);
	}

	protected function field( $id, $label, $type = Controls_Manager::TEXT, $default = null, $args = array() ) {
		if ( null !== $default ) {
			$args['default'] = $default;
		}
		$this->add_control( $id, $args + array(
			'label' => $label,
			'type' => $type,
			'label_block' => true,
		) );
	}

	protected function typography_section( $selectors ) {
		$this->start_controls_section( 'typography', array(
			'label' => esc_html__( 'Typography', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		foreach ( $selectors as $id => list( $label, $selector ) ) {
			$this->add_group_control( Group_Control_Typography::get_type(), array(
				'name' => $id . '_typography',
				'label' => $label,
				'selector' => '{{WRAPPER}} ' . $selector,
			) );
		}
		$this->end_controls_section();
	}

	protected function link_repeater( $id, $label, $defaults, $with_icon = false ) {
		$row = new Repeater();
		$row->add_control( 'label', array(
			'label' => esc_html__( 'Label', 'omni-widget-pack' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
		) );
		$row->add_control( 'url', array(
			'label' => esc_html__( 'Link', 'omni-widget-pack' ),
			'type' => Controls_Manager::URL,
			'options' => array( 'url', 'is_external', 'nofollow' ),
		) );
		if ( $with_icon ) {
			$row->add_control( 'icon', array(
				'label' => esc_html__( 'Icon', 'omni-widget-pack' ),
				'type' => Controls_Manager::ICONS,
			) );
		}
		$this->add_control( $id, array(
			'label' => $label,
			'type' => Controls_Manager::REPEATER,
			'fields' => $row->get_controls(),
			'default' => $defaults,
			'title_field' => '{{{ label }}}',
		) );
	}

	protected function link( $label, $link, $class = '', $arrow = false, $icon = array(), $current = false, $show_icon_label = false ) {
		$url = esc_url( $link['url'] ?? '' );
		if ( '' === $url || '' === trim( $label ) ) {
			return;
		}
		$external = ! empty( $link['is_external'] );
		$rel = trim( ( $external ? 'noopener noreferrer ' : '' ) . ( ! empty( $link['nofollow'] ) ? 'nofollow' : '' ) );
		?>
		<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo $url; ?>"
			<?php if ( $external ) : ?> target="_blank"<?php endif; ?>
			<?php if ( $rel ) : ?> rel="<?php echo esc_attr( $rel ); ?>"<?php endif; ?>
			<?php if ( $current ) : ?> aria-current="page"<?php endif; ?>>
			<?php if ( ! empty( $icon['value'] ) ) : ?>
				<span<?php if ( ! $show_icon_label ) : ?> class="owp-visually-hidden"<?php endif; ?>><?php echo esc_html( $label ); ?></span>
				<span class="owp-action__icon" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?></span>
			<?php else : ?>
				<?php echo esc_html( $label ); ?>
			<?php endif; ?>
			<?php if ( $arrow ) : ?><span class="owp-arrow" aria-hidden="true">→</span><?php endif; ?>
		</a>
		<?php
	}

	protected function image( $image, $size = 'large', $class = '' ) {
		if ( ! empty( $image['id'] ) ) {
			$html = wp_get_attachment_image( absint( $image['id'] ), $size, false, array( 'class' => $class ) );
			if ( $html ) {
				echo $html;
				return;
			}
		}
		$url = esc_url( $image['url'] ?? '' );
		if ( $url ) {
			printf( '<img class="%s" src="%s" alt="%s">', esc_attr( $class ), $url, esc_attr( $image['alt'] ?? '' ) );
		}
	}
}
