<?php
if ( ! class_exists( 'WP_List_Table' ) ) :
  include_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
endif;

class PLZ_List_Forms extends WP_List_Table {
  public $per_page = 20;
  public $total_items = 0;

  public function prepare_items() {
    $hidden = array();
    $current_page = $this->get_pagenum();
    $this->items = $this->get_data( $current_page );
    $columns = $this->get_columns();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array( $columns, $hidden, $sortable );

    $this->set_pagination_args(
      array(
        'total_items' => $this->total_items,
        'per_page' => $this->per_page,
        'total_pages' => ceil( $this->total_items / $this->per_page ),
      )
    );
  }

  public function get_data( $current_page ) {
    $orderby = 'title';
    $order = 'asc';

    if ( isset( $_GET ) && isset( $_GET['orderby'] ) && ! empty( $_GET['orderby'] ) ) :
      $orderby = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
    endif;

    if ( isset( $_GET ) && isset( $_GET['order'] ) && ! empty( $_GET['order'] ) ) :
      $order = sanitize_text_field( wp_unslash( $_GET['order'] ) );
    endif;

    $options = array(
      'body'            => array(
        '_wpnonce'      => wp_create_nonce( 'wp_rest' ),
        'args'          => 'sort_by=' . $orderby . '&sort_dir=' . $order . '&page=' . $current_page . '&per_page=' . $this->per_page,
        'filters'       => array('sort_by' => $orderby, 'sort_dir' => $order, 'page' => $current_page, 'per_page' => $this->per_page )
      ),
      'headers'         => array(
        'Cache-Control' => 'no-cache',
      ),
      'cookies'         => plz_get_user_cookies()
    );

    $result = wp_remote_post( get_rest_url( null, 'plz/v2/configuration/get-forms-list' ), $options );
    $forms = json_decode( wp_remote_retrieve_body( $result ) );

    if ( isset( $forms->list ) && ! isset( $forms->error ) ) :
      $this->total_items = $forms->metas->total_results;

      return $forms->list;
    else :
      return false;
    endif;
  }

  public function get_columns() {
    $columns = array('name' => __('Name', 'plezi-for-wordpress'),
                     'edit' => __('Edit', 'plezi-for-wordpress'),
                     'plz_preview' => __('Preview', 'plezi-for-wordpress'),
                     'stats' => __('Stats', 'plezi-for-wordpress'),
                     'shortcode' => __('Shortcode', 'plezi-for-wordpress'),
                     'copy' => __('Copy', 'plezi-for-wordpress'),
                    );

    return $columns;
  }

  public function get_sortable_columns() {
    $sortable_columns = array(
      'name' => array('title', true),
    );

    return $sortable_columns;
  }

  public function column_default( $item, $column_name ) {	
    switch ( $column_name ) :
      case 'name':
        return $item->attributes->title;
      case 'edit':
        $content = '<a href="https://enjoy.plezi.co/resources/" title="' . esc_attr__( 'Edit', 'plezi-for-wordpress' ) . ' - ' . esc_attr( $item->attributes->title ) . '" target="_blank">';
        $content .= '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.08596 2.91345L13.0866 6.91407L4.3994 15.6013L0.832527 15.995C0.355027 16.0478 -0.0484106 15.6441 0.00471439 15.1666L0.401589 11.5972L9.08596 2.91345ZM15.561 2.31782L13.6825 0.439384C13.0966 -0.146553 12.1463 -0.146553 11.5603 0.439384L9.79315 2.20657L13.7938 6.2072L15.561 4.44001C16.1469 3.85376 16.1469 2.90376 15.561 2.31782Z" fill="#002D4F"/></svg>';
        $content .= '</a>';

        return $content;
      case 'plz_preview':
        $content = '<a href="#" title="' . esc_attr__( 'Preview', 'plezi-for-wordpress' ) . ' - ' . esc_attr( $item->attributes->title ) . '" form-id="' . esc_attr( $item->id ) . '" class="plz-preview-shortcode">';
        $content .= '<svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.9033 4.92779C14.397 1.98861 11.4147 0 8 0C4.58527 0 1.60221 1.99 0.0966517 4.92806C0.0331076 5.05376 0 5.19264 0 5.33348C0 5.47433 0.0331076 5.6132 0.0966517 5.7389C1.60304 8.67807 4.58527 10.6667 8 10.6667C11.4147 10.6667 14.3978 8.67668 15.9033 5.73862C15.9669 5.61292 16 5.47405 16 5.3332C16 5.19236 15.9669 5.05348 15.9033 4.92779ZM8 9.33335C7.20887 9.33335 6.43551 9.09875 5.77771 8.65923C5.11992 8.2197 4.60723 7.59499 4.30447 6.86408C4.00172 6.13317 3.92251 5.32891 4.07685 4.55298C4.23119 3.77706 4.61216 3.06432 5.17157 2.50491C5.73098 1.9455 6.44371 1.56454 7.21964 1.41019C7.99556 1.25585 8.79983 1.33507 9.53074 1.63782C10.2616 1.94057 10.8864 2.45326 11.3259 3.11106C11.7654 3.76886 12 4.54222 12 5.33334C12.0003 5.8587 11.897 6.37896 11.696 6.86438C11.4951 7.3498 11.2005 7.79086 10.829 8.16235C10.4575 8.53383 10.0165 8.82846 9.53104 9.02939C9.04562 9.23032 8.52536 9.33361 8 9.33335ZM8 2.66667C7.76198 2.67 7.5255 2.70541 7.29694 2.77195C7.48534 3.02797 7.57574 3.34303 7.55177 3.65999C7.52779 3.97695 7.39101 4.27483 7.16625 4.49959C6.94148 4.72436 6.64361 4.86113 6.32665 4.88511C6.00969 4.90909 5.69463 4.81868 5.43861 4.63029C5.29282 5.1674 5.31914 5.7367 5.51385 6.25807C5.70857 6.77944 6.06188 7.22662 6.52406 7.53667C6.98623 7.84672 7.534 8.00403 8.09027 7.98646C8.64653 7.96889 9.18328 7.77732 9.62497 7.43871C10.0667 7.10011 10.391 6.63152 10.5525 6.0989C10.7139 5.56628 10.7042 4.99645 10.5248 4.46961C10.3454 3.94278 10.0053 3.48546 9.5524 3.16204C9.09948 2.83861 8.55654 2.66536 8 2.66667Z" fill="#002D4F"/></svg>';
        $content .= '</a>';

        return $content;
      case 'stats':
        $content = '<a href="https://enjoy.plezi.co/resources/" title="' . esc_attr__( 'Stats', 'plezi-for-wordpress' ) . ' - ' . esc_attr( $item->attributes->title ) . '" target="_blank">';
        $content .= '<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.5 10H2V0.5C2 0.22375 1.77625 0 1.5 0H0.5C0.22375 0 0 0.22375 0 0.5V11C0 11.5522 0.447812 12 1 12H15.5C15.7762 12 16 11.7762 16 11.5V10.5C16 10.2238 15.7762 10 15.5 10ZM14.5 1H10.8106C10.1425 1 9.80781 1.80781 10.2803 2.28031L11.2928 3.29281L9 5.58594L6.70719 3.29313C6.31656 2.9025 5.68344 2.9025 5.29313 3.29313L3.14656 5.43969C2.95125 5.635 2.95125 5.95156 3.14656 6.14688L3.85344 6.85375C4.04875 7.04906 4.36531 7.04906 4.56063 6.85375L6 5.41406L8.29281 7.70687C8.68344 8.0975 9.31656 8.0975 9.70687 7.70687L12.7069 4.70687L13.7194 5.71938C14.1919 6.19187 14.9997 5.85719 14.9997 5.18906V1.5C15 1.22375 14.7762 1 14.5 1Z" fill="#002D4F"/></svg>';
        $content .= '</a>';

        return $content;
      case 'shortcode':
        return '[plezi form=' . esc_attr( $item->id ) . ']';
      case 'copy':
        $content = '<a href="#" title="' . esc_attr__( 'Copy', 'plezi-for-wordpress' ) . ' - ' . esc_attr( $item->attributes->title ) . '" form-id="' . esc_attr( $item->id ) . '" class="plz-copy-shortcode">';
        $content .= '<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 14V15.25C10 15.6642 9.66422 16 9.25 16H0.75C0.335781 16 0 15.6642 0 15.25V3.75C0 3.33578 0.335781 3 0.75 3H3V12.25C3 13.215 3.78503 14 4.75 14H10ZM10 3.25V0H4.75C4.33578 0 4 0.335781 4 0.75V12.25C4 12.6642 4.33578 13 4.75 13H13.25C13.6642 13 14 12.6642 14 12.25V4H10.75C10.3375 4 10 3.6625 10 3.25ZM13.7803 2.28034L11.7197 0.219656C11.579 0.0790133 11.3882 1.03999e-06 11.1893 0L11 0V3H14V2.81066C14 2.61175 13.921 2.42099 13.7803 2.28034Z" fill="#002D4F"/></svg>';
        $content .= '</a>';

        return $content;
    default:
      return $item->id;
    endswitch;
  }

  public function get_table_classes() {
    return array();
  }
}
