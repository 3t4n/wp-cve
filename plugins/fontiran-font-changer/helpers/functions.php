<?php


function cp_var($file, $die = false, $type = 'var_dump') {
	echo '<pre dir="ltr">';
		$type($file);
	echo '</pre>';
	if($die == true) die();
	
}



function fi_get_fontlist () {
	$fontlist = get_option('fontiran');
	return $fontlist;
}

function get_html_select($array, $selected = false) {
		
	$html = '';
		foreach($array as $key => $val) {
			
			
			($selected == $val) ? $sel = 'selected="selected"' : $sel = '';
			$html .= '<option value="'.$val.'" '.$sel.'>'.$val.'</option>';
		}
	echo $html;
		
}



function fi_fonts_name ($output = 'array', $selected = false) {


		$fontlist = fi_get_fontlist();
		
		if (empty($fontlist)) return;
		
		$fonts_name = array();
		for($i=0;$i<count($fontlist);$i++) {
			$fonts_name[$i] = $fontlist[$i]['name'];
		}
		
		// remove duplicate names
		$fonts_name = array_unique($fonts_name);
		
		
		$fonts_name = apply_filters('fontiran_get_fontname', $fonts_name);
		
		
		if($output == 'html') {
			$html = '';
			foreach($fonts_name as $key => $val) {
				
				
				($selected == $val) ? $sel = 'selected="selected"' : $sel = '';
				$html .= '<option value="'.$val.'" '.$sel.'>'.$val.'</option>';
			}
			
			echo $html;
			
		} else {
			return $fonts_name;
		}
		
	}



function fi_create_css($filename = 'fontiran' ,$putdir = null) {

	$font_formats = array(
        "ttf" => "truetype",
        "otf" => "opentype",
		"eot" => "embedded-opentype"
    );


	$font_list = fi_get_fontlist();
	
	if (empty($font_list)) return;
	
	$css = '';
	
	$ext = array_keys($font_list);
	
	$i=0;
	foreach($font_list as $font) { 
		
		$dir = FIRAN_FONTS_URL . '/'.$font['name'].'/';
		$i = 1;
		$len = count($font['files']);
		
		$css .= "@font-face {";
		$css .= "font-family: \"{$font['name']}\";";
		$css .= "font-style: {$font['style']};";
		$css .= "font-weight: {$font['weight']};";
		$css .= 'src:';
		
		foreach($font['files'] as $key=>$path) {

			$query = ($key == 'eot') ? "?#iefix" : "";

			if (isset($font_formats[$key])) {
            	$extension = $font_formats[$key];
        	} else {
				$extension = $key;
			}
			
			// last loop
			$dot = ( $len-$i == 0 ) ? ';' : ','; 
			
			$css .= "url('" . $dir . $path . $query . "') format('" . $extension . "')".$dot;
			
			$i++;
		}
		
		$css .= '}';	
	 }
	 
	if(trim($css) != '') {
		// $uploads = wp_upload_dir();
			
		// if(!wp_enqueue_style( 'fontiran_front', FIRAN_PATH. 'fontiran_front.css', $css  )) {
		if(!file_put_contents(FIRAN_DATA . $filename . '.css', $css)) {
			$error = true;}
	} else {
		if(file_exists(FIRAN_DATA  . $filename . '.css'))	{ unlink(FIRAN_DATA. $putdir . $filename . '.css'); }
	}
	
}


add_action('wp_ajax_fi_add_rule', 'fi_add_rule');
function fi_add_rule() {
	
	if (!isset($_POST['data']) || empty($_POST['data'])) {die('0');};
	
	$n = sanitize_html_class($_POST['data']);
?>






      <input name="fi_ops[<?php echo $n; ?>][subject]" class="choose_element" type="hidden">
      <div class="row">
       <div class="fi-grid fi-label-row firan-fix">
         <div class="col-3">
          <h4>عنوان این کلاس</h4>
         </div>
         <div class="col-9">
          <input name="fi_ops[<?php echo $n; ?>][label]" class="fi-label" type="text">
          <span class="sub-des">این بخش تنها برای دسته بندی دستورات است و در خروجی نمایش داده نمی شود.</span>
         </div>
        </div>
       </div>
      
      
      
      
      <div class="row">
       <div class="fi-grid fi-subject-row firan-fix">
          <div class="col-3">
           <h4>انتخاب کلاس ها</h4>
           </div>
           <div class="col-9">
            <textarea name="fi_ops[<?php echo $n; ?>][subject]" class="show-code" type="text"></textarea>
            <span class="sub-des">تگ ها، کلاس ها و یا آیدی هایی که می خواهید سفارشی سازی کنید را در این بخش وارد کنید. (این بخش بایسته است)</span>
          </div>
       </div>
      </div>
      
      
      
      <div class="row">
       <div class="fi-grid fi-font-row firan-fix">
          <div class="col-3">
           <h4>تنظیمات فونت</h4>
          </div>
          <div class="col-9">
           
           
           
           <div class="row">

			<div class="cols col-4">
            <label>نام فونت</label>
            <select name="fi_ops[<?php echo $n; ?>][font]" class="choose_font_type">
            <option value="0">گزینش فونت</option>
            <?php fi_fonts_name('html'); ?>
           </select>
            </div>




        <div class="cols col-4">
         <label>وزن فونت</label>
         <select name="fi_ops[<?php echo $n; ?>][weight]">
          <option value="0">گزینش کنید</option>
          <?php get_html_select(array('normal','100','200','300','400','500','600','700','800','900','bold')); ?>
         </select>         
        </div>
        
        <div class="cols col-4">
         <label>استایل فونت</label>
         <select name="fi_ops[<?php echo $n; ?>][style]">
          <option value="0">گزینش کنید</option>
          <?php get_html_select(array('normal','italic','oblique')); ?>
         </select>
        </div>

           </div>
           
           

          </div>
           
        </div>
       </div>
       
       
       <div class="row">
        <div class="fi-grid fi-color-row firan-fix">
         <div class="col-3">
          <h4>انتخاب کلاس ها</h4>
         </div>
         <div class="col-9">
          <label>رنگ بندی</label>
          <div class="lcwp_colpick">
           <span class="lcwp_colblock"></span>
           <input name="fi_ops[<?php echo $n; ?>][color]" type="text">
          </div>
         </div>
        

        </div>
       </div>
      

<!----------------------------------------------------------------------------->
		 
<?php
	die();
}


// Ajax Delete
add_action('wp_ajax_fi_delete_webfont', 'fi_delete_webfont_php');
function fi_delete_webfont_php() {
	$font_name = sanitize_text_field($_POST['font_name']); 
	$font_weight = sanitize_text_field( $_POST['font_weight']);
	$font_style = sanitize_text_field($_POST['font_style']);
	
	$font_list = fi_get_fontlist();

	if(!$font_list) return;


	sort($font_list);
	for($i = 0; $i < count($font_list); $i++ ) {

		if($font_list[$i]['name'] == $font_name &&  $font_list[$i]['weight'] == $font_weight &&  $font_list[$i]['style'] == $font_style  ) {
			$success = true;
			unset($font_list[$i]);
			break;		
		}
	}

	if(isset($success)) {
		ksort($font_list);
		$font_list = array_values($font_list);
		update_option('fontiran', $font_list);
		fi_create_css();
		echo 'success';
	} else {
		echo 'error';
	}
	
	die();	
}

