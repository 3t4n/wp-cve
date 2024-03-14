<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
* Trait for common data
*/
trait Hmcab_Common
{
    protected function load_admin_sidebar()
    {
        ?>
		<div class="hmcab-admin-sidebar" style="width: 20%; float: left;">
			<div class="postbox pro-features">
				<h3 class="hndle"><span>Pro Features</span></h3>
				<div class="inside centered">
					<ul>
						<li>&#10003; Display Ticker News For Internal and External News</li>
						<li>&#10003; Name font color / font size</li>
						<li>&#10003; Title font color / font size</li>
						<li>&#10003; Description / biographical info font color / size</li>
						<li>&#10003; Email / Website font color / size</li>
						<li>&#10003; Photo/Avatar animation</li>
						<li>&#10003; Widget content alignment</li>
					</ul>
					<?php 
        ?>
						<p style="margin-bottom: 1px! important;"><a href="https://coolauthorbox.hmplugin.com/" target="_blank" class="button button-primary hmcab-button" style="background: #F5653E;">Upgrade Now!</a></p>
						<?php 
        ?>
				</div>
			</div>
			<div class="postbox">
				<h3 class="hndle"><span>Follow - Join HM Plugin</span></h3>
                <div class="inside centered">
                    <p style="margin-bottom: 1px! important;"><a href='https://wwww.facebook.com/hmplugin' class="button button-info" target="_blank">Join HM Plugin<span class="dashicons dashicons-facebook" style="position: relative; top: 3px; margin-left: 3px; color: #0fb9da;"></span></a></p>
                </div>
				<div class="inside centered">
					<a href="https://twitter.com/hmplugin" target="_blank" class="button button-secondary">Follow @hmplugin<span class="dashicons dashicons-twitter" style="position: relative; top: 3px; margin-left: 3px; color: #0fb9da;"></span></a>
				</div>
				<div class="inside centered">
					<a href="http://www.youtube.com/@hmplugin" target="_blank" class="button button-secondary">Subscribe HM Plugin<span class="dashicons dashicons-youtube" style="position: relative; top: 3px; margin-left: 3px; color: #CC0000;"></span></a>
				</div>
			</div>
		</div> 
		<?php 
    }

}