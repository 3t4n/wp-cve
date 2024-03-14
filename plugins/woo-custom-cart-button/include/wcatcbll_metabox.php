<?php
//Adding meta box to product page
function catcbll_atc_register_meta_box()
{
	add_meta_box('wcatc_meta_box', esc_html__(__('Product Custom Button Settings', 'catcbll')), 'catcbll_atc_meta_box_callback', 'product', 'advanced', 'high');
}
add_action('add_meta_boxes', 'catcbll_atc_register_meta_box');

// Meta box HTML.
if (!function_exists('catcbll_atc_meta_box_callback')) {
	function catcbll_atc_meta_box_callback($meta_id)
	{
		wp_nonce_field(basename(__FILE__), "wcatcbnl-nonce");
		$catcbll_btn_lbl = get_post_meta($meta_id->ID, '_catcbll_btn_label', true);
		$catcbll_btn_act = get_post_meta($meta_id->ID, '_catcbll_btn_link', true);
		// button label
		if (isset($catcbll_btn_lbl)) {
			$btn_lbl = $catcbll_btn_lbl;
		} else {
			$btn_lbl = "";
		}
		// button url
		if (isset($catcbll_btn_act)) {
			$btn_url = $catcbll_btn_act;
		} else {
			$btn_url = "";
		}

		// if label exist count label
		if ((isset($btn_lbl)) && ($btn_lbl != '')) {
			$btn_lbl_count = count($btn_lbl);
		} else {
			$btn_lbl_count = 0;
		}
		echo '<div class="catcbll_main_sec">';
		echo '<div class="catcbll_left">';
		// if label count >= 1
		if ($btn_lbl_count >= 1) {
			?>
			<div class="catcbll_clone">
			<input type="hidden"  name="catcbll_hidden_counter" id="catcbll_hide_value" value="<?php echo $btn_lbl_count;?>" /><button id="catcbll_add_btn" class="catcbll_add_btn"><?php echo esc_html__(__('Add New', 'catcbll'));?></button>
			</div>
			<?php
			for ($y = 0; $y < $btn_lbl_count; $y++) {
				
				?>
				<div id="main_fld_<?php echo $y;?>" class="main_prd_fld"><div id="wcatcbll_wrap_<?php echo $y;?>" class="wcatcbll_wrap">
				<div class="wcatcbll" id="wcatcbll_prdt_<?php echo $y;?>" >
				<div class="wcatcbll_mr_100"><span class="tgl-indctr" aria-hidden="true"></span><button id="btn_remove_<?php echo $y;?>" class="btn_remove top_prd_btn" data-field="<?php echo $y;?>"><?php echo esc_html__(__('Remove', 'catcbll'));?></button></div>
				</div>
				</div>
				<div class="wcatcbll_content" id="wcatcbll_fld_<?php echo $y;?>"><div class="wcatcbll_p-20">
				<label for="wcatcbll_atc_text" style="width:150px; display:inline-block;"><?php echo esc_html__(__('Label', 'catcbll'));?></label>

				<input type="text" name="wcatcbll_wcatc_atc_text[]" class="title_field" value="<?php echo esc_attr($btn_lbl[$y]);?>" style="width:300px;" placeholder="<?php __('Add To Basket Or Shop Now Or Shop On Amazon', 'catcbll');?>"/>&nbsp;<div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="wcatcblltooltiptext"><?php  echo esc_html__(__('This Text Will Be Shown On The Button Linking To The External Product', 'catcbll'));?></span></div>
				<br><br>
				<label for="title_field" style="width:150px; display:inline-block;"><?php echo esc_html__(__('URL', 'catcbll'));?></label>
				<input type="url" name="wcatcbll_wcatc_atc_action[]" class="title_field" value="<?php echo esc_url_raw($btn_url[$y]);?>" style="width:300px;" placeholder="https://hirewebxperts.com"/>&nbsp;<div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="wcatcblltooltiptext"><?php  echo esc_html__(__('Enter The External URL To The Product', 'catcbll'));?></span></div>
				</div></div>
				</div>
				<?php
			} // end for each
			echo '<div id="wcatcbll_repeat" class="wcatcbll_repeat"></div>'; 
		} else {
			?>
			<div class="catcbll_clone" style="background:#fff">
			<input type="hidden"  name="catcbll_hidden_counter" id="catcbll_hide_value" value="0" /><button id="catcbll_add_btn" class="catcbll_add_btn"><?php echo esc_html__(__('Add New', 'catcbll'));?> </button>
			</div>
			<div id="main_fld_0" class="main_prd_fld"><div id="wcatcbll_wrap_0" class="wcatcbll_wrap">
			<div class="wcatcbll" id="wcatcbll_prdt_0" style="display:none">
			<div class="wcatcbll_mr_100"><span class="tgl-indctr" aria-hidden="true"></span><button class="btn_remove top_prd_btn" data-field="1"><?php echo esc_html__(__('Remove', 'catcbll'));?> </button></div></div>
			</div>
			<div class="wcatcbll_content" id="wcatcbll_fld_0"><div class="wcatcbll_p-20">
			<label for="wcatcbll_atc_text" style="width:150px; display:inline-block;"><?php echo esc_html__(__('Label', 'catcbll'));?> </label>

			<input type="text" name="wcatcbll_wcatc_atc_text[]" class="title_field" value="" style="width:300px;" placeholder="<?php __('Add To Basket Or Shop Now Or Shop On Amazon', 'catcbll');?> "/>&nbsp;<div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="wcatcblltooltiptext"><?php  echo esc_html__(__('This Text Will Be Shown On The Button Linking To The External Product', 'catcbll'));?> </span></div>
			<br><br>
			<label for="title_field" style="width:150px; display:inline-block;"><?php echo esc_html__(__('URL', 'catcbll'));?> </label>

			<input type="url" name="wcatcbll_wcatc_atc_action[]" class="title_field" value="" style="width:300px;" placeholder="https://hirewebxperts.com"/>&nbsp;<div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="wcatcblltooltiptext"><?php  echo esc_html__(__('Enter The External URL To The Product', 'catcbll'));?> </span></div>
			</div></div>

			</div>
			<div id="wcatcbll_repeat" class="wcatcbll_repeat"></div> 
			<?php
		} //end else
		echo '</div>';
		echo '<div class="catcbll_right">';
		echo '<p class="more_infor">'.__('Distinct Information Per Product', 'catcbll').'</p>';
		//$content   = '';
		$moreinfo  = get_post_meta( $meta_id->ID, '_catcbll_more_info', true );
		if(is_array($moreinfo)){
			$content = '';
		}else{
			if(isset($moreinfo) && !empty($moreinfo)){
				$content = $moreinfo;
			}else{
				$content = '';
			}
		}
		$editor_id = 'catcbll_more_info';
		$settings = array(
			'tinymce'       => array(
				'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
				'toolbar2'      => '',
				'toolbar3'      => '',
			),
			'media_buttons' => false,'textarea_name' => 'catcbll_more_info' 
		);
		wp_editor( $content, $editor_id, $settings );
		echo '</div>';
		echo '</div>';
	}
}

/**
 * Insert values in postmeta table.
 */
if (!function_exists('wcatcbnl_wcatc_atc_save_postdata')) {
	function wcatcbnl_wcatc_atc_save_postdata($post_id, $post, $update)
	{
		if (!isset($_POST["wcatcbnl-nonce"]) || !wp_verify_nonce($_POST["wcatcbnl-nonce"], basename(__FILE__)))
			return $post_id;

		if (!current_user_can("edit_post", $post_id))
			return $post_id;

		if (isset($_POST) && !empty($_POST['action']) && $_POST['action'] == 'editpost') {
			if (!empty($_POST['wcatcbll_wcatc_atc_text']) || !empty($_POST['wcatcbll_wcatc_atc_action'])) {

				foreach ($_POST['wcatcbll_wcatc_atc_text'] as $lbl_key => $lbl_val) {
					$btn_lbl_name[] = sanitize_text_field($lbl_val);
				} // end foreach for label

				foreach ($_POST['wcatcbll_wcatc_atc_action'] as $url_key => $url_val) {
					$btn_act_url[] = sanitize_text_field($url_val);
				} //end forech for url				

				update_post_meta($post_id, '_catcbll_btn_label', $btn_lbl_name);
				update_post_meta($post_id, '_catcbll_btn_link', $btn_act_url);
				update_post_meta($post_id, '_catcbll_more_info',	wp_kses_post($_POST['catcbll_more_info']));
			} //end if !empty
		} // end if isset($_POST)
		
	} //end function
} // end !function_exists
add_action('save_post', 'wcatcbnl_wcatc_atc_save_postdata', 10, 3);
