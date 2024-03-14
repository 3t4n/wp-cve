<?php

namespace MyCustomizer\WooCommerce\Connector\Factory;

//use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validation;

define( 'MCZR_VIEWS_DIR', realpath( __DIR__ . '/../views' ) );
define( 'MCZR_PLUGIN_VIEW_DIR', realpath( __DIR__ . '/../' ) );
define( 'MCZR_TRANSLATION_DIR', MCZR_PLUGIN_VIEW_DIR . '/Resources/translations' );

class MczrFactory {

	private static $instance;

	public static function getInstance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		// Set up the Validator component
		$validator = Validation::createValidator();
		// Set up the Translation component
		// $translator = new Translator( 'en' );
		// $translator->addLoader( 'xlf', new XliffFileLoader() );
		// $translator->addResource( 'xlf', MCZR_TRANSLATION_DIR . '/validators.en.xlf', 'en', 'validators' );
		// $translator->addResource( 'xlf', MCZR_TRANSLATION_DIR . '/validators.en.xlf', 'en', 'validators' );
		$this->twig = new \Twig\Environment(
			new \Twig\Loader\FilesystemLoader(
				array(
					MCZR_VIEWS_DIR,
					MCZR_PLUGIN_VIEW_DIR . '/Resources/views',
				)
			)
		);
		//$this->twig->addExtension( new TranslationExtension( $translator ) );

		$this->validator = $validator;
		return;
	}
	public $twig;
	public $validator;

	public function getValidator() {
		return $this->validator;
	}

	public function getTwig() {
		return $this->twig;
	}
}
