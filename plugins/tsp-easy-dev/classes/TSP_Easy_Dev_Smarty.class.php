<?php	
if ( !class_exists( 'TSP_Easy_Dev_Smarty' ) )
{
	/**
	 * Wrapper for the Smarty class
	 * @package 	TSP_Easy_Dev
	 * @author 		sharrondenice, letaprodoit
	 * @author 		Sharron Denice, Let A Pro Do IT!
	 * @copyright 	2021 Let A Pro Do IT!
	 * @license 	APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version 	1.3.0
	 */
	class TSP_Easy_Dev_Smarty extends Smarty
	{
		/**
		 * A boolean to turn debugging on for this class - used in Smarty so must be public
		 *
		 * @ignore
		 *
		 * @var boolean
		 */
		public $debugging = false;

		/**
		 * Constructor
		 *
		 * @param array $template_dirs Optional array of template directories
		 * @param string $cache_dir Optional directory for cache
		 * @param string $compiled_dir Optional directory for cache
		 * @param boolean $form Optional are we displaying a form or not
		 *
		 */
		public function __construct( $template_dirs = null, $cache_dir = null, $compiled_dir = null, $form = false )
		{
			parent::__construct();
			
			// Only use the default globals if they are none in the database
			
			if ( !empty( $template_dirs ))
				$this->setTemplateDir( $template_dirs );
			
			if ( !empty( $cache_dir ))
				$this->setCompileDir( $cache_dir );
			
			if ( !empty( $compiled_dir ))
				$this->setCacheDir( $compiled_dir );

			if ( $form )
			{
				$this->assign( 'EASY_DEV_FORM_FIELDS',	'easy-dev-form-fields.tpl' );
				$this->assign( 'field_prefix',			TSP_EASY_DEV_FIELD_PREFIX );
				$this->assign( 'class',					'');
			}//end if
		}//end __construct
	}//end TSP_Easy_Dev_Smarty
}//end if