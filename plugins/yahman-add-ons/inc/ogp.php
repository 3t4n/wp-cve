<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_meta_ogp($ogp,$sns_account,$meta_description){

  

  $url = $description = $title = $image = '';

  $meta['url'] = $meta['description'] = $meta['title'] = $meta['image'] = $meta['type'] = '';

  $ogp_logo = !empty($ogp['image']) ? $ogp['image'] : YAHMAN_ADDONS_URI . 'assets/images/ogp.jpg';

  if ( is_singular() ){
    $meta['type'] = 'article';
    $meta['url'] = get_the_permalink();
    $meta['title'] = get_the_title();

    if($meta_description){
      $meta['description'] = mb_strimwidth( $meta_description, 0 , 150, '&hellip;' );

    }else{

      if(have_posts()): while(have_posts()): the_post();
        $meta['description'] = mb_strimwidth( wp_strip_all_tags(strip_shortcodes(get_the_content()), true), 0 , 150, '&hellip;' );
      endwhile; endif;

    }

    if(has_post_thumbnail()) {
      $meta['image'] = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full' );
      $meta['image'] = $meta['image'][0];
    }else{
      $meta['image'] = $ogp_logo;
    }

  }else{
    
    $meta['type'] = 'website';

    $meta['image'] = $ogp_logo;



    if( $meta_description ){

      $meta['description'] = $meta_description;

    }else{

      $meta['description'] = get_the_archive_description();
      if($meta['description'] === '' )
        $meta['description'] = get_bloginfo ( 'description' );

    }

    if(is_home()){
      
      $meta['url'] = home_url();
      $meta['title'] = get_bloginfo('name');

    }else{

      $meta['title'] = str_replace( ' &#8211; '.get_bloginfo('name') , '' , wp_get_document_title() );


      $meta['url'] = strtok ( ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] , '?' );

    }

  }

  $get_locale = get_locale();
  if ($get_locale === 'ja'){
    $get_locale = 'ja_JP';
  }

  echo '<meta property="og:url" content="'.esc_url($meta['url']).'" />'."\n";
  echo '<meta property="og:type" content="'.esc_attr($meta['type']).'" />'."\n";
  echo '<meta property="og:title" content="'.esc_attr($meta['title']).'" />'."\n";
  echo '<meta property="og:description" content="'.esc_attr($meta['description']).'" />'."\n";
  echo '<meta property="og:image" content="'.esc_url($meta['image']).'" />'."\n";
  echo '<meta property="og:site_name" content="'.esc_attr(get_bloginfo('name')).'" />'."\n";
  echo '<meta property="og:locale" content="'.esc_attr($get_locale).'" />'."\n";
  if ( $sns_account['facebook_app_id'] != ''){
    echo '<meta property="fb:app_id" content="'.esc_attr($sns_account['facebook_app_id']).'" />'."\n";
  }
  if ( $sns_account['facebook_admins'] != ''){
    echo '<meta property="fb:admins" content="'.esc_attr($sns_account['facebook_admins']).'" />'."\n";
  }

  
  $twitter_card = isset($ogp['twitter_card']) ? $ogp['twitter_card'] : false;

  if ( !$twitter_card ) return;

  $twitter_user_name = isset($sns_account['twitter']) ? $sns_account['twitter'] : false;

  if ( !$twitter_user_name ) return;

  $twitter_user_name = '@' . str_replace( '@' , '' , $twitter_user_name );
  echo '<meta name="twitter:card" content="'.esc_attr($twitter_card).'" />'."\n";
  echo '<meta name="twitter:site" content="'.esc_attr($twitter_user_name).'" />'."\n";



}

