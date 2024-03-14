<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Action_Esignature 
 */
final class NF_Actions_Esignature extends NF_Abstracts_Action
{
    /**
    * @var string
    */
    protected $_name  = 'e-signature';

    /**
    * @var array
    */
    protected $_tags = array();

    /**
    * @var string
    */
    protected $_timing = 'late';

    /**
    * @var int
    */
    protected $_priority = 10;

    /**
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'E-Signature', 'ninja-forms' );
        
        $settings = include plugin_dir_path( __FILE__ ) . 'ActionEsignatureSettings.php' ;
        if (!function_exists('WP_E_Sig'))
        return; 
        $this->_settings = array_merge( $this->_settings, $settings );
        
       // $this->_backwards_compatibility();
    }

    /*
    * PUBLIC METHODS
    */

    public function process( $action_settings, $form_id, $data )
    {
        $id = $action_settings['id'] ; 
        $post_id = ESIG_NF_SETTING::get_sub_id($data);
      
         if(!$post_id){
             return $data;
         }
       
         if(!function_exists("WP_E_Sig"))
         {
             return $data ; 
         }

            $sad = new esig_sad_document();


           // $form_id = $ninja_forms_processing->get_form_ID();

          //  $post_id = $ninja_forms_processing->get_form_setting('sub_id');

            $signing_logic = $action_settings[ 'signing_logic' ];

            //update_option('testing', $signing_logic) ; 
            $sad_page_id = $action_settings[ 'select_sad' ];

            $document_id = $sad->get_sad_id($sad_page_id);
            $docStatus  = WP_E_Sig()->document->getStatus($document_id);
            
            if($docStatus !="stand_alone"){
                return false;
            }
            
            $signer_email = $action_settings[ 'signer_email' ];
            $signer_name = $action_settings[ 'signer_name' ];               
           
            // check for valid signer name 
            if(!WP_E_Sig()->validation->esig_valid_string($signer_name)) return false ; 

            if(!(strlen($signer_name) >= 2)){
                return;
            }
            
            // if not email address 
            if (!is_email($signer_email)) {
                return;
            }
            $nf_esig_admin = new ESIG_NFDS_Admin();
            // sending email invitation / redirecting .
            $result = $nf_esig_admin->esig_invite_document($document_id, $signer_email, $signer_name, $form_id, $post_id, $signing_logic, $id,$action_settings);
            if($signing_logic == 'redirect'){
                $data[ 'actions' ][ 'redirect' ] = $result; 
            }
      
           return $data;
        
    }

   
   /*
    * PUBLIC METHODS
    */

    public function save( $action_settings )
    {
        
    }
    
}
