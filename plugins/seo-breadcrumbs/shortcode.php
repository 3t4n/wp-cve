<?php
if ( ! shortcode_exists( 'seo-breadcrumbs' ) ) {
   if ( function_exists( 'seo_breadcrumbs' )) {
add_shortcode( 'seo-breadcrumbs', 'seo_breadcrumbs' );
}
}
?>