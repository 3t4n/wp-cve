<?php
/**
 * The file that defines the Admin Notices
 *
 * @link       www.faboba.com
 * @since      1.3
 *
 * @package    Falang for Elementor Lite
 */
namespace Falang\Elementor\Admin;

use Falang\Core\Button;

class Admin_Notices {

	/**
	 * Stores notices.
	 * each notice need to have a notice_[notice_name] callback function
	 * @var array
	 */
	private static $notices = array('rate_us_feedback');
    const ADMIN_NOTICES_NAME        = 'falang_for_elementor_lite';
	const ADMIN_NOTICES_KEY         = 'falang_for_elementor_lite_dismissed_notices';
    const ADMIN_NOTICES_KEY_DATE    = 'falang_for_elementor_lite_dismissed_notices_date';//last notices dismissed date
    const ADMIN_NOTICES_INSTALLED_TIME  = 'falang_for_elementor_lite_installed_time';//store the time the notice was installed
    const ADMIN_NOTICES_TIME        = 7*24*60*60;//4*60 ;// 7*24*60*60;1 week between each notices

	private $install_time = null;

	private $current_screen_id = null;


	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Get custom notices
	 *
	 * @since 1.3
	 *
	 * @return array
	 */
	public static function get_notices() {
		return self::$notices;
	}

	/**
	 * Get install time.
	 *
	 * Retrieve the time when Falang for Elementor lite was installed.
	 *
	 * @since 1.3
	 * @access public
	 * @static
	 *
	 * @return int Unix timestamp when Falang for Elementor was installed.
	 */
	public function get_install_time() {
		$installed_time = get_option( self::ADMIN_NOTICES_INSTALLED_TIME );

		if ( ! $installed_time ) {
			$installed_time = time();

			update_option( self::ADMIN_NOTICES_INSTALLED_TIME, $installed_time );
		}

		return $installed_time;
	}

	/**
	 * Has a notice been dismissed?
	 *
	 * @since 1.3
	 *
	 * @param string $notice Notice name
	 * @return bool
	 */
	public static function is_dismissed( $notice ) {
		$dismissed = get_option( self::ADMIN_NOTICES_KEY, array() );

		return in_array( $notice, $dismissed );
	}

	/**
	 * Displays notices
	 *
	 * @since 1.3
	 */
	public function admin_notices() {
		$this->install_time = $this->get_install_time();
		$this->current_screen_id = get_current_screen()->id;

		if ( current_user_can( 'manage_options' ) ) {


			//Core notices are always displayed

            //upgrade notice is displayed only when necessary return true)
            //on false try to display other notice
			if ($this->can_display_notice( 'first_activation' ) && ! $this->is_dismissed( 'first_activation' )  ) {
                if ($this->notice_upgrade_plugin()){
                    return;
                }
			}

            //upgrade plugin can't be done with a is_dismissed because it should be be displayed several time
            if ($this->can_display_notice( 'upgrade_plugin' ) && $this->is_time('upgrade_plugin') ) {
                if ($this->notice_upgrade_plugin()){
                    return;
                }
            }

			// Custom notices are displayed with a time space
            // no test on dismissed to allow loop on custom notices
			foreach ( $this->get_notices() as $notice ) {
                if (apply_filters('falang_for_elementor_lite_notices',$notice))
                    if ($this->can_display_notice($notice) && $this->is_time($notice)  && !$this->is_dismissed($notice)) {
                        $method_callback = "notice_{$notice}";
                        if ($this->$method_callback()) {
                            return;
                        }
                    }
			}

			//all notices are displayed we can now reset it after the last execution time
            //and the time is done
            if ($this->can_display_notice($notice) && $this->is_time($notice) && $this->all_notices_displayed()){
                $this->reset_dismissed_notice();
            }

		}
	}

    /**
     * Should we display notices on this screen?
     *
     * @since 1.3
     *
     * @param  string $notice The notice name.
     * @return bool
     */
	protected function is_time($notice ){
        $last_notice_date = get_option( self::ADMIN_NOTICES_KEY_DATE,0 );
        $time = time();
        if ($time > $last_notice_date+ self::ADMIN_NOTICES_TIME){
            return true;
        } else {
            return false;
        }
    }

	/**
	 * Should we display notices on this screen?
	 *
	 * @since 1.3
	 *
	 * @param  string $notice The notice name.
	 * @return bool
	 */
	protected function can_display_notice( $notice ) {
		$screen          = get_current_screen();
		$screen_id       = $screen ? $screen->id : '';
		$show_on_screens = array(
			'dashboard',
			'plugins',
		);

		/**
		 * Filter admin notices which can be displayed
		 * Notices should only show on Falang screens, the main dashboard, and on the plugins screen.
		 *
		 * @since 1.3
		 *
		 * @param bool   $display Whether the notice should be displayed or not.
		 * @param string $notice  The notice name.
		 */
		return apply_filters(
			'falang_for_elementor_can_display_notice',
			in_array(
				$screen->id,
				array(
					'dashboard',
					'plugins',
					'toplevel_page_falang-translation',
					'falang_page_falang-terms',
					'falang_page_falang-menus',
					'falang_page_falang-strings',
					'falang_page_falang-options',
					'falang_page_falang-language',
					'falang_page_falang-settings',
					'falang_page_falang-help'
				)
			),
			$notice
		);
	}

    /**
     * reset dissmissed notices when all notices are alreasy show
     *
     * @since 1.5
     *
     */
    public function reset_dismissed_notice(){
        $dismissed[] = 'first_activation';
        update_option( self::ADMIN_NOTICES_KEY,$dismissed);//set the last dismissed date

        $dismissed_time = time();
        update_option( self::ADMIN_NOTICES_KEY_DATE,$dismissed_time);//set the last dismissed date

    }

    /**
     * check if all notices are displayed at least 1 time
     *
     * @since 1.3
     *
     */
    public function all_notices_displayed(){
        $dismissed = get_option( self::ADMIN_NOTICES_KEY, array() );

        $containsAllValues = !array_diff(self::get_notices(), $dismissed);
        return $containsAllValues;
    }

    /**
     * Render html attributes
     *
     * @since 1.3
     *
     * @access public
     * @static
     * @param array $attributes
     *
     * @return string
     */
    public static function render_html_attributes( array $attributes ) {
        $rendered_attributes = [];

        foreach ( $attributes as $attribute_key => $attribute_values ) {
            if ( is_array( $attribute_values ) ) {
                $attribute_values = implode( ' ', $attribute_values );
            }

            $rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
        }

        return implode( ' ', $rendered_attributes );
    }

	public function print_admin_notice( array $options ) {
		$default_options = [
			'id' => null,
			'title' => '',
			'description' => '',
			'classes' => [ 'notice', 'falang-notice' ], // We include WP's default notice class so it will be properly handled by WP's js handler
			'type' => '',
			'dismissible' => true,
			'icon' => 'icon-falang-for-elementor',
			'button' => [],
			'button_secondary' => [],
		];

		$options = array_replace_recursive( $default_options, $options );

		$notice_classes = $options['classes'];
		$dismiss_button = '';
		$icon = '';

		if ( $options['type'] ) {
			$notice_classes[] = 'falang-notice--' . $options['type'];
		}

		if ( $options['dismissible'] ) {
			$label = esc_html__( 'Dismiss', 'falang-for-elementor-lite' );
			$notice_classes[] = 'falang-notice--dismissible';
			$dismiss_button = '<i class="falang-notice__dismiss" role="button" aria-label="' . $label . '" tabindex="0">'.$label.'</i>';
		}

		if ( $options['icon'] ) {
			$notice_classes[] = 'falang-notice--extended';
			$icon = '<div class="falang-notice__icon-wrapper"><i class="' . esc_attr( $options['icon'] ) . '" aria-hidden="true"></i></div>';
		}

		$wrapper_attributes = [
		    'data-plugin_id' => self::ADMIN_NOTICES_NAME,
			'class' => $notice_classes,
		];

		if ( $options['id'] ) {
			$wrapper_attributes['data-notice_id'] = $options['id'];
		}
		?>
		<div <?php echo self::render_html_attributes( $wrapper_attributes ); ?>>
			<?php echo $dismiss_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="falang-notice__aside">
				<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="falang-notice__content">
				<?php if ( $options['title'] ) { ?>
					<h3><?php echo wp_kses_post( $options['title'] ); ?></h3>
				<?php } ?>

				<?php if ( $options['description'] ) { ?>
					<p><?php echo wp_kses_post( $options['description'] ); ?></p>
				<?php } ?>

				<?php if ( ! empty( $options['button']['text'] ) || ! empty( $options['button_secondary']['text'] ) ) { ?>
					<div class="falang-notice__actions">
						<?php
						foreach ( [ $options['button'], $options['button_secondary'] ] as $index => $button_settings ) {
							if ( empty( $button_settings['variant'] ) && $index ) {
								$button_settings['variant'] = 'outline';
							}

							if ( empty( $button_settings['text'] ) ) {
								continue;
							}

							$button = new Button( $button_settings );
							$button->print_button();
						} ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
	}

    /**
     * First activation notice
     *
     * @since 1.3
    */
    private function notice_first_activation() {
        $notice_id = 'first_activation';

        if ( ! current_user_can( 'manage_options' ) ) {
            return false;
        }

        $options = [
            'title' => esc_html__( 'Thanks for installing Falang for Elementor Lite!', 'falang-for-elementor-lite' ),
            'description' => __( 'Enjoying the experience with Falang For Elementor? Please take a moment to spread your love by rating us on <a target="_blank" href="https://wordpress.org/plugins/falang-for-elementor-lite/#reviews">WordPress.org!</a>', 'falang-for-elementor-lite' ),
            'id' => $notice_id,
            'button' => [
                'text' => esc_html__( 'Documentation', 'falang-for-elementor-lite'),
                'url' => 'https://www.faboba.com/en/wordpress/falang-for-elementor/documentation.html',
                'new_tab' => true,
                'type' => 'cta',
            ],
            'button_secondary' => [
                'text' => esc_html__( 'Do you have question ?', 'falang-for-elementor-lite' ),
                'url' => 'https://www.faboba.com/falangw/contact/',
                'new_tab' => true,
                'icon' => 'dashicons dashicons-edit',
                'type' => '',
            ],
        ];

        $this->print_admin_notice( $options );

        return true;
    }

    /**
     * Rate us notice
     *
     * @since 1.3
    */
    private function notice_rate_us_feedback() {
		$notice_id = 'rate_us_feedback';

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$options = [
			'title' => esc_html__( 'Thanks for using Falang for Elementor Lite!', 'falang-for-elementor-lite' ),
			'description' => esc_html__( 'Enjoying the experience with Falang For Elementor? Please take a moment to spread your love by rating us on WordPress.org!', 'falang-for-elementor-lite' ),
			'id' => $notice_id,
			'button' => [
				'text' => esc_html__( 'Happy To Help', 'falang-for-elementor-lite'),
				'url' => 'https://wordpress.org/plugins/falang-for-elementor-lite/',
				'new_tab' => true,
				'type' => 'cta',
			],
			'button_secondary' => [
				'text' => esc_html__( 'Do you have question ?', 'falang-for-elementor-lite' ),
				'url' => 'https://www.faboba.com/falangw/contact/',
				'new_tab' => true,
                'icon' => 'dashicons dashicons-edit',
				'type' => '',
			],
		];

		$this->print_admin_notice( $options );

		return true;
	}

    /*
     * Notices to upgrade 1
     * @since 1.3
     *
     */
    private function notice_upgrade_plugin() {

        if ( ! current_user_can( 'update_plugins' ) ) {
            return false;
        }

        // Check if have any upgrades.
        $update_plugins = get_site_transient( 'update_plugins' );

        $has_remote_update_package = ! ( empty( $update_plugins ) || empty( $update_plugins->response[ FALANG_ELEMENTOR_LITE_PLUGIN_BASE ] ) || empty( $update_plugins->response[ FALANG_ELEMENTOR_LITE_PLUGIN_BASE ]->package ) );

        if ( ! $has_remote_update_package && empty( $upgrade_notice['update_link'] ) ) {
            return false;
        }

        if ( $has_remote_update_package ) {
            $product = $update_plugins->response[ FALANG_ELEMENTOR_LITE_PLUGIN_BASE ];

            $details_url = self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $product->slug . '&section=changelog&TB_iframe=true&width=600&height=800' );
            $upgrade_url = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' . FALANG_ELEMENTOR_LITE_PLUGIN_BASE ), 'upgrade-plugin_' . FALANG_ELEMENTOR_LITE_PLUGIN_BASE );
            $new_version = $product->new_version;
        } else {
            $upgrade_url = $upgrade_notice['update_link'];
            $details_url = $upgrade_url;

            $new_version = $upgrade_notice['version'];
        }

        // Check if have upgrade notices to show.
        if ( version_compare( FALANG_ELEMENTOR_LITE_PLUGIN_BASE, $upgrade_notice['version'], '>=' ) ) {
//            return false;
        }

        $notice_id = 'upgrade_notice_' . $upgrade_notice['version'];

        $message = sprintf(
        /* translators: 1: Details URL, 2: Accessibility text, 3: Version number, 4: Update URL, 5: Accessibility text */
            __( 'There is a new version of Falang for Elementor Lite available. <a href="%1$s" class="thickbox open-plugin-details-modal" aria-label="%2$s">View version %3$s details</a> or <a href="%4$s" class="update-link" aria-label="%5$s">update now</a>.', 'falang-for-elementor-lite' ),
            esc_url( $details_url ),
            esc_attr( sprintf(
            /* translators: %s:  Elementor Lite version */
                __( 'View Falang for Elementor Lite version %s details', 'falang-for-elementor-lite' ),
                $new_version
            ) ),
            $new_version,
            esc_url( $upgrade_url ),
            esc_attr( esc_html__( 'Update Falang for Elementor Lite Now', 'falang-for-elementor-lite' ) )
        );

        $options = [
            'title' => esc_html__( 'Update Notification', 'falang-for-elementor-lite' ),
            'description' => $message,
            'button' => [
                'icon_classes' => 'dashicons dashicons-update',
                'text' => esc_html__( 'Update Now', 'falang-for-elementor-lite' ),
                'url' => $upgrade_url,
            ],
            'id' => $notice_id,
        ];

        $this->print_admin_notice( $options );

        return true;
    }
}