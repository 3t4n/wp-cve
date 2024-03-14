<?php

function head_txt( $id, $val = '', $type = '' ) {
	
			$getdata = array( 'headertext', 'maincontent', 'headertextfont', 'headertextcol', 'maincontentfont', 'maincontentcol' );
			$data = easynotify_loader( $getdata, $id, $val, $type );

				echo '
				<div class="enoty-wrapper">
				'.( trim ( $data['headertext'] != '' ) && trim ( $data['headertext'] != 'none' ) ? '<div class="noty-text-header bottom-shadow"><h1 style="font-size:'.$data['headertextfont'].'; color:'.$data['headertextcol'].';">'.$data['headertext'].'</h1></div>' : '' ).'
				<div class="noty-content-wrap">
				<div class="noty-content-center">
				<div class="noty-popup-content" style="font-size:'.$data['maincontentfont'].' !important; color:'.$data['maincontentcol'].' !important;">'.wpautop($data['maincontent']).'</div>';

				echo '</div></div></div>';

}

?>