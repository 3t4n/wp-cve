<?php if ( ! defined( 'ABSPATH' ) ) exit; wp_enqueue_media();?>
	<div class="fb-add-new-div">
		<input type="hidden" name="flipbox_builder_Flipbox_save_data_action" value="flipbox_builder_Flipbox_save_data_action" />
		<ul class="clearfix" id="Flipbox_panel">
			<?php

			$i=1;
			$data = unserialize(get_post_meta( $post->ID, 'flipbox_builder_Flipbox_data', true));
			$TotalCount =  get_post_meta( $post->ID, 'flipbox_builder_Flipbox_count', true );
			if($TotalCount) 
			{
				if($TotalCount!=-1)
				{
					foreach($data as $single_data)
					{
						 $front_title = $single_data['front_title'];
						 $front_icon = $single_data['front_icon'];
						 $back_description = $single_data['back_description'];
						 $btntext = $single_data['btntext'];
						 $back_link = $single_data['back_link'];
						 $flip_image_field = $single_data['flip_image_field'];					
						 $flip_image_id = $single_data['flip_image_id'];
						?>
				<li class="Flipbox_ac-panel Flipbox_single_acc_box"> <span class="flipbox_ac_label "><?php _e('Title','flipbox-builder-text-domain'); ?></span>
					<input type="text" name="front_title[]" placeholder="Enter Title Here" value="<?php echo esc_attr($front_title); ?>" class="flipbox_ac_label_text " /> <span class="flipbox_ac_label d10if flip_icon_class"><?php _e('Icon','flipbox-builder-text-domain'); ?></span>						
					<div class="d10if flip_icon_class">
					<input  name="front_icon[]" class="icon-class-input" value="<?php echo esc_attr($front_icon); ?>" type="text" readonly="readonly" class="flipbox_ac_label_text" /><span class="demo-icon <?php echo esc_attr($front_icon); ?>"></span> <button type="button" class="fb-btn-iconpicker picker-button"><?php esc_html_e('Pick an Icon','flipbox-builder-text-domain'); ?></button> </div>					
					<div id="iconPicker" class="modal fade">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header">				
					<h4 class="modal-title h4"><?php esc_html_e('Icon Picker','flipbox-builder-text-domain'); ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
					<div>
					<ul class="icon-picker-list">
					<li>
						<a data-class="{{item}} {{activeState}}" data-index="{{index}}">
							<span class="{{item}}"></span>
							<span class="name-class">{{item}}</span>
						</a>
					</li>
					</ul>
					</div>
					</div>
					<div class="modal-footer">
					<button type="button" id="change-icon" class="btn btn-success">
					<span class="fa fa-check-circle-o"></span>
					<?php esc_html_e('Use Selected Icon','flipbox-builder-text-domain'); ?>
					</button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Cancel','flipbox-builder-text-domain'); ?></button>
					</div>
					</div>
					</div>
					</div>
					<span class="flipbox_ac_label"><?php _e('Description','flipbox-builder-text-domain'); ?></span>
					<textarea name="back_description[]" placeholder="Enter Description Here" class="flipbox_ac_label_text"><?php echo esc_attr($back_description); ?></textarea>
					 <span style="display:none;" class="flipbox_ac_label d10f flip_image_class"><?php _e('Upload Image','flipbox-builder-text-domain'); ?></span>
					<div style="display:none;" class="myplugin-upload-wrap d10f flip_image_class"> <img class="upimgflip" src="<?php echo esc_url($flip_image_field); ?>" width="250" /></div>
					 <input class="inimg d10f flip_image_class" type="hidden" name="flip_image_field[]" value="<?php echo esc_attr($flip_image_field);?>" />
					<button onclick="flipbox_media_upload(this)" type="button" class="fb-btn-success wpm_choosen_style d10f flip_image_class flip_upload_image_button"><?php esc_html_e('Upload Image','flipbox-builder-text-domain'); ?></button>
					<input type="hidden" name="flip_image_id[]" value="<?php echo esc_attr($flip_image_id); ?>" class="image-id-access d10f flip_image_class" /><br/>					
					<span style="" class="flipbox_ac_label d12f flip_button_class"><?php _e('Button','flipbox-builder-text-domain'); ?></span>
					<input style="" type="text" name="btntext[]" placeholder="Enter Button Text Here" class="flipbox_ac_label_text d12f flip_button_class" value="<?php echo esc_attr($btntext); ?>" /> <span style="" class="flipbox_ac_label d12f flip_button_class"><?php esc_html_e('Add Your Link Or Read More Link Here (With http://or https://)','flipbox-builder-text-domain'); ?></span>
					<input style="" type="text" name="back_link[]" placeholder="Enter Link Here" class="flipbox_ac_label_text d12f flip_button_class" value="<?php echo esc_attr($back_link); ?>" /> <a class="remove_flip_button" href="#delete"><i class="fa fa-trash-o"></i></a> </li>
				<?php

				}
			}
			
		}
		else{
			for($i=0;$i<3;$i++){
			?>
					<li class="Flipbox_ac-panel Flipbox_single_acc_box"> <span class="flipbox_ac_label"><?php _e('Title','flipbox-builder-text-domain'); ?></span>
						<input type="text" id="front_title[]" name="front_title[]" placeholder="Enter Title Here" class="flipbox_ac_label_text" value="Sample Title Here" /> <span class="flipbox_ac_label d10if flip_icon_class"><?php _e('Icon','flipbox-builder-text-domain'); ?></span>
						<div class="d10if flip_icon_class">
						<input  name="front_icon[]" class="icon-class-input" value="fa fa-laptop" type="text" readonly="readonly" class="flipbox_ac_label_text" /><span class="demo-icon fa fa-laptop"></span> <button type="button" class="fb-btn-iconpicker picker-button"><?php esc_html_e('Pick an Icon','flipbox-builder-text-domain'); ?></button> </div>
						<div id="iconPicker" class="modal fade">
						<div class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
				
						<h4 class="modal-title"><?php esc_html_e('Icon Picker','flipbox-builder-text-domain'); ?></h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
						<div>
						<ul class="icon-picker-list">
						<li>
							<a data-class="{{item}} {{activeState}}" data-index="{{index}}">
								<span class="{{item}}"></span>
								<span class="name-class">{{item}}</span>
							</a>
						</li>
						</ul>
						</div>
						</div>
						<div class="modal-footer">
						<button type="button" id="change-icon" class="btn btn-success">
						<span class="fa fa-check-circle-o"></span>
						<?php esc_html_e('Use Selected Icon','flipbox-builder-text-domain'); ?>
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Cancel','flipbox-builder-text-domain'); ?></button>
						</div>
						</div>
						</div>
						</div>						
						<span class="flipbox_ac_label"><?php _e('Description','flipbox-builder-text-domain'); ?></span>
						<textarea name="back_description[]" placeholder="Enter Description Here" class="flipbox_ac_label_text"><?php esc_html_e('Sample Description Here','flipbox-builder-text-domain'); ?></textarea> <span style="display:none;" class="flipbox_ac_label d10f flip_image_class"><?php _e('Upload Image','flipbox-builder-text-domain'); ?></span>
						<div style="display:none;" class="myplugin-upload-wrap d10f flip_image_class"> <img class="upimgflip" src="<?php echo esc_url(FLIPBOXBUILDER_URL.'admin/images/flipbox-demo-3.jpg') ?>" width="250" />
						</div>	
						<input class="inimg d10f flip_image_class" type="hidden" name="flip_image_field[]" value="<?php echo esc_url(FLIPBOXBUILDER_URL.'admin/images/flipbox-demo-3.jpg') ?>" /> 
						<button onclick="flipbox_media_upload(this)" type="button" class="fb-btn-success wpm_choosen_style d10f flip_image_class flip_upload_image_button"><?php esc_html_e('Upload Image','flipbox-builder-text-domain'); ?></button>
						<input type="hidden" name="flip_image_id[]" value="0" class="image-id-access d10f flip_image_class"/><br/>						
						<span style="" class="flipbox_ac_label d12f flip_button_class"><?php _e('Button','flipbox-builder-text-domain'); ?></span>
						<input style="" type="text" id="btntext[]" name="btntext[]" placeholder="Enter Button Text Here" class="flipbox_ac_label_text d12f flip_button_class" value="Click Here" /> <span style="" class="flipbox_ac_label d12f flip_button_class"><?php esc_html_e('Add Your Link Or Read More Link Here (With http://or https://)','flipbox-builder-text-domain'); ?></span>
						<input style="" type="text" id="back_link[]" name="back_link[]" placeholder="Enter Link Here" class="flipbox_ac_label_text d12f flip_button_class" value="https://www.google.com" /> <a class="remove_flip_button" href="#delete"><i class="fa fa-trash-o"></i></a> </li>
					<?php

		
			}
		}?>
		</ul>
	</div> 
	<a class="Flipbox_ac-panel add_flipbox_ac_new" id="add_new_flipbox_ac" onclick="add_new_flipbox(dd)">
	 <?php _e('Add New FlipBox','flipbox-builder-text-domain'); ?>
	</a>
	<a style="" class="add-new-delete-all add_flipbox_ac_new delete_all_acc" id="delete_all_acc"> <i style="" class="fa fa-trash-o"></i> <span style=""><?php _e('Delete All','flipbox-builder-text-domain'); ?></span> </a>
	<div class="clear-left-div"></div>
	<?php require('add-flipbox-js-footer.php'); ?>
<script>
function flipbox_media_upload(el) {
	uploadID = jQuery(el).prev('input');
	showImg = jQuery(el).prev('input').prev('div').children('img');
	imageid = jQuery(el).next('input');
	media_uploader = wp.media({
		frame: "post",
		state: "insert",
		library: {
			type: 'image' // limits the frame to show only images
		},
		multiple: false
	});
	media_uploader.on("insert", function() {
		var json = media_uploader.state().get("selection").first().toJSON();
		var image_url = json.url;
		jQuery(imageid).val(json.id);
		//alert(image_url);
		jQuery(uploadID).val(image_url);
		jQuery(showImg).attr('src', image_url);
		var image_caption = json.caption;
		var image_title = json.title;
	});
	media_uploader.open();
}

function flipbox_media_delete(el) {
	var answer = confirm('Are you sure?');
	uploadID = jQuery(el).prev('input');
	showImg = jQuery(el).parent().prev('img');
	if(answer == true) {
		jQuery(showImg).attr('src', '');
		jQuery(uploadID).attr('value', '');
	}
}
</script>