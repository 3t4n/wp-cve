<?php

/**
 * Easy related posts .
 *
 * @package   Easy_Related_Posts_Core_display
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * Post data class of plugin templates
 *
 * @package Easy_Related_Posts_Core_display
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpPostData {

    /**
     * WP_Post var
     *
     * @since 2.0.0
     * @var WP_Post
     */
    private $post;

    /**
     * Post id
     *
     * @since 2.0.0
     * @var int
     */
    private $ID;

    /**
     * Post title
     *
     * @since 2.0.0
     * @var string
     */
    private $title;

    /**
     * Post excerpt
     *
     * @since 2.0.0
     * @var string
     */
    private $excerpt;

    /**
     * Rating
     *
     * @since 2.0.0
     * @var float
     */
    private $rating;

    /**
     * Post thumbnail url
     *
     * @since 2.0.0
     * @var string
     */
    private $thumbnail;

    /**
     * Post permalink
     *
     * @since 2.0.0
     * @var string
     */
    private $permalink;

    /**
     * Post date
     *
     * @since 2.0.0
     * @var string
     */
    private $postDate;

    /**
     * Compontent positions
     *
     * @since 2.0.0
     * @var array
     */
    private $positions = array();

    /**
     *
     * @var erpOptions 
     */
    private $options;

    /**
     *
     * @param WP_Post $post
     * @param array $options
     * @param float $rating
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function __construct(WP_Post $post, erpOptions $options, $rating) {
        $this->options = $options;
        $this->post = $post;
        $this->ID = $post->ID;
        $this->setTitle();
        $this->rating = $rating;
        $this->setPermalink();
        $this->setPostDate('Y-m-d H:i:s');
        $this->setPositions($options);
    }

    /**
     * Get post title sourounded by a span tag and styled based on 
     * options
     *
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getTitle() {
        return $this->title;
    }
    
    /**
     * Get post title as provided from post obj
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.2
     */
    public function getPostTitle() {
        return $this->post->post_title;
    }
    
    /**
     * Get post title as provided from post obj with escaped 
     * html chars
     * 
     * @param bool $raw True to get raw title from post obj, false 
     * to get post title sourounded by a span tag and styled based on 
     * options. Default false (formated).
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.2
     */
    public function getPostTitleEscaped($raw = false) {
        return $raw ? esc_attr($this->post->post_title) : esc_attr($this->getTitle());
    }
    
    /**
     * Get post author as provided from post obj
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.2
     */
    public function getPostAuthor($param){
        return $this->post->post_author;
    }
    
    /**
     * Get number of comments provided from post obj
     * @return int
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.2
     */
    public function getPostCommentCount(){
        return $this->post->comment_count;
    }

    /**
     * Get post time
     *
     * @param string $timeFormat
     * @return string Formated time
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getTheTime($timeFormat = 'Y-m-d H:i:s') {
        return date($timeFormat, strtotime($this->postDate));
    }

    /**
     * Set post date
     *
     * @param string $postFormat
     * @return \display\erpPostData
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    private function setPostDate($postFormat = 'Y-m-d H:i:s') {
        $this->postDate = get_the_time($postFormat, $this->ID);
        return $this;
    }

    /**
     * Set title
     *
     * @return \display\erpPostData
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    private function setTitle() {
        $size = $this->options->getPostTitleFontSize();
        $color = $this->options->getPostTitleColor();

        $fontColor = $color !== '#ffffff' ? ' color: ' . $color . '; ' : '';
        $fontSize = $size !== 0 ? ' font-size: ' . $size . 'px; ' : '';
        $openTag = '<span style="' . $fontColor . $fontSize . '">';
        $closeTag = '</span>';

        $this->title = $openTag . $this->post->post_title . $closeTag;
        return $this;
    }

    /**
     * Get post except
     *
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getExcerpt() {
        if (!isset($this->excerpt)) {
            $this->setExcerpt(erpDefaults::$comOpts ['excLength'], erpDefaults::$comOpts ['moreTxt']);
        }
        return $this->excerpt;
    }

    /**
     * Set post excerpt
     *
     * @param int $excLength
     *        	Excerpt length in words
     * @param string $moreText
     *        	More text to be displayed after post excerpt
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setExcerpt($excLength, $moreText) {
        if (!empty($this->post->post_excerpt)) {
            $exc = $this->post->post_excerpt;
        } else {
            $exc = $this->post->post_content;
        }

        $exc = strip_shortcodes($exc);
        $exc = str_replace(']]>', ']]&gt;', $exc);
        $exc = wp_strip_all_tags($exc);


        $size = $this->options->getExcFontSize();
        $color = $this->options->getExcColor();

        $fontColor = $color !== '#ffffff' ? ' color: ' . $color . '; ' : '';
        $fontSize = $size !== 0 ? ' font-size: ' . $size . 'px; ' : '';
        $openTag = '<span style="' . $fontColor . $fontSize . '">';
        $closeTag = '</span>';

        $tokens = explode(' ', $exc, $excLength + 1);

        if (count($tokens) > $excLength) {
            array_pop($tokens);
        }

        array_push($tokens, ' ' . $moreText);
        $exc = implode(' ', $tokens);
        $this->excerpt = $openTag . $exc . $closeTag;
    }

    /**
     * Get post rating
     *
     * @return float
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getRating() {
        return $this->rating;
    }

    /**
     * Set post rating
     *
     * @param float $rating
     * @return \display\erpPostData
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    private function setRating($rating) {
        $this->rating = $rating;
        return $this;
    }

    /**
     * Get post proccesed thumbnail
     *
     * @param int $height
     *        	Thumbnail height
     * @param int $width
     *        	Thumbnail width
     * @param boolean $crop
     *        	Crop thumbnail
     * @return string URL path to generated thumb
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getThumbnail($height, $width, $crop) {
        if (!isset($this->thumbnail)) {
            $this->setThumbnail($this->options->getDefaultThumbnail());
        }

        if (($height > 0 || $width > 0) && $crop) {
            $image = $this->resize($this->thumbnail, (int) $width, (int) $height, (bool) $crop);
            if (!is_wp_error($image) && !empty($image)) {
                return $image;
            }
        }

        return $this->thumbnail;
    }

    /**
     * Uses bfi resizer to resize image and returns url to new image
     * @param string $url
     * @param int $width
     * @param int $height
     * @param bool $crop
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    private function resize($url, $width = NULL, $height = NULL, $crop = true) {
        erpPaths::requireOnce(erpPaths::$bfiResizer);
        return bfi_thumb($url, array('width' => $width, 'height' => $height, 'crop' => $crop));
    }

    /**
     * If the post have thumbnail
     *
     * @return boolean
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function hasThumbnail() {
        return has_post_thumbnail($this->ID);
    }

    /**
     * Sets post thumbnail URL
     *
     * @param string $defaultThumbnail
     *        	URL to default thumbnail
     * @param string $size
     *        	Optional, default is 'single-post-thumbnail'
     * @return erpPostData
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setThumbnail($defaultThumbnail, $size = 'single-post-thumbnail') {        
        if (has_post_thumbnail($this->ID)) {
        	$image_url = wp_get_attachment_image_src(get_post_thumbnail_id($this->ID), $size);
        	$image_url = $image_url[0];
        } else {
        	$image_url = '';
        	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $this->post->post_content, $matches);
        	
        	if(empty($matches [1] [0])){
        		$image_url = $defaultThumbnail;
        	} else {
        		$image_url = $matches [1] [0];
        	}
        }
        $this->thumbnail = $image_url;
        return $this;
    }

    /**
     * Get permalink
     *
     * @return string Permalink URL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getPermalink() {
        return $this->permalink;
    }

    /**
     * Sets permalink.
     * If rating system is in use modifies permalink to include from directive
     *
     * @param int $from
     *        	Host post id
     * @return \display\erpPostData
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    private function setPermalink() {
        $this->permalink = get_permalink($this->ID);
        return $this;
    }

    /**
     * Set positions based on options (content)
     *
     * @param array $options
     *        	Assoc array
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    private function setPositions($options) {
        if (!$this->options->getContentPositioning() || !is_array($this->options->getContentPositioning())) {
            $this->positions [0] = &$this->thumbnail;
            $this->positions [1] = &$this->title;
            $this->positions [2] = &$this->excerpt;
        } else {
            foreach ($this->options->getContentPositioning() as $k => $v) {
                if ($v == 'title') {
                    $this->positions [$k] = &$this->title;
                } elseif ($v == 'thumbnail') {
                    $this->positions [$k] = &$this->thumbnail;
                } elseif ($v == 'excerpt') {
                    $this->positions [$k] = &$this->excerpt;
                }
            }
        }
    }

    /**
     * Get content that should be displayed at given position
     *
     * @param int $position
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getContentAtPosition($position) {
        return $position [$position - 1];
    }

    public function getTheId() {
        return $this->ID;
    }

    /**
     */
    function __destruct() {
        
    }

}
