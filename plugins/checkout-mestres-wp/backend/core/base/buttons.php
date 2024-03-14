<?php

function cwmpAdminCreateButtons($args){
	$html = '';
	foreach($args['box'] as $box){
		$html .= '<div class="mwp-box">';
			$html .= '<div class="col-1">';
			$html .= '<h3>'.$box['title'].'</h3>';
			$html .= '<p>'.$box['description'].'</p>';
			if(!empty($box['button']['url'])){ $html .= '<a href="'.$box['button']['url'].'" class="action">'.$box['button']['label'].'</a>'; }
			if(!empty($box['help'])){ $html .= '<a href="'.$box['help'].'">Dúvidas? Veja a documentação</a>'; }
			$html .= '</div>';
			$html .= '<ul class="col-2">';
			$html .= '<li style="text-align:right;">';
				if(get_option("cwmp_".$box['button']['id'])=="S"){
					$html .= '<input type="image" class="buttonFuncionalidade" id="'.$box['button']['id'].'" src="'.CWMP_PLUGIN_ADMIN_URL.'assets/images/mwp-ico-on.png" width="80" alt="" style="width:80px !important;display:inline;" />';
				}else{
					$html .= '<input type="image" class="buttonFuncionalidade" id="'.$box['button']['id'].'" src="'.CWMP_PLUGIN_ADMIN_URL.'assets/images/mwp-ico-off.png" width="80" alt="" style="width:80px !important;display:inline;" />';
				}
				$html .= '</li>';
			$html .= '</ul>';
		$html .= '</div>';
	}
	echo $html;
}
function cwmpAdminCreateDiagnostic($args){
	$html = '';
	foreach($args['box'] as $box){
		$html .= '<div class="mwp-box">';
			$html .= '<div class="col-1">';
			$html .= '<h3>'.$box['title'].'</h3>';
			$html .= '<p>'.$box['description'].'</p>';
			if(!empty($box['button']['url'])){ $html .= '<a href="'.$box['button']['url'].'" class="action">'.$box['button']['label'].'</a>'; }
			if(!empty($box['help'])){ $html .= '<a href="'.$box['help'].'">Dúvidas? Veja a documentação</a>'; }
			$html .= '</div>';
			$html .= '<ul class="col-2">';
			$html .= '<li style="text-align:right;">';
				if(get_option("cwmp_".$box['button']['class'])=="red"){
					$html .= '
						<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M25 0C11.2 0 0 11.2 0 25C0 38.8 11.2 50 25 50C38.8 50 50 38.8 50 25C50 11.2 38.8 0 25 0ZM27.5 37.5H22.5V32.5H27.5V37.5ZM27.5 27.5H22.5V12.5H27.5V27.5Z" fill="#D82531"/>
						</svg>
					';
				}else{
					$html .= '
						<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M25 50C38.8068 50 50 38.8068 50 25C50 11.1932 38.8068 0 25 0C11.1932 0 0 11.1932 0 25C0 38.8068 11.1932 50 25 50ZM14.7727 21.7864L21.5909 28.6045L35.2273 14.9682L38.4409 18.1818L21.5909 35.0318L11.5591 25L14.7727 21.7864Z" fill="#2E940A"/>
						</svg>
					';
				}
				$html .= '</li>';
			$html .= '</ul>';
		$html .= '</div>';
	}
	echo $html;
}