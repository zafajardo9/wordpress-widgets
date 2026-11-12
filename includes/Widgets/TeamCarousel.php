<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Omni\WidgetPack\Plugin;

defined( 'ABSPATH' ) || exit;

final class TeamCarousel extends BaseWidget {
	public function get_name() {
		return 'owp-team-carousel';
	}

	public function get_title() {
		return esc_html__( 'Our Team Carousel', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_script_depends() {
		return array( Plugin::INTERACTION_HANDLE );
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	protected function register_controls() {
		$this->content_section();
		$this->field( 'team_heading', esc_html__( 'Team Heading', 'omni-widget-pack' ), Controls_Manager::WYSIWYG, esc_html__( 'Our Team', 'omni-widget-pack' ) );
		$member = new Repeater();
		$member->add_control( 'portrait', array(
			'label' => esc_html__( 'Portrait', 'omni-widget-pack' ),
			'type' => Controls_Manager::MEDIA,
		) );
		foreach ( array(
			'name' => esc_html__( 'Name', 'omni-widget-pack' ),
			'position' => esc_html__( 'Position', 'omni-widget-pack' ),
			'bio' => esc_html__( 'Profile Details', 'omni-widget-pack' ),
		) as $key => $label ) {
			$member->add_control( $key, array( 'label' => $label, 'type' => Controls_Manager::WYSIWYG ) );
		}
		$this->field( 'members', esc_html__( 'Team Members', 'omni-widget-pack' ), Controls_Manager::REPEATER, array(
			array(
				'name' => esc_html__( 'Alex Rivera', 'omni-widget-pack' ),
				'position' => esc_html__( 'Managing Director', 'omni-widget-pack' ),
				'bio' => esc_html__( 'Alex leads the team and works with clients to plan their next steps.', 'omni-widget-pack' ),
			),
			array(
				'name' => esc_html__( 'Jamie Santos', 'omni-widget-pack' ),
				'position' => esc_html__( 'Operations Manager', 'omni-widget-pack' ),
				'bio' => esc_html__( 'Jamie coordinates day-to-day operations and supports the delivery team.', 'omni-widget-pack' ),
			),
		), array(
			'fields' => $member->get_controls(),
			'title_field' => '{{{ name }}}',
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'navigation_icons', array(
			'label' => esc_html__( 'Navigation Icons', 'omni-widget-pack' ),
		) );
		$this->field( 'previous_icon', esc_html__( 'Previous Icon', 'omni-widget-pack' ), Controls_Manager::ICONS, array(
			'value' => 'fas fa-chevron-left',
			'library' => 'fa-solid',
		) );
		$this->field( 'next_icon', esc_html__( 'Next Icon', 'omni-widget-pack' ), Controls_Manager::ICONS, array(
			'value' => 'fas fa-chevron-right',
			'library' => 'fa-solid',
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'card_style', array(
			'label' => esc_html__( 'Cards', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		$carousel = '{{WRAPPER}} .owp-team';
		foreach ( array(
			'card_bg' => esc_html__( 'Card Background', 'omni-widget-pack' ),
			'card_text' => esc_html__( 'Card Text Color', 'omni-widget-pack' ),
			'active_bg' => esc_html__( 'Selected Card Background', 'omni-widget-pack' ),
			'active_text' => esc_html__( 'Selected Card Text Color', 'omni-widget-pack' ),
			'card_border' => esc_html__( 'Card Border Color', 'omni-widget-pack' ),
			'active_border' => esc_html__( 'Selected Card Border Color', 'omni-widget-pack' ),
		) as $key => $label ) {
			$this->add_control( $key, array(
				'label' => $label,
				'type' => Controls_Manager::COLOR,
				'selectors' => array( $carousel => '--owp-team-' . str_replace( '_', '-', $key ) . ': {{VALUE}};' ),
			) );
		}
		foreach ( array(
			'card_width' => array( esc_html__( 'Card Width', 'omni-widget-pack' ), 140, 400 ),
			'card_height' => array( esc_html__( 'Minimum Card Height', 'omni-widget-pack' ), 90, 320 ),
			'card_border_width' => array( esc_html__( 'Border Thickness', 'omni-widget-pack' ), 0, 12 ),
			'card_radius' => array( esc_html__( 'Corner Radius', 'omni-widget-pack' ), 0, 64 ),
			'card_gap' => array( esc_html__( 'Gap Between Cards', 'omni-widget-pack' ), 0, 48 ),
		) as $key => $control ) {
			$this->add_responsive_control( $key, array(
				'label' => $control[0],
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array( 'px' => array( 'min' => $control[1], 'max' => $control[2] ) ),
				'selectors' => array( $carousel => '--owp-team-' . str_replace( '_', '-', $key ) . ': {{SIZE}}{{UNIT}};' ),
			) );
		}
		$this->end_controls_section();

		$this->start_controls_section( 'text_style', array(
			'label' => esc_html__( 'Text Colors', 'omni-widget-pack' ),
			'tab' => Controls_Manager::TAB_STYLE,
		) );
		foreach ( array(
			'heading' => esc_html__( 'Team Heading', 'omni-widget-pack' ),
			'name' => esc_html__( 'Profile Name', 'omni-widget-pack' ),
			'position' => esc_html__( 'Profile Position', 'omni-widget-pack' ),
			'bio' => esc_html__( 'Profile Details', 'omni-widget-pack' ),
		) as $key => $label ) {
			$this->add_control( 'text_color_' . $key, array(
				'label' => $label,
				'type' => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .owp-team__' . $key => 'color: {{VALUE}};' ),
			) );
		}
		$this->end_controls_section();

		$this->typography_section( array(
			'team_heading' => array( esc_html__( 'Team Heading', 'omni-widget-pack' ), '.owp-team__heading' ),
			'profile_name' => array( esc_html__( 'Profile Name', 'omni-widget-pack' ), '.owp-team__name' ),
			'profile_position' => array( esc_html__( 'Profile Position', 'omni-widget-pack' ), '.owp-team__position' ),
			'profile_bio' => array( esc_html__( 'Profile Details', 'omni-widget-pack' ), '.owp-team__bio' ),
			'card_name' => array( esc_html__( 'Card Name', 'omni-widget-pack' ), '.owp-team__card-name' ),
			'card_position' => array( esc_html__( 'Card Position', 'omni-widget-pack' ), '.owp-team__card-position' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$members = array_values( $settings['members'] ?? array() );
		$id = 'owp-team-' . wp_generate_uuid4();
		?>
		<section class="owp-team owp-widget" aria-label="<?php echo esc_attr( wp_strip_all_tags( $settings['team_heading'] ?? '' ) ?: __( 'Our Team', 'omni-widget-pack' ) ); ?>">
			<?php foreach ( $members as $index => $member ) : ?>
				<article class="owp-team__profile" id="<?php echo esc_attr( $id . '-' . $index ); ?>"<?php if ( $index ) : ?> hidden<?php endif; ?>>
					<div class="owp-team__copy">
						<div class="owp-team__name"><?php echo wp_kses_post( wpautop( $member['name'] ?? '' ) ); ?></div>
						<div class="owp-team__position"><?php echo wp_kses_post( wpautop( $member['position'] ?? '' ) ); ?></div>
						<div class="owp-team__bio"><?php echo wp_kses_post( wpautop( $member['bio'] ?? '' ) ); ?></div>
					</div>
					<?php $this->image( $member['portrait'] ?? array(), 'large', 'owp-team__portrait' ); ?>
				</article>
			<?php endforeach; ?>
			<div class="owp-team__navigation">
				<div class="owp-team__heading"><?php echo wp_kses_post( wpautop( $settings['team_heading'] ?? '' ) ); ?></div>
				<?php if ( $members ) : ?>
					<div class="owp-team__controls">
						<?php if ( count( $members ) > 1 ) : ?>
							<button class="owp-team__arrow" type="button" data-owp-team-step="-1" aria-label="<?php esc_attr_e( 'Previous team member', 'omni-widget-pack' ); ?>">
								<?php if ( ! Icons_Manager::render_icon( $settings['previous_icon'] ?? array(), array( 'aria-hidden' => 'true' ) ) ) : ?>
									<span aria-hidden="true">‹</span>
								<?php endif; ?>
							</button>
						<?php endif; ?>
						<div class="owp-team__track" role="group" aria-label="<?php esc_attr_e( 'Team members', 'omni-widget-pack' ); ?>">
							<?php foreach ( $members as $index => $member ) : ?>
								<button class="owp-team__card" type="button" aria-controls="<?php echo esc_attr( $id . '-' . $index ); ?>" aria-pressed="<?php echo $index ? 'false' : 'true'; ?>">
									<span class="owp-team__card-copy">
										<span class="owp-team__card-name"><?php echo esc_html( wp_strip_all_tags( $member['name'] ?? '' ) ?: __( 'Team member', 'omni-widget-pack' ) ); ?></span>
										<span class="owp-team__card-position"><?php echo esc_html( wp_strip_all_tags( $member['position'] ?? '' ) ); ?></span>
									</span>
									<span class="owp-team__thumb" aria-hidden="true"><?php $this->image( $member['portrait'] ?? array(), 'medium' ); ?></span>
								</button>
							<?php endforeach; ?>
						</div>
						<?php if ( count( $members ) > 1 ) : ?>
							<button class="owp-team__arrow" type="button" data-owp-team-step="1" aria-label="<?php esc_attr_e( 'Next team member', 'omni-widget-pack' ); ?>">
								<?php if ( ! Icons_Manager::render_icon( $settings['next_icon'] ?? array(), array( 'aria-hidden' => 'true' ) ) ) : ?>
									<span aria-hidden="true">›</span>
								<?php endif; ?>
							</button>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
