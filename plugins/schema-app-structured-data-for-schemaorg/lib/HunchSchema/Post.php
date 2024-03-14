<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Description of BlogPosting
 *
 * @author mark
 */
class HunchSchema_Post extends HunchSchema_Page
{
    public function getResource($pretty = false)
    {
        global $post;

        parent::getResource($pretty);


        $this->schemaType = ! empty( $this->Settings['SchemaDefaultTypePost'] ) ? $this->Settings['SchemaDefaultTypePost'] : 'BlogPosting';
        $this->schema['@type'] = $this->schemaType;
		$this->schema['@id'] = get_permalink() . '#' . $this->schema['@type'];


        // Get the Categories
        $categories = get_the_category();
        if (count($categories) > 0) {
            foreach ($categories AS $category) {
                $categoryNames[] = $category->name;
            }
            $this->schema['about'] = $categoryNames;
        }


		$this->schema['wordCount'] = str_word_count( wp_strip_all_tags( $this->getContent() ) );
		$this->schema['keywords'] = $this->getTags();

		if ( ! empty( $this->Settings['SchemaArticleBody'] ) )
		{
			$this->schema['articleBody'] = str_replace( array( "\n", "\r" ), '', strip_tags( apply_filters( 'the_content', get_post_field( 'post_content', $post->ID ) ) ) );
		}


        return $this->toJson( $this->schema, $pretty );
    }


    public function getBreadcrumb( $Pretty = false ) {
		$position							= 1;
		$this->SchemaBreadcrumb['@context']	= 'https://schema.org/';
		$this->SchemaBreadcrumb['@type']	= 'BreadcrumbList';

		if ( get_option( 'permalink_structure' ) ) {
			$permalink_host			= stristr( get_permalink(), parse_url( get_permalink(), PHP_URL_PATH ), true );
			$permalink_path_parts	= array_values( array_filter( explode( '/', parse_url( get_permalink(), PHP_URL_PATH ) ) ) );

			if ( ! empty( $permalink_path_parts ) ) {
				array_pop( $permalink_path_parts );
			}

			if ( ! empty( $permalink_path_parts ) ) {
				$breadcrumb_permalink = $permalink_host;

				foreach ( $permalink_path_parts as $item ) {
					$breadcrumb_permalink .= "/{$item}/";

					$this->SchemaBreadcrumb['itemListElement'][] = array(
						'@type' => 'ListItem',
						'position' => $position,
						'name' => ucwords( str_replace( '-', ' ', $item ) ),
						'item' => $breadcrumb_permalink . '#breadcrumbitem',
					);

					$position++;
				}
			}
		}

		$this->SchemaBreadcrumb['itemListElement'][] = array(
			'@type' => 'ListItem',
			'position' => $position++,
			'name' => get_the_title(),
			'item' => get_permalink() . '#breadcrumbitem',
		);

        return $this->toJson( $this->SchemaBreadcrumb, $Pretty );
    }
}