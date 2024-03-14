<?php
/*
 *  T4B News Ticker v1.2.9 - 23 November, 2023
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( !class_exists('t4bnt_settings_config' ) ):
class t4bnt_settings_config {

	private $settings_api;

	function __construct() {
		$this->settings_api = new t4bnt_WeDevs_Settings_API;
		add_action( 'admin_init', array($this, 'admin_init') );
		add_action( 'admin_menu', array($this, 'admin_menu') );
	}

	function admin_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();
	}

	function admin_menu() {
		add_options_page('T4B News Ticker', __( 'Ticker Settings','t4b-news-ticker' ), 'manage_options', 't4bnt-settings', array($this, 't4bnt_plugin_page'));
	}

	// setings tabs
	function get_settings_sections() {
		$sections = array(
			array(
			'id' => 't4bnt_general',
			'title' => __( 'General Settings', 't4b-news-ticker' )
			)
		);
		return $sections;
	}

	/**
	* Returns all the settings fields
	*
	* @return array settings fields
	*/
	function get_settings_fields() {
		$settings_fields = array( 
			't4bnt_general' => array(
                array(
                    'name'				=> 'ticker_news',
                    'label'				=> __( 'Enable Ticker', 't4b-news-ticker' ),
                    'desc'				=> __( 'Mark if you want to show News Ticker.', 't4b-news-ticker' ),
                    'type'				=> 'checkbox',
					'default'			=> 'on'
                ),
                array(
                    'name'				=> 'ticker_home',
                    'label'				=> __( 'Show in Homepage Only', 't4b-news-ticker' ),
                    'desc'				=> __( 'Select if you want to show the News Ticker only in homepage.', 't4b-news-ticker' ),
                    'type'				=> 'checkbox',
					'default'			=> 'off'
                ),
                array(
                    'name'              => 'ticker_title',
                    'label'             => __( 'Enter Ticker Title', 't4b-news-ticker' ),
                    'desc'              => __( 'Enter a title for the News Ticker', 't4b-news-ticker' ),
                    'placeholder'       => __( 'News Ticker', 't4b-news-ticker' ),
                    'type'              => 'text',
                    'default'           => 'Trending Now',
                    'sanitize_callback' => 'sanitize_text_field'
				),
                array(
                    'name'				=> 'ticker_effect',
                    'label'				=> __( 'Ticker Animation Type', 't4b-news-ticker' ),
                    'desc'				=> __( 'Select type of animation (Default: \'scroll\').', 't4b-news-ticker' ),
                    'type'				=> 'select',
                    'default'			=> 'scroll',
                    'options'			=> array(
							'slide'		=> 'Slide',
							'fade'		=> 'Fade',
							'ticker'	=> 'Ticker',
							'scroll'	=> 'Scroll'
					)
                ),
                array(
                    'name'              => 'ticker_fadetime',
                    'label'             => __( 'Timeout', 't4b-news-ticker' ),
                    'desc'              => __( 'Time between the fades in milliseconds (Default: \'2000\')', 't4b-news-ticker' ),
                    'placeholder'       => __( '2000', 't4b-news-ticker' ),
                    'min'               => 100,
                    'max'               => 20000,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '2000',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'				=> 'scroll_control',
                    'label'				=> __( 'Scrolling Control', 't4b-news-ticker' ),
                    'desc'				=> __( 'Mark if you want to show the News Ticker control buttons.', 't4b-news-ticker' ),
                    'type'				=> 'checkbox',
					'default'			=> 'off'
                ),
                array(
                    'name'              => 'scroll_speed',
                    'label'             => __( 'Speed of Scrolling', 't4b-news-ticker' ),
                    'desc'              => __( 'Scrolling speed of the ticker.', 't4b-news-ticker' ),
                    'placeholder'       => __( '0.05', 't4b-news-ticker' ),
                    'min'               => 0.01,
                    'max'               => 0.2,
                    'step'              => '0.01',
                    'type'				=> 'number',
                    'default'           => '0.05',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'              => 'reveal_speed',
                    'label'             => __( 'Speed of Ticker', 't4b-news-ticker' ),
                    'desc'              => __( 'Revealing speed of the ticker.', 't4b-news-ticker' ),
                    'placeholder'       => __( '0.10', 't4b-news-ticker' ),
                    'min'               => 0.01,
                    'max'               => 0.9,
                    'step'              => '0.01',
                    'type'				=> 'number',
                    'default'           => '0.10',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'    			=> 'ticker_type',
                    'label'   			=> __( 'News Ticker Type', 't4b-news-ticker' ),
                    'desc'   			=> __( '', 't4b-news-ticker' ),
                    'type'    			=> 'radio',
                    'default'           => 'category',
                    'options' 			=> array(
							'category' 	=> 'Categories' ,
							'tag' 		=> 'Tags',
							'custom' 	=> 'Custom Text'
                    )
                ),
                array(
                    'name'				=> 'ticker_cat',
                    'label'				=> __( 'News Ticker Categories', 't4b-news-ticker' ),
                    'desc'				=> __( 'Select a category for News Ticker to show.', 't4b-news-ticker' ),
                    'type'				=> 'select',
                    'default'			=> '',
                    'options'			=> get_t4bnt_categories()
                ),
				array(
                    'name'              => 'ticker_tag',
                    'label'             => __( 'Select News Ticker Tags', 't4b-news-ticker' ),
                    'desc'              => __( 'Select tag names seprated by comma.', 't4b-news-ticker' ),
                    'placeholder'       => __( '', 't4b-news-ticker' ),
                    'type'              => 'textarea',
				),
				array(
					'name'      		=> 'ticker_postno',
					'label'     		=> __( 'Number of post', 't4b-news-ticker' ),
					'desc'      		=> __( 'Number of post to show. Default -1, means show all.', 't4b-news-ticker' ),
                    'placeholder'       => __( '10', 't4b-news-ticker' ),
                    'min'               => -1,
                    'max'               => 100,
					'type'     		 	=> 'number',
					'default'  			=> -1
				),
				array(
					'name'      		=> 'ticker_order',
					'label'     		=> __( 'Select Post Order', 't4b-news-ticker' ),
					'desc'      		=> __( '', 't4b-news-ticker' ),
					'type'      		=> 'select',
					'default'   		=> 'DESC',
					'options'   		=> array(
							'ASC'     	=> __( 'Ascending', 't4b-news-ticker' ),
							'DESC'    	=> __( 'Descending', 't4b-news-ticker' )
					),
				),
				array(
					'name'      		=> 'ticker_order_by',
					'label'     		=> __( 'Select Post Order By', 't4b-news-ticker' ),
					'desc'      		=> __( '', 't4b-news-ticker' ),
					'type'      		=> 'select',
					'default'   		=> 'date',
					'options'   		=> array(
							'ID'     	=> __( 'Post ID', 't4b-news-ticker' ),
							'name'    	=> __( 'Post Name (post slug)', 't4b-news-ticker' ),
							'date'    	=> __( 'Post Date', 't4b-news-ticker' )
					),
				),
                array(
                    'name'    			=> 'ticker_custom',
                    'label'   			=> __( 'News Ticker Custom Text', 't4b-news-ticker' ),
                    'desc'    			=> __( 'Enter custom text for your news ticker. One sentence with or without link per line.', 't4b-news-ticker' ),
                    'type'    			=> 'wysiwyg',
                    'default' 			=> ''
                ),
                array(
                    'name'				=> 'ticker_ntab',
                    'label'				=> __( 'Open in New Tab', 't4b-news-ticker' ),
                    'desc'				=> __( 'Select if you want to open link in new tab.', 't4b-news-ticker' ),
                    'type'				=> 'checkbox',
					'default'			=> 'off'
                ),
			),
		);
		return $settings_fields;
	}

	// wraping the settings
	function t4bnt_plugin_page() { ?>
		<?php do_action ( 't4bnt_before_settings' ); ?>
		<div class="t4bnt_settings_area">
			<div class="wrap t4bnt_settings"><?php
				$this->settings_api->show_navigation();
				$this->settings_api->show_forms(); ?>
				<div id="t4bntusage-premium" class="t4bntusage-sidebar">
					<h3><?php esc_html_e('Code Usage Instruction in a Theme', 't4b-news-ticker'); ?></h3>
					<div class="t4bnt">
						<p><?php esc_html_e('To display a news ticker shortcode in a WordPress theme file, you can use the ', 't4b-news-ticker'); ?><strong><?php esc_html_e('do_shortcode', 't4b-news-ticker'); ?></strong> <?php esc_html_e(' function:', 't4b-news-ticker'); ?></p>
						<pre><code><span class="t4bnt-meta">&lt;?php</span> <span class="t4bnt-keyword">echo</span> <span class="t4bnt-function">do_shortcode</span>(<span class="t4bnt-string">'[t4b-ticker]'</span>); <span class="t4bnt-meta">?&gt;</span></code></pre>
						<p><?php esc_html_e('You can place this code anywhere in your theme file where you want the news ticker to appear. For example, you could place it in the template file for a specific page of your site.', 't4b-news-ticker'); ?></p>
						<p><?php esc_html_e('Keep in mind that if you are making changes directly to your theme files, those changes will be overwritten if you update the theme. To avoid this, you can create a child theme and make your changes there instead.', 't4b-news-ticker'); ?></p>
					</div>
				</div>
			</div>
			<div class="t4bnt_settings_content">
				<?php do_action ( 't4bnt_settings_content' ); ?>
			</div>
		</div>
		<?php do_action ( 't4bnt_after_settings' ); ?>
		<?php
	}

	/**
	* Get all the pages
	*
	* @return array page names with key value pairs
	*/
	function get_pages() {
		$pages = get_pages();
		$pages_options = array();
		if ( $pages ) {
			foreach ($pages as $page) {
				$pages_options[$page->ID] = $page->post_title;
			}
		}
		return $pages_options;
	}
}
endif;

$settings = new t4bnt_settings_config();

//--------- trigger setting api class---------------- //
function t4bnt_get_option( $option, $section, $default = '' ) {
	$options = get_option( $section );
	if ( isset( $options[$option] ) ) {
		return $options[$option];
	}
	return $default;
}
//--------- get categories for news ticker---------------- //
function get_t4bnt_categories() {
	$ticker_categories = get_categories();
	$categories = array("Select a category");
	//print_r($ticker_categories);
	foreach ($ticker_categories as $category) {
		$categories[$category->cat_ID] = $category->name;
	}
	//$categories_tmp = array_shift($categories);
	//print_r($categories);
	return $categories;
}
?>