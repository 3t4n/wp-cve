<?php
/*
Plugin Name: Add to Header
Plugin URI: http://www.devdevote.com/wordpress/plugins/add-to-header/
Description: Add stuff to your theme header, such as CSS, Javascript, metadata, Google Analytics etc.
Author: Jens T&ouml;rnell
Version: 1.0
Author URI: http://www.jenst.se
*/

$add_to_header = new add_to_header();
$add_to_header->add_menu();

class add_to_header
{
	function add_menu()
	{
		add_action('admin_head', array('add_to_header', 'admin_add_js'));
		add_action('admin_menu', array('add_to_header', 'admin_add_menu'));
		add_action('wp_head', array('add_to_header', 'front_add_data'));
	}
	public static function admin_add_menu()
	{
		add_options_page('Add to Header', 'Add to Header', 8, 'add_to_header', array('add_to_header', 'options'));
	}
	
	public static function admin_add_js()
	{
		if($_GET['page'] == 'add_to_header')
		{
			$js_url = trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__))) . 'js/add-to-header.js';
			echo '<script type="text/javascript" src="' . $js_url . '"></script>';
		}
	}
	
	function data_save()
	{
		if(isset($_POST['submitter']))
		{
			$option_name = 'add_to_header';
			$options['data'][0] = $_POST['new_data'];
			
			if ( get_option($option_name) )
				update_option($option_name, $options);
			else
				add_option( $option_name, $options );
		}
	}
	
	public static function front_add_data()
	{
		$domain_url = trailingslashit(get_bloginfo('url'));
		$blog_url = trailingslashit(get_bloginfo('wpurl'));
		$theme_url = trailingslashit(get_bloginfo('template_url'));
		$plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
	
		$options = get_option('add_to_header');
		$output = $options['data'][0];
		
		$output = str_replace('%domain_url%', $domain_url, $output);
		$output = str_replace('%blog_url%', $blog_url, $output);
		$output = str_replace('%theme_url%', $theme_url, $output);
		$output = str_replace('%plugin_url%', $plugin_url, $output);
		
		$output = "\n<!-- ADD TO HEADER - START -->\n" . $output . "\n<!-- ADD TO HEADER - END -->\n";
		
		echo stripslashes($output);
	}

	public static function options()
	{
		add_to_header::data_save();
		$options = get_option('add_to_header');
		
		$domain_url = trailingslashit(get_bloginfo('url'));
		$blog_url = trailingslashit(get_bloginfo('wpurl'));
		$theme_url = trailingslashit(get_bloginfo('template_url'));
		$plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
		
		?>
		<div class="wrap">
			<form method="post" name="add_to_header_form">
				<div id="icon-options-general" class="icon32"><br /></div>
				<h2>Add to Header</h2>
				
				<h3>Add HTML to header</h3>
				<textarea id="new_data" name="new_data" class="large-text code" cols="50" rows="10"><?php echo stripslashes($options['data'][0]); ?></textarea>
				<input type="submit" name="submitter" value="<?php esc_attr_e('Save Changes') ?>" class="button-primary" />
				
				<h3>Shortcodes</h3>
				<p>Add shortcodes in the header HTML to insert URLs dynamically.</p>
				<table class="widefat">
					<thead>
						<tr>
							<th>Dynamic shortcodes</th>
							<th>Shortcodes are replaced by these URLs</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>%domain_url%</td>
							<td><?php echo $domain_url; ?></td>
						</tr>
						<tr>
							<td>%blog_url%</td>
							<td><?php echo $blog_url; ?></td>
						</tr>
						<tr>
							<td>%theme_url%</td>
							<td><?php echo $theme_url; ?></td>
						</tr>
						<tr>
							<td>%plugin_url%</td>
							<td><?php echo $plugin_url; ?></td>
						</tr>
					</tbody>
				</table>
				
				<h3>Code blocks</h3>
				<p>Insert the code blocks you like with a click.</p>
				
				<table class="widefat">
					<thead>
						<tr>
							<th>Code block description</th>
							<th>Code block HTML</th>
							<th>Click to insert code</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Stylesheet - Internal CSS</td>
							<td><em>&lt;style type="text/css"&gt;<br /><strong>YOUR_CSS</strong><br />&lt;/style&gt;</em>
							<td>
								<input onclick="add_clicktag('css_internal', 'first')" type="button" class="button-secondary" value="Add first" />
								<input onclick="add_clicktag('css_internal', 'last')" type="button" class="button-secondary" value="Add last" />
							</td>
						</tr>
						<tr>
							<td>Stylesheet - External CSS</td>
							<td><em>&lt;link rel="stylesheet" type="text/css" href="<strong>YOUR_CSS</strong>" /&gt;</em></td>
							<td>
								<input onclick="add_clicktag('css_external', 'first')" type="button" class="button-secondary" value="Add first" />
								<input onclick="add_clicktag('css_external', 'last')" type="button" class="button-secondary" value="Add last" />
							</td>
						</tr>
						<tr>
							<td>Javascript - Internal</td>
							<td><em>&lt;script type="text/javascript"&gt;<br/><strong>YOUR_JAVASCRIPT</strong><br />&lt;/script&gt;</em></td>
							<td>
								<input onclick="add_clicktag('js_internal', 'first')" type="button" class="button-secondary" value="Add first" />
								<input onclick="add_clicktag('js_internal', 'last')" type="button" class="button-secondary" value="Add last" />
							</td>
						</tr>
						<tr>
							<td>Javascript - External</td>
							<td><em>&lt;script type="text/javascript" src="<strong>YOUR_JAVASCRIPT</strong>" /&gt;</em></td>
							<td>
								<input onclick="add_clicktag('js_external', 'first')" type="button" class="button-secondary" value="Add first" />
								<input onclick="add_clicktag('js_external', 'last')" type="button" class="button-secondary" value="Add last" />
							</td>
						</tr>
						<tr>
							<td>Metadata</td>
							<td><em>&lt;meta name="META_NAME" content="<strong>META_CONTENT</strong>" /&gt;</em></td>
							<td>
								<input onclick="add_clicktag('meta', 'first')" type="button" class="button-secondary" value="Add first" />
								<input onclick="add_clicktag('meta', 'last')" type="button" class="button-secondary" value="Add last" />
							</td>
						</tr>
					</tbody>
				</table>
				
				<h3>Pre-generated HTML</h3>
				<p>The HTML below just shows what is generated by this and other plugins.</p>
				
				<textarea name="current_data" class="large-text code" cols="50" rows="100" readonly="readonly"><?php echo wp_head(); ?></textarea>
			</form>
		</div>
		<?php
	}
}
?>