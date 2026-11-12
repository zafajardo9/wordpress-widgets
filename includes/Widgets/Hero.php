<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

final class Hero extends BaseWidget {
	public function get_name() {
		return 'owp-hero';
	}

	public function get_title() {
		return esc_html__( 'Hero Section', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	protected function register_controls() {
		$this->content_section();
		$this->field( 'heading', esc_html__( 'Headline', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Build your business with confidence', 'omni-widget-pack' ) );
		$this->field( 'description', esc_html__( 'Subhead', 'omni-widget-pack' ), Controls_Manager::TEXTAREA );
		$this->field( 'primary_text', esc_html__( 'Primary Action Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Talk to Us', 'omni-widget-pack' ) );
		$this->field( 'primary_url', esc_html__( 'Primary Action Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '#contact' ) );
		$this->field( 'secondary_text', esc_html__( 'Secondary Action Label', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Explore our services', 'omni-widget-pack' ) );
		$this->field( 'secondary_url', esc_html__( 'Secondary Action Link', 'omni-widget-pack' ), Controls_Manager::URL, array( 'url' => '#services' ) );
		$this->field( 'show_milestones', esc_html__( 'Show Service Milestones', 'omni-widget-pack' ), Controls_Manager::SWITCHER, 'yes' );
		$milestone = new Repeater();
		$milestone->add_control( 'label', array(
			'label' => esc_html__( 'Milestone', 'omni-widget-pack' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
		) );
		$this->field( 'milestones', esc_html__( 'Service Milestones', 'omni-widget-pack' ), Controls_Manager::REPEATER, array(
			array( 'label' => esc_html__( 'Discover', 'omni-widget-pack' ) ),
			array( 'label' => esc_html__( 'Plan', 'omni-widget-pack' ) ),
			array( 'label' => esc_html__( 'Build', 'omni-widget-pack' ) ),
			array( 'label' => esc_html__( 'Launch', 'omni-widget-pack' ) ),
			array( 'label' => esc_html__( 'Grow', 'omni-widget-pack' ) ),
		), array(
			'fields' => $milestone->get_controls(),
			'title_field' => '{{{ label }}}',
			'condition' => array( 'show_milestones' => 'yes' ),
		) );
		$this->end_controls_section();
		$this->typography_section( array(
			'heading' => array( esc_html__( 'Headline', 'omni-widget-pack' ), '.owp-hero .owp-widget-heading' ),
			'body' => array( esc_html__( 'Subhead', 'omni-widget-pack' ), '.owp-hero__description' ),
			'action' => array( esc_html__( 'Actions', 'omni-widget-pack' ), '.owp-hero .owp-action' ),
			'milestone' => array( esc_html__( 'Milestones', 'omni-widget-pack' ), '.owp-hero__milestones' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$milestones = array_filter( array_column( $settings['milestones'] ?? array(), 'label' ), 'trim' );
		$show_milestones = 'yes' === $settings['show_milestones'] && $milestones;
		?>
		<section class="owp-hero owp-widget<?php echo $show_milestones ? ' owp-hero--split' : ''; ?>">
			<div class="owp-hero__copy">
				<?php if ( $settings['heading'] ) : ?><h1 class="owp-widget-heading"><?php echo esc_html( $settings['heading'] ); ?></h1><?php endif; ?>
				<?php if ( $settings['description'] ) : ?><p class="owp-hero__description"><?php echo nl2br( esc_html( $settings['description'] ) ); ?></p><?php endif; ?>
				<div class="owp-actions">
					<?php
					$this->link( $settings['primary_text'], $settings['primary_url'], 'owp-action owp-action--primary' );
					$this->link( $settings['secondary_text'], $settings['secondary_url'], 'owp-action owp-action--text', true );
					?>
				</div>
			</div>
			<?php if ( $show_milestones ) : ?>
				<ol class="owp-hero__milestones" aria-label="<?php esc_attr_e( 'Service milestones', 'omni-widget-pack' ); ?>">
					<?php foreach ( $milestones as $label ) : ?>
						<li><span><?php echo esc_html( $label ); ?></span></li>
					<?php endforeach; ?>
				</ol>
			<?php endif; ?>
		</section>
		<?php
	}
}
