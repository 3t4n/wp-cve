<?php
class WPFingerprint_Settings_Table extends WP_List_Table
{
  function __construct()
  {
    parent::__construct( array (
			'singular' => __( 'Plugin', 'wpfingerprint' ),
			'plural'   => __( 'Plugins', 'wpfingerprint' ),
			'ajax'     => false
		) );
  }

  function no_items()
  {
    return '';
  }

  function column_default( $item, $column_name )
  {
    switch ( $column_name ) {
			case 'plugin':
			case 'status':
				return $item[ $column_name ];
			default:
		}
  }

  function get_columns() {
		$columns = [
			'plugin'    => __( 'Plugin', 'wpfingerprint' ),
			'status' => __( 'Status', 'wpfingerprint' ),
			'source'    => __( 'Source', 'wpfingerprint' ),
      'checked'    => __( 'Last Checked', 'wpfingerprint' ),
		];

		return $columns;
	}

  public function get_sortable_columns() {
    $sortable_columns = array(
      'plugin' => array( 'plugin', true ),
      'status' => array( 'status', false )
    );

    return $sortable_columns;
  }

  public function prepare_items() {

    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();
    $data = array();
    usort( $data, array( &$this, 'sort_data' ) );
    $perPage = 2;
    $currentPage = $this->get_pagenum();
    $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
    $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
	}
}
