<?php
/**
 * ECAE General Options
 *
 * @package ECAE
 */

?>

<div class="postbox">
	<h3 class="hndle"><span><?php esc_html_e( 'General Excerpt Options', 'easy-custom-auto-excerpt' ); ?></span></h3>
	<div class="inside" style="z-index:1;">
		<table class="form-table">
			<?php
			$excerpt_method_ar = array(
				'0' => array(
					'value' => 'paragraph',
					'label' => __( 'Paragraph', 'easy-custom-auto-excerpt' ),
				),
				'1' => array(
					'value' => 'word',
					'label' => __( 'Character', 'easy-custom-auto-excerpt' ),
				),
				'2' => array(
					'value' => '1st-paragraph',
					'label' => __( 'Show First Paragraph', 'easy-custom-auto-excerpt' ),
				),
				'3' => array(
					'value' => '2nd-paragraph',
					'label' => __( 'Show 1st - 2nd Paragraph', 'easy-custom-auto-excerpt' ),
				),
				'4' => array(
					'value' => '3rd-paragraph',
					'label' => __( 'Show 1st - 3rd Paragraph', 'easy-custom-auto-excerpt' ),
				),
			);

			$excerpt_method = array(
				'name'         => 'tonjoo_ecae_options[excerpt_method]',
				'description'  => __( 'Paragraph method will cut per paragraph. Character method will cut per word', 'easy-custom-auto-excerpt' ),
				'label'        => __( 'Excerpt method', 'easy-custom-auto-excerpt' ),
				'value'        => $options['excerpt_method'],
				'select_array' => $excerpt_method_ar,
			);

			tj_print_select_option( $excerpt_method );
			?>

			<tr valign="top">
				<th><?php esc_html_e( 'Excerpt size', 'easy-custom-auto-excerpt' ); ?></th>
				<td><input type="number" name="tonjoo_ecae_options[width]" value="<?php echo esc_attr( $options['width'] ); ?>"></td>
				<td><?php esc_html_e( 'Number of character preserved', 'easy-custom-auto-excerpt' ); ?></td>
			</tr>

			<?php

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

			$yes_no_select = array(
				'name'         => 'tonjoo_ecae_options[strip_shortcode]',
				'description'  => __( "If you select 'yes' any shortcode will be eliminated from the excerpt", 'easy-custom-auto-excerpt' ),
				'label'        => __( 'Strip shortcode', 'easy-custom-auto-excerpt' ),
				'value'        => $options['strip_shortcode'],
				'select_array' => $yes_no_options,
			);

			tj_print_select_option( $yes_no_select );

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

			$yes_no_select = array(
				'name'         => 'tonjoo_ecae_options[strip_empty_tags]',
				'description'  => __( "If you select 'yes' any empty HTML tags will be eliminated from the excerpt", 'easy-custom-auto-excerpt' ),
				'label'        => __( 'Strip empty HTML tags', 'easy-custom-auto-excerpt' ),
				'value'        => $options['strip_empty_tags'],
				'select_array' => $yes_no_options,
			);

			tj_print_select_option( $yes_no_select );

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

			$yes_no_select = array(
				'name'         => 'tonjoo_ecae_options[disable_on_feed]',
				'description'  => sprintf( __( 'Disable any excerpt on RSS feed. Click %1$shere%2$s to view your RSS page.', 'easy-custom-auto-excerpt' ), '<a href="' . get_bloginfo( 'rss2_url' ) . '" target="_blank">', '</a>' ),
				'label'        => __( 'Disable on RSS Feed', 'easy-custom-auto-excerpt' ),
				'value'        => $options['disable_on_feed'],
				'select_array' => $yes_no_options,
			);

			tj_print_select_option( $yes_no_select );

			$yes_no_select = array(
				'name'         => 'tonjoo_ecae_options[special_method]',
				'description'  => __( 'Use this method only if there are any problem with the excerpt', 'easy-custom-auto-excerpt' ),
				'label'        => __( 'Special method', 'easy-custom-auto-excerpt' ),
				'value'        => $options['special_method'],
				'select_array' => $yes_no_options,
			);

			tj_print_select_option( $yes_no_select );

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

			$excerpt_yes_options = array(
				'0' => array(
					'value' => 'no',
					'label' => __( 'No', 'easy-custom-auto-excerpt' ),
				),
				'1' => array(
					'value' => 'yes',
					'label' => __( 'Yes', 'easy-custom-auto-excerpt' ),
				),
			);

			$extra_html_markup = array(
				'label'       => __( 'Extra HTML markup', 'easy-custom-auto-excerpt' ),
				'name'        => 'tonjoo_ecae_options[extra_html_markup]',
				'value'       => $options['extra_html_markup'],
				'description' => __( 'Extra HTML markup to save. Use "|" (without quote) between markup', 'easy-custom-auto-excerpt' ),
			);

			/**
			 * There is are a bug
			 * jika excerpt tidak sampai 2 paragraf maka seluruh html tag di strip
			 * we have no time to fix it
			 * so we disable the kses code (regex.php)
			 * and hide this Extra HTML markup option
			 */
			// tj_print_text_option($extra_html_markup);
			?>
		</table>
	</div>
</div>

<div class="postbox">
	<h3 class="hndle">
		<span><?php esc_html_e( 'Typography Options', 'easy-custom-auto-excerpt' ); ?></span>
	</h3>
	<div class="inside" style="z-index:1;">
		<table class="form-table pro-form">
			<?php

			$justify_options = array(
				'0' => array(
					'value' => 'no',
					'label' => __( 'No Changes', 'easy-custom-auto-excerpt' ),
				),
				'1' => array(
					'value' => 'left',
					'label' => __( 'Left', 'easy-custom-auto-excerpt' ),
				),
				'2' => array(
					'value' => 'right',
					'label' => __( 'Right', 'easy-custom-auto-excerpt' ),
				),
				'3' => array(
					'value' => 'justify',
					'label' => __( 'Justify', 'easy-custom-auto-excerpt' ),
				),
				'4' => array(
					'value' => 'center',
					'label' => __( 'Center', 'easy-custom-auto-excerpt' ),
				),
			);

			$justify_select = array(
				'name'         => 'tonjoo_ecae_options[justify]',
				'description'  => __( 'The plugin will try to align the text on the excerpt page', 'easy-custom-auto-excerpt' ),
				'label'        => __( 'Text align', 'easy-custom-auto-excerpt' ),
				'value'        => $options['justify'],
				'select_array' => $justify_options,
			);

			tj_print_select_option( $justify_select );

			?>

			<tr valign="top">
				<th><?php esc_html_e( 'Font Size (in px)', 'easy-custom-auto-excerpt' ); ?></th>
				<td>
					<input type="number" name="tonjoo_ecae_options[font_size]" value="<?php echo esc_attr( $options['font_size'] ) ?>" min="0">
				</td>
				<td><?php esc_html_e( 'Left blank to make no changes to current font size', 'easy-custom-auto-excerpt' ) ?></td>
			</tr>
		</table>
	</div>
</div>

<div class="postbox">
	<h3 class="hndle">
		<span><?php esc_html_e( 'Display Image Options', 'easy-custom-auto-excerpt' ); ?></span>
		<?php if ( ! function_exists( 'is_ecae_premium_exist' ) ) : ?>
			<span class="upgrade-to-pro">
				<a href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_ecae&utm_medium=setting_image&utm_campaign=upsell">
					<i class="fa fa-rocket"></i>
					<?php esc_html_e( 'Upgrade to PRO to unlock all of these image options!', 'easy-custom-auto-excerpt' ); ?>
				</a>
			</span>
		<?php endif; ?>
	</h3>
	<div class="inside" style="z-index:1;">
		<table class="form-table pro-form">
			<?php
			$yes_no_options = array(
				'0' => array(
					'value' => 'no',
					'label' => __( 'No', 'easy-custom-auto-excerpt' ),
				),
				'1' => array(
					'value' => 'yes',
					'label' => __( 'Show All Images', 'easy-custom-auto-excerpt' ),
				),
				'2' => array(
					'value' => 'first-image',
					'label' => __( 'Show only First Image', 'easy-custom-auto-excerpt' ),
				),
				'3' => array(
					'value' => 'featured-image',
					'label' => __( 'Use Featured Image', 'easy-custom-auto-excerpt' ),
				),
			);

			$image_select = array(
				'name'         => 'tonjoo_ecae_options[show_image]',
				'description'  => '',
				'label'        => __( 'Content image', 'easy-custom-auto-excerpt' ),
				'value'        => $options['show_image'],
				'select_array' => $yes_no_options,
				'description'  => __( 'Display Image in excerpt', 'easy-custom-auto-excerpt' ),
			);

			tj_print_select_option( $image_select );

			// premium anouncement.
			// if ( ! function_exists( 'is_ecae_premium_exist' ) ) {
				echo "<tr><td colspan=3><div class='meta-subtitle'><i>";
				esc_html_e( 'Options below only work for Content Image: Show Only First Image and Use Featured Image', 'easy-custom-auto-excerpt' );
				echo '</i></div></td></tr>';
			// }

			$clickable_image = array(
				'name'         => 'tonjoo_ecae_options[clickable_image]',
				'description'  => '',
				'label'        => __( 'Clickable image', 'easy-custom-auto-excerpt' ) . $pro_label,
				'value'        => $options['clickable_image'],
				'select_array' => array(
					'0' => array(
						'value' => 'no',
						'label' => __( 'No', 'easy-custom-auto-excerpt' ),
					),
					'1' => array(
						'value' => 'yes',
						'label' => __( 'Yes', 'easy-custom-auto-excerpt' ),
					),
				),
				'description'  => __( 'If yes image will be linked to its single post', 'easy-custom-auto-excerpt' ),
				'premium'      => true,
			);

			tj_print_select_option( $clickable_image );

			$yes_no_options = array(
				'0' => array(
					'value' => 'none',
					'label' => __( 'None', 'easy-custom-auto-excerpt' ),
				),
				'1' => array(
					'value' => 'left',
					'label' => __( 'Left', 'easy-custom-auto-excerpt' ),
				),
				'2' => array(
					'value' => 'right',
					'label' => __( 'Right', 'easy-custom-auto-excerpt' ),
				),
				'3' => array(
					'value' => 'center',
					'label' => __( 'Center', 'easy-custom-auto-excerpt' ),
				),
				'4' => array(
					'value' => 'float-left',
					'label' => __( 'Float Left', 'easy-custom-auto-excerpt' ),
				),
				'5' => array(
					'value' => 'float-right',
					'label' => __( 'Float Right', 'easy-custom-auto-excerpt' ),
				),
			);

			$image_select = array(
				'name'         => 'tonjoo_ecae_options[image_position]',
				'description'  => '',
				'label'        => __( 'Image position', 'easy-custom-auto-excerpt' ) . $pro_label,
				'value'        => $options['image_position'],
				'select_array' => $yes_no_options,
				'description'  => __( 'Image position option', 'easy-custom-auto-excerpt' ),
				'premium'      => true,
			);

			tj_print_select_option( $image_select );

			?>

			<tr valign="top">
				<th><?php esc_html_e( 'Image width', 'easy-custom-auto-excerpt' ); ?><?php echo $pro_label; ?></th>
				<td>
					<input type="radio" name="tonjoo_ecae_options[image_width_type]" value="auto" <?php echo esc_attr( $disable_premium ); ?> <?php if ( $options['image_width_type'] == 'auto' ) { echo 'checked'; } ?>>
					Auto
				</td>
				<td>&nbsp;</td>
			</tr>

			<tr valign="top">
				<th>&nbsp;</th>
				<td>
					<input type="radio" name="tonjoo_ecae_options[image_width_type]" value="manual" <?php echo esc_attr( $disable_premium ); ?> <?php if ( 'manual' === $options['image_width_type'] ) { echo 'checked'; } ?>>
					<input type="number" name="tonjoo_ecae_options[image_width]" value="<?php echo esc_attr( $options['image_width'] ); ?>" <?php echo esc_attr( $disable_premium ); ?> style="width: 175px;margin-top: -5px;">
				</td>
				<td>&nbsp;</td>
			</tr>

			<tr valign="top">
				<th><?php esc_html_e( 'Image margin', 'easy-custom-auto-excerpt' ); ?><?php echo $pro_label; ?></th>
				<td>
					<p style="padding-top:0px;float:left;"><?php esc_html_e( 'Top', 'easy-custom-auto-excerpt' ); ?></p>
					<input type="number" name="tonjoo_ecae_options[image_padding_top]" <?php echo esc_attr( $disable_premium ); ?> value="<?php echo esc_attr( $options['image_padding_top'] ); ?>" style="float: right;width: 100px;" >
				</td>
				<td>px</td>
			</tr>

			<tr valign="top">
				<th>&nbsp;</th>
				<td>
					<p style="padding-top:0px;float:left;"><?php esc_html_e( 'Right', 'easy-custom-auto-excerpt' ); ?></p>
					<input type="number" name="tonjoo_ecae_options[image_padding_right]" <?php echo esc_attr( $disable_premium ); ?> value="<?php echo esc_attr( $options['image_padding_right'] ); ?>" style="float: right;width: 100px;" >
				</td>
				<td>px</td>
			</tr>

			<tr valign="top">
				<th>&nbsp;</th>
				<td>
					<p style="padding-top:0px;float:left;"><?php esc_html_e( 'Bottom', 'easy-custom-auto-excerpt' ); ?></p>
					<input type="number" name="tonjoo_ecae_options[image_padding_bottom]" <?php echo esc_attr( $disable_premium ); ?> value="<?php echo esc_attr( $options['image_padding_bottom'] ); ?>" style="float: right;width: 100px;" >
				</td>
				<td>px</td>
			</tr>

			<tr valign="top">
				<th>&nbsp;</th>
				<td>
					<p style="padding-top:0px;float:left;"><?php esc_html_e( 'Left', 'easy-custom-auto-excerpt' ); ?></p>
					<input type="number" name="tonjoo_ecae_options[image_padding_left]" <?php echo esc_attr( $disable_premium ); ?> value="<?php echo esc_attr( $options['image_padding_left'] ); ?>" style="float: right;width: 100px;" >
				</td>
				<td>px</td>
			</tr>

			<?php

			echo '<tr><td colspan="3"><div class="meta-subtitle"><i>';
			esc_html_e( 'Image thumbnail size options below only work for Content Image: Use Featured Image', 'easy-custom-auto-excerpt' );
			echo '</i></div></td></tr>';

			$image_thumbnail_size = array();
			foreach ( get_intermediate_image_sizes() as $key => $value ) {
				$image_thumbnail_size[] = array(
					'value' => $value,
					'label' => $value,
				);
			}

			$image_select = array(
				'name'         => 'tonjoo_ecae_options[image_thumbnail_size]',
				'description'  => '',
				'label'        => __( 'Image thumbnail size', 'easy-custom-auto-excerpt' ) . $pro_label,
				'value'        => $options['image_thumbnail_size'],
				'select_array' => $image_thumbnail_size,
				'premium'      => true,
			);

			tj_print_select_option( $image_select );
			?>
		</table>
	</div>
</div>
