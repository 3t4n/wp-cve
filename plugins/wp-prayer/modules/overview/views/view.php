<?php
/**
 * Plugin Overviews.
 * @package WP Prayer Engine
 * @author Flipper Code <flippercode>
 **/
?>
<div class="container">
	<div class="row">
		<div class="col-md-11">
			   <h4 class="alert alert-info"> <?php _e( 'How to Use',WPE_TEXT_DOMAIN ); ?> </h4>
			  <div class="wpgmp-overview">
				<blockquote><?php _e( 'Create prayer form and listing' ); ?></blockquote>
				<ol>
					<li><?php
					$url = admin_url( 'admin.php?page=wpe_form_prayer' );
					$link = sprintf( wp_kses( __( 'Add prayers <a href="%s">here</a>',  WPE_TEXT_DOMAIN), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
					echo $link;?>
					</li>
					<li><?php
					$url ='';
					$link = sprintf( wp_kses( __( 'Prayer request form and listing use shortcodes', WPE_TEXT_DOMAIN ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
					echo $link;?>
					</li>
					<li><?php
					$url = admin_url( 'admin.php?page=wpe_manage_prayer' );
					$link = sprintf( wp_kses( __( 'Manage submitted prayer requests <a href="%s">here</a>',  WPE_TEXT_DOMAIN), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
					echo $link;?>
					</li>
					<li><?php
					$url = admin_url( 'admin.php?page=wpe_manage_settings' );
					$link = sprintf( wp_kses( __( 'Settings <a href="%s">here</a>.', WPE_TEXT_DOMAIN ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
					echo $link;?>
					</li>
				</ol>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-11">
			<!-- second section here -->
				<h4 class="alert alert-info"> <?php _e( 'Shortcodes',WPE_TEXT_DOMAIN ); ?> </h4>
				<div class="wpgmp-overview">
					<blockquote>
						<?php _e( 'Create prayers list and prayer form page', WPE_TEXT_DOMAIN ) ?>
					</blockquote>
					<p>
						<?php _e( 'Prayers and prayer request form use shortcode',WPE_TEXT_DOMAIN );  ?>
					</p>
					<p>
						<h5 class="alert alert-info"><?php _e( 'Display Prayer Request and Praise Report Form',WPE_TEXT_DOMAIN ); ?></h5>
					</p>
					<p>
						<?php _e( 'Format for shortcode:',WPE_TEXT_DOMAIN ); ?>
					</p>
					<p>
						<code>[wp-prayer-engine form]</code>
					</p>

<p>
						<h5 class="alert alert-info"><?php _e( 'Display Prayer Request Form',WPE_TEXT_DOMAIN ); ?></h5>
					</p>
					<p>
						<?php _e( 'Format for shortcode:',WPE_TEXT_DOMAIN ); ?>
					</p>
					<p>
						<code>[wp-prayer-engine form type=prayer]</code>
					</p>
<p>
						<h5 class="alert alert-info"><?php _e( 'Display Praise Report Form',WPE_TEXT_DOMAIN ); ?></h5>
					</p>
					<p>
						<?php _e( 'Format for shortcode:',WPE_TEXT_DOMAIN ); ?>
					</p>
					<p>
						<code>[wp-prayer-engine form type=praise]</code>
					</p>
					<p>
						<h5 class="alert alert-info"><?php _e( 'Display Prayer Requests',WPE_TEXT_DOMAIN ); ?></h5>
					</p>
					<p>
						<?php _e( 'Format for shortcode:',WPE_TEXT_DOMAIN );?>
					</p>
					<p>
						<code>[wp-prayer-engine]</code>
					</p>
					<p>
						<h5 class="alert alert-info"><?php _e( 'Display Praise Reports',WPE_TEXT_DOMAIN ); ?></h5>
					</p>
					<p>
						<?php _e( 'Format for shortcode:',WPE_TEXT_DOMAIN );?>
					</p>
					<p>
						<code>[wp-prayer-praise]</code>
					</p>
				</div>
		  </div>
	</div>       
</div>
