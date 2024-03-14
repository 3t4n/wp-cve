"use strict";

jQuery(document).ready(function(){

	if ( jQuery('.cmb2-options-page.option-ec_options').length ) {

		var ec_wrap = jQuery('.cmb2-options-page.option-ec_options');
		var ec_sidebar_html = '';

		ec_sidebar_html += '<div class="embed-code-admin-sidebar">';
			ec_sidebar_html += '<div class="embed-code-admin-sidebar-title">';
				ec_sidebar_html += 'Improve Your Site';
			ec_sidebar_html += '</div>';
			ec_sidebar_html += '<div class="embed-code-admin-sidebar-content">';
				ec_sidebar_html += '<p>Want to take your site to the next level? Check out our free WordPress tutorials at <a href="http://designbombs.com" target="_blank">DesignBombs</a></p>';
				ec_sidebar_html += '<p>Some of our popular guides:</p>';
				ec_sidebar_html += '<ul><li><a href="https://www.designbombs.com/best-email-list-building-plugins-wordpress/" target="_blank">Top Email List Building Plugins</a></li>';
				ec_sidebar_html += '<li><a href="https://www.designbombs.com/wordpress-security/" target="_blank">Secure Your WordPress Site</a></li>';
				ec_sidebar_html += '<li><a href="https://www.designbombs.com/how-to-make-a-website/" target="_blank">How To Create A Website with WordPress</a></li></ul>';
			ec_sidebar_html += '</div>';
		ec_sidebar_html += '</div>';

		ec_wrap.append(ec_sidebar_html);

	}

});