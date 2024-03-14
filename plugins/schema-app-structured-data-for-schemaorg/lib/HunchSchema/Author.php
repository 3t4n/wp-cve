<?php

defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Description of Author Page
 *
 * @author mark
 */
class HunchSchema_Author extends HunchSchema_Thing {

    public $schemaType = "ProfilePage";

    public function __construct() {
        
    }

    public function getResource($pretty = false) {
		global $post;

        $author = $this->getAuthor();

        $this->schema = array
        (
            '@context' => 'https://schema.org/',
            '@type' => $this->schemaType,
            '@id' => esc_url(get_author_posts_url(get_the_author_meta('ID', $post->post_author))) . '#' . $this->schemaType,
            'headline' => sprintf('About %s', get_the_author()),
            'dateCreated' => get_userdata( $post->post_author )->user_registered,
            'datePublished' => get_the_date('Y-m-d H:i:s'),
            'dateModified' => get_the_modified_date('Y-m-d H:i:s'),
            'about' => $author,
            'mainEntity' => $author,
        );

        return $this->toJson( $this->schema, $pretty );
    }

}
