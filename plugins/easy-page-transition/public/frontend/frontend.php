<?php

//Add styles to frontend based on which transition type was selected.
function cd_ept_transition_types_stylesheet() {

  if(get_option( 'easy_page_transition_type' ) == 1){
    $typeFileCSS = 'fade-out-fade-in';
  }elseif(get_option( 'easy_page_transition_type' ) == 2) {
    $typeFileCSS = 'fade-up';
  }elseif(get_option( 'easy_page_transition_type' ) == 3) {
    $typeFileCSS = 'fade-down';
  }elseif(get_option( 'easy_page_transition_type' ) == 4) {
    $typeFileCSS = 'fade-right';
  }elseif(get_option( 'easy_page_transition_type' ) == 5) {
    $typeFileCSS = 'fade-left';
  }elseif(get_option( 'easy_page_transition_type' ) == 6) {
    $typeFileCSS = 'swipe-top';
  }elseif(get_option( 'easy_page_transition_type' ) == 7) {
    $typeFileCSS = 'swipe-bottom';
  }elseif(get_option( 'easy_page_transition_type' ) == 8) {
    $typeFileCSS = 'swipe-right';
  }elseif(get_option( 'easy_page_transition_type' ) == 9) {
    $typeFileCSS = 'swipe-left';
  }else{
    $typeFileCSS = 'fade-out-fade-in';
  }


//Enqueue Styles
wp_enqueue_style( 'EasyPageTransitionStyles', plugins_url( 'transition-styles/'. $typeFileCSS .'.css', __FILE__ ) );

}

//Add Styles
add_action( 'wp_enqueue_scripts', 'cd_ept_transition_types_stylesheet' );




//Adding Scripts to frontend based on which transition type was selected.
function cd_ept_transition_types_scripts() {

    if(get_option( 'easy_page_transition_type' ) == 1 ||
    get_option( 'easy_page_transition_type' ) == 2 ||
    get_option( 'easy_page_transition_type' ) == 3 ||
    get_option( 'easy_page_transition_type' ) == 4 ||
    get_option( 'easy_page_transition_type' ) == 5 ) {
      $typeFileJS = 'fade';
    }elseif(get_option( 'easy_page_transition_type' ) == 6 ||
    get_option( 'easy_page_transition_type' ) == 7 ||
    get_option( 'easy_page_transition_type' ) == 8 ||
    get_option( 'easy_page_transition_type' ) == 9 ) {
      $typeFileJS = 'swipe';
    }else{
      $typeFileJS = 'fade';
    }


    //Enqueue Scripts
    wp_enqueue_script( 'EasyPageTransitionScripts', plugins_url( 'transition-scripts/'. $typeFileJS .'.js', __FILE__ ), array('jquery'), '1.0.0', true );
}

//AddingScripts
add_action('wp_enqueue_scripts', 'cd_ept_transition_types_scripts');




//If transition type is a swipe animations add Swipe element.
if(get_option( 'easy_page_transition_type' ) >= 6){

function cd_ept_swipe_element() { ?>

  <?php if(get_option( 'easy_page_transition_color_selector' ) != ''){
    $bgColor = 'style="background-color:'. get_option( 'easy_page_transition_color_selector' ) .';"';
  }else{
    $bgColor = '';
  } ?>

  <div class="ept_swipe" <?php echo $bgColor; ?>></div>

<?php }

add_action( 'wp_head', 'cd_ept_swipe_element' );

}




?>
