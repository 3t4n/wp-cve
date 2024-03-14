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
var mobx_wp_images=function(){for(var e=document.querySelectorAll('a > img[class*="wp-image-"]'),t=0,a=e.length;t<a;t++){var n=e[t],i=n.parentElement,l=(n.title||n.alt)&&mobx_options.autoCaption,r=i.nextElementSibling,m=i.nextElementSibling;r=r&&r.className.indexOf("wp-caption-text")>-1?r.innerHTML:"",m&&"FIGCAPTION"===m.tagName&&(r=m.innerHTML||r),n.setAttribute("data-src",i.href),mobx.addAttr(n,{title:l?n.title||n.alt:r||"",desc:l?r:""})}};jQuery(document).ready(function(){jQuery(".single-image-gallery").removeData("carousel-extra"),mobx_wp_images()});
SCRIPT;
