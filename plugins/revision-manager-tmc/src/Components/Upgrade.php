<?php
namespace tmc\revisionmanager\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 14.03.2018
 * Time: 10:38
 */

use shellpress\v1_4_0\src\Shared\Components\IComponent;
use tmc\revisionmanager\src\App;

class Upgrade extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------

		$this::s()->event->addOnActivate( array( $this, '_a_doUpgradeLegacyOptions' ) );
		$this::s()->event->addOnUpdate( array( $this, '_a_doUpgradeLegacyOptions' ) );

	}

	/**
	 * Transforms old chosen post types option to new structure.
	 *
	 * @param array $oldOption
	 *
	 * @return array
	 */
    protected function parseOldChosenPostTypesOptionToNew( $oldOption ) {

        $newOption = array();

        foreach( (array) $oldOption as $postType => $value ){

            if(
                is_array( $value )
                && array_key_exists( 'checked', $value )
                && $value['checked'] == '1'
            ){
                $newOption[ $postType ] = '1';
            } else {
                $newOption[ $postType ] = '0';
            }

        }

        return $newOption;

    }

    //  ================================================================================
    //  ACTIONS
    //  ================================================================================

    /**
     * Performs upgrade from old options to new.
     * When done, removes old options from database.
     * It flushes new options.
     *
     * @return void
     */
    public function _a_doUpgradeLegacyOptions() {

    	//  ----------------------------------------
    	//  Really old options
    	//  ----------------------------------------

	    if( $this::s()->options->get( 'internal/doneUpgradeLegacyOptions', '0' ) ){

		    $oldOptionKeys = array(
			    'rm_tmc_post_types'         =>  'postTypes/chosen',
			    'rm_tmc_editor_cap'         =>  'capabilities/capCopy',
			    'rm_tmc_accept_cap'         =>  'capabilities/capAccept',
			    'rm_tmc_mail_role'          =>  'capabilities/roleNotification',
			    'rm_tmc_emails_exclude'     =>  'capabilities/excludedEmails',
			    'rm_tmc_mail_content'       =>  'notifications/content',
			    'rm_tmc_mail_title'         =>  'notifications/title',
			    'rm_tmc_options_version'    =>  null
		    );

		    foreach( $oldOptionKeys as $oldOptionKey => $newOptionKey ){

			    $oldOption = get_option( $oldOptionKey, null );

			    //  Perform upgrade

			    if( $oldOption && ! empty( $newOptionKey ) ){

				    if( $oldOptionKey === 'rm_tmc_post_types' ){    //  Only this option has custom structure.

					    $this::s()->options->set( $newOptionKey, $this->parseOldChosenPostTypesOptionToNew( (array) $oldOption ) );

				    } else {

					    $this::s()->options->set( $newOptionKey, $oldOption );

				    }

			    }

			    //  Remove old option

			    delete_option( $oldOptionKey );

		    }

		    $this::s()->options->set( 'internal/doneUpgradeLegacyOptions', '1' );
		    $this::s()->options->flush();

	    }
		
		//  ----------------------------------------
		//  OLD EDD License Manager
		//  ----------------------------------------
	
	    $oldEddLicenseKey   = get_option( 'tmcrevisionmanagersrccomponentslicense_license' );
	    $oldEddLicenseData  = get_option( 'tmcrevisionmanagersrccomponentslicense_data' );
	    
	    if( $oldEddLicenseKey && $oldEddLicenseData ){
			
			$this::s()->options->set( 'license/key', $oldEddLicenseKey );
			$this::s()->options->set( 'license/keyExpiryDatetime', 'lifetime' );
			$this::s()->options->set( 'license/lastCheckDatetime', current_time( 'mysql' ) );
			$this::s()->options->set( 'license/keyStatus', '' );
			$this::s()->options->set( 'license/isKeyCorrect', true );
			$this::s()->options->set( 'license/domain', get_site_url() );
			
			$this::s()->options->flush();
			
			delete_option( 'tmcrevisionmanagersrccomponentslicense_license' );
			delete_option( 'tmcrevisionmanagersrccomponentslicense_data' );
			
	    }

    }

}