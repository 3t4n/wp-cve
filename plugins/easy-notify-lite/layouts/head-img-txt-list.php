<?php

function head_img_txt_list( $id, $val = '', $type = '' ) {
	
			$getdata = array( 'headertext', 'maincontent', 'mainimage', 'bullets', 'bulletstyle', 'headertextfont', 'headertextcol', 'maincontentfont', 'maincontentcol', 'bulletsfont', 'bulletssize', 'bulletsison' );
			$data = easynotify_loader( $getdata, $id, $val, $type );
	
			if ( trim( $data['mainimage'] == '' ) ) {
				$image = plugins_url( '../inc/images/no_image.png' , __FILE__ );
			} else {
				$image = $data['mainimage'];
				}
			
				echo '
				<div class="enoty-wrapper">
				'.( trim ( $data['headertext'] != '' ) && trim ( $data['headertext'] != 'none' ) ? '<div class="noty-text-header bottom-shadow"><h1 style="font-size:'.$data['headertextfont'].'; color:'.$data['headertextcol'].';">'.$data['headertext'].'</h1></div>' : '' ).'
				<div class="noty-content-wrap">
				<div class="noty-popup-image"><img src="'.$image.'"></div>
				<div class="noty-content-right">
				<div class="noty-popup-content" style="font-size:'.$data['maincontentfont'].' !important; color:'.$data['maincontentcol'].' !important;">'.wpautop($data['maincontent']).'</div>
				<div class="noty-popup-bullet-wrap">
				<ul class="noty-popup-bullet">';
				if ( $data['bulletsison'] == 'on' ) {
				foreach($data['bullets'] as $row) {
					echo '<li class="'.$data['bulletstyle'].'" style="display:'.( trim ( $row == '' ) ? 'none' : '' ).'; font-size:'.$data['bulletssize'].' !important; color:'.$data['bulletsfont'].' !important;"><p>'.$row.'</p></li>';
					} }
				echo '</ul></div></div></div></div>';

}

?>