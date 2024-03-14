<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;

class ADMINZ_Helper_Woocommerce_Gallery{
	function __construct() {
		add_filter('woocommerce_gallery_image_size',function (){
			return array_values(wc_get_image_size( 'thumbnail' ));
		});
		// gallery height
		add_filter('woocommerce_gallery_image_size',function ($size){	
			return 'woocommerce_thumbnail';
		});
		add_filter('woocommerce_gallery_image_html_attachment_image_params',function($arr){	
			$arr['class'] .= "skip-lazy zzzz_fixed_gallery_size_";
			return $arr;
		},10,1);
		add_action('wp_footer',function(){
			$size = apply_filters( 'woocommerce_gallery_image_size', 'woocommerce_single' );
			$sizes= wc_get_image_size($size);
			$ratio = $sizes['width'] ."/".$sizes['height'];
			?>
			<style type="text/css">
				.zzzz_fixed_gallery_size_{
					aspect-ratio: <?php echo $ratio; ?>;
				}
			</style>
			<?php
		});
	}
}