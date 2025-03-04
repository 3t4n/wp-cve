<?php

defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Description of Category
 *
 * @author mark
 */
class HunchSchema_Category extends HunchSchema_Thing {

    public $schemaType = "CollectionPage";
    
    public function getResource($pretty = false) {
        $hasPart = array();

        while ( have_posts() ) : the_post();

			$part = array(
				'@type' => ! empty( $this->Settings['SchemaDefaultTypePost'] ) ? $this->Settings['SchemaDefaultTypePost'] : 'BlogPosting',
				'@id' => get_the_permalink(),
				'headline' => get_the_title(),
				'url' => get_the_permalink(),
				'datePublished' => get_the_date('Y-m-d'),
				'dateModified' => get_the_modified_date('Y-m-d'),
				'mainEntityOfPage' => get_the_permalink(),
				'author' => $this->getAuthor(),
				'publisher' => $this->getPublisher(),
				'image' => $this->getImage(),
				'keywords' => $this->getTags(),
			);

			if ( get_comments_number() && empty( $this->Settings['SchemaHideComments'] ) ) {
				$part['commentCount'] = get_comments_number();
				$part['comment'] = $this->getComments();
			}

            $hasPart[] = $part;

        endwhile;


        $this->schema = array(
			'@context' => 'https://schema.org/',
            '@type' => $this->schemaType,
			'@id' => get_category_link( get_query_var( 'cat' ) ) . '#' . $this->schemaType,
            'headline' => single_cat_title( '', false ) . " Category",
            'description' => category_description(),
            'url' => get_category_link( get_query_var( 'cat' ) ),
            'hasPart' => $hasPart
        );

        return $this->toJson( $this->schema, $pretty );
    }

}