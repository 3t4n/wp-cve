<?php
/**
 * Fetch Feed Data from Feed XML file.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Feed;

use Podcast_Player\Helper\Store\FeedData;
use Podcast_Player\Helper\Store\ItemData;
use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Validation as Validation_Fn;

/**
 * Fetch Feed Data from Feed XML file.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Fetch_Feed_New {

    /**
     * Holds podcast feed URL.
     *
     * @since  6.5.0
     * @access public
     * @var    string
     */
    public $url = '';

    /**
	 * Holds feed raw data.
	 *
	 * @since  6.5.0
	 * @access private
	 * @var    string
	 */
	private $feed;

    /**
     * Atom Feed Namespace.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    private $atom;

    /**
     * iTunes Feed Namespace.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    private $itunes;

    /**
	 * Holds instance of current podcast item.
	 *
	 * @since  6.4.3
	 * @access private
	 * @var    object
	 */
	private $item = '';

	/**
     * Holds iTunes namespace for current podcast item
     *
     * @since  1.0.0
     * @access public
     * @var    mixed
     */
    private $itemItunes;

    /**
     * Holds atom namespace for current podcast item
     *
     * @since  1.0.0
     * @access public
     * @var    mixed
     */
    private $itemAtom;

    /**
	 * Holds ID of current podcast item.
	 *
	 * @since  6.5.0
	 * @access private
	 * @var    string
	 */
	private $id;

    /**
     * Holds fetch process response.
     *
     * @since  1.0.0
     * @access public
     * @var    mixed
     */
    public $response = null;

    /**
     * Create initial state of the object.
     *
     * @since 6.5.0
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->get_fetched_object();
    }

    /**
     * Get Podcast Feed Data.
     *
     * @since 6.5.0
     */
    public function get_fetched_object()
    {
        // Make HTTP request to the feed URL.
        $content = wp_safe_remote_request(
            $this->url,
            array('timeout' => 10)
        );

        // Get xml feed content from the HTTP response.
        $content = wp_remote_retrieve_body( $content );

        // Get valid feed xml content.
        if ( $content ) {
            $content = str_replace(
                'http://www.itunes.com/DTDs/Podcast-1.0.dtd',
                'http://www.itunes.com/dtds/podcast-1.0.dtd',
                $content
            );
            $content = $this->get_valid_xml( $content );
        }

        // Create error object if feed data not available.
        if (! $content || ! isset( $content->channel ) || ! $content->channel ) {
            $this->response = new \WP_Error(
                'no-feed-data',
                __( 'Feed Data Not Available', 'podcast-player' )
            );
            return;
        }

        // Get porper xml data or WP Error object.
        if (is_wp_error($content)) {
            $this->response = $content;
            return;
        }
        
        $this->feed = $content->channel;
        $this->itunes = $this->feed->children("http://www.itunes.com/dtds/podcast-1.0.dtd");
        $this->atom = $this->feed->children("http://www.w3.org/2005/Atom");
    }

    /**
     * Validate XML.
     *
     * Check if a given string is a valid XML.
     *
     * @since 6.5.0
     *
     * @param string $xmlstr Podcast feed XML.
     */
    private function get_valid_xml($xmlstr)
    {
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($xmlstr, 'SimpleXMLIterator');

        if (false === $doc) {
            $response = new \WP_Error();
            $errors = libxml_get_errors();

            foreach ($errors as $error) {
                $response->add($this->get_xml_errorcode($error), trim($error->message));
            }
        
            libxml_clear_errors();
            return $response;
        }
        return $doc;
    }

    /**
     * Get properly formatted xml error code.
     *
     * @since 6.5.0
     *
     * @param object $error LibXMLError object.
     */
    private function get_xml_errorcode($error)
    {
        $return  = '';
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code";
                break;
        }
        return $return;
    }

    /**
     * Prepare feed data object.
     *
     * @since 6.5.0
     */
    public function get_feed_data() {
        if ( is_wp_error( $this->response ) ) {
            return $this->response;
        }

        $items = $this->get_feed_items();
        if ( empty( $items ) ) {
            return new \WP_Error(
				'no-items-error',
				esc_html__( 'No feed items available.', 'podcast-player' )
			);
        }

        $feed = new FeedData();
        $feed->set( 'title', $this->get_feed_title() );
        $feed->set( 'desc', $this->get_feed_description() );
        $feed->set( 'link', $this->get_feed_link() );
        $feed->set( 'image', $this->get_feed_cover() );
        $feed->set( 'furl', $this->url );
        $feed->set( 'fkey', md5( $this->url ) );
        $feed->set( 'copyright', $this->get_feed_copyright() );
        $feed->set( 'author', $this->get_feed_author() );
        $feed->set( 'podcats', $this->get_feed_category() );
        $feed->set( 'lastbuild', $this->get_feed_lastbuild() );
        $feed->set( 'owner', $this->get_feed_owner() );
        $feed->set( 'items', $items );
        $feed->set( 'seasons', $this->get_feed_seasons( $items ) );
        $feed->set( 'categories', $this->get_items_categories( $items ) );
        $feed->set( 'total', $this->get_total_items( $items ) );
        
        return $feed;
    }

    /**
	 * Fetch items level data.
	 *
	 * @since  6.4.3
	 */
	public function get_feed_items() {
		$nitems = array();
		$items  = $this->feed->item;

		// Check items are available and are iterable.
		if ( ! $items || ! ( is_array( $items ) || ( is_object( $items ) && method_exists( $items, 'rewind' ) ) ) ) {
			return array();
		}

		foreach ( $items as $item ) {
			$this->item = $item;
			$this->itemItunes = $item->children("http://www.itunes.com/dtds/podcast-1.0.dtd");
            $this->itemAtom = $item->children("http://www.w3.org/2005/Atom");
			$data = $this->get_item_data();
			if ( $data ) {
				$nitems[ $this->id ] = $data;
			}
		}
		return $nitems;
	}

    /**
	 * Fetch single item data.
	 *
	 * @since  6.4.3
	 */
	public function get_item_data() {
		list( $media, $media_type ) = $this->get_item_media();
		if ( ! $media || ! $media_type ) {
			return false;
		}

		$this->id = $this->get_item_id( $media );
        $item = new ItemData();
        $item->set( 'title', $this->get_item_title() );
        $item->set( 'description', $this->get_item_description() );
        $item->set( 'author', $this->get_item_author() );
        $item->set( 'date', $this->get_item_date() );
        $item->set( 'link', $this->get_item_link($media) );
        $item->set( 'src', $media );
        $item->set( 'featured', $this->get_item_featured() );
        $item->set( 'mediatype', $media_type );
        $item->set( 'episode', $this->get_itunes_episode() );
        $item->set( 'season', $this->get_itunes_season() );
        $item->set( 'categories', $this->get_item_cats() );
        $item->set( 'episode_id', $this->get_episode_id($media) );
        $item->set( 'duration', $this->get_item_duration() );
        return $item;
	}

    /**
	 * Get media src from the item.
	 *
	 * @since  6.4.3
	 */
	public function get_item_media() {
		$enclosure  = $this->get_media_enclosure();
		$media = false !== $enclosure ? (string) $enclosure->attributes()->url : '';
		$media_type = $media ? Get_Fn::get_media_type( $media ) : '';
		return array( esc_url_raw( $media ), sanitize_text_field( $media_type ) );
	}

	/**
	 * Get media src from the item.
	 *
	 * @since  6.4.3
	 */
	public function get_media_enclosure() {
		// Look for media in the media group.
        $media = $this->item->children('http://search.yahoo.com/mrss/');
		$group = isset($media->group) ? $media->group : false;
		if ($group && ( is_array($group) || ( is_object($group) && method_exists($group, 'rewind') ) ) ) {
			foreach($group as $g) {
				$contents = isset($g->children('http://search.yahoo.com/mrss/')->content) ? $g->children('http://search.yahoo.com/mrss/')->content : false;
				if ($contents && ( is_array($contents) || ( is_object($contents) && method_exists($contents, 'rewind') ) ) ) {
					foreach($contents as $enclosure) {
						if ( method_exists( $enclosure, 'attributes' ) ) {
							$type = (string) $enclosure->attributes()->type;
							if (false !== strpos($type, 'audio') || false !== strpos( $type, 'video' )) {
								return $enclosure;
							}
						}
					}
				}
			}
		}

		// Look for media in direct media content.
		$contents = isset($media->content) ? $media->content : false;
		if ($contents && ( is_array($contents) || ( is_object($contents) && method_exists($contents, 'rewind') ) ) ) {
			foreach($contents as $enclosure) {
				if ( method_exists( $enclosure, 'attributes' ) ) {
					$type = (string) $enclosure->attributes()->type;
					if (false !== strpos($type, 'audio') || false !== strpos( $type, 'video' )) {
						return $enclosure;
					}
				}
			}
		}

		// Finally look for media in the enclosures.
		$enc = isset($this->item->enclosure) ? $this->item->enclosure : false;
		if (! $enc || ! ( is_array($enc) || ( is_object($enc) && method_exists($enc, 'rewind') ) ) ) {
			return false;
		}
		foreach($enc as $enclosure) {
			if ( method_exists( $enclosure, 'attributes' ) ) {
				$type = (string) $enclosure->attributes()->type;
				if (false !== strpos($type, 'audio') || false !== strpos( $type, 'video' )) {
					return $enclosure;
				}
			}
		}

        // Finally, look for media in all enclosures.
        foreach($enc as $enclosure) {
			if ( method_exists( $enclosure, 'attributes' ) ) {
				$url = (string) $enclosure->attributes()->url;
                if ( $url && false !== Get_Fn::get_media_type( $url ) ) {
                    return $enclosure;
                }
			}
		}
		return false;
	}

    /**
	 * Generate current item's unique ID.
	 *
	 * @since  6.4.3
	 *
	 * @param string $media Media for current item.
	 */
	public function get_item_id( $media ) {
		return md5( $media );
	}

    /**
     * Get item title.
     *
     * @since 1.0.0
     */
    private function get_item_title() {
        return trim( (string) $this->item->title );
    }

	/**
     * Get item description.
     *
     * @since 1.0.0
     */
    private function get_item_description() {
        $content = isset( $itemAtom->content ) && trim( (string) $itemAtom->content ) ? trim( (string) $itemAtom->content ) : '';

        if ( ! $content ) {
            $namespace = $this->item->children( "http://purl.org/rss/1.0/modules/content/" );
            $content = isset( $namespace->encoded ) && trim( (string) $namespace->encoded ) ? trim( (string) $namespace->encoded ) : '';
        }

        if ( ! $content ) {
			$content = $this->item->description && trim( (string) $this->item->description ) ? trim( (string) $this->item->description ) : '';
		}

        if ( ! $content ) {
			$content = isset( $this->itemAtom->summary ) && trim( (string) $this->itemAtom->summary ) ? trim( (string) $this->itemAtom->summary ) : '';
		}

        if ( ! $content ) {
			$content = isset( $this->itemItunes->summary ) && trim( (string) $this->itemItunes->summary ) ? trim( (string) $this->itemItunes->summary ) : '';
		}

        if ( ! $content ) {
			$content = isset( $this->itemItunes->subtitle ) && trim( (string) $this->itemItunes->subtitle ) ? trim( (string) $this->itemItunes->subtitle ) : '';
		}

		if ( $content ) {
			if ( 'yes' === Get_Fn::get_plugin_option( 'rel_external' ) ) {
				$link_mod = Add_External_Link_Attr::get_instance();
				$content  = $link_mod->init( $content );
			}
			return $content;
		} else {
			return '';
		}
    }

	/**
     * Get Item Author.
     *
     * @since 1.0.0
     */
    private function get_item_author() {
        $authors = $this->get_item_authors();
		if ( ! empty( $authors )) {
			return $authors[0];
		}
		return '';
    }

	/**
     * Get item Author.
     *
     * @since 1.0.0
     */
    private function get_item_authors() {
		$authors = array();
		$auths = isset( $this->itemItunes->author ) ? $this->itemItunes->author : false;
		if ( $auths && ( is_array($auths) || ( is_object($auths) && method_exists($auths, 'rewind') ) ) ) {
			foreach ($auths as $author) {
				$authors[] = trim( (string) $author );
			}
		}

		$auths = isset( $this->itemAtom->author ) ? $this->itemAtom->author : false;
		if ( $auths && ( is_array($auths) || ( is_object($auths) && method_exists($auths, 'rewind') ) ) ) {
			foreach ($auths as $author) {
				$authors[] = trim( (string) $author->name );
			}
		}

        $authors = array_unique( array_filter( $authors ) );
        if (empty( $authors )) {
            $feed_authors = $this->get_feed_authors();
            foreach ( $feed_authors as $author ) {
                $authors[] = trim( (string) $author );
            }
        }
		return $authors;
    }

	/**
     * Get item publish date.
     *
     * @since 1.0.0
     */
    private function get_item_date() {
		$date = $this->item->pubDate ? $this->item->pubDate : '';
        if ( ! $date && isset( $this->itemAtom->published ) ) {
            $date = $this->itemAtom->published;
        }

        if ( ! $date && $this->itemAtom->updated ) {
            $date = $this->itemAtom->updated;
        }

        return $date ? (string) $date : '';
    }

	/**
     * Get item link.
     *
     * @since 1.0.0
	 *
	 * @param string $media Episode media url.
     */
    private function get_item_link( $media ) {
        $link = isset( $this->itemAtom->link ) ? $this->itemAtom->link : false;
        if ( $link ) {
            if ( method_exists( $link, 'attributes' ) ) {
                $link = trim( (string) $link->attributes()->href );
            } else {
                $link = false;
            }
        }

        if ( ! $link ) {
            $link = $this->item->link && (string) $this->item->link ? trim( (string) $this->item->link ) : '';
        }

        if ( ! $link ) {
            $guid = $this->item->guid ? $this->item->guid : false;
            if ( $guid ) {
                $isPerma = '';
                if ( method_exists( $guid, 'attributes' ) ) {
                    $isPerma = (string) $guid->attributes()->isPermaLink;
                }

                if ( 'false' !== $isPerma ) {
                    $u = trim( (string) $guid );
                    if ( Validation_Fn::is_valid_url( $u ) ) {
                        $link = $u;
                    }
                }
            }
        }

        if ( ! $link ) {
            $link = $media;
        }
        
        return $link;
    }

	/**
     * Get item featured image.
     *
     * @since 1.0.0
     */
    private function get_item_featured() {
        $image = isset( $this->itemItunes->image ) ? $this->itemItunes->image : false;
        if ( $image ) {
            $image = (string) $image->attributes()->href;
        }
        if ( !$image ) {
			$enc = isset( $this->item->enclosure ) ? $this->item->enclosure : false;
			if ( $enc && ( is_array( $enc ) || ( is_object( $enc ) && method_exists( $enc, 'rewind' ) ) ) ) {
				foreach ( $enc as $enclosure ) {
                    $type = (string) $enclosure->type;
                    if ( false !== strpos( $type, 'image' ) ) {
                        $image = (string) $enclosure->url;
                        break;
                    }
                }
			}
        }

        if ( ! $image ) {
            $media = $this->item->children('http://search.yahoo.com/mrss/');
            $contents = isset($media->content) ? $media->content : false;
            if ( $contents ) {
                if ( is_array( $contents ) || ( is_object($contents) && method_exists($contents, 'rewind') ) ) {
                    foreach($contents as $enclosure) {
                        if ( method_exists( $enclosure, 'attributes' ) ) {
                            $type = (string) $enclosure->attributes()->medium;
                            if (false !== strpos($type, 'image')) {
                                $image = (string) $enclosure->attributes()->url;
                                break;
                            }
                        }
                    }
                } else {
                    if ( method_exists( $contents, 'attributes' ) ) {
                        $type = (string) $contents->attributes()->medium;
                        if (false !== strpos($type, 'image')) {
                            $image = (string) $contents->attributes()->url;
                        }
                    }
                }
            }
        }

		if ( $image && Validation_Fn::is_valid_image_url( $image ) ) {
			return $image;
		} else {
			return '';
		}
    }

	/**
     * Get item iTunes episode number.
     *
     * @since 1.0.0
     */
    private function get_itunes_episode() {
		$episode = isset( $this->itemItunes->episode ) ? (string) $this->itemItunes->episode : false;
		$season = $this->get_itunes_season();
		if ( $episode ) {
			$episode = $season ? $season . '-' . $episode : $episode;
			return $episode;
		}
		return '';
    }

	/**
     * Get item iTunes episode season.
     *
     * @since 1.0.0
     */
    private function get_itunes_season() {
        return isset( $this->itemItunes->season ) && $this->itemItunes->season ? (string) $this->itemItunes->season : '';
    }

	/**
     * Get item iTunes episode categories.
     *
     * @since 1.0.0
     */
    private function get_item_cats() {
        $categories = array();
		$cats = isset( $this->item->category ) ? $this->item->category : false;
		if ( $cats && ( is_array($cats) || ( is_object($cats) && method_exists($cats, 'rewind') ) ) ) {
			foreach ($cats as $category) {
				$term = (string) $category;
				$term = sanitize_text_field($term);
				$key  = strtolower(str_replace(' ', '', $term));
				if ($key) {
					$categories[$key] = $term;
				}
			}
		}

		$cats = isset( $this->itemAtom->category ) ? $this->itemAtom->category : false;
		if (! $cats || ! ( is_array($cats) || ( is_object($cats) && method_exists($cats, 'rewind') ) ) ) {
			return $categories;
		}
		foreach ($cats as $category) {
			$term = (string) $category->term;
			$term = sanitize_text_field($term);
			$key  = strtolower(str_replace(' ', '', $term));
			if ($key) {
				$categories[$key] = $term;
			}
		}

		return $categories;
    }

	/**
	 * Get Episode ID.
	 *
	 * @since  5.7.0
	 *
	 * @param string $media Media Src
	 */
	public function get_episode_id($media) {
		$id = isset( $this->itemAtom->id ) && $this->itemAtom->id ? $this->itemAtom->id : $this->item->guid;
		if ( ! $id ) {
			$link  = $this->get_item_link( $media );
			$title = $this->get_item_title();
			$desc  = $this->get_item_description();
			$id = md5($link . $title . $desc);
		}
		return (string) $id;
	}

	/**
	 * Get Episode duration.
	 *
	 * @since  5.7.0
	 */
	public function get_item_duration() {
		$d = isset( $this->itemItunes->duration ) ? (string) $this->itemItunes->duration : false;
        return ( ! $d || empty( $d ) ) ? false : $d;
	}

    /**
     * Get podcast title.
     *
     * @since 6.5.0
     */
    private function get_feed_title() {
        return (string) $this->feed->title;
    }

    /**
     * Get podcast description.
     *
     * @since 6.5.0
     */
    private function get_feed_description() {
		$desc = (string) $this->feed->description;
		if (! $desc && isset($this->itunes->summary)) {
			$desc = (string) $this->itunes->summary;
		}
        return $desc;
    }

    /**
     * Get podcast website link.
     *
     * @since 6.5.0
     */
    private function get_feed_link() {
        $links = isset( $this->feed->link ) ? $this->feed->link : false;
        if ( $links ) {
            if ( is_array($links) || ( is_object($links) && method_exists($links, 'rewind') ) ) {
                foreach ($links as $link) {
                    if ( (string) $link ) {
                        return trim( (string) $link );
                    }
                }
            } else {
                trim( (string) $links );
            }
        }
        return '';
    }

    /**
     * Get podcast cover.
     *
     * @since 6.5.0
     */
    private function get_feed_cover() {
        $cover = $this->itunes && isset( $this->itunes->image ) ? (string) $this->itunes->image->attributes()->href : '';
        if ( ! $cover ) {
            $cover = isset( $this->feed->image->url ) ? (string) $this->feed->image->url : '';
        }
        return trim( $cover );
    }

    /**
     * Get podcast copyright info.
     *
     * @since 1.0.0
     */
    private function get_feed_copyright() {
        return trim( (string) $this->feed->copyright );
    }

	/**
     * Get podcast Author.
     *
     * @since 1.0.0
     */
    private function get_feed_author() {
        $authors = $this->get_feed_authors();
		if (! empty($authors)) {
			return $authors[0];
		}
		return '';
    }

	/**
     * Get podcast Authors.
     *
     * @since 1.0.0
     */
    private function get_feed_authors() {
		$authors = array();
		$auths = isset( $this->itunes->author ) ? $this->itunes->author : false;
        if ( $auths ) {
            if ( is_array($auths) || ( is_object($auths) && method_exists($auths, 'rewind') ) ) {
                foreach ($auths as $author) {
                    $authors[] = (string) $author;
                }
            } else {
                $authors[] = (string) $auths;
            }
        }

		$auths = isset( $this->atom->author ) ? $this->atom->author : false;
        if ( $auths ) {
            if ( is_array($auths) || ( is_object($auths) && method_exists($auths, 'rewind') ) ) {
                foreach ($auths as $author) {
                    $authors[] = (string) $author;
                }
            } else {
                $authors[] = (string) $auths;
            }
        }

		return $authors;
    }

	/**
     * Get podcast Categories.
     *
     * @since 1.0.0
     */
    private function get_feed_category() {
        $categories = array();
		
		$podcats = isset( $this->itunes->category ) ? $this->itunes->category : false;
		// Check items are available and are iterable.
		if (! $podcats || ! ( is_array($podcats) || ( is_object($podcats) && method_exists($podcats, 'rewind') ) ) ) {
			return array();
		}
		foreach ( $podcats as $podcat ) {
			$label = (string) $podcat->attributes()->text;
			if ( ! $label ) {
				continue;
			}
			$label = sanitize_text_field( $label );
			$key = strtolower( str_replace( ' ', '', $label ) );
			if ( ! isset( $categories[ $key ] ) ) {
				$categories[ $key ] = array(
					'label'   => $label,
					'subcats' => array(),
				);
			}
			$subcats = $podcat->category;
			if (! $subcats || ! ( is_array($subcats) || ( is_object($subcats) && method_exists($subcats, 'rewind') ) ) ) {
				continue;
			}
			$sub = $categories[ $key ]['subcats'];
			foreach ($subcats as $subcat) {
				$sub[] = (string) $subcat->attributes()->text;
			}
			$sub = array_unique(array_filter($sub));
			$categories[$key]['subcats'] = $sub;
		}
        return $categories;
    }
	
	/**
     * Get podcast Owner.
     *
     * @since 1.0.0
     */
    private function get_feed_owner() {
        $owner = $this->itunes->owner;
        $name = '';
        $email = '';
        if ($owner) {
            $name_child = $owner->children("http://www.itunes.com/dtds/podcast-1.0.dtd")->name;
            if ($name_child) {
                $name = sanitize_text_field((string) $name_child);
            }
            $email_child = $owner->children("http://www.itunes.com/dtds/podcast-1.0.dtd")->email;
            if ($email_child) {
                $email = sanitize_email((string) $email_child);
            }
        }
        return array(
            'name' => $name,
            'email' => $email,
        );
    }

    /**
     * Get lastbuild date for the feed.
     *
     * @since 1.0.0
     */
    private function get_feed_lastbuild() {
		return 0;
    }

    /**
     * Get cumulative array of all seasons.
     *
     * @since 1.0.0
     *
     * @param array $items Array of podcast feed items object
     */
    private function get_feed_seasons( $items ) {
        $seasons = array();
        foreach ( $items as $item ) {
            if ( $item instanceof ItemData ) {
                $seasons[] = $item->get('season');
            }
        }
        return array_values( array_filter( array_unique( $seasons ) ) );
    }
    
    /**
     * Get cumulative array of all items categories.
     *
     * @since 1.0.0
     *
     * @param array $items Array of podcast feed items object
     */
    private function get_items_categories( $items ) {
        $cats = array();
        foreach ( $items as $item ) {
            if ( $item instanceof ItemData ) {
                $cats = array_merge( $cats, $item->get('categories') );
            }
        }
        return array_filter( array_unique( $cats ) );
    }
    
    /**
     * Get total items in the podcast feed.
     *
     * @since 1.0.0
     *
     * @param array $items Array of podcast feed items object
     */
    private function get_total_items( $items ) {
        return count( $items );
    }
}
