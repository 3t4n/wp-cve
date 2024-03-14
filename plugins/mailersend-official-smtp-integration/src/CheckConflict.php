<?php

namespace MailerSend;

class CheckConflict {

	// true if conflicts are found
	private static $has_conflicts = false;

	// init status
	private static $init_check = false;

	// array of smtp plugins
	private static $plugins = [
		[
			'name' => 'Easy WP SMTP',
			'path' => 'easy-wp-smtp/easy-wp-smtp.php'
		],
		[
			'name' => 'WP Mail SMTP',
			'path' => 'wp-mail-smtp/wp_mail_smtp.php'
		],
		[
			'name' => 'Mail Bank',
			'path' => 'wp-mail-bank/wp-mail-bank.php'
		],
		[
			'name' => 'Post SMTP Mailer/Email Log',
			'path' => 'postman-smtp/postman-smtp.php'
		],
		[
			'name' => 'FluentSMTP',
			'path' => 'fluent-smtp/fluent-smtp.php'
		],
		[
			'name' => 'SMTP Mailer',
			'path' => 'smtp-mailer/main.php'
		],
		[
			'name' => 'WP SMTP',
			'path' => 'wp-smtp/wp-smtp.php'
		],
		[
			'name' => 'SAR Friendly SMTP',
			'path' => 'sar-friendly-smtp/sar-friendly-smtp.php'
		],
		[
			'name' => 'SMTP Mail',
			'path' => 'smtp-mail/index.php'
		],
		[
			'name' => 'WP Mail Smtp - SMTP7',
			'path' => 'wp-mail-smtp-mailer/wp-mail-smtp-mailer.php'
		],
		[
			'name' => 'SMTP by BestWebSoft',
			'path' => 'bws-smtp/bws-smtp.php'
		],
		[
			'name' => 'Contact Form & SMTP Plugin for WordPress by PirateForms',
			'path' => 'pirate-forms/pirate-forms.php'
		],
		[
			'name' => 'YaySMTP – Simple WP SMTP Mail',
			'path' => 'yay-smtp/yay-smtp.php'
		],
	];

	// array of conflicting plugin names
	private static $conflicts = [];

	private function __construct() {
		//nothing
	}

	/**
	 * Initialize class
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function init() {

		if ( ! self::$init_check ) {

			self::$init_check = true;

			foreach ( self::$plugins as $plugin ) {

				if ( is_plugin_active( $plugin['path'] ) ) {

					self::$conflicts[] = $plugin['name'];
				}
			}

			if ( count( self::$conflicts ) > 0 ) {
				self::$has_conflicts = true;
			}
		}
	}

	/**
	 * Returns status of plugin conflicts
	 *
	 * @access      public
	 * @return      boolean
	 * @since       1.0.0
	 */
	public static function hasConflict(): bool {

		CheckConflict::init();

		return self::$has_conflicts;
	}

	/**
	 * Warning Notice of conflicting plugins
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function viewNotice() {

		CheckConflict::init();

		?>
        <div class="notice notice-warning mailersend-conflict-found-notice">
            <p>
				<?php echo wp_kses_post( self::conflictMessage() ); ?>
            </p>

			<?php if ( get_current_screen()->id != 'plugins' ) : ?>
                <p>
					<?php _e( '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">Deactivate</a>' ) ?>
                </p>
			<?php endif; ?>
        </div>
		<?php
	}

	/**
	 * Warning message of conflicting plugins
	 *
	 * @access      private
	 * @return      string
	 * @since       1.0.0
	 */
	private static function conflictMessage(): string {

		return sprintf(
			esc_html__( 'Heads up! MailerSend has detected the following ' . _n( 'plugin', 'plugins', count( self::$conflicts ), 'mailersend-official-smtp-integration' )
			            . ' ' . _n( 'is', 'are', count( self::$conflicts ), 'mailersend-official-smtp-integration' )
			            . ' activated: %1$s. Please deactivate ' . _n( 'it', 'them', count( self::$conflicts ), 'mailersend-official-smtp-integration' )
			            . ' to prevent conflicts.', 'mailersend-official-smtp-integration' ),
			implode( ', ', self::$conflicts )
		);
	}
}
