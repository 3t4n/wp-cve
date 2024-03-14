<?php
namespace Login_With_AJAX;

// admin modal notices
class Admin_Modals {
	
	public static $output_js = false;
	
	public static function init() {
		add_filter('admin_enqueue_scripts', array( static::class, 'admin_enqueue_scripts' ), 100);
		add_filter('wp_ajax_lwa-admin-popup-modal', array( static::class, 'ajax' ));
		add_filter('lwa_admin_notice_review-nudge_message', array( static::class, 'review_notice' ));
		if( time() < 1709078400 ) {
			add_filter( 'lwa_admin_notice_promo-popup_message', array( static::class, 'promo_notice' ) );
		}
		add_filter( 'lwa_admin_notice_expired-reminder_message', array( static::class, 'expired_reminder_notice' ) );
		add_filter( 'lwa_admin_notice_expiry-reminder_message', array( static::class, 'expiry_reminder_notice' ) );
	}
	
	public static function admin_enqueue_scripts(){
		if( !current_user_can('update_plugins') ) return;
		// show modal
		$data = is_multisite() ? get_site_option('lwa_admin_notices') : get_option('lwa_admin_notices');
		
		
		if( !empty($data['admin-modals']) ){
			$show_plugin_pages = !empty($_REQUEST['page']) && preg_match('/^login\-with\-ajax/', $_REQUEST['page']);
			$show_network_admin = is_network_admin() && !empty($_REQUEST['page']) && preg_match('/^login\-with\-ajax/', $_REQUEST['page']);
			// show review nudge
			if( !empty($data['admin-modals']['review-nudge']) && $data['admin-modals']['review-nudge'] < time() ) {
				if(true ) {
					// check it hasn't been shown more than 1 times, if so revert it to a regular admin notice
					if( empty($data['admin-modals']['review-nudge-count']) ){
						$data['admin-modals']['review-nudge-count'] = 0;
					}
					if( $data['admin-modals']['review-nudge-count'] < 1 ) {
						// enqueue script and load popup action
						if ( ! wp_script_is( 'login-with-ajax-admin' ) ) {
							\LoginWithAjax::enqueue_scripts_and_styles( true );
							Admin::enqueue_scripts_and_styles( true );
						}
						add_filter( 'admin_footer', array( static::class, 'review_popup' ) );
						$data['admin-modals']['review-nudge-count']++;
						update_site_option('lwa_admin_notices', $data);
					}else{
						// move it into a regular admin notice and stop displaying
						unset($data['admin-modals']['review-nudge-count']);
						unset($data['admin-modals']['review-nudge']);
						update_site_option('lwa_admin_notices', $data);
						// notify user of new update
						$Admin_Notice = new Admin_Notice(array( 'name' => 'review-nudge', 'who' => 'admin', 'where' => 'all' ));
						Admin_Notices::add($Admin_Notice, is_multisite());
					}
				}
			}
			// promo
			// check if pro license is active
			$pro_license_active = defined('LWA_PRO_VERSION');
			if( $pro_license_active ){
				$key = get_option('lwa_pro_api_key');
				$pro_license_active = !(empty($key['until']) || $key['until'] < strtotime('+10 months'));
			}
			if( time() < 1709078400 && !empty($data['admin-modals']['promo-popup']) /*&& !$pro_license_active*/) {
				if( $data['admin-modals']['promo-popup'] == 1 || ($data['admin-modals']['promo-popup'] == 2 ) ) {
					// enqueue script and load popup action
					if( empty($data['admin-modals']['promo-popup-count']) ){
						$data['admin-modals']['promo-popup-count'] = 0;
					}
					if( $data['admin-modals']['promo-popup-count'] <= 1 ) {
						if ( ! wp_script_is( 'login-with-ajax-admin' ) ) {
							\LoginWithAjax::enqueue_scripts_and_styles( true );
							Admin::enqueue_scripts_and_styles( true );
						}
						add_filter('admin_footer', array( static::class, 'promo_popup' ));
						$data['admin-modals']['promo-popup-count']++;
						update_site_option('lwa_admin_notices', $data);
					}else{
						// move it into a regular admin notice and stop displaying
						unset($data['admin-modals']['promo-popup-count']);
						unset($data['admin-modals']['promo-popup']);
						update_site_option('lwa_admin_notices', $data);
						// notify user of new update
						$Admin_Notice = new Admin_Notice(array( 'name' => 'promo-popup', 'who' => 'admin', 'where' => 'all' ));
						Admin_Notices::add($Admin_Notice, is_multisite());
					}
				}
			}
		}
		
		// LWA Pro License Expired Promo & Reminder
		if( defined('LWA_PRO_VERSION') ){
			$key = get_option('lwa_pro_api_key');
			$license_expired = empty($key['until']) || $key['until'] < time();
			// add reminder for expiring
			if( !empty($key['until']) && !$license_expired ) {
				if( $key['until'] < strtotime('+14 days') ) {
					if( !Options::get('license_expiry_reminder') ) {
						Options::set('license_expiry_reminder', true);
						$Admin_Notice = new Admin_Notice(array( 'name' => 'expiry-reminder', 'who' => 'admin', 'where' => 'all' ));
						Admin_Notices::add($Admin_Notice, is_multisite());
						// reset others
						Options::remove('license_expired_reminder');
						Admin_Notices::remove('expired-reminder');
						Options::remove('license_expiry_promo');
						Admin_Notices::remove('expired-promo');
					}
				} else {
					// remove all
					if ( Options::get('license_expiry_reminder') ) {
						Options::remove('license_expiry_reminder');
						Admin_Notices::remove('expiry-reminder');
					}
					if ( Options::get('license_expiry_promo') ) {
						Options::remove( 'license_expiry_promo' );
						Admin_Notices::remove('expired-promo');
					}
					if ( Options::get('license_expired_reminder') ) {
						Options::remove( 'license_expired_reminder' );
						Admin_Notices::remove('expired-reminder');
					}
				}
			}
		}
	}
	
	public static function review_popup(){
		// check admin data and see if show data is still enabled
		?>
		<div class="lwa-modal-overlay lwa-admin-modal" id="lwa-review-nudge" data-nonce="<?php echo wp_create_nonce('lwa-review-nudge'); ?>">
			<div class="lwa-modal-popup lwa-wrapper lwa-bones">
				<div class="lwa pixelbones">
					<header>
						<div class="lwa-modal-title"><?php esc_html_e('Enjoying Login With AJAX? Help Us Improve!', 'login-with-ajax'); ?></div>
					</header>
					<div class="lwa-modal-content has-image">
						<div>
							<p><?php esc_html_e('Pardon the interruption... we hope you\'re enjoying Login With AJAX, and if so, we\'d really appreciate a positive review on the wordpress.org repository!', 'login-with-ajax'); ?></p>
							<p><?php esc_html_e('Login With AJAX has been maintained, developed and supported for free since it was released in 2008, positive reviews are one way that help us keep going.', 'login-with-ajax'); ?></p>
							<p><?php esc_html_e('If you could spare a few minutes, we would appreciate it if you could please leave us a review.', 'login-with-ajax'); ?></p>
						</div>
						<div class="image">
							<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/star-halo.svg'; ?>" style="width:75%; opacity:0.7;">
							<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/icon.svg'; ?>">
						</div>
					</div><!-- content -->
					<footer class="lwa-submit-section input">
						<div>
							<a href="https://wordpress.org/support/plugin/login-with-ajax/reviews/?filter=5#new-topic-0" class="button button-primary input" target="_blank" style="margin:10px auto; --accent-color:#429543; --accent-color-hover:#429543;">
								Leave a Review
								<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/five-stars.svg'; ?>" style="max-height:10px; width:50px; margin-left:5px;">
							</a>
							<button class="button button-secondary dismiss-modal"><?php esc_html_e('Dismiss Message', 'login-with-ajax'); ?></button>
						</div>
					</footer>
				</div>
			</div><!-- modal -->
		</div>
		<?php
		static::output_js();
	}
	
	public static function review_notice(){
		ob_start();
		?>
		<div style="display: grid; grid-template-columns: 80px auto; grid-gap: 20px;">
			<div style="align-self: center; text-align: center; padding-left: 10px;">
				<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/star-halo.svg'; ?>" style="width:75%; opacity:0.7;">
				<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/icon.svg'; ?>" style="width: 100%;">
			</div>
			<div>
				<p><?php esc_html_e('Pardon the interruption... we hope you\'re enjoying Login With AJAX, and if so, we\'d really appreciate a positive review on the wordpress.org repository!', 'login-with-ajax'); ?></p>
				<p>
					<?php esc_html_e('Login With AJAX has been maintained, developed and supported for free since it was released in 2008, positive reviews are one that help us keep going.', 'login-with-ajax'); ?>
					<?php esc_html_e('If you could spare a few minutes, we would appreciate it if you could please leave us a review.', 'login-with-ajax'); ?>
				</p>
				<a href="https://wordpress.org/support/plugin/login-with-ajax/reviews/?filter=5#new-topic-0" class="button button-primary input" target="_blank" style="margin:10px 10px 10px 0; --accent-color:#429543; --accent-color-hover:#429543;">
					Leave a Review
					<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/five-stars.svg'; ?>" style="max-height:10px; width:50px; margin-left:5px;">
				</a>
				<a href="<?php echo esc_url( admin_url('admin-ajax.php?action=lwa_dismiss_admin_notice&notice=review-nudge&redirect=1&nonce='.wp_create_nonce('lwa_dismiss_admin_noticereview-nudge') ) ); ?>" class="button button-secondary" style="margin:10px 0;"><?php esc_html_e('Dismiss', 'login-with-ajax'); ?></a>
			</div>
		</div><!-- content -->
		<?php
		return ob_get_clean();
	}
	
	public static function promo_popup(){
		// check admin data and see if show data is still enabled
		?>
		<div class="lwa-modal-overlay lwa-admin-modal" id="lwa-promo-popup" data-nonce="<?php echo wp_create_nonce('lwa-promo-popup'); ?>">
			<div class="lwa-modal-popup lwa-wrapper lwa-bones">
				<div class="lwa pixelbones">
					<div class="lwa pixelbones">
						<header>
							<a class="lwa-close-modal dismiss-modal" href="#"></a><!-- close modal -->
							<div class="lwa-modal-title">Login With AJAX Pro - Limited Time 50% Off!</div>
						</header>
						<div class="lwa-modal-content has-image" style="--font-size:16px;">
							<div>
								<p>Pardon the interruption.... we'd like to make sure you're aware of our limited time deal. Purchase a license, renew or upgrade and get up to 50% off!</p>
								<p>We have just released Pro 2.0 which adds some amazing new features:</p>
								<ul>
									<li>PassKeys - Passwordless and Secure On-Click Logins</li>
									<li>SMS 2FA</li>
									<li>WhatsApp One-Click 2FA</li>
									<li>Telegram One-Click 2FA</li>
								</ul>
								<p>We hope you're enjoying the plugin and if you're at all considering going Pro, you still have time to make the best of this limited opportunity!</p>
							</div>
							<div class="image">
								<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/icon.svg'; ?>">
								<a href="https://loginwithajax.com/gopro/?utm_source=login-with-ajax&utm_medium=plugin-popup&utm_campaign=plugins" class="button button-primary input" target="_blank" style="margin:10px auto; --accent-color:#429543; --accent-color-hover:#429543;">Go Pro!</a>
							</div>
						</div><!-- content -->
						<footer class="lwa-submit-section input">
							<div>
							</div>
							<div>
								<button class="button button-secondary dismiss-modal">Dismiss Notice</button>
							</div>
						</footer>
					</div><!-- modal -->
				</div>
			</div>
		</div>
		<?php
		static::output_js();
	}
	
	public static function promo_notice(){
		ob_start();
		?>
		<div style="display: grid; grid-template-columns: 80px auto; grid-gap: 20px;">
			<div style="text-align: center; padding-left: 10px; padding-top:10px;">
				<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/icon.svg'; ?>" style="width: 100%;">
			</div>
			<div>
				<h3>Login With AJAX Pro - Offer Ends Soon!</h3>
				<p>Pardon the interruption.... we'd like to make sure you're aware of our limited time deal. Purchase a license, renew or upgrade and get up to 50% off!</p>
				<p>LWA 2.0 introduces some amazing new login and security features such as  Passkeys (one-click logins) and additional 2FA methods including SMS, WhatsApp and Telegram.</p>
				<p>We hope you're enjoying the plugin and if you're at all considering going Pro, you still have time to make the best of this limited opportunity!</p>
				<a href="https://loginwithajax.com/gopro/?utm_source=login-with-ajax&utm_medium=plugin-notice&utm_campaign=plugins" class="button button-primary" target="_blank" style="margin:10px auto; --accent-color:#429543; --accent-color-hover:#429543;">Go Pro!</a>
				<a href="<?php echo esc_url( admin_url('admin-ajax.php?action=lwa_dismiss_admin_notice&notice=promo-popup&redirect=1&nonce='.wp_create_nonce('lwa_dismiss_admin_noticepromo-popup') ) ); ?>" class="button button-secondary" style="margin:10px 0;"><?php esc_html_e('Dismiss', 'login-with-ajax'); ?></a>
			</div>
		</div><!-- content -->
		<?php
		return ob_get_clean();
	}
	
	public static function expired_reminder_notice(){
		ob_start();
		?>
		<div style="display: grid; grid-template-columns: 80px auto; grid-gap: 20px;">
			<div style="text-align: center; padding-left: 10px; padding-top:10px;">
				<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/icon.svg'; ?>" style="width: 100%;">
			</div>
			<div>
				<h3>Login With AJAX Pro - License Expired</h3>
				<p>Your Pro license has expired, meaning you will not have access to our latest updates and Pro support. Please renew your license to get access to the latest features and our Pro support.</p>
				<p>We are regularly adding new features, don't miss out and renew now!</p>
				<a href="https://loginwithajax.com/gopro/?utm_source=login-with-ajax&utm_medium=plugin-notice&utm_campaign=plugins" class="button button-primary input" target="_blank" style="margin-right:10px; --accent-color:#429543; --accent-color-hover:#429543;">Renew Now!</a>
			</div>
		</div><!-- content -->
		<?php
		return ob_get_clean();
	}
	
	public static function expiry_reminder_notice(){
		ob_start();
		$key = get_option('lwa_pro_api_key');
		$expiry_date = date('Y-m-d', $key['until']);
		?>
		<div style="display: grid; grid-template-columns: 80px auto; grid-gap: 20px;">
			<div style="text-align: center; padding-left: 10px; padding-top:10px;">
				<img src="<?php echo LOGIN_WITH_AJAX_URL . '/assets/images/icon.svg'; ?>" style="width: 100%;">
			</div>
			<div>
				<h3>Login With AJAX Pro - Your License is Expiring Soon...</h3>
				<p>Your Pro license is expiring on <?php echo $expiry_date; ?>. By renewing on time, you maintain your current plan pricing and conditions.</p>
				<p>Renew now to maintain access to our latest updates and Pro support. We hope you're finding the plugin useful and we look forward to providing you with more exciting new features!</p>
				<a href="https://loginwithajax.com/gopro/?utm_source=login-with-ajax&utm_medium=plugin-notice&utm_campaign=plugins" class="button button-primary input" target="_blank" style="margin-right:10px; --accent-color:#429543; --accent-color-hover:#429543;">Renew Now!</a>
			</div>
		</div><!-- content -->
		<?php
		return ob_get_clean();
	}
	
	public static function output_js(){
		if( !static::$output_js ){
			?>
			<script>
				jQuery(document).ready(function($){
					// Modal Open/Close
					let openModal = function( modal, onOpen = null ){
						modal = jQuery(modal);
						modal.appendTo(document.body);
						setTimeout( function(){
							modal.addClass('active').find('.lwa-modal-popup').addClass('active');
							jQuery(document).triggerHandler('lwa:_modal_open', [modal]);
							if( typeof onOpen === 'function' ){
								setTimeout( onOpen, 200); // timeout allows css transition
							}
						}, 100); // timeout allows css transition
					};
					let closeModal = function( modal, onClose = null ){
						modal = jQuery(modal);
						modal.removeClass('active').find('.lwa-modal-popup').removeClass('active');
						setTimeout( function(){
							if( modal.attr('data-parent') ){
								let wrapper = jQuery('#' + modal.attr('data-parent') );
								if( wrapper.length ) {
									modal.appendTo(wrapper);
								}
							}
							modal.triggerHandler('lwa_modal_close');
							if( typeof onClose === 'function' ){
								onClose();
							}
						}, 500); // timeout allows css transition
					}
					$('.lwa-admin-modal').each( function(){
						let modal = $(this);
						let ignore_event = false;
						openModal( modal );
						modal.on('lwa_modal_close', function(){
							// send AJAX to close
							if( ignore_event ) return false;
							$.post( LWA.ajaxurl, { action : 'lwa-admin-popup-modal', 'dismiss':'close', 'modal':modal.attr('id'), 'nonce': modal.attr('data-nonce') });
						});
						modal.find('button.dismiss-modal').on('click', function(){
							// send AJAX to close
							ignore_event = true;
							closeModal(modal);
							$.post( LWA.ajaxurl, { action : 'lwa-admin-popup-modal', 'dismiss':'button', 'modal':modal.attr('id'), 'nonce':modal.attr('data-nonce') });
						});
					});
				});
			</script>
			<?php
			static::$output_js = true;
		}
	}
	
	public static function ajax(){
		if( !empty($_REQUEST['modal']) && wp_verify_nonce($_REQUEST['nonce'], $_REQUEST['modal']) ){
			$action = sanitize_key( preg_replace('/^lwa\-/', '', $_REQUEST['modal']) );
			$data = is_multisite() ? get_site_option('lwa_admin_notices') : get_option('lwa_admin_notices');
			if( $_REQUEST['dismiss'] == 'button' || $data['admin-modals'][$action] === 2 ) {
				// disable the modal so it's not shown again
				unset($data['admin-modals'][$action]);
				if( !empty($data['admin-modals'][$action.'-count']) ) unset($data['admin-modals'][$action.'-count']);
				is_multisite() ? update_site_option('lwa_admin_notices', $data) : update_option('lwa_admin_notices', $data);
			}else{
				// limit popup to LWA pages only
				$data['admin-modals'][$action] = 2;
				is_multisite() ? update_site_option('lwa_admin_notices', $data) : update_option('lwa_admin_notices', $data);
			}
		}
	}
}
Admin_Modals::init();