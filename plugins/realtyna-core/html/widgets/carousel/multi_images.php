<?php
/**
 * Overriden file of WPL Plugin to make it customized for Sesame theme. 
 * This view is showing Property listing Carousel. The featured properties in the homepage of Demo.
 * @author Realtyna Inc.
 */
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** import js codes **/
$this->_wpl_import('widgets.carousel.scripts.js', true, true);

$image_height = isset($this->instance['data']['image_height']) ? $this->instance['data']['image_height'] : 220;
$images_per_page = isset($this->instance['data']['images_per_page']) ? $this->instance['data']['images_per_page'] : 3;
$image_width = ( 1170 - ($images_per_page * 20)) / $images_per_page ;
$auto_play = isset($this->instance['data']['auto_play']) ? $this->instance['data']['auto_play'] : false;
$slide_interval = isset($this->instance['data']['slide_interval']) ? $this->instance['data']['slide_interval'] : 3000;
$show_tags = isset($this->instance['data']['show_tags']) ? $this->instance['data']['show_tags'] : false;
$wpl_rtl = is_rtl() ? 'true' : 'false';
$this->lazyload = isset($this->instance['data']['lazy_load']) ? $this->instance['data']['lazy_load'] : false;
$lazy_load = $this->lazyload ? 'owl-lazy' : '';
$src = $this->lazyload ? 'data-src' : 'src';

/** add Layout js **/
$js[] = (object) array('param1'=>'owl.slider', 'param2'=>'packages/owl_slider/owl.carousel.min.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

$images = NULL;
$wpl_properties_count = count($wpl_properties);
$tags = wpl_flex::get_tag_fields((isset($this->instance['data']['kind']) ? $this->instance['data']['kind'] : 0));

foreach($wpl_properties as $key=>$gallery)
{
	if(!isset($gallery["items"]["gallery"][0])) continue;

	$params = array();
    $params['image_name'] 		= $gallery["items"]["gallery"][0]->item_name;
    $params['image_parentid'] 	= $gallery["items"]["gallery"][0]->parent_id;
    $params['image_parentkind'] = $gallery["items"]["gallery"][0]->parent_kind;
    $params['image_source'] 	= wpl_global::get_upload_base_path(wpl_property::get_blog_id($params['image_parentid'])).$params['image_parentid'].DS.$params['image_name'];

    $image_title = wpl_property::update_property_title($gallery['raw']);
    $image_location = $gallery['location_text'];
	
    if(isset($gallery['items']['gallery'][0]->item_extra2) and trim($gallery['items']['gallery'][0]->item_extra2) != '') $image_alt = $gallery['items']['gallery'][0]->item_extra2;
    else $image_alt = $gallery['raw']['meta_keywords'];

    $image_description	= $gallery["items"]["gallery"][0]->item_extra2;

    if($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params, 1);
    else $image_url = $gallery["items"]["gallery"][0]->item_extra3;
    
	$tags_str = '';
	if($show_tags)
    {
		$tags_str = '<div class="wpl-listing-tags-wp">
					<div class="wpl-listing-tags-cnt">
						'.$this->tags($tags, $gallery['raw']).'
					</div>
				</div>';
    }
    
    $images .= '
    <div class="wpl-carousel-item" '.$this->itemscope.' '.$this->itemtype_SingleFamilyResidence.'>
        <div class="wpl-carousel-top">
          <img '.$this->itemprop_image.' class="'.$lazy_load.'" src="'.esc_attr($image_url).'" alt="'.esc_attr($image_alt).'" height="'.esc_attr($image_height).'" style="height: '.esc_attr($image_height).'px;" />
           <a '.$this->itemprop_url.' class="more_info" href="'.esc_url($gallery["property_link"]).'">'. esc_html__('More', 'sesame').'</a>
        </div>
        <div class="title">
            <h3 class="main-title" '.$this->itemprop_name.'>'.esc_html($image_title).'</h3>
            <h4 class="address" '.$this->itemprop_address.'>'.esc_html($image_location).'</h4>
            <div class="wpl_prp_listing_icon_box">
                 '.(isset($gallery['materials']['bedrooms']) ? '<div class="bedrooms" content="'.esc_attr($gallery['materials']['bedrooms']['value']).'">'.esc_html($gallery['materials']['bedrooms']['value']).'</div>' : '').'
                 '.(isset($gallery['materials']['bathrooms']) ? '<div class="bathrooms" content="'.esc_attr($gallery['materials']['bathrooms']['value']).'">'.esc_html($gallery['materials']['bathrooms']['value']).'</div>' : '').'
                 '.(isset($gallery['materials']['living_area']) ? '<div class="built_up_area" content="'.esc_attr($gallery['materials']['living_area']['value']).'">'.esc_html($gallery['materials']['living_area']['value']).'</div>' : '').'
                 '.((isset($gallery['materials']['f_150']) and isset($gallery['materials']['f_150']['values'])) ? '<div class="parking" content="'.esc_attr($gallery['materials']['f_150']['values'][0]).'">'.esc_html($gallery['materials']['f_150']['values'][0]).'</div>' : '').'
            </div>
            <div class="wpl-carousel-bot">
                '.(isset($gallery['materials']['price']) ? '<div class="price" content="'.esc_attr($gallery['materials']['price']['value']).'">'.$gallery['materials']['price']['value'].'</div>' : '').'
            </div>
           
        </div>
		'.$tags_str.'
    </div>';
}
?>

<div id="wpl-multi-images-<?php echo esc_attr($this->widget_id); ?>" class="wpl-plugin-owl wpl-carousel-multi-images container <?php if($wpl_properties_count == 1) echo "wpl-carousl-multi-single";  echo esc_attr($this->css_class); ?> ">
    <?php echo $images; ?>
</div>
<?php if($wpl_properties_count > 1): ?>
<script type="text/javascript">
wplj(function()
{
    wplj("#wpl-multi-images-<?php echo $this->widget_id; ?>").owlCarousel(
        {
            items: <?php echo esc_attr($images_per_page); ?>,
            loop: true,
            nav: true,
            autoplay: <?php echo $auto_play ? 'true' : 'false'; ?>,
            autoplayTimeout: <?php echo esc_attr($slide_interval ? $slide_interval : '3000'); ?>,
            autoplayHoverPause: true,
            navText: false,
            dots: false,
            responsiveClass: true,
            lazyLoad: true,
            rtl: <?php echo $wpl_rtl; ?>,
            responsive: {
                0: {
                    items: 1,
                    nav: false,
                    dots: true
                },
                768: {
                    items: 2
                },
                1024:{
                    items: <?php echo esc_attr($images_per_page); ?>
                }
            }
        });
});
</script>
<?php endif; ?>