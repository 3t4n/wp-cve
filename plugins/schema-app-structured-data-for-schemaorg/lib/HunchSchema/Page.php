<?php

defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Description of Article
 *
 * @author mark
 */
class HunchSchema_Page extends HunchSchema_Thing
{
    /**
     * Get Default Schema.org for Resource
     * 
     * @param type boolean
     * @return type string
     */
    public function getResource($pretty = false)
    {
        global $post;

        $Permalink = get_permalink();

        if ( is_front_page() )
        {
			$Permalink = home_url();
        }

        $MarkupTypeDefault = ! empty( $this->Settings['SchemaDefaultTypePage'] ) ? $this->Settings['SchemaDefaultTypePage'] : 'Article';
        $MarkupType = get_post_meta( $post->ID, '_HunchSchemaType', true );
		$this->schemaType = $MarkupType ? $MarkupType : $MarkupTypeDefault;


        $this->schema = array
        (
            '@context' => 'https://schema.org/',
            '@type' => $this->schemaType,
            '@id' => $Permalink . '#' . $this->schemaType,
            'mainEntityOfPage' => $Permalink,
            'headline' => get_the_title(),
            'name' => get_the_title(),
            'description' => $this->getExcerpt(),
            'datePublished' => get_the_date( 'Y-m-d' ),
            'dateModified' => get_the_modified_date('Y-m-d'),
            'author' => $this->getAuthor(),
            'publisher' => $this->getPublisher(),
            'image' => $this->getImage(),
            'url' => $Permalink,
        );

		if ( ! empty( $this->Settings['SchemaDefaultVideoMarkup'] ) )
		{
			$this->schema['video'] = $this->getVideos();
		}

		if ( get_comments_number() && empty( $this->Settings['SchemaHideComments'] ) )
		{
			$this->schema['commentCount'] = get_comments_number();
			$this->schema['comment'] = $this->getComments();
		}

        return $this->toJson( $this->schema, $pretty );
    }


    public function getBreadcrumb( $Pretty = false ) {
		global $post;

		$position							= 1;
		$this->SchemaBreadcrumb['@context']	= 'https://schema.org';
		$this->SchemaBreadcrumb['@type']	= 'BreadcrumbList';

		if ( $post->post_parent ) {
			$post_ancestors = array_reverse( get_post_ancestors( $post->ID ) );

			foreach( $post_ancestors as $post_id ) {
				$this->SchemaBreadcrumb['itemListElement'][] = array(
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => get_the_title( $post_id ),
					'item' => get_permalink( $post_id ) . "#breadcrumbitem",
				);
			}
		}

		if ( ! is_front_page() ) {
			$this->SchemaBreadcrumb['itemListElement'][] = array(
				'@type' => 'ListItem',
				'position' => $position++,
				'name' => get_the_title(),
				'item' => get_permalink() . '#breadcrumbitem',
			);
		} else {
			return;
		}

        return $this->toJson( $this->SchemaBreadcrumb, $Pretty );
    }
}