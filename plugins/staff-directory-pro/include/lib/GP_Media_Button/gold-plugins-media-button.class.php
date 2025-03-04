<?php
//we need to wrap easy t's in a class_exists as well, to prevent conflicts from both
//as a workaround, i've also renamed this one
if(!class_exists('Company_Directory_Gold_Plugins_Media_Button'))
{
	class Company_Directory_Gold_Plugins_Media_Button
	{
		var $buttons = array();
		var $button_label = '';
		var $button_dashicon = '';
		
		function __construct($button_label = '', $button_dashicon = '')
		{
			$this->add_hooks();
			$this->button_label = $button_label;
			$this->button_dashicon = $button_dashicon;
		}
		
		function add_button($widget_title, $shortcode, $widget_class, $dashicon = '')
		{
			$this->buttons[] = array(
				'widget_title' => $widget_title,
				'shortcode' => $shortcode,
				'widget_class' => $widget_class,
				'dashicon' => $dashicon
			);
		}
		
		function add_hooks()
		{
			add_action( 'admin_enqueue_scripts',  array( $this, 'enqueue_admin_scripts' ) );
			add_action( 'media_buttons',  array( $this, 'add_media_button' ) );
			add_action( 'wp_ajax_gold_plugins_insert_widget_popup', array( $this, 'output_media_button_page' ) );
		}
		
		/* 
		 * Enqueue scripts and styles needed to display the popups and media buttons
		 */
		function enqueue_admin_scripts()
		{
			global $pagenow;
						
			if( 'edit.php' == $pagenow || 'post-new.php' == $pagenow  || 'post.php' == $pagenow || (('admin.php' == $pagenow) && (strpos($_GET['page'], 'shortcode-generator') !== false)) )//RWG: add check for SC generator pages
			{
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_script(
					'gp_media_button_v1',
					plugins_url('js/gp_media_button_v1.js', __FILE__ ),
					array( 'jquery' ),
					false,
					true
				);
				
				wp_enqueue_style("wp-jquery-ui-dialog");
				
				wp_register_style( 'gold_plugins_media_button_stylesheet', plugins_url('css/gp_media_button_v1.css', __FILE__) );
				wp_enqueue_style( 'gold_plugins_media_button_stylesheet' );
			}
		}
		
		
		/* 
		 * Adds our custom media buttons to the WordPress editor's interface
		 */
		function add_media_button($context = '')
		{
			$output = '';
			$ajax_url = admin_url( 'admin-ajax.php?action=gold_plugins_insert_widget_popup' );
			$output .= '<div class="gp_media_button_group">';
			$btn = '';
			if (!empty($this->button_dashicon)) {
				$btn = sprintf('<span class="dashicons dashicons-%s wp-media-buttons-icon"></span>', $this->button_dashicon);
			}
			$output .= sprintf('<a class="gp_media_button_group_toggle">%s <span class="media_button_label">%s</span> <span class="dashicons dashicons-arrow-down-alt2 wp-media-buttons-icon"></span>	</a>', $btn, $this->button_label);
			$output .= '<ul class="gp_media_button_group_dropdown">';
				$button_template = '<a onclick="gp_loadMediaButtonPopup(\'%s\', \'%s\', \'%s\'); return false;" href="#" class="button gold_plugins_media_button"><span class="dashicons dashicons-%s wp-media-buttons-icon"></span> <span class="media_button_label">%s</span></a>';
				foreach($this->buttons as $button) {
					$output .= sprintf($button_template, $button['widget_class'], $button['shortcode'], $button['widget_title'], $button['dashicon'], $button['widget_title']);
				}
			$output .= '</ul>';
			$output .= '</div>';
			add_filter( 'wp_kses_allowed_html', array($this, 'temp_allow_onclick_on_links'), 10, 2 );
			echo wp_kses($output, 'post');
			remove_filter( 'wp_kses_allowed_html', array($this, 'temp_allow_onclick_on_links'), 10, 2 );
		}
		
		/*
		 * Allow onclick on <a> tags when using wp_kses. Should be hooked to 
		 *  the 'wp_kses_allowed_html' filter
		 *
		 * Note: we use this only for our own links, and then we turn it back
		 * 	     off by removing the filter immediately
		 *
		 * @param $allowed array List of allowed tags
		 * @param $context string Context from wp_kses()
		 * @filter should be hooked to wp_kses_allowed_html
		 *
		 * @return array Modified list of allowed tags, which now allows 
	     *			 onclick  for '<a>' tags
		 */
		function temp_allow_onclick_on_links($allowed, $context)
		{
			if ( 'post' === $context ) {
				$allowed['a']['onclick'] = true;
			}
			return $allowed;
		}
		
		/* 
		 * Outputs a popup containing a widget (as specified in the REQUEST vars).
		 * IMPORTANT: 	This function outputs the form to the screen and then terminates (dies),
		 * 				as it is meant to be called via WP's Admin AJAX methods
		 * 
		 * Note: $_REQUEST['widget_name'] and $_REQUEST['shortcode'] must always be specified. 
		 */
		function output_media_button_page()
		{
			/** Load required files from WordPress Administration Widgets API */
			require_once(ABSPATH . 'wp-admin/includes/widgets.php');
			global $wp_registered_sidebars;
			global $wp_registered_widget_controls;
			global $wp_registered_widgets;

			// init local vars
			$base = ! empty($_REQUEST['widget_name'])
					? sanitize_text_field($_REQUEST['widget_name'])
					: '';
			$shortcode = ! empty($_REQUEST['shortcode'])
						 ? sanitize_text_field($_REQUEST['shortcode'])
						 : '';
			$multi_number = '1';
			
			// If a required param is missing abort with an error message
			if ( empty($base) || empty($shortcode) ) {
				echo '<p>' . __('Error: You must specify a base and a shortcode.') . "</p>\n";
				wp_die();
			}

			// start form wrapper
			echo '<div id="gold_plugins_media_button_popup_wrapper">';
			echo '<div id="gold_plugins_media_button_popup">';		

			// Copy minimal info from an existing instance of this widget to a new instance
			foreach ( $wp_registered_widget_controls as $control ) {
				if ( $base === $control['id_base'] ) {
					$control_callback = $control['callback'];
					$control['params'][0]['number'] = -1;
					$widget_id = $control['id'] = $control['id_base'] . '-' . $multi_number;
					$wp_registered_widget_controls[$control['id']] = $control;
					break;
				}
			}

			// Determine the name of the widget, so we can display it before the form
			if ( isset($wp_registered_widget_controls[$widget_id]) && !isset($control) ) {
				$control = $wp_registered_widget_controls[$widget_id];
				$control_callback = $control['callback'];
			} elseif ( !isset($wp_registered_widget_controls[$widget_id]) && isset($wp_registered_widgets[$widget_id]) ) {
				$name = esc_html( strip_tags($wp_registered_widgets[$widget_id]['name']) );
			}

			if ( !isset($name) ) {
				$name = esc_html( strip_tags($control['name']) );
			}
			
			// Output the actual widget form.
			?>
			<div class="inner_wrapper">
				<div class="editwidget"<?php printf(' style="width: %dpx"', max( intval($control['width']), 350 ) ); ?>>
					<h3><?php echo esc_html($name); ?></h3>
					<form action="#" method="post" onsubmit="gp_insertWidgetIntoPost(); return false;" data-shortcode="<?php echo esc_html($shortcode); ?>">
						<div class="widget-inside">
						<?php
							if ( is_callable( $control_callback ) ) {
								call_user_func_array( $control_callback, $control['params'] );
							}
							else {
								echo '<p>' . __('There are no options for this widget.') . "</p>\n";
							}
						 ?>
						</div>

						<div class="widget-control-actions">
							<?php submit_button( __( 'Insert Now' ), 'button-primary alignright', 'savewidget', false ); ?>
							<br class="clear" />
						</div>
					</form>
				</div>
			</div>
		<?php
			// close form
			echo '</div><!--gold_plugins_media_button_popup-->';
			echo '</div><!--gold_plugins_media_button_popup_wrapper-->';
			
			// end the AJAX request cleanly
			wp_die();
		}
		
	} // end class Gold_Plugins_Media_Button
}//end class_exists check
