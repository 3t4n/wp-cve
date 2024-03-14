<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WOO_CTRL Class
 *
 * Extends the standard Woocommerce email handlers to allow for test emails, product images and embedded template images
 *
 * @class       WOO_CTRL
 * @version     1.061
 * @package     WOO_CTRL
 * @author      Ian Young
 */

class WOO_CTRL {
	
	public static $include_thumbs;
	public static $embed_images;
	public static $thumb_size;
	public static $custom_size;
	public static $header_image;
	public static $image_wrapper;
	public static $include_cats;
	public static $include_all_cats;
	public static $include_sku;
	public static $extracted_text = array();
	public static $default_custom_size = array(
		'width'	=> 60,
		'height'	=> 60,
	);
	public static $recipient = '';
	public static $customer_note = 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.';
	public static $attached_images = array();
	
	public function init() {
		self::set_settings();
		self::init_actions();
		self::init_filters();
	}
	
	public function init_actions() {
		
		// woocommerce specific actions
		add_action( 'woocommerce_admin_field_wooctrl_title', array( __CLASS__, 'settings_wooctrl_title' ), 10, 1 );
		add_action( 'woocommerce_admin_field_email_preview_button', array( __CLASS__, 'email_preview_button' ), 10, 1 );
		add_action( 'woocommerce_admin_field_email_custom_image_size', array( __CLASS__, 'email_custom_image_size' ), 10, 1 );
		add_action( 'woocommerce_admin_field_email_select_order', array( __CLASS__, 'email_select_order' ), 10, 1 );
		add_action( 'woocommerce_admin_field_email_media_button', array( __CLASS__, 'email_media_button' ), 10, 1 );
		// add_action( 'woocommerce_email_settings_after', array( __CLASS__, 'email_content_form'), 10 );
		add_action( 'woocommerce_email_settings_after', array( __CLASS__, 'email_test_form'), 20 );
		add_action( 'woocommerce_order_item_meta_end', array( __CLASS__, 'display_product_cats'), 10, 4);
		
		// wordpress actions to setup plugin
		add_action( 'admin_init', array( __CLASS__, 'load_textdomain') );
		add_action( 'admin_init', array( __CLASS__, 'check_template_view') );
		add_action( 'current_screen', array( __CLASS__, 'wooctrl_enqueue_media') );
		add_action( 'wp_ajax_wooctrl_send_test_email', array( __CLASS__, 'test_email') );
		add_action( 'phpmailer_init', array( __CLASS__, 'init_phpmailer') );
		add_action( 'shutdown', array( __CLASS__, 'perform_shutdown') );
		
	}
	
	public function init_filters() {
		
		// woocommerce specific filters
		add_filter( 'woocommerce_get_settings_email', array(__CLASS__, 'additional_email_settings'), 10, 2 );
		add_filter( 'woocommerce_email_order_items_args', array(__CLASS__, 'email_order_items_args'), 10, 1);
		add_filter( 'woocommerce_order_item_thumbnail', array( __CLASS__, 'get_order_item_thumbnail'), 10, 2 );
		add_filter( 'woocommerce_admin_settings_sanitize_option_wooctrl_global_email_type', array( __CLASS__, 'save_global_email_type'), 10, 3);
		if('yes'==self::$embed_images || 1==self::$embed_images) {
			add_filter( 'woocommerce_mail_content', array( __CLASS__, 'find_images'), 10, 1);
		}
		if('yes'==self::$include_sku || 1==self::$include_sku) {
			add_filter( 'woocommerce_get_sku', array( __CLASS__, 'get_sku'), 10, 2);
		}
		
	}
	
	public function wooctrl_enqueue_media() {
		$screen = get_current_screen();
		wp_enqueue_style('wooctrl_css', WOOCTRL_DIR.'assets/css/styles.css');
		if( 'woocommerce_page_wc-settings' == $screen->base && 'email' == $_GET['tab'] ) {
			wp_enqueue_media();
			wp_enqueue_script('wooctrl_media_button', WOOCTRL_DIR.'assets/js/select_media.js', array('jquery'));
		}
	}
	
	public function add_plugin_action_link( $links ) {
		array_unshift($links, '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=email' ) . '">Email Settings</a>');
		return $links;
	}
	
	public function set_settings() {
		self::$thumb_size = isset($_GET['thumb_size']) ? $_GET['thumb_size'] : get_option('wooctrl_thumb_size');
		self::$include_thumbs = isset($_GET['include_thumbs']) ? $_GET['include_thumbs'] : get_option('wooctrl_include_thumbs');
		self::$embed_images = isset($_GET['embed_images']) ? $_GET['embed_images'] : get_option('wooctrl_embed_images');
		self::$header_image = isset($_GET['thumb_size']) ? $_GET['thumb_size'] : get_option('woocommerce_email_header_image');
		self::$image_wrapper = isset($_GET['image_wrapper']) ? $_GET['image_wrapper'] : get_option('wooctrl_image_wrapper');
		self::$include_cats = isset($_GET['include_cats']) ? $_GET['include_cats'] : get_option('wooctrl_include_cats');
		self::$include_all_cats = isset($_GET['include_all_cats']) ? $_GET['include_all_cats'] : get_option('wooctrl_include_all_cats');
		self::$include_sku = isset($_GET['include_sku']) ? $_GET['include_sku'] : get_option('wooctrl_include_sku');
		if(isset($_GET['custom_image_size-width']) && isset($_GET['custom_image_size-height'])) {
			self::$custom_size = array(
				'width' => $_GET['custom_image_size-width'],
				'height' => $_GET['custom_image_size-height']
			);
		} else {
			self::$custom_size = get_option('wooctrl_custom_image_size');
		}
	}
	
	public function load_textdomain() {
		load_plugin_textdomain( WOOCTRL_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	public function check_template_view() {
		// we don't need to add wooctrl_preview arg to our query args, as we're not querying the database with it
		$email_class = isset($_GET['wooctrl_preview']) ? $_GET['wooctrl_preview'] : false;
		$order = isset($_GET['order']) ? $_GET['order'] : false;
		$recipient = isset($_GET['recipient']) ? $_GET['recipient'] : false;
		if($email_class && current_user_can('edit_posts')) {
			$args = array(
				'email_class'	=> $email_class,
				'order'			=> $order,
				'recipient'		=> !empty($recipient) ? $recipient : 'browser'
			);
			self::test_email($args);
			exit;
		}
	}
	
	public function test_email($args=false) {
		
		// check if we're sending via ajax
		if(defined('DOING_AJAX')) {
			
			$args['email_class'] = $_POST['email_class'];
			$args['order'] = $_POST['order'];
			$args['recipient'] = $_POST['recipient'];
			
			if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'wooctrl_test_email')) {
				die('Invalid nonce');
			}
			
		}
		
		if(strpos($args['email_class'],'WC_Email')===false) {
			die('Must be a subclass of WC_Email');
		}
		
		self::$recipient = $args['recipient'];
		
		$filter_email_class = strtolower( str_replace( 'WC_Email_', '' , $args['email_class'] ) );
		
		// filter the recipient, which is set per email class, per order	
		add_filter( 'woocommerce_email_recipient_'.$filter_email_class , array( __CLASS__, 'filter_email_recipient'), 10, 2);

		// filter the subject, prepending TEST EMAIL:
		add_filter('woocommerce_email_subject_'.$filter_email_class , array( __CLASS__, 'filter_email_subject'), 1, 2);
		
		if( isset( $GLOBALS['wc_advanced_notifications'] ) ) {
			unset( $GLOBALS['wc_advanced_notifications'] );
		}
	
		// load the parent email class, which includes all the subclasses we might need
		new WC_Emails;
		$new_email = new $args['email_class'];
			
		// make sure email isn't sent to the intended recipient
		apply_filters( 'woocommerce_email_enabled_' . $filter_email_class, false, $new_email->object ); 
		
		// if we don't have an order, grab the latest valid order
		if(!$args['order']) {
			$orderlist = self::get_latest_orders(1);
			if(!empty($orderlist)) {
				$args['order'] = key($orderlist);
			}
		}
		
		if(!$args['order']) {
			die( __('No orders could be found to test this email.', 'wooctrl') );
		}
		
		// customer notes trigger method accepts an array of order id and the note string
		if( 'customer_note' == $filter_email_class) {
			$new_email->trigger( array( 'order_id'=>$args['order'], 'customer_note' => self::$customer_note ) );
		} else {
			$new_email->trigger( $args['order'] );
		}
		
		if(is_email(self::$recipient)) {
			// save our last test settings
			update_option('wooctrl_latest_test_email', $args);
		}

		// echo the email content for browser
		if( !defined('DOING_AJAX') ) {
			echo $new_email->style_inline( $new_email->get_content() );
		} else {
			echo json_encode(array(
				'result' => 'Test email sent to '.htmlentities(self::$recipient),
				'embedded_images' => self::$attached_images,
			));
		}
		
		exit;
		
	}
	
	public function find_images($contents) {
		$content_images = self::extract_images($contents);
		if(!empty($content_images)) {
			foreach($content_images as $key => $imgsrc) {
				// replace image, and add to attached images
				$cid = 'wooctrl_inline_image_'.$key;
				$contents = str_replace($imgsrc, 'cid:'.$cid, $contents);
				self::$attached_images[$cid] = $imgsrc;
			}
		}
		return $contents;
	}
	
	public function extract_images($html) {
		
		if(stripos($html, '<img') !== false) {
            $imgsrc_regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
            preg_match_all($imgsrc_regex, $html, $matches);
			if(is_array($matches) && !empty($matches)) {
                return $matches[2];
            }
        }
		return false;
		
	}
	
	public function init_phpmailer($phpmailer) {
		if(!empty(self::$attached_images)) {
			foreach(self::$attached_images as $cid => $img) {
				$url_components = parse_url($img);
				// only embed local images
				if($url_components['host'] && strpos($url_components['host'], $_SERVER['HTTP_HOST'])===false) {
					continue;
				}
				$filename = $_SERVER['DOCUMENT_ROOT'].$url_components['path'];
				$imgname = basename($filename);
				if(file_exists($filename)) {
					// use the opportunity to resize the image down to our custom values
					// keeping our emails as small as possible
					if(strpos($img,'?wooctrl_product_image')!==false && 'custom'==self::$thumb_size && isset(self::$custom_size['width']) && isset(self::$custom_size['height'])) {
						$img_edit = wp_get_image_editor( $filename );
						$img_edit->resize(self::$custom_size['width'], self::$custom_size['height'], true);
						$tmp = WOOCTRL_CACHE.time().$imgname;
						$img_edit->save($tmp);
						$filename = $tmp;
					}
					$phpmailer->AddEmbeddedImage( $filename, $cid, $imgname );
				}
			}
		}
		return $phpmailer;
	}
	
	public function filter_email_recipient($recipient, $object) {
		return self::$recipient;
	}
	
	public function filter_email_subject($subject) {
		return 'TEST EMAIL: '.$subject;
	}
	
	/* By default, the 'woocommerce_order_item_thumbnail' function uses the thumbnail size as the base size
		which will cause issues should the size required be larger than the thumb
		so we hook into the filter to grab instead the correctly sized intermediate image.
		We also add ?wooctrl_product_image to the image url so we can resize only the product images if we're embedding them into the email
		*/
	public function get_order_item_thumbnail($html, $item) {
		
		$thumb_size = self::$thumb_size;
		if(!isset($thumb_size)) {
			$thumbsize = self::$thumb_size = get_option('wooctrl_thumb_size','thumbnail');
		}
		if('custom'==self::$thumb_size) {
			$custom_size = get_option('wooctrl_custom_image_size',self::$default_custom_size);
			self::$custom_size = $custom_size;
			$thumb_size = array_values($custom_size);
		}
		$order = new WC_Order;
		$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
		
		$imgtag = '<img src="' . ( $_product->get_image_id() ? current( wp_get_attachment_image_src( $_product->get_image_id(), $thumb_size) ) : wc_placeholder_img_src() ) .'?wooctrl_product_image" alt="' . esc_attr__( 'Product Image', 'woocommerce' ) . '" height="' . esc_attr( $thumb_size[1] ) . '" width="' . esc_attr( $thumb_size[0] ) . '" />';
		
		// is the image wrapper valid?
		if(($closetag = strpos(self::$image_wrapper,'</'))!==false) {
			$wrappertag = sprintf(substr(self::$image_wrapper, 0, $closetag).'%s'.substr(self::$image_wrapper, $closetag), $imgtag);
		} else {
			$wrappertag = sprintf('<div style="margin-bottom: 5px">%s</div>', $imgtag);
		}
		
		return apply_filters('wooctrl_order_item_thumbnail', $wrappertag, $item);
	}
	
	/*
	Not available as yet, with the way the email sections are currently built within woocommerce
	public function add_email_subsection($sections) {
		$sections['wooctrl'] = __( 'Extended Control', 'wooctrl' );
		return $sections;
	}
	*/
	
	/* This adds the form settings we would like to have within their own subsection
		into the main email settings page.
		These are the global settings for all emails.
		*/
	public function additional_email_settings($settings) {
		
		$image_sizes = get_intermediate_image_sizes();
		$image_sizes[] = 'custom';
		
		// Please comment out the following line if you don't want to replace the "Header Image" text field with a media button
		self::change_header_image_field_type( $settings );
		
		$settings[] = array( 'name' => __( 'Extended Control', 'wooctrl' ), 'type' => 'wooctrl_title', 'desc' => __( 'The following options extend your control of Woocommerce emails.', 'wooctrl' ), 'id' => 'wooctrl', 'class' => 'icon-wooctrl' );
		
		$settings[] = array(
			'name'     => __( 'Product thumbnails', 'wooctrl' ),
			'desc_tip' => __( 'This will display product thumbnails on each order item line within the email', 'wooctrl' ),
			'id'       => 'wooctrl_include_thumbs',
			'type'     => 'checkbox',
			'css'      => 'min-width:300px;',
			'desc'     => __( 'Include product thumbnails', 'wooctrl' ),
		);
		
		$settings[] = array(
			'name'     => __( 'Product categories', 'wooctrl' ),
			'desc_tip' => __( 'This will display the product categories under each order item line within the email', 'wooctrl' ),
			'id'       => 'wooctrl_include_cats',
			'type'     => 'checkbox',
			'css'      => 'min-width:300px;',
			'desc'     => __( 'Show product categories', 'wooctrl' ),
		);
		
		$settings[] = array(
			'name'     => '&nbsp;',
			'desc_tip' => __( 'Show the full category breadcrumb for each product', 'wooctrl' ),
			'id'       => 'wooctrl_include_all_cats',
			'type'     => 'checkbox',
			'css'      => 'min-width:300px;',
			'desc'     => __( 'Show full category breadcrumb', 'wooctrl' ),
		);
		
		$settings[] = array(
			'name'     => __( 'Product SKU', 'wooctrl' ),
			'desc_tip' => __( 'This will display the product sku on each order item line within the email', 'wooctrl' ),
			'id'       => 'wooctrl_include_sku',
			'type'     => 'checkbox',
			'css'      => 'min-width:300px;',
			'desc'     => __( 'Include product SKU', 'wooctrl' ),
		);
		
		$settings[] = array(
			'name'		=> __( 'Thumbnail size', 'wooctrl' ),
			'desc_tip'	=> __( 'Select the image size for your emails', 'wooctrl' ),
			'id'			=> 'wooctrl_thumb_size',
			'type'		=> 'select',
			'options'	=> array_combine($image_sizes, $image_sizes),
			'default'	=> 'shop_thumbnail',
			'class'		=> 'wc-enhanced-select',
			'css'		=> 'max-width:200px;',
		);
		
		$settings[] = array(
			'name'		=> __( 'Custom thumbnail size', 'wooctrl' ),
			'desc_tip'	=> __( 'Set a custom image size for your emails. You must select custom from thumbnail size.', 'wooctrl' ),
			'id'			=> 'wooctrl_custom_image_size',
			'type'		=> 'email_custom_image_size',
			'default'	=> self::$default_custom_size,
		);
		
		$settings[] = array(
			'name'     => __( 'Embed images', 'wooctrl' ),
			'desc_tip' => __( 'This will attach and embed any email images, including the header image if you have chosen one, within the email', 'wooctrl' ),
			'id'       => 'wooctrl_embed_images',
			'type'     => 'checkbox',
			'desc'     => __( 'Attach and embed any images within the email', 'wooctrl' ),
		);
		
		/* Since 1.01 */
		$settings[] = array(
			'name'		=> __( 'Image wrapper HTML', 'wooctrl' ),
			'desc_tip'	=> __( 'Enter the HTML that wraps the product thumbnail. Ensure you close the tag.', 'wooctrl' ),
			'id'			=> 'wooctrl_image_wrapper',
			'type'		=> 'textarea',
			'css'		=> 'width:300px; height:75px',
			'default'	=> '<div style="margin-bottom:5px"></div>'
		);
		
		$settings[] = array(
			'name'		=> __( 'Global email type', 'wooctrl' ),
			'desc_tip'	=> __( 'Change the email type for all Woocommerce emails at once.', 'wooctrl' ),
			'id'			=> 'wooctrl_global_email_type',
			'type'		=> 'select',
			'options'	=> array(
				'nochange'	=> 'No change',
				'plain' 		=> 'Plain',
				'html'		=> 'HTML',
				'multipart'	=> 'Multipart'
			),
			'class'		=> 'wc-enhanced-select',
			'css'		=> 'max-width:200px;',
		);
		/* end */
		
		/* Since 1.06 */
		$settings[] = array(
			'id'			=> 'wooctrl_preview_template',
			'type'		=> 'email_preview_button',
		);
		/* end */
		
		$settings[] = array( 'type' => 'sectionend', 'id' => 'wooctrl' );
		
		return apply_filters('wooctrl_email_settings', $settings);
		
	}
	
	/* Adds our custom args to the email_order_items_args, so we can include images
		*/
	public function email_order_items_args($args) {
		
		if('yes'==self::$include_thumbs || 1==self::$include_thumbs) {
			$args['show_image'] = true;
			$thumbsize = get_option('wooctrl_thumb_size','thumbnail');
			if('custom'==$thumbsize) {
				$custom = get_option('wooctrl_custom_image_size');
				if($custom) {
					$thumbsize = array_values($custom);
				}
			}
			$args['image_size'] = $thumbsize;
		}
		
		if('yes'==self::$include_sku || 1==self::$include_sku) {
			$args['show_sku'] = true;
		}
		
		return apply_filters('wooctrl_email_order_items_args', $args);
		
	}
	
	public function get_sku( $sku='', $product=NULL ) {
		
		if(empty($sku) && isset($product)) {
			$sku = get_post_meta($product->id, '_sku', true);
		}
		return $sku;
		
	}
	
	public function display_product_cats($item_id, $item, $order=false, $plain_text=false ) {
		
		if('yes'==self::$include_cats || 1==self::$include_cats) {
			$terms = wp_get_object_terms( $item['item_meta']['_product_id'], array('product_cat') );
			if($terms) {
				echo apply_filters('wooctrl_category_wrapper_start', '<p style="font-size:11px; color:#999; margin-bottom:0;">');
				$showall = 'yes'==self::$include_all_cats || 1==self::$include_all_cats;
				self::display_terms_line($terms, $showall);
				echo apply_filters('wooctrl_category_wrapper_end', '</p>');
			}
		}
		
	}
	
	public function display_terms_line($terms, $showall=false) {
		$tarr = array();
		if($showall) {
			foreach($terms as $t) {
				$tarr[] = $t->name;
			}
		} else {
			$tarr[] = array_pop($terms)->name;
		}
		echo implode( apply_filters('wooctrl_category_separator',' > ') , $tarr);
	}
	
	// Form type handler for use by the WC_Settings_API when the field type is "wooctrl_header"
	public function settings_wooctrl_title($value) {
		
		if ( ! empty( $value['title'] ) ) {
			echo '<h2 class="icon-wooctrl">' . esc_html( $value['title'] ) . '</h2>';
		}
		if ( ! empty( $value['desc'] ) ) {
			echo wpautop( wptexturize( wp_kses_post( $value['desc'] ) ) );
		}
		echo '<table class="form-table">'. "\n\n";

	}
	
	// Form type handler for use by the WC_Settings_API when the field type is "email_custom_image_size"
	public function email_custom_image_size($value) {
		
		$tooltip_html = wc_help_tip( $value['desc_tip'] );
		$crop_tooltip = wc_help_tip( __('Custom image sizes are always hard cropped','wooctrl') );
		$size = get_option($value['id']);
		$width = isset( $size[ 'width' ] ) ? $size[ 'width' ] : $value[ 'default' ][ 'width' ];
		$height = isset( $size[ 'height' ] ) ? $size[ 'height' ] : $value[ 'default' ][ 'height' ];
		?><tr valign="top">
			<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?> <?php echo $tooltip_html; ?></th>
			<td class="forminp image_width_settings">
				<input name="<?php echo esc_attr( $value['id'] ); ?>[width]"  id="<?php echo esc_attr( $value['id'] ); ?>-width" type="text" size="3" value="<?php echo $width; ?>" /> &times; <input name="<?php echo esc_attr( $value['id'] ); ?>[height]"  id="<?php echo esc_attr( $value['id'] ); ?>-height" type="text" size="3" value="<?php echo $height; ?>" />px
			</td>
		</tr>
		<?php
	}
	
	// Form type handler to select an individual order when field type is "email_select_order"
	// TODO: Allow entry of an order number
	public function email_select_order($value) {
		
		$tooltip_html = wc_help_tip( $value['desc_tip'] );
		$default = $value['default'];
		?><tr valign="top">
			<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?> <?php echo $tooltip_html; ?></th>
			<td class="forminp select_order">
				<select name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
					<?php
					foreach($value['options'] as $opt) {
						$sel = $value['default']==$opt ? 'selected="selected"' : '';
						echo sprintf('<option value="%1$s" %2$s>%1$s</option>', $opt, $sel);
					}
					?>
				</select>
				<input name="<?php echo esc_attr( $value['id'] ); ?>_custom" id="<?php echo esc_attr( $value['id'] ); ?>_custom" type="text" size="3" value="" />
			</td>
		</tr>
		<?php
		
	}
	
	// Form type handler to replace the textfield for header image with a media button when field type is "email_media_button"
	public function email_media_button($value) {
		
		$current_value = get_option($value['id']);
		if(1==$value['desc_tip']) {
			$tooltip = $value['desc'];
		} else {
			$tooltip = $value['desc_tip'];
		}
		?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo wp_kses_post( $value['title'] ); ?></label>
                <?php echo wc_help_tip( $tooltip ); ?>
            </th>
            <td class="forminp">
				<?php
				// show reduced thumbnail of the header, uncropped
				$remove_hidden = '';
				if($current_value) {
					echo '<img src="'.$current_value.'" style="width:160px; height:auto" id="'.$value['id'].'_image" />					<br />';
				} else {
					echo '<img style="width:60px; height:60px; border:solid 1px #ddd;" id="'.$value['id'].'_image" /><br />';
					$remove_hidden = ' style="display:none" ';
				}
				?>
				<input type="hidden" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php echo esc_attr($current_value); ?>" />
                <fieldset>
                    <legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
                    <button type="button" class="button button-default wooctrl_add_media" data-mediaid="<?php echo $value['id']; ?>" data-title="Select <?php echo $value['title']; ?>"><?php _e( 'Select Image', 'wooctrl'); ?></button>
					<button type="button" class="button button-default wooctrl_delete_image" data-mediaid="<?php echo $value['id']; ?>" <?php echo $remove_hidden; ?>><?php _e( 'Remove Image', 'wooctrl'); ?></button>
                </fieldset>
            </td>
        </tr>
        <?php
		
	}
	
	// Form type handler to show preview button when field type is "email_preview_button"
	public function email_preview_button() {
		
		$tooltip = __('Preview your settings in the browser without saving. The latest order will be used in the "Order Completed" email','wooctrl');
		$emailtype = apply_filters('wooctrl_preview_email_type','WC_Email_Customer_Completed_Order');
		?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <?php _e('Preview without saving','wooctrl'); ?>
				<?php echo wc_help_tip( $tooltip ); ?>
            </th>
            <td class="forminp">
				<button class="button button-wooctrl-preview" type="button"><img src="<?php echo WOOCTRL_DIR.'/assets/img/wooctrl-icon.png'; ?>" style="width:16px; height:16px; vertical-align:text-top;" /> <?php _e('Preview Settings','wooctrl'); ?></button>
            </td>
        </tr>
		<script>
		jQuery(function($) {
			$('.button-wooctrl-preview').click(function() {
				var form = $(this).closest('form');
				var fields = ['include_thumbs','include_cats','include_all_cats','include_sku','thumb_size','custom_image_size-width','custom_image_size-height','embed_images'];
				var data = {
					wooctrl_preview:'<?php echo $emailtype; ?>',
				};
				for(var f in fields) {
					if($('#wooctrl_'+fields[f]).attr('type')=='checkbox') {
						data[fields[f]] = $('#wooctrl_'+fields[f]).prop('checked') ? 1 : 0;
					} else {
						data[fields[f]] = $('#wooctrl_'+fields[f]).val();
					}
				}
				var query = $.param(data);
				window.open('<?php echo admin_url(); ?>?'+query,'_blank');
			});
		});
		</script>
        <?php
		
	}
	
	// Shown on the individual email pages, allowing changes to email content
	/*public function email_content_form($email) {
		
		$email_class_name = get_class($email);
		
		$form_fields = array(
			'wooctrl_email_content'	=> array(
				'title'		=> __( 'Email Content', 'wooctrl' ),
				'type'		=> 'textarea',
				'desc_tip'	=> __( 'Enter the content you would like to show in this email', 'wooctrl' ),
				'css'		=> 'max-width:25em;',
			),
		);
		?>
		<hr />
		<h2 class="icon-wooctrl"><?php _e('Email content', 'wooctrl'); ?></h2>
		<table class="form-table">
			<?php $email->generate_settings_html( $form_fields, true ); ?>
		</table>
		<?php
		
	}*/
	
	// Shown on the individual email pages, allowing quick preview or test emails to be sent
	public function email_test_form($email) {
		
		$email_class_name = get_class($email);
		
		$last = get_option('wooctrl_latest_test_email');
		if(!$last) {
			$last = array(
				'recipient' => get_option('admin_email'),
				'order' => null,
			);
		}
		$orders = self::get_latest_orders();
		
		$form_fields = array(
			'test_order_number'	=> array(
				'title'		=> __( 'Using Order', 'wooctrl' ),
				'type'		=> 'select',
				'options'	=> $orders,
				'default'	=> $last['order'],
				'desc_tip'	=> true,
				'class'		=> 'wc-enhanced-select',
			),
			'test_recipient'	=> array(
				'title'		=> __( 'Email Address', 'wooctrl' ),
				'type'		=> 'email',
				'default'	=> $last['recipient']
			),
		);
		?>
		<hr />
		<h2 class="icon-wooctrl"><?php _e('Test this email template','wooctrl'); ?></h2>
		<table class="form-table">
			<?php $email->generate_settings_html( $form_fields, true ); ?>
			<tr>
				<th scope="row" class="titledesc">&nbsp;</th>
				<td>
					<p><a target="_blank" class="button button-default" role="button" id="view_in_browser" href="#">View in browser</a>
					<em>or</em> 
					<a target="_blank" class="button button-default" role="button" id="send_email_test" href="#">Send test email</a></p>
					<p id="wooctrl_msg"></p>
				</td>
			</tr>
		</table>
		<script>
			jQuery(function($) {
				$('#view_in_browser').click(function(e) {
					var order = $('#woocommerce_<?php echo $email->id; ?>_test_order_number').val();
					var emailclass = '<?php echo $email_class_name; ?>';
					if(order) {
						$(e.target).attr('href','?wooctrl_preview='+emailclass+'&order='+order);
					} else {
						$('#wooctrl_msg').text('Please select an order!');
					}
				});
				$('#send_email_test').click(function(e) {
					e.preventDefault();
					$('#wooctrl_msg').empty();
					$(e.target).text('Sending...');
					var order = $('#woocommerce_<?php echo $email->id; ?>_test_order_number').val();
					if(!order) {
						$('#wooctrl_msg').text('Please select an order!');
						return;
					}
					var emailclass = '<?php echo $email_class_name; ?>';
					var recipient = $('#woocommerce_<?php echo $email->id; ?>_test_recipient').val();
					if(recipient) {
						$.ajax({
							url:ajaxurl,
							data:{
								action:'wooctrl_send_test_email',
								_wpnonce:'<?php echo wp_create_nonce('wooctrl_test_email'); ?>',
								order:order,
								email_class:'<?php echo $email_class_name; ?>',
								recipient:recipient
							},
							type:'POST',
							dataType:'json',
							success:function(res) {
								$('#wooctrl_msg').text(res.result);
								$(e.target).text('Send test email');
							},
							error:function(res) {
							}
						});
					}
				});
			});
		</script>
		<hr />
		<?php
		
	}
	
	public function get_latest_orders($num=10, $cur=null) {
		
		global $wpdb;
		$q = 'SELECT order_id FROM '.$wpdb->prefix . 'woocommerce_order_items GROUP BY order_id ORDER BY order_item_id DESC LIMIT %d';
		$orders = $wpdb->get_results( $wpdb->prepare( $q, $num) );
		
		if(empty($orders)) {
			return array();
		}
		
		$ret = array();
		foreach($orders as $order) {
			$ret[$order->order_id] = __('Order','wooctrl').' #'.$order->order_id;
		}
		return $ret;
		
	}
	
	public function change_header_image_field_type( &$settings ) {
		
		foreach($settings as &$args) {
			if( isset($args['id']) && 'woocommerce_email_header_image' == $args['id'] ) {
				$args['type'] = 'email_media_button';
				$args['desc_tip'] = __( 'Upload or select your email header image', 'wooctrl');
			}
		}
		
	}
	
	public function save_global_email_type($value, $option, $raw_value) {
		
		if('nochange'==$value) return $value;
		
		// grab all the woocommerce emails, loop through and save their email types
		$E = new WC_Emails;
		$emails = $E->get_emails();
		foreach($emails as $e) {
			$settings = $e->settings;
			$settings['email_type'] = $value;
			$option_name = sprintf('woocommerce_%s_settings', $e->id);
			update_option($option_name, $settings);
		}
		
		// always save as nochange
		return 'nochange';
		
	}
	
	/* Performed as wordpress shuts down at the end of a request
		So we'll use this to remove all cached images from the WOOCTRL_CACHE folder
		*/
	public static function perform_shutdown() {
		$files = glob(WOOCTRL_CACHE.'*');
		foreach($files as $file) {
			if(is_file($file)) {
				unlink($file);
			}
		}
	}
	
}

WOO_CTRL::init();