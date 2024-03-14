<?php
/**
 * Base class to store podcast feed data.
 *
 * @link       https://easypodcastpro.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Store;

use Podcast_Player\Helper\Store\StoreBase;

/**
 * Storage Register
 *
 * @since 1.0.0
 */
class StorageRegister extends StoreBase {
    /**
     * Holds podcast custom post object ID
     *
     * @since  1.0.0
     * @access private
     * @var    int
     */
    protected $object_id = 0;

    /**
     * Holds podcast title.
     *
     * @since  1.0.0
     * @access private
     * @var    string
     */
    protected $title = '';

    /**
     * Holds podcast unique ID from feed.
     *
     * @since  1.0.0
     * @access private
     * @var    string
     */
    protected $unique_id = '';

    /**
     * Holds podcast feed URLs.
     *
     * @since  1.0.0
     * @access private
     * @var    array
     */
    protected $feed_url = array();

    /**
     * Get escape functions.
     *
     * @since 1.0.0
     */
    protected function typeDeclaration() {
        // Data type declaration for safe and proper data output.
        return array(
            'object_id' => 'int',
            'title'     => 'title',
            'feed_url'  => 'arrUrl',
            'unique_id' => 'string',
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

    /**
     * Query to get specific object.
     *
     * @since 1.0.0
     *
     * @param string $name   Property Name
     * @param string $needle Check if string contained by prop value.
     */
    public function query($name, $needle) {
        if ($name && $needle && property_exists($this, $name)) {
            if (is_array($this->$name)) {
                $haystack = join('', $this->$name);
            } else {
                $haystack = (string) $this->$name;
            }

            if (false !== strpos((string) $haystack, (string) $needle)) {
                return $this;
            }
        }
        return false;
    }

    /**
     * Podcast Lookup.
     *
     * @since 1.0.0
     *
     * @param string $needle Check if string contained by prop value.
     */
    public function lookup($needle) {
        if ($this->query('feed_url', $needle)) {
            return $this;
        } elseif ($this->query('object_id', $needle)) {
            return $this;
        } elseif ($this->query('unique_id', $needle)) {
            return $this;
        }
        return false;
    }
}
