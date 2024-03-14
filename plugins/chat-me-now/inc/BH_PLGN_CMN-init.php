<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
include(BH_PLGN_CMN_PATH."/inc/BH_PLGN_CMN-dialog.php");

//Class for the setup plugin menu and initialization 
class bhpcmn_main extends bhpcmn_dialog {
    function __construct(){
        
        //Setup admin panel
        if(is_admin()){
            parent::__construct(true);
            $this->bhpcmn_admin_area();
            
        }
        else
            parent::__construct(false);
    }
    //bhpcmn_admin_area() load admin panel on dashboard
     function bhpcmn_admin_area(){
       
        //Call admin area class 
        include(BH_PLGN_CMN_PATH."/inc/BH_PLGN_CMN-admin.php");
        new bhpcmn_admin($this);
    }
    
}
