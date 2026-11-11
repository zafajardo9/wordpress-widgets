<?php
/**
 * Shared base class for every widget in the pack.
 *
 * @package DuranWidgetPack
 */

namespace Duran\WidgetPack\Widgets;

use Elementor\Widget_Base;

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
		return array( \Duran\WidgetPack\Plugin::CATEGORY );
	}

	/**
	 * Common search keywords for the Elementor panel.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'duran', 'pack', 'component' );
	}

	/**
	 * Load the pack stylesheet whenever a pack widget is rendered.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( \Duran\WidgetPack\Plugin::STYLE_HANDLE );
	}
}
