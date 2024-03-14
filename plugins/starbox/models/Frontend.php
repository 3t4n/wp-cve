<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

class ABH_Models_Frontend {

    public $author;
    public $details = null;
    public $category = null;
    public $position;
    public $single = true;

    /**
     * Get the html author box
     * @global object $wp_query
     * @return string
     */
    public function getAuthorBox() {
        global $wp_query;

        if (!isset($this->author))
            return;

        $content = '';

        if (isset($this->author) && isset($this->author->ID) ) {

            if(!$this->details) {
                $this->details = array(
                    'abh_use' => 1,
                    'abh_nofollow_social' => 1,
                    'abh_noopener_social' => 0,
                    'abh_noreferrer_social' => 0,
                    // --
                    'abh_title' => "",
                    'abh_company' => "",
                    'abh_company_url' => "",
                    'abh_extra_description' => "",
                    // --
                    'abh_socialtext' => "",
                    'abh_twitter' => "",
                    'abh_facebook' => "",
                    'abh_linkedin' => "",
                    'abh_instagram' => "",
                    'abh_flickr' => "",
                    'abh_pinterest' => "",
                    'abh_tumblr' => "",
                    'abh_youtube' => "",
                    'abh_vimeo' => "",
                    'abh_klout' => "",
                    'abh_gravatar' => "",
                    'abh_theme' => "default",
                    'abh_position' => "default",
                );
            }

            if (!isset($this->author->user_description))
                $this->author->user_description = '';


            if ($this->details['abh_theme'] == 'default')
                $this->details['abh_theme'] = ABH_Classes_Tools::getOption('abh_theme');

            $content .= '
                         <div class="abh_box abh_box_' . $this->position . ' abh_box_' . esc_attr($this->details['abh_theme']) . '">
                                <ul class="abh_tabs">
                                 <li class="abh_about abh_active"><a href="#abh_about">' . esc_html__('About', _ABH_PLUGIN_NAME_) . '</a></li>
                                 <li class="abh_posts"><a href="#abh_posts">' . esc_html__('Latest Posts', _ABH_PLUGIN_NAME_) . '</a></li>
                                </ul>
                                <div class="abh_tab_content">' .
                $this->showAuthorDescription() .
                $this->showAuthorPosts() . '
                                </div>
                         </div>';

            return $this->clearTags($content);
        }
        return '';
    }

    /**
     * Get the image for the author
     * @return string
     */
    public function getProfileImage() {
        if (isset($this->details['abh_gravatar']) && $this->details['abh_gravatar'] <> '' && file_exists(_ABH_GRAVATAR_DIR_ . $this->details['abh_gravatar'])) {
            $image =  '<img src="' . _ABH_GRAVATAR_URL_ . $this->details['abh_gravatar'] . '" class="photo" width="' . ABH_IMAGESIZE . '" alt="' . esc_attr($this->author->display_name) . '" />';
        } else {
            $image =  get_avatar($this->author->ID, ABH_IMAGESIZE, '', esc_attr($this->author->display_name));
        }

        return apply_filters('abh_author_profile_image', $image);

    }

    /**
     * Get the author Title and Description
     * @return string
     */
    private function showAuthorDescription() {

        $rel = array();
        $nofollow = '';
        if(isset($this->details['abh_nofollow_social']) && $this->details['abh_nofollow_social']){
            $rel[] = 'nofollow';
        }
        if(isset($this->details['abh_noopener_social']) && $this->details['abh_noopener_social']){
            $rel[] = 'noopener';
        }
        if(isset($this->details['abh_noreferrer_social']) && $this->details['abh_noreferrer_social']){
            $rel[] = 'noreferrer';
        }

        if(!empty($rel)){
            $nofollow = 'rel="'.join(' ', $rel).'"';
        }

        $content = '
                <section class="' . (($this->single) ? 'vcard author' : '') . ' abh_about_tab abh_tab" ' . (($this->single) ? 'itemscope itemprop="author" itemtype="http://schema.org/Person"' : '') . ' style="display:block">
                    <div class="abh_image" ' . (($this->single) ? 'itemscope itemtype="http://schema.org/ImageObject"' : '') . '>';

        if (ABH_AUTHORLINK) {
            if ($this->author->user_url) {
                $content .= '<a href = "' . esc_url($this->author->user_url) . '" class="url" target = "_blank" title = "' . esc_attr($this->author->display_name) . '" ' . $nofollow . ' > ' . $this->getProfileImage() . '</a > ';
            } else {
                $content .= '<a href = "' . get_author_posts_url($this->author->ID) . '" class="url" title = "' . esc_attr($this->author->display_name) . '" > ' . $this->getProfileImage() . '</a > ';
            }
        } else {
            $content .= $this->getProfileImage();
        }
        $content .= '</div>
                    <div class="abh_social"> ' . $this->getSocial($this->single) . '</div>
                    <div class="abh_text">
                        <div class="abh_name fn name" ' . (($this->single) ? 'itemprop="name"' : '') . ' ' . ((ABH_Classes_Tools::getOption('abh_titlefontsize') <> 'default') ? 'style="font-size:' . ABH_Classes_Tools::getOption('abh_titlefontsize') . ' !important;"' : '') . '>' . (ABH_AUTHORLINK ? (($this->author->user_url) ? '<a href="' . esc_url($this->author->user_url) . '" class="url" target="_blank" ' . $nofollow . '>' . esc_html($this->author->display_name) . '</a>' : '<a href="' . get_author_posts_url($this->author->ID) . '" class="url">' . esc_html($this->author->display_name) . '</a>') : esc_html($this->author->display_name)) . '</div>
                        <div class="abh_job" ' . ((ABH_Classes_Tools::getOption('abh_descfontsize') <> 'default') ? 'style="font-size:' . ABH_Classes_Tools::getOption('abh_descfontsize') . ' !important;"' : '') . '>' . (($this->details['abh_title'] <> '' && $this->details['abh_company'] <> '') ? '<span class="title" ' . ((ABH_Classes_Tools::getOption('abh_descfontsize') <> 'default') ? 'style="font-size:' . ABH_Classes_Tools::getOption('abh_descfontsize') . ' !important;"' : '') . '>' . esc_html($this->details['abh_title']) . '</span> ' . __('at', _ABH_PLUGIN_NAME_) . ' <span class="org" ' . ((ABH_Classes_Tools::getOption('abh_descfontsize') <> 'default') ? 'style="font-size:' . ABH_Classes_Tools::getOption('abh_descfontsize') . ' !important;"' : '') . '>' . (($this->details['abh_company_url'] <> '') ? sprintf('<a href="%s" target="_blank">%s</a>', esc_url($this->details['abh_company_url']), esc_attr($this->details['abh_company'])) : esc_attr($this->details['abh_company'])) . '</span>' : '') . '</div>
                        <div class="description note abh_description" ' . (($this->single) ? 'itemprop="description"' : '') . ' ' . ((ABH_Classes_Tools::getOption('abh_descfontsize') <> 'default') ? 'style="font-size:' . ABH_Classes_Tools::getOption('abh_descfontsize') . ' !important;"' : '') . '>' . ((isset($this->details['abh_extra_description']) && $this->details['abh_extra_description'] <> '') ? nl2br($this->details['abh_extra_description']) : nl2br($this->author->user_description)) . '</div>
                    </div>
               </section>';

        return apply_filters('abh_author_description_html', $content);

    }

    /**
     * Get the html author latest posts
     * @return string
     */
    private function showAuthorPosts() {
        $rel = array();
        $nofollow = '';
        if(isset($this->details['abh_nofollow_social']) && $this->details['abh_nofollow_social']){
            $rel[] = 'nofollow';
        }
        if(isset($this->details['abh_noopener_social']) && $this->details['abh_noopener_social']){
            $rel[] = 'noopener';
        }
        if(isset($this->details['abh_noreferrer_social']) && $this->details['abh_noreferrer_social']){
            $rel[] = 'noreferrer';
        }

        if(!empty($rel)){
            $nofollow = 'rel="'.join(' ', $rel).'"';
        }

        $content = '
                <section class="abh_posts_tab abh_tab" >
                    <div class="abh_image">
                      ' . (ABH_AUTHORLINK ? (($this->author->user_url) ? '<a href="' . esc_url($this->author->user_url) . '" class="url" target="_blank" title="' . esc_attr($this->author->display_name) . '" ' . $nofollow . '>' . $this->getProfileImage() . '</a>' : '<a href="' . get_author_posts_url($this->author->ID) . '" class="url" title="' . esc_attr($this->author->display_name) . '">' . $this->getProfileImage() . '</a>') : $this->getProfileImage()) . '
                    </div>
                    <div class="abh_social"> ' . $this->getSocial() . '</div>
                    <div class="abh_text">
                        <div class="abh_name" ' . ((ABH_Classes_Tools::getOption('abh_titlefontsize') <> 'default') ? 'style="font-size:' . ABH_Classes_Tools::getOption('abh_titlefontsize') . ' !important;"' : '') . '>' . sprintf(__('Latest posts by %s', _ABH_PLUGIN_NAME_), esc_attr($this->author->display_name)) . ' <span class="abh_allposts">' . (ABH_AUTHORLINK ? '(<a href="' . get_author_posts_url($this->author->ID) . '">' . __('see all', _ABH_PLUGIN_NAME_) . '</a>)' : '') . '</span></div>
                        <div class="abh_description note" >' . $this->getLatestPosts() . '</div>
                    </div>
               </section>';

        return apply_filters('abh_author_latest_posts_html', $content);

    }

    /**
     * Get the social icon for the author
     * @return string
     */
    private function getSocial($itemprop = false) {
        $rel = array();
        $nofollow = '';
        if(isset($this->details['abh_nofollow_social']) && $this->details['abh_nofollow_social']){
            $rel[] = 'nofollow';
        }
        if(isset($this->details['abh_noopener_social']) && $this->details['abh_noopener_social']){
            $rel[] = 'noopener';
        }
        if(isset($this->details['abh_noreferrer_social']) && $this->details['abh_noreferrer_social']){
            $rel[] = 'noreferrer';
        }

        if(!empty($rel)){
            $nofollow = 'rel="'.join(' ', $rel).'"';
        }

        $content = '';
        $count = 0;

        if (isset($this->details['abh_facebook']) && $this->details['abh_facebook'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_facebook'], 'http') === false) ? 'http://facebook.com/' : '') . esc_attr($this->details['abh_facebook']) . '" title="' . __('Facebook', _ABH_PLUGIN_NAME_) . '" class="abh_facebook" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_twitter']) && $this->details['abh_twitter'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_twitter'], 'http') === false) ? 'http://twitter.com/' : '') . esc_attr($this->details['abh_twitter']) . '" title="' . __('Twitter', _ABH_PLUGIN_NAME_) . '" class="abh_twitter" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_linkedin']) && $this->details['abh_linkedin'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_linkedin'], 'http') === false) ? 'http://www.linkedin.com/in/' : '') . esc_attr($this->details['abh_linkedin']) . '" title="' . __('LinkedIn', _ABH_PLUGIN_NAME_) . '" class="abh_linkedin" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_instagram']) && $this->details['abh_instagram'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_instagram'], 'http') === false) ? 'http://instagram.com/' : '') . esc_attr($this->details['abh_instagram']) . '" title="' . __('Instagram', _ABH_PLUGIN_NAME_) . '" class="abh_instagram" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_flickr']) && $this->details['abh_flickr'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_flickr'], 'http') === false) ? 'http://www.flickr.com/photos/' : '') . esc_attr($this->details['abh_flickr']) . '" title="' . __('Flickr', _ABH_PLUGIN_NAME_) . '" class="abh_flickr" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_pinterest']) && $this->details['abh_pinterest'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_pinterest'], 'http') === false) ? 'http://pinterest.com/' : '') . esc_attr($this->details['abh_pinterest']) . '" title="' . __('Pinterest', _ABH_PLUGIN_NAME_) . '" class="abh_pinterest" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_tumblr']) && $this->details['abh_tumblr'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_tumblr'], 'http') === false) ? 'http://' . esc_attr($this->details['abh_tumblr']) . '.tumblr.com/' : esc_attr($this->details['abh_tumblr'])) . '" title="' . __('Tumblr', _ABH_PLUGIN_NAME_) . '" class="abh_tumblr" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_youtube']) && $this->details['abh_youtube'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_youtube'], 'http') === false) ? 'http://www.youtube.com/user/' : '') . esc_attr($this->details['abh_youtube']) . '" title="' . __('YouTube', _ABH_PLUGIN_NAME_) . '" class="abh_youtube" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_vimeo']) && $this->details['abh_vimeo'] <> '') {
            $count++;
            $content .= '<a ' . (($itemprop) ? 'itemprop="sameAs"' : '') . ' href="' . ((strpos($this->details['abh_vimeo'], 'http') === false) ? 'http://vimeo.com/' : '') . esc_attr($this->details['abh_vimeo']) . '" title="' . __('Vimeo', _ABH_PLUGIN_NAME_) . '" class="abh_vimeo" target="_blank" ' . $nofollow . '></a>';
        }


        if ($count == 5 || $count == 6) {
            $content = '<div style="width:85px; margin: 0 0 0 auto;">' . $content . '</div>';
        } elseif ($count == 7 || $count == 8) {
            $content = '<div style="width:120px; margin: 0 0 0 auto;">' . $content . '</div>';
        } elseif ($count == 9 || $count == 10) {
            $content = '<div style="width:140px; margin: 0 0 0 auto;">' . $content . '</div>';
        } elseif ($count == 11 || $count == 12) {
            $content = '<div style="width:160px; margin: 0 0 0 auto;">' . $content . '</div>';
        }

        if ($count > 0 && isset($this->details['abh_socialtext']) && $this->details['abh_socialtext'] <> '')
            $content = '<div style="clear: both; font-size:12px; font-weight:normal; width: 85px; margin: 0 0 2px auto; line-height: 20px;">' . esc_html($this->details['abh_socialtext']) . '</div>' . $content;

        return $content;
    }


    /**
     * Get the List Of Posts for the author
     * @return string
     */
    private function getLatestPosts() {
        $content = '<ul>';
        $latest_posts = new WP_Query(array('posts_per_page' => ABH_Classes_Tools::getOption('anh_crt_posts'), 'author' => $this->author->ID));

        $latest_posts = apply_filters('abh_author_latest_posts', $latest_posts);

        while ($latest_posts->have_posts()) : $latest_posts->the_post();

            if (isset($this->category) && $this->category <> '') {
                $found = false;
                $categories = get_the_category();
                foreach ($categories as $category) {
                    if (!is_numeric($this->category)) {
                        if ($this->category == $category->name) {
                            $found = true;
                            break;
                        }
                    } elseif (is_numeric($this->category)) {
                        if ($this->category == $category->cat_ID) {
                            $found = true;
                            break;
                        }
                    }
                }
                if (!$found)
                    continue;
            }

            if (get_the_title() <> '')
                $content .= '
				<li ' . ((ABH_Classes_Tools::getOption('abh_descfontsize') <> 'default') ? 'style="font-size:' . ABH_Classes_Tools::getOption('abh_descfontsize') . ' !important;"' : '') . ' >
					<a href="' . get_permalink() . '">' . get_the_title() . '</a>' .
                    (((int)get_the_time('U') > 0) ? '<span> - ' . @date_i18n(get_option('date_format'), (int)get_the_time('U')) . '</span>' : '') . '
				</li>';
        endwhile;
        wp_reset_postdata();
        $content .= '</ul>';

        return $content;
    }

    /**
     * Clear the new lines from the author box
     * @param type $content
     * @return string
     */
    private function clearTags($content) {
        return preg_replace_callback('~\<[^>]+\>.*\</[^>]+\>~ms', array($this, 'stripNewLines'), $content);
    }

    /**
     * Clear the new lines
     * @param type $match
     * @return type
     */
    public function stripNewLines($match) {
        return str_replace(array("\r", "\n", "  "), '', $match[0]);
    }

    /**
     * Get the meta with Social and Profile
     * @return string
     */
    public function showMeta() {
        if (!isset($this->author))
            return;

        $meta = "\n<!-- StarBox - the Author Box for Humans " . ABH_VERSION . ", visit: http://wordpress.org/plugins/starbox/ -->\n";

        if (ABH_Classes_Tools::getOption('abh_showopengraph') == 1 && is_author()) {
            //Show the OpenGraph
            $meta .= $this->showOpenGraph();
        }

        if (isset($this->details['abh_google']) && $this->details['abh_google'] <> '')
            $meta .= $this->showGoogleAuthorMeta(); //show google author meta
        if (isset($this->details['abh_facebook']) && $this->details['abh_facebook'] <> '')
            $meta .= $this->showFacebookAuthorMeta(); //show facebook author meta

        $meta .= "<!-- /StarBox - the Author Box for Humans -->\n\n";

        return $meta;
    }

    /**
     * Get the Open Graph for the current author
     * @return string
     */
    public function showOpenGraph() {
        $og = '';
        $og .= sprintf('<meta property="og:url" content="%s" />', get_author_posts_url($this->author->ID)) . "\n";
        $og .= sprintf('<meta property="og:type" content="%s" />', 'profile') . "\n";
        $og .= sprintf('<meta property="profile:first_name" content="%s" />', get_the_author_meta('first_name', $this->author->ID)) . "\n";
        $og .= sprintf('<meta property="profile:last_name" content="%s" />', get_the_author_meta('last_name', $this->author->ID)) . "\n";

        return $og;
    }

    /**
     * Get the Google author Meta
     * @return string
     */
    public function showGoogleAuthorMeta() {
        return '<link rel="author" href="' . ((strpos($this->details['abh_google'], 'http') === false) ? 'http://plus.google.com/' : '') . $this->details['abh_google'] . '" />' . "\n";
    }

    /**
     * Get the Facebook author Meta
     * @return string
     */
    public function showFacebookAuthorMeta() {
        return '<meta property="article:author" content="' . ((strpos($this->details['abh_facebook'], 'http') === false) ? 'http://facebook.com/' : '') . $this->details['abh_facebook'] . '" />' . "\n";
    }

    /**
     * Check if the page is a valid HTML page
     * @return bool
     */
    public function isHtmlHeader() {
        $headers = headers_list();

        foreach ($headers as $index => $value) {
            if (strpos($value, ':') !== false) {
                $exploded = @explode(': ', $value);
                if (@count((array)$exploded) > 1) {
                    $headers[$exploded[0]] = $exploded[1];
                }
            }
        }
        if (isset($headers['Content-Type'])) {
            if (strpos($headers['Content-Type'], 'text/html') !== false) {
                return true;
            }
        } else {
            return false;
        }

        return false;
    }
}