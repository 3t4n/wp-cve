<?php
function moonClerk_script($form_id, $width = '100%') {
  $moonClerk_form = $form_id;
  $moonClerk_scripts = "<script type='text/javascript'>var mc{$moonClerk_form};(function(d,t) {var s=d.createElement(t),opts={'checkoutToken':'{$moonClerk_form}','width':'$width'};s.src='https://d2l7e0y6ygya2s.cloudfront.net/assets/embed.js';s.onload=s.onreadystatechange = function() {var rs=this.readyState;if(rs) if(rs!='complete') if(rs!='loaded') return;try {mc{$moonClerk_form}=new MoonclerkEmbed(opts);mc{$moonClerk_form}.display();} catch(e){}};var scr=d.getElementsByTagName(t)[0];scr.parentNode.insertBefore(s,scr);})(document,'script');</script>
";

  $code = $moonClerk_scripts;
  return $code;
}

function moonClerk_short($attr = array(), $content = null) {
  $form_id = '';

  // ID
  if( isset($attr['id']) ) :
    $id = esc_attr( $attr['id'] );
    $form_id = $id;
    $id = "id=\"mc{$id}\"";
  else :
    $id = '';
  endif;

  // Content
  if(!isset($content)) {
    $content = 'Pay';
  }

  // Width
  if( isset($attr['width']) ) :
    $width = esc_attr( $attr['width'] );
  else :
    $width = '100%';
  endif;

  // Class
  if( isset($attr['class']) ) :
    $class = esc_attr( $attr['class'] );
    $class = "class=\"{$class}\"";
  else :
    $class = 'class="button"';
  endif;

  // Target
  if( isset($attr['tab']) ) :
    $target = "target=\"_blank\"";
  else :
    $target = '';
  endif;

  $code = "<div $id $class ><a $target href='https://app.moonclerk.com/pay/{$attr['id']}'>$content</a></div>" . moonClerk_script($form_id, $width);
  return $code;
}
add_shortcode('moonclerk','moonClerk_short');
