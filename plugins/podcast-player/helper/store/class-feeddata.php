<?php
/**
 * Object to store podcast feed data.
 *
 * Object will save channel level data and itemdata objects.
 *
 * @link       https://easypodcastpro.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Store;

/**
 * Store podcast feed level data.
 *
 * @package Podcast_Player
 */
class FeedData extends StoreBase {

    /**
     * Holds podcast store object ID.
     *
     * @since  1.0.0
     * @access protected
     * @var    int
     */
    protected $post_id = 0;

    /**
     * Holds podcast title.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $title;

    /**
     * Holds podcast description.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $desc;

    /**
     * Holds podcast website link.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $link;

    /**
     * Holds podcast cover image.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $image;

    /**
     * Holds podcast cover image ID.
     *
     * @since  1.0.0
     * @access protected
     * @var    int
     */
    protected $cover_id = 0;

    /**
     * Holds podcast feed url.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $furl;

    /**
     * Holds podcast podcast unique key.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $fkey;

    /**
     * Holds podcast copyright information.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $copyright;

    /**
     * Holds podcast author.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $author;

    /**
     * Holds podcast title.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $podcats;

    /**
     * Holds podcast lastbuild date.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $lastbuild;

    /**
     * Holds podcast owner name and email ID.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $owner;

    /**
     * Holds array of podcast episode objects.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $items;

    /**
     * Holds array of all podcast seasons.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $seasons;

    /**
     * Holds array of all podcast episode categories.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $categories;

    /**
     * Holds total number of podcast episodes.
     *
     * @since  1.0.0
     * @access protected
     * @var    int
     */
    protected $total;

    /**
     * Get escape functions.
     *
     * @since 1.0.0
     */
    protected function typeDeclaration() {
        // Data type declaration for safe and proper data output.
        return array(
            'post_id'    => 'int',
            'title'      => 'title',
            'desc'       => 'desc',
            'link'       => 'url',
            'image'      => 'url',
            'cover_id'   => 'int',
            'furl'       => 'url',
            'fkey'       => 'string',
            'copyright'  => 'string',
            'author'     => 'string',
            'podcats'    => 'podcats',
            'lastbuild'  => 'string',
            'owner'      => 'owner',
            'items'      => 'none',
            'seasons'    => 'arrString',
            'categories' => 'arrString',
            'total'      => 'int',
        );
    }

    /**
     * Retrieve podcast feed data as an array.
     *
     * @since 1.0.0
     *
     * @param string $context Retrieve Context
     */
    public function retrieve( $context = 'echo' ) {
        $items = $this->get( 'items' );
        if ( empty( $items ) ) {
            return new \WP_Error(
				'no-items-error',
				esc_html__( 'No feed items available.', 'podcast-player' )
			);
        }

        $epi_arr = array();
        foreach ($items as $key => $item) {
            $epi_arr[ $key ] = $item->retrieve( $context );
        }

        // Data type declaration for safe and proper data output.
        $retrieve = $this->get(
            array(
                'title',
                'desc',
                'link',
                'image',
                'cover_id',
                'furl',
                'fkey',
                'copyright',
                'author',
                'podcats',
                'lastbuild',
                'owner',
                'seasons',
                'categories',
                'total',
            ),
            $context
        );
        $retrieve[ 'items' ] = $epi_arr;
        return $retrieve;
    }

    /**
     * Set magic method.
     *
     * Do not allow adding any new properties to this object.
     *
     * @since 1.0.0
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value) {
        throw new Exception("Cannot add new property \$$name to instance of " . __CLASS__);
    }
}
