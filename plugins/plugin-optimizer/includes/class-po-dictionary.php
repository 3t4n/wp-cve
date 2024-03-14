<?php

class SOSPO_Dictionary{

    protected static $instance = null;
    
    protected $dictionary_url = 'https://po-dictionary.herokuapp.com/';
    protected $prospector_url = 'https://po-prospector.herokuapp.com/';//'http://po-prospector.herokuapp.com/';

    private function __construct() {}

    static function get_instance() {
        
        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;
    }

    // main method for creating a cURL request
    function request( $args , $endpoint = "count", $prospector = false ){
        
        $json = json_encode( $args );

        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',                    
            'Content-Length: ' . strlen( $json ),
        ];

        $options = [
            CURLOPT_URL             => ( $prospector ? $this->prospector_url : $this->dictionary_url ) . 'api/v1/' . $endpoint,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $json,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        $response = json_decode( $server_output );
        
        if( $response->status == "success" && ! empty( $response->data ) ){
            
            return $response->data;
        }
        
        return new \WP_Error( 'server', $response->message );
    }
    
    // main method for getting the count of the filters in the collection
    function count( $args, $prospector = true ){
        
        $data = $this->request( $args, "count", $prospector );
        
        if( ! is_wp_error( $data ) ){
            
            $data = $data->count;
        }
        
        return $data;
    }
    
    // main method for getting the filters from the collection
    function get( $args, $prospector = false  ){
        
        $data = $this->request( $args, "get", $prospector );
        
        if( ! is_wp_error( $data ) ){
            
            $data = $data->filters;
        }
        
        return $data;
    }
    
    
    /**
     * Constructs the query to send to prospector
     * So far this sends the admin menu endpoints
     * @return int Count of filters
     */
    function get_prospector_count(){
        
        // The endpoints
        $menu_endpoints = get_option( "po_admin_menu_list" );

        // The plugins
        $plugins = array_keys(get_plugins());

        $all_plugins = get_plugins();
        $all_plugins = array('plugins'=>array_keys($all_plugins));

        // the option only exists if have already retrieved filters from server
        if( $po_filter_retrieval = get_option( 'po_admin_menu_list') ){
            $all_plugins = array_merge(array('endpoints' => (array)$menu_endpoints['endpoints']), $all_plugins);
        }

        // search plugins by belongs to
        $all_plugins['belongsTo'] = 'relevant';        

        $count = $this->count( $all_plugins, true );
        
        return is_wp_error( $count ) ? "unknown" : $count;
    }
    
    /**
     * Constructs the query to send to prospector
     * So far this sends all installed plugins
     * @return int Count of filters
     */
    private function get_benefit_filters_query(){
        
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $plugins = get_plugins();

        $args = [
            "query" => [
                '$and' => [
                    [ 'belongsTo' => [ '$in' => array_merge( array_keys( $plugins ), [ '_core' ] ) ] ],
                    // [ 'status' => 'pending' ],
                    [ 'status' => 'approved' ],
                ],
            ],
        ];
        
        // TODO We need to add the 'status' => 'approved' as a condition
        // Jake's note: Not sure what the condition is for, talk to Craig
        
        return $args;
    }

    /**
     * Retrieves from dictionary if passing ids param
     * otherwise goes to prospector
     * @param  array  $dictionary_ids document index from mongo
     * @return json   server response
     */
    function retrieve( $dictionary_ids = [] ){
        
        $url  = empty( $dictionary_ids ) ? $this->dictionary_url . 'api/v1/retrieve' : $this->prospector_url . 'api/v1/retrieveById';
        
        $json = empty( $dictionary_ids ) ? json_encode( $dictionary_ids ) : json_encode( [ "ids" => $dictionary_ids, "columns" => [ "_id", "status" ], ] );
        
        $ch   = curl_init();

        $headers = [
            'Content-Type: application/json',                    
            'Content-Length: ' . strlen( $json ),
        ];
        
        $options = [
            CURLOPT_URL             => $url,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $json,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        return $server_output;
    }

    /**
     * Retrieve filters when belongs to currently installed plugins
     * @return json Server response
     */
    function get_relevant_filters(){
        
        // sospo_mu_plugin()->write_log( array_keys( get_plugins() ), "test-123-get_plugins" );
        
        $send_out = [ 'belongsTo' => array_keys( get_plugins() ) ];
        
        return $this->retrieve( $send_out );
    }
    
    /**
     * Retrieve filters that have pending status
     * @return json Server response
     */
    function get_pending_filters(){
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,  PODICT_URL.'api/v1/pending');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $options = [
            CURLOPT_URL             => $this->dictionary_url . 'api/v1/pending',
            CURLOPT_RETURNTRANSFER  => true,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        return $server_output;
    }
    
    /**
     * Retrieve filters that have approved status
     * @param  string $index specifies if what we search filters by ie. endpoint/plugin
     * @param  string $query specifies the query passed into mongoose on node server
     * @return json          Server response
     */
    function get_approved_filters( $index = 'all', $query = '' ){

        $send_out = [ 'index' => $index, 'query' => $query ];

        $json = json_encode( $send_out );

        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',                    
            'Content-Length: ' . strlen( $json ),
        ];
        
        $options = [
            CURLOPT_URL             => $this->dictionary_url . 'api/v1/approved',
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $json,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        return $server_output;
    }

}

function sospo_dictionary(){
    return SOSPO_Dictionary::get_instance();
}
sospo_dictionary();
