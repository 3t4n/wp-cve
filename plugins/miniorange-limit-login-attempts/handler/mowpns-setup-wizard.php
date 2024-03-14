<?php

class SetupWizard{

    public static function getCurrentState(){
        $currentState = get_site_option('molla_current_setup_step',0);
        return $currentState;
    }

    public static function setCurrentState($state){
        update_site_option('molla_current_setup_step',$state);
    }

    public static function increaseCurrentState(){
        update_site_option('molla_current_setup_step',SetupWizard::getCurrentState()+1);
    }

    public static function decreseCurrentState(){
        if(SetupWizard::getCurrentState()>0)
        update_site_option('molla_current_setup_step',SetupWizard::getCurrentState()-1);
    }

    public static function finishSetup(){
        update_site_option('molla_setup_wizard',false);
        update_site_option('molla_setup_done',true);
    }

    public static function startSetup(){
        update_site_option('molla_setup_wizard',true);
        update_site_option('molla_setup_done',false);
    }

    public static function isSetupDone(){
        get_site_option('molla_setup_done',false);
    }

    public static function showSetupWizard(){
        
        if(!SetupWizard::isSetupDone() || true){

            $currentState = SetupWizard::getCurrentState();

            try{
                global $mo_lla_dirName;
                include $mo_lla_dirName.'views/setup-wizard/'.Mo_lla_MoWpnsConstants::MOWPNS_SETUP_WIZARD_STEPS[$currentState];
            }catch(Excption $e){
                die('something went wrong');
            }

        }
    }

} new SetupWizard;

?>

