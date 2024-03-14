<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WPC_Insert_Code
 * @author    Chris Baldelomar <chris@webplantmedia.com>
 * @license   GPL-2.0+
 * @link      http://webplantmedia.com
 * @copyright 2014 Chris Baldelomar
 */
?>
<?php
$plugin_prefix = $this->plugin->get_plugin_prefix();
$group_name = $this->plugin->get_plugin_slug() . '-group';
?>


<div id="wpc-insert-code-plugin" class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<p class="description"><?php echo __( sprintf( 'You can insert HTML, Javascript, and CSS, in any textarea below. Make sure you wrap your Javascript inside the %1$s tag, and your CSS inside the %2$s tag', '<code>' . esc_html( '<script>' ) . '</code>', '<code>' . esc_html( '<style>' ) . '</code>' ) ); ?></h2>

	<form method="post" action="options.php">

		<?php settings_fields( $group_name ); ?>

		<div>

			<h3><?php echo __( 'Head', 'wpc_insert_code' ); ?></h3>

			<div class="wpc-insert-code-textarea-wrapper">
				<textarea name="<?php echo $plugin_prefix; ?>_head" class="wpc-insert-code-textarea" rows="5" cols="30"><?php echo esc_textarea( get_option( $plugin_prefix . '_head', '' ) ); ?></textarea>
			</div>

		</div>

		<?php if ( $this->plugin->helper->test_theme_support_for_insert( 'top-of-page' ) ) : ?>
		
			<div>

				<h3><?php echo __( 'Top of Page', 'wpc_insert_code' ); ?></h3>

				<div class="wpc-insert-code-textarea-wrapper">
					<textarea name="<?php echo $plugin_prefix; ?>_top_of_page" class="wpc-insert-code-textarea" rows="5" cols="30"><?php echo esc_textarea( get_option( $plugin_prefix . '_top_of_page', '' ) ); ?></textarea>
				</div>

			</div>

		<?php endif; ?>

		<?php if ( $this->plugin->helper->test_theme_support_for_insert( 'above-header' ) ) : ?>
		
			<div>

				<h3><?php echo __( 'Above Header', 'wpc_insert_code' ); ?></h3>

				<div class="wpc-insert-code-textarea-wrapper">
					<textarea name="<?php echo $plugin_prefix; ?>_above_header" class="wpc-insert-code-textarea" rows="5" cols="30"><?php echo esc_textarea( get_option( $plugin_prefix . '_above_header', '' ) ); ?></textarea>
				</div>

			</div>

		<?php endif; ?>

		<?php if ( $this->plugin->helper->test_theme_support_for_insert( 'below-header' ) ) : ?>
		
			<div>

				<h3><?php echo __( 'Below Header', 'wpc_insert_code' ); ?></h3>

				<div class="wpc-insert-code-textarea-wrapper">
					<textarea name="<?php echo $plugin_prefix; ?>_below_header" class="wpc-insert-code-textarea" rows="5" cols="30"><?php echo esc_textarea( get_option( $plugin_prefix . '_below_header', '' ) ); ?></textarea>
				</div>

			</div>

		<?php endif; ?>

		<?php if ( $this->plugin->helper->test_theme_support_for_insert( 'above-content' ) ) : ?>
		
			<div>

				<h3><?php echo __( 'Above Content', 'wpc_insert_code' ); ?></h3>

				<div class="wpc-insert-code-textarea-wrapper">
					<textarea name="<?php echo $plugin_prefix; ?>_above_content" class="wpc-insert-code-textarea" rows="5" cols="30"><?php echo esc_textarea( get_option( $plugin_prefix . '_above_content', '' ) ); ?></textarea>
				</div>

			</div>

		<?php endif; ?>

		<?php if ( $this->plugin->helper->test_theme_support_for_insert( 'below-content' ) ) : ?>
		
			<div>

				<h3><?php echo __( 'Below Content', 'wpc_insert_code' ); ?></h3>

				<div class="wpc-insert-code-textarea-wrapper">
					<textarea name="<?php echo $plugin_prefix; ?>_below_content" class="wpc-insert-code-textarea" rows="5" cols="30"><?php echo esc_textarea( get_option( $plugin_prefix . '_below_content', '' ) ); ?></textarea>
				</div>

			</div>

		<?php endif; ?>

		<div>

			<h3><?php echo __( 'Footer', 'wpc_insert_code' ); ?></h3>

			<div class="wpc-insert-code-textarea-wrapper">
				<textarea name="<?php echo $plugin_prefix; ?>_footer" class="wpc-insert-code-textarea" rows="5" cols="30"><?php echo esc_textarea( get_option( $plugin_prefix . '_footer', '' ) ); ?></textarea>
			</div>

		</div>

		<?php submit_button(); ?>

	</form>

</div>
