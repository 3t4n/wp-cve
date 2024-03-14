<?php
		/**
		 * Directory to access.
		 *
		 * @var string
		 */
		$current_dir = dirname(__DIR__);


		/**
		 * Directory to access.
		 *
		 * @var string
		 */
		$email = urlencode('support@wppool.dev');

?>
	<div class="ssgsw-support-popup-overlay" @click="togglePopup"></div>
	<div class="ssgsw-support-popup" :class="{'show': popupVisible }">
			<span class="support-cross-button" @click="togglePopup">&times;</span>
			<div class="wrapper">
				<img src="<?php echo esc_url(plugins_url('public/images/support/wppool.svg', $current_dir)); ?>" alt="">
				<p><?php esc_html_e('Choose how you want to connect to us. Select the option convenient for you','stock-sync-with-google-sheet-for-woocommerce'); ?></p>
				<?php
				printf('<a href="https://mail.google.com/mail/?view=cm&to=%s" target="_blank">',esc_attr($email));
				?>
					<div class="cards">
						<div class="support-card">
							<img src="<?php echo esc_url(plugins_url('public/images/support/gmail.svg', $current_dir)); ?>" alt="">
							<div class="content">
							<p><?php esc_html_e('Gmail','stock-sync-with-google-sheet-for-woocommerce'); ?></p>
								<span><?php esc_html_e('Open Gmail in browser','stock-sync-with-google-sheet-for-woocommerce'); ?></span>
							</div>
						</div>
					</div>
				</a>
				<?php
				printf('<a href="mailto:?to=%s" target="_blank">',esc_attr($email));
				?>
					<div class="cards">
						<div class="support-card">
							<img src="<?php echo esc_url(plugins_url('public/images/support/hotmail.svg', $current_dir)); ?>" alt="">
							<div class="content">
							<p><?php esc_html_e('Outlook','stock-sync-with-google-sheet-for-woocommerce'); ?></p>
								<span><?php esc_html_e('Open Outlook in browser','stock-sync-with-google-sheet-for-woocommerce'); ?></span>
							</div>
						</div>
					</div>
				</a>
				<?php
				printf('<a href="https://compose.mail.yahoo.com/?to=%s&subject=&body=" target="_blank">',esc_attr($email));
				?>
				 
					<div class="cards">
						<div class="support-card">
							<img src="<?php echo esc_url(plugins_url('public/images/support/yahoo.svg', $current_dir)); ?>" alt="">
							<div class="content">
							<p><?php esc_html_e('Yahoo','stock-sync-with-google-sheet-for-woocommerce'); ?></p>
								<span><?php esc_html_e('Open Yahoo in browser','stock-sync-with-google-sheet-for-woocommerce'); ?></span>
							</div>
						</div>
					</div>
				</a> 
				<?php
				printf('<a href="mailto:%s" target="_blank">', esc_attr($email));
				?>
					<div class="cards">
						<div class="support-card">
							<img src="<?php echo esc_url(plugins_url('public/images/support/mail.svg', $current_dir)); ?>" alt="">
							<div class="content">
							<p><?php esc_html_e('Default Email App','stock-sync-with-google-sheet-for-woocommerce'); ?></p>
								<span><?php esc_html_e('Open your default email app','stock-sync-with-google-sheet-for-woocommerce'); ?></span>
							</div>
						</div>
					</div>
				</a>
				<a href="javascript;" @click.prevent="copyEmail">
					<div class="cards">
						<div class="support-card last">
							<img src="<?php echo esc_url(plugins_url('public/images/support/support-mail.svg', $current_dir)); ?>" alt="">
							<div class="content" style="min-width:265px;">
								<p><?php esc_html_e('support@wppool.dev','stock-sync-with-google-sheet-for-woocommerce'); ?></p>
								<span><?php esc_html_e('Copy email address to your clipboard','stock-sync-with-google-sheet-for-woocommerce'); ?></span>
							</div>
							<button class="support-button" x-text="copied ? 'Copied!' : 'Copy Email'" data-email="support@wppool.dev" @click.prevent="copyEmail">Copy Email</button>
						</div>
					</div>
				</a>

				<div class="wppool-brand">
				<p>
				<?php esc_html_e('Powered by','stock-sync-with-google-sheet-for-woocommerce'); ?> <a href="<?php echo esc_url('https://wppool.dev'); ?>" target="_blank"><?php esc_html_e('WPPOOL','stock-sync-with-google-sheet-for-woocommerce'); ?></a></p>
				</div>
		</div>
	</div>
