<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
if( ! class_exists('Locatoraid_Searchform_Widget30') )
{
class Locatoraid_Searchform_Widget30 extends WP_Widget
{
	public $app = '';
	public $w_arg = array(
		'category'=> 0,
		);

	public function __construct()
	{
		$this->dir = dirname(__FILE__) . '/..';
		$this->app = 'locatoraid';
		parent::__construct(
	 		'locatoraid_widget', // Base ID
			'Locatoraid Search Form', // Name
			array( 
				'description' => __( 'Show your locator search form', 'locatoraid' ),
				) // Args
		);
	}

 	public function form( $instance )
	{
		$instance = wp_parse_args( (array) $instance, $this->w_arg );
		$return = $this->render( 'admin', array('instance' => $instance) );
		echo $return;
	}

	public function widget( $args, $instance )
	{
		/* find the front page */
		global $wpdb;
		$shortcode = '' . $this->app . '';

		$pages = array();
		$pages = $wpdb->get_results( 
			"
			SELECT 
				ID 
			FROM $wpdb->posts 
			WHERE 
				( post_type = 'post' OR post_type = 'page' ) 
				AND 
				( post_content LIKE '%[" . $shortcode . "%]%' )
				AND 
				( post_status = 'publish' )
			"
			);
		if( ! $pages ){
			return;
		}

		$default_locator_page = get_permalink($pages[0]->ID);

		$label = (isset($instance['label'])) ? $instance['label'] : __('Address or Zip Code', 'locatoraid');
		$btn = (isset($instance['btn'])) ? $instance['btn'] : __('Search', 'locatoraid');
		$target = (isset($instance['target'])) ? $instance['target'] : $default_locator_page;

		$params = array(
			'locator_page'	=> $target,
			'label'			=> $label,
			'btn'			=> $btn,
			);
		$return = $this->render( 'front', $params );
		echo $return;
	}

	public function render( $view, $vars = array() )
	{
		$file = dirname(__FILE__) . '/widget_searchform_view_' . $view . '.php';
		if( ! file_exists($file) ){
			$content = 'File "' . $view . '" does not exist<br>';
		}
		else {
			extract( $vars );
			ob_start();
			require( $file );
			$content = ob_get_contents();
			ob_end_clean();
		}
		return $content;
	}
}

add_action( 'widgets_init', function(){ register_widget( "Locatoraid_Searchform_Widget30" ); } );
}