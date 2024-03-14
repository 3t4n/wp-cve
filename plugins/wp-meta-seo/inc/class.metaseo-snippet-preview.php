<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Class WPMSEOSnippetPreview
 * Generates a Google Search snippet preview.
 * Takes a $post, $title and $description
 */
class WPMSEOSnippetPreview
{

    /**
     * Snippet content
     *
     * @var string
     */
    protected $content;
    /**
     * Options
     *
     * @var array
     */
    protected $options;
    /**
     * Current post
     *
     * @var object
     */
    protected $post;
    /**
     * Snippet title
     *
     * @var string
     */
    protected $title;
    /**
     * Snippet description
     *
     * @var string
     */
    protected $description;
    /**
     * Snippet date
     *
     * @var string
     */
    protected $date = '';
    /**
     * Snippet URL
     *
     * @var string
     */
    protected $url;
    /**
     * Snippet slug
     *
     * @var string
     */
    protected $slug = '';

    /**
     * WPMSEOSnippetPreview constructor.
     *
     * @param object $post        Current post
     * @param string $title       Title
     * @param string $description Description
     *
     * @return void
     */
    public function __construct($post, $title, $description)
    {
        $this->post        = $post;
        $this->title       = esc_html($title);
        $this->description = esc_html($description);

        $this->setDate();
        $this->setUrl();
        $this->setContent();
    }

    /**
     * Getter for $this->content
     *
     * @return string html for snippet preview
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets date if available
     *
     * @return void
     */
    protected function setDate()
    {
        if (is_object($this->post)) {
            $date       = $this->getPostDate();
            $this->date = '<span class="date">' . esc_html($date) . ' — </span>';
        }
    }

    /**
     * Retrieves a post date when post is published, or return current date when it's not.
     *
     * @return string
     */
    protected function getPostDate()
    {
        if (isset($this->post->post_date) && $this->post->post_status === 'publish') {
            $date = date_i18n('j M Y', strtotime($this->post->post_date));
        } else {
            $date = date_i18n('j M Y');
        }

        return (string) $date;
    }

    /**
     * Generates the url that is displayed in the snippet preview.
     *
     * @return void
     */
    protected function setUrl()
    {
        $this->url = str_replace(array('http://', 'https://'), '', get_bloginfo('url')) . '/';
        $this->setSlug();
    }

    /**
     * Sets the slug and adds it to the url if the post has been published and the post name exists.
     *
     * If the post is set to be the homepage the slug is also not included.
     *
     * @return void
     */
    protected function setSlug()
    {
        $frontpage_post_id   = (int) (get_option('page_on_front'));
        $permalink_structure = get_option('permalink_structure');
        if (is_object($this->post) && isset($this->post->post_name) && $this->post->post_name !== ''
            && (int) $this->post->ID !== (int) $frontpage_post_id) {
            $this->slug = sanitize_title($this->title);
            if (!empty($permalink_structure)) {
                $this->url .= esc_html($this->slug);
            }
        }

        if (isset($this->post->post_status) && ($this->post->post_status !== 'auto-draft' && $this->post->post_status !== 'draft')) {
            if (!empty($this->post->ID)) {
                $this->url = get_permalink((int)$this->post->ID);
            }
        }
    }

    /**
     * Generates the html for the snippet preview and assign it to $this->content.
     *
     * @return void
     */
    protected function setContent()
    {
//        $settings = get_option('_metaseo_settings');
//        if (!$settings || !isset($settings['metaseo_metatitle_tab'])) {
//            $settings['metaseo_metatitle_tab'] = 1;
//        }
        //$title_snippet = (!empty($settings['metaseo_metatitle_tab']) ? '%title%' : '');
        $url = esc_url($this->url);
        $url = str_replace('http://', '', $url);
        // convert %title% snippet back to default page title
        $firstCreatePost = 0;
        if (empty($this->post->post_title)) {
            $firstCreatePost = 1;
        }
        // phpcs:disable WordPress.Security.EscapeOutput -- Content escaped before line 167
        $content = '<div id="wpmseosnippet">
<a class="url m-t-10" style="width: 100%; padding-left: 12px; margin-bottom:5px">' . $url . '</a>
<div class="metabox-snippet-title">
<div class="container-snippet">
  <span class="text">'.esc_html($this->title).'</span>
  <input type="hidden" data-firstcreatepost="'. esc_attr($firstCreatePost) .'" class="title input has-length metaseo_tool" id="metaseo_wpmseo_title" name="metaseo_wpmseo_title" value="' . esc_html($this->title). '" />
  <input class="title input has-length metaseo_tool" placeholder="'.esc_html__('Put your meta title here. Click here to edit...', 'wp-meta-seo').'" data-tippy="'.esc_html__('This is your meta title that should be displayed in Google Search results for this page', 'wp-meta-seo').'" id="metaseo_snippet_title" value="" />
</div>
<span id="metaseo_wpmseo_title-length" class="length-box-meta"></span>
</div>

<div class="metabox-snippet-description">
<textarea class="desc has-length metaseo_wpmseo_snippet_desc metaseo_tool" placeholder="'.esc_html__('Put your meta description here. Click here to edit...', 'wp-meta-seo').'" data-tippy="'.esc_html__('This is your meta description that should be displayed in Google Search results for this page', 'wp-meta-seo').'" id="metaseo_wpmseo_desc" name="metaseo_wpmseo_desc" style="resize: none;">' . esc_html($this->description) . '</textarea>
<span id="metaseo_wpmseo_desc-length" class="length-box-meta"></span>
</div>
</div>';
        //phpcs:enable
        $this->setContentThroughFilter($content);
    }

    /**
     * Sets the html for the snippet preview through a filter
     *
     * @param string $content Content string.
     *
     * @return void
     */
    protected function setContentThroughFilter($content)
    {
        $properties         = get_object_vars($this);
        $properties['desc'] = $properties['description'];
        $this->content      = apply_filters('wpmseo_snippet', $content, $this->post, $properties);
    }
}
