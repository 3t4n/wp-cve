<?php
/**
 * Overriden file of WPL Plugin to make it customized for Sesame theme. 
 * This view is showing Property listing Carousel.
 * @author Realtyna Inc.
 */
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** import js codes **/
$this->_wpl_import('widgets.carousel.scripts.js', true, true);

/** add Layout js **/
$js[] = (object) array('param1'=>'owl.slider', 'param2'=>'packages/owl_slider/owl.carousel.min.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

$description_column = 'field_308';
if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

$image_width = isset($this->instance['data']['image_width']) ? $this->instance['data']['image_width'] : 90;
$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 82;
$show_tags = isset($this->instance['data']['show_tags']) ? $this->instance['data']['show_tags'] : false;

?>
<div class="wpl_carousel_container <?php echo esc_attr($this->css_class); ?>">
	<ul class="simple_list wpl-plugin-owl">
		<?php
        $tags = wpl_flex::get_tag_fields((isset($this->instance['data']['kind']) ? $this->instance['data']['kind'] : 0));
        
		foreach($wpl_properties as $key=>$gallery)
		{
			if(!isset($gallery['items']['gallery'][0])) continue;
			
            $params = array();
            $params['image_name'] 		= $gallery['items']['gallery'][0]->item_name;
            $params['image_parentid'] 	= $gallery['items']['gallery'][0]->parent_id;
            $params['image_parentkind'] = $gallery['items']['gallery'][0]->parent_kind;
            $params['image_source'] 	= wpl_global::get_upload_base_path(wpl_property::get_blog_id($params['image_parentid'])).$params['image_parentid'].DS.$params['image_name'];
            
            $image_title = wpl_property::update_property_title($gallery['raw']);

            $description = stripslashes(strip_tags($gallery['raw'][$description_column]));
            $cut_position = (trim($description) ? strrpos(substr($description, 0, 250), '.', -1) : 0);
            if(!$cut_position) $cut_position = 249;
			
            if(isset($gallery['items']['gallery'][0]->item_extra2) and trim($gallery['items']['gallery'][0]->item_extra2) != '') $image_alt = $gallery['items']['gallery'][0]->item_extra2;
            else $image_alt = $gallery['raw']['meta_keywords'];
            
            $image_description = $gallery["items"]["gallery"][0]->item_extra2;

            if($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
            else $image_url = $gallery["items"]["gallery"][0]->item_extra3;
            
            // Location visibility
            $location_visibility = wpl_property::location_visibility($gallery['items']['gallery'][0]->parent_id, $gallery['items']['gallery'][0]->parent_kind, wpl_users::get_user_membership());
        
            echo '
            <li '.$this->itemscope.' '.$this->itemtype_SingleFamilyResidence.'>
                <div class="left_section">
                    <a '.$this->itemprop_url.' href="'.esc_url($gallery["property_link"]).'">
                        <span style="width:'.esc_attr($image_width).'px;height:'.esc_attr($image_height).'px;">
                            <img '.$this->itemprop_image.' src="'.esc_url($image_url).'" title="'.esc_attr($image_title).'" alt="'.esc_attr($image_alt).'" width="'.esc_attr($image_width).'" height="'.esc_attr($image_height).'" style="width: '.esc_attr($image_width).'px; height: '.esc_attr($image_height).'px;" />
                        </span>
                    </a>
                </div>
                <div class="right_section">
                   <div class="title" '.$this->itemprop_name.'><a class="more_info_title" href="'.esc_url($gallery["property_link"]).'">'.esc_html($image_title).'</a></div>
                    <div class="location" '.$this->itemprop_address.'>'.($location_visibility === true ? esc_html($gallery["location_text"]) : esc_html($location_visibility)).'</div>
                    <div class="description" '.$this->itemprop_description.'>'.esc_html(substr($description, 0, $cut_position + 1)).'</div>
                    <div class="wpl_icon_box">
                        '.(isset($gallery['materials']['bedrooms']) ? '<div class="bedrooms" content="'.esc_attr($gallery['materials']['bedrooms']['value']).'">'.esc_html($gallery['materials']['bedrooms']['value']).'</div>' : '').'
                        '.(isset($gallery['materials']['bathrooms']) ? '<div class="bathrooms" content="'.esc_attr($gallery['materials']['bathrooms']['value']).'">'.esc_html($gallery['materials']['bathrooms']['value']).'</div>' : '').'
                        '.(isset($gallery['materials']['living_area']) ? '<div class="living_area" content="'.esc_attr($gallery['materials']['living_area']['value']).'">'.esc_html($gallery['materials']['living_area']['value']).'</div>' : '').'
                    </div>
                    <div class="right_section_bottom">
                        '.(isset($gallery['materials']['price']) ? '<div class="price" content="'.esc_attr($gallery['materials']['price']['value']).'">'.esc_html($gallery['materials']['price']['value']).'</div>' : '').'
                         <a class="more_info" href="'.esc_url($gallery["property_link"]).'">'.esc_html__('More Info', 'sesame').'</a>
                     </div>
                </div>
               '.($show_tags ? '
				<div class="wpl-listing-tags-wp">
					<div class="wpl-listing-tags-cnt">
						'.$this->tags($tags, $gallery['raw']).'
					</div>
				</div>' : '').'
            </li>';
		}
		?>
	</ul>
</div>
<script type="text/javascript">
    wplj(function() {
        wplj(".wpl_carousel_container .simple_list").owlCarousel({
            items: 1,
            nav: true,
            navText: false,
            dots: false,
            responsiveClass: true
        });
    });
</script>