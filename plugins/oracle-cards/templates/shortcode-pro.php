<?php
defined( 'EOS_CARDS_DIR' ) || exit; //exit if file not inclued by the plugin

$opts = eos_cards_get_options();
$defaults = eos_cards_default_deck_options();
$default_atts = eos_cards_default_atts();
$dir = is_rtl() ? 'right' : 'left';
if( empty( $atts ) ){
  $atts = $default_atts;
}
$cards_options = isset( $atts['deck'] ) && isset( $opts[$atts['deck']] ) ? $opts[$atts['deck']] : $defaults;
global $post;
$data = '';
foreach( $atts as $k => $v ){
  $data .= ' data-'.esc_attr( $k ).'="'.esc_attr( $v ).'"';
}
extract( shortcode_atts( $default_atts, $atts ) );
$w = isset( $cards_options['card_width'] ) ? $cards_options['card_width'] : $defaults['card_width'];
$h = isset( $cards_options['card_height'] ) ? $cards_options['card_height'] : $defaults['card_height'];
$ratio = round( $h/$w,2 );
if( $card_width && $card_height ){
  $w = absint( $card_width );
  $h = absint( $card_height );
}
if( $deck === 'none' ) return;
if( 'deck' === $deck_type ){
  $deck_from = 999999999;
  $atts['deck_from'] = 999999999;
}
if( 'remove' === $on_mobile && wp_is_mobile() ) return;
$args = array(
  'posts_per_page' => absint( $maxnumber ),
  'post_type' => 'card',
  'orderby' => 'rand',
  'taxonomy' => 'decks',
  'tax_query' => array(
    array(
      'taxonomy' => 'decks',
      'field' => 'id',
      'terms' => esc_attr( $deck ),
    )
  )
);
$cards = get_posts( $args );
$maxN = min( $maxnumber,count( $cards ) );
$admin_preview = false;
if( is_admin() && isset( $_GET['page'] ) && 'oracle-card-preview' === $_GET['page'] && isset( $_GET['card_id'] ) && current_user_can( 'edit_others_posts' ) ){
  $admin_preview = true;
  $card = get_post( absint( $_GET['card_id'] ) );
}
else{
  $card = $cards[rand(0,max(0,$maxN - 1))];
}
if( !$cards ) return;
$distance = absint( $distance ) > 0 ? absint( 10*$distance )/10 : 2;
$maxrand = min( 3,$maxrand );
if( !$custom_back_id ){
  $custom_back_id = isset( $cards_options['custom_back_card_id'] ) && 5 === $cards_options['def-back-card-choice'] ? $cards_options['custom_back_card_id'] : $defaults['custom_back_card_id'];
}
$class = $class != '' ? ' '.$class : '';
$dir = is_rtl() ? 'right' : 'left';
if( $custom_back_id ){
  $img = wp_get_attachment_image_src( $custom_back_id,'large' );
  $src = $img[0];
}
else{
  $cards_back = isset( $cards_options['def-back-card-choice'] ) ? $cards_options['def-back-card-choice'] : $defaults['def-back-card-choice'];
  $src = EOS_CARDS_URL.'/admin/img/card-back-'.$cards_back.'.png';
}
$deck_id = 'oc-deck-'.$deck.'-'.uniqid();
$params = array(
  'type' => $deck_type,
  'card_width' => absint( $w ),
  'ratio' => $ratio,
  'direction' => is_rtl() ? 'right' : 'left',
  'show_title' => 'true' !== $show_title ? 'false' : 'true',
  'title_alignment' => in_array( $title_alignment,array( 'left','center','right' ) ) ? $title_alignment : 'initial',
  'ajax_loader' => EOS_CARDS_ASSETS_URL.'/img/ajax-loader.gif',
  'ajaxurl' => admin_url( 'admin-ajax.php' ),
  'is_admin_preview' => $admin_preview,
  'deck_from' => absint( $deck_from )
);
$dataset = '';
foreach( $params as $param => $value ){
  $dataset .= ' data-'.$param.'="'.esc_attr( $value ).'"';
}
$output = '<div style="height:1px;width:1px;overflow:hidden;position:absolute;left:-99999px;top:-99999px"><img src="'.esc_url( $src ).'" /></div>';
if( !isset( $_REQUEST['action'] ) || 'eos_mix_cards' !== $_REQUEST['action'] ){
  $output .= '<!-- Oracle Cards, Emotional Online Storytelling -->';
  $output .= '<div id="'.esc_attr( $deck_id ).'" class="oracle-cards-wrapper" style="padding-top:'.esc_attr( $space_top ).'px"'.$dataset.' data-clicked="0"'.$data.'" >';
}
$extra_padding = 0;
$output .= '<div class="oracle-cards"><div class="eos-cards-deck-wrp eos-cards-fan'.esc_attr( $class ).'" style="position:relative;height:0">';
$visibility = isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'],array( 'elementor','elementor_ajax','render_widget' ) ) ? '' : 'visibility:hidden;';
$output .= '<div class="card-back-wrp" style="'.$visibility.'position:relative">';
$n = 0;
$nFloat = 0;
$ofs = 0;
$back_border_color = '' !== $back_border_color ? ' '.$back_border_color : '';
$border = 'yes' === $back_border ? 'border:1px solid'.esc_attr( $back_border_color ).';' : '';
$border_radius = '' !== $border_radius ? eos_cards_add_css_prefixes( 'border-radius',absint( $border_radius ).'px' ) : '';
foreach( $cards as $cardPost ){
  $link = get_post_meta( $card->ID,'_eos_linked_url_key',true );
  $margin = max( min( absint( $maxmargin ),( -10*$maxN + absint( 10*( 10*$nFloat + rand( 1,absint( $maxrand ) )/10 + ( $w/2 ) ) )/10 ) ),-absint( $maxmargin ) );
  $margin_rand_top = rand( -10,10 ) +  $n/6;
  $ofs += $margin;
  $extra_padding += $margin_rand_top;
  $output .= '<div id="eos-card-'.$n.'" class="eos-card card-in-deck" data-link="'.esc_url( $link ).'" data-content="'.esc_attr( do_shortcode( $card -> post_content ) ).'" data-title="'.esc_attr( $card -> post_title ).'" style="'.$visibility.'cursor:pointer;z-index:999;width:'.$w.'px;position:absolute;top:20px;'.$dir.':50%;margin-'.$dir.':'.$margin.'px;margin-top:'.$margin_rand_top.'px">';
  $rotation = -15 + ( min( 1, max(-1,cos( 360 * $n/$maxN ) ) )*10*$n/$maxN )/20 + 20*$n/$maxN;
  $output .= '<img style="'.$border.$border_radius.'transform:rotate('.$rotation.'deg)" class="card-back-img" width="'.$w.'" height="'.$h.'" alt="'.$card -> post_title.'" src="'.$src.'" />';
  $output .= '<img class="card-front-img" style="'.$border_radius.'transform:rotate('.$rotation.'deg) scaleX(-1);display:none" width="'.$w.'" height="'.$h.'" alt="'.$card -> post_title.'" src="'.get_the_post_thumbnail_url( $card,'large' ).'" />';
  $output .= '</div>';
  $n = $n + absint( $distance );
  $nFloat = $n + absint( 10*$distance )/10;
}
$output .= '</div>';
$output .= '<div class="card-front-wrp" style="z-index:-1;position:relative;overflow:hidden">';;
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$extra_padding = round( $extra_padding/$maxN,1 ) + intval( $space_top_button );
if( !$admin_preview ){
  $output .= '<div id="eos-mix-cards-wrp" style="margin-top:0" class="eos-hidden center"><input type="submit" class="refresh-cards button" style="white-space:normal" value="'.esc_attr( apply_filters( 'eos_cards_mix_button_text',$button_text_mix ) ).'" /></div>';
}
$output .= '<div id="eos-card-btn-wrp" style="margin-top:0" class="eos-hidden center"><input type="submit" style="white-space:normal" class="take-a-card button" value="'.esc_attr( apply_filters( 'eos_cards_pick_button_text',$button_text_pick ) ).'" /></div>';
$output .= '<div id="eos-card-content" style="padding-top:'.esc_attr( $space_top_text ).'px;min-height:0"></div>';
if( !isset( $_REQUEST['action'] ) || 'eos_mix_cards' !== $_REQUEST['action'] ){
  $output .= '</div>';
  $output .= '<style scoped>';
  $output .= '.eos-cards-deck-wrp{padding-bottom:'.( $extra_padding + $maxN + absint( $w * $ratio ) ).'px}';
  $output .= '@media screen and (max-width:'.( 100 + $w ).'px){';
  $output .= '.eos-cards-deck-wrp{padding-bottom:calc('.absint( 100 * $ratio ).'% - '.( -40 + round( ( absint( 100 * $ratio ) - 2*$extra_padding * ( 100 + $w )/$w ),1 ) ).'px)}';
  if( 'hide' === $on_mobile ){
    $output .= '.oracle-cards-wrapper{display:none !important}';
  }
  $output .= '}</style>';
  $output .= '<!-- Oracle Cards End -->';
}
eos_cards_sht_script( $atts,$default_atts,false,$w,$ratio );
