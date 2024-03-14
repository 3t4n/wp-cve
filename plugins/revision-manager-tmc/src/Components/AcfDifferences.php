<?php
namespace tmc\revisionmanager\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 10.09.2018
 * Time: 14:21
 */

use shellpress\v1_4_0\src\Shared\Components\IComponent;
use tmc\revisionmanager\src\App;

class AcfDifferences extends IComponent {

	/**
	 * Simple array containing all fields and sub fields values, keyed by form name.
	 *
	 * @var array
	 */
	private $_allFieldsValuesCached = array();

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------
		add_action( 'acf/render_field',                 array( $this, '_a_renderAdditionalFieldHtml' ), 100 );
		add_action( 'acf/input/admin_head',             array( $this, '_a_provideCachedPreviousValues' ) );
		add_action( 'acf/input/admin_enqueue_scripts',  array( $this, '_a_addScriptsAndStyles' ) );

		//  ----------------------------------------
		//  Filters
		//  ----------------------------------------

		add_filter( 'acf/prepare_field',                array( $this, '_f_markFieldDifference' ), 100 );

	}

	/**
	 * Returns all values keyed by full input name.
	 * Caution! This method is heavy. Try to cache values somewhere else.
	 *
	 * @param int $postId
	 *
	 * @return array
	 */
	public function getAllFieldsAndSubFieldsValues( $postId ) {

		if( ! class_exists( 'acf' ) ) return array();                   //  Bail early. No ACF.

		//  ----------------------------------------
		//  Parse upper fields ( most basic )
		//  ----------------------------------------

		$fieldsArgs     = get_field_objects( $postId, false, true );
		$cachedValues   = array();

		if( $fieldsArgs ){
			foreach( $fieldsArgs as $fieldName => $fieldArgs ){

				$arrayOfKeys    = array( 'acf' );
				$arrayOfKeys[]  = $fieldArgs['key'];

				$this->_parseFieldValuesDefinitionToListOfKeyedValues( $fieldArgs['value'], $arrayOfKeys, $cachedValues );

			}
		}

		return $cachedValues;

	}

	/**
	 * Checks if given nested array of values contains another sub-field keys.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	private function _doesValueContainSubFieldKeys( $value ) {

		if( is_array( $value ) ){

			foreach( $value as $subKey => $subValue ){

				if( strpos( (string) $subKey, 'field_' ) !== false ) return true;

				if( is_array( $subValue ) ){

					foreach( $subValue as $subsubKey => $subsubValue ){

						if( strpos( (string) $subsubKey, 'field_' ) !== false ) return true;

					}

				}

			}

		}

		return false;

	}

	/**
	 * Parses multi-dimensional array of field and sub-fields values
	 * to simple one-dimensional array of keyed values.
	 *
	 * @param mixed $value
	 * @param string[] $arrayOfKeys
	 * @param string[] $cachedValues
	 */
	private function _parseFieldValuesDefinitionToListOfKeyedValues( $value, $arrayOfKeys, &$cachedValues ) {

		if( $this->_doesValueContainSubFieldKeys( $value ) ){

			//  ----------------------------------------
			//  This value contains another sub-fields
			//  ----------------------------------------

			foreach( $value as $key => $subValue ){

				$newArrayOfKeys = $arrayOfKeys;         //  Copy array.
				array_push( $newArrayOfKeys, $key );    //  Add This key to array.

				$this->_parseFieldValuesDefinitionToListOfKeyedValues( $subValue, $newArrayOfKeys, $cachedValues );

			}

		} else {

			//  ----------------------------------------
			//  Prepare name from array of keys
			//  ----------------------------------------

			$name = ''; //  Here is full name made of keys.

			$i = 0;
			foreach( (array) $arrayOfKeys as $key ){

				//  Repeated ACF input key nodes are prefixed with "row-".
				if( is_numeric( $key ) ) $key = "row-{$key}";

				$name .= $i ? '[' . $key . ']' : $key;  //  The fist key is not surrounded with brackets!
				$i++;

			}

			//  ----------------------------------------
			//  Save value
			//  ----------------------------------------

			$cachedValues[$name] = $value;

		}

	}

	/**
	 * @param mixed     $oldValue
	 * @param mixed     $newValue
	 * @param array     $acfFieldArgs
	 *
	 * @return string
	 */
	public function getDisplayOfChanges( $oldValue, $newValue, $acfFieldArgs ) {

		if( is_string( $oldValue ) && is_string( $newValue ) ){

			$wordCount = 10;

			if( str_word_count( $oldValue ) >= $wordCount || str_word_count( $newValue ) >= $wordCount ){
				return wp_text_diff( $oldValue, $newValue );
			} else {
				return sprintf( '<table class="diff"><tr><td class="diff-deletedline">%1$s</td></tr></table>', $oldValue );
			}

		} else if( is_array( $oldValue ) || is_array( $newValue ) ){

			return wp_text_diff( implode( ',', (array) $oldValue ), implode( ',', (array) $newValue ) );

		} else {

			return $this::s()->utility->getFormattedVarExport( $oldValue );

		}

	}

	/**
	 * Checks if ACF is enabled and version is supported.
	 *
	 * @return bool
	 */
	public function isCurrentAcfVersionCompatible() {

		if( function_exists( 'acf_get_setting' ) ){

			if( version_compare( '5', acf_get_setting('version'), '<' ) ){
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}

	}

	/**
	 * Check if given field should display its changes.
	 *
	 * @param array $fieldArgs
	 *
	 * @return bool
	 */
	public function isFieldAcceptedToDisplayChanges( $fieldArgs ) {

		//  Bail early if not on supported edit page.
		if( ! App::i()->revisions->isOnRevisionEditPage() ) return false;

		//  Bail early. No values cached.
		if( empty( $this->_allFieldsValuesCached ) ) return false;

		//  Bail early. No repeaters and groups please.
		if( in_array( $fieldArgs['type'], array( 'repeater', 'flexible_content', 'group' ) ) ) return false;

		//  Bail early. This is clone.
		if( strpos( $fieldArgs['name'], 'acfclone' ) !== false ) return false;

		//  Bail early. This field is a clone. In future we may support this?
		if( ! empty( $fieldArgs['_clone'] ) ) return false;

		return true;

	}

	/**
	 * Uses already cached previous values and returns the one corresponding to input name.
	 * Null is returned, if value could not be found.
	 *
	 * @param string $fieldInputName - The real input name inside DOM
	 *
	 * @return mixed|null
	 */
	public function getPreviousValueByFieldInputName( $fieldInputName ) {

		if( ! empty( $this->_allFieldsValuesCached ) ){

			if( isset( $this->_allFieldsValuesCached[$fieldInputName] ) ){

				return $this->_allFieldsValuesCached[$fieldInputName];

			}

		}

		return null;    //  Nothing found.

	}

	//  ================================================================================
	//  ACTIONS
	//  ================================================================================

	/**
	 * Provides all ACF fields and sub fields values for further use.
	 * Called on acf/input/admin_head.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_provideCachedPreviousValues() {

		if( ! App::i()->revisions->isOnRevisionEditPage() ) return;     //  Bail early if not on supported edit page.
		if( ! App::i()->settings->isAcfMarkChangesEnabled() ) return;   //  Bail early if marks are disabled.
		if( ! $this->isCurrentAcfVersionCompatible() ) return;          //  Bail early if there is wrong ACF version.

		$clonedPostId   = get_the_ID();
		$originalPostId = get_post_meta( $clonedPostId, 'linked_post_id', true );

		if( $clonedPostId && $originalPostId ){
			$this->_allFieldsValuesCached = $this->getAllFieldsAndSubFieldsValues( $originalPostId );
		}

	}

	/**
	 * Prints additional field difference HTML.
	 * Called on acf/render_field.
	 *
	 * @param array $clonedField
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_renderAdditionalFieldHtml( $clonedField ) {

		if( ! $this->isFieldAcceptedToDisplayChanges( $clonedField ) ) return;

		//  ----------------------------------------
		//  Check original field value
		//  ----------------------------------------

		$previousValue = $this->getPreviousValueByFieldInputName( $clonedField['name'] );

		//  ----------------------------------------
		//  Display changes
		//  ----------------------------------------

		if( $previousValue !== $clonedField['value'] && ! empty( $previousValue ) ) {

			if( empty( $previousValue ) && ! empty( $clonedField['value'] ) ){
				//  Do nothing.
			} else {

				printf( '<div class="rm_tmc_diff">%1$s</div>', $this->getDisplayOfChanges( $previousValue, $clonedField['value'], $clonedField ) );

			}

		}

	}

	/**
	 * Provides some styling from user defined settings and other scripts.
	 * Called on acf/field_group/admin_enqueue_scripts.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_addScriptsAndStyles() {

		if( ! App::i()->revisions->isOnRevisionEditPage() ) return;     //  Bail early if not on supported edit page.
		if( ! App::i()->settings->isAcfMarkChangesEnabled() ) return;   //  Bail early if marks are disabled.

		//  ----------------------------------------
		//  Color accents
		//  ----------------------------------------

		echo '<style>' . PHP_EOL;
		printf( '.acf-field.rm_tmc_changed_value { border-left-color: %1$s !important; }', App::i()->settings->getAcfChangeMarkColor() );
		printf( '.acf-field.rm_tmc_new_value { border-left-color: %1$s !important; }', App::i()->settings->getAcfNewMarkColor() );
		echo '</style>' . PHP_EOL;

	}

	//  ================================================================================
	//  FILTERS
	//  ================================================================================

	/**
	 * Called on acf/prepare_field.
	 *
	 * @param array $clonedField
	 *
	 * @return array
	 */
	public function _f_markFieldDifference( $clonedField ) {

		if( ! $this->isFieldAcceptedToDisplayChanges( $clonedField ) ) return $clonedField;

		//  ----------------------------------------
		//  Check original field value
		//  ----------------------------------------

		$previousValue = $this->getPreviousValueByFieldInputName( $clonedField['name'] );

		//  ----------------------------------------
		//  Modify field class
		//  ----------------------------------------

		if( $previousValue !== $clonedField['value'] ) {

			if( empty( $previousValue ) && ! empty( $clonedField['value'] ) ){
				$clonedField['wrapper']['class'] .= ' rm_tmc_new_value';
			} else {

				if( empty( $previousValue ) && empty( $clonedField['value'] ) ){
					//  Do nothing.
				} else {
					$clonedField['wrapper']['class'] .= ' rm_tmc_changed_value';
				}

			}

		}

		return $clonedField;

	}

}