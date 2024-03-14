<?php 
defined('ABSPATH') or die("No direct script access!");

if ( ! defined( 'OCOPD_TPL_DIR1' ) ) {
	define('OCOPD_TPL_DIR1', QCOPD_DIR1 . "/views");
}

if ( ! defined( 'OCOPD_TPL_URL1' ) ) {
	define('OCOPD_TPL_URL1', QCOPD_URL1 . "/views");
}

//Shortcode area
add_shortcode('qcld-ilist', 'qcilist_textlist_full_shortcode');
if(!function_exists('qcilist_textlist_full_shortcode')){
function qcilist_textlist_full_shortcode( $atts = array() ){
	
	ob_start();

	//Defaults & Set Parameters
	extract( shortcode_atts(
		array(
			'orderby' 			=> 'title',
			'order' 			=> 'ASC',
			'mode' 				=> 'one',
			'list_id' 			=> '',
			'upvote'			=> 'off',
			'column' 			=> '1',
			'style' 			=> 'simple',
			'list_img' 			=> 'true',
			'item_orderby' 		=> "",
			'embed_option' 		=> 0,
			'category' 			=> "",
			'capture' 			=> "",
			'disable_lightbox' 	=> 'false'
		), $atts
	));

	if($column>4){
		$column = 4;
	}
	
	$shortcodeAtts = array(
		'orderby' 		=> $orderby,
		'order' 		=> $order,
		'mode' 			=> $mode,
		'list_id'		=> $list_id,
		'upvote'		=> $upvote,
		'column' 		=> $column,
		'style' 		=> $style,
		'list_img' 		=> $list_img,
		'item_orderby' 	=> $item_orderby,
		'category' 		=> $category,
		'capture' 		=> $capture,
		
	);
	//print_r($shortcodeAtts);exit;
	$limit = -1;

	if( $mode == 'one' )
	{
		$limit = 1;	
	}

	//Query Parameters
	$list_args = array(
		'post_type' 		=> 'ilist',
		'orderby' 			=> $orderby,
		'order' 			=> $order,
		'posts_per_page' 	=> $limit,
	);

	if( $list_id != "" && $mode == 'one' )
	{
		$list_args = array_merge($list_args, array( 'p' => $list_id ));
	}
	
	if( $category != "" )
	{
		$taxArray = array(
			array(
				'taxonomy' => 'sl_cat',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
		
		$list_args = array_merge($list_args, array( 'tax_query' => $taxArray ));
		
	}
	
	// The Query
	$list_query = new WP_Query( $list_args );

	// The Loop
	if ( $list_query->have_posts() ) 
	{

		

		$listId = 1;

		while ( $list_query->have_posts() ) 
		{
			$list_query->the_post();
			
			
			//$lists = get_cmb_group('qcopd_list_item01');
			//Finding Meta Post type.
			$ilist_chart = get_post_meta( get_the_ID(), 'ilist_chart' );
			$show_chart_position = get_post_meta( get_the_ID(), 'show_chart_position' );
			$sl_meta_post_type = get_post_meta( get_the_ID(), 'post_type_radio_sl' );
			//Finding template Defined from user_error
			$template_code = '';
			if($sl_meta_post_type[0]=='textlist'){
				$sl_get_template = get_post_meta( get_the_ID(), 'qcld_sl_template_text' );
				$template_code = isset( $sl_get_template[0] ) ? $sl_get_template[0] : '';
			}elseif($sl_meta_post_type[0]=='imagelist'){
				$sl_get_template = get_post_meta( get_the_ID(), 'qcld_sl_template_image' );
				$template_code = isset( $sl_get_template[0] ) ? $sl_get_template[0] : '';
			}else{
				$sl_get_template = get_post_meta( get_the_ID(), 'qcld_sl_template_mix' );
				$template_code = isset( $sl_get_template[0] ) ? $sl_get_template[0] : '';
			}
			//Getting Group Field Data.
			$lists = get_post_meta( get_the_ID(), 'qcld_text_group' );
			// Check Template Exists Or Not.
			?>
				<!-- This site is using Infographic Maker iList WordPress Plugin - https://www.quantumcloud.com/products/ -->
			<?php
			if(file_exists(OCOPD_TPL_DIR1 . "/$template_code/template.php")){



				if($capture == 'true'){

					echo '<div class="ilist-capture-wrap"><button class="ilist-download-btn" title="'.esc_html('Click to take screenshot ( Pro Feature ) ').'" >Save as Image</button><img src="" id="ilist-created-element"/> <a href='.esc_url("https://www.quantumcloud.com/products/infographic-maker-ilist/").' target="_blank" style="color:indianred"> ( Pro ) </a> </div>';

					echo '<style>body{margin-top:-32px!important}.iList-outer-wrapper{padding:15px!important}::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 6px rgba(0,0,0,.3);background-color:#f5f5f5}::-webkit-scrollbar{width:8px;background-color:#f5f5f5}::-webkit-scrollbar-thumb{background-color:#626262;border:2px solid #555}</style>';
					
					echo '<div id="iList-outer-wrapper"  class="iList-outer-wrapper">';

					wp_enqueue_script( 'ilist-chart-jssss', QCOPD_ASSETS_URL1 . '/js/Chart.js');
				}

					require ( OCOPD_TPL_DIR1 . "/$template_code/template.php" );

				if($capture == 'true'){
					echo '</div>';
				}

				wp_reset_query();
			}else{
				//Template Not Exists.
				echo esc_html('Oops! Template File Not Exists!', 'iList' );
			}
			


			$listId++;
		}	//End While
?>
<script type="text/javascript">
jQuery(document).ready(function($){

	<?php if($disable_lightbox=="true"): ?>
	$('a').each(function(e){
		if(typeof $(this).attr('data-lightbox') != 'undefined'){
			$(this).attr('href',"#");
			$(this).removeAttr('data-lightbox');
			$(this).addClass('sldclickdisable');
			$(this).css('cursor','default');
		}		
	})
	<?php endif; ?>
	
})
</script>
<?php
		
	}
	else
	{
		echo "<div><p>".esc_html('No directory items was found.', 'iList')."</p></div>";
	}
	//End IF

    $content = ob_get_clean();
    return $content;
}
}

if(!function_exists('ilist_custom_sort_by_tpl_upvotes')){
	function ilist_custom_sort_by_tpl_upvotes($a, $b){
		//return $a['sl_thumbs_up'] * 1 < $b['sl_thumbs_up'] * 1;

		$a_sl_thumbs_up = isset($a['sl_thumbs_up']) && !empty( $a['sl_thumbs_up'] ) ? $a['sl_thumbs_up'] : 0;
		$b_sl_thumbs_up = isset($b['sl_thumbs_up']) && !empty( $b['sl_thumbs_up'] ) ? $b['sl_thumbs_up'] : 0;

		if( $a_sl_thumbs_up === $b_sl_thumbs_up ){
			return 0;
		}

		return $a_sl_thumbs_up < $b_sl_thumbs_up  ? 1 : -1;
		
	}
}