<?
include("../../../wp-load.php");

switch($_GET['s']):
	case 1:
	//		error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	//	echo "teste";
		//exit;
		$arrv = explode(',', $_GET['v']);
	//	print_r($arrv);
		//function gplt_code($parcd)
		//	{
				$ptype=$arrv[0];
				$dexcp=$arrv[1];
				$orient=$arrv[2];
				$imgo=$arrv[3];
				$ttgo=$arrv[4];
				$dtgo=$arrv[5];
				$dtfrm=$arrv[6];
				$categ=$arrv[7];
				$postnr=$arrv[8];
				$linn=$arrv[9];
				$divwid=$arrv[10];
				$tbwid=$arrv[11];
				$tbhig=$arrv[12];
				$cp=$arrv[13];
				$cs=$arrv[14];
				$lwt=$arrv[15];
				$tte=$arrv[16];
				$sptb=$arrv[17];
				$tgtb=$arrv[18];
				$ordm=$arrv[19];
				$ordf=$arrv[20];
				$metk=$arrv[21];
				$mett=$arrv[22];
				$pgin=$arrv[23];
				$pgnm=$arrv[24];
				//echo $pgnm;
				$pgmx=$arrv[25];				
				$gptb =$arrv[26];
				$ech =$arrv[27];	
				$gplwtdiv=$arrv[28];

				getPostListThumbs($ptype,$dexcp,$orient,$imgo,$ttgo,$dtgo,$dtfrm,$categ,$postnr,$linn,$divwid,$tbwid,$tbhig,$cp,$cs,$lwt,$tte,$sptb,$tgtb,$ordm,$ordf,$metk,$mett,$pgin,$gptb,$pgnm,$pgmx,$ech,$gplwtdiv);

			//}	
		//add_action('init', 'gplt_code');
		//error_reporting(E_ALL);
		//	ini_set('display_errors', '1');
		//gplt_code(1);
		//getPostListThumbs($_GET['v']);
	break;	

	case 2:		
		echo "testing";
	break;
endswitch;
?>