<?php



/**

 *

 */

class emailblaster extends WP_Widget

{

	public function __construct() {

		parent::__construct(

			'emailblaster',

			//title of the widget in the WP dashboard
			__('Email Blaster'),

			array('description'=>'Displays an Email Blaster form.', 'class'=>'emailblasterwidget')

		);

	}



	/**

	 *

	 * @param type $instance

	 */

	public function form($instance)

	{
		// these are the default widget values
		$default = array(

			'title' => __(''),

			'code'=> __('')

			);

		$instance = wp_parse_args( (array)$instance, $default );

		//this is the html for the fields in the wp dashboard
		echo "\r\n";

		echo "<p><b>Great Work!</b> - Your widget is ready to go. Just enter your form QuickCode or <a href=\"https://emailblaster.cloud/landing/wordpress\">build a new form.</a></p>";

		echo "<p>";

		echo "<label for='".$this->get_field_id('title')."'>" . __('Title') . ":</label> " ;

		echo "<input type='text' class='widefat' id='".$this->get_field_id('title')."' name='".$this->get_field_name('title')."' value='" . esc_attr($instance['title'] ) . "' />" ;

		echo "</p>";

		echo "<p>";

		echo "<label for='".$this->get_field_id('code')."'>" . __('QuickCode') . ":</label> " ;

		echo "<input type='text' class='widefat' id='".$this->get_field_id('code')."' name='".$this->get_field_name('code')."' value='" . esc_attr( $instance['code'] ) . "' placeholder='Your Email Blaster Quick Code' />" ;

		echo "</p>";


	}



	/**

	 *

	 * @param type $new_instance

	 * @param type $old_instance

	 * @return type

	 */

	public function update($new_instance, $old_instance)

	{

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		$instance['code'] = $new_instance['code'];

		return $instance;

	}



	/**

	 * Renders the actual widget

	 *

	 * @global post $post

	 * @param array $args

	 * @param type $instance

	 */



	public function widget($args, $instance)

	{
        // Load jQuery & .JS
		if (!is_admin()) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('the_js', plugins_url('/resources/emailblaster.min.js?rev=1',__FILE__) );
		 }


		extract($args, EXTR_SKIP);

		//global WP theme-driven "before widget" code
		echo $before_widget;

		// [Email Blaster] Generate Submitted Embed Code
		if(function_exists('base64url_decode')){ } else {
			function base64url_decode($data) { return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));}
		}
    $eb_Translate = base64url_decode($instance['code']);
		// [Email Blaster] Determine If Subscribe/Form Builder
		if (strpos($eb_Translate, 'sub/') !== false) {
				$eb_RequestPath = 'https://emailblaster.cloud/'.$eb_Translate;
		} else {
			$eb_RequestPath = 'https://emailblaster.cloud/fB/'.$eb_Translate;
		}
		// [Email Blaster] Echo Output
	  if ($eb_Translate !== '') {
       echo '<div class="EmailBlasterWidget"><iframe src="'.$eb_RequestPath.'"';
	     echo ' width="100%" scrolling="no" frameborder="0" class="EmailBlasterForm"></iframe></div>';
	  }



		//global WP theme-driven "after widget" code
		echo $after_widget;
	}

}
