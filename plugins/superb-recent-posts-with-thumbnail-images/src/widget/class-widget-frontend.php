<?php

namespace SuperbRecentPosts\Widget;

use SuperbRecentPosts\Widget\WidgetConstant;

if (! defined('WPINC')) {
    die;
}

class WidgetFrontend
{
    private $Title;
    private $RecentPosts;
    private $DisplayDate;
    private $AlignText;
    private $DisplayThumbnails;
    private $AlignThumbnails;
    private $args;

    public function __construct($args, $instance)
    {
        if ((!$instance[WidgetConstant::SHOW_HOMEPAGE] && is_front_page()) ||
        (!$instance[WidgetConstant::SHOW_BLOGPAGE] && is_home()) ||
        (!$instance[WidgetConstant::SHOW_PAGESPOSTS] && !is_front_page() && !is_home())) {
            echo '<!-- Superb Recent Posts Widget Hidden -->';
            return false;
        }
        $this->args = $args;
        $this->Title = apply_filters('widget_title', $instance[WidgetConstant::TITLE]);
        $numberofposts = absint($instance[WidgetConstant::NUMBER_OF_POSTS]);
        $excludecurrent = ($instance[WidgetConstant::EXCLUDE_CURRENT] && !is_front_page() && !is_home()) ? array(get_the_ID()) : array();
        $recent_posts_args = array("numberposts" => $numberofposts, "post_status" => "publish", "exclude" => $excludecurrent);
        $this->RecentPosts = wp_get_recent_posts($recent_posts_args);
        $this->DisplayDate = $instance[WidgetConstant::DISPLAY_DATE];
        $this->AlignText = $instance[WidgetConstant::ALIGN_TEXT];
        $this->DisplayThumbnails = $instance[WidgetConstant::DISPLAY_THUMBNAILS];
        $this->AlignThumbnails = $instance[WidgetConstant::ALIGN_THUMBNAILS];
        $this->Setup();
    }

    private function Setup()
    {
        echo $this->args['before_widget'];
        $this->AddTitle();
        $this->BuildWidget();
        echo $this->args['after_widget'];
    }

    private function AddTitle()
    {
        if (! empty($this->Title)) {
            echo $this->args['before_title'] . esc_html($this->Title) . $this->args['after_title'];
        }
    }

    private function BuildWidget()
    { ?>
        <div class="spbrposts-wrapper<?php echo " spbrposts-align-".esc_attr($this->AlignThumbnails); echo " spbrposts-text-align-".esc_attr($this->AlignText); echo $this->DisplayDate ? '' : ' spbrposts-no-date'; echo $this->DisplayThumbnails ? '' : ' spbrposts-no-thumbnail'; ?>">
            <ul class="spbrposts-ul"> <?php
                foreach ($this->RecentPosts as &$single_post) {
                    $this->AddListItem($single_post);
                }
                unset($single_post); ?>
            </ul>
            <!-- Superb Recent Posts Widget -->
        </div>
        <?php
    }

    private function AddListItem($single_post)
    {
        $the_post_title = $single_post['post_title'] === '' ? $single_post['post_name'] : $single_post['post_title'];
        $the_thumbnail_url = get_the_post_thumbnail_url($single_post['ID'], array(45,45));
        $thumbnail_url = !$the_thumbnail_url ? SUPERBRECENTPOSTS_ASSETS_PATH.'/img/45x45_placeholder.png' : $the_thumbnail_url;
        $the_permalink = get_the_permalink($single_post['ID']);
        $permalink = !$the_permalink ? "#" : $the_permalink; ?>

                <li class="spbrposts-li">
                    <?php if ($this->DisplayThumbnails) {?>
                    <a class="spbrposts-img" href="<?php echo esc_url($permalink); ?>" rel="bookmark">
                        <img width="45" height="45" class="spbrposts-thumb" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($the_post_title); ?>">
                    </a>
                    <?php } ?>
                    <h3 class="spbrposts-title">
                        <a href="<?php echo esc_url($permalink); ?>" title="Permalink to <?php echo esc_attr($the_post_title); ?>" rel="bookmark"><?php echo esc_html($the_post_title); ?></a>
                    </h3>
                    <?php if ($this->DisplayDate) {?>
                        <time class="spbrposts-time published" datetime="2020-01-23T04:08:00+00:00"><?php echo esc_html(get_the_date('F j, Y', $single_post['ID'])); ?></time>
                    <?php } ?>
                </li>
        
        <?php
    }
}
