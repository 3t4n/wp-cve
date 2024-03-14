<?php

class ContactListPublicPagination {
  
  public static function getPagination($pagination_active, $wp_query, $embed_id) {

    $html = '';

    $s = get_option('contact_list_settings');

    if ( isset($s['pagination_type']) && $s['pagination_type'] == 'improved' ) {
    
      $paged_current = 1;
    
      if ($pagination_active && isset( $_GET['_page'] ) && $_GET['_page']) {
        $paged_current = (int) $_GET['_page'];
      }
      
      $pagination_args = array(
      
         'base'     => preg_replace('/\?.*/', '/', get_pagenum_link(1)) . '%_%',
      
         'total'    => $wp_query->max_num_pages,
         'current'  => max(1, $paged_current),
         'format'   => '?_page=%#%',
      
         'add_args' => array(
           '_paged' => $embed_id
         ));

      if (paginate_links($pagination_args)) {

        $html .= '<div class="contact-list-pagination-improved">';
  
        $html .= '<div class="contact-list-pagination-improved-more-files">' . sanitize_text_field( __('Browse contacts:', 'contact-list') ) . '</div>';
        
        $html .= paginate_links($pagination_args);
  
        $html .= '</div>';
        
      }

    } else {

      $html .= ContactListPublicHelpers::pagination($wp_query);    

    }

    return $html;

  }

}
