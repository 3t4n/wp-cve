<?php
class WC_REST_List_Items_API_By_WooPOS_Controller extends WC_REST_CRUD_Controller{
	protected $namespace = 'wc/v2';
	protected $namespace2 = 'wc/v3';
	protected $rest_base = 'list_items';
	
	public $post_fields = array( 'post_name', 'post_title', 'post_content' );

	public function register_routes(){
		register_rest_route( $this->namespace,  '/' . $this->rest_base . '(?:/(?P<type>))?', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( $this, 'list_items' ),
				'args' => $this->get_params(),
				'permission_callback' => '__return_true'
				)
		) );
		register_rest_route( $this->namespace2,  '/' . $this->rest_base . '(?:/(?P<type>))?', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( $this, 'list_items' ),
				'args' => $this->get_params(),
				'permission_callback' => '__return_true'
				)
		) );
	}
	
	public function get_params(){
		$params = array(
			'type'				=> array(
				'required'			=> true,
				'description'		=> __( 'Type of item to list ("users", "posts", "products", etc)', 'woopos_list_items' ),
				'type'				=> 'string',
				'sanitize_callback'	=> function($param){
					return strtolower($param);
				}
			),
			'orderby'			=> array(
				'required'			=> false,
				'description'		=> __( 'Parameter to sort items by (default "modified")', 'woopos_list_items' ),
				'type'				=> 'string',
				'default'			=> 'modified',
				'sanitize_callback'	=> function($param){
					return strtolower($param);
				}
			),
			'order'				=> array(
				'required'			=> false,
				'description'		=> __( 'ASC or DESC (default "DESC")', 'woopos_list_items' ),
				'type'				=> 'string',
				'default'			=> 'desc',
				'validate_callback'	=> function($param){
					return in_array( strtolower($param), array('asc', 'desc') );
				},
				'sanitize_callback'	=> function($param){
					return strtolower($param);
				}
			),
			'after_date'		=> array(
				'required'			=> false,
				'description'		=> __( 'last_modified after this date', 'woopos_list_items' ),
				'type'				=> 'string',
				'format'			=> 'date-time'
			),
			'before_date'		=> array(
				'required'			=> false,
				'description'		=> __( 'last_modified before that date', 'woopos_list_items' ),
				'type'				=> 'string',
				'format'			=> 'date-time'
			),
			'items_per_page'	=> array(
				'required'			=> false,
				'description'		=> __( 'The maximum returned number of results (needed in pagination)', 'woopos_list_items' ),
				'type'				=> 'number',
				'default'			=> '100'
			),
			'offset'			=> array(
				'required'			=> false,
				'description'		=> __( 'Offset the returned results (needed in pagination)', 'woopos_list_items' ),
				'type'				=> 'number'
			),
			'paged'				=> array(
				'required'			=> false,
				'description'		=> __( 'When used with number, defines the page of results to return. Default 1.', 'woopos_list_items' ),
				'type'				=> 'number'
			),
			'post_status'		=> array(
				'required'			=> false,
				'description'		=> __( 'Status of posts to retrieve', 'woopos_list_items' ),
				'type'				=> 'string',
				'default'			=> 'any'
			)
		);
		return $params;
	}
	
	public function list_items($request){
		$response = array();
		try{
			$args = array();
			if( !empty($request['orderby']) ){
				$args['orderby'] = $request['orderby'];
			}
			if( !empty($request['order']) ){
				$args['order'] = $request['order'];
			}
			if( !empty($request['after_date']) || !empty($request['before_date']) ){
				$args['date_query'] = array();
				if( !empty($request['after_date']) ){
					$args['date_query']['column'] = 'post_modified';		
					$args['date_query']['after'] = $request['after_date'];
				}
				if( !empty($request['before_date']) ){
					$args['date_query']['column'] = 'post_modified';		
					$args['date_query']['before'] = $request['before_date'];
				}
				
			}
			$args['posts_per_page'] = isset($args['date_query']) ? -1 : $request['items_per_page'];
			if( !empty($request['offset']) ){
				$args['offset'] = $request['offset'];
			}
			if( !empty($request['paged']) ){
				$args['paged'] = $request['paged'];
			}
			
			if( !empty($request['post_status']) ){
				$args['post_status'] = $request['post_status'];
			}
$ids = array();
			
if( $request['type'] === 'users' ) {
	$users = get_users( array( 'fields' => array( 'ID' ) ) );
	foreach($users as $user){
		$UserData = get_user_meta( $user->ID );  
		if( !empty($request['after_date']) ){
			if(strtotime($UserData['woopos_profile_updated'][0]) > strtotime($request['after_date'])){
    				if( !in_array($user->ID, $ids) ){
					$ids[] = (int) $user->ID;
    				}
			}
		}
	}
}
else{
			$args['post_type'] = $request['type'];
			
			$query = new WP_Query( $args );
			$results = $query->posts;
			
			foreach($results as $item){			
				$value = $item->ID;				
				if( !in_array($value, $ids) ){
					$ids[] = (int) $value;
				}
			}
}
			$response = array(
				'result' => 'success',
				'ids' => $ids
			);
		}catch(Exception $e){
			$response = array(
				'result' => 'error',
				'message' => $e->getMessage()
			);
		}
		return $response;
	}
}