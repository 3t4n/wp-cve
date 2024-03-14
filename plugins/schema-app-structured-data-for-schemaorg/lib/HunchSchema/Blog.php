<?php

defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Description of Blog
 *
 * @author mark
 */
class HunchSchema_Blog extends HunchSchema_Thing
{
    public $schemaType = "Blog";

    public function getResource($pretty = false)
    {
        if ( is_front_page() && is_home() || is_front_page() )
        {
            $Headline = get_bloginfo( 'name' );
            $Permalink = home_url();
        }
        else
        {
            $Headline = get_the_title( get_option( 'page_for_posts' ) );
            $Permalink = get_permalink( get_option( 'page_for_posts' ) );
        }


        $hasPart = array();

        while ( have_posts() ) : the_post();

			$part = array(
				'@type' => ! empty( $this->Settings['SchemaDefaultTypePost'] ) ? $this->Settings['SchemaDefaultTypePost'] : 'BlogPosting',
				'@id' => get_the_permalink(),
				'headline' => get_the_title(),
				'url' => get_the_permalink(),
				'datePublished' => get_the_date( 'Y-m-d' ),
				'dateModified' => get_the_modified_date( 'Y-m-d' ),
				'mainEntityOfPage' => get_the_permalink(),
				'author' => $this->getAuthor(),
				'publisher' => $this->getPublisher(),
				'image' => $this->getImage(),
				'wordCount' => str_word_count( get_the_content() ),
				'keywords' => $this->getTags(),
            );

			if ( get_comments_number() && empty( $this->Settings['SchemaHideComments'] ) ) {
				$part['commentCount'] = get_comments_number();
				$part['comment'] = $this->getComments();
			}

            $hasPart[] = $part;

        endwhile;


        $this->schema = array (
            '@context' => 'https://schema.org/',
            '@type' => $this->schemaType,
			'@id' => $Permalink . '#' . $this->schemaType,
            'headline' => $Headline,
            'description' => get_bloginfo( 'description' ),
            'url' => $Permalink,
        );

		if ( ! empty( $this->Settings['SchemaDefaultTypePost'] ) ) {
			$this->schema['hasPart'] = $hasPart;
		} else {
			$this->schema['blogPost'] = $hasPart;
		}


        return $this->toJson( $this->schema, $pretty );
    }
}