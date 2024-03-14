<?php

/**
 * Backend Class for use in all Free Fly plugins
 * Version 1.1
 */
 
 	/**
	 * Style the sidebar
	 */
		function admin_css() {
    			$siteurl = get_option('siteurl');
    			$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/fly_plugins_tools.css';
    			echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
		}
		add_action('admin_head', 'admin_css');

if (!class_exists('Fly_Plugin_Admin')) {  
	class Fly_Plugin_Admin {

		/**
		 * Create a potbox widget
		 */
		function postbox($id, $title, $content) {
		?>
			<div id="<?php echo $id; ?>" class="postbox">
				<h3 class="hndle"><span><?php echo $title; ?></span></h3>
				<div class="inside">
					<?php echo $content; ?>
				</div>
			</div>
		<?php
			$this->toc .= '<li><a href="#'.$id.'">'.$title.'</a></li>';
		}	
		
		
		/**
		 * Box with latest news from flyplugins.com for sidebar
		 */
		function fly_news() {
			$rss = fetch_feed('http://feeds.feedburner.com/FlyPlugins');
			$rss_items = $rss->get_items( 0, $rss->get_item_quantity(3) );
			
			$content = '<ul>';
			if ( !$rss_items ) {
			    $content .= '<li class="fly">'.__( 'No news items, feed might be broken...', 'fly' ).'</li>';
			} else {
			    foreach ( $rss_items as $item ) {
			    	$url = preg_replace( '/#.*/', '', esc_url( $item->get_permalink(), $protocolls=null, 'display' ) );
					$content .= '<li class="fly">';
					$content .= '<a class="rsswidget" href="'.$url.'">'. esc_html( $item->get_title() ) .'</a> ';
					$content .= '</li>';
			    }
			}						
			$content .= '<li class="facebook"><a href="http://www.facebook.com/pages/Fly-Plugins/310313022393440">'.__( 'Like Fly Plugins on Facebook', 'fly' ).'</a></li>';
			$content .= '<li class="twitter"><a href="https://twitter.com/#!/FlyPlugins">'.__( 'Follow Fly Plugins on Twitter', 'fly' ).'</a></li>';
			//$content .= '<li class="googleplus"><a href="https://plus.google.com/u/0/b/117422611553543551383/117422611553543551383/posts">'.__( 'Circle Fly Plugins on Google+', 'fly' ).'</a></li>';
			//$content .= '<li class="rss"><a href="http://feeds.feedburner.com/FlyPlugins">'.__( 'Subscribe with RSS', 'fly' ).'</a></li>';
			//$content .= '<li class="email"><a href="http://feedburner.google.com/fb/a/mailverify?uri=FlyPlugins&amp;loc=en_US">'.__( 'Subscribe by email', 'fly' ).'</a></li>';
			$content .= '</ul>';
			$this->postbox('flylatest', __( 'Latest news from FlyPlugins.com', 'fly' ), $content);
		}

		/**
		 * PayPal donation box for free plugins
		 */
		function donate() {
			$this->postbox('donate','<strong class="red">'.__( 'Like our FREE Fly Plugins?', 'Ascending Post' ).'</strong>','<p><strong>'.__( 'Want to help make our free Fly Plugins better? Donate today! We appreciate any amount donated. Thank you for supporting Fly Plugins!', 'fly' ).'</strong></p><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="P5ZLXCHS9BRFW">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>'
			.'<p>'
			.'<ul>'
			.'<li><a href="http://wordpress.org/">'.__('Please rate the plugin 5 ★\'s on WordPress.org', 'fly').'</a></li>'
			.'<li><a href="http://www.flyplugins.com/">'.__('Visit us at our website FlyPlugins.com', 'fly').'</a></li>'
			.'</ul>');
		}
		

	}
} 

?>