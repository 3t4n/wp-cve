<?php

/*-------------------------------------------------------------------------------*/
/* Lets register our shortcode
/*-------------------------------------------------------------------------------*/ 
function pdfp_cpt_content_func($atts){
	extract( shortcode_atts( array(
		'id' => null,
	), $atts ) ); 

	// $license = get_fpdf_license(true);
	// if($license == 'false'){
	// 	return false;
	// }
	
	$file_url = get_fpdf_post_meta($id,'source', '');
	$height = get_fpdf_post_meta($id, 'height', ['height' => 842, 'unit' => 'px']);
    $width = get_fpdf_post_meta($id, 'width', ['width' => 100, 'unit' => '%']);
    $print = get_fpdf_post_meta($id, 'print', '1') == '1' ? 'vera' : 'false';
    $default_browser = get_fpdf_post_meta($id, 'default_browser', '0');
    $show_filename = get_fpdf_post_meta($id, 'show_filename', '0');
    $show_download_btn = get_fpdf_post_meta($id, 'show_download_btn', '0') == '1' ? 'vera' : 'false';
    $download_btn_text = get_fpdf_post_meta($id, 'download_btn_text', 'Download File');
    $view_fullscreen_btn = get_fpdf_post_meta($id, 'view_fullscreen_btn', '0') == '1' ? 'true' : 'false';
    $fullscreen_btn_text = get_fpdf_post_meta($id, 'fullscreen_btn_text', 'View Full Screen');
    $vfbtntb = get_fpdf_post_meta($id, 'view_fullscreen_btn_target_blank', '0') == '1' ? '_blank' : '_self';
    $protect = get_fpdf_post_meta($id, 'protect', '0');
    $default_browser = get_fpdf_post_meta($id, 'default_browser', '0');
    $disable_alert = get_fpdf_post_meta($id, 'disable_alert', '0');
    $only_pdf = get_fpdf_post_meta($id, 'only_pdf', '0');
    $thumbnail_toggle_menu = get_fpdf_post_meta($id, 'thumbnail_toggle_menu', '1') == '1' ? 'true' : 'false';
    $page = get_fpdf_post_meta($id, 'jump_to', '0');
    $custom_css = get_fpdf_post_meta($id, 'custom_css', '');

	?>
	<?php ob_start(); 

		if($protect){
			$viewer_base_url= PDFPRO_PLUGIN_DIR."pdfjs-new/web/pviewer.php";}else{
			$viewer_base_url= PDFPRO_PLUGIN_DIR."pdfjs-new/web/viewer.php";
		}

		$final_url = $viewer_base_url."?file=".$file_url."&nobaki=".$show_download_btn."&stdono=".$print."&onlypdf=$only_pdf&side=".$thumbnail_toggle_menu."&open=false#page=".$page;

		$options = [
			'baseURL' => $viewer_base_url,
			'disableContent' => (boolean)get_post_meta($id,'pdfp_onei_pp_right_click', true)
		]
	?>
	<?php if(!empty($file_url)){ ?>
	<div class="pdfp_main_wrapper" data-option='<?php echo wp_json_encode($options) ?>' style="width:100%; overflow:hidden;">
		<div class="pdfp_cta_wrapper" style="width:100%; overflow:hidden; float:left;">
		<?php if($show_filename == '1' && !$protect):?>File name :  <?php $name = basename($file_url); echo $name; ?><br/> <?php endif;?>
		<?php if($show_download_btn === 'vera' && !$protect):?><a class="pdfp_download" download href="<?php echo $file_url; ?>"><button class="pdfp_download_btn" style="margin-right:15px;"><?php echo $download_btn_text; ?></button></a><?php endif;//Download button ?>	
		<?php if($view_fullscreen_btn === 'true'):?><a class="pdfp_fullscreen" target="<?php echo $vfbtntb; ?>"  href="<?php echo $final_url; ?>"><button class="pdfp_fullscreen_btn" ><?php echo $fullscreen_btn_text; ?></button></a><?php endif; //View full screen button ?>
		</div>
		<div class="pdfp_frame_wrapper" style="width:100%; overflow:hidden; padding-top:10px;">
		<?php
			if($default_browser === '1' && getBrowser() === 'Edge'){ ?>
				<iframe title="<?php echo esc_attr(basename($file_url)) ?>" class="pdfp_frame" id="fra<?php echo $id; ?>" onload="disable<?php echo $id; ?>();" onMyLoad="disable<?php echo $id; ?>();" width="<?php echo $width['width'].$width['unit']; ?>" height="<?php echo $height['height'].$height['unit'];?>" src="<?php  echo $file_url;?>">
			</iframe>
			<?php }	else { ?>
				<iframe title="<?php echo esc_attr(basename($file_url)) ?>" class="pdfp_frame" id="fra<?php echo $id; ?>" onload="disable<?php echo $id; ?>();" onMyLoad="disable<?php echo $id; ?>();" width="<?php echo $width['width'].$width['unit']; ?>" height="<?php echo $height['height'].$height['unit'];?>" src="<?php  echo $final_url;?>">
			</iframe>
			<?php } ?>
		</div>
	</div>
	<style type="text/css">
	<?php echo pdfp_retrive_option_value('custom_css','pdfp_css','/Custom CSS/'); ?>
	</style>
	<?php if($protect): ?>
	<script type="text/jscript">

	function disable<?php echo $id; ?>()
	{
		document.addEventListener('keydown', function (event) {
			if(event.ctrlKey){
				if(event.key === 's' || event.key === 'a'){
					event.preventDefault();
				}
			}
		});
			document.getElementById("fra<?php echo $id; ?>").contentWindow.document.oncontextmenu = function(){
				<?php if(get_post_meta($id,'pdfp_onei_pp_disable_alert', true)==='on'): ?>
					return false;
				<?php else: ?>
					alert("Please don't copy !");
				<?php endif; ?>
				
				return false;
			};    
	}  

		const blobURL<?php echo esc_html($id); ?> = async () => {
			
			let src = iframe?.src;
			let source = src.match(/\?file=(.+\.pdf)/);
			if(!source){
				return false;
			}
			source = source[1];
			let objUrl = null;
			const result = await fetch(source);
			const blob = await result.blob();
			objUrl = URL.createObjectURL(blob);
			// disable context menu
			document.oncontextmenu = function (e) {
				e.preventDefault();
			};

			// disable ctrl, alt, shift, f12
			document.onkeydown = function (e) {
				if (e.ctrlKey || e.shiftKey || e.altKey || e.key === "F12") {
				return false;
				} else {
				return true;
				}
			};
			source = src.replace(/\?file=(.+\.pdf)/i, `?file=${objUrl}`);
			iframe.src = source;
			return objUrl;
			};
			blobURL<?php echo esc_html($id); ?>();
	
	</script>
	<style type="text/css" media="print">
			body {
				-webkit-user-select: none;
				-moz-user-select: -moz-none;
				-ms-user-select: none;
				user-select: none;
			}
		*{ display: none; }
	</style>
	<?php else: ?>
		<script type="text/jscript">
	function disable<?php echo $id; ?>()
	{
		return; 
	}  
	</script>
	<?php endif;?>	
	<?php } else { echo "<h3>Oops ! You forgot to select a pdf file. </h3>";}?>
	<?php $output = ob_get_clean(); return $output;?>
	<?php
}
// add_shortcode('pdf','pdfp_cpt_content_func');


function get_fpdf_post_meta($id, $key, $default = null, $true = false){
	$meta = metadata_exists( 'post', $id, '_fpdf' ) ? get_post_meta($id, '_fpdf', true) : '';
    if(isset($meta[$key]) && $meta != ''){
        if($true == true){
            if($meta[$key] == '1'){
                return true;
            }else if($meta[$key] == '0'){
                return false;
            }
        }else {
            return $meta[$key];
        }
        
    }else {
        return $default;
    }
}

// Get setting options value
function pdfp_retrive_option_value( $option, $section, $default = '' ){	

	$options = get_option( $section );

	if ( isset( $options[$option] ) ) {
		return $options[$option];
	}

	return $default;
} 