<?php
namespace Omni\WidgetPack\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Omni\WidgetPack\Plugin;

defined( 'ABSPATH' ) || exit;

final class Accordion extends BaseWidget {
	public function get_name() {
		return 'owp-accordion';
	}

	public function get_title() {
		return esc_html__( 'Accordion / FAQ', 'omni-widget-pack' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	public function get_script_depends() {
		return array( Plugin::INTERACTION_HANDLE );
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	protected function register_controls() {
		$this->content_section();
		$item = new Repeater();
		$item->add_control( 'question', array(
			'label' => esc_html__( 'Question', 'omni-widget-pack' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
		) );
		$item->add_control( 'answer', array(
			'label' => esc_html__( 'Answer', 'omni-widget-pack' ),
			'type' => Controls_Manager::WYSIWYG,
		) );
		$item->add_control( 'category', array(
			'label' => esc_html__( 'Category Badge', 'omni-widget-pack' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
		) );
		$item->add_control( 'badge_color', array(
			'label' => esc_html__( 'Badge Color', 'omni-widget-pack' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'yellow',
			'options' => array( 'yellow' => esc_html__( 'Yellow', 'omni-widget-pack' ), 'red' => esc_html__( 'Red', 'omni-widget-pack' ) ),
			'condition' => array( 'category!' => '' ),
		) );
		$this->field( 'items', esc_html__( 'Questions and Answers', 'omni-widget-pack' ), Controls_Manager::REPEATER, array(
			array(
				'question' => esc_html__( 'How do we get started?', 'omni-widget-pack' ),
				'answer' => esc_html__( 'Tell us about your goals so we can discuss the next steps.', 'omni-widget-pack' ),
			),
			array(
				'question' => esc_html__( 'How can I get in touch?', 'omni-widget-pack' ),
				'answer' => esc_html__( 'Use the contact form to send your questions.', 'omni-widget-pack' ),
			),
		), array(
			'fields' => $item->get_controls(),
			'title_field' => '{{{ question }}}',
		) );
		$this->field( 'expand_mode', esc_html__( 'Expand Mode', 'omni-widget-pack' ), Controls_Manager::SELECT, 'single', array(
			'options' => array( 'single' => esc_html__( 'Single', 'omni-widget-pack' ), 'multiple' => esc_html__( 'Multiple', 'omni-widget-pack' ) ),
		) );
		$this->field( 'faq_schema', esc_html__( 'Output FAQ Schema', 'omni-widget-pack' ), Controls_Manager::SWITCHER );
		$this->end_controls_section();
		$this->typography_section( array(
			'heading' => array( esc_html__( 'Questions', 'omni-widget-pack' ), '.owp-accordion__question' ),
			'body' => array( esc_html__( 'Answers', 'omni-widget-pack' ), '.owp-accordion__answer' ),
			'badge' => array( esc_html__( 'Badges', 'omni-widget-pack' ), '.owp-accordion .owp-badge' ),
		) );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$single = 'single' === $settings['expand_mode'];
		$group = wp_unique_id( 'owp-faq-' );
		$questions = array();
		?>
		<div class="owp-accordion owp-widget"<?php if ( $single ) : ?> data-owp-single<?php endif; ?>>
			<?php foreach ( $settings['items'] ?? array() as $item ) :
				$question = trim( $item['question'] ?? '' );
				if ( '' === $question ) {
					continue;
				}
				$answer = wp_kses_post( wpautop( $item['answer'] ?? '' ) );
				?>
				<details<?php if ( $single ) : ?> name="<?php echo esc_attr( $group ); ?>"<?php endif; ?>>
					<summary>
						<span class="owp-accordion__question owp-widget-heading"><?php echo esc_html( $question ); ?></span>
						<?php if ( ! empty( $item['category'] ) ) : ?>
							<span class="owp-badge<?php echo 'red' === ( $item['badge_color'] ?? '' ) ? ' owp-badge--red' : ''; ?>"><?php echo esc_html( $item['category'] ); ?></span>
						<?php endif; ?>
						<span class="owp-accordion__chevron" aria-hidden="true"></span>
					</summary>
					<div class="owp-accordion__answer"><?php echo $answer; ?></div>
				</details>
				<?php
				if ( 'yes' === $settings['faq_schema'] && '' !== trim( wp_strip_all_tags( $answer ) ) ) {
					$questions[] = array(
						'@type' => 'Question',
						'name' => $question,
						'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $answer ),
					);
				}
			endforeach; ?>
		</div>
		<?php if ( $questions ) : ?>
			<script type="application/ld+json"><?php echo wp_json_encode( array(
				'@context' => 'https://schema.org',
				'@type' => 'FAQPage',
				'mainEntity' => $questions,
			), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
		<?php endif;
	}
}
