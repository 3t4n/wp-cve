<div class="wrap about-wrap dicode-icons-admin-wrap">
	<div class="dicode-icons-plugins dicode-icons-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="dicode-icons-box">
				<div class="dicode-icons-box-head">
					<?php esc_html_e('Icons Search','classiadspro'); ?>
				</div>
				<div class="dicode-icons-box-content">
<?php
	$admin = new Dicode_Icons_Pack_Admin('', '');
	
	echo '<div class="wrap icon-library-page">';
	echo '<p>' . esc_html__('Search and find your desired icon and copy class to past in your desired location.', 'classiadspro') . '</p>';

	echo '<input autocomplete="off" size="60" placeholder="Type a keyword to find your icon... " type="text" class="icon-filter" value="" name="icon-filter-by-name" />';

	echo '<div class="dicode-icons-font-icons-wrapper clearfix">';


echo '<ul id="icon-lists">';
	foreach ($admin->admin_lib_icon_list() as $key) {
		echo '<li class="dicode-icon-item">';
		echo '<ul class="icon-box">';
		echo '<li class="medium"><i class="' . esc_attr($key) . '" ></i></li>';
		echo '<li class="class-name" title="Class name">';
			echo '<div>Copy Class:</div>';
			echo '<div class="result"></div>';
			echo '<div class="dicode-icon-btn" data-clipboard-icon-class="' . esc_attr($key) . '"><img src="'. esc_url(DICODE_ICONS_ASSETS_URL . 'images/copy.png') .'" /></div>';
			echo '<div class="filter-text">' . esc_attr($key) . '"</div>';
			echo '</li>';
		echo '</ul>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
?>
</div>
</div>
</div>
</div>
</div>
<script>
(function($) {
      //var btn = document.querySelector('.dicode-icon-btn');

      $('.dicode-icon-btn').on('click', function() {
		  $('.result').hide();
		  var icon_class = jQuery(this).attr('data-clipboard-icon-class');
		  //alert(icon_class);
        const textCopied = ClipboardJS.copy(icon_class);
		var parent = $(this).parent();
		$(this).parent().find('.result').text('copied!').show();
        //console.log('copied!', textCopied);
      });
})(jQuery);
    </script>