<script>var dd;</script>
<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
$postid=get_the_ID();
$Default_Settings = unserialize(get_option('flipbox_builder_Flipbox_default_Settings'));
$Flipbox_Settings = unserialize(get_post_meta( $postid, 'flipbox_builder_Flipbox_Settings', true));
$option_names1 = array(
        "flip_fliptype" 	 	 => $Default_Settings["flip_fliptype"],
		"flip_itemperrow"    	 => $Default_Settings["flip_itemperrow"],
		"flip_linkopen"      	 => $Default_Settings["flip_linkopen"],
		"flip_icon_size"   		 => $Default_Settings["flip_icon_size"],
		"flipfrontcolor"    	 => $Default_Settings["flipfrontcolor"],
		"flipbackgcolor"     	 => $Default_Settings["flipbackgcolor"],
		
		"flip_title_font"   	 => $Default_Settings["flip_title_font"],
		"fliptitlecolor"     	 => $Default_Settings["fliptitlecolor"],
		"flip_title_fontfamily"  => $Default_Settings["flip_title_fontfamily"],
		"flip_desc_font_size"    => $Default_Settings["flip_desc_font_size"],
		"flipdesccolor"     	 => $Default_Settings["flipdesccolor"],
		"flip_desc_font"     	 => $Default_Settings["flip_desc_font"],
		"flip_custom_css"        => $Default_Settings["flip_custom_css"],
		"flipbuttoncolor"        => $Default_Settings["flipbuttoncolor"],
		"flipbuttonbackccolor"   => $Default_Settings["flipbuttonbackccolor"],
		"templates"      		 => $Default_Settings["templates"], 
		"flipbuttonbackhcolor"   => $Default_Settings["flipbuttonbackhcolor"], 
		"flipbuttonhcolor"       => $Default_Settings["flipbuttonhcolor"], 
		"flipiconcolor"      	 => $Default_Settings["flipiconcolor"], 
		"flipbackcolor"      	 => $Default_Settings["flipbackcolor"], 
		"flip_textalign"         => $Default_Settings["flip_textalign"], 
		"flipbuttonborderccolor" => $Default_Settings["flipbuttonborderccolor"],
		"flipbuttonhbordercolor" => $Default_Settings["flipbuttonhbordercolor"],
        );
		foreach($option_names1 as $option_name1 => $default_value1) {
            if(isset($Flipbox_Settings[$option_name1])) 
                ${"" . $option_name1}  = $Flipbox_Settings[$option_name1];
            else
                ${"" . $option_name1}  = $default_value1;
        }		
		
?>
<input type="hidden" id="flipbox_builder_flipbox_setting_save_action" name="flipbox_builder_flipbox_setting_save_action" value="flipbox_setting_save_action" />
<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item"> <a class="nav-link active" id="Design-tab" data-toggle="tab" href="#Design" role="tab" aria-controls="Design" aria-selected="true"><?php esc_html_e('Design','flipbox-builder-text-domain'); ?></a> </li>
	<li class="nav-item"> <a class="nav-link" id="Flipbox-tab" data-toggle="tab" href="#Flipbox" role="tab" aria-controls="Flipbox" aria-selected="false"><?php esc_html_e('Flipbox','flipbox-builder-text-domain'); ?></a> </li>
	<li class="nav-item"> <a class="nav-link" id="Setting-tab" data-toggle="tab" href="#Setting" role="tab" aria-controls="Setting" aria-selected="false"><?php esc_html_e('Settings','flipbox-builder-text-domain'); ?></a> </li>
	<li class="nav-item"> <a class="nav-link" id="Css-tab" data-toggle="tab" href="#Css" role="tab" aria-controls="Css" aria-selected="false">Custom CSS</a> </li>
	<li class="nav-item"> <a class="nav-link" id="Shortcode-tab" data-toggle="tab" href="#Shortcode" role="tab" aria-controls="Shortcode" aria-selected="false"><?php esc_html_e('Shortcode','flipbox-builder-text-domain'); ?></a> </li>
</ul>
<div class="tab-content" id="myTabContent">
	<div class="tab-pane fade show active" id="Design" role="tabpanel" aria-labelledby="Design-tab">
		<h1 class="fb-6310-template-list fb-h1-design" ><?php esc_html_e('Select Flipbox Layout','flipbox-builder-text-domain'); ?></h1>
		
		<?php require_once(FLIPBOXBUILDER_DIR_PATH."admin/gridlayout.php");?>
	</div>
	<div class="tab-pane fade" id="Flipbox" role="tabpanel" aria-labelledby="Flipbox-tab">
		<h1 class="fb-6310-template-list fb-h1-design" ><?php esc_html_e('Add New Flipbox','flipbox-builder-text-domain'); ?></h1>
		<?php require_once(FLIPBOXBUILDER_DIR_PATH."admin/add-new.php");?>
	</div>
	<div class="tab-pane fade" id="Setting" role="tabpanel" aria-labelledby="Setting-tab">
		<?php require_once(FLIPBOXBUILDER_DIR_PATH."admin/setting.php");?>
	</div>
	<div class="tab-pane fade" id="Css" role="tabpanel" aria-labelledby="Css-tab">
		<h1 class="fb-6310-template-list fb-h1-design" ><?php esc_html_e('Add Custom CSS','flipbox-builder-text-domain'); ?></h1>
		<textarea class="textareafocus custom-css-textarea" name="flip_custom_css" id="flip_custom_css" style=""><?php echo esc_html($flip_custom_css); ?></textarea>		
		<p><?php esc_html_e('Enter Css without ','flipbox-builder-text-domain');?><strong>&lt;style&gt; &lt;/style&gt; </strong><?php esc_html_e(' tag','flipbox-builder-text-domain'); ?></p>
	</div>
	<div class="tab-pane fade" id="Shortcode" role="tabpanel" aria-labelledby="Shortcode-tab">
		<h1 class="fb-6310-template-list fb-h1-design" ><?php esc_html_e('Flipbox Shortcode','flipbox-builder-text-domain'); ?></h1>
		<p><?php _e("Use below shortcode in any Page/Post to publish your Flipbox", 'flipbox-builder-text-domain');?></p>
		<input type="text" value='[Flipbox id=<?php echo get_the_ID();?>]' readonly="readonly" onclick="this.select()" class="shortcode-textfield" /> </div>
		
</div><center>
<button type="button" class="prevtab btnprevnext"><?php esc_html_e('Prev','flipbox-builder-text-domain'); ?></button>
<button type="button" class="nexttab  btnprevnext btnprevnext1"><?php esc_html_e('Next','flipbox-builder-text-domain'); ?></button></center>
<!-- /.container -->
<script type="text/javascript">
/* -------------------------------------------------------------
            bootstrapTabControl
        ------------------------------------------------------------- */
function bootstrapTabControl() {
	var i, items = jQuery('.nav-link'),
		pane = jQuery('.tab-pane');
	// next
	jQuery('.nexttab').on('click', function() {
		for(i = 0; i < items.length; i++) {
			if(jQuery(items[i]).hasClass('active') == true) {
				break;
			}			
		}
		if(i < items.length - 1) {
			// for tab
			jQuery(items[i]).removeClass('active');
			jQuery(items[i + 1]).addClass('active');
			// for pane
			jQuery(pane[i]).removeClass('show active');
			jQuery(pane[i + 1]).addClass('show active');
		}		
	});
	// Prev
	jQuery('.prevtab').on('click', function() {
		for(i = 0; i < items.length; i++) {
			if(jQuery(items[i]).hasClass('active') == true) {
				break;
			}
		}
		if(i != 0) {
			// for tab
			jQuery(items[i]).removeClass('active');
			jQuery(items[i - 1]).addClass('active');
			// for pane
			jQuery(pane[i]).removeClass('show active');
			jQuery(pane[i - 1]).addClass('show active');
		}
	});
}
bootstrapTabControl();
jQuery(document).ready(function($) {
	jQuery('.my-color-field').wpColorPicker();
	select_template(<?php echo esc_attr($templates); ?>);
	
});

function select_template(id) {
	dd = id;
	jQuery(".design_btn").click(function() {
		default_setting_function(dd);
		
	});
	jQuery(".design_btn").attr('style', '');
	jQuery(".fb-6310-template-list").removeClass('btnselected');
	jQuery(".fb-padding-15").removeClass('btnselected1');
	jQuery(".design_btn").prop("disabled", false);
	jQuery(".design_btn").text("Select");
	jQuery(".fb_checked_temp").hide();
	jQuery("#checked_temp_" + id).show();
	jQuery("#templates_btn" + id).attr('disabled', 'disabled');
	jQuery("#templates_btn" + id).attr('style', 'background:#FFF;border-color:#FFF;color:#D110A5;');
	jQuery(".fb-border" + id).addClass('btnselected');
	jQuery(".fb-border-top" + id).addClass('btnselected1');
	jQuery("#templates_btn" + id).text("Selected");
	jQuery("#design" + id).prop("checked", true);
	if(document.getElementById("design" + id).checked == true) {
		if(id == 1 || id == 8 || id == 10) {
			jQuery('.flip_image_class').show();
			jQuery('.flip_icon_class').hide();
		} else {
			jQuery('.flip_image_class').hide();
			jQuery('.flip_icon_class').show();
		}
		if(id == 1) {
			jQuery('.design-1-front').hide();
		} else {
			jQuery('.design-1-front').show();
		}
		// if(id == 2 || id == 3 || id == 7)
		// {
			// jQuery('.iconcolorclass').hide();
		// }
		
		
	}
}

function default_setting_function(id) {
	if(id == 1) {
		
		jQuery('.upimgflip').attr('src', "<?php echo esc_url(FLIPBOXBUILDER_URL.'admin/images/flipbox-demo-3.jpg') ?>");
		jQuery('.inimg').attr('value', "<?php echo esc_url(FLIPBOXBUILDER_URL.'admin/images/flipbox-demo-3.jpg') ?>");
		
		jQuery("#flipfrontcolor").val("#e91e63");
		jQuery("#flipfrontcolor").attr("value", "#e91e63");
		jQuery("#flipfrontcolor").attr("data-default-color", "#e91e63");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#e91e63");
		jQuery("#flipbackgcolor").val("#0274be");
		jQuery("#flipbackgcolor").attr("value", "#0274be");
		jQuery("#flipbackgcolor").attr("data-default-color", "#0274be");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#0274be");
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#000000");
		jQuery("#flipbackcolor").attr("value", "#000000");
		jQuery("#flipbackcolor").attr("data-default-color", "#000000");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#000000");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "42");
		jQuery("#rangevalue1").text("42");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#ffffff");
		jQuery("#flipbuttoncolor").attr("value", "#ffffff");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackccolor").val("#0274be");
		jQuery("#flipbuttonbackccolor").attr("value", "#0274be");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#0274be");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#0274be");
		jQuery("#flipbuttonhcolor").val("#ffffff");
		jQuery("#flipbuttonhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackhcolor").val("#0274be");
		jQuery("#flipbuttonbackhcolor").attr("value", "#0274be");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#0274be");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#0274be");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
	}
	if(id == 2) {
		
		jQuery("#flipfrontcolor").val("#8a35ff");
		jQuery("#flipfrontcolor").attr("value", "#8a35ff");
		jQuery("#flipfrontcolor").attr("data-default-color", "#8a35ff");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#8a35ff");
		jQuery("#flipbackgcolor").val("#502fc6");
		jQuery("#flipbackgcolor").attr("value", "#502fc6");
		jQuery("#flipbackgcolor").attr("data-default-color", "#502fc6");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#502fc6");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#8a35ff");
		jQuery("#flipbackcolor").attr("value", "#8a35ff");
		jQuery("#flipbackcolor").attr("data-default-color", "#8a35ff");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#8a35ff");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "16");
		jQuery("#rangevalue6").text("16");
		jQuery("#range1").attr("value", "40");
		jQuery("#rangevalue1").text("40");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#ffffff");
		jQuery("#flipbuttoncolor").attr("value", "#ffffff");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackccolor").val("#502fc6");
		jQuery("#flipbuttonbackccolor").attr("value", "#502fc6");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#502fc6");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#502fc6");
		jQuery("#flipbuttonhcolor").val("#ffffff");
		jQuery("#flipbuttonhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackhcolor").val("#502fc6");
		jQuery("#flipbuttonbackhcolor").attr("value", "#502fc6");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#502fc6");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#502fc6");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
	}
	if(id == 3) {
		
		jQuery("#flipfrontcolor").val("#c6171d");
		jQuery("#flipfrontcolor").attr("value", "#c6171d");
		jQuery("#flipfrontcolor").attr("data-default-color", "#c6171d");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#c6171d");
		jQuery("#flipbackgcolor").val("#000000");
		jQuery("#flipbackgcolor").attr("value", "#000000");
		jQuery("#flipbackgcolor").attr("data-default-color", "#000000");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#000000");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#c6171d");
		jQuery("#flipbackcolor").attr("value", "#c6171d");
		jQuery("#flipbackcolor").attr("data-default-color", "#c6171d");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#c6171d");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "50");
		jQuery("#rangevalue1").text("50");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#ffffff");
		jQuery("#flipbuttoncolor").attr("value", "#ffffff");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackccolor").val("#000000");
		jQuery("#flipbuttonbackccolor").attr("value", "#000000");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#000000");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#000000");
		jQuery("#flipbuttonhcolor").val("#ffffff");
		jQuery("#flipbuttonhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackhcolor").val("#000000");
		jQuery("#flipbuttonbackhcolor").attr("value", "#000000");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#000000");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#000000");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
	}
	if(id == 4) {
		
		jQuery("#flipfrontcolor").val("#ffffff");
		jQuery("#flipfrontcolor").attr("value", "#ffffff");
		jQuery("#flipfrontcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackgcolor").val("#ffffff");
		jQuery("#flipbackgcolor").attr("value", "#ffffff");
		jQuery("#flipbackgcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#d110a4");
		jQuery("#flipbackcolor").attr("value", "#d110a4");
		jQuery("#flipbackcolor").attr("data-default-color", "#d110a4");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#d110a4");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#000000");
		jQuery("#fliptitlecolor").attr("value", "#000000");
		jQuery("#fliptitlecolor").attr("data-default-color", "#000000");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#000000");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "42");
		jQuery("#rangevalue1").text("42");
		jQuery("#flipdesccolor").val("#000000");
		jQuery("#flipdesccolor").attr("value", "#000000");
		jQuery("#flipdesccolor").attr("data-default-color", "#000000");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#000000");
		jQuery("#flipbuttoncolor").val("#ffffff");
		jQuery("#flipbuttoncolor").attr("value", "#ffffff");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackccolor").val("#d110a4");
		jQuery("#flipbuttonbackccolor").attr("value", "#d110a4");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#d110a4");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#d110a4");
		jQuery("#flipbuttonhcolor").val("#d110a4");
		jQuery("#flipbuttonhcolor").attr("value", "#d110a4");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#d110a4");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#d110a4");
		jQuery("#flipbuttonbackhcolor").val("#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonborderccolor").val("#d110a4");
		jQuery("#flipbuttonborderccolor").attr("value", "#d110a4");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#d110a4");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#d110a4");
		
		jQuery("#flipbuttonhbordercolor").val("#d110a4");
		jQuery("#flipbuttonhbordercolor").attr("value", "#d110a4");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#d110a4");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#d110a4");
	}
	if(id == 5) {
		
		jQuery("#flipfrontcolor").val("#1abc9c");
		jQuery("#flipfrontcolor").attr("value", "#1abc9c");
		jQuery("#flipfrontcolor").attr("data-default-color", "#1abc9c");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#1abc9c");
		jQuery("#flipbackgcolor").val("#0eb5e1");
		jQuery("#flipbackgcolor").attr("value", "#0eb5e1");
		jQuery("#flipbackgcolor").attr("data-default-color", "#0eb5e1");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#0eb5e1");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#00000038");
		jQuery("#flipbackcolor").attr("value", "#00000038");
		jQuery("#flipbackcolor").attr("data-default-color", "#00000038");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#00000038");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "42");
		jQuery("#rangevalue1").text("42");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#0eb5e1");
		jQuery("#flipbuttoncolor").attr("value", "#0eb5e1");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#0eb5e1");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#0eb5e1");
		jQuery("#flipbuttonbackccolor").val("#ffffff");
		jQuery("#flipbuttonbackccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonhcolor").val("#0eb5e1");
		jQuery("#flipbuttonhcolor").attr("value", "#0eb5e1");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#0eb5e1");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#0eb5e1");
		jQuery("#flipbuttonbackhcolor").val("#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
	}
	if(id == 6) {
		
		jQuery("#flipfrontcolor").val("#932dc6");
		jQuery("#flipfrontcolor").attr("value", "#932dc6");
		jQuery("#flipfrontcolor").attr("data-default-color", "#932dc6");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#932dc6");
		jQuery("#flipbackgcolor").val("#932dc6");
		jQuery("#flipbackgcolor").attr("value", "#932dc6");
		jQuery("#flipbackgcolor").attr("data-default-color", "#932dc6");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#932dc6");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#932dc6");
		jQuery("#flipbackcolor").attr("value", "#932dc6");
		jQuery("#flipbackcolor").attr("data-default-color", "#932dc6");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#932dc6");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "42");
		jQuery("#rangevalue1").text("42");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#932dc6");
		jQuery("#flipbuttoncolor").attr("value", "#932dc6");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#932dc6");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#932dc6");
		jQuery("#flipbuttonbackccolor").val("#ffffff");
		jQuery("#flipbuttonbackccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonhcolor").val("#932dc6");
		jQuery("#flipbuttonhcolor").attr("value", "#932dc6");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#932dc6");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#932dc6");
		jQuery("#flipbuttonbackhcolor").val("#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
	}
	if(id == 7) {
		
		jQuery("#flipfrontcolor").val("#0087cd");
		jQuery("#flipfrontcolor").attr("value", "#0087cd");
		jQuery("#flipfrontcolor").attr("data-default-color", "#0087cd");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#0087cd");
		jQuery("#flipbackgcolor").val("#d56500");
		jQuery("#flipbackgcolor").attr("value", "#d56500");
		jQuery("#flipbackgcolor").attr("data-default-color", "#d56500");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#d56500");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#0087cd");
		jQuery("#flipbackcolor").attr("value", "#0087cd");
		jQuery("#flipbackcolor").attr("data-default-color", "#0087cd");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#0087cd");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "50");
		jQuery("#rangevalue1").text("50");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#ffffff");
		jQuery("#flipbuttoncolor").attr("value", "#ffffff");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackccolor").val("#d56500");
		jQuery("#flipbuttonbackccolor").attr("value", "#d56500");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#d56500");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#d56500");
		jQuery("#flipbuttonhcolor").val("#d56500");
		jQuery("#flipbuttonhcolor").attr("value", "#d56500");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#d56500");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#d56500");
		jQuery("#flipbuttonbackhcolor").val("#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
	}
	if(id == 8) {
		
		jQuery('.upimgflip').attr('src', "<?php echo esc_url(FLIPBOXBUILDER_URL.'admin/images/Daniel-1.jpg') ?>");
		jQuery('.inimg').attr('value', "<?php echo esc_url(FLIPBOXBUILDER_URL.'admin/images/Daniel-1.jpg') ?>");
		
		jQuery("#flipfrontcolor").val("#22d594");
		jQuery("#flipfrontcolor").attr("value", "#22d594");
		jQuery("#flipfrontcolor").attr("data-default-color", "#22d594");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#22d594");
		jQuery("#flipbackgcolor").val("#1931a5");
		jQuery("#flipbackgcolor").attr("value", "#1931a5");
		jQuery("#flipbackgcolor").attr("data-default-color", "#1931a5");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#1931a5");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#000000");
		jQuery("#flipbackcolor").attr("value", "#000000");
		jQuery("#flipbackcolor").attr("data-default-color", "#000000");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#000000");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "56");
		jQuery("#rangevalue1").text("56");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#ffffff");
		jQuery("#flipbuttoncolor").attr("value", "#ffffff");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackccolor").val("#1931a5");
		jQuery("#flipbuttonbackccolor").attr("value", "#1931a5");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#1931a5");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#1931a5");
		jQuery("#flipbuttonhcolor").val("#ffffff");
		jQuery("#flipbuttonhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackhcolor").val("#1931a5");
		jQuery("#flipbuttonbackhcolor").attr("value", "#1931a5");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#1931a5");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#1931a5");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
	}
	if(id == 9) {
		
		jQuery("#flipfrontcolor").val("#ca2693");
		jQuery("#flipfrontcolor").attr("value", "#ca2693");
		jQuery("#flipfrontcolor").attr("data-default-color", "#ca2693");
		jQuery("#flipfrontcolor").parents("span").prev("button").attr("style", "background-color:#ca2693");
		jQuery("#flipbackgcolor").val("#ca2693");
		jQuery("#flipbackgcolor").attr("value", "#ca2693");
		jQuery("#flipbackgcolor").attr("data-default-color", "#ca2693");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#ca2693");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#ca2693");
		jQuery("#flipbackcolor").attr("value", "#ca2693");
		jQuery("#flipbackcolor").attr("data-default-color", "#ca2693");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#ca2693");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "42");
		jQuery("#rangevalue1").text("42");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#ca2693");
		jQuery("#flipbuttoncolor").attr("value", "#ca2693");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#ca2693");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#ca2693");
		jQuery("#flipbuttonbackccolor").val("#ffffff");
		jQuery("#flipbuttonbackccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonhcolor").val("#ca2693");
		jQuery("#flipbuttonhcolor").attr("value", "#ca2693");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#ca2693");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#ca2693");
		jQuery("#flipbuttonbackhcolor").val("#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		
	}
	if(id == 10) {
		
		jQuery('.upimgflip').attr('src', "<?php echo esc_url(FLIPBOXBUILDER_URL.'admin/images/team-thumb6.jpg') ?>");
		jQuery('.inimg').attr('value', "<?php echo esc_url(FLIPBOXBUILDER_URL.'admin/images/team-thumb6.jpg') ?>");
		
		jQuery("#flipfrontcolor").val("#ff4e8c");
		jQuery("#flipfrontcolor").attr("value", "#ff4e8c");
		jQuery("#flipfrontcolor").attr("data-default-color", "#ff4e8c");
		jQuery("#flipfrontcolor").parents("span").prev('button').attr("style", "background-color:#ff4e8c;");
		jQuery("#flipbackgcolor").val("#ff4e8c");
		jQuery("#flipbackgcolor").attr("value", "#ff4e8c");
		jQuery("#flipbackgcolor").attr("data-default-color", "#ff4e8c");
		jQuery("#flipbackgcolor").parents("span").prev("button").attr("style", "background-color:#ff4e8c");		
		jQuery("#flipiconcolor").val("#ffffff");
		jQuery("#flipiconcolor").attr("value", "#ffffff");
		jQuery("#flipiconcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipiconcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbackcolor").val("#000000");
		jQuery("#flipbackcolor").attr("value", "#000000");
		jQuery("#flipbackcolor").attr("data-default-color", "#000000");
		jQuery("#flipbackcolor").parents("span").prev("button").attr("style", "background-color:#000000");
		jQuery("#range5").attr("value", "21");
		jQuery("#rangevalue5").text("21");
		jQuery("#fliptitlecolor").val("#ffffff");
		jQuery("#fliptitlecolor").attr("value", "#ffffff");
		jQuery("#fliptitlecolor").attr("data-default-color", "#ffffff");
		jQuery("#fliptitlecolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#range6").attr("value", "14");
		jQuery("#rangevalue6").text("14");
		jQuery("#range1").attr("value", "56");
		jQuery("#rangevalue1").text("56");
		jQuery("#flipdesccolor").val("#ffffff");
		jQuery("#flipdesccolor").attr("value", "#ffffff");
		jQuery("#flipdesccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipdesccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttoncolor").val("#ff4e8c");
		jQuery("#flipbuttoncolor").attr("value", "#ff4e8c");
		jQuery("#flipbuttoncolor").attr("data-default-color", "#ff4e8c");
		jQuery("#flipbuttoncolor").parents("span").prev("button").attr("style", "background-color:#ff4e8c");
		jQuery("#flipbuttonbackccolor").val("#ffffff");
		jQuery("#flipbuttonbackccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonbackccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonbackccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonhcolor").val("#ffffff");
		jQuery("#flipbuttonhcolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhcolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhcolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		jQuery("#flipbuttonbackhcolor").val("#ff4e8c");
		jQuery("#flipbuttonbackhcolor").attr("value", "#ff4e8c");
		jQuery("#flipbuttonbackhcolor").attr("data-default-color", "#ff4e8c");
		jQuery("#flipbuttonbackhcolor").parents("span").prev("button").attr("style", "background-color:#ff4e8c");
		
		jQuery("#flipbuttonborderccolor").val("#ffffff");
		jQuery("#flipbuttonborderccolor").attr("value", "#ffffff");
		jQuery("#flipbuttonborderccolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonborderccolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
		
		jQuery("#flipbuttonhbordercolor").val("#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("value", "#ffffff");
		jQuery("#flipbuttonhbordercolor").attr("data-default-color", "#ffffff");
		jQuery("#flipbuttonhbordercolor").parents("span").prev("button").attr("style", "background-color:#ffffff");
	}
	range1slider();	
	range5slider();
	range6slider();
	colorpicker1();
}
</script>
	
 
 
