<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $ap_langs, $ap_langin, $ap_langs_multiple;
$ap_langs = $ap_langs_multiple = array();
$ap_langs['english'] = array();
$ap_langs['russian'] = array('А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','ы','Э', 'Ю','Я' );
$ap_langs['korean'] = array('ㄱ','ㄴ','ㄷ','ㄹ','ㅁ','ㅂ','ㅅ','ㅇ','ㅈ','ㅊ','ㅋ','ㅌ','ㅍ','ㅎ' );
$ap_langs['hungarian'] = array('A','Á','B','C','Cs','D','Dz','Dzs','E','É','F','G','Gy','H','I','Í','J','K','L','Ly','M','N','Ny','O','Ó','Ö','Ő','P','Q','R','S','Sz','T','Ty','U','Ú','Ü','Ű','V','W','X','Y','Z','Zs');
$ap_langs['greek'] = array('α','β','γ','δ','ε','ζ','η','θ','ι','κ','λ','μ','ν','ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω' );
$ap_langs['danish'] =  array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','æ','ø','å');
$ap_langs['arabic'] =  array('ا', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي');
 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	

$ap_langs_multiple['danish'] = array('å'=>array('aa'));

$ap_langs_multiple['arabic'] = array('ا'=>array('ا','أ','إ'));


if(!empty($ap_langs)){
	foreach($ap_langs as $k=>$v){
		$i = substr($k, 0, 2);
		$ap_langin[$i] = $k;
	}
}