<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php 
	
	
	global $woo_cs_data, $woo_cs_url, $woo_cs_pro, $woo_cs_premium_link, $woo_cs_all_plugins, $woo_cs_plugins_activated;

	$styles_working = false;



?>	

<div class="wrap woo_cs_settings">

<div id="icon-options-general"><br></div><h2><i class="fas fa-road"></i>&nbsp;<?php echo trim(str_replace('Woo', '', $woo_cs_data['Name'])); ?> (<?php echo $woo_cs_data['Version']; ?>)<?php echo ($woo_cs_pro?' '.'Pro':''); ?> - <?php _e('Settings', 'woo-coming-soon'); ?>


</h2>

<hr />
<br />






<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<?php wp_nonce_field( 'woo_cs_nonce_action', 'woo_cs_nonce_field' ); ?>

    <div class="alert alert-primary" role="alert">
		<?php _e('Do you want to TURN ON a Coming Soon page for your website?', 'woo-coming-soon'); ?>
    </div>
	
 <?php
                                    //pree($cs_all_plugins);

                                    if(!array_key_exists('chameleon/index.php', $woo_cs_all_plugins)){
                                        ?>
                                        <a href="plugin-install.php?s=chameleon&tab=search&type=term" class="btn btn-lg btn-danger float-left" target="_blank"><?php _e('Install Chameleon for Styles','woo-coming-soon'); ?></a>
                                        <?php
										echo woo_cs_demo_styles();
                                    }elseif(!in_array('chameleon/index.php', $woo_cs_plugins_activated)){
                                        ?>
                                        <a href="plugins.php?plugin_status=inactive&s=chameleon" class="btn btn-lg btn-danger float-left" target="_blank"><?php _e('Activate Chameleon for Styles','woo-coming-soon'); ?></a>
                                        
                                        <?php
										echo woo_cs_demo_styles();
                                    }else{
									$styles_working = true;
                                    global $wpc_assets_loaded, $wpc_dir, $wpc_url, $wpc_supported;
                                    
                                    $wp_chameleon = get_option( 'wp_chameleon', array());
                                    $wp_chameleon_background = get_option( 'wp_chameleon_background', array());
                                    $wp_chameleon_background = (is_array($wp_chameleon_background)?$wp_chameleon_background:array());
                                    $short = 'cs';
                                    $cs_backgrounds = (array_key_exists($short, $wp_chameleon_background) ? $wp_chameleon_background[$short] : array());
                                    $selected_background_url = '';
                                    
                                    //pree($wpc_assets_loaded['ap']);
                                    //pree(get_option('cs_style'));
                                    //pree($wp_chameleon['ap']);
                                    if(isset($wpc_assets_loaded[$short]) && !empty($wpc_assets_loaded[$short])){
                                        ksort($wpc_assets_loaded[$short]);
                                        //pree($wp_chameleon);
                                        ?>
                                        <select name="cs_style" id="cs_styles" class="wcs_style">
                                            <option value=""><?php _e('Default (LIVE Website)','woo-coming-soon'); ?></option>
                                            <?php
											$remote_demo = 'http://androidbubble.com/html/';
                                            foreach($wpc_assets_loaded[$short] as $style_name=>$style_data){
												
												// pree($short);
												// pree($style_name);
												// pree($style_data);
                                                // pree($wpc_supported[$short]['slug']);

                                                if(function_exists('wpc_previews'))
                                                $wpc_previews = wpc_previews($wpc_supported[$short]['slug'], $style_name, $style_data, $short);
                                                //pree($wpc_supported[$short]['slug']. ' > ' .$style_name. ' > ' .$style_data. ' > ' .$short);

                                                
                                                $selected = ((isset($wp_chameleon[$short][$style_name]) && !empty($wp_chameleon[$short][$style_name]) && current($wp_chameleon[$short][$style_name])=='enabled')?$style_name:'');
                                                $current_background = (array_key_exists($style_name, $cs_backgrounds) ? $cs_backgrounds[$style_name] : '');
                                                $bg_url = ($current_background ? wp_get_attachment_url($current_background) : '');
                                                
                                                if(!$selected_background_url && $selected){
                                                    
                                                    $selected_background_url = $bg_url;
                                                }
												
												$style_data['images']['screenshot'] = isset($style_data['images']['screenshot'])?$style_data['images']['screenshot']:$style_data['images']['thumb'];

                                                ?>
                                                <option data-demo="<?php echo $remote_demo.str_replace(array('.jpg', '.png', '.gif'), '', $style_name); ?>" data-attachment="<?php echo $current_background; ?>" data-bg="<?php echo $bg_url; ?>" data-preview="<?php echo isset($style_data['images']['screenshot'])?str_replace($wpc_dir, $wpc_url, $style_data['images']['screenshot']):''; ?>" value="<?php echo $style_name; ?>" <?php selected( $style_name, $selected ); ?>><?php echo ucwords(str_replace(array('_', '-'), ' ', $style_name)); ?></option>
                                                <?php
                                            }



                                            ?>




                                        </select>


                                        <?php 
                                        

                                        ?>

                                        <div class="cs_preview_wrapper">
                                        
                                            <div class="cs_preview cs_preview_container">
                                                <a href="" target="_blank">
                                                    <img src="" />
                                                </a>
                                            </div>

                                            <div class="cs_preview_container">

                                                <div class="cs_bg_selection" id="cs_bg_media_selection">
                                                    <span class="cs_bg_text">
                                                        <?php _e('Custom BG Image from Media Library (Optional)', 'woo-coming-soon'); ?>
                                                    </span>
                                                    <?php if($selected_background_url): ?>

                                                        <img src="<?php echo $selected_background_url; ?>" />

                                                    <?php endif; ?>
                                                    <span class="cs_bg_remove" title="<?php _e('Remove Image', 'woo-coming-soon'); ?>" <?php echo (!$selected_background_url ? 'style="display:none;"' : ''); ?>>&times;</span>
                                                </div>

                                                <input type="hidden" name="cs_bg_attachment" class="cs_bg_attachment" value="<?php echo $current_background; ?>">
                                            
                                            </div>

                                        </div>

                                        <?php
                                    }
									}
                                    ?>
 	   
            

	

    

    
<?php if($styles_working): ?>
	<p class="submit" style="clear:both;">
    <input type="submit" value="<?php _e('Save Changes', 'woo-coming-soon'); ?>" class="button button-primary" id="submit" name="submit" />
    </p>
<?php endif; ?>    
</form>





</div>


<script type="text/javascript" language="javascript">

jQuery(document).ready(function($) {
	
	setTimeout(function(){
		if($('select.wcs_style').length>0){
			$('select.wcs_style').change();
		}	
	}, 1000);

    function cs_set_bg_image(attachment_id, attachment_url){

        $('input.cs_bg_attachment').val(attachment_id);

        if(attachment_id != '' && attachment_url != ''){

            $('.cs_bg_selection img').remove();
            $('span.cs_bg_remove').show();
            $('.cs_bg_selection').append('<img src="'+attachment_url+'">');

        }else{

            $('.cs_bg_selection img').remove();
            $('span.cs_bg_remove').hide();
        }

    }
	
	$('select.wcs_style').on('change keyup', function(){
		
        var selected_option = $(this).find('option:selected');
        var attachment_id = selected_option.data('attachment');
        var bg = selected_option.data('bg');
		var preview = selected_option.data('preview');
		var demo_url = selected_option.data('demo');
		$('.cs_preview a').attr('href', demo_url);
		$('.cs_preview img').attr('src', preview);
		if($(this).val()==''){
			$('.cs_preview_wrapper').hide();
		}else{
			$('.cs_preview_wrapper').show();
		}

        cs_set_bg_image(attachment_id, bg);
		
	});


    
    


    setTimeout(function(){
		if ($('.cs_bg_selection').length > 0) {
			if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
				$('body').on('click', '.cs_bg_selection', function(e) {
					
                    var clicked_el = $(e.target);

                    if(clicked_el.hasClass('cs_bg_remove')){

                        $('input.cs_bg_attachment').val('');
                        $('.cs_bg_selection img').remove();
                        clicked_el.hide();
                        return;
                    }

                    
					
					e.preventDefault();
					var this_id = $(this).prop('id');

					wp.media.editor.send.attachment = function(props, attachment) {
	
                        cs_set_bg_image(attachment.id, attachment.url);
	
					};
					wp.media.editor.open(this_id);

				});
				
			}		
		}
	}, 1000);

});

</script>
<style type="text/css">
.notice.notice-error,
.error.notice,
.update-nag,
#message {
    display: none;
}
[class^="icon-"], [class*=" icon-"] {
    margin-right: 6px;
}
#wpcontent, #wpfooter {
    background-color: #fff;
}
</style>