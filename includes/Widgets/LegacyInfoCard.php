<?php
namespace Omni\WidgetPack\Widgets;

defined( 'ABSPATH' ) || exit;

/** Continue rendering Elementor documents saved before the Omni rename. */
final class LegacyInfoCard extends InfoCard {
	public function get_name() {
		return 'duran-info-card';
	}

	public function show_in_panel() {
		return false;
	}
}
