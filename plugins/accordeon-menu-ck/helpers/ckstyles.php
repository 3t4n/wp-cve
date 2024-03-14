<?php
/**
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr - https://www.ceikay.com
 */

Namespace Accordeonmenuck;
// No direct access
defined('CK_LOADED') or die;

/**
 * CKStyles is a class to manage the styles
 *
 * @author Cedric KEIFLIN http://www.joomlack.fr
 */
class CKStyles extends \stdClass {

	public function create($fields, $customstyles, $direction = 'ltr') {

		$styles = "";
		if (! empty($customstyles)) {
		// look for the custom styles to manage from plugins for example
		$customprefixes = array();
		foreach ($customstyles as $prefix => $selector) {
			$customprefixes[] = $prefix;
		}
		// merge the existing prefix and the new one
		// $prefixes = array_merge($prefixes, $customprefixes);
		}
		$prefixes = $customprefixes;

		$cssstyles = new \stdClass();
		foreach ($prefixes as $prefix) {
			$cssstyles->$prefix = new \stdClass();
			$cssstyles->$prefix->css = self::genCss($fields, $prefix, $direction);
		}


	if (! empty($customstyles)) {
		$id = '|ID|';
		// loop through all custom styles from plugins or other elements
		foreach ($customstyles as $prefix => $selector) {
			$selectors = explode('|', str_replace('|qq|', '"', $selector));
			$fullselector = $id . ' ' . implode(',' . $id . ' ', $selectors);
			// $fullselector = implode(',', $selectors);
			if (
					$cssstyles->$prefix->css['background']
					|| $cssstyles->$prefix->css['gradient']
					|| $cssstyles->$prefix->css['borders']
					|| $cssstyles->$prefix->css['borderradius']
					|| $cssstyles->$prefix->css['height']
					|| $cssstyles->$prefix->css['width']
					|| $cssstyles->$prefix->css['color']
					|| $cssstyles->$prefix->css['margins']
					|| $cssstyles->$prefix->css['paddings']
					|| $cssstyles->$prefix->css['alignement']
					|| $cssstyles->$prefix->css['shadow']
					|| $cssstyles->$prefix->css['fontbold']
					|| $cssstyles->$prefix->css['fontitalic']
					|| $cssstyles->$prefix->css['fontunderline']
					|| $cssstyles->$prefix->css['fontuppercase']
					|| $cssstyles->$prefix->css['letterspacing']
					|| $cssstyles->$prefix->css['wordspacing']
					|| $cssstyles->$prefix->css['textindent']
					|| $cssstyles->$prefix->css['lineheight']
					|| $cssstyles->$prefix->css['fontsize']
					|| $cssstyles->$prefix->css['fontfamily']
					|| $cssstyles->$prefix->css['textshadow']
					|| $cssstyles->$prefix->css['custom']
					) {
						$styles .= "
" . $fullselector . " {
"
					. $cssstyles->$prefix->css['background']
					. $cssstyles->$prefix->css['gradient']
					. $cssstyles->$prefix->css['borders']
					. $cssstyles->$prefix->css['borderradius']
					. $cssstyles->$prefix->css['height']
					. $cssstyles->$prefix->css['width']
					. $cssstyles->$prefix->css['color']
					. $cssstyles->$prefix->css['margins']
					. $cssstyles->$prefix->css['paddings']
					. $cssstyles->$prefix->css['alignement']
					. $cssstyles->$prefix->css['shadow']
					. $cssstyles->$prefix->css['fontbold']
					. $cssstyles->$prefix->css['fontitalic']
					. $cssstyles->$prefix->css['fontunderline']
					. $cssstyles->$prefix->css['fontuppercase']
					. $cssstyles->$prefix->css['letterspacing']
					. $cssstyles->$prefix->css['wordspacing']
					. $cssstyles->$prefix->css['textindent']
					. $cssstyles->$prefix->css['lineheight']
					. $cssstyles->$prefix->css['fontsize']
					. $cssstyles->$prefix->css['fontfamily']
					. $cssstyles->$prefix->css['textshadow']
					. $cssstyles->$prefix->css['custom']
					. "}
";
			}

			if (
					$cssstyles->$prefix->css['color']
					|| $cssstyles->$prefix->css['fontbold']
					|| $cssstyles->$prefix->css['fontitalic']
					|| $cssstyles->$prefix->css['fontunderline']
					|| $cssstyles->$prefix->css['fontuppercase']
					|| $cssstyles->$prefix->css['letterspacing']
					|| $cssstyles->$prefix->css['wordspacing']
					|| $cssstyles->$prefix->css['textindent']
					|| $cssstyles->$prefix->css['lineheight']
					|| $cssstyles->$prefix->css['fontsize']
					|| $cssstyles->$prefix->css['fontfamily']
					|| $cssstyles->$prefix->css['textshadow']
					) {
						$styles .= "
" . $fullselector . " a {
"
					. $cssstyles->$prefix->css['color']
					. $cssstyles->$prefix->css['fontbold']
					. $cssstyles->$prefix->css['fontitalic']
					. $cssstyles->$prefix->css['fontunderline']
					. $cssstyles->$prefix->css['fontuppercase']
					. $cssstyles->$prefix->css['letterspacing']
					. $cssstyles->$prefix->css['wordspacing']
					. $cssstyles->$prefix->css['textindent']
					. $cssstyles->$prefix->css['lineheight']
					. $cssstyles->$prefix->css['fontsize']
					. $cssstyles->$prefix->css['fontfamily']
					. $cssstyles->$prefix->css['textshadow']
					. "}
";
			}
			// add the animations to the element
			$styles .= $this->genAnimations($fields, $prefix, $fullselector);
		}
	}

;
		/* ---- fin des css ------ */
		return $styles;
	}

	function genCss($fields, $prefix, $direction) {
		$action = 'preview';

		// construct variable names
		$backgroundimageurl = $prefix . 'bgimage';
		$backgroundimageleft = $prefix . 'bgpositionx';
		$backgroundimagetop = $prefix . 'bgpositiony';
		$backgroundimagerepeat = $prefix . 'bgimagerepeat';
		$backgroundimageattachment = $prefix . 'bgimageattachment';
		$backgroundcolor = $prefix . 'bgcolor1';
		$backgroundopacity = $prefix . 'bgopacity';
		$gradientcolor = $prefix . 'bgcolor2';
		$gradient1position = $prefix . 'backgroundpositionend';
		$gradient1opacity = $prefix . 'backgroundopacityend';
		$gradient2color = $prefix . 'backgroundcolorstop1';
		$gradient2position = $prefix . 'backgroundpositionstop1';
		$gradient2opacity = $prefix . 'backgroundopacitystop1';
		$gradient3color = $prefix . 'backgroundcolorstop2';
		$gradient3position = $prefix . 'backgroundpositionstop2';
		$gradient3opacity = $prefix . 'backgroundopacitystop2';
		$gradientdirection = $prefix . 'backgrounddirection';
		$hasopacity = false;
		$backgroundimagesize = $prefix . 'backgroundimagesize';
		$opacity = $prefix . 'opacity';
		$bgimagesize = $prefix . 'bgimagesize';

		// set the background color
		$css['background'] = (isset($fields->$backgroundcolor) AND $fields->$backgroundcolor != '') ? "\tbackground: " . $fields->$backgroundcolor . ";\r\n" : "";
		$backgroundcolorvalue = (isset($fields->$backgroundcolor) AND $fields->$backgroundcolor) ? $fields->$backgroundcolor : "";

		// manage rgba color for opacity
		if (isset($fields->$backgroundopacity) AND $fields->$backgroundopacity != '' AND isset($fields->$backgroundcolor)) {
			$hasopacity = true;
			$rgbavalue = $this->hex2RGB($fields->$backgroundcolor, $fields->$backgroundopacity);
			$css['background'] .= (isset($fields->$backgroundcolor) AND $fields->$backgroundcolor) ? "\tbackground: " . $rgbavalue . ";\r\n\t-pie-background: " . $rgbavalue . ";\r\n" : "";
		}
		if (isset($fields->$backgroundopacity) AND $fields->$backgroundopacity == '0') {
			$css['background'] .= "\tbackground: none;\r\n";
		}

		$imageurl = "";
		if (isset($fields->$backgroundimageurl) AND $fields->$backgroundimageurl) {
			
			if ($action == 'preview') {
				$imageurl = substr($fields->$backgroundimageurl, 0, 4)  == 'http' ? $fields->$backgroundimageurl : ACCORDEONMENUCK_URI_ROOT . '/' . $fields->$backgroundimageurl;
//				$imageurl = substr($fields->$backgroundimageurl, 0, 4)  == 'http' ? $fields->$backgroundimageurl : $fields->$backgroundimageurl;
			} else {
				$imageurl = explode("/", $fields->$backgroundimageurl);
				$imageurl = end($imageurl);
				$imageurl = "../images/" . $imageurl;
			}
		}

		// set the background image
		$backgroundimageleftvalue = (isset($fields->$backgroundimageleft) AND $fields->$backgroundimageleft != null) ? $fields->$backgroundimageleft : "center";
		$backgroundimagetopvalue = (isset($fields->$backgroundimagetop) AND $fields->$backgroundimagetop != null) ? $fields->$backgroundimagetop : "center";
		$backgroundimagerepeatvalue = (isset($fields->$backgroundimagerepeat) AND $fields->$backgroundimagerepeat) ? $fields->$backgroundimagerepeat : "no-repeat";
		$backgroundimageurlvalue = (isset($fields->$backgroundimageurl) AND $fields->$backgroundimageurl) ? $fields->$backgroundimageurl : "";
		$backgroundimageattachmentvalue = (isset($fields->$backgroundimageattachment) AND $fields->$backgroundimageattachment) ? $fields->$backgroundimageattachment : "";

		if ($backgroundimageleftvalue != 'top' AND $backgroundimageleftvalue != 'right' AND $backgroundimageleftvalue != 'bottom' AND $backgroundimageleftvalue != 'left' AND $backgroundimageleftvalue != 'center' AND !stristr($backgroundimageleftvalue, "px")
		)
			$backgroundimageleftvalue = $this->testUnit($backgroundimageleftvalue);

		if ($backgroundimagetopvalue != 'top' AND $backgroundimagetopvalue != 'right' AND $backgroundimagetopvalue != 'bottom' AND $backgroundimagetopvalue != 'left' AND $backgroundimagetopvalue != 'center' AND !stristr($backgroundimagetopvalue, "px")
		)
			$backgroundimagetopvalue = $this->testUnit($backgroundimagetopvalue);

		// set the background color
		if ((isset($fields->class) AND !stristr($fields->class, 'bannerlogo')) OR !isset($fields->class)) {
			$css['background'] = (isset($fields->$backgroundimageurl) AND $fields->$backgroundimageurl) ? "\tbackground: " . $backgroundcolorvalue . " url(" . $imageurl . ") " . $backgroundimageleftvalue . " " . $backgroundimagetopvalue . " " . $backgroundimagerepeatvalue . " " . $backgroundimageattachmentvalue . ";\r\n" : $css['background'];
			if ($hasopacity) 
				$css['background'] .= (isset($fields->$backgroundimageurl) AND $fields->$backgroundimageurl) ? "\tbackground: " . $rgbavalue . " url(" . $imageurl . ") " . $backgroundimageleftvalue . " " . $backgroundimagetopvalue . " " . $backgroundimagerepeatvalue . " " . $backgroundimageattachmentvalue . ";\r\n" : "";
		}

		//set the background size
		if (isset($fields->$backgroundimageurl) AND $fields->$backgroundimageurl AND isset($fields->$backgroundimagesize) AND $fields->$backgroundimagesize != 'none') {
			$css['background'] .= "\tbackground-size: " . $fields->$backgroundimagesize . ";\r\n";
		}

		$css['background'] .= (isset($fields->$opacity) AND $fields->$opacity) ? "\topacity: " . ($fields->$opacity / 100) . ";" : "";
		// background size
		$css['background'] .= (isset($fields->$bgimagesize) AND $fields->$bgimagesize != 'default') ? "\tbackground-size: " . $fields->$bgimagesize . ";" : "";

		$gradient0colorvalue = (isset($fields->$backgroundcolor) AND $fields->$backgroundcolor) ? $fields->$backgroundcolor : "";
		$gradient1colorvalue = (isset($fields->$gradientcolor) AND $fields->$gradientcolor) ? $fields->$gradientcolor : "";
		$gradient1positionvalue = (isset($fields->$gradient1position) AND $fields->$gradient1position) ? $fields->$gradient1position . "%" : "100%";
		$gradient2colorvalue = (isset($fields->$gradient2color) AND $fields->$gradient2color) ? $fields->$gradient2color : "";
		$gradient2positionvalue = (isset($fields->$gradient2position) AND $fields->$gradient2position) ? $fields->$gradient2position . "%" : "";
		$gradient3colorvalue = (isset($fields->$gradient3color) AND $fields->$gradient3color) ? $fields->$gradient3color : "";
		$gradient3positionvalue = (isset($fields->$gradient3position) AND $fields->$gradient3position) ? $fields->$gradient3position . "%" : "";

		if (isset($fields->$gradientdirection)) {
			switch ($fields->$gradientdirection) {
				case 'bottomtop':
					$gradientdirectionvalue = 'center bottom';
					$gradientdirectionvaluebis = 'left bottom, left top';
					$gradientdirectionvaluebis2 = 'x1="0%" y1="100%"
				x2="0%" y2="0%"';
					$gradientdirectionvalue3 = 'to top';
					break;
				case 'leftright':
					$gradientdirectionvalue = 'center left';
					$gradientdirectionvaluebis = 'left top, right top';
					$gradientdirectionvaluebis2 = 'x1="0%" y1="0%"
				x2="100%" y2="0%"';
					$gradientdirectionvalue3 = 'to right';
					break;
				case 'rightleft':
					$gradientdirectionvalue = 'center right';
					$gradientdirectionvaluebis = 'right top, left top';
					$gradientdirectionvaluebis2 = 'x1="100%" y1="0%"
				x2="0%" y2="0%"';
					$gradientdirectionvalue3 = 'to left';
					break;
				case 'topbottom':
				default :
					$gradientdirectionvalue = 'center top';
					$gradientdirectionvaluebis = 'left top, left bottom';
					$gradientdirectionvaluebis2 = 'x1="0%" y1="0%"
				x2="0%" y2="100%"';
					$gradientdirectionvalue3 = 'to bottom';
					break;
			}
		} else {
			$gradientdirectionvalue = 'center top';
			$gradientdirectionvaluebis = 'left top, left bottom';
			$gradientdirectionvaluebis2 = 'x1="0%" y1="0%"
				x2="0%" y2="100%"';
			$gradientdirectionvalue3 = 'to bottom';
		}


		$gradientstop2 = '';
		$gradientstop2webkit = '';
		$gradientstop2bis = '';
		$gradientstop3 = '';
		$gradientstop3webkit = '';
		$gradientstop3bis = '';
		if ($gradient2colorvalue AND $gradient2positionvalue) {
			$gradientstop2 = ',' . $gradient2colorvalue . ' ' . $gradient2positionvalue;
			$gradientstop2webkit = ',color-stop(' . $gradient2positionvalue . ',' . $gradient2colorvalue . ')';
			$gradientstop2bis = '<stop offset="' . $gradient2positionvalue . '"   stop-color="' . $gradient2colorvalue . '" stop-opacity="1"/>';
		}
		if ($gradient3colorvalue AND $gradient3positionvalue) {
			$gradientstop3 = ',' . $gradient3colorvalue . ' ' . $gradient3positionvalue;
			$gradientstop3webkit = ',color-stop(' . $gradient3positionvalue . ',' . $gradient3colorvalue . ')';
			$gradientstop3bis = '<stop offset="' . $gradient3positionvalue . '"   stop-color="' . $gradient3colorvalue . '" stop-opacity="1"/>';
		}



		if ($gradient0colorvalue && $gradient1colorvalue) {
			// $css['gradient'] = "\tbackground-image: url(\"" . $prefix . $id . "-gradient.svg\");\r\n"
			$css['gradient'] = ""
					. "\tbackground-image: -o-linear-gradient(" . $gradientdirectionvalue . "," . $gradient0colorvalue . $gradientstop2 . $gradientstop3 . ", " . $gradient1colorvalue . ' ' . $gradient1positionvalue . ");\r\n"
					. "\tbackground-image: -webkit-gradient(linear, " . $gradientdirectionvaluebis . ",from(" . $gradient0colorvalue . ")" . $gradientstop2webkit . $gradientstop3webkit . ", color-stop(" . $gradient1positionvalue . ', ' . $gradient1colorvalue . "));\r\n"
					. "\tbackground-image: -moz-linear-gradient(" . $gradientdirectionvalue . "," . $gradient0colorvalue . $gradientstop2 . $gradientstop3 . ", " . $gradient1colorvalue . ' ' . $gradient1positionvalue . ");\r\n"
					. "\tbackground-image: linear-gradient(" . $gradientdirectionvalue3 . "," . $gradient0colorvalue . $gradientstop2 . $gradientstop3 . ", " . $gradient1colorvalue . ' ' . $gradient1positionvalue . ");\r\n";
					// . "\t-pie-background: linear-gradient(" . $gradientdirectionvalue . "," . $gradient0colorvalue . $gradientstop2 . $gradientstop3 . ", " . $gradient1colorvalue . ' ' . $gradient1positionvalue . ");\r\n";
		} else {
			$css['gradient'] = "";
		}


		// construct variable names
		$borderscolor = $prefix . 'borderscolor';
		$borderssize = $prefix . 'borderswidth';
		$bordersstyle = $prefix . 'bordersstyle';
		$bordertopcolor = $prefix . 'bordertopcolor';
		$bordertopsize = $prefix . 'bordertopwidth';
		$bordertopstyle = $prefix . 'bordertopstyle';
		$borderbottomcolor = $prefix . 'borderbottomcolor';
		$borderbottomsize = $prefix . 'borderbottomwidth';
		$borderbottomstyle = $prefix . 'borderbottomstyle';
		$borderleftcolor = $prefix . 'borderleftcolor';
		$borderleftsize = $prefix . 'borderleftwidth';
		$borderleftstyle = $prefix . 'borderleftstyle';
		$borderrightcolor = $prefix . 'borderrightcolor';
		$borderrightsize = $prefix . 'borderrightwidth';
		$borderrightstyle = $prefix . 'borderrightstyle';
		// for border radius
		$borderradius = $prefix . 'roundedcorners';
		$borderradiustopleft = $prefix . 'roundedcornerstl';
		$borderradiustopright = $prefix . 'roundedcornerstr';
		$borderradiusbottomleft = $prefix . 'roundedcornersbl';
		$borderradiusbottomright = $prefix . 'roundedcornersbr';

		$fields->$bordersstyle = isset($fields->$bordersstyle) ? $fields->$bordersstyle : 'solid';
		$fields->$bordertopstyle = isset($fields->$bordertopstyle) ? $fields->$bordertopstyle : 'solid';
		$fields->$borderbottomstyle = isset($fields->$borderbottomstyle) ? $fields->$borderbottomstyle : 'solid';
		$fields->$borderleftstyle = isset($fields->$borderleftstyle) ? $fields->$borderleftstyle : 'solid';
		$fields->$borderrightstyle = isset($fields->$borderrightstyle) ? $fields->$borderrightstyle : 'solid';

		$css['borders'] = (isset($fields->$borderssize) AND $fields->$borderssize == '0') ? "\tborder: none;\r\n" : "";
		$css['bordertop'] = (isset($fields->$bordertopsize) AND $fields->$bordertopsize == '0') ? "\tborder-top: none;\r\n" : "";
		$css['borderbottom'] = (isset($fields->$borderbottomsize) AND $fields->$borderbottomsize == '0') ? "\tborder-bottom: none;\r\n" : "";
		$css['borderleft'] = (isset($fields->$borderleftsize) AND $fields->$borderleftsize == '0') ? "\tborder-left: none;\r\n" : "";
		$css['borderright'] = (isset($fields->$borderrightsize) AND $fields->$borderrightsize == '0') ? "\tborder-right: none;\r\n" : "";

		$css['borders'] = (isset($fields->$borderscolor) AND $fields->$borderscolor AND isset($fields->$borderssize) AND $fields->$borderssize) ? "\tborder: " . $fields->$borderscolor . " " . $this->testUnit($fields->$borderssize) . " " . $fields->$bordersstyle . ";\r\n" : $css['borders'];
		$css['bordertop'] = (isset($fields->$bordertopcolor) AND $fields->$bordertopcolor AND isset($fields->$bordertopsize) AND $fields->$bordertopsize) ? "\tborder-top: " . $fields->$bordertopcolor . " " . $this->testUnit($fields->$bordertopsize) . " " . $fields->$bordertopstyle . ";\r\n" : $css['bordertop'];
		$css['borderbottom'] = (isset($fields->$borderbottomcolor) AND $fields->$borderbottomcolor AND isset($fields->$borderbottomsize) AND $fields->$borderbottomsize) ? "\tborder-bottom: " . $fields->$borderbottomcolor . " " . $this->testUnit($fields->$borderbottomsize) . " " . $fields->$borderbottomstyle . ";\r\n" : $css['borderbottom'];
		$css['borderleft'] = (isset($fields->$borderleftcolor) AND $fields->$borderleftcolor AND isset($fields->$borderleftsize) AND $fields->$borderleftsize) ? "\tborder-left: " . $fields->$borderleftcolor . " " . $this->testUnit($fields->$borderleftsize) . " " . $fields->$borderleftstyle . ";\r\n" : $css['borderleft'];
		$css['borderright'] = (isset($fields->$borderrightcolor) AND $fields->$borderrightcolor AND isset($fields->$borderrightsize) AND $fields->$borderrightsize) ? "\tborder-right: " . $fields->$borderrightcolor . " " . $this->testUnit($fields->$borderrightsize) . " " . $fields->$borderrightstyle . ";\r\n" : $css['borderright'];

		// compile all borders
		$css['borders'] .= $css['bordertop'] . $css['borderbottom'] . $css['borderleft'] . $css['borderright'];

		// $borderradiusvalue = (isset($fields->$borderradius) AND ($fields->$borderradius || $fields->$borderradius == "0")) ? $fields->$borderradius : "0";
		$borderradiusvalue = "0";
		$borderradiustopleftvalue = (isset($fields->$borderradiustopleft) AND ($fields->$borderradiustopleft || $fields->$borderradiustopleft == "0")) ? $fields->$borderradiustopleft : $borderradiusvalue;
		$borderradiustoprightvalue = (isset($fields->$borderradiustopright) AND ($fields->$borderradiustopright || $fields->$borderradiustopleft == "0")) ? $fields->$borderradiustopright : $borderradiusvalue;
		$borderradiusbottomleftvalue = (isset($fields->$borderradiusbottomleft) AND ($fields->$borderradiusbottomleft || $fields->$borderradiustopleft == "0")) ? $fields->$borderradiusbottomleft : $borderradiusvalue;
		$borderradiusbottomrightvalue = (isset($fields->$borderradiusbottomright) AND ($fields->$borderradiusbottomright || $fields->$borderradiustopleft == "0")) ? $fields->$borderradiusbottomright : $borderradiusvalue;

		if ( (isset($fields->$borderradiustopleft) AND $fields->$borderradiustopleft != "")
			|| (isset($fields->$borderradiustopright) AND $fields->$borderradiustopright != "")
			|| (isset($fields->$borderradiusbottomleft) AND $fields->$borderradiusbottomleft != "")
			|| (isset($fields->$borderradiusbottomright) AND $fields->$borderradiusbottomright != "")
		) {
			// $css['borderradius'] = "\t-moz-border-radius: " . $this->testUnit($borderradiusvalue) . ";\r\n"
					// . "\t-o-border-radius: " . $this->testUnit($borderradiusvalue) . ";\r\n"
					// . "\t-webkit-border-radius: " . $this->testUnit($borderradiusvalue) . ";\r\n"
					// . "\tborder-radius: " . $this->testUnit($borderradiusvalue) . ";\r\n"
			$css['borderradius'] =  "\t-moz-border-radius: " . $this->testUnit($borderradiustopleftvalue) . " " . $this->testUnit($borderradiustoprightvalue) . " " . $this->testUnit($borderradiusbottomrightvalue) . " " . $this->testUnit($borderradiusbottomleftvalue) . ";\r\n"
					. "\t-o-border-radius: " . $this->testUnit($borderradiustopleftvalue) . " " . $this->testUnit($borderradiustoprightvalue) . " " . $this->testUnit($borderradiusbottomrightvalue) . " " . $this->testUnit($borderradiusbottomleftvalue) . ";\r\n"
					. "\t-webkit-border-radius: " . $this->testUnit($borderradiustopleftvalue) . " " . $this->testUnit($borderradiustoprightvalue) . " " . $this->testUnit($borderradiusbottomrightvalue) . " " . $this->testUnit($borderradiusbottomleftvalue) . ";\r\n"
					. "\tborder-radius: " . $this->testUnit($borderradiustopleftvalue) . " " . $this->testUnit($borderradiustoprightvalue) . " " . $this->testUnit($borderradiusbottomrightvalue) . " " . $this->testUnit($borderradiusbottomleftvalue) . ";\r\n";
		} else {
			$css['borderradius'] = "";
		}

		// construct variable names
		$height = $prefix . 'height';
		$width = $prefix . 'width';
		$color = $prefix . 'fontcolor';
		$colorLegacy = $prefix . 'color';
		$lineheight = $prefix . 'lineheight';
		$margintop = $prefix . 'margintop';
		$marginbottom = $prefix . 'marginbottom';
		$marginleft = $prefix . 'marginleft';
		$marginright = $prefix . 'marginright';
		$margins = $prefix . 'margins';
		$paddingtop = $prefix . 'paddingtop';
		$paddingbottom = $prefix . 'paddingbottom';
		$paddingleft = $prefix . 'paddingleft';
		$paddingright = $prefix . 'paddingright';
		$paddings = $prefix . 'paddings';

		$css['height'] = (isset($fields->$height) AND $fields->$height) ? "\theight: " . $this->testUnit($fields->$height) . ";\r\n" : "";
		$css['width'] = (isset($fields->$width) AND $fields->$width) ? "\twidth: " . $this->testUnit($fields->$width) . ";\r\n" : "";
		$css['color'] = (isset($fields->$color) AND $fields->$color) ? "\tcolor: " . $fields->$color . ";\r\n" : "";
		$css['color'] = $css['color'] ? $css['color'] : (isset($fields->$colorLegacy) AND $fields->$colorLegacy) ? "\tcolor: " . $fields->$colorLegacy . ";\r\n" : "";
		$css['lineheight'] = (isset($fields->$lineheight) AND $fields->$lineheight) ? "\tline-height: " . $this->testUnit($fields->$lineheight) . ";\r\n" : "";
		$css['margintop'] = (isset($fields->$margintop) AND ($fields->$margintop OR $fields->$margintop == '0')) ? "\tmargin-top: " . $this->testUnit($fields->$margintop) . ";\r\n" : "";
		$css['marginbottom'] = (isset($fields->$marginbottom) AND ($fields->$marginbottom OR $fields->$marginbottom == '0')) ? "\tmargin-bottom: " . $this->testUnit($fields->$marginbottom) . ";\r\n" : "";
		$css['marginleft'] = (isset($fields->$marginleft) AND ($fields->$marginleft OR $fields->$marginleft == '0')) ? "\tmargin-left: " . $this->testUnit($fields->$marginleft) . ";\r\n" : "";
		$css['margins'] = (isset($fields->$margins) AND ($fields->$margins OR $fields->$margins == '0')) ? "\tmargin: " . $this->testUnit($fields->$margins) . ";\r\n" : "";
		$css['marginright'] = (isset($fields->$marginright) AND ($fields->$marginright OR $fields->$marginright == '0')) ? "\tmargin-right: " . $this->testUnit($fields->$marginright) . ";\r\n" : "";
		$css['paddingtop'] = (isset($fields->$paddingtop) AND ($fields->$paddingtop OR $fields->$paddingtop == '0')) ? "\tpadding-top: " . $this->testUnit($fields->$paddingtop) . ";\r\n" : "";
		$css['paddingbottom'] = (isset($fields->$paddingbottom) AND ($fields->$paddingbottom OR $fields->$paddingbottom == '0')) ? "\tpadding-bottom: " . $this->testUnit($fields->$paddingbottom) . ";\r\n" : "";
		$css['paddingleft'] = (isset($fields->$paddingleft) AND ($fields->$paddingleft OR $fields->$paddingleft == '0')) ? "\tpadding-left: " . $this->testUnit($fields->$paddingleft) . ";\r\n" : "";
		$css['paddingright'] = (isset($fields->$paddingright) AND ($fields->$paddingright OR $fields->$paddingright == '0')) ? "\tpadding-right: " . $this->testUnit($fields->$paddingright) . ";\r\n" : "";
		$css['paddings'] = (isset($fields->$paddings) AND ($fields->$paddings OR $fields->$paddings == '0')) ? "\tpadding: " . $this->testUnit($fields->$paddings) . ";\r\n" : "";

		$css['margins'] .= $css['margintop'] . $css['marginright'] . $css['marginbottom'] . $css['marginleft'];
		$css['paddings'] .= $css['paddingtop'] . $css['paddingright'] . $css['paddingbottom'] . $css['paddingleft'];

		// construct variable names
		$shadowcolor = $prefix . 'shadowcolor';
		$shadowhoffset = $prefix . 'shadowoffsetx';
		$shadowvoffset = $prefix . 'shadowoffsety';
		$shadowblur = $prefix . 'shadowblur';
		$shadowspread = $prefix . 'shadowspread';
		$shadowinset = $prefix . 'shadowinset';
		$shadowopacity = $prefix . 'shadowopacity';

		// manage shadow box
		$shadowcolorvalue = (isset($fields->$shadowcolor) AND $fields->$shadowcolor) ? $fields->$shadowcolor : "";
		$shadowhoffsetvalue = (isset($fields->$shadowhoffset) AND $fields->$shadowhoffset) ? $fields->$shadowhoffset : "0";
		$shadowvoffsetvalue = (isset($fields->$shadowvoffset) AND $fields->$shadowvoffset) ? $fields->$shadowvoffset : "0";
		$shadowblurvalue = (isset($fields->$shadowblur) AND $fields->$shadowblur) ? $fields->$shadowblur : "";
		$shadowspreadvalue = (isset($fields->$shadowspread) AND $fields->$shadowspread) ? $fields->$shadowspread : "0";
		$shadowinsetvalue = (isset($fields->$shadowinset) AND $fields->$shadowinset === '1') ? ' inset' : '';

		// manage rgba color for opacity
		if (isset($fields->$shadowopacity) AND $fields->$shadowopacity !== '' AND $shadowcolorvalue !== '') {
			$shadowcolorvalue = $this->hex2RGB($shadowcolorvalue, $fields->$shadowopacity);
		}
		
		if ($shadowcolorvalue && $shadowblurvalue) {
			$css['shadow'] = "\tbox-shadow: " . $shadowcolorvalue . " " . $this->testUnit($shadowhoffsetvalue) . " " . $this->testUnit($shadowvoffsetvalue) . " " . $this->testUnit($shadowblurvalue) . " " . $this->testUnit($shadowspreadvalue) . $shadowinsetvalue . ";\r\n"
					. "\t-moz-box-shadow: " . $shadowcolorvalue . " " . $this->testUnit($shadowhoffsetvalue) . " " . $this->testUnit($shadowvoffsetvalue) . " " . $this->testUnit($shadowblurvalue) . " " . $this->testUnit($shadowspreadvalue) . $shadowinsetvalue . ";\r\n"
					. "\t-webkit-box-shadow: " . $shadowcolorvalue . " " . $this->testUnit($shadowhoffsetvalue) . " " . $this->testUnit($shadowvoffsetvalue) . " " . $this->testUnit($shadowblurvalue) . " " . $this->testUnit($shadowspreadvalue) . $shadowinsetvalue . ";\r\n";
		} else {
			$css['shadow'] = "";
		}

		// construct variable names
		$fontactivation = $prefix . 'fontactivation';
		// $fontbold = $prefix . 'fontbold';
		$fontitalic = $prefix . 'fontitalic';
		$fontunderline = $prefix . 'fontunderline';
		// $fontuppercase = $prefix . 'fontuppercase';
		$fontfamily = $prefix . 'fontfamily';
		$googlefont = $prefix . 'googlefont';
		$fontweight = $prefix . 'fontweight';
		$fontsize = $prefix . 'fontsize';
		// $alignementactivation = $prefix . 'alignementactivation';
		// $alignement = $prefix . 'alignement';
		// $alignementleft = $prefix . 'alignementleft';
		// $alignementcenter = $prefix . 'alignementcenter';
		// $alignementjustify = $prefix . 'alignementjustify';
		// $alignementright = $prefix . 'alignementright';
		$wordspacing = $prefix . 'wordspacing';
		$letterspacing = $prefix . 'letterspacing';
		$textindent = $prefix . 'textindent';
		$textalign = $prefix . 'textalign';
		$fontweight = $prefix . 'fontweight';
		$texttransform = $prefix . 'texttransform';

		// $css['alignement'] = "";
		// if (isset($fields->$alignementright) AND $fields->$alignementright == 'checked') {
			// $css['alignement'] = $direction == "rtl" ? "\ttext-align: left;\r\n" : "\ttext-align: right;\r\n";
		// } else if (isset($fields->$alignementcenter) AND $fields->$alignementcenter == 'checked') {
			// $css['alignement'] = "\ttext-align: center;\r\n";
		// } else if (isset($fields->$alignementjustify) AND $fields->$alignementjustify == 'checked') {
			// $css['alignement'] = "\ttext-align: justify;\r\n";
		// } else if (isset($fields->$alignementleft) AND $fields->$alignementleft == 'checked') {
			// $css['alignement'] = $direction == "rtl" ? "\ttext-align: right;\r\n" : "\ttext-align: left;\r\n";
			// ;
		// }

		// $css['fontbold'] = "";
		$css['fontitalic'] = "";
		$css['fontunderline'] = "";
		$css['fontuppercase'] = "";
		
		$css['alignement'] = (isset($fields->$textalign) AND $fields->$textalign) ? "\ttext-align: " . $fields->$textalign . ";\r\n" : "";
		$css['fontbold'] = (isset($fields->$fontweight) AND $fields->$fontweight) ? "\tfont-weight: " . $fields->$fontweight . ";\r\n" : "";
		$css['fontuppercase'] = (isset($fields->$texttransform) AND $fields->$texttransform) ? "\ttext-transform: " . $fields->$texttransform . ";\r\n" : "";
		
		

		// if (isset($fields->$fontbold) AND $fields->$fontbold) {
			// if ($fields->$fontbold != 'default')
				// $css['fontbold'] = $fields->$fontbold == 'bold' ? "\tfont-weight: bold;\r\n" : "\tfont-weight: normal;\r\n";
		// }

		if (isset($fields->$fontitalic) AND $fields->$fontitalic) {
			if ($fields->$fontitalic != 'default')
				$css['fontitalic'] = $fields->$fontitalic == 'italic' ? "\tfont-style: italic;\r\n" : "\tfont-style: normal;\r\n";
		}

		if (isset($fields->$fontunderline) AND $fields->$fontunderline) {
			if ($fields->$fontunderline != 'default')
				$css['fontunderline'] = $fields->$fontunderline == 'underline' ? "\ttext-decoration: underline;\r\n" : "\ttext-decoration: none;\r\n";
		}

		// if (isset($fields->$fontuppercase) AND $fields->$fontuppercase) {
			// if ($fields->$fontuppercase != 'default')
				// $css['fontuppercase'] = $fields->$fontuppercase == 'uppercase' ? "\ttext-transform: uppercase;\r\n" : "\ttext-transform: none;\r\n";
		// }

		$css['textindent'] = (isset($fields->$textindent) AND $fields->$textindent) ? "\ttext-indent: " . $this->testUnit($fields->$textindent) . ";\r\n" : "";
		$css['letterspacing'] = (isset($fields->$letterspacing) AND $fields->$letterspacing) ? "\tletter-spacing: " . $this->testUnit($fields->$letterspacing) . ";\r\n" : "";
		$css['wordspacing'] = (isset($fields->$wordspacing) AND $fields->$wordspacing) ? "\tword-spacing: " . $this->testUnit($fields->$wordspacing) . ";\r\n" : "";
		$css['fontsize'] = (isset($fields->$fontsize) AND $fields->$fontsize) ? "\tfont-size: " . $this->testUnit($fields->$fontsize) . ";\r\n" : "";
		$css['fontstylessquirrel'] = '';
		if (isset($fields->$fontfamily) AND $fields->$fontfamily == 'googlefont') {
			$fields->$googlefont = str_replace('+', ' ', $fields->$googlefont);
			$css['fontfamily'] = (isset($fields->$googlefont) AND $fields->$googlefont != "default") ? "\tfont-family: '" . $fields->$googlefont . "';\r\n" : "";
			$css['fontbold'] = (isset($fields->$fontweight) AND $fields->$fontweight != "") ? "\tfont-weight: " . $fields->$fontweight . ";\r\n" : "";
		} else {
			$css['fontfamily'] = (isset($fields->$fontfamily) AND $fields->$fontfamily != "default") ? "\tfont-family: " . $fields->$fontfamily . ";\r\n" : "";
		}
		// compatibility with multiple intefaces
		$gfontfamily = $prefix . 'textgfont';
		if (isset($fields->$gfontfamily) AND $fields->$gfontfamily) {
			$fields->$gfontfamily = str_replace('+', ' ', $fields->$gfontfamily);
			$css['fontfamily'] .= (isset($fields->$gfontfamily) AND $fields->$gfontfamily) ? "\tfont-family: '" . $fields->$gfontfamily . "';\r\n" : "";
		}


		// construct variable names
		$textshadowcolor = $prefix . 'textshadowcolor';
		$textshadowblur = $prefix . 'textshadowblur';
		$textshadowoffsetx = $prefix . 'textshadowoffsetx';
		$textshadowoffsety = $prefix . 'textshadowoffsety';
		$textshadowoffsetxvalue = (isset($fields->$textshadowoffsetx) && $fields->$textshadowoffsetx) ? $this->testUnit($fields->$textshadowoffsetx) : '0';
		$textshadowoffsetyvalue = (isset($fields->$textshadowoffsety) && $fields->$textshadowoffsety) ? $this->testUnit($fields->$textshadowoffsety) : '0';

		$css['textshadow'] = "";
		if (isset($fields->$textshadowcolor) AND $fields->$textshadowcolor AND $fields->$textshadowblur) {
			$css['textshadow'] = "\ttext-shadow: " . $textshadowoffsetxvalue . " " . $textshadowoffsetyvalue . " " . $this->testUnit($fields->$textshadowblur) . " " . $fields->$textshadowcolor . ";\r\n";
		} else if (isset($fields->$textshadowblur) AND $fields->$textshadowblur == '0') {
			$css['textshadow'] = "\ttext-shadow: none;\r\n";
		}

		// construct variable names
		$normallinkfontbold = $prefix . 'normallinkfontbold';
		$normallinkfontitalic = $prefix . 'normallinkfontitalic';
		$normallinkfontunderline = $prefix . 'normallinkfontunderline';
		$normallinkfontuppercase = $prefix . 'normallinkfontuppercase';
		$normallinkcolor = $prefix . 'normallinkcolor';

		$css['normallinkfontbold'] = "";
		$css['normallinkfontitalic'] = "";
		$css['normallinkfontunderline'] = "";
		$css['normallinkfontuppercase'] = "";

		if (isset($fields->$normallinkfontbold) AND $fields->$normallinkfontbold) {
			if ($fields->$normallinkfontbold != 'default')
				$css['normallinkfontbold'] = $fields->$normallinkfontbold == 'bold' ? "\tfont-weight: bold;\r\n" : "\tfont-weight: normal;\r\n";
		}

		if (isset($fields->$normallinkfontitalic) AND $fields->$normallinkfontitalic) {
			if ($fields->$normallinkfontitalic != 'default')
				$css['normallinkfontitalic'] = $fields->$normallinkfontitalic == 'italic' ? "\tfont-style: italic;\r\n" : "\tfont-style: normal;\r\n";
		}

		if (isset($fields->$normallinkfontunderline) AND $fields->$normallinkfontunderline) {
			if ($fields->$normallinkfontunderline != 'default')
				$css['normallinkfontunderline'] = $fields->$normallinkfontunderline == 'underline' ? "\ttext-decoration: underline;\r\n" : "\ttext-decoration: none;\r\n";
		}

		if (isset($fields->$normallinkfontuppercase) AND $fields->$normallinkfontuppercase) {
			if ($fields->$normallinkfontuppercase != 'default')
				$css['normallinkfontuppercase'] = $fields->$normallinkfontuppercase == 'uppercase' ? "\ttext-transform: uppercase;\r\n" : "\ttext-transform: none;\r\n";
		}

		$css['normallinkcolor'] = (isset($fields->$normallinkcolor) AND $fields->$normallinkcolor) ? "\tcolor: " . $fields->$normallinkcolor . ";\r\n" : "";


		// construct variable names
		$hoverlinkactivation = $prefix . 'hoverlinkactivation';
		$hoverlinkfontbold = $prefix . 'hoverlinkfontbold';
		$hoverlinkfontitalic = $prefix . 'hoverlinkfontitalic';
		$hoverlinkfontunderline = $prefix . 'hoverlinkfontunderline';
		$hoverlinkfontuppercase = $prefix . 'hoverlinkfontuppercase';
		$hoverlinkcolor = $prefix . 'hoverlinkcolor';

		$css['hoverlinkfontbold'] = "";
		$css['hoverlinkfontitalic'] = "";
		$css['hoverlinkfontunderline'] = "";
		$css['hoverlinkfontuppercase'] = "";

		if (isset($fields->$hoverlinkfontbold) AND $fields->$hoverlinkfontbold) {
			if ($fields->$hoverlinkfontbold != 'default')
				$css['hoverlinkfontbold'] = $fields->$hoverlinkfontbold == 'bold' ? "\tfont-weight: bold;\r\n" : "\tfont-weight: normal;\r\n";
		}

		if (isset($fields->$hoverlinkfontitalic) AND $fields->$hoverlinkfontitalic) {
			if ($fields->$hoverlinkfontitalic != 'default')
				$css['hoverlinkfontitalic'] = $fields->$hoverlinkfontitalic == 'italic' ? "\tfont-style: italic;\r\n" : "\tfont-style: normal;\r\n";
		}

		if (isset($fields->$hoverlinkfontunderline) AND $fields->$hoverlinkfontunderline) {
			if ($fields->$hoverlinkfontunderline != 'default')
				$css['hoverlinkfontunderline'] = $fields->$hoverlinkfontunderline == 'underline' ? "\ttext-decoration: underline;\r\n" : "\ttext-decoration: none;\r\n";
		}

		if (isset($fields->$hoverlinkfontuppercase) AND $fields->$hoverlinkfontuppercase) {
			if ($fields->$hoverlinkfontuppercase != 'default')
				$css['hoverlinkfontuppercase'] = $fields->$hoverlinkfontuppercase == 'uppercase' ? "\ttext-transform: uppercase;\r\n" : "\ttext-transform: none;\r\n";
		}

		$css['hoverlinkcolor'] = (isset($fields->$hoverlinkcolor) AND $fields->$hoverlinkcolor) ? "\tcolor: " . $fields->$hoverlinkcolor . ";\r\n" : "";


		$custom = $prefix . 'custom';
		$css['custom'] = (isset($fields->$custom) AND $fields->$custom) ? "\t" . $fields->$custom . "\r\n" : "";

		// construct variable names
		$positionx = $prefix . 'positionx';
		$positiony = $prefix . 'positiony';

		$css['position'] = "";
		if (isset($fields->$positionx) AND $fields->$positionx !== '') {
			$css['position'] .= "\tleft: " . $this->testUnit($fields->$positionx) . ";\r\n";
		}
		if (isset($fields->$positiony) AND $fields->$positiony !== '') {
			$css['position'] .= "\ttop: " . $this->testUnit($fields->$positiony) . ";\r\n";
		}

		return $css;
	}

	/**
	* Set the CSS3 animations for the blocks
	*/
	private function genAnimations($fields, $prefix, $id) { 
		if (! isset($fields->{$prefix . 'animfade'})) return; // if no animation field is found, nothing to do here


		// fade, move, rotate, scale, flip?rotateY, replay
		$css = '';
		$transition = Array(); // transition: opacity 0.4s;transition: opacity 0.2s, transform 0.35s;
		$transform0 = Array(); // transform: rotate(45deg);transform: translate3d(0,40px,0);
		$transform100 = Array(); // transform: rotate(45deg);transform: translate3d(0,40px,0);
		$style0 = Array();
		$style100 = Array();
		$duration = isset($fields->{$prefix . 'animdur'}) && $fields->{$prefix . 'animdur'} ? $fields->{$prefix . 'animdur'} . 's' : '1s';
		$delay = isset($fields->{$prefix . 'animdelay'}) && $fields->{$prefix . 'animdelay'} ? $fields->{$prefix . 'animdelay'} . 's' : '0s';
		// fade effect
		if ($fields->{$prefix . 'animfade'} == '1') {
			$transition['fade'] = 'opacity ' . $duration;
			$style0[] = 'opacity: 0';
			$style100[] = 'opacity: 1';
		}
		// move effect
		if ($fields->{$prefix . 'animmove'} == '1') {
			$transition['transform'] = 'transform ' . $duration;
			switch($fields->{$prefix . 'animmovedir'}) {
				case 'ltrck':
				default:
					$transform0[] = 'translate3d(-' . (int)$fields->{$prefix . 'animmovedist'} . 'px,0,0)';
				break;
				case 'rtlck':
					$transform0[] = 'translate3d(' . (int)$fields->{$prefix . 'animmovedist'} . 'px,0,0)';
				break;
				case 'ttbck':
					$transform0[] = 'translate3d(0,-' . (int)$fields->{$prefix . 'animmovedist'} . 'px,0)';
				break;
				case 'bttck':
					$transform0[] = 'translate3d(0,' . (int)$fields->{$prefix . 'animmovedist'} . 'px,0)';
				break;
			}

			$transform100[] = 'translate3d(0,0,0)';
		}
		// rotate effect
		if ($fields->{$prefix . 'animrot'} == '1') {
			$transition['transform'] = (isset($transition['transform']) && $transition['transform']) ? $transition['transform'] : 'transform ' . $duration;
			$transform0[] = 'rotate(' . $fields->{$prefix . 'animrotrad'} . 'deg)';
			$transform100[] = 'rotate(0deg)';
		}
		// scale effect
		if ($fields->{$prefix . 'animscale'} == '1') {
			$transition['transform'] = (isset($transition['transform']) && $transition['transform']) ? $transition['transform'] : 'transform ' . $duration;
			$transform0[] = 'scale(0)';
			$transform100[] = 'scale(1)';
		}

		if (count($transition)) {
			// start
			$css .= $id . ' {
				-webkit-transition: ' . implode(', ', $transition) . ';
				transition: ' . implode(', ', $transition) . ';

				' . (count($transform0) ? '-webkit-transform: ' . implode(' ', $transform0) . ';
				transform: ' . implode(' ', $transform0) . ';' : '') . '
				' . implode(';', $style0) . ';
				 -webkit-transition-delay: ' . $delay . ';
				transition-delay: ' . $delay . ';
			}
			';
			// end
			$id = str_replace('|ID|', '|ID| .swiper-slide-active', $id);
			$css .= $id . ' {
				' . (count($transform0) ? '-webkit-transform: ' . implode(' ', $transform100) . ';
				transform: ' . implode(' ', $transform100) . ';' : '') . '
				' . implode(';', $style100) . ';
			}';
		}

		return $css;
	}

		/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	function testUnit($value, $defaultunit = "px") {

		if ((stristr($value, 'px')) OR (stristr($value, 'em')) OR (stristr($value, '%')) OR $value == 'auto')
			return $value;

		return $value . $defaultunit;
	}

	/**
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr (hexadecimal color value)
	 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
	 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
	 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
	 */
	function hex2RGB($hexStr, $opacity) {
		$opacity = $opacity < 1 ? $opacity : $opacity / 100;
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		$rgbacolor = "rgba(" . $rgbArray['red'] . "," . $rgbArray['green'] . "," . $rgbArray['blue'] . "," . $opacity . ")";

		return $rgbacolor;
	}
}


