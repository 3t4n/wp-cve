<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Awesome Icon Admin View
 *
 * @package YITH
 * @author YITH <plugins@yithemes.com>
 * @since 1.0.0
 */

extract( $args ); //phpcs:ignore


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$current_options = wp_parse_args( $args['value'], $args['std'] );
$current_icon    = YPOP_Icon()->get_icon_data( $current_options['icon'] );


$options['icon'] = YIT_Plugin_Common::get_icon_list();


?>



<div id="<?php echo esc_attr( $id ); ?>-container"
					<?php
					if ( isset( $deps ) ) :
						?>
	data-field="<?php echo esc_attr( $id ); ?>" data-dep="<?php echo esc_attr( $deps['ids'] ); ?>" data-value="<?php echo esc_attr( $deps['values'] ); ?>" <?php endif ?>class="select_icon rm_option rm_input rm_text">
	<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>

	<div class="option">
		<div class="select_wrapper icon_list_type clearfix">
			<select name="<?php echo esc_attr( $name ); ?>[select]" id="<?php echo esc_attr( $id ); ?>[select]" <?php if ( isset( $std['select'] ) ) : ?>
				data-std="<?php echo esc_attr( $std['select'] ); ?>"<?php endif; ?>>
				<?php foreach ( $options['select'] as $val => $option ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $current_options['select'], $val ); ?> ><?php echo esc_html( $option ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>


		<div class="icon-manager-wrapper">
			<div class="icon-manager-text">
				<div class="icon-preview" <?php echo esc_attr( $current_icon ); ?>></div>
				<input type="text" id="<?php echo esc_attr( $id ); ?>[icon]" class="icon-text" name="<?php echo esc_attr( $name ); ?>[icon]" value="<?php echo esc_attr( $current_options['icon'] ); ?>" />
			</div>


			<div class="icon-manager">
				<ul class="icon-list-wrapper">
					<?php
					foreach ( $options['icon'] as $font => $icons ) :
						foreach ( $icons as $key => $icon ) :
							?>
							<li data-font="<?php echo esc_attr( $font ); ?>" data-icon="<?php echo esc_attr( ( strpos( $key, '\\' ) === 0 ) ? '&#x' . substr( $key, 1 ) : $key ); ?>" data-key="<?php echo esc_attr( $key ); ?>" data-name="<?php echo esc_attr( $icon ); ?>"></li>
							<?php
						endforeach;
					endforeach;
					?>
				</ul>
			</div>
		</div>


		<div class="input_wrapper custom_icon_wrapper upload" style="clear:both;">
			<input type="text" name="<?php echo esc_attr( $name ); ?>[custom]" id="<?php echo esc_attr( $id ); ?>-custom" value="<?php echo esc_attr( $current_options['custom'] ); ?>" class="upload_img_url upload_custom_icon" />
			<input type="button" value="<?php esc_attr_e( 'Upload', 'yith-woocommerce-popup' ); ?>" id="<?php echo esc_attr( $id ); ?>-custom-button" class="upload_button button" />

			<div class="upload_img_preview" style="margin-top:10px;">
				<?php
				$file = $current_options['custom'];
				if ( ! preg_match( '/(jpg|jpeg|png|gif|ico)$/', $file ) ) {
					$file = YITH_YPOP_ASSETS_URL . 'images/sleep.png';
				}
				?>
				<?php esc_html_e( 'Image preview', 'yith-woocommerce-popup' ); ?> : <img src="<?php echo esc_url( $file ); ?>"  style="max-width :27px;max-height: 25px;"/>
			</div>
		</div>

	</div>

	<div class="clear"></div>


	<div class="description">
		<?php echo esc_html( $desc ); ?>
		<?php if ( 'custom' === $std['select'] ) : ?>
			<?php
			// translators: 1.image name 2.image url.
			printf( wp_kses_post( __( '(Default: %1$s <img src="%2$s"/>)', 'yith-woocommerce-popup' ) ), esc_html( $options['select']['custom'] ), esc_html( $std['custom'] ) );
			?>
		<?php else : ?>
			<?php
			// translators: 1.image class.
			printf( wp_kses_post( __( '(Default: <i %s></i> )', 'yith-woocommerce-popup' ) ), esc_attr( $current_icon ) );
			?>
		<?php endif; ?>
	</div>

	<div class="clear"></div>

</div>

<script>

	jQuery(document).ready( function($){

		$('.select_wrapper.icon_list_type').on('change', function(){

			var t       = $(this);
			var parents = $('#' + t.parents('div.select_icon').attr('id'));
			var option  = $('option:selected', this).val();
			var to_show = option == 'none' ? '' : option == 'icon'  ? '.icon-manager-wrapper' : '.custom_icon_wrapper';

			parents.find('.option > div:not(.icon_list_type)').removeClass('show').addClass('hidden');
			parents.find( to_show ).removeClass( 'hidden' ).addClass( 'show' );
		});

		$('.select_wrapper.icon_list_type').trigger('change');

		var $icon_list = $('.select_icon').find('ul.icon-list-wrapper'),
			$preview = $('.icon-preview'),
			$element_list = $icon_list.find('li'),
			$icon_text = $('.icon-text');

		$element_list.on("click", function () {
			var $t = $(this);
			$element_list.removeClass('active');
			$t.addClass('active');
			$preview.attr('data-font', $t.data('font'));
			$preview.attr('data-icon', $t.data('icon'));
			$preview.attr('data-name', $t.data('name'));
			$preview.attr('data-key', $t.data('key'));

			$icon_text.val($t.data('font') + ':' + $t.data('name'));

		});
	});

</script>
