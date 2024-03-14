<?php
/**
 * Object to store podcast Episode feed data.
 *
 * Object will save episode level data.
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
class ItemData extends StoreBase {

    /**
     * Holds item title.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $title;

    /**
     * Holds item description.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $description;

    /**
     * Holds item description.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $author;

    /**
     * Holds item release date with offset.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $date;

    /**
     * Holds item link.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $link;

    /**
     * Holds item audio src.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $src;

    /**
     * Holds item featured image url.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $featured;

    /**
     * Holds item featured image ID.
     *
     * @since  1.0.0
     * @access protected
     * @var    int
     */
    protected $featured_id = 0;

    /**
     * Holds item media type.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $mediatype;

    /**
     * Holds item iTunes episode number.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $episode;

    /**
     * Holds item iTunes season number.
     *
     * @since  1.0.0
     * @access protected
     * @var    int
     */
    protected $season;

    /**
     * Holds item categories.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $categories;

    /**
     * Holds item unique ID.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $episode_id;

    /**
     * Holds item play duration.
     *
     * @since  1.0.0
     * @access protected
     * @var    int
     */
    protected $duration;

    /**
     * Get escape functions.
     *
     * @since 1.0.0
     */
    protected function typeDeclaration() {
        // Data type declaration for safe and proper data output.
        return array(
            'title'       => 'title',
            'description' => 'desc',
            'author'      => 'string',
            'date'        => 'date',
            'link'        => 'url',
            'src'         => 'url',
            'featured'    => 'url',
            'featured_id' => 'int',
            'mediatype'   => 'string',
            'episode'     => 'string',
            'season'      => 'int',
            'categories'  => 'arrString',
            'episode_id'  => 'episodeid',
            'duration'    => 'dur',
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
        // Data type declaration for safe and proper data output.
        return $this->get(
            array(
                'title',
                'description',
                'author',
                'date',
                'link',
                'src',
                'featured',
                'featured_id',
                'mediatype',
                'episode',
                'season',
                'categories',
                'episode_id',
                'duration',
            ),
            $context
        );
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
