<?php
defined( 'EOS_CARDS_DIR' ) || exit; //exit if file not inclued by the plugin.
$this->enqueue_assets = true;

$opts = eos_cards_get_option();
$default_atts = $this->default_atts();
$dir = is_rtl() ? 'right' : 'left';

if( empty( $atts ) ){
  $atts = $default_atts;
}
foreach( $atts as $k => $v ){
  if( !isset( $atts[$k] ) ){
    $atts[$k] = $opts[$k];
  }
}
$cards_options = isset( $atts['deck'] ) && isset( $opts[$atts['deck']] ) ? $opts[$atts['deck']] : $default_atts;
foreach( $cards_options as $k => $v ){
  if( !isset( $cards_options[$k] ) ){
    $cards_options[$k] = $default_atts[$k];
  }
}
global $post;
$data = '';
foreach( $atts as $k => $v ){
  $data .= ' data-'.esc_attr( $k ).'="'.esc_attr( $v ).'"';
}
extract( shortcode_atts( $default_atts,apply_filters( 'oracle_cards_shortcode_atts',$atts ) ) );
$w = isset( $cards_options['card_width'] ) && absint( $cards_options['card_width'] ) > 0 ? absint( $cards_options['card_width'] ) : absint( $default_atts['card_width'] );
$h = isset( $cards_options['card_height'] ) && absint( $cards_options['card_height'] ) > 0 ? absint( $cards_options['card_height'] ) : absint( $default_atts['card_height'] );
$w = apply_filters( 'oracle_cards_shortcode_card_width',$w,$atts );
$h = apply_filters( 'oracle_cards_shortcode_card_height',$h,$atts );
$ratio = round( $h/$w,2 );
if( $deck === 'none' ) return;
if( 'deck' === $deck_type ){
  $deck_from = 999999999;
  $atts['deck_from'] = 999999999;
}
$button_class = isset( $atts['button_class'] ) && '' !== $atts['button_class'] ? ' '.esc_attr( $atts['button_class'] ) : $default_atts['button_class'];
if( 'remove' === $on_mobile && wp_is_mobile() ) return;
$maxnumber = $this->maxncards;
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
$admin_preview = false;
$cards = get_posts( $args );
if( isset( $_GET['card_id'] ) ){
  $admin_preview = true;
  $cards = get_posts( array( 'ID' => absint( $_GET['card_id'] ) ) );
  $maxN = min( 15,min( $maxnumber,count( $cards ) ) );
  $card = $preview_card = $cards[0];
}
else{
  $maxN = min( 15,min( $maxnumber,count( $cards ) ) );
  $card = $cards[rand(0,max(0,$maxN - 1))];
}

$js = '<script>function oc_get_random_card(){var rnd=Math.floor(Math.random()*'.esc_js( max( 0,count( $cards ) - 1 ) ).')';
$js_titles = ',titles=[';
$js_ids = ',ids=[';
$js_srcs = ',cards=[';
$js_links = ',links=[';
$card_ids = $links = $srcs = $titles = array();
foreach( $cards as $card ){
  $srcs[$card->ID] =  $admin_preview ? get_the_post_thumbnail_url( absint( $_GET['card_id'] ),'large' ) : get_the_post_thumbnail_url( $card,'large' );
  $card_ids[] = $admin_preview ? absint( $_GET['card_id'] ) : $card->ID;
  $titles[$card->ID] = $admin_preview ? esc_attr( get_the_title( absint( $_GET['card_id'] ) ) ) : esc_attr( $card->post_title );
}
$linksObj = $this->get_multiple_metadata( '_eos_linked_url_key',$card_ids );
if( $linksObj && is_array( $linksObj ) ) {
  foreach( $linksObj as $obj ){
    $links[$obj->post_id] = $obj->meta_value;
  }
}
foreach( $cards as $card ){
  $js_titles .= '"'.esc_js( $titles[$card->ID] ).'",';
  $js_srcs .= '"'.esc_js( $srcs[$card->ID] ).'",';
  $js_links .= ! empty( $links ) && isset( $links[$card->ID] ) ? '"'.esc_js( esc_url( $links[$card->ID] ) ).'",' : ',';
  $js_ids .= '"'.esc_js( $card->ID ).'",';
}
$js = $js.rtrim( $js_srcs,',' ).']'.rtrim( $js_links,',' ).']'.rtrim( $js_ids,',' ).']'.rtrim( $js_titles,',' ).'];window.all_cards_data = [cards,links,titles];return [cards[rnd],links[rnd],ids[rnd],titles[rnd]];}oc_get_random_card();';




if( !$cards ) return;
$distance = $distance > 0 ? absint( 10*$distance )/10 : 2;

$maxrand = min( 3,$maxrand );
if( !$custom_back_id ){
  $custom_back_id = isset( $cards_options['custom_back_card_id'] ) && 5 === $cards_options['def-back-card-choice'] ? $cards_options['custom_back_card_id'] : $default_atts['custom_back_card_id'];
}
$class = $class != '' ? ' '.$class : '';
$dir = is_rtl() ? 'right' : 'left';
if( $custom_back_id ){
  $img = wp_get_attachment_image_src( $custom_back_id,'large' );
  $src = $img && is_array( $img ) && isset( $img[0] ) ? $img[0] : EOS_CARDS_URL.'/admin/img/card-back-'.$cards_back.'.png';
}
else{
  $cards_back = isset( $cards_options['def-back-card-choice'] ) ? $cards_options['def-back-card-choice'] : $default_atts['def-back-card-choice'];
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
$border_radius = '' !== $border_radius ? $this->add_css_prefixes( 'border-radius',absint( $border_radius ).'px' ) : '';
foreach( $cards as $cardPost ){
  $margin = max( min( absint( $maxmargin ),( -10*$maxN + absint( 10*( 10*$nFloat + rand( 1,absint( $maxrand ) )/10 + ( $w/2 ) ) )/10 ) ),-absint( $maxmargin ) );
  $margin_rand_top = rand( -10,10 ) +  $n/6;
  $ofs += $margin;
  $extra_padding += $margin_rand_top;
  $output .= '<div id="eos-card-'.$n.'" class="eos-card card-in-deck" style="'.$visibility.'cursor:pointer;z-index:999;width:'.$w.'px;position:absolute;top:20px;'.$dir.':50%;margin-'.$dir.':'.$margin.'px;margin-top:'.$margin_rand_top.'px">';
  $rotation = -15 + ( min( 1, max(-1,cos( 360 * $n/$maxN ) ) )*10*$n/$maxN )/20 + 20*$n/$maxN;
  $output .= '<img style="'.$border.$border_radius.'transform:rotate('.$rotation.'deg)" class="card-back-img" width="'.$w.'" height="'.$h.'" alt="'.$card -> post_title.'" src="'.$src.'" />';
  $output .= '<img class="card-front-img" data-border_radius="'.$border_radius.'" style="'.$border_radius.'transform:rotate('.$rotation.'deg) scaleX(-1);display:none" width="'.$w.'" height="'.$h.'" alt="'.$card -> post_title.'" src="" />';
  $output .= '</div>';
  $n = $n + absint( $distance );
  $nFloat = $n + absint( 10*$distance )/10;
}
$output .= '</div>';
$output .= '<div class="card-front-wrp" style="z-index:-1;position:relative;overflow:hidden">';;
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$js .= 'var rnd_card=oc_get_random_card(),fc=document.getElementsByClassName("card-front-img");for(var c=0;c<fc.length;++c){fc[c].src = rnd_card[0];}</script>';
$output .= $js;
$extra_padding = round( $extra_padding/$maxN,1 ) + intval( $space_top_button );
if( !$admin_preview ){
  $output .= '<div style="margin-top:0" class="eos-mix-cards-wrp eos-hidden center"><input type="submit" class="refresh-cards button'.$button_class.'" style="white-space:normal" value="'.esc_attr( apply_filters( 'eos_cards_mix_button_text',$button_text_mix ) ).'" /></div>';
}
$output .= '<div id="eos-card-btn-wrp" style="margin-top:0" class="eos-hidden center"><input type="submit" style="white-space:normal" class="take-a-card button'.$button_class.'" value="'.esc_attr( apply_filters( 'eos_cards_pick_button_text',$button_text_pick ) ).'" /></div>';
$output .= '<div class="eos-card-content" style="padding-top:'.esc_attr( $space_top_text ).'px;min-height:0">';

if( isset( $atts['content_title'] ) && '' !== $atts['content_title'] ){
  $content_title_html = '<h2 class="cards-content-title eos-hidden">'.esc_html( $atts['content_title'] ).'</h2>';
  $output .= apply_filters( 'eos_cards_content_title_html',$content_title_html );
}
$output .= '</div>';
if( !isset( $_REQUEST['action'] ) || 'eos_mix_cards' !== $_REQUEST['action'] ){
  $output .= '</div>';
  $output .= '<style scoped>';
  $output .= '.eos-cards-deck-wrp{padding-bottom:'.( $extra_padding + $maxN + absint( $w * $ratio ) ).'px}';
  $output .= '.oracle-cards-wrapper .card-back-img,.oracle-cards-wrapper .card-front-img{width:'.$w.'px;height:'.$h.'px}';
  $output .= '@media screen and (max-width:'.( 100 + $w ).'px){';
  $output .= '.oracle-cards-wrapper .card-back-img,.oracle-cards-wrapper .card-front-img{max-width:100%;height:auto}';
  $output .= '.eos-cards-deck-wrp{padding-bottom:calc('.absint( 100 * $ratio ).'% - '.( -40 + round( ( absint( 100 * $ratio ) - 2*$extra_padding * ( 100 + $w )/$w ),1 ) ).'px)}';
  if( 'hide' === $on_mobile ){
    $output .= '.oracle-cards-wrapper{display:none !important}';
  }
  $output .= '}</style>';
  $output .= $js;
  $output .= '<!-- Oracle Cards End -->';
}
$this->shortcode_atts = $atts;
$this->default_atts = $default_atts;
$this->card_with = $w;
$this->card_ratio = $ratio;
