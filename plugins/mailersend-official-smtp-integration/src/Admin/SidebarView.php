<?php

namespace MailerSend\Admin;

class SidebarView {

	public function __construct() {

		$this->getView();
	}

	private function getView() {
		?>
        <div id="side-info-column" class="mailersend-sidebar">
            <h2><?php _e( 'Need help?', 'mailersend-official-smtp-integration' ); ?></h2>

            <div class="sidebar_infolink">
                <h3>
                    <a href="<?php echo esc_url( 'https://www.mailersend.com/contact-us' ); ?>" target="_blank">
						<?php esc_html_e( 'Contact 24/7 customer support', 'mailersend-official-smtp-integration' ); ?>
                    </a>
                </h3>

                <h3>
                    <a href="<?php echo esc_url( 'https://mailersend.com/help/how-to-integrate-mailersend-with-woocommerce' ); ?>"
                       target="_blank">
						<?php esc_html_e( 'How to send transactional email with SMTP', 'mailersend-official-smtp-integration' ); ?>
                    </a>
                </h3>

                <h3>
                    <a href="<?php echo esc_url( 'https://app.mailersend.com/billing/choose' ); ?>" target="_blank">
						<?php esc_html_e( 'Get Premium for multiple domains and more!', 'mailersend-official-smtp-integration' ); ?>
                    </a>
                </h3>

                <h3>
                    <a href="<?php echo esc_url( 'https://www.mailersend.com/about-us' ); ?>" target="_blank">
						<?php esc_html_e( 'About MailerSend', 'mailersend-official-smtp-integration' ); ?>
                    </a>
                </h3>

                <h3>
                    <a href="<?php echo esc_url( 'https://www.capterra.com/p/214665/MailerSend/' ); ?>" target="_blank">
						<?php esc_html_e( '❤️ this plugin? Give us a 5-star review!', 'mailersend-official-smtp-integration' ); ?>
                    </a>
                </h3>
            </div>
        </div>

		<?php
	}
}
