<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Omni\WidgetPack\Plugin;

defined( 'ABSPATH' ) || exit;

final class Hero extends BaseWidget {
	private function filepino_milestones() {
		return array(
			'registration' => __( "Establishment &\nRegistration", 'omni-widget-pack' ),
			'operations'   => __( 'Operations', 'omni-widget-pack' ),
			'compliance'   => __( 'Compliance', 'omni-widget-pack' ),
			'people'       => __( 'People & HR', 'omni-widget-pack' ),
			'growth'       => __( "Sustainable\nGrowth", 'omni-widget-pack' ),
		);
	}

	public function get_style_depends() {
		return array( Plugin::FILEPINO_HERO_STYLE_HANDLE );
	}

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
		$this->field( 'layout', esc_html__( 'Layout', 'omni-widget-pack' ), Controls_Manager::SELECT, 'standard', array(
			'options' => array(
				'standard' => esc_html__( 'Standard', 'omni-widget-pack' ),
				'filepino' => esc_html__( 'FilePino Homepage', 'omni-widget-pack' ),
			),
		) );
		$standard = array( 'condition' => array( 'layout' => 'standard' ) );
		$filepino = array( 'condition' => array( 'layout' => 'filepino' ) );
		$this->field( 'heading', esc_html__( 'Headline', 'omni-widget-pack' ), Controls_Manager::TEXT, esc_html__( 'Build your business with confidence', 'omni-widget-pack' ), $standard );
		$this->field( 'description', esc_html__( 'Subhead', 'omni-widget-pack' ), Controls_Manager::TEXTAREA, null, $standard );
		$this->field( 'filepino_heading_intro', esc_html__( 'Headline First Line on Tablet and Mobile', 'omni-widget-pack' ), Controls_Manager::TEXT, __( 'Your Integrated', 'omni-widget-pack' ), $filepino );
		$this->field( 'filepino_heading', esc_html__( 'Headline Continuation', 'omni-widget-pack' ), Controls_Manager::TEXT, __( 'Business Services Platform', 'omni-widget-pack' ), $filepino );
		$this->field( 'filepino_badge', esc_html__( 'Tablet and Mobile Badge', 'omni-widget-pack' ), Controls_Manager::TEXT, __( 'Trusted Philippine business support', 'omni-widget-pack' ), $filepino );
		$this->field( 'filepino_tagline', esc_html__( 'Tablet and Mobile Tagline', 'omni-widget-pack' ), Controls_Manager::TEXT, __( 'Trusted expertise. From formation to growth.', 'omni-widget-pack' ), $filepino );
		$this->field( 'filepino_description', esc_html__( 'Description', 'omni-widget-pack' ), Controls_Manager::TEXTAREA, __( 'Establish your company with confidence. Get connected support for registration, operations, and compliance—so you can focus on growing your Philippine business.', 'omni-widget-pack' ), $filepino );
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
			'condition' => array( 'layout' => 'standard', 'show_milestones' => 'yes' ),
		) );
		foreach ( $this->filepino_milestones() as $icon => $label ) {
			$this->field( 'filepino_milestone_' . $icon, str_replace( "\n", ' ', $label ), Controls_Manager::TEXTAREA, $label, array(
				'condition' => array( 'layout' => 'filepino', 'show_milestones' => 'yes' ),
			) );
		}
		$this->end_controls_section();
		$this->typography_section( array(
			'heading' => array( esc_html__( 'Headline', 'omni-widget-pack' ), '.owp-hero__copy .owp-widget-heading' ),
			'body' => array( esc_html__( 'Subhead', 'omni-widget-pack' ), '.owp-hero__description' ),
			'action' => array( esc_html__( 'Actions', 'omni-widget-pack' ), '.owp-hero__copy .owp-action' ),
			'milestone' => array( esc_html__( 'Milestones', 'omni-widget-pack' ), ':is(.owp-hero__milestones, .owp-filepino-hero__journey .owp-milestones__label)' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( 'filepino' === ( $settings['layout'] ?? '' ) ) {
			$this->render_filepino( $settings );
			return;
		}

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

	private function render_filepino( $settings ) {
		$milestones = array();
		if ( 'yes' === $settings['show_milestones'] ) {
			foreach ( $this->filepino_milestones() as $icon => $default_label ) {
				$label = trim( $settings[ 'filepino_milestone_' . $icon ] ?? $default_label );
				if ( '' !== $label ) {
					$milestones[ $icon ] = $label;
				}
			}
		}
		?>
		<div class="owp-filepino-hero-shell">
			<section class="owp-filepino-hero owp-widget">
				<div class="owp-hero__copy">
					<?php if ( $settings['filepino_badge'] ) : ?>
						<div class="owp-filepino-hero__badge"><span aria-hidden="true">✓</span><p class="owp-filepino-hero__badge-text"><?php echo esc_html( $settings['filepino_badge'] ); ?></p></div>
					<?php endif; ?>
					<?php if ( $settings['filepino_heading_intro'] || $settings['filepino_heading'] ) : ?>
						<h1 class="owp-filepino-hero__heading owp-widget-heading"><?php
							if ( $settings['filepino_heading_intro'] ) {
								echo '<span class="owp-filepino-hero__heading-intro">' . esc_html( $settings['filepino_heading_intro'] ) . '</span> ';
							}
							echo esc_html( $settings['filepino_heading'] );
						?></h1>
					<?php endif; ?>
					<?php if ( $settings['filepino_tagline'] ) : ?>
						<p class="owp-filepino-hero__tagline"><?php echo esc_html( $settings['filepino_tagline'] ); ?></p>
					<?php endif; ?>
					<?php if ( $settings['filepino_description'] ) : ?>
						<p class="owp-filepino-hero__description owp-hero__description"><?php echo nl2br( esc_html( $settings['filepino_description'] ) ); ?></p>
					<?php endif; ?>
					<div class="owp-filepino-hero__actions owp-actions">
						<?php
						$this->link( $settings['primary_text'], $settings['primary_url'], 'owp-action owp-action--primary' );
						$this->link( $settings['secondary_text'], $settings['secondary_url'], 'owp-action owp-action--text', true );
						?>
					</div>
				</div>
				<div class="owp-filepino-hero__art" aria-hidden="true"></div>
				<?php if ( $milestones ) : ?>
					<div class="owp-filepino-hero__journey">
						<ol class="owp-milestones" aria-label="<?php esc_attr_e( 'Service milestones', 'omni-widget-pack' ); ?>">
							<?php foreach ( $milestones as $icon => $label ) : ?>
								<li class="owp-milestones__item">
									<span class="owp-milestones__marker"><img src="<?php echo esc_url( OWP_URL . 'assets/images/hero/' . $icon . '.svg' ); ?>" alt="" width="28" height="28"></span>
									<span class="owp-milestones__label"><?php echo nl2br( esc_html( $label ) ); ?></span>
								</li>
							<?php endforeach; ?>
						</ol>
					</div>
				<?php endif; ?>
			</section>
		</div>
		<?php
	}
}
