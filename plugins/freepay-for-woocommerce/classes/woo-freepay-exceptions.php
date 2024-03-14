<?php

class FreePay_Exception extends Exception
{
	/**
	 * Contains a log object instance
	 * @access protected
	 */
	protected $log;

    protected $request_data;

	protected $request_url;

	protected $response_data;
    
    
  	/**
	* __Construct function.
	* 
	* Redefine the exception so message isn't optional
	*
	* @access public
	* @return void
	*/ 
    public function __construct($message, $code = 0, Exception $previous = null, $request_url = '', $request_data = '', $response_data = '') {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);

        $this->log = new WC_FreePay_Log();
        
        $this->request_data = $request_data;
        $this->request_url = $request_url;
        $this->response_data = $response_data;
    }


  	/**
	* write_to_logs function.
	* 
	* Stores the exception dump in the WooCommerce system logs
	*
	* @access public
	* @return void
	*/  
	public function write_to_logs() 
	{
		$this->log->separator();
		$this->log->add( 'FreePay Exception file: ' . $this->getFile() );
		$this->log->add( 'FreePay Exception line: ' . $this->getLine() );
		$this->log->add( 'FreePay Exception code: ' . $this->getCode() );
		$this->log->add( 'FreePay Exception message: ' . $this->getMessage() );
		$this->log->separator();
	}


  	/**
	* write_standard_warning function.
	* 
	* Prints out a standard warning
	*
	* @access public
	* @return void
	*/ 
	public function write_standard_warning()
	{	
		printf( 
			wp_kses( 
				__( "An error occured. For more information check out the <strong>%s</strong> logs inside <strong>WooCommerce -> System Status -> Logs</strong>.", 'freepay-for-woocommerce' ), [ 'strong' => [] ]
			), 
			$this->log->get_domain() 
		);
	}
}


class FreePay_API_Exception extends FreePay_Exception 
{ 	
  	/**
	* write_to_logs function.
	* 
	* Stores the exception dump in the WooCommerce system logs
	*
	* @access public
	* @return void
	*/  
	public function write_to_logs() 
	{
		$this->log->separator();
		$this->log->add( 'FreePay API Exception file: ' . $this->getFile() );
		$this->log->add( 'FreePay API Exception line: ' . $this->getLine() );
		$this->log->add( 'FreePay API Exception code: ' . $this->getCode() );
		$this->log->add( 'FreePay API Exception message: ' . $this->getMessage() );
      
        if( ! empty($this->request_url)) {
            $this->log->add( 'FreePay API Exception Request URL: ' . $this->request_url);
        } 

        if( ! empty($this->request_data)) {
            $this->log->add( 'FreePay API Exception Request DATA: ' . $this->request_data);
        }

        if( ! empty($this->response_data)) {
            $this->log->add( 'FreePay API Exception Response DATA: ' . json_encode($this->response_data));
        }
        
		$this->log->separator();
        
	}
}

class FreePay_Capture_Exception extends FreePay_API_Exception {}

?>