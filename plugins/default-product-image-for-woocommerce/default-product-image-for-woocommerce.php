<?php
/**
* Plugin Name: Placeholder Image for Woocommerce
* Plugin URI: http://itron.pro
* Description: Allows to specify default image for Woocommerce products
* Version: 1.1
* License:     GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Author: Игорь iTRON Тронь
* Author URI: http://itron.pro
* Text Domain: default-product-image-for-woocommerce
* Domain Path: /languages
*/
	
	define( 'WCDI', 'default-product-image-for-woocommerce' );
	global $wcdi;
	$wcdi = array();
	$wcdi['url'] = substr( plugin_dir_url( __FILE__ ), 0, -1 );
	$wcdi['path'] = __DIR__ ;
	
	#	Регистрация настроек для страницы WooCommerce - Настройки - Товары - Отображение
	add_filter( 'woocommerce_product_settings', 'wcdi_settings', 10 );
	add_action( 'woocommerce_admin_field_image', 'wcdi_def_image_field', 5, 1 );
	#	Замена штатного placeholder-изображения на свое
	add_action( "init", "wcdi_placeholder_replacing", 100 );
	add_filter( 'wcdi_wc_placeholder_src', 'wcdi_wc_placeholder_src_size', 10 );
	add_filter( 'post_thumbnail_html', 'wcdi_post_thumbnail_html', 100, 5 );
	add_action( 'admin_footer', 'wcdi_media_load', 100 );
	add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
	
	function wcdi_settings( $wc_settings ){
		global $wcdi;
		$last = array_pop( $wc_settings );
		
		$wc_settings[] = array(
			'title'    => __( 'Placeholder Image', WCDI ),
			'desc'     => '',
			'id'       => 'wcdi_default_img',
			'type'     => 'image',
			'desc_tip' =>  false,
			'default'  => 0
		);
		$wc_settings[] = $last;
		
		return $wc_settings;
	}

	function wcdi_def_image_field( $data ){					
		
		$value = WC_Admin_Settings::get_option( @ $data[ 'id' ] );
		
		?><tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( @ $value['id'] ); ?>"><?php echo esc_html( @ $data['title'] ); ?></label>
				<?php //echo $tooltip_html; ?>
			</th>
			<td class="forminp forminp-<?php echo sanitize_title( @ $data['type'] ) ?>">
			<?php	
				wcdi_media_modal_images( array(
					'button_id'		=> esc_attr( @ $data[ 'id' ] . '-button' ),
					'option_name'	=> esc_attr( @ $data[ 'id' ] ),
					'data'			=> esc_attr( @ $value ),
				));
			?>
			</td>
		</tr><?php
	}


	function wcdi_placeholder_replacing(){
		$img_id = WC_Admin_Settings::get_option( 'wcdi_default_img' );
		if ( ! empty( $img_id ) ):
			add_filter( 'woocommerce_placeholder_img', 'wcdi_replace_wc_placeholder_img', 100, 3 );
			add_filter( 'woocommerce_placeholder_img_src', 'wcdi_replace_wc_placeholder_img_src', 100 );
		endif;
	}
	function wcdi_replace_wc_placeholder_img( $html, $size, $dimensions ){
		$img_id = WC_Admin_Settings::get_option( 'wcdi_default_img' );
		return ! empty( $img_id ) ? wp_get_attachment_image( $img_id, $size ) : $html;
	};	
	function wcdi_replace_wc_placeholder_img_src(){
		$img_id = WC_Admin_Settings::get_option( 'wcdi_default_img' );
		return wp_get_attachment_image_url( $img_id, apply_filters( 'wcdi_wc_placeholder_src', 'shop_single' ) );
	};
	
	function wcdi_wc_placeholder_src_size(){
		if ( is_product_category() ) return 'shop_catalog';
	}
	
	function wcdi_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ){
		global $wpdb, $post;
		if ( ! empty( $html ) ) return $html;
		if ( ! empty( $post_thumbnail_id ) ) return $html;
		
		if ( $post->ID != $post_id ) :
			$post_type = $wpdb->get_results( $wpdb->prepare( "SELECT post_type FROM {$wpdb->posts} WHERE ID = %d", $post_id ) );
			if ( empty ( $post_type ) || $post_type[0]->post_type != 'product' ) { return $html; };
		else :
			if ( $post->post_type != 'product' ) { return $html; };
		endif;
		
		$img_id = WC_Admin_Settings::get_option( 'wcdi_default_img' );
		return wp_get_attachment_image( $img_id, $size );		
	}
	
	function wcdi_media_modal_images( $args ){
		if ( function_exists( 'nb_media_modal_images' ) ) :
			nb_media_modal_images( $args );
		else :
			global $post;
			$defaults = array(
				'post_id'		=> @$post->ID, 
				'button_id'		=> 'button_id_' . rand( 0, 9999 ),
				'button_text'	=> __( 'Select', WCDI ) . ' ' . __( 'Image', WCDI ),
				'multiselect'	=> false,
				'img_width'		=> 'initial',
				'data'			=> false,
				'meta_key'		=> false, 
				'thumb_size'	=> 'thumbnail',
			);
			$r = ( object )wp_parse_args( $args, $defaults );
			
			$r->img_width = ! empty( $r->img_width ) ? 'style="width : ' . $r->img_width . ';"' : '' ;
			?>
			<div id="<?php echo $r->button_id ?>_wrapper" class="wpmediamodal_wrapper">
				<button class="wpmediamodal" id="<?php echo $r->button_id ;?>" wpmediamodal-ids="<?php echo $r->button_id ?>_img_ids" wpmediamodal-preview="<?php echo $r->button_id ?>_preview" wpmediamodal-multiSelect=<?php echo $r->multiselect ?> ><?php echo $r->button_text ?></button><br />
				<ul class="preview clearfix" id="<?php echo $r->button_id ?>_preview" >
					<?php
					if ( $r->meta_key !== false ) :
						if ( metadata_exists( 'post', $r->post_id, $r->meta_key ) ) {
							$previews = get_post_meta( $r->post_id, $r->meta_key, TRUE );
						} ;
					else :
						$previews = $r->data ;
					endif ;
						$attachments = array_filter( explode( ',', $previews ) );
						if ( $attachments ) {
							foreach ( $attachments as $attachment_id ) {
								echo '<li class="image" data-attachment_id="'.$attachment_id.'" ' . $r->img_width . '>'.wp_get_attachment_image( $attachment_id, $r->thumb_size ).'<span><a class="delete_slide" title="' . __( 'Delete' ) .'"></a></span></li>';					
							}
						}
					?>
				</ul>
				<input type="hidden" id="<?php echo $r->button_id ?>_img_ids" name="<?php echo $r->option_name ?>" value="<?php echo esc_attr( $previews ); ?>" />
				<br clear="all" />
			</div>
			<?php
		endif;
	}
	
	function wcdi_media_load(){
		if ( function_exists( 'nb_media_load' ) ) :
			nb_media_load();
			return;
		else :
			global $wcdi;
			?>
			<script type="text/javascript">
				
				( function( $ ){
					jQuery.fn.wpMediaModal = function( options ){
						options = $.extend( {
							preview : false,
							ids : false,
							multiSelect : false,
							modalTitle : "<?php echo __( 'Select', WCDI ) . ' ' . __( 'Image', WCDI ) ?>",
							modalButton : "<?php echo __( 'Select', WCDI ) ?>",
							
							attachment_ids : "" //Задавать этот параметр не следует, он чисто технологический
						}, options );

						var make = function(){
							var slideshow_frame;							
							var $ids = jQuery( '#' + options.ids );
							var $preview = jQuery( '#' + options.preview );	
								
							// Uploading files
							jQuery( this ).live( 'click', function( event ){
						
								event.preventDefault();
								// If the media frame already exists, reopen it.
								if ( slideshow_frame ) {
									slideshow_frame.open();
									return;
								}
								// Create the media frame.
								slideshow_frame = wp.media.frames.downloadable_file = wp.media({
									title: options.modalTitle,
									button: {
										text: options.modalButton,
									},
									multiple: options.multiSelect
								});

								options.attachment_ids = $ids.val();
								// When an image is selected, run a callback.
								slideshow_frame.on( 'select', function() {
									options.attachment_ids = $ids.val();
									var selection = slideshow_frame.state().get('selection');
									selection.map( function( attachment ) {
										attachment = attachment.toJSON();
										if ( attachment.id ) {
											if ( options.multiSelect ) {
												options.attachment_ids = options.attachment_ids ? options.attachment_ids + "," + attachment.id : attachment.id;
											} else {
												options.attachment_ids = attachment.id;
												$preview.children( 'li.image' ).remove();
											};
											

											$preview.append('\
												<li class="image" data-attachment_id="' + attachment.id + '">\
													<img src="' + attachment.url + '" />\
													<span><a href="#" class="delete_slide" title="<?php _e( 'Delete image', WCDI ); ?>"><?php _e( 'Delete', WCDI ); ?></a></span>\
												</li>');
										}
										$ids.trigger( 'selection' );
									} );
									$ids.val( options.attachment_ids );
								});
								// Finally, open the modal
								slideshow_frame.open();
							});
							// Remove files
							$preview.on( 'click', 'a.delete_slide', function() {

								jQuery( this ).closest( '.image' ).remove();
								options.attachment_ids = '';

								$preview.find( '.image' )
									.css( 'cursor','default' )
									.each( function() {
										var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
										options.attachment_ids = options.attachment_ids + attachment_id + ',';
									});

								$ids.val( options.attachment_ids );
								return false;
							} );					
						
						};

						return this.each( make ); 
					};
				})( jQuery );
				
				jQuery( document ).ready( function( $ ){
					jQuery( '.wpmediamodal' ).each(
						function( index, element ){
							jQuery( element ).wpMediaModal({
								ids			: jQuery( this ).attr( "wpmediamodal-ids" ),
								//ids			: "extra_ct_img_ids",
								preview		: jQuery( this ).attr( "wpmediamodal-preview" ),
								multiSelect	: jQuery( this ).attr( "wpmediamodal-multiSelect" ),
								modalTitle	: jQuery( this ).attr( "wpmediamodal-modalTitle" ),
								modalButton	: jQuery( this ).attr( "wpmediamodal-modalButton" )
							});						
						}
					);
				});
			</script>
			<style>
				.wpmediamodal{
					cursor : pointer;
				}
				.wpmediamodal_wrapper ul.preview {
					margin: 10px 0px 0px !important;
					float : none;
				}
				.wpmediamodal_wrapper ul.preview li.image {
					border: 0px solid #D5D5D5;
					position: relative;
					float: left;
					height: auto;
					margin: 0px 7px 7px 0px;
					cursor: move;
					border-radius: 2px;
					overflow: hidden;
				}
				#side-sortables .wpmediamodal_wrapper ul.preview li.image {
					/*width : 100%;*/
				}
				.wpmediamodal_wrapper ul.preview li.image img {
					width: 100%;
					height: auto;
					border-radius: 1px;
				}
				.wpmediamodal_wrapper ul.preview li.image {
					/*cursor: move;*/
				}	
				.wpmediamodal_wrapper ul.preview li.image .delete_slide {
					position: absolute;
					top: 5px;
					right: 5px;
					text-indent: -9999px;
					font-family: Dashicons;
					text-decoration: none;
					cursor : pointer;
				}
				.wpmediamodal_wrapper ul.preview li.image .delete_slide:before{
					content: "\f153";
					font-size: 18px;
					width: 18px;
					height: 18px;
					color: #FFF;
					text-indent: initial;
					display: block;
					box-shadow: none;
				}
				.wpmediamodal_wrapper ul.preview li.image .delete_slide:hover:before{
					color : #AD0000;
				}
			</style>		
			<?php
		endif;
	}
	