<?php
namespace Omni\WidgetPack;

defined( 'ABSPATH' ) || exit;

final class ButtonStyles {
	const OPTION = 'owp_button_styles';

	public static function variants() {
		return array(
			'primary' => __( 'Primary', 'omni-widget-pack' ),
			'secondary' => __( 'Secondary', 'omni-widget-pack' ),
			'accent' => __( 'Accent', 'omni-widget-pack' ),
			'ghost' => __( 'Ghost', 'omni-widget-pack' ),
			'text' => __( 'Text Link with Arrow', 'omni-widget-pack' ),
		);
	}

	public static function register_setting() {
		register_setting( self::OPTION, self::OPTION, array(
			'type' => 'array',
			'sanitize_callback' => array( self::class, 'sanitize' ),
		) );
	}

	public static function sanitize( $input ) {
		$colors = array();
		foreach ( self::variants() as $variant => $label ) {
			$properties = 'text' === $variant ? array( 'fg' ) : array( 'bg', 'fg' );
			foreach ( $properties as $property ) {
				$value = $input[ $variant ][ $property ] ?? '';
				$color = is_string( $value ) ? sanitize_hex_color( trim( $value ) ) : '';
				if ( $color ) {
					$colors[ $variant ][ $property ] = $color;
				}
			}
		}
		return $colors;
	}

	public static function add_styles() {
		$declarations = array();
		foreach ( self::sanitize( get_option( self::OPTION, array() ) ) as $variant => $colors ) {
			foreach ( $colors as $property => $color ) {
				$declarations[] = '--owp-button-' . $variant . '-' . $property . ':' . $color;
			}
		}
		if ( $declarations ) {
			wp_add_inline_style( Plugin::STYLE_HANDLE, ':root{' . implode( ';', $declarations ) . '}' );
		}
	}

	public static function render_settings() {
		$colors = self::sanitize( get_option( self::OPTION, array() ) );
		$properties = array(
			'bg' => __( 'Background', 'omni-widget-pack' ),
			'fg' => __( 'Text', 'omni-widget-pack' ),
		);
		?>
		<h2><?php esc_html_e( 'Button Styles', 'omni-widget-pack' ); ?></h2>
		<p><?php esc_html_e( 'Set site-wide colors for each Omni button variant using hex values such as #F9C32E. Leave a field blank to use the preset. Custom buttons inherit Primary wherever their own colors are blank.', 'omni-widget-pack' ); ?></p>
		<?php settings_errors(); ?>
		<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="post">
			<?php settings_fields( self::OPTION ); ?>
			<table class="widefat striped">
				<thead><tr>
					<th scope="col"><?php esc_html_e( 'Variant', 'omni-widget-pack' ); ?></th>
					<?php foreach ( $properties as $label ) : ?>
						<th scope="col"><?php echo esc_html( $label ); ?></th>
					<?php endforeach; ?>
				</tr></thead>
				<tbody>
					<?php foreach ( self::variants() as $variant => $label ) : ?>
						<tr>
							<th scope="row"><?php echo esc_html( $label ); ?></th>
							<?php foreach ( $properties as $property => $property_label ) : ?>
								<td>
									<?php if ( 'text' !== $variant || 'bg' !== $property ) : ?>
										<input type="text" size="10" pattern="#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})"
											name="<?php echo esc_attr( self::OPTION . '[' . $variant . '][' . $property . ']' ); ?>"
											value="<?php echo esc_attr( $colors[ $variant ][ $property ] ?? '' ); ?>"
											aria-label="<?php echo esc_attr( $label . ' · ' . $property_label ); ?>">
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php submit_button( __( 'Save Button Styles', 'omni-widget-pack' ) ); ?>
		</form>
		<?php
	}
}
