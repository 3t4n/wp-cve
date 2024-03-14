<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return <<<'SCRIPT'
var mobx_wp_gallery=function(){for(var t=document.querySelectorAll('.gallery[id*="gallery-"], .wp-block-gallery'),e=0,l=t.length;e<l;e++){t[e].removeAttribute("data-carousel-extra");for(var a=t[e].querySelectorAll(".gallery-icon > a, .blocks-gallery-item a"),r=0,i=a.length;r<i;r++){var n=a[r],o=n.firstElementChild,b=(o.title||o.alt)&&mobx_options.autoCaption,m=n.parentElement.nextElementSibling,c=n.nextElementSibling;m=m&&-1<m.className.indexOf("wp-caption-text")?m.innerHTML:"",c&&"FIGCAPTION"===c.tagName&&(m=c.innerHTML||m),o.setAttribute("data-rel","wp-gallery-"+(e+1)),o.setAttribute("data-title",b?o.title||o.alt:m||""),o.setAttribute("data-desc",b?m:""),o.setAttribute("data-thumb",o.src),mobx.addAttr(n,{rel:"wp-gallery-"+(e+1),title:b?o.title||o.alt:m||"",desc:b?m:"",thumb:o.src})}}};mobx_wp_gallery();
SCRIPT;
