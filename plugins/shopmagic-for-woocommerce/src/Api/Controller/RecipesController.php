<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use WPDesk\ShopMagic\Api\Normalizer\WorkflowAutomationNormalizer;
use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Recipe\RecipeConverter;
use WPDesk\ShopMagic\Workflow\Automation\Automation;

class RecipesController {

	private const DEFAULT_LOCALE = 'en_US';

	/** @var string */
	private $dir;

	public function __construct() {
		$this->dir = __DIR__ . '/../../../config/recipes';
	}

	public function index( WorkflowAutomationNormalizer $normalizer, RecipeConverter $converter ): \WP_REST_Response {
		$locale       = $this->get_locale();
		$lang_path    = $this->dir . '/' . $locale;
		$recipe_files = (array) scandir( $lang_path );
		sort( $recipe_files, SORT_NATURAL );
		$recipes = ( new ArrayCollection( $recipe_files ) )
			->filter( static function ( string $filename ) use ( $lang_path ) {
				return is_file( $lang_path . '/' . $filename );
			} )
			->map( static function ( string $filename ) use ( $lang_path, $converter ): Automation {
				$decoded = json_decode( file_get_contents( $lang_path . '/' . $filename ), true );

				return $converter->to_automation( $decoded );
			} )
			->map( \Closure::fromCallable( [ $normalizer, 'normalize' ] ) )
			->to_array();

		return new \WP_REST_Response( $recipes );
	}

	private function get_locale(): string {
		$locale = get_locale();
		if ( ! file_exists( $this->dir . '/' . $locale ) ) {
			$locale = self::DEFAULT_LOCALE;
		}

		return $locale;
	}

}
