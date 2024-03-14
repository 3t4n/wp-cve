<?php
namespace tmc\revisionmanager\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 08.03.2018
 * Time: 13:03
 */

use shellpress\v1_4_0\src\Shared\Components\IComponent;
use tmc\revisionmanager\src\App;

class Settings extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Defaults
		//  ----------------------------------------

		$this::s()->options->setDefaultOptions(
			array(
				'internal'                      =>  array(
					'doneUpgradeLegacyOptions'      =>  '0'
				),
				'capabilities'                  =>  array(
					'capCopy'                       =>  'edit_posts',
					'capAccept'                     =>  'publish_posts',
					'roleNotification'              =>  'administrator',
					'excludedEmails'                =>  null
				),
				'merging'                       =>  array(
					'mergeDate'                     =>  '0'
				),
				'wpDifferences'                 =>  array(
					'displayPostTitle'              =>  '1',
					'displayPostContent'            =>  '1'
				),
				'acfDifferences'                =>  array(
					'markChanges'                   =>  '1',
					'changeMarkColor'               =>  '#2980b9',
					'newMarkColor'                  =>  '#27ae60'
				),
				'postTypes'                     =>  array(
					'chosen'                        =>  array(
						'post'                          =>  '1'
					)
				),
				'notifications'                 =>  array(
					'whoReceives'                   =>  'all',
					'type'                          =>  'everySingle',
					'title'                         =>  __( 'Revision Manager TMC - Accept changes', 'rm_tmc' ),
					'content'                       =>  file_get_contents( $this::s()->getPath( '/assets/emailTemplates/default_mail.html' ) )
				),
				'license'                       =>  array(
					'key'                           =>  null,
					'keyExpiryDatetime'             =>  null,
					'lastCheckDatetime'             =>  null,
					'keyStatus'                     =>  null,
					'isKeyCorrect'                  =>  false,
					'domain'                        =>  null
				)
			)
		);

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------

		$this::s()->event->addOnActivate( array( $this, '_a_provideDefaultOptions' ) );
		$this::s()->event->addOnUpdate( array( $this, '_a_provideDefaultOptions' ) );

	}

    /**
     * Returns slugs of chosen supported post types.
     * It supports both radio and checkbox method of saved data.
     *
     * @return string[]
     */
    public function getChosenPostTypesSlugs() {

        /** @var string|array $option */
        $option = $this::s()->options->get( 'postTypes/chosen', array() );
        $slugs  = array();

        if( ! empty( $option ) && is_array( $option ) ){    //  Probably checkbox option

            foreach( $option as $slug => $isSelected ){
                if( $isSelected ){

                    $slugs[] = $slug;

                }
            }

        } elseif( ! empty( $option ) ) {                    //  Probably radio option

            $slugs[] = $option;

        }

        //  Test for accepted usage.

        if( ! App::i()->jetPlugs->isCodeActive() ){
            if( in_array( 'post', $slugs ) ){
                return array( 'post' );
            } else {
                return array();
            }
        }

        return $slugs;

    }

    /**
     * @return string|null
     */
    public function getCapabilityForCopyCreation() {

        return $this::s()->options->get( 'capabilities/capCopy' );

    }

    /**
     * @return string|null
     */
    public function getCapabilityForAcceptingChanges() {

        return $this::s()->options->get( 'capabilities/capAccept' );

    }

    /**
     * @return string|null
     */
    public function getRoleForNotifications() {

        return $this::s()->options->get( 'capabilities/roleNotification' );

    }

    /**
     * @return string[]
     */
    public function getExcludedEmailsFromNotifications() {

        $unfilteredEmails = (string) $this::s()->options->get( 'capabilities/excludedEmails', '' );

        //  Remove whitespaces

        $filteredEmails = preg_replace( '/\s*/m', '', $unfilteredEmails );
        $filteredEmails = str_replace ( ' ', '', $filteredEmails );

        //  Turn string to array

        $emails = (array) explode( ',', $filteredEmails );  //  Explode emails by comma

        return $emails;

    }

	/**
	 * @return string|null - everySingle/collective
	 */
    public function getNotificationType() {

    	return $this::s()->options->get( 'notifications/type' );

    }

	/**
	 * @return string|null - all/authors
	 */
    public function getWhoReceivesNotifications() {

    	return $this::s()->options->get( 'notifications/whoReceives' );

    }

    /**
     * @return string|null
     */
    public function getNotificationSubject() {

        return $this::s()->options->get( 'notifications/title' );

    }

    /**
     * @return string|null
     */
    public function getNotificationContent() {

        return $this::s()->options->get( 'notifications/content' );

    }

	/**
	 * @return bool
	 */
    public function isAcfMarkChangesEnabled() {

	    return (bool) $this::s()->options->get( 'acfDifferences/markChanges', '1' );

    }

	/**
	 * @return bool
	 */
    public function isWpPostTitleDifferencesEnabled() {

    	return (bool) $this::s()->options->get( 'wpDifferences/displayPostTitle', '1' );

    }

	/**
	 * @return bool
	 */
	public function isWpPostContentDifferencesEnabled() {

		return (bool) $this::s()->options->get( 'wpDifferences/displayPostContent', '1' );

	}

	/**
	 * @return string
	 */
    public function getAcfChangeMarkColor() {

    	return $this::s()->options->get( 'acfDifferences/changeMarkColor', '#000' );

    }

	/**
	 * @return string
	 */
    public function getAcfNewMarkColor() {

    	return $this::s()->options->get( 'acfDifferences/newMarkColor', '#000' );

    }

	/**
	 * @param int $userId
	 *
	 * @deprecated - To remove???
	 *
	 * @return int
	 */
    public function getNumOfNotifPerDayForUser( $userId = null ) {

    	if( ! is_numeric( $userId ) ){
    		$userId = get_current_user_id();
	    }

    	$options = (array) get_user_meta( $userId, $this::s()->getPrefix(), true );

    	return (int) $this::s()->utility->getValueByKeysPath( $options, array( 'numOfNotifPerDay' ), 0 );

    }

	/**
	 * @return bool
	 */
    public function shouldRevisionReplaceOriginalPostDate() {

    	return (bool) $this::s()->options->get( 'merging/mergeDate', '0' );

    }
    
    //  ================================================================================
    //  ACTIONS
    //  ================================================================================

	/**
	 * Called on update and activation of plugin.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_provideDefaultOptions() {
		
		$this::s()->options->fillDifferencies();
		$this::s()->options->flush();
    	
	}
	
}