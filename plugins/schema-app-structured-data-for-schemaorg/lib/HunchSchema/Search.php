<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Description of Search
 *
 * @author mark
 */
class HunchSchema_Search extends HunchSchema_Thing {

    public $schemaType = "SearchResultsPage";
    
    public function getResource($pretty = false) {
        $this->schema = array(
            '@context' => 'https://schema.org/',
            '@type' => $this->schemaType,
            '@id' => get_search_link() . '#' . $this->schemaType,
        );
        
        return $this->toJson( $this->schema, $pretty );
    }
}
