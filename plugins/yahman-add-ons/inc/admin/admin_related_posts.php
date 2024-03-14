<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Related posts
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_related_posts($option,$option_key,$option_checkbox){

  foreach ($option_key['related_posts'] as $key => $value  ) {
    $related_posts[$key] = $option['related_posts'][$key];
  }
  foreach ($option_checkbox['related_posts'] as $key => $value  ) {
    $related_posts[$key] = isset($option['related_posts'][$key]) ? true: false;
  }

  ?>

  <div id="ya_related_posts_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Related Posts','yahman-add-ons'); ?></h2>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="related_posts_post">
          <?php esc_html_e('Related posts under in the contents of the post','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Randomly select related posts based on tags and categories.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[related_posts][post]" type="checkbox" id="related_posts_post"<?php checked(true, $related_posts['post']); ?> class="ya_checkbox" />
        <label for="related_posts_post"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="related_posts_post_title"><?php esc_html_e('Heading title','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Related posts heading title.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[related_posts][post_title]" type="text" id="related_posts_post_title" value="<?php echo esc_attr($related_posts['post_title']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="related_posts_num"><?php esc_html_e('Maximum number of related posts','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Select the maximum number of related posts to display.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[related_posts][post_num]" type="number"  min="1" max="20" value="<?php echo $related_posts['post_num']; ?>" id="related_posts_num" class="" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="related_posts_post_style"><?php esc_html_e('Display style','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Select how to display related posts.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[related_posts][post_style]" id="related_posts_post_style">
        <option value="1"<?php selected( $related_posts['post_style'], "1" ); ?>><?php esc_html_e( 'List', 'yahman-add-ons' ); ?></option>
        <option value="2"<?php selected( $related_posts['post_style'], "2" ); ?>><?php esc_html_e( 'List with thumbnail', 'yahman-add-ons' ); ?></option>
        <option value="3"<?php selected( $related_posts['post_style'], "3" ); ?>><?php esc_html_e( 'Title over a thumbnail', 'yahman-add-ons' ); ?></option>
        <option value="4"<?php selected( $related_posts['post_style'], "4" ); ?>><?php esc_html_e( 'Title under a thumbnail', 'yahman-add-ons' ); ?></option>
      </select>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="related_posts_page">
          <?php esc_html_e('Related posts under in the contents of the page','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Randomly select related pages based on tags and categories.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('We recommend using the following plug-ins to use this function.','yahman-add-ons'); ?>
          <br>
          <a href="<?php echo esc_url('https://wordpress.org/plugins/pages-with-category-and-tag/' ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('Pages with category and tag', 'yahman-add-ons'); ?></a>
          <br>
          By YAHMAN
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[related_posts][page]" type="checkbox" id="related_posts_page"<?php checked(true, $related_posts['page']); ?> class="ya_checkbox" />
        <label for="related_posts_page"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="related_posts_page_title"><?php esc_html_e('Heading title','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Related pages heading title.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[related_posts][page_title]" type="text" id="related_posts_page_title" value="<?php echo esc_attr($related_posts['page_title']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="related_posts_page_num"><?php esc_html_e('Maximum number of related pages','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Select the maximum number of related pages to display.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[related_posts][page_num]" type="number"  min="1" max="20" value="<?php echo $related_posts['page_num']; ?>" id="related_posts_page_num" class="" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="related_posts_page_style"><?php esc_html_e('Display style','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Select how to display related pages.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[related_posts][page_style]" id="related_posts_page_style">
        <option value="1"<?php selected( $related_posts['page_style'], "1" ); ?>><?php esc_html_e( 'List', 'yahman-add-ons' ); ?></option>
        <option value="2"<?php selected( $related_posts['page_style'], "2" ); ?>><?php esc_html_e( 'List with thumbnail', 'yahman-add-ons' ); ?></option>
        <option value="3"<?php selected( $related_posts['page_style'], "3" ); ?>><?php esc_html_e( 'Title over a thumbnail', 'yahman-add-ons' ); ?></option>
        <option value="4"<?php selected( $related_posts['page_style'], "4" ); ?>><?php esc_html_e( 'Title under a thumbnail', 'yahman-add-ons' ); ?></option>
      </select>
    </div>



  </div>




  <?php
}
