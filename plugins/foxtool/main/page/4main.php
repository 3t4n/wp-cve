<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('DISPLAY', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check4" data-target="play4" type="checkbox" name="foxtool_settings[main]" value="1" <?php if ( isset($foxtool_options['main']) && 1 == $foxtool_options['main'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play4" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-icons"></i> <?php _e('Add font Awesome to the website', 'foxtool') ?></h3>
	<!-- main add font icon 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[main-add1]" value="1" <?php if ( isset($foxtool_options['main-add1']) && 1 == $foxtool_options['main-add1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Bật font Awesome', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you want to use font Awesome, you can enable it (it an icon font). You can search for icons at:', 'foxtool'); ?><br>
	<b><a target="_blank" href="https://fontawesome.com/search"><?php _e('Access Font Awesome to find icons', 'foxtool'); ?></a></b><br>
	<?php _e('Free to use "fa-regular" and "fa-brands" styles', 'foxtool'); ?>
	</p>
	
  <h3><i class="fa-regular fa-font-case"></i> <?php _e('Change the font for the page', 'foxtool') ?></h3>
	<!-- add font Google 1 -->
	<?php $styles = array('Default', 'Arial', 'Oswald', 'Nunito', 'Josefin Sans', 'Montserrat', 'Roboto Condensed', 'Open Sans', 'Raleway', 'Playfair Display', 'Inter', 'Lora', 'Quicksand', 'Kanit', 'Comfortaa', 'Prompt', 'IBM Plex Serif', 'Spectral', 'Philosopher', 'Taviraj', 'Readex Pro', 'Anybody'); ?>
	<select name="foxtool_settings[main-font1]" id="foxtool-font"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['main-font1']) && $foxtool_options['main-font1'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	<div id="ft-font-demo">This is a font demo</div>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Choose a font style for your website. Once selected, all the text on your website will switch to the font style you choose', 'foxtool'); ?></p>
  
  <h3><i class="fa-regular fa-snowflake"></i> <?php _e('Decorative effects for the website', 'foxtool') ?></h3>
	<!-- add font Google 1 -->
	<?php $styles = array('None', 'Snow1', 'Snow2', 'Lunar1', 'Lunar2'); ?>
	<select name="foxtool_settings[main-hover1]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['main-hover1']) && $foxtool_options['main-hover1'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Choose decorations for the website, such as Christmas or Lunar New Year (If any effects cause issues on your website, it may be due to javascript conflicts. You can switch to other effects to use)', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-circle-half-stroke"></i> <?php _e('Dark mode', 'foxtool') ?></h3>
	<!-- darkmode -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[main-mode1]" value="1" <?php if ( isset($foxtool_options['main-mode1']) && 1 == $foxtool_options['main-mode1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable dark mode', 'foxtool'); ?></label>
	</p>
	<p>
	<?php $styles = array('Dark1', 'Dark2'); ?>
	<select name="foxtool_settings[main-mode10]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['main-mode10']) && $foxtool_options['main-mode10'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p>
	<?php $styles = array('Left', 'Right'); ?>
	<select name="foxtool_settings[main-mode11]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['main-mode11']) && $foxtool_options['main-mode11'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[main-mode12]" min="10" max="300" value="<?php if(!empty($foxtool_options['main-mode12'])){echo sanitize_text_field($foxtool_options['main-mode12']);} else { echo sanitize_text_field('30');} ?>" class="ftslide" data-index="5">
	<span><?php _e('Spacing below', 'foxtool'); ?> <span id="demo5"></span> PX</span>
	</p>
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[main-mode13]" min="10" max="100" value="<?php if(!empty($foxtool_options['main-mode13'])){echo sanitize_text_field($foxtool_options['main-mode13']);} else { echo sanitize_text_field('30');} ?>" class="ftslide" data-index="6">
	<span><?php _e('Border distance', 'foxtool'); ?> <span id="demo6"></span> PX</span>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This function allows you to initiate a dark mode library, enabling the website to switch between light and dark modes', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-magnifying-glass"></i> <?php _e('Quick search', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[main-search1]" value="1" <?php if ( isset($foxtool_options['main-search1']) && 1 == $foxtool_options['main-search1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable quick search', 'foxtool'); ?></label>
	</p>
	<div class="tb-doi" id="tb-doi-sogiay" style="display:none"><img src="<?php echo esc_url(FOXTOOL_URL . 'img/load4.gif'); ?>" /> <?php _e('Automatic initialization after <span id="sogiay" style="padding: 5px;">3</span>s', 'foxtool'); ?></div>
	<p>
	<input class="ft-input-small" name="foxtool_settings[main-search-c1]" type="number" placeholder="10" value="<?php if(!empty($foxtool_options['main-search-c1'])){echo sanitize_text_field($foxtool_options['main-search-c1']);} ?>"/>
	<label class="ft-label-right"><?php _e('Number of items displayed', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enter the number of posts or products to be displayed when searching', 'foxtool'); ?></p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[main-search-c2]" type="text" data-coloris value="<?php if(!empty($foxtool_options['main-search-c2'])){echo sanitize_text_field($foxtool_options['main-search-c2']);} ?>"/>
	<label class="ft-right-text"><?php _e('Button color', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[main-search-c3]" type="text" data-coloris value="<?php if(!empty($foxtool_options['main-search-c3'])){echo sanitize_text_field($foxtool_options['main-search-c3']);} ?>"/>
	<label class="ft-right-text"><?php _e('Border color', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[main-search-c4]" type="text" data-coloris value="<?php if(!empty($foxtool_options['main-search-c4'])){echo sanitize_text_field($foxtool_options['main-search-c4']);} ?>"/>
	<label class="ft-right-text"><?php _e('Text color', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Change the color of the search box to your preferences', 'foxtool'); ?></p>
	<?php 
	$args = array(
    'public'   => true,
	);
	$post_types = get_post_types($args, 'objects'); 
	foreach ($post_types as $post_type_object) {
		if ($post_type_object->name == 'attachment' || $post_type_object->name == 'page') {
			continue;
		}
		?>
		<label class="nut-switch">
			<input type="checkbox" name="foxtool_settings[main-search-posttype][]" value="<?php echo $post_type_object->name; ?>" <?php if (isset($foxtool_options['main-search-posttype']) && in_array($post_type_object->name, $foxtool_options['main-search-posttype'])) echo 'checked="checked"'; ?> />
			<span class="slider"></span>
		</label>
		<label class="ft-label-right"><?php echo $post_type_object->labels->name; ?></label>
		</p>
		<?php
	}
	?>
	<div class="save-json">
	<a href="javascript:void(0)" id="save-json"><i class="fa-regular fa-database"></i> <?php _e('Generate data', 'foxtool'); ?></a>
	<a href="javascript:void(0)" id="delete-json-folder"><i class="fa-regular fa-trash"></i> <?php _e('Delete data', 'foxtool'); ?></a>
	<div id="tb-json"></div>
	<div class="tb-doi" id="tb-doi" style="display:none"><img src="<?php echo esc_url(FOXTOOL_URL . 'img/load4.gif'); ?>" /> <span id="starprocess"></span></div>
	<script>
	jQuery(document).ready(function($) {
	        $('input[name="foxtool_settings[main-search1]"]').change(function() {
	            if ($(this).is(':checked')) {
	            	$('#tb-doi-sogiay').show();
	                var $targetCheckbox = $('input[name="foxtool_settings[main-search-posttype][]"]').prop('checked', false);
	                if ($targetCheckbox.length > 0) {
	                    $targetCheckbox.prop('checked', true);
	                    var countdown = 3;
			                var countdownInterval = setInterval(function() {
			                    $('#sogiay').text(countdown);
			                    countdown--;
			                    if (countdown < 0) {
			                        clearInterval(countdownInterval);
			                        // $('#save-json').trigger('click');
			                        $('#tb-doi-sogiay').hide();
			                        // $('html, body').animate({
			                        //    scrollTop: $('#save-json').offset().top
			                        // }, 1000);
			                    }
			                }, 1000);
	                }
	            }else{
                	$('input[name="foxtool_settings[main-search-posttype][]"]').prop('checked', false);
	            }
	        });
	});
	jQuery(document).ready(function($){
		jQuery(document).ready(function($){
        $('#save-json').on('click', function() {
            $('#tb-doi').show();
            var ajax_nonce = '<?php echo wp_create_nonce('foxtool_search_get'); ?>';
            var page = 1;
            var sopost = 0;
            function callAjax() {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {
                        action: 'foxtool_json_get',
                        security: ajax_nonce,
                        page: page
                    },
                    success: function(response) {
                        var jsonResponse = JSON.parse(response);
                        if (jsonResponse.page === -1) {
                            $('#loadbarprocess').html('<span><?php _e("Number of data completed: '+sopost+'", "foxtool"); ?></span>');
                            $('#tb-doi').hide();
                        } else {
                        	sopost = jsonResponse.count;
	                        var html = '<span><?php _e("Please wait: '+sopost+'", "foxtool"); ?></span>';
	                    	$('#starprocess').html(html);
                            page = jsonResponse.page;
                            callAjax();
                        }
                    }
                });
            }
            callAjax();
        });
		$('#delete-json-folder').on('click', function() {
			var ajax_nonce = '<?php echo wp_create_nonce('foxtool_search_del'); ?>'; 
			function callAjax() {
				$.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'foxtool_json_del', 
						security: ajax_nonce
					},
					success: function(response) {
						$('#loadbarprocess').html('<span><?php _e("Deletion successful", "foxtool"); ?></span>'); 
					}
				});
			}
			callAjax();
		});	
    });	
	});
	</script>
	<div id="loadbarprocess"></div>
	</div>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Configure the options and create search data. If you want to refresh, you can delete the search data and recreate it. After enabling quick search and completing data creation, a quick search popup will appear when you enter the search box on the website', 'foxtool'); ?></p>
</div>	