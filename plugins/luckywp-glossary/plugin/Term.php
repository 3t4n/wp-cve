<?php

namespace luckywp\glossary\plugin;

use Exception;
use luckywp\glossary\core\base\BaseObject;
use WP_Post;

/**
 * @property int $id
 * @property string $name
 * @property string $permalink
 * @property string $content
 * @property string[] $synonyms
 */
class Term extends BaseObject
{

    const POST_TYPE = 'lwpgls_term';

    /**
     * @var WP_Post
     */
    protected $post;

    public function __construct($post)
    {
        parent::__construct();
        if (is_int($post)) {
            $post = get_post($post);
        }
        if (!($post instanceof WP_Post) || $post->post_type != self::POST_TYPE) {
            throw new Exception('Invalid Post');
        }
        $this->post = $post;
    }

    public function getId()
    {
        return $this->post->ID;
    }

    public function getName()
    {
        return $this->post->post_title;
    }

    private $_permalink;

    public function getPermalink()
    {
        if ($this->_permalink === null) {
            $this->_permalink = get_permalink($this->post);
        }
        return $this->_permalink;
    }

    public function getContent()
    {
        return apply_filters('the_content', $this->post->post_content);
    }

    private $_synonyms;

    /**
     * @return string[]
     */
    public function getSynonyms()
    {
        if ($this->_synonyms === null) {
            $this->_synonyms = static::synonymsStringToArray(get_post_meta($this->getId(), '_lwpgls_synonyms', true));
        }
        return $this->_synonyms;
    }

    /**
     * @param string[] $synonyms
     */
    public function setSynonyms($synonyms)
    {
        update_post_meta($this->getId(), '_lwpgls_synonyms', implode(',', $synonyms));
        $this->_synonyms = null;
    }

    /**
     * @param string $str
     * @return array
     */
    public static function synonymsStringToArray($str)
    {
        $synonyms = explode(',', (string)$str);
        $synonyms = array_map('trim', $synonyms);
        $synonyms = array_unique($synonyms);
        $synonyms = array_filter($synonyms, function ($synonym) {
            return $synonym != '';
        });
        return $synonyms;
    }
}
