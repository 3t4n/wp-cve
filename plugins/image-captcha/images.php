<?php
function image_captcha_names( $new_image = array() ) {
  $images = array(
              array('image' => '06-03.png','answers' => array('сыр', 'cheese')),
              array('image' => '06-06.png','answers' => array('пицца', 'pizza')),
              array('image' => '06-11.png','answers' => array('хот дог', 'hot dog')),
              array('image' => '06-15.png','answers' => array('апельсин', 'orange')),
              array('image' => '06-16.png','answers' => array('пиво', 'beer')),
              array('image' => '06-18.png','answers' => array('мороженное', 'ice cream')),
              array('image' => '06-26.png','answers' => array('морковка', 'carrot')),
              array('image' => '06-27.png','answers' => array('лук', 'onion')),
              array('image' => '06-28.png','answers' => array('виноград', 'grapes')),
              array('image' => '06-33.png','answers' => array('яйца', 'eggs')),
              array('image' => '06-34.png','answers' => array('рак', 'cancer')),
              array('image' => '06-43.png','answers' => array('курица', 'chicken')),
              array('image' => '06-47.png','answers' => array('краб', 'crab')),
              array('image' => '06-50.png','answers' => array('кальмар', 'squid')),
              array('image' => '06-57.png','answers' => array('вишня', 'cherry')),
              array('image' => '06-61.png','answers' => array('конфета', 'candy')),
              array('image' => '06-75.png','answers' => array('арбуз', 'watermelon')),
              array('image' => '06-85.png','answers' => array('корова', 'cow')),
              array('image' => '06-89.png','answers' => array('свинья', 'pig')),
              array('image' => '06-91.png','answers' => array('штопор', 'corkscrew')),
            );
  if( ! empty( $new_image ) ) {
    array_push( $images, $new_image );
  }
  return $images;
}