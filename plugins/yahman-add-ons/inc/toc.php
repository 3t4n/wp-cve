<?php
defined( 'ABSPATH' ) || exit;
/**
 * Table of contents
 *
 * @package YAHMAN Add-ons
 */




function yahman_addons_toc($the_content,$option) {
  $toc['title'] = apply_filters('yahman_addons_toc_title', isset($option['toc']['title']) ? esc_html($option['toc']['title']) : esc_html_x( 'Table of contents', 'toc' , 'yahman-add-ons' ) );
  
  $toc['dc'] = isset($option['toc']['dc']) ? (int)$option['toc']['dc'] : 3;
  
  
  $toc['dp'] = isset($option['toc']['dp']) ? (int)$option['toc']['dp'] : 1;

  $toc['hierarchical'] = isset($option['toc']['hierarchical']) ? true: false;
  $toc['numerical'] = isset($option['toc']['numerical']) ? true: false;
  $toc['hide'] = isset($option['toc']['hide']) ? true: false;
  $toc['page_break'] = isset($option['toc']['nextpage']) ? true: false;

  $toc['widget'] = isset($option['widget']['toc']) ? true: false;

  
  $toc_data['page_break_content'] = false;
  $toc_data['page_permalink'] = array();
  $toc_data['yahman_themes'] = '';

  $heading_num = '';
  $heading_title = '';



  if( $toc['page_break'] ){

    global $post;

    if ( preg_match('$<!--nextpage-->$', $post->post_content) ) {

      $heading = array();

      
      $heading_count = preg_match_all( '/<h([1-6]).*?>(.*?)<\/h[1-6].*?>/iu', $post->post_content, $heading );

      
      if( $heading_count >= $toc['dc'] ) {
        $toc_data['page_break_content'] = true;
        $i = 0;

        
        $permalink = trailingslashit( get_permalink($post->ID) );

        
        
        if ( get_query_var('paged') ) { $now_page = get_query_var('paged'); }
        elseif ( get_query_var('page') ) { $now_page = get_query_var('page'); }
        else { $now_page = 1; }

        
        $now_page = $now_page - 1;

        
        $pages = explode('<!--nextpage-->', $post->post_content);

        foreach ($pages as $page_num => $value) {

          
          $page_heading_count = preg_match_all( '/<h([1-6]).*?>(.*?)<\/h[1-6].*?>/iu', $value, $page_heading );

          for($j = 0; $j < $page_heading_count; $j++){

            if($page_heading_count !== 0){


              if( $page_num === 0 ) {

                $toc_data['page_permalink'][$i] = $permalink;

              }else{

                $toc_data['page_permalink'][$i] = $permalink . ($page_num + 1) . '/';

              }

              
              if( $page_num === $now_page ) $toc_data['page_permalink'][$i] = '';

              
              if( $page_num === $now_page ) {
                $heading_num = $page_heading[1][0];
                $heading_title = $page_heading[2][0];
              }

              
              if( $page_num === $now_page + 1  ) {
                $toc_data['yahman_themes'] = $page_num;
              }


            }

            ++$i;
          }

        }






      }

    }

  }

  
  if( !$toc_data['page_break_content'] ){
    $heading = array();
    $heading_count = preg_match_all( '/<h([1-6]).*?>(.*?)<\/h[1-6].*?>/iu', $the_content, $heading );
  }

  
  
  if( $heading_count < $toc['dc'] && !$toc_data['page_break_content']) return $the_content;

    //$numif = $toc['numerical'] ? '1' : "0" ;
    //$hieif = $toc['hierarchical'] ? '1' : "0" ;

  $toc_html['caret_right'] = '<svg width="10" height="10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="svg_icon" d="M18.8,12c0,0.4-0.2,0.8-0.4,1.1L7.8,23.6C7.5,23.8,7.1,24,6.8,24c-0.8,0-1.5-0.7-1.5-1.5v-21C5.3,0.7,5.9,0,6.8,0 c0.4,0,0.8,0.2,1.1,0.4l10.5,10.5C18.6,11.2,18.8,11.6,18.8,12z"></path></svg>';
  $toc_html['caret_down'] = '<svg width="10" height="10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="svg_icon" d="M24,6.8c0,0.4-0.2,0.8-0.4,1.1L13.1,18.3c-0.3,0.3-0.7,0.4-1.1,0.4s-0.8-0.2-1.1-0.4L0.4,7.8C0.2,7.5,0,7.1,0,6.8 c0-0.8,0.7-1.5,1.5-1.5h21C23.3,5.3,24,5.9,24,6.8z"></path></svg>';

  $toc_html['header'] = '<nav id="toc" class="toc p10 p12 mb_L dib shadow_box"><input id="tog_toc" type="checkbox"'. (!$toc['hide'] ? '': ' checked' ) .' /><div class="toc_ctrl f_box"><label for="tog_toc" class="toc_view toc_caret toc_lab ta_c dib fw2 fs14 tap_no">'.$toc_html['caret_right'].'</label><label for="tog_toc" class="toc_hide toc_caret toc_lab ta_c dib fw2 fs14 tap_no">'.$toc_html['caret_down'].'</label> <label for="tog_toc" class="toc_title toc_lab fw8 fs14 w100 tap_no">'.esc_html($toc['title']).'</label></div>'."\n";
  $toc_html['footer'] = '</nav>';
  $toc_html['content'] = '';




  if($toc['hierarchical']){
    
    $toc_back_data = yahman_addons_toc_hierarchical($the_content,$heading,$toc['numerical'],$toc_data );
  }else{
    
    $toc_back_data = yahman_addons_toc_non_hierarchical($the_content,$heading, $toc['numerical'],$toc_data );
  }

  $toc_html['content'] = $toc_back_data['html_content'];
  $the_content = $toc_back_data['the_content'];

  
  if( !$toc['page_break'] || !$toc_data['page_break_content'] ){

    $heading_num = $heading[1][0];
    $heading_title = $heading[2][0];

  }

  
  $pattern = '{<h'.$heading_num.'(.*?)>(.*?)'.yahman_addons_toc_special_character_replace($heading_title).'(.*?)<\/h'.$heading_num.'>}ismu';
  if($toc['dp'] === 1 ){
    
    $replacement = $toc_html['header'].$toc_html['content'].$toc_html['footer']."\n".'<h'.$heading_num.'$1>${2}'.$heading_title.'$3</h'.$heading_num.'>';
    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
  }else if ($toc['dp'] === 2 ){
    
    $replacement = '<h'.$heading_num.'$1>${2}'.$heading_title.'$3</h'.$heading_num.'>'.$toc_html['header'].$toc_html['content'].$toc_html['footer'];
    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
  }else{
    
    $the_content  = $toc_html['header'].$toc_html['content'].$toc_html['footer'].$the_content;
  }

  set_query_var('yahman_addons_toc', true);

  if( $toc['widget'] && is_active_widget( false, false, 'ya_toc_widget', true ) && $toc_html['content'] !== ''){
    $pattern = '/<input id="toggle_toc".*?<\/label>\]<\/div>/iu';
    $toc_html['header']  = '<div class="toc_widget">';
    $toc_html['footer']  = '</div>';
    set_query_var( 'yahman_addons_toc_html', $toc_html['header'].$toc_html['content'].$toc_html['footer'] );

  }
  
  if(!YAHMAN_ADDONS_TEMPLATE){
    add_action( 'wp_footer', 'yahman_addons_enqueue_style_toc' );
  }

  return $the_content;

}

function yahman_addons_toc_special_character_replace($replace) {
  
  $brackets_search = array(
    '\\',
    '?',
    '*',
    '+',
    '.',
    '(',
    ')',
    '{',
    '}',
    '[',
    ']',
    '^',
    '$',
    '-',
    '|',
    '=',
    '!',
    '<',
    '>',
    ':',
  );
  $brackets_replace = array(
    '\\\\',
    '\?',
    '\*',
    '\+',
    '\.',
    '\(',
    '\)',
    '\{',
    '\}',
    '\[',
    '\]',
    '\^',
    '\$',
    '\-',
    '\|',
    '\=',
    '\!',
    '\<',
    '\>',
    '\:',
  );
  return str_replace($brackets_search,$brackets_replace,$replace);
}

function yahman_addons_toc_hierarchical($the_content,$heading, $is_numerical,$toc_data) {

  
  

  $toc_data['top_level'] = 6;
  foreach($heading[1] as $temp){
    
    if($temp < $toc_data['top_level']) $toc_data['top_level'] = (int)$temp;
  }

  $heading_no[1] = $heading_no[2] = $heading_no[3] = $heading_no[4] = $heading_no[5] = $heading_no[6] = $num_heading[1] = $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;

  $before_level = $toc_data['top_level'];
  $after_level = (int)$heading[1][1];

  $numerical = '';

  $toc_back_data['html_content'] = '<ul class="toc_ul fs17 m0" style="list-style:none;">';

  foreach( $heading[1] as $no => $now ){

    $now = (int)$now;

    if( !$toc_data['page_break_content'] ) $toc_data['page_permalink'][$no] = '';

    $num_heading[$now]++;
    $heading_no[$now]++;
    $link_number = $heading_no[$now];
    $currnt_level = $now;


    $pattern = '{<h'.$now.'(.*?)>'.preg_quote($heading[2][$no]).'<\/h'.$now.'>}isum';
    $replacement = '<h'.$now.'$1><span id="heading'.$now.'_'.$link_number.'">'.$heading[2][$no].'</span></h'.$now.'>';

    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
    
    if($is_numerical){
      switch ($now){
        case 1:
        $numerical = $num_heading[1].' ';
        $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 2:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).$num_heading[2].' ';
        $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 3:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).$num_heading[3].' ';
        $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 4:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).$num_heading[4].' ';
        $num_heading[5] = $num_heading[6] = 0;
        break;
        case 5:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).($toc_data['top_level'] < 5 ? $num_heading[4].'.' : '' ).$num_heading[5].' ';
        $num_heading[6] = 0;
        break;
        case 6:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).($toc_data['top_level'] < 5 ? $num_heading[4].'.' : '' ).($toc_data['top_level'] < 6 ? $num_heading[5].'.' : '' ).$num_heading[6].' ';
        break;
        default:
        $numerical = '';
      }
      
    }

    if($before_level === $currnt_level){
      $toc_back_data['html_content'] .= '<li>'.esc_html($numerical).'<a href="'.esc_url($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }else if ($currnt_level > $before_level ){

      while($currnt_level !== $before_level){
        $toc_back_data['html_content'] .= '<ul><li>';
        $currnt_level-- ;
      }
      $toc_back_data['html_content'] .= esc_html($numerical).'<a href="'.esc_url($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }else{
      
      $toc_back_data['html_content'] .= '<li>'.esc_html($numerical).'<a href="'.esc_url($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }
    $before_level = $now;

    if(isset($heading[1][$no + 1])) $after_level = (int)$heading[1][$no + 1];

    if($before_level === $after_level){
      $toc_back_data['html_content'] .= '</li>'."\n";

    }else if($before_level > $after_level){

      $diff_level = $before_level - $after_level;
      while($diff_level > 0){
        $toc_back_data['html_content'] .= '</li></ul></li>'."\n";
        $diff_level-- ;
      }

      $toc_back_data['html_content'] .= ''."\n";
      

    }else{

      $toc_back_data['html_content'] .= "\n";
      
    }

  }

  if ($before_level > $toc_data['top_level']){
    $toc_back_data['html_content'] .= '</li>'."\n";
    $diff_level = $before_level;
    while($diff_level > 2){
      $toc_back_data['html_content'] .= '</ul></li>'."\n";
      $diff_level-- ;
    }
  }

  $toc_back_data['html_content'] .= '</ul>'."\n";

  $toc_back_data['the_content'] = $the_content;

  return $toc_back_data;

}

function yahman_addons_toc_non_hierarchical($the_content,$heading, $is_numerical,$toc_data) {


  $ulol = $is_numerical ? 'ol' : 'ul';
  $toc_back_data['html_content'] = '<'.esc_attr($ulol).' class="toc_ul'.($ulol === 'ol' ? ' toc_ol' : '').' fs17 m0" style="list-style:none;">';

  $heading_no[1] = $heading_no[2] = $heading_no[3] = $heading_no[4] = $heading_no[5] = $heading_no[6] = $num_heading[1] = $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;

  foreach($heading[1] as $no => $now ){

    $now = (int)$now;
    if( !$toc_data['page_break_content'] ) $toc_data['page_permalink'][$no] = '';

    $heading_no[$now]++;
    $link_number = $heading_no[$now];


    $pattern = '{<h'.$now.'(.*?)>'.yahman_addons_toc_special_character_replace($heading[2][$no]).'<\/h'.$now.'>}isum';

    $replacement = '<h'.$now.'$1><span id="heading'.$now.'_'.$link_number.'">'.$heading[2][$no].'</span></h'.$now.'>';

    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
    $toc_back_data['html_content'] .= '<li><a href="'.esc_url($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number).'">'.wp_strip_all_tags($heading[2][$no]).'</a></li>'."\n";

  }
  $toc_back_data['html_content'] .= '</'.esc_attr($ulol).'>'."\n";

  $toc_back_data['the_content'] = $the_content;

  return $toc_back_data;

}
