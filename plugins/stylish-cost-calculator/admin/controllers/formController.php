<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// require(dirname(__DIR__, 1) . "/models/Form.php");
/**
 * *This loads data of form with all realtion
 * !this should be use instead of making queries in php file
 * !more that one file uses this queries
 */

class formController {

	protected $db;

	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}

	public function __destruct() {
		// $this->db->close();
	}

	/**
	 * @param string $formname
	 * @param string $description
	 * @param string $inheritFontType
	 * @param string $titleFontSize
	 * @param string $titleFontType
	 * @param string $titleFontWeight
	 * @param string $titleColorPicker
	 * @param string $ServicefontSize
	 * @param string $fontType
	 * @param string $fontWeight
	 * @param string $ServiceColorPicker
	 * @param string $objectSize
	 * @param string $objectColorPicker
	 * @param string $elementSkin
	 * @param string $addContainer
	 * @param string $addtoCheckout
	 * @param string $buttonStyle
	 * @param string $turnoffborder
	 * @param string $turnoffemailquote
	 * @param string $turnviewdetails
	 * @param string $turnoffcoupon
	 * @param string $barstyle
	 * @param string $turnofffloating
	 * @param string $removeTotal
	 * @param string $minimumTotal
	 * @param string $minimumTotalChoose
	 * @param string $removeTitle
	 * @param string $turnoffUnit
	 * @param string $turnoffQty
	 * @param string $turnoffSave
	 * @param string $turnoffTax
	 * @param string $taxVat
	 * @param string $symbol
	 * @param string $removeCurrency
	 * @param string $userCompletes
	 * @param string $userClicksf
	 * @param string $showTaxBeforeTotal
	 * @param string $formFieldsArray
	 * @param string $webhookSettings
	 * @param string $showFieldsQuoteArray
	 * @param string $translation
	 * @param string $paypalConfigArray
	 *
	 * @return integer $id returns id of created form
	 */

	function create( array $values ) {
		$now = current_time( 'mysql' );
		//?get last id and adds 1 because id its not autoincrement
		( isset( $values['id'] ) ) ? $form_id = $values['id'] : $form_id = intval( $this->getLastId() ) + 1;

		( isset( $values['formname'] ) ) ? $formname                     = $values['formname'] : $formname = '';
		( isset( $values['description'] ) ) ? $description               = $values['description'] : $description = '';
		( isset( $values['inheritFontType'] ) ) ? $inheritFontType       = $values['inheritFontType'] : $inheritFontType = 'true';
		( isset( $values['titleFontSize'] ) ) ? $titleFontSize           = $values['titleFontSize'] : $titleFontSize = '30px';
		( isset( $values['titleFontType'] ) ) ? $titleFontType           = $values['titleFontType'] : $titleFontType = null;
		( isset( $values['titleFontWeight'] ) ) ? $titleFontWeight       = $values['titleFontWeight'] : $titleFontWeight = null;
		( isset( $values['titleColorPicker'] ) ) ? $titleColorPicker     = $values['titleColorPicker'] : $titleColorPicker = '#000000';
		( isset( $values['ServicefontSize'] ) ) ? $ServicefontSize       = $values['ServicefontSize'] : $ServicefontSize = '18px';
		( isset( $values['fontType'] ) ) ? $fontType                     = $values['fontType'] : $fontType = null;
		( isset( $values['fontWeight'] ) ) ? $fontWeight                 = $values['fontWeight'] : $fontWeight = null;
		( isset( $values['ServiceColorPicker'] ) ) ? $ServiceColorPicker = $values['ServiceColorPicker'] : $ServiceColorPicker = '#000000';
		( isset( $values['objectSize'] ) ) ? $objectSize                 = $values['objectSize'] : $objectSize = null;
		( isset( $values['objectColorPicker'] ) ) ? $objectColorPicker   = $values['objectColorPicker'] : $objectColorPicker = '#000000';
		( isset( $values['elementSkin'] ) ) ? $elementSkin               = $values['elementSkin'] : $elementSkin = 'style_1';
		( isset( $values['addContainer'] ) ) ? $addContainer             = $values['addContainer'] : $addContainer = 'false';
		( isset( $values['addtoCheckout'] ) ) ? $addtoCheckout           = $values['addtoCheckout'] : $addtoCheckout = 'open_cart';
		( isset( $values['buttonStyle'] ) ) ? $buttonStyle               = $values['buttonStyle'] : $buttonStyle = '1';
		( isset( $values['turnoffborder'] ) ) ? $turnoffborder           = $values['turnoffborder'] : $turnoffborder = 'false';
		( isset( $values['turnoffemailquote'] ) ) ? $turnoffemailquote   = $values['turnoffemailquote'] : $turnoffemailquote = 'false';
		( isset( $values['turnviewdetails'] ) ) ? $turnviewdetails       = $values['turnviewdetails'] : $turnviewdetails = 'false';
		( isset( $values['turnoffcoupon'] ) ) ? $turnoffcoupon           = $values['turnoffcoupon'] : $turnoffcoupon = 'false';
		( isset( $values['barstyle'] ) ) ? $barstyle                     = $values['barstyle'] : $barstyle = 'scc_tp_style4';
		( isset( $values['turnofffloating'] ) ) ? $turnofffloating       = $values['turnofffloating'] : $turnofffloating = 'false';
		( isset( $values['removeTotal'] ) ) ? $removeTotal               = $values['removeTotal'] : $removeTotal = 'false';
		( isset( $values['minimumTotal'] ) ) ? $minimumTotal             = $values['minimumTotal'] : $minimumTotal = '0';
		( isset( $values['minimumTotalChoose'] ) ) ? $minimumTotalChoose = $values['minimumTotalChoose'] : $minimumTotalChoose = null;
		( isset( $values['removeTitle'] ) ) ? $removeTitle               = $values['removeTitle'] : $removeTitle = 'false';
		( isset( $values['turnoffUnit'] ) ) ? $turnoffUnit               = $values['turnoffUnit'] : $turnoffUnit = 'fase';
		( isset( $values['turnoffQty'] ) ) ? $turnoffQty                 = $values['turnoffQty'] : $turnoffQty = 'fase';

		( isset( $values['turnoffSave'] ) ) ? $turnoffSave                   = $values['turnoffSave'] : $turnoffSave = 'false';
		( isset( $values['turnoffTax'] ) ) ? $turnoffTax                     = $values['turnoffTax'] : $turnoffTax = 'false';
		( isset( $values['taxVat'] ) ) ? $taxVat                             = $values['taxVat'] : $taxVat = null;
		( isset( $values['symbol'] ) ) ? $symbol                             = $values['symbol'] : $symbol = '0';
		( isset( $values['removeCurrency'] ) ) ? $removeCurrency             = $values['removeCurrency'] : $removeCurrency = 'false';
		( isset( $values['userCompletes'] ) ) ? $userCompletes               = $values['userCompletes'] : $userCompletes = 'false';
		( isset( $values['userClicksf'] ) ) ? $userClicksf                   = $values['userClicksf'] : $userClicksf = 'false';
		( isset( $values['showTaxBeforeTotal'] ) ) ? $showTaxBeforeTotal     = $values['showTaxBeforeTotal'] : $showTaxBeforeTotal = 'false';
		( isset( $values['formFieldsArray'] ) ) ? $formFieldsArray           = $values['formFieldsArray'] : $formFieldsArray = null;
		( isset( $values['webhookSettings'] ) ) ? $webhookSettings           = $values['webhookSettings'] : $webhookSettings = null;
		( isset( $values['showFieldsQuoteArray'] ) ) ? $showFieldsQuoteArray = $values['showFieldsQuoteArray'] : $showFieldsQuoteArray = null;
		( isset( $values['translation'] ) ) ? $translation                   = $values['translation'] : $translation = null;
		( isset( $values['paypalConfigArray'] ) ) ? $paypalConfigArray       = $values['paypalConfigArray'] : $paypalConfigArray = null;
		( isset( $values['isWoocommerceCheckoutEnabled'] ) ) ? $isWoocommerceCheckoutEnabled = $values['isWoocommerceCheckoutEnabled'] : $isWoocommerceCheckoutEnabled = null;
		( isset( $values['isStripeEnabled'] ) ) ? $isStripeEnabled                           = $values['isStripeEnabled'] : $isStripeEnabled = null;
		( isset( $values['ShowFormBuilderOnDetails'] ) ) ? $ShowFormBuilderOnDetails         = $values['ShowFormBuilderOnDetails'] : $ShowFormBuilderOnDetails = 'false';

		$query   = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_forms
            (id, formname, isWoocommerceCheckoutEnabled, isStripeEnabled, `description`, inheritFontType, titleFontSize, titleFontType, titleFontWeight, titleColorPicker, ServicefontSize, fontType, fontWeight, ServiceColorPicker, objectSize, objectColorPicker, elementSkin, addContainer, addtoCheckout,
            buttonStyle, turnoffborder, turnoffemailquote, turnviewdetails, turnoffcoupon, barstyle, turnofffloating, removeTotal, minimumTotal, minimumTotalChoose, removeTitle, turnoffUnit, turnoffQty, turnoffSave, turnoffTax, taxVat, symbol, removeCurrency, userCompletes,
            userClicksf, showTaxBeforeTotal, formFieldsArray, webhookSettings, showFieldsQuoteArray, translation, paypalConfigArray, ShowFormBuilderOnDetails, created_at) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);",
			$form_id,
			$formname,
			$isWoocommerceCheckoutEnabled,
			$isStripeEnabled,
			$description,
			$inheritFontType,
			$titleFontSize,
			$titleFontType,
			$titleFontWeight,
			$titleColorPicker,
			$ServicefontSize,
			$fontType,
			$fontWeight,
			$ServiceColorPicker,
			$objectSize,
			$objectColorPicker,
			$elementSkin,
			$addContainer,
			$addtoCheckout,
			$buttonStyle,
			$turnoffborder,
			$turnoffemailquote,
			$turnviewdetails,
			$turnoffcoupon,
			$barstyle,
			$turnofffloating,
			$removeTotal,
			$minimumTotal,
			$minimumTotalChoose,
			$removeTitle,
			$turnoffUnit,
			$turnoffQty,
			$turnoffSave,
			$turnoffTax,
			$taxVat,
			$symbol,
			$removeCurrency,
			$userCompletes,
			$userClicksf,
			$showTaxBeforeTotal,
			$formFieldsArray,
			$webhookSettings,
			$showFieldsQuoteArray,
			$translation,
			$paypalConfigArray,
			$ShowFormBuilderOnDetails,
			$now
		);
		$result  = $this->db->query( $query );
		$last_id = $this->db->insert_id;
		if ( $result ) {
			return $form_id;
		} else {
			return 0;
		}
	}
	/**
	 * *Returns one calculator data with sections, subsections, elements, elementitems and conditions
	 * !more than one file uses this query
	 * @param integer $id not null,
	 * @return object form with all realtion
	 */
	function readWithRelations( int $id ) {
		$scc_form = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_forms WHERE id =%d ;", $id ) );
		if ( $scc_form ) {
			$form_id            = $scc_form->id;
			$sections           = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_sections WHERE form_id =%d ORDER By `order`;", $form_id ) );
			$scc_form->sections = $sections;
			foreach ( $sections as $section ) {
				$section_id          = $section->id;
				$subsection          = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_subsections WHERE section_id =%d ;", $section_id ) );
				$section->subsection = $subsection;
				foreach ( $section->subsection as $sub ) {
					$sub_id       = $sub->id;
					$elements     = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_elements WHERE subsection_id =%d ORDER By orden +0; ", $sub_id ) );
					$sub->element = $elements;
					foreach ( $sub->element as $el2 ) {
						$elem_id         = $el2->id;
						$condition       = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_conditions WHERE element_id =%d ;", $elem_id ) );
						$el2->conditions = $condition;
						foreach ( $el2->conditions as $c ) {
							if ( $c->elementitem_id ) {
								$element             = $this->db->get_row( $this->db->prepare( "SELECT `name`,`uniqueId` FROM {$this->db->prefix}df_scc_elementitems WHERE id =%d ;", $c->elementitem_id ) );
								$c->elementitem_name = $element;
							}
							if ( $c->condition_element_id ) {
								$element              = $this->db->get_row( $this->db->prepare( "SELECT `titleElement`,`type`,`uniqueId` FROM {$this->db->prefix}df_scc_elements WHERE id =%d ;", $c->condition_element_id ) );
								$c->element_condition = $element;
							}
						}
					}
					foreach ( $sub->element as $el ) {
						$elem_id          = $el->id;
						$elements         = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_elementitems WHERE element_id =%d ;", $elem_id ) );
						$el->elementitems = $elements;
					}
				}
			}
			return $scc_form;
		}
	}

	/**
	 * *Returs one or all forms
	 * @param integer $id
	 * @return object returns the object with the id
	 * @return array if parameter is empty returns all forms
	 */

	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( "SELECT * FROM {$this->db->prefix}df_scc_forms" ) :
			$result            = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_forms WHERE id =%d", $id ) );
		return $result;
	}

     /**
      * *Returns all forms' name and the ID, for use in gutenberg block
      *
      * @param int $id
      *
      * @return array if parameter is empty returns all forms
      */
	  public function read_all_gutenberg() {
		$result = $this->db->get_results( "SELECT id, formname FROM {$this->db->prefix}df_scc_forms", ARRAY_A );

		return $result;
	}

	function getLastId() {
		$result = $this->db->get_row( $this->db->prepare( "SELECT MAX(id) as lastId FROM {$this->db->prefix}df_scc_forms", null ) );
		if ( $result ) {
			if ( $result->lastId == 'null' ) {
				return 0;
			} else {
				return $result->lastId;
			}
		} else {
			return 0;
		}

	}

	/**
	 * *This updates calculator title
	 * @param array $values array to update
	 * @param integer $id key of the form to update
	 * @return bool true or false
	 */
	function update( array $values ) {
		$id   = $values['id'];
		$i    = new self();
		$todo = $i->read( $id );

		( isset( $values['formname'] ) ) ? $formname                     = $values['formname'] : $formname = $todo->formname;
		( isset( $values['description'] ) ) ? $description               = $values['description'] : $description = $todo->description;
		( isset( $values['inheritFontType'] ) ) ? $inheritFontType       = $values['inheritFontType'] : $inheritFontType = $todo->inheritFontType;
		( isset( $values['titleFontSize'] ) ) ? $titleFontSize           = $values['titleFontSize'] : $titleFontSize = $todo->titleFontSize;
		( isset( $values['titleFontType'] ) ) ? $titleFontType           = $values['titleFontType'] : $titleFontType = $todo->titleFontType;
		( isset( $values['titleFontWeight'] ) ) ? $titleFontWeight       = $values['titleFontWeight'] : $titleFontWeight = $todo->titleFontWeight;
		( isset( $values['titleColorPicker'] ) ) ? $titleColorPicker     = $values['titleColorPicker'] : $titleColorPicker = $todo->titleColorPicker;
		( isset( $values['ServicefontSize'] ) ) ? $ServicefontSize       = $values['ServicefontSize'] : $ServicefontSize = $todo->ServicefontSize;
		( isset( $values['fontType'] ) ) ? $fontType                     = $values['fontType'] : $fontType = $todo->fontType;
		( isset( $values['fontWeight'] ) ) ? $fontWeight                 = $values['fontWeight'] : $fontWeight = $todo->fontWeight;
		( isset( $values['ServiceColorPicker'] ) ) ? $ServiceColorPicker = $values['ServiceColorPicker'] : $ServiceColorPicker = $todo->ServiceColorPicker;
		( isset( $values['objectSize'] ) ) ? $objectSize                 = $values['objectSize'] : $objectSize = $todo->objectSize;
		( isset( $values['objectColorPicker'] ) ) ? $objectColorPicker   = $values['objectColorPicker'] : $objectColorPicker = $todo->objectColorPicker;
		( isset( $values['elementSkin'] ) ) ? $elementSkin               = $values['elementSkin'] : $elementSkin = $todo->elementSkin;
		( isset( $values['addContainer'] ) ) ? $addContainer             = $values['addContainer'] : $addContainer = $todo->addContainer;
		( isset( $values['addtoCheckout'] ) ) ? $addtoCheckout           = $values['addtoCheckout'] : $addtoCheckout = $todo->addtoCheckout;
		( isset( $values['buttonStyle'] ) ) ? $buttonStyle               = $values['buttonStyle'] : $buttonStyle = $todo->buttonStyle;
		( isset( $values['turnoffborder'] ) ) ? $turnoffborder           = $values['turnoffborder'] : $turnoffborder = $todo->turnoffborder;
		( isset( $values['turnoffemailquote'] ) ) ? $turnoffemailquote   = $values['turnoffemailquote'] : $turnoffemailquote = $todo->turnoffemailquote;
		( isset( $values['turnviewdetails'] ) ) ? $turnviewdetails       = $values['turnviewdetails'] : $turnviewdetails = $todo->turnviewdetails;
		( isset( $values['turnoffcoupon'] ) ) ? $turnoffcoupon           = $values['turnoffcoupon'] : $turnoffcoupon = $todo->turnoffcoupon;
		( isset( $values['barstyle'] ) ) ? $barstyle                     = $values['barstyle'] : $barstyle = $todo->barstyle;
		( isset( $values['turnofffloating'] ) ) ? $turnofffloating       = $values['turnofffloating'] : $turnofffloating = $todo->turnofffloating;
		( isset( $values['removeTotal'] ) ) ? $removeTotal               = $values['removeTotal'] : $removeTotal = $todo->removeTotal;
		( isset( $values['minimumTotal'] ) ) ? $minimumTotal             = $values['minimumTotal'] : $minimumTotal = $todo->minimumTotal;
		( isset( $values['minimumTotalChoose'] ) ) ? $minimumTotalChoose = $values['minimumTotalChoose'] : $minimumTotalChoose = $todo->minimumTotalChoose;
		( isset( $values['removeTitle'] ) ) ? $removeTitle               = $values['removeTitle'] : $removeTitle = $todo->removeTitle;
		( isset( $values['turnoffUnit'] ) ) ? $turnoffUnit               = $values['turnoffUnit'] : $turnoffUnit = $todo->turnoffUnit;
		( isset( $values['turnoffQty'] ) ) ? $turnoffQty                 = $values['turnoffQty'] : $turnoffQty = $todo->turnoffQty;

		( isset( $values['turnoffSave'] ) ) ? $turnoffSave                   = $values['turnoffSave'] : $turnoffSave = $todo->turnoffSave;
		( isset( $values['turnoffTax'] ) ) ? $turnoffTax                     = $values['turnoffTax'] : $turnoffTax = $todo->turnoffTax;
		( isset( $values['taxVat'] ) ) ? $taxVat                             = $values['taxVat'] : $taxVat = $todo->taxVat;
		( isset( $values['symbol'] ) ) ? $symbol                             = $values['symbol'] : $symbol = $todo->symbol;
		( isset( $values['removeCurrency'] ) ) ? $removeCurrency             = $values['removeCurrency'] : $removeCurrency = $todo->removeCurrency;
		( isset( $values['userCompletes'] ) ) ? $userCompletes               = $values['userCompletes'] : $userCompletes = $todo->userCompletes;
		( isset( $values['userClicksf'] ) ) ? $userClicksf                   = $values['userClicksf'] : $userClicksf = $todo->userClicksf;
		( isset( $values['showTaxBeforeTotal'] ) ) ? $showTaxBeforeTotal     = $values['showTaxBeforeTotal'] : $showTaxBeforeTotal = $todo->showTaxBeforeTotal;
		( isset( $values['formFieldsArray'] ) ) ? $formFieldsArray           = $values['formFieldsArray'] : $formFieldsArray = $todo->formFieldsArray;
		( isset( $values['webhookSettings'] ) ) ? $webhookSettings           = $values['webhookSettings'] : $webhookSettings = $todo->webhookSettings;
		( isset( $values['showFieldsQuoteArray'] ) ) ? $showFieldsQuoteArray = $values['showFieldsQuoteArray'] : $showFieldsQuoteArray = $todo->showFieldsQuoteArray;
		( isset( $values['translation'] ) ) ? $translation                   = $values['translation'] : $translation = $todo->translation;
		( isset( $values['paypalConfigArray'] ) ) ? $paypalConfigArray       = $values['paypalConfigArray'] : $paypalConfigArray = $todo->paypalConfigArray;
		( isset( $values['isWoocommerceCheckoutEnabled'] ) ) ? $isWoocommerceCheckoutEnabled = $values['isWoocommerceCheckoutEnabled'] : $isWoocommerceCheckoutEnabled = $todo->isWoocommerceCheckoutEnabled;
		( isset( $values['isStripeEnabled'] ) ) ? $isStripeEnabled                           = $values['isStripeEnabled'] : $isStripeEnabled = $todo->isStripeEnabled;
		( isset( $values['ShowFormBuilderOnDetails'] ) ) ? $ShowFormBuilderOnDetails         = $values['ShowFormBuilderOnDetails'] : $ShowFormBuilderOnDetails = 'false';
		$wrapper_max_width = isset( $values['wrapper_max_width'] ) ? $values['wrapper_max_width'] : $todo->wrapper_max_width;

		$request = $this->db->query(
			$this->db->prepare(
				"UPDATE {$this->db->prefix}df_scc_forms SET formname=%s, isWoocommerceCheckoutEnabled =%s, isStripeEnabled =%s, `description`=%s,inheritFontType=%s,titleFontSize=%s,titleFontType=%s,titleFontWeight=%s,titleColorPicker=%s,ServicefontSize=%s,
        fontType=%s,fontWeight=%s,ServiceColorPicker=%s,objectSize=%s,objectColorPicker=%s,elementSkin=%s,addContainer=%s,addtoCheckout=%s,buttonStyle=%s,turnoffborder=%s,turnoffemailquote=%s,turnviewdetails=%s,turnoffcoupon=%s,barstyle=%s,
        turnofffloating=%s,removeTotal=%s,minimumTotal=%s,minimumTotalChoose=%s,removeTitle=%s,turnoffUnit=%s,turnoffQty=%s,turnoffSave=%s,turnoffTax=%s,taxVat=%s,symbol=%s,removeCurrency=%s,userCompletes=%s,userClicksf=%s,showTaxBeforeTotal=%s,
        formFieldsArray=%s,webhookSettings=%s,showFieldsQuoteArray=%s,translation=%s,paypalConfigArray=%s,ShowFormBuilderOnDetails=%s,wrapper_max_width=%d WHERE id=%d ;",
				$formname,
				$isWoocommerceCheckoutEnabled,
				$isStripeEnabled,
				$description,
				$inheritFontType,
				$titleFontSize,
				$titleFontType,
				$titleFontWeight,
				$titleColorPicker,
				$ServicefontSize,
				$fontType,
				$fontWeight,
				$ServiceColorPicker,
				$objectSize,
				$objectColorPicker,
				$elementSkin,
				$addContainer,
				$addtoCheckout,
				$buttonStyle,
				$turnoffborder,
				$turnoffemailquote,
				$turnviewdetails,
				$turnoffcoupon,
				$barstyle,
				$turnofffloating,
				$removeTotal,
				$minimumTotal,
				$minimumTotalChoose,
				$removeTitle,
				$turnoffUnit,
				$turnoffQty,
				$turnoffSave,
				$turnoffTax,
				$taxVat,
				$symbol,
				$removeCurrency,
				$userCompletes,
				$userClicksf,
				$showTaxBeforeTotal,
				$formFieldsArray,
				$webhookSettings,
				$showFieldsQuoteArray,
				$translation,
				$paypalConfigArray,
				$ShowFormBuilderOnDetails,
				$wrapper_max_width,
				$id
			)
		);

		if ( $request ) {
			return json_encode( array( 'msj' => 'the form was updated' ) );
		} else {
			return json_encode( array( 'msj' => 'There was an error' ) );
		}
	}

	/**
	 * *This deletes one calculator
	 * @param integer $id key of the calculator to delete
	 * @return bool true or false
	 */
	function delete( int $id ) {
		$query = $this->db->delete( "{$this->db->prefix}df_scc_forms", array( 'id' => $id ) );
		if ( $query ) {
			return json_encode( array( 'msj' => 'the form was deleted' ) );
		} else {
			return json_encode( array( 'msj' => 'An error occured, please contact support team' ) );
		}
	}
}
