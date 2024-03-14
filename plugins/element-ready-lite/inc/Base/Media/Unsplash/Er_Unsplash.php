<?php

namespace Element_Ready\Base\Media\Unsplash;

class Er_Unsplash extends Unsplash_Contact
{
   
	public function __construct(){
		$this->app_id = $this->element_ready_get_api_option('media_unsplash_api_key');
	}
	
	public function list_photos( $page = null,$perpage = 30 ){
		
		if( $page !='' ){
			$this->page = $page;
		}
		
		if( $perpage !='' ){
			$this->per_page = $perpage;
		}

		$url = add_query_arg( array(
				'page'      => $this->page,
				'per_page'  => $this->per_page,
				'order_by'  => 'popular',
				'client_id' => $this->app_id
		    ),
		    $this->base_domain.'/photos' 
		);
      
        $return_data = [
			'results'  => $this->get_api_data( esc_url_raw($url) ),
			'page'     => $this->page,
			'per_page' => $this->per_page
		];

	    return $return_data;
	}

	public function get_photo($id = 'WHWYBmtn3_0'){

		$url = add_query_arg( array(
			'client_id' => $this->app_id
		    ),
		    $this->base_domain.'/photos/'.$id 
		);
	     
	    return $this->get_encode_api_data( esc_url_raw( $url ));
	}

	public function search($q='',$page=''){
		
		if( $page !='' ){

			$this->page = $page;
		}

		$url = add_query_arg( array(
			'query'     => sanitize_text_field( $q ),
			'page'      => $this->page,
			'per_page'  => $this->per_page,
			'order_by' => 'latest',
			'client_id' => $this->app_id,
		    ),
		   $this->base_domain.'/search/photos' 
		);
       
	    return $this->get_encode_api_data(esc_url_raw( $url ));
	}
	public function search_collection($q='',$page = null){
		
		if( is_integer($page) ){

			$this->page = $page;
		}

		$url = add_query_arg( array(
			'query'     => sanitize_text_field( $q ),
			'page'      => $this->page,
			'client_id' => $this->app_id,
		    ),
		   $this->base_domain.'/search/collections' 
		);
		
	    return $this->get_encode_api_data(esc_url($url));
	}

	public function download($id = 'WHWYBmtn3_0'){

		$url = add_query_arg( array(
			'client_id' => esc_attr( $this->app_id )
		),
		 $this->base_domain.'/photos/'.$id.'/download' 
		);
	  
	    return $this->get_encode_api_data(esc_url_raw($url));
	}

	function setPage($page) {
		$this->page = $page;
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
		  return $this->$property;
		}
	}
	public function __set($property, $value) {

		if (property_exists($this, $property)) {
		  $this->$property = $value;
		}
		
		return $this;
	}

}

