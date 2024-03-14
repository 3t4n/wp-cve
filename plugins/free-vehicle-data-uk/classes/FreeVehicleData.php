<?php
namespace FreeVehicleData;


if ( ! defined( 'ABSPATH' ) ) exit;
final class FreeVehicleData
{
    protected static $Instance = null;
    
    public $sVersion;
    public $sURL;
    public $sBaseURL;
    public $sPath;
    protected $sBaseName;
    protected $oAssets;
    protected $oAdmin;
    protected $oShortcodes;

    
    public static function Instance() {
        if ( is_null( self::$Instance ) ) {
            self::$Instance = new self();
        }
        if( !session_id() ){
            session_start();
        }
        return self::$Instance;
    }
    function __construct() {
        $this->sVersion = '1.31';
        $this->sPath     = plugin_dir_path(FVD_FILE);
        $this->sURL      = plugin_dir_url(FVD_FILE);
        $this->sBaseName = plugin_basename(FVD_FILE);
        $this->sBaseURL  = FVD_BASE_URL;

        $this->oAssets   = new \FreeVehicleData\Assets(); 
        $this->oAdmin   = new \FreeVehicleData\Admin();
        $this->oShortcodes  = new \FreeVehicleData\Shortcodes();

    }
    static public function Activate()
    {
        if (get_option( 'FVDUKDisableEndPoint' ) == false){
            add_option( 'FVDUKDisableEndPoint', 'yes' );//disable endpoint until signup (avoid errors)
	}
	update_option('FVDUKCreditUs','yes');
	//if (get_option( 'FVDUKCreditUs' ) == false){
	//add_option( 'FVDUKCreditUs', 'no' );//Set credit us option
	//}
	
	if (get_option( 'FVDUKCreditLink' ) == false){
            $selectone = array("https://www.rapidcarcheck.co.uk/", "https://www.rapidcarcheck.co.uk/developer-api/");	
            $kxx = array_rand($selectone);
            $vxx = $selectone[$kxx];
            add_option( 'FVDUKCreditLink', $vxx );//Set credit us option
	}
    }
}
