<?php
/**
 * Model definition for Quote form fields.
 */

class QuoteFormFieldProps {
	protected $id;
	protected $name;
	protected $description;
	protected $type;
	protected $mandatory;
	protected $translationKey;
	protected $isDeletable;

	public function __construct( $fieldProps ) {
		$propKey              = array_keys( $fieldProps )[0];
		$propValues           = $fieldProps[ $propKey ];
		$this->id             = $propKey;
		$this->name           = $propValues['name'];
		$this->description    = $propValues['description'];
		$this->type           = $propValues['type'];
		$this->mandatory      = $propValues['isMandatory'];
		$this->translationKey = $propValues['trnKey'];
		$this->isDeletable    = $propValues['deletable'];
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function setProps( $props ) {
		$this->name        = $props['name'];
		$this->description = $props['description'];
		$this->type        = $props['type'];
		$this->mandatory   = $props['isMandatory'];
	}

	public function toJSON() {
		return array(
			$this->id => array(
				'name'        => $this->name,
				'description' => $this->description,
				'type'        => $this->type,
				'isMandatory' => $this->mandatory,
				'trnKey'      => $this->translationKey,
				'deletable'   => $this->isDeletable,
			),
		);
	}
}

class QuoteFormField {

	protected $calculatorId;
	public $fieldsCollection;
	const DEFAULT_FORM_FIELDS = '[{"name":{"name":"Your Name","description":"Type in your name","type":"text","isMandatory":null,"trnKey":"Your Name","deletable":false}},{"email":{"name":"Your Email","description":"Type in your email","type":"email","isMandatory":true,"trnKey":"Your Email","deletable":false}},{"phone":{"name":"Your Phone","description":"phone","type":"phone","isMandatory":null,"trnKey":"Your Phone (Optional)","deletable":false}}]';

	public function __construct( $calculatorId ) {
		global $wpdb;
		$query = $wpdb->get_results( $wpdb->prepare( "SELECT formFieldsArray FROM {$wpdb->prefix}df_scc_forms WHERE id = %d", $calculatorId ) );
		$result = $query[0]->formFieldsArray;

		$quoteFormFields        = $result ? json_decode( stripslashes( $result ), true ) : json_decode( self::DEFAULT_FORM_FIELDS, 1 );
		$this->calculatorId     = $calculatorId;
		$this->fieldsCollection = array_map(
			function ( $d ) {
				return new QuoteFormFieldProps( $d );
			},
			$quoteFormFields
		);
	}

	public function addOrUpdate( $fieldParams ) {
		$fieldKey      = array_keys( $fieldParams )[0];
		$existingField = $this->findFieldByKey( $fieldKey );
		if ( ! empty( $existingField ) ) {
			$existingField->setProps( $fieldParams[ $fieldKey ] );
		} else {
			array_push( $this->fieldsCollection, new QuoteFormFieldProps( $fieldParams ) );
		}
	}

	public function findFieldByKey( $key ) {
		$res = array_filter(
			$this->fieldsCollection,
			function ( $cb ) use ( $key ) {
				return $cb->getId() == $key;
			}
		);
		return count( $res ) ? array_values( $res )[0] : false;
	}

	private function findFieldIndexByKey( $key ) {
		$res = array_filter(
			$this->fieldsCollection,
			function ( $cb ) use ( $key ) {
				return $cb->getId() == $key;
			}
		);
		return array_keys( $res )[0];
	}

	private function toJSON() {
		return array_map(
			function( $arg ) {
				return $arg->toJSON();
			},
			$this->fieldsCollection
		);
	}

	public function save() {
		global $wpdb;
		$queryStatus = $wpdb->update( "{$wpdb->prefix}df_scc_forms", array( 'formFieldsArray' => json_encode( array_values( $this->toJSON() ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ), array( 'id' => $this->calculatorId ) );
		return $queryStatus;
	}

	public function delete( $fieldKey ) {
		$toDeleteFieldIndex = $this->findFieldIndexByKey( $fieldKey );
		unset( $this->fieldsCollection[ $toDeleteFieldIndex ] );
	}
}
