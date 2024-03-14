<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_Subscription extends WP_Widget {

	private static $form_id = 0;
	private static $groups = [];
	private $enable_notification = false;

	public function __construct() {
		if (get_locale() == 'fa_IR')
			$text1=	esc_html('اطلاع رسانی پیام واتساپ محصولات ووکامرس');
		else
			$text1= esc_html('Information WhatsApp message of WooCommerce products');
		parent::__construct(
			'WooNotify_Widget_360Messenger',$text1
		);

		add_shortcode( WooNotify_Shortcode( true, true ), [ $this, 'displayForm' ] );

		$this->enable_notification = WooNotify()->Options( 'enable_notif_360Messenger_main' );

		if ( $this->enable_notification ) {
			add_action( 'woocommerce_product_thumbnails', [ $this, 'showInSingleProduct' ], 100 );
			add_action( 'woocommerce_single_product_summary', [ $this, 'showInSingleProduct' ], 39 );
			add_action( 'wp_ajax_wc_360Messenger_save_notification_data', [ $this, 'updateSubscription' ] );
			add_action( 'wp_ajax_nopriv_wc_360Messenger_save_notification_data', [ $this, 'updateSubscription' ] );
		}
	}

	/*widget*/
	public function form( $instance ) {

		$title = isset( $instance['title'] ) ? esc_html(sanitize_text_field($instance['title'])) : esc_html('خبرنامه واتساپ'); ?>

        <p>
            
			<label for="<?php  esc_html( sanitize_text_field( $this->get_field_id( 'title' ) ) ); ?>">
			
			
				<?php esc_html_e( 'Title:' ); ?>
				
                <span class="description">

				<?php
				if (get_locale() == 'fa_IR')
					esc_html( 'این ابزارک را فقط باید در صفحه محصولات استفاده کنید.');
				else
				 esc_html('You should only use this widget on the products page.');
				?>
				</span>
            </label>

            <input class="widefat" id="<?php  esc_html(sanitize_text_field($this->get_field_id( 'title' ))); ?>"
                   name="<?php  $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php  esc_html( sanitize_text_field($title )); ?>"/>
        </p>
		<?php
	}

	/*widget*/
	public function update( $new_instance, $old_instance ) {

		$instance = ! empty( $old_instance ) && is_array( $old_instance ) ? $old_instance : [];

		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( $new_instance['title'] );
		}

		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = '';
		}

		return $instance;
	}

	/*widget*/
	public function widget( $args, $instance ) {

		if ( ! $this->enable_notification || ! is_product() ) {
			return;
		}

		$groups = $this->getGroups();
		if ( empty( $groups ) ) {
			return;
		}
		//old code
		// $args['before_widget'];
		//new code
		 wp_kses_post( $args['before_widget'] );

		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! empty( $title ) ) {
			 esc_html(sanitize_text_field($args['before_title'] . ( $title ) . $args['after_title']));
		}

		WooNotify_Shortcode();

		 $args['after_widget'];
	}

	/*نمایش در صفحه محصول*/

	private function getGroups( $product_id = '' ) {

		if ( empty( self::$groups ) ) {

			$product_id = WooNotify()->ProductId( $product_id );

			self::$groups = WooNotify_360Messenger_Contacts::getGroups( $product_id, true, true );
		}

		return self::$groups;
	}

	/*فرم ثبت شماره برای محصول*/

	public function showInSingleProduct() {

		$product_id     = get_the_ID();
		$is_old_product = ! get_post_meta( $product_id, '_is_360Messenger_set', true );

		if ( $is_old_product && ! WooNotify()->Options( 'notif_old_pr' ) ) {
			$this->enable_notification = false;

			return;
		}

		$show_form = WooNotify()->getValue( 'enable_notif_360Messenger', $product_id );
		if ( ! WooNotify()->maybeBool( $show_form ) ) { //این شرط اگر ریترن کنه یعنی نمایش دستی انتخاب شده
			return;
		}

		if ( strval( $show_form ) == 'thumbnail' ) {
			$stop = current_action() != 'woocommerce_product_thumbnails';
		} else {
			$stop = current_action() == 'woocommerce_product_thumbnails';
		}

		if ( $stop ) {
			return;
		}

		$this->displayForm( $product_id );
	}

	public function displayForm( $product = '' ) {

		if ( ! $this->enable_notification ) {
			return;
		}

		$product_id = WooNotify()->ProductId( $product );
		if ( ! is_product() || empty( $product_id ) ) {
			return;
		}

		$product = wc_get_product( $product_id );

		$groups = $this->getGroups( $product_id );
		if ( empty( $groups ) ) {
			return;
		}

		do_action( 'WooNotify_before_product_newsletter_form', $product );

		$id = ++ self::$form_id;

		$can_be_subscribe = ! WooNotify()->hasNotifCond( 'notif_only_loggedin', $product_id ) || is_user_logged_in();

		$disabled = '';
		if ( ! $can_be_subscribe ) {
			$disabled = 'disabled="disabled"';
		}

		?>

        <form class="360Messenger-notif-form" id="360Messenger-notif-form-<?php  absint( $id ); ?>" method="post">
            <div style="display:none !important;width:0 !important;height:0 !important;">
    <img style="width:16px;display:inline;" src="<?php  esc_url( WooNotify_URL . '/assets/images/tick.png' ); ?>" />
    <img style="width:16px;display:inline;" src="<?php  esc_url( WooNotify_URL . '/assets/images/false.png' ); ?>" />
    <img style="width:16px;display:inline;" src="<?php  esc_url( WooNotify_URL . '/assets/images/ajax-loader.gif' ); ?>" />
</div>


            <div class="360Messenger-notif-enable-p" id="360Messenger-notif-enable-p-<?php  absint( $id ); ?>">
                <label id="360Messenger-notif-enable-label-<?php  absint( $id ); ?>" class="360Messenger-notif-enable-label"
                       for="360Messenger-notif-enable-<?php  absint( $id ); ?>">
                    <input type="checkbox" id="360Messenger-notif-enable-<?php  absint( $id ); ?>" class="360Messenger-notif-enable"
                           name="360Messenger_notif_enable"
                           value="1">
                    <strong><?php  WooNotify()->getValue( 'notif_title', $product_id ); ?></strong>
                </label>
            </div>

            <div class="360Messenger-notif-content" id="360Messenger-notif-content">
				<?php foreach ( $groups as $code => $text ) : ?>
                    <!--<p id="360Messenger-notif-groups-p-<?php /* $code . '_' . $id; */ ?>" class="360Messenger-notif-groups-p">-->
                    <label class="360Messenger-notif-groups-label 360Messenger-notif-groups-label-<?php  esc_html(sanitize_text_field( $code )); ?>"
                           for="360Messenger-notif-groups-<?php  esc_html(sanitize_text_field( $code . '_' . $id )); ?>">
                        <input type="checkbox"
                               id="360Messenger-notif-groups-<?php  esc_html(sanitize_text_field( $code . '_' . $id )); ?>" <?php  esc_attr( $disabled ); ?>
                               class="360Messenger-notif-groups" name="360Messenger_notif_groups[]"
                               value="<?php  esc_attr( $code ); ?>"/>
						<?php  esc_html( $text ); ?>
                    </label><br>
                    <!--</p>-->
				<?php endforeach; ?>

                <div class="360Messenger-notif-mobile-div">
                    <input type="text" id="360Messenger-notif-mobile-<?php  absint( $id ); ?>" class="360Messenger-notif-mobile"
                           name="360Messenger_notif_mobile"
                           value="<?php  get_user_meta( get_current_user_id(), WooNotify()->buyerMobileMeta(), true ); ?>"
                           style="text-align: left; direction: ltr" <?php  esc_html(sanitize_text_field( $disabled )); ?>
                           title="شماره موبایل" placeholder="شماره موبایل"/>
                </div>

				<?php if ( ! $can_be_subscribe ) : ?>
                    <p id="360Messenger-notif-disabled-<?php  absint( $id ); ?>" class="360Messenger-notif-disabled">
						 
						//old code
						// WooNotify()->getValue( 'notif_only_loggedin_text', $product_id ); 
						//new code
						<?php  esc_html( WooNotify()->getValue( 'notif_only_loggedin_text', $product_id ) ); ?>

						
						
                    </p>
				<?php else : ?>
                    <button id="360Messenger-notif-submit-<?php  absint( $id ); ?>"
                            class="360Messenger-notif-submit single_add_to_cart_button button alt"
                            style="margin-top: 5px;"
                            type="submit">ثبت
                    </button>
				<?php endif; ?>

                <p id="360Messenger-notif-result-p-<?php  absint( $id ); ?>" class="360Messenger-notif-result-p">
                    <span id="360Messenger-notif-result-<?php  absint( $id ); ?>" class="360Messenger-notif-result"></span>
                </p>
            </div>
        </form>

		<?php
		do_action( 'WooNotify_after_product_newsletter_form', $product );

		if ( $id == 1 ) {
			wc_enqueue_js( '
			jQuery(document).ready(function($){
				$(".360Messenger-notif-content").hide();
			    $(document.body).on( "change", ".360Messenger-notif-enable", function() {
					if( $(this).is(":checked") )
						$(this).closest("form").find(".360Messenger-notif-content").fadeIn();			
					else
				    	$(this).closest("form").find(".360Messenger-notif-content").fadeOut();
				}).on( "click", ".360Messenger-notif-submit", function() {
				    var form = $(this).closest("form");
				    var result = form.find(".360Messenger-notif-result");
				    result.html( "<img style=\"width:16px;display:inline;\" src=\"' . WooNotify_URL . '/assets/images/ajax-loader.gif\" />" );
			    	var 360Messenger_group = [];
				    form.find(".360Messenger-notif-groups:checked").each(function(i){
					    360Messenger_group[i] = $(this).val();
			    	});
				    $.ajax({
					    url : "' . admin_url( "admin-ajax.php" ) . '",
				    	type : "post",
					    data : {
						    action : "wc_360Messenger_save_notification_data",
					    	security: "' . wp_create_nonce( "wc_360Messenger_save_notification_data" ) . '",
						    360Messenger_mobile : form.find(".360Messenger-notif-mobile").val(),
						    360Messenger_group : 360Messenger_group,
						    product_id : "' . $product_id . '",
					    },
				    	success : function( response ) {
					    	result.html( response );
					    }
			    	});
				    return false;
		    	});
		    });
		' );
		}
	}

	public function updateSubscription() {

		check_ajax_referer( 'wc_360Messenger_save_notification_data', 'security' );

		$error_image = '<img style="width:16px;display:inline;" src="' . WooNotify_URL . '/assets/images/false.png">&nbsp;';

		
		$product_id = isset( $_POST['product_id'] ) ? absint( sanitize_text_field($_POST['product_id']) ) : 0;

		if ( empty( $product_id ) ) {
			if (get_locale() == 'fa_IR')
				die( esc_html(sanitize_text_field($error_image)) . esc_html('حطایی رخ داده است.') );
			else
				die( esc_html(sanitize_text_field($error_image)) . esc_html('An error occurred.') );
		}

		$can_be_subscribe = ! WooNotify()->hasNotifCond( 'notif_only_loggedin', $product_id ) || is_user_logged_in();
		if ( ! $can_be_subscribe ) {
			die( esc_html(sanitize_text_field($error_image)) . WooNotify()->getValue( 'notif_only_loggedin_text', $product_id ) );
		}
		
		$mobile = WooNotify()->modifyMobile(esc_attr( sanitize_text_field( $_POST['360Messenger_mobile'] ?? '' )) );
		if ( empty( $mobile ) ) {
			if (get_locale() == 'fa_IR')
				die( esc_html($error_image . 'شماره واتساپ را وارد نمایید.' ));
			else
				die( esc_html($error_image . 'Enter the WhatsApp number.') );
		}

		if ( ! WooNotify()->validateMobile( $mobile ) ) {
			if (get_locale() == 'fa_IR')
				die( esc_html($error_image . 'شماره واتساپ معتبر نیست.' ));
			else
				die( esc_html($error_image . 'The WhatsApp number is not valid.') );
		}

		if ( empty( $_POST['360Messenger_group'] ) ) {
			if (get_locale() == 'fa_IR')
				die( esc_html($error_image . 'انتخاب یکی از گزینه ها الزامیست.') );
			else
				die(esc_html( $error_image . 'Choosing one of the options is required.') );
		}

		$groups = (new WooNotify_360Messenger_Settings() )->WooNotify_360Messenger_array_sanitize_text_field( (array) $_POST['360Messenger_group'] );

		$success_image = '<img style="width:16px;display:inline;" src="' . WooNotify_URL . '/assets/images/tick.png">&nbsp;';

		$contact = (array) WooNotify_360Messenger_Contacts::getContactByMobile( $product_id, $mobile );

		if ( ! empty( $contact['id'] ) ) {

			$old_groups = ! empty( $contact['groups'] ) ? explode( ',', $contact['groups'] ) : [];
			$new_groups = array_merge( $old_groups, $groups );

			$update = WooNotify_360Messenger_Contacts::updateContact( [
				'id'         => $contact['id'],
				'product_id' => $product_id,
				'mobile'     => $mobile,
				'groups'     => $new_groups,
			] );

			if ( $update !== false ) {
				if (get_locale() == 'fa_IR')
					die( esc_html(sanitize_text_field($success_image) . 'اطلاعات شما با موفقیت بروز شد.') );
				else
					die( esc_html(sanitize_text_field($success_image) . 'Your information has been successfully updated.' ));
			}

		} else {

			$insert = WooNotify_360Messenger_Contacts::insertContact( [
				'product_id' => $product_id,
				'mobile'     => $mobile,
				'groups'     => $groups,
			] );

			if ( $insert ) {
				if (get_locale() == 'fa_IR')
					die(esc_html( sanitize_text_field($success_image) . 'اطلاعات شما با موفقیت ثبت شد.' ));
				else
					die( esc_html(sanitize_text_field($success_image) . 'Your information has been successfully registered.') );
			}
		}
		if (get_locale() == 'fa_IR')
			die( esc_html(sanitize_text_field($error_image) . 'خطایی رخ داده است. مجددا تلاش کنید.') );
		else
			die( esc_html(sanitize_text_field($error_image) . 'An error occurred. Try again.') );
	}
}


add_action( 'widgets_init', function () {
	register_widget( 'WooNotify_360Messenger_Subscription' );
} );