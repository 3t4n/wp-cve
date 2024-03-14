<?php
/**
 * ECAE General Options
 *
 * @package ECAE
 */

?>

<div class="postbox">
	<h3 class="hndle"><span><?php esc_html_e( 'Read More Button', 'easy-custom-auto-excerpt' ); ?></span></h3>
	<div class="inside" style="z-index:1;">
		<table class="form-table">
			<?php
			$readmore_align_options = array(
				'0' => array(
					'value' => 'left',
					'label' => __( 'Left (default)', 'easy-custom-auto-excerpt' ),
				),
				'1' => array(
					'value' => 'center',
					'label' => __( 'Center', 'easy-custom-auto-excerpt' ),
				),
				'2' => array(
					'value' => 'right',
					'label' => __( 'Right', 'easy-custom-auto-excerpt' ),
				),
			);

			$yes_no_options = array(
				'0' => array(
					'value' => 'no',
					'label' => __( 'No', 'easy-custom-auto-excerpt' ),
				),
				'1' => array(
					'value' => 'yes',
					'label' => __( 'Yes', 'easy-custom-auto-excerpt' ),
				),
			);

			$button_display_option = array(
				'0' => array(
					'value' => 'normal',
					'label' => __( 'Normal', 'easy-custom-auto-excerpt' ),
				),
				'1' => array(
					'value' => 'always_show',
					'label' => __( 'Always Show', 'easy-custom-auto-excerpt' ),
				),
				'2' => array(
					'value' => 'always_hide',
					'label' => __( 'Always Hide', 'easy-custom-auto-excerpt' ),
				),
			);


			$text_options = array(
				'label'       => __( 'Read more text', 'easy-custom-auto-excerpt' ),
				'name'        => 'tonjoo_ecae_options[read_more]',
				'value'       => $options['read_more'],
				'description' => __( 'If you do not want to display it, fill with "-" (without quote)', 'easy-custom-auto-excerpt' ),
			);

			$readmore_text_before_options = array(
				'label' => __( 'Text before button link', 'easy-custom-auto-excerpt' ),
				'name'  => 'tonjoo_ecae_options[read_more_text_before]',
				'value' => $options['read_more_text_before'],
			);

			$text_after_content = array(
				'label'       => __( 'Text after content', 'easy-custom-auto-excerpt' ),
				'name'        => 'tonjoo_ecae_options[text_after_content]',
				'value'       => $options['text_after_content'],
				'description' => __( 'The text located right after your content, for example dots <code>[...]</code>. You can also style the text by css with this selector: <code>.ecae-dots</code>', 'easy-custom-auto-excerpt' ),
			);

			$readmore_inline = array(
				'name'         => 'tonjoo_ecae_options[readmore_inline]',
				'description'  => __( "If you select 'yes', the read more button will inline with the last paragraph", 'easy-custom-auto-excerpt' ),
				'label'        => __( 'Inline button', 'easy-custom-auto-excerpt' ),
				'value'        => $options['readmore_inline'],
				'select_array' => $yes_no_options,
			);

			$readmore_align_select = array(
				'name'         => 'tonjoo_ecae_options[read_more_align]',
				'description'  => __( 'This option will not work if option <b>Inline Button</b> is set to <b>Yes</b>', 'easy-custom-auto-excerpt' ),
				'label'        => __( 'Read more align', 'easy-custom-auto-excerpt' ),
				'value'        => $options['read_more_align'],
				'select_array' => $readmore_align_options,
			);

			$readmore_display = array(
				'name'         => 'tonjoo_ecae_options[button_display_option]',
				'description'  => __( 'Normal mode = show read more button, only if content length is bigger than excerpt size', 'easy-custom-auto-excerpt' ),
				'label'        => __( 'Display option', 'easy-custom-auto-excerpt' ),
				'value'        => $options['button_display_option'],
				'select_array' => $button_display_option,
			);

			tj_print_select_option( $readmore_display );
			tj_print_text_option( $text_options );
			tj_print_text_option( $text_after_content );
			tj_print_select_option( $readmore_inline );
			tj_print_select_option( $readmore_align_select );
			?>
		</table>
	</div>
</div>

<div class="postbox">
	<h3 class="hndle">
		<span><?php esc_html_e( 'Button Style', 'easy-custom-auto-excerpt' ); ?></span>
		<?php if ( ! function_exists( 'is_ecae_premium_exist' ) ) : ?>
			<span class="upgrade-to-pro">
				<a href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_ecae&utm_medium=setting_button&utm_campaign=upsell">
					<i class="fa fa-rocket"></i>
					<?php esc_html_e( 'Upgrade to PRO to unlock all of these image options!', 'easy-custom-auto-excerpt' ); ?>
				</a>
			</span>
		<?php endif; ?>
	</h3>
	<div class="inside" style="z-index:1;">
		<table class="form-table pro-form">
			<?php
			$dir             = dirname( __FILE__ ) . '/buttons';
			$skins           = scandir( $dir );
			$button_skin     = array();
			$button_skin_val = $options['button_skin'];
			if ( empty( $button_skin_val ) || 'none' === $button_skin_val ) {
				$button_skin_val = 'ecae-buttonskin-none';
			}

			$button_skin['ecae-buttonskin-none']  = array(
				'label' => 'None',
				'value' => 'ecae-buttonskin-none',
				'img'   => plugin_dir_url( __FILE__ ) . 'buttons/ecae-buttonskin-none.png',
			);
			$button_skin['ecae-buttonskin-black'] = array(
				'label' => 'Black',
				'value' => 'ecae-buttonskin-black',
				'img'   => plugin_dir_url( __FILE__ ) . 'buttons/ecae-buttonskin-black.png',
			);
			$button_skin['ecae-buttonskin-white'] = array(
				'label' => 'White',
				'value' => 'ecae-buttonskin-white',
				'img'   => plugin_dir_url( __FILE__ ) . 'buttons/ecae-buttonskin-white.png',
			);

			if ( function_exists( 'is_ecae_premium_exist' ) ) {
				$dir = dirname( plugin_dir_path( __FILE__ ) ) . '/' . ECAE_PREMIUM_DIR_NAME . '/buttons';

				$skins = scandir( $dir );

				foreach ( $skins as $key => $value ) {

					$extension        = pathinfo( $value, PATHINFO_EXTENSION );
					$filename         = pathinfo( $value, PATHINFO_FILENAME );
					$extension        = strtolower( $extension );
					$the_value        = strtolower( $filename );
					$filename_ucwords = str_replace( '-', ' ', $filename );
					$filename_ucwords = ucwords( $filename_ucwords );
					$filename_ucwords = str_replace( 'Ecae Buttonskin ', '', ucwords( $filename_ucwords ) );

					if ( 'css' === $extension ) {
						$button_skin[ $the_value ] = array(
							'label' => "$filename_ucwords (Pro)",
							'value' => "$the_value-PREMIUMtrue",
							'img'   => plugins_url( ECAE_PREMIUM_DIR_NAME . '/buttons/' . $the_value . '.png' ),
						);
					}
				}
			} else {
				$skins = scandir( plugin_dir_path( __FILE__ ) . 'assets/premium-promo' );

				foreach ( $skins as $key => $value ) {

					$extension        = pathinfo( $value, PATHINFO_EXTENSION );
					$filename         = pathinfo( $value, PATHINFO_FILENAME );
					$extension        = strtolower( $extension );
					$the_value        = strtolower( $filename );
					$filename_ucwords = str_replace( '-', ' ', $filename );
					$filename_ucwords = ucwords( $filename_ucwords );
					$filename_ucwords = str_replace( 'Ecae Buttonskin ', '', ucwords( $filename_ucwords ) );

					if ( 'png' === $extension ) {
						$button_skin[ $the_value ] = array(
							'label' => "$filename_ucwords (Pro)",
							'value' => "$the_value-PREMIUMtrue",
							'img'   => plugin_dir_url( __FILE__ ) . 'assets/premium-promo/' . $the_value . '.png',
						);
					}
				}

				if ( '-PREMIUMtrue' === substr( $button_skin_val, -12 ) ) {
					$button_skin_val = 'ecae-buttonskin-none';
				}
			}

			$button_skin_val2 = str_replace( '-PREMIUMtrue', '', $button_skin_val );
			?>

			<tr valign="top">
				<th><?php esc_html_e( 'Button style', 'easy-custom-auto-excerpt' ); ?></th>
				<td>
					<a href="#TB_inline?width=800&height=450&inlineId=customize-button" class="thickbox button-skin-selector" title="<?php esc_html_e( 'Button Style', 'easy-custom-auto-excerpt' ); ?>" id="button-skin-selector">
						<img src="<?php echo esc_url( $button_skin[ $button_skin_val2 ]['img'] ); ?>" alt="">
						<span><?php echo esc_html( $button_skin[ $button_skin_val2 ]['label'] ); ?></span>
					</a>

					<input type="hidden" name="tonjoo_ecae_options[button_skin]" value="<?php echo esc_attr( $button_skin_val ); ?>" id="button_skin">
				</td>
				<td>&nbsp;</td>
			</tr>

			<?php
			$button_font_array = array(
				'0'  => array(
					'value' => '',
					'label' => 'Use Content Font',
				),
				'1'  => array(
					'value' => 'Open Sans',
					'label' => 'Open Sans',
				),
				'2'  => array(
					'value' => 'Lobster',
					'label' => 'Lobster',
				),
				'3'  => array(
					'value' => 'Lobster Two',
					'label' => 'Lobster Two',
				),
				'4'  => array(
					'value' => 'Ubuntu',
					'label' => 'Ubuntu',
				),
				'5'  => array(
					'value' => 'Ubuntu Mono',
					'label' => 'Ubuntu Mono',
				),
				'6'  => array(
					'value' => 'Titillium Web',
					'label' => 'Titillium Web',
				),
				'7'  => array(
					'value' => 'Grand Hotel',
					'label' => 'Grand Hotel',
				),
				'8'  => array(
					'value' => 'Pacifico',
					'label' => 'Pacifico',
				),
				'9'  => array(
					'value' => 'Crafty Girls',
					'label' => 'Crafty Girls',
				),
				'10' => array(
					'value' => 'Bevan',
					'label' => 'Bevan',
				),
			);

			$button_font = array(
				'name'         => 'tonjoo_ecae_options[button_font]',
				'description'  => '',
				'label'        => __( 'Button font', 'easy-custom-auto-excerpt' ) . $pro_label,
				'value'        => $options['button_font'],
				'select_array' => $button_font_array,
				'premium'      => true,
			);

			tj_print_select_option( $button_font );

			if ( ! function_exists( 'is_ecae_premium_exist' ) ) {
				$options['button_font_size'] = '14';
			}

			?>

			<tr valign="top">
				<th><?php esc_html_e( 'Button font size', 'easy-custom-auto-excerpt' ); ?><?php echo $pro_label; ?></th>
				<td><input type="number" name="tonjoo_ecae_options[button_font_size]" <?php echo esc_attr( $disable_premium ); ?> value="<?php echo esc_attr( $options['button_font_size'] ); ?>"></td>
				<td>&nbsp;</td>
			</tr>

			<?php tj_print_text_option( $readmore_text_before_options ); ?>

			<tr valign="top">
				<th><?php esc_html_e( 'Customize button HTML', 'easy-custom-auto-excerpt' ); ?><?php echo $pro_label; ?></th>
				<td><button type="button" class="button-primary" id="customize-current-skin" <?php echo esc_attr( $disable_premium ); ?>><?php esc_html_e( 'Customize current skin', 'easy-custom-auto-excerpt' ); ?></button></td>
				<td>&nbsp;</td>
			</tr>

			<?php if ( function_exists( 'is_ecae_premium_exist' ) ) : ?>
				<tr valign="top" id="custom-html"<?php echo $options['custom_html'] ? '' : ' style="display:none"'; ?>>
					<th><?php esc_html_e( 'Custom HTML', 'easy-custom-auto-excerpt' ); ?><?php echo $pro_label; ?></th>
					<td>
						<div id="ace-editor-html"><?php echo esc_textarea( $options['custom_html'] ); ?></div>
						<textarea id="ace_editor_html_value" name="tonjoo_ecae_options[custom_html]" ><?php echo esc_textarea( $options['custom_html'] ); ?></textarea>
					</td>
				</tr>
			<?php endif; ?>

			<tr valign="top">
				<th><?php esc_html_e( 'Additional CSS', 'easy-custom-auto-excerpt' ); ?></th>
				<td>
					<div id="ace-editor"><?php echo esc_textarea( $options['custom_css'] ); ?></div>
					<textarea id="ace_editor_value" name="tonjoo_ecae_options[custom_css]" ><?php echo esc_textarea( $options['custom_css'] ); ?></textarea>
					<p style="font-size:14px;">
						<?php _e( 'Some css attributes need to use <code>!important</code> value to affect', 'easy-custom-auto-excerpt' ); ?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th colspan=3>
					<?php esc_html_e( 'You can manually add the button by putting this shortcode to your post', 'easy-custom-auto-excerpt' ); ?>: <code>[ecae_button]</code>
					<br /><br />
					<i><?php esc_html_e( 'Required "strip shortcode options" = No', 'easy-custom-auto-excerpt' ); ?></i>
				</th>
			</tr>
		</table>
	</div>
</div>

<div class="postbox excerpt-preview">
	<h3 class="hndle">
		<span><?php esc_html_e( 'Excerpt Preview', 'easy-custom-auto-excerpt' ); ?></span>
	</h3>
	<div class="inside">
		Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus, consectetur? Eveniet voluptatibus illo maiores ducimus eligendi velit fuga voluptate magni repudiandae numquam ipsam nesciunt fugit animi, voluptas repellat ut aliquid quia. Odio ipsa veritatis similique libero ipsum totam deserunt distinctio inventore doloremque, neque eveniet unde voluptate corporis tempora fugit. Ullam eius voluptates deserunt, ipsum exercitationem.
		<span id="ecae_ajax_preview_button"></span>
	</div>
</div>

<div id="customize-button" style="display:none">
	<div>
		<?php if ( ! function_exists( 'is_ecae_premium_exist' ) ) : ?>
			<div class="ecae-btn-unlock">
				<p><?php esc_html_e( 'Upgrade to PRO and unlock all of these 40+ beautiful and eye-catching button skins, ready for you to use.', 'easy-custom-auto-excerpt' ); ?></p>
				<a href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_ecae&utm_medium=setting_skin&utm_campaign=upsell" class="upgrade-to-unlock"><?php esc_html_e( 'Upgrade to Unlock', 'easy-custom-auto-excerpt' ); ?></a>
			</div>
		<?php endif; ?>
		<div class="ecae-btns">
			<?php $i = 1; ?>
			<?php foreach ( $button_skin as $k => $skin ) : ?>
				<?php
				$btn_available = true;
				if ( ! function_exists( 'is_ecae_premium_exist' ) && strpos( $skin['value'], '-PREMIUMtrue' ) !== false ) {
					$btn_available = false;
				}
				?>
				<div class="ecae-btn<?php echo $button_skin_val === $skin['value'] ? ' btn-selected' : ''; ?>">
					<?php if ( $btn_available ) : ?>
						<a href="#" data-value="<?php echo esc_attr( $skin['value'] ); ?>" data-img="<?php echo esc_url( $skin['img'] ); ?>" data-label="<?php echo esc_html( $skin['label'] ); ?>">
					<?php else : ?>
						<div>
					<?php endif; ?>
						<img src="<?php echo esc_url( $skin['img'] ); ?>">
						<div><?php echo esc_html( $skin['label'] ); ?></div>
						<span class="bg-selected"></span>
						<i class="fa fa-check" aria-hidden="true"></i>
					<?php if ( $btn_available ) : ?>
						</a>
					<?php else : ?>
						</div>
					<?php endif; ?>
					</a>
				</div>
				<?php $i++; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
