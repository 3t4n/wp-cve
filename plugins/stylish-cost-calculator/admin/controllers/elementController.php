<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class elementController {


	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}
	public function __destruct() {
		// $this->db->close();
	}

	/**
	 *
	 * @param string $orden
	 * @param string $titleElement
	 * @param string $type not null
	 * @param string $value1
	 * @param string $value2
	 * @param string $value3
	 * @param string $value4
	 * @param string $length to be removed
	 * @param string $uniqueId to be removed
	 * @param string $mandatory
	 * @param string $titleColumnDesktop
	 * @param string $titleColumnMobile
	 * @param string $subsection_id not null - foreign_key
	 * @param string $showPriceHint
	 * @param string $displayFrontend
	 * @param string $displayDetailList
	 * @param string $showTitlePdf
	 * @return int $id return id of created element
	 */

	function create( array $values ) {
		if ( ! function_exists( 'unique' ) ) {
			function unique() {
				return substr( str_shuffle( '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 0, 10 );
			}
		}

		( isset( $values['orden'] ) ) ? $orden                           = $values['orden'] : $orden = '';
		( isset( $values['titleElement'] ) ) ? $titleElement             = $values['titleElement'] : $titleElement = null;
		( isset( $values['type'] ) ) ? $type                             = $values['type'] : $type = '';
		( isset( $values['value1'] ) ) ? $value1                         = $values['value1'] : $value1 = null;
		( isset( $values['value2'] ) ) ? $value2                         = $values['value2'] : $value2 = null;
		( isset( $values['value3'] ) ) ? $value3                         = $values['value3'] : $value3 = null;
		( isset( $values['value4'] ) ) ? $value4                         = $values['value4'] : $value4 = null;
		( isset( $values['length'] ) ) ? $length                         = $values['length'] : $length = '12asd';
		( isset( $values['uniqueId'] ) ) ? $uniqueId                     = $values['uniqueId'] : $uniqueId = unique();
		( isset( $values['mandatory'] ) ) ? $mandatory                   = $values['mandatory'] : $mandatory = 0;
		( isset( $values['titleColumnDesktop'] ) ) ? $titleColumnDesktop = $values['titleColumnDesktop'] : $titleColumnDesktop = '4';
		( isset( $values['titleColumnMobile'] ) ) ? $titleColumnMobile   = $values['titleColumnMobile'] : $titleColumnMobile = '12';
		( isset( $values['subsection_id'] ) ) ? $subsection_id           = $values['subsection_id'] : $subsection_id = '';
		( isset( $values['showPriceHint'] ) ) ? $showPriceHint           = $values['showPriceHint'] : $showPriceHint = 0;
		( isset( $values['displayFrontend'] ) ) ? $displayFrontend       = $values['displayFrontend'] : $displayFrontend = 0;
		( isset( $values['displayDetailList'] ) ) ? $displayDetailList   = $values['displayDetailList'] : $displayDetailList = 0;
		( isset( $values['showInputBoxSlider'] ) ) ? $showInputBoxSlider = $values['showInputBoxSlider'] : $showInputBoxSlider = 00;

		( isset( $values['showTitlePdf'] ) ) ? $showTitlePdf = $values['showTitlePdf'] : $showTitlePdf = 0;
		$query  = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_elements (`orden`,`titleElement`,`type`,`value1`,`value2`,`value3`,`value4`,`length`,`uniqueId`,`mandatory`,`titleColumnDesktop`, 
        `titleColumnMobile`, `subsection_id`, `showPriceHint`, `displayFrontend`, `displayDetailList`, `showTitlePdf`,`showInputBoxSlider`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);",
			$orden,
			$titleElement,
			$type,
			$value1,
			$value2,
			$value3,
			$value4,
			$length,
			$uniqueId,
			$mandatory,
			$titleColumnDesktop,
			$titleColumnMobile,
			$subsection_id,
			$showPriceHint,
			$displayFrontend,
			$displayDetailList,
			$showTitlePdf,
			$showInputBoxSlider
		);
		$result = $this->db->query( $query );
		$id     = $this->db->insert_id;
		if ( $result ) {
			return $id;
		} else {
			return $this->db->last_error;
		}
	}

	/**
	 * @param $id id of element to get row
	 * @return object object of one element
	 * @return array array of elements
	 * ?id must be "" to get all elements
	 */
	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_elements" ) ) :
			$result            = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_elements WHERE id =%d", $id ) );
		return $result;
	}

	/**
	 * @param string $id
	 * @param string $orden
	 * @param string $titleElement
	 * @param string $type
	 * @param string $value1
	 * @param string $value2
	 * @param string $value3
	 * @param string $value4
	 * @param string $length
	 * @param string $uniqueId
	 * @param string $mandatory
	 * @param string $titleColumnDesktop
	 * @param string $titleColumnMobile
	 * @param string $subsection_id foreign_key
	 * @param string $showPriceHint
	 * @param string $displayFrontend
	 * @param string $displayDetailList
	 * @param string $showTitlePdf
	 * @param string $element_woocomerce_product_id
	 * ?values must be array with name of column and value
	 */
	function update( array $values ) {
		$i                                     = new self();
		$todo                                  = $i->read( $values['id'] );
		$id                                    = $values['id'];
		( isset( $values['orden'] ) ) ? $orden = $values['orden'] : $orden = $todo->orden;
		( isset( $values['titleElement'] ) ) ? $titleElement             = $values['titleElement'] : $titleElement = $todo->titleElement;
		( isset( $values['type'] ) ) ? $type                             = $values['type'] : $type = $todo->type;
		( isset( $values['value1'] ) ) ? $value1                         = $values['value1'] : $value1 = $todo->value1;
		( isset( $values['value2'] ) ) ? $value2                         = $values['value2'] : $value2 = $todo->value2;
		( isset( $values['value3'] ) ) ? $value3                         = $values['value3'] : $value3 = $todo->value3;
		( isset( $values['value4'] ) ) ? $value4                         = $values['value4'] : $value4 = $todo->value4;
		$value5 														 = isset( $values['value5'] ) ? $values['value5'] : $todo->value5;
		$value5                                                          = intval( $value5 );
		( isset( $values['length'] ) ) ? $length                         = $values['length'] : $length = $todo->length;
		( isset( $values['uniqueId'] ) ) ? $uniqueId                     = $values['uniqueId'] : $uniqueId = $todo->uniqueId;
		( isset( $values['mandatory'] ) ) ? $mandatory                   = $values['mandatory'] : $mandatory = $todo->mandatory;
		( isset( $values['titleColumnDesktop'] ) ) ? $titleColumnDesktop = $values['titleColumnDesktop'] : $titleColumnDesktop = $todo->titleColumnDesktop;
		( isset( $values['titleColumnMobile'] ) ) ? $titleColumnMobile   = $values['titleColumnMobile'] : $titleColumnMobile = $todo->titleColumnMobile;
		( isset( $values['subsection_id'] ) ) ? $subsection_id           = $values['subsection_id'] : $subsection_id = $todo->subsection_id;
		( isset( $values['showPriceHint'] ) ) ? $showPriceHint           = $values['showPriceHint'] : $showPriceHint = $todo->showPriceHint;
		( isset( $values['displayFrontend'] ) ) ? $displayFrontend       = $values['displayFrontend'] : $displayFrontend = $todo->displayFrontend;
		( isset( $values['displayDetailList'] ) ) ? $displayDetailList   = $values['displayDetailList'] : $displayDetailList = $todo->displayDetailList;
		( isset( $values['showTitlePdf'] ) ) ? $showTitlePdf             = $values['showTitlePdf'] : $showTitlePdf = $todo->showTitlePdf;
		( isset( $values['element_woocomerce_product_id'] ) ) ? $element_woocomerce_product_id = $values['element_woocomerce_product_id'] : $element_woocomerce_product_id = $todo->element_woocomerce_product_id;
		( isset( $values['showInputBoxSlider'] ) ) ? $showInputBoxSlider                       = $values['showInputBoxSlider'] : $showInputBoxSlider = 0;

		$query    = $this->db->prepare(
			"UPDATE {$this->db->prefix}df_scc_elements SET orden =%s,titleElement=%s,`type`=%s,value1=%s,value2=%s,value3=%s,value4=%s,value5=%d,`length`=%s,uniqueId=%s,mandatory=%d,titleColumnDesktop=%s, 
        titleColumnMobile=%s, subsection_id=%d, showPriceHint=%d, displayFrontend=%d, displayDetailList=%d, showTitlePdf=%d, element_woocomerce_product_id=%s,showInputBoxSlider=%d WHERE id =%d",
			$orden,
			$titleElement,
			$type,
			$value1,
			$value2,
			$value3,
			$value4,
			$value5,
			$length,
			$uniqueId,
			$mandatory,
			$titleColumnDesktop,
			$titleColumnMobile,
			$subsection_id,
			$showPriceHint,
			$displayFrontend,
			$displayDetailList,
			$showTitlePdf,
			$element_woocomerce_product_id,
			$showInputBoxSlider,
			$id
		);
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * @param id
	 * ?param to delete element
	 */

	function delete( int $id ) {
		$query    = $this->db->prepare( "DELETE FROM {$this->db->prefix}df_scc_elements WHERE id = %d", $id );
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * *get elements by subsection
	 * @param integer $subsection_id
	 * ? this is use to checked if there is already a slider in subsection
	 */
	function getBySubsection( int $subsection_id ) {
		$result = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_elements WHERE subsection_id =%d", $subsection_id ) );
		return $result;
	}

	/**
	 * *Search by uniqueId Column and returns id
	 * !is used for conditionals
	 * @param integer $uniqueId
	 * @return integer $idElement
	 */

	function getByUniqueId( string $unqueId ) {

		$result = $this->db->get_row( $this->db->prepare( "SELECT id,`type` FROM {$this->db->prefix}df_scc_elements WHERE uniqueId =%s LIMIT 1", $unqueId ) );
		if ( $result ) {
			return $result;
		} else {
			return null;
		}
	}
}
