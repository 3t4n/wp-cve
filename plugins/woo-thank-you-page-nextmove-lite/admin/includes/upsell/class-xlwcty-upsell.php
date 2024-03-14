<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class XLWCTY_Upsell {

	protected static $instance = null;
	protected $name = '';
	protected $year = '';
	protected $notice_time = array();
	protected $notice_displayed = false;
	protected $plugin_path = XLWCTY_PLUGIN_FILE;

	/**
	 * construct
	 */
	public function __construct() {
		$this->name = 'NextMove: WooCommerce Thank You Pages';
		$this->year = date( 'y' );
		if ( 1 === absint( date( 'n' ) ) ) {
			$this->year = $this->year - 1;
		}

		$this->hooks();
		$this->set_notice_timings();
	}

	/**
	 * Getting class instance
	 * @return null|instance
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Initiate hooks
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'xl_notice_variable' ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'notice_enqueue_scripts' ) );
		add_action( 'wp_ajax_nextmove_upsells_dismiss', array( $this, 'xl_dismiss_notice' ) );

		add_action( 'admin_notices', array( $this, 'xl_christmas_sale_notice' ), 10 );
		add_action( 'admin_notices', array( $this, 'xl_bfcm_sale_notice' ), 10 );
		add_action( 'admin_notices', array( $this, 'xl_pre_black_friday_sale_notice' ), 10 );
		add_action( 'admin_notices', array( $this, 'xl_halloween_sale_notice' ), 10 );

		add_action( 'admin_notices', array( $this, 'xl_upsells_notice_html_finale' ), 10 );
		add_action( 'admin_notices', array( $this, 'xl_upsells_notice_html_nextmove' ), 10 );
		add_action( 'admin_notices', array( $this, 'xl_upsells_notice_html_autonami' ), 10 );

		add_action( 'admin_notices', array( $this, 'xl_upsells_notice_js' ), 20 );
	}

	/**
	 * Assigning plugin notice timings
	 * Always use 2 time period as 'no'
	 */
	public function set_notice_timings() {
		$finale_notice_time          = array(
			'0' => 20 * DAY_IN_SECONDS, // +20 days
			'1' => 3 * DAY_IN_SECONDS, // +3 days
		);
		$this->notice_time['finale'] = $finale_notice_time;

		$nextmove_notice_time          = array(
			'0' => 3 * DAY_IN_SECONDS, // +3 days
			'1' => 3 * DAY_IN_SECONDS, // +3 days
		);
		$this->notice_time['nextmove'] = $nextmove_notice_time;

		$autonami_notice_time          = array(
			'0' => 10 * DAY_IN_SECONDS, // +10 days
			'1' => 3 * DAY_IN_SECONDS, // +3 days
		);
		$this->notice_time['autonami'] = $autonami_notice_time;

		$halloween_sale_notice_time                           = array(
			'0' => 0.05 * DAY_IN_SECONDS, // +1.2 hrs
			'1' => 1 * DAY_IN_SECONDS, // +1 day
		);
		$this->notice_time[ 'halloween_sale_' . $this->year ] = $halloween_sale_notice_time;

		$pre_bf_sale_notice_time                                = array(
			'0' => 0.05 * DAY_IN_SECONDS, // +1.2 hrs
			'1' => 1 * DAY_IN_SECONDS, // +1 day
		);
		$this->notice_time[ 'pre_black_friday_' . $this->year ] = $pre_bf_sale_notice_time;

		$bfcm_sale_notice_time                      = array(
			'0' => 0.05 * DAY_IN_SECONDS, // +1.2 hrs
			'1' => 1 * DAY_IN_SECONDS, // +1 day
		);
		$this->notice_time[ 'bfcm_' . $this->year ] = $bfcm_sale_notice_time;

		$christmas_sale_notice_time                      = array(
			'0' => 0.05 * DAY_IN_SECONDS, // +1.2 hrs
			'1' => 1 * DAY_IN_SECONDS, // +1 day
		);
		$this->notice_time[ 'christmas_' . $this->year ] = $christmas_sale_notice_time;
	}

	/**
	 * Assign notice variable to false if not set
	 * @global boolean $xl_upsells_notice_active
	 */
	public function xl_notice_variable() {
		global $xl_upsells_notice_active;
		if ( '' == $xl_upsells_notice_active ) {
			$xl_upsells_notice_active = false;
		}
	}

	/**
	 * Enqueue assets
	 */
	public function notice_enqueue_scripts() {
		wp_enqueue_style( 'xlwcty-notices-css', plugin_dir_url( $this->plugin_path ) . 'admin/assets/css/xlwcty-xl-notice.css', array(), XLWCTY_VERSION );
		wp_enqueue_script( 'wp-util' );
	}

	/**
	 * Upsell notice html - NextMove
	 *
	 * @return void
	 */
	public function xl_upsells_notice_html_nextmove() {
		global $xl_upsells_notice_active;
		$short_slug = 'nextmove';
		if ( true === $xl_upsells_notice_active ) {
			return;
		}
		if ( true === $this->plugin_already_installed( $short_slug ) ) {
			return;
			/** As this is a lite plugin */
		}
		if ( true === $this->hide_notice() ) {
			return;
		}
		if ( ! isset( $this->notice_time[ $short_slug ] ) ) {
			return;
		}
		$this->main_plugin_activation( $short_slug );
		if ( true === $this->notice_dismissed( $short_slug ) ) {
			return;
		}
		$this->notice_displayed = true;
		echo $this->nextmove_notice_html();
		$xl_upsells_notice_active = true;
	}

	/**
	 * Upsell notice html - Finale
	 *
	 * @return void
	 */
	public function xl_upsells_notice_html_finale() {
		global $xl_upsells_notice_active;
		$short_slug = 'finale';
		if ( true === $xl_upsells_notice_active ) {
			return;
		}
		if ( true === $this->plugin_already_installed( $short_slug ) ) {
			return;
		}
		if ( true === $this->hide_notice() ) {
			return;
		}
		if ( ! isset( $this->notice_time[ $short_slug ] ) ) {
			return;
		}
		$this->main_plugin_activation( $short_slug );
		if ( true === $this->notice_dismissed( $short_slug ) ) {
			return;
		}
		$this->notice_displayed = true;
		echo $this->finale_notice_html();
		$xl_upsells_notice_active = true;
	}

	/**
	 * Upsell notice html - FunnelKit Automation
	 *
	 * @return void
	 */
	public function xl_upsells_notice_html_autonami() {
		global $xl_upsells_notice_active;
		$short_slug = 'autonami';
		if ( true === $xl_upsells_notice_active ) {
			return;
		}
		if ( true === $this->plugin_already_installed( $short_slug ) ) {
			return;
		}
		if ( true === $this->hide_notice() ) {
			return;
		}
		if ( ! isset( $this->notice_time[ $short_slug ] ) ) {
			return;
		}
		$this->main_plugin_activation( $short_slug );
		if ( true === $this->notice_dismissed( $short_slug ) ) {
			return;
		}
		$this->notice_displayed = true;
		echo $this->autonami_notice_html();
		$xl_upsells_notice_active = true;
	}

	/**
	 * Halloween Sale notice html
	 *
	 * @return void
	 * @throws Exception
	 */
	public function xl_halloween_sale_notice() {
		global $xl_upsells_notice_active;
		$short_slug = 'halloween_sale_' . $this->year;
		if ( true === $xl_upsells_notice_active ) {
			return;
		}
		if ( true === $this->hide_notice() ) {
			return;
		}
		if ( true === $this->valid_time_duration( $short_slug ) ) {
			return;
		}
		$this->main_plugin_activation( $short_slug );
		if ( true === $this->notice_dismissed( $short_slug ) ) {
			return;
		}
		$this->notice_displayed = true;
		echo $this->halloween_sale_notice_html();
		$xl_upsells_notice_active = true;
	}

	/**
	 * Pre Black Friday Sale notice html
	 *
	 * @return void
	 * @throws Exception
	 */
	public function xl_pre_black_friday_sale_notice() {
		global $xl_upsells_notice_active;
		$short_slug = 'pre_black_friday_' . $this->year;
		if ( true === $xl_upsells_notice_active ) {
			return;
		}
		if ( true === $this->hide_notice() ) {
			return;
		}
		if ( true === $this->valid_time_duration( $short_slug ) ) {
			return;
		}
		$this->main_plugin_activation( $short_slug );
		if ( true === $this->notice_dismissed( $short_slug ) ) {
			return;
		}
		$this->notice_displayed = true;
		echo $this->pre_black_friday_notice_html();
		$xl_upsells_notice_active = true;
	}

	/**
	 * Black Friday Cyber Monday Sale notice html
	 *
	 * @return void
	 * @throws Exception
	 */
	public function xl_bfcm_sale_notice() {
		global $xl_upsells_notice_active;
		$short_slug = 'bfcm_' . $this->year;
		if ( true === $xl_upsells_notice_active ) {
			return;
		}
		if ( true === $this->hide_notice() ) {
			return;
		}
		if ( true === $this->valid_time_duration( $short_slug ) ) {
			return;
		}
		$this->main_plugin_activation( $short_slug );
		if ( true === $this->notice_dismissed( $short_slug ) ) {
			return;
		}
		$this->notice_displayed = true;
		echo $this->bfcm_notice_html();
		$xl_upsells_notice_active = true;
	}

	/**
	 * Christmas New Year Sale notice html
	 *
	 * @return void
	 * @throws Exception
	 */
	public function xl_christmas_sale_notice() {
		global $xl_upsells_notice_active;
		if ( true === $xl_upsells_notice_active ) {
			return;
		}
		if ( true === $this->hide_notice() ) {
			return;
		}
		if ( true === $this->valid_time_duration( 'christmas_' . $this->year ) ) {
			return;
		}
		$this->main_plugin_activation( 'christmas_' . $this->year );
		if ( true === $this->notice_dismissed( 'christmas_' . $this->year ) ) {
			return;
		}
		$this->notice_displayed = true;
		echo $this->christmas_notice_html();
		$xl_upsells_notice_active = true;
	}

	/**
	 * Checking if plugin already installed
	 * @return boolean
	 */
	public function plugin_already_installed( $plugin_short_name ) {
		if ( 'nextmove' == $plugin_short_name ) {
			if ( class_exists( 'XLWCTY_Core' ) ) {
				return true;
			}
		}
		if ( 'finale' == $plugin_short_name ) {
			if ( class_exists( 'WCCT_Core' ) ) {
				return true;
			}
		}
		if ( 'autonami' == $plugin_short_name ) {
			if ( class_exists( 'BWFAN_Core' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Hide upsell notice on defined pages.
	 * @return boolean
	 */
	public function hide_notice() {
		$screen     = get_current_screen();
		$base_array = array( 'plugin-install', 'update-core', 'post', 'export', 'import', 'upload', 'media', 'edit', 'edit-tags' );
		$post_type  = 'xlwcty_thankyou';
		if ( is_object( $screen ) && in_array( $screen->base, $base_array ) ) {
			if ( 'post' == $screen->base && $post_type == $screen->post_type ) {
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * First time assigning display timings for plugin upsell
	 *
	 * @param $plugin_short_name
	 */
	public function main_plugin_activation( $plugin_short_name ) {
		$notice_displayed_count = get_option( $plugin_short_name . '_upsell_displayed', '0' );
		if ( '0' == $notice_displayed_count ) {
			if ( isset( $this->notice_time[ $plugin_short_name ][ $notice_displayed_count ] ) && '' != $this->notice_time[ $plugin_short_name ][ $notice_displayed_count ] ) {
				$this->plugin_upsell_set_values( (int) $this->notice_time[ $plugin_short_name ][ $notice_displayed_count ], $plugin_short_name, ( (int) $notice_displayed_count + 1 ) );
			} else {
				// set expiration for an year
				$this->plugin_upsell_set_values( YEAR_IN_SECONDS, $plugin_short_name );
			}
		}
	}

	/**
	 * Setting values in transient or option for upsell plugin
	 *
	 * @param $expire_time
	 * @param $plugin_short_name
	 * @param $notice_displayed_count
	 */
	public function plugin_upsell_set_values( $expire_time, $plugin_short_name, $notice_displayed_count = 100 ) {
		$this->set_xl_transient( $plugin_short_name . '_upsell_hold_time', 'yes', $expire_time );
		update_option( $plugin_short_name . '_upsell_displayed', $notice_displayed_count, false );
	}

	/**
	 * Check if the notice is dismissed
	 *
	 * @param $plugin_short_name
	 *
	 * @return boolean
	 */
	public function notice_dismissed( $plugin_short_name ) {
		$upsell_dismissed_forever = get_option( $plugin_short_name . '_upsell_displayed', false );
		if ( '100' == $upsell_dismissed_forever ) {
			return true;
		}
		$notice_display = $this->get_xl_transient( $plugin_short_name . '_upsell_hold_time' );
		if ( false === $notice_display ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the notice display duration is correct
	 *
	 * @param $plugin_short_name
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function valid_time_duration( $plugin_short_name ) {
		$current_date_obj = new DateTime( 'now', new DateTimeZone( 'America/New_York' ) );
		if ( 'halloween_sale_' . $this->year == $plugin_short_name ) {
			if ( $current_date_obj->getTimestamp() > 1541131200 ) {
				return true;
			}
			// 1541131200 nov 2 midnight
		} elseif ( 'pre_black_friday_' . $this->year == $plugin_short_name ) {
			if ( $current_date_obj->getTimestamp() < 1574208000 || $current_date_obj->getTimestamp() > 1574985600 ) {
				return true;
			}
			// 1574208000 nov 20 midnight
			// 1574985600 nov 29 midnight
		} elseif ( 'bfcm_' . $this->year == $plugin_short_name ) {
			if ( $current_date_obj->getTimestamp() < 1574985600 || $current_date_obj->getTimestamp() > 1575676800 ) {
				return true;
			}
			// 1574985600 nov 29 midnight
			// 1575676800 dec 7 midnight
		} elseif ( 'christmas_' . $this->year == $plugin_short_name ) {
			if ( $current_date_obj->getTimestamp() < 1576800000 || $current_date_obj->getTimestamp() > 1577923200 ) {
				return true;
			}
			// 1576800000 dec 20 midnight
			// 1577923200 jan 2 midnight
		}

		return false;
	}

	/**
	 * Dismiss the notice via Ajax
	 * @return void
	 */
	public function xl_dismiss_notice() {
		if ( isset( $_POST['notice_displayed_count'] ) && ( '' != $_POST['notice_displayed_count'] ) ) {
			$notice_displayed_count = $_POST['notice_displayed_count'];
		} else {
			$notice_displayed_count = '100';
		}
		$this->dismiss_notice( $_POST['plugin'], $notice_displayed_count );
		wp_send_json_success();
	}

	/**
	 * Dismiss notice
	 *
	 * @param $plugin_short_name
	 * @param $notice_displayed_count
	 *
	 * @return void
	 */
	public function dismiss_notice( $plugin_short_name, $notice_displayed_count = '' ) {
		if ( empty( $notice_displayed_count ) ) {
			$notice_displayed_count = get_option( $plugin_short_name . '_upsell_displayed', '0' );
		}
		if ( '+1' == $notice_displayed_count ) {
			$notice_time = $this->notice_time[ $plugin_short_name ];
			end( $notice_time );
			$key = key( $notice_time );
			if ( isset( $notice_time[ $key ] ) && ( '' != $notice_time[ $key ] ) ) {
				$this->plugin_upsell_set_values( (int) $notice_time[ $key ], $plugin_short_name, ( (int) $key ) );

				return;
			}
		}
		if ( isset( $this->notice_time[ $plugin_short_name ][ $notice_displayed_count ] ) && ( '' != $this->notice_time[ $plugin_short_name ][ $notice_displayed_count ] ) ) {
			$this->plugin_upsell_set_values( (int) $this->notice_time[ $plugin_short_name ][ $notice_displayed_count ], $plugin_short_name, ( (int) $notice_displayed_count + 1 ) );
		} else {
			// set expiration for an year
			$this->plugin_upsell_set_values( YEAR_IN_SECONDS, $plugin_short_name );
		}
	}

	/**
	 * Upsell notice js
	 * common per plugin
	 */
	public function xl_upsells_notice_js() {
		if ( true === $this->notice_displayed ) {
			ob_start();
			?>
            <script type="text/javascript">
                (function ($) {
                    var noticeWrap = $('#xl_notice_type_3');
                    var pluginShortSlug = noticeWrap.attr("data-plugin");
                    var pluginSlug = noticeWrap.attr("data-plugin-slug");
                    $('body').on('click', '.xl-notice-dismiss', function (e) {
                        e.preventDefault();
                        var $this = $(this);

                        noticeWrap = $this.parents('#xl_notice_type_3');
                        pluginShortSlug = noticeWrap.attr("data-plugin");

                        var xlDisplayedMode = $this.attr("data-mode");
                        if ('dismiss' == xlDisplayedMode) {
                            xlDisplayedCount = '100';
                        } else if ('later' == xlDisplayedMode) {
                            xlDisplayedCount = '+1';
                        }
                        wp.ajax.send('nextmove_upsells_dismiss', {
                            data: {
                                plugin: pluginShortSlug,
                                notice_displayed_count: xlDisplayedCount,
                            },
                        });
                        $this.closest('.updated').slideUp('fast', function () {
                            $this.remove();
                        });
                    });
                    $(document).on('wp-plugin-install-success', function (e, args) {
                        if (args.slug == pluginSlug) {
                            wp.ajax.send('nextmove_upsells_dismiss', {
                                data: {
                                    plugin: pluginShortSlug,
                                    notice_displayed_count: '100',
                                },
                            });
                        }
                    });
                })(jQuery);
            </script>
			<?php
			echo ob_get_clean();
		}
	}

	protected function external_template( $notice_slug, $plugin_name, $plugin_url, $heading, $sub_heading, $image ) {
		?>
        <div class="updated" id="xl_notice_type_3" data-offer="yes" data-plugin="<?php echo $notice_slug ?>">
            <div class="xl_upsell_area">
                <div class="upsell_left_abs">
                    <img width="70" src="<?php echo $image ?>" alt="<?php echo $plugin_name ?>"/>
                </div>
                <div class="upsell_main_abs">
                    <h3><?php echo $heading ?></h3>
                    <p><?php echo $sub_heading ?></p>
                </div>
                <div class="upsell_right_abs">
                    <div id="plugin-filter" class="upsell_xl_plugin_btn">
                        <a class="button-primary" href="<?php echo $plugin_url; ?>" data-name="<?php echo $plugin_name ?>" target="_blank">Explore this Amazing Offer</a>
                        <span class="dashicons dashicons-calendar"></span>
                        <a class="xl-notice-dismiss" data-mode="later" href="javascript:void(0)">May be later</a>
                        <span class="dashicons dashicons-hidden"></span>
                        <a class="xl-notice-dismiss" data-mode="dismiss" title="Dismiss forever" href="javascript:void(0)">No, thanks</a></p>
                    </div>
                </div>
            </div>
            <span class="dashicons dashicons-megaphone"></span>
        </div>
		<?php
	}

	protected function repo_template( $plugin_slug, $plugin_short_slug, $plugin_name, $plugin_url, $heading, $sub_heading, $image ) {
		?>
        <div class="updated" id="xl_notice_type_3" data-plugin="<?php echo $plugin_short_slug ?>" data-plugin-slug="<?php echo $plugin_slug; ?>">
            <div class="xl_upsell_area">
                <div class="upsell_left_abs">
                    <img src="<?php echo $image; ?>" alt="<?php echo $plugin_name ?>">
                </div>
                <div class="upsell_main_abs">
                    <h3><?php echo $heading ?></h3>
                    <p><?php echo $sub_heading ?></p>
                </div>
                <div class="upsell_right_abs">
                    <div id="plugin-filter" class="upsell_xl_plugin_btn plugin-card plugin-card-<?php echo $plugin_slug; ?>">
                        <a class="button-primary install-now button" data-slug="<?php echo $plugin_slug; ?>" href="<?php echo $plugin_url; ?>" aria-label="Install <?php echo $plugin_name ?>" data-name="Install <?php echo $plugin_name ?>">Try
                            Free Version</a>
                        <span class="dashicons dashicons-calendar"></span>
                        <a class="xl-notice-dismiss" data-mode="later" href="javascript:void(0)">May be later</a>
                        <span class="dashicons dashicons-hidden"></span>
                        <a class="xl-notice-dismiss" data-mode="dismiss" title="Dismiss forever" href="javascript:void(0)">No, thanks</a>
                    </div>
                </div>
            </div>
            <span class="dashicons dashicons-megaphone"></span>
        </div>
		<?php
	}

	/**
	 * NextMove upsell notice html
	 *
	 * @return false|string
	 */
	protected function nextmove_notice_html() {
		$plugin_name = $this->name;
		$plugin_url  = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'notice',
			'utm_campaign' => 'halloween-' . $this->year,
			'utm_term'     => 'sale',
		), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
		$heading     = "Say good bye to templated & lousy Thank You pages. Hack your growth with NextMove.";
		$sub_heading = "Use NextMove to create profit-pulling Thank You pages with plug & play components and watch your repeats orders explode.";
		$image       = plugin_dir_url( $this->plugin_path ) . 'admin/assets/img/nextmove.png';

		$notice_slug = 'nextmove';

		ob_start();
		$this->external_template( $notice_slug, $plugin_name, $plugin_url, $heading, $sub_heading, $image );

		return ob_get_clean();
	}

	/**
	 * Finale upsell notice html
	 *
	 * @return false|string
	 */
	protected function finale_notice_html() {
		$plugin_slug       = 'finale-woocommerce-sales-countdown-timer-discount';
		$plugin_short_slug = 'finale';
		$plugin_name       = 'WooCommerce Sales Countdown Timer & Discounts – Finale Lite';
		$plugin_url        = wp_nonce_url( add_query_arg( array(
			'action' => 'install-plugin',
			'plugin' => $plugin_slug,
			'from'   => 'import',
		), self_admin_url( 'update.php' ) ), 'install-plugin_' . $plugin_slug );
		$heading           = 'Set up profit-pulling promotions in your WooCommerce Store this season.';
		$sub_heading       = 'Finale helps store owners run seasonal offers, flash sales, deals of the day & festive offers to boost conversions.';
		$image             = plugin_dir_url( $this->plugin_path ) . 'admin/assets/img/finale.png';

		ob_start();
		$this->repo_template( $plugin_slug, $plugin_short_slug, $plugin_name, $plugin_url, $heading, $sub_heading, $image );

		return ob_get_clean();
	}

	/**
	 * Autonami upsell notice html
	 *
	 * @return false|string
	 */
	protected function autonami_notice_html() {
		$plugin_slug       = 'wp-marketing-automations';
		$plugin_short_slug = 'autonami';
		$plugin_name       = 'FunnelKit Marketing Automations For WordPress';
		$plugin_url        = wp_nonce_url( add_query_arg( array(
			'action' => 'install-plugin',
			'plugin' => $plugin_slug,
			'from'   => 'import',
		), self_admin_url( 'update.php' ) ), 'install-plugin_' . $plugin_slug );
		$heading           = 'Just launched: Now put your marketing and store management on autopilot.';
		$sub_heading       = 'Use FunnelKit Marketing to set up cart abandonment, post-purchase, win-back campaigns & more. Up-level your email and SMS marketing game.';
		$image             = plugin_dir_url( $this->plugin_path ) . 'admin/assets/img/autonami.png';

		ob_start();
		$this->repo_template( $plugin_slug, $plugin_short_slug, $plugin_name, $plugin_url, $heading, $sub_heading, $image );

		return ob_get_clean();
	}

	/**
	 * Halloween Sale notice html
	 *
	 * @return false|string
	 */
	protected function halloween_sale_notice_html() {
		$plugin_name = $this->name;
		$plugin_url  = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'notice',
			'utm_campaign' => 'halloween-' . $this->year,
			'utm_term'     => 'sale',
		), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
		$heading     = "Upgrade to NEXTMOVE PRO and Save 20%! Use coupon 'XLHALLOWEEN'";
		$sub_heading = "This <em>one-time</em> spooky deal ends on <em>November 2nd</em> 12 AM EST. 'Act Fast!'";
		$image       = plugin_dir_url( $this->plugin_path ) . 'admin/assets/img/halloween.jpg';

		$notice_slug = 'halloween_sale_' . $this->year;

		ob_start();
		$this->external_template( $notice_slug, $plugin_name, $plugin_url, $heading, $sub_heading, $image );

		return ob_get_clean();
	}

	/**
	 * Pre Black Friday Sale notice html
	 *
	 * @return false|string
	 */
	protected function pre_black_friday_notice_html() {
		$plugin_name = $this->name;
		$plugin_link = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'notice',
			'utm_campaign' => 'pre-black-friday-' . $this->year,
			'utm_term'     => 'sale',
		), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
		$heading     = 'Prepare your store for Black Friday Sale. Upgrade to NEXTMOVE PRO and Save 20%!';
		$sub_heading = "Use coupon 'XLPREBFCM'. Deal expires on <em>November 23th</em> 12 AM EST. 'Act Fast!'";
		$image       = plugin_dir_url( $this->plugin_path ) . 'admin/assets/img/black-friday.jpg';

		$notice_slug = 'pre_black_friday_' . $this->year;

		ob_start();
		$this->external_template( $notice_slug, $plugin_name, $plugin_link, $heading, $sub_heading, $image );

		return ob_get_clean();
	}

	/**
	 * Black Friday Cyber Monday Sale notice html
	 *
	 * @return false|string
	 */
	protected function bfcm_notice_html() {
		$plugin_name = $this->name;
		$plugin_link = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'notice',
			'utm_campaign' => 'bfcm_' . $this->year,
			'utm_term'     => 'sale',
		), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
		$heading     = "Upgrade to NEXTMOVE PRO and Save 30%! Use coupon 'XLBFCM'";
		$sub_heading = "Get a super high 30% off on our plugins. Act fast! Deal expires on <em>November 30th</em> 12 AM EST.";
		$image       = plugin_dir_url( $this->plugin_path ) . 'admin/assets/img/black-friday.jpg';

		$notice_slug = 'bfcm_' . $this->year;

		ob_start();
		$this->external_template( $notice_slug, $plugin_name, $plugin_link, $heading, $sub_heading, $image );

		return ob_get_clean();
	}

	/**
	 * Christmas New Year Sale notice html
	 *
	 * @return false|string
	 */
	protected function christmas_notice_html() {
		$plugin_name = $this->name;
		$plugin_link = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'notice',
			'utm_campaign' => 'christmas-' . $this->year,
			'utm_term'     => 'sale',
		), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
		$heading     = "Upgrade to NEXTMOVE PRO and Save 25%! Use coupon 'XLCHRISTMAS'";
		$sub_heading = "Get a super high 25% off on our plugins. Act fast! Deal expires on <em>January 2nd</em> 12 AM EST.";
		$image       = plugin_dir_url( $this->plugin_path ) . 'admin/assets/img/christmas.jpg';

		$notice_slug = 'christmas_' . $this->year;

		ob_start();
		$this->external_template( $notice_slug, $plugin_name, $plugin_link, $heading, $sub_heading, $image );

		return ob_get_clean();
	}


	/**
	 * Set custom transient as native transient sometimes don't save when cache plugins active
	 *
	 * @param $key
	 * @param $value
	 * @param $expiration
	 */
	public function set_xl_transient( $key, $value, $expiration ) {
		$array = array(
			'time'  => time() + (int) $expiration,
			'value' => $value,
		);
		update_option( '_xl_transient_' . $key, $array, false );
	}

	/**
	 * get custom transient value
	 *
	 * @param $key
	 *
	 * @return boolean
	 */
	public function get_xl_transient( $key ) {
		$data = get_option( '_xl_transient_' . $key, false );
		if ( false === $data ) {
			return false;
		}
		$current_time = time();
		if ( is_array( $data ) && isset( $data['time'] ) ) {
			if ( $current_time > (int) $data['time'] ) {
				delete_option( '_xl_transient_' . $key );

				return false;
			} else {
				return $data['value'];
			}
		}

		return false;
	}
}

XLWCTY_Upsell::get_instance();
