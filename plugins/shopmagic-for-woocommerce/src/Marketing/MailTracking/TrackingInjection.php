<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

use WPDesk\ShopMagic\Components\Mailer\Email;
use WPDesk\ShopMagic\Components\UrlGenerator\FrontendUrlGenerator;

class TrackingInjection {
	private const HTML_LINK_PATTERN = '/(<a[^>]*href=["\'])([^"\']*)/';
	/** @var FrontendUrlGenerator */
	private $url_generator;

	public function __construct( FrontendUrlGenerator $url_generator ) {
		$this->url_generator = $url_generator;
	}

	public function inject_tracking_pixel( TrackedEmail $tracked_email, Email $email ): Email {
		// Append the tracking url
		$tracking_pixel = '<img border=0 width=1 alt="" height=1 src="' . add_query_arg(
				[ 'c' => $tracked_email->get_message_id() ],
				$this->url_generator->generate( 'track/sm-open' )
			) . '" />';

		$linebreak = uniqid(); // Hack to keep linebreaks untouched during modifications
		$email     = $email->message( str_replace( "\n", $linebreak, $email->message ) );

		if ( preg_match( '/^(.*<body[^>]*>)(.*)$/', $email->message, $matches ) ) {
			$email = $email->message( $matches[1] . $tracking_pixel . $matches[2] );
		} else {
			$email = $email->message( $email->message . $tracking_pixel );
		}
		$email = $email->message( str_replace( $linebreak, "\n", $email->message ) );

		return $email;
	}

	public function inject_link_tracker( TrackedEmail $tracked_email, Email $email ): Email {
		return $email->message( preg_replace_callback(
			self::HTML_LINK_PATTERN,
			function ( $matches ) use ( $tracked_email ) {
				[ $original, $html, $uri ] = $matches;

				if ( empty( $uri ) ) {
					return $original;
				}

				$uri = $this->validate_uri( $uri );

				if ( is_null( $uri ) ) {
					return $original;
				}

				$rawurlencode = rawurlencode( $uri );

				return $html . add_query_arg(
						[
							'l' => $rawurlencode,
							'c' => $tracked_email->get_message_id(),
						],
						$this->url_generator->generate( 'track/sm-click' )
					);
			},
			$email->message
		) );
	}

	private function validate_uri( string $uri ): ?string {
		$scheme = parse_url( $uri, PHP_URL_SCHEME );
		if ( is_null( $scheme ) || in_array( $scheme, [ 'https', 'http' ], true ) ) {
			return str_replace( '&amp;', '&', $uri );
		}

		return null;
	}
}
