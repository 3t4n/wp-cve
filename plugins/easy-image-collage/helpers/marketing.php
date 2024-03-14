<?php

class EIC_Marketing {

    private $campaign = false;

    public function __construct()
    {
        $campaigns = array(
			'birthday-2022' => array(
				'start' => new DateTime( '2022-01-12 10:00:00', new DateTimeZone( 'Europe/Brussels' ) ),
				'end' => new DateTime( '2022-01-31 10:00:00', new DateTimeZone( 'Europe/Brussels' ) ),
				'notice_title' => 'Celebrating my birthday',
				'notice_text' => 'Get a 30% discount right now!',
				'page_title' => 'Birthday Discount!',
				'page_text' => 'Good news: I\'m celebrating my birthday with a <strong>30% discount on any of our plugins</strong>. Just use this code on the checkout page: <em>BDAY2022</em>',
				'url' => 'https://bootstrapped.ventures/birthday-discount/',
			),
			'black-friday-2022' => array(
				'start' => new DateTime( '2021-11-23 10:00:00', new DateTimeZone( 'Europe/Brussels' ) ),
				'end' => new DateTime( '2021-11-30 10:00:00', new DateTimeZone( 'Europe/Brussels' ) ),
				'notice_title' => 'Black Friday & Cyber Monday Deal',
				'notice_text' => 'Get a 30% discount right now!',
				'page_title' => 'Black Friday Discount!',
				'page_text' => 'Good news: we\'re having a Black Friday & Cyber Monday sale and you can get a <strong>30% discount on any of our plugins</strong>. Just use this code on the checkout page: <em>BF2022</em>',
				'url' => 'https://bootstrapped.ventures/black-friday/',
			),
			'birthday-2023' => array(
				'start' => new DateTime( '2023-01-24 10:00:00', new DateTimeZone( 'Europe/Brussels' ) ),
				'end' => new DateTime( '2023-01-31 10:00:00', new DateTimeZone( 'Europe/Brussels' ) ),
				'notice_title' => 'Celebrating my birthday',
				'notice_text' => 'Get a 30% discount right now!',
				'page_title' => 'Birthday Discount!',
				'page_text' => 'Good news: I\'m celebrating my birthday with a <strong>30% discount on any of our plugins</strong>. Just use this code on the checkout page: <em>BDAY2023</em>',
				'url' => 'https://bootstrapped.ventures/birthday-discount/',
			),
		);

		$now = new DateTime();

		foreach ( $campaigns as $id => $campaign ) {
			if ( $campaign['start'] < $now && $now < $campaign['end'] ) {
				$campaign['id'] = $id;
				$this->campaign = $campaign;
				break;
			}
		}

		if ( false !== $this->campaign ) {
            add_action( 'eic_modal_notices', array( $this, 'marketing_notice' ) );
        }
    }

    public function marketing_notice()
    {
        if ( ! EasyImageCollage::is_premium_active() ) {
            $url = $this->campaign['url'] . '?utm_source=eic&utm_medium=plugin&utm_campaign=' . urlencode( $this->campaign['id'] );

            echo '<div style="border: 1px solid darkgreen; padding: 5px; margin-bottom: 5px; background-color:rgba(0,255,0,0.15);">';
            echo '<strong>' . $this->campaign['notice_title'] . '</strong><br/>';
            echo $this->campaign['page_text'] . '<br/><br/>';
            echo '<a href="' . $url . '" target="_blank">'  . $this->campaign['notice_text'] .  '</a>';
            echo '</div>';
        }
    }
}