<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Search Engine Visibility Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_search_engine($option,$option_key,$option_checkbox){

  foreach ($option_key['robot'] as $key => $value  ) {
    $robot[$key] = $option['robot'][$key];
  }


  $robot_list = array(
    '404' => esc_html_x('404', 'robot' ,'yahman-add-ons') ,
    'search' => esc_html_x('search', 'robot' ,'yahman-add-ons') ,
    'year' => esc_html_x('year', 'robot' ,'yahman-add-ons') ,
    'month' => esc_html_x('month', 'robot' ,'yahman-add-ons') ,
    'day' => esc_html_x('day', 'robot' ,'yahman-add-ons') ,
    'tag' => esc_html_x('tag', 'robot' ,'yahman-add-ons') ,
    'category' => esc_html_x('category', 'robot' ,'yahman-add-ons') ,
    'attachment' => esc_html_x('attachment', 'robot' ,'yahman-add-ons') ,
  );

  ?>

  <div id="ya_robot_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Search engine search target','yahman-add-ons'); ?></h2>
    <p class="description"><?php esc_html_e('It is up to search engines to honor this request.','yahman-add-ons'); ?></p>


    <h3><?php esc_html_e('Pages to exclude from search engines','yahman-add-ons'); ?></h3>

    <?php
    foreach ($robot_list as $key => $value) { ?>
      <div class="ya_setting_content">
        <div class="ya_tooltip_wrap">
          <label for="robot_<?php echo esc_attr($key); ?>">
            <?php echo esc_html($value); ?>
          </label>
          <div class="ya_tooltip">
            <?php echo sprintf( esc_html__( 'Exclude %1$s pages from search.', 'yahman-add-ons') , esc_html($value) ); ?>
          </div>
        </div>
        <div class="ya_checkbox">
          <input name="yahman_addons[robot][<?php echo esc_attr($key); ?>]" type="checkbox" id="robot_<?php echo esc_attr($key); ?>"<?php checked(true, isset($option['robot'][$key]) ? true: false ); ?> class="ya_checkbox" />
          <label for="robot_<?php echo esc_attr($key); ?>"></label>
        </div>
      </div>


      <?php
    }
    ?>


    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="robot_post_not_in"><?php esc_html_e('Enter the post ID to use this function','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Please enter the post ID you want to exclude from the search.', 'yahman-add-ons'); ?>
          <br>
          <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
        </div>
      </div>
      <input name="yahman_addons[robot][post_not_in]" type="text" id="robot_post_not_in" value="<?php echo $robot['post_not_in']; ?>" class="widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="robot_parent_not_in"><?php esc_html_e('Enter the ID of the parent page that uses this function','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Enter the post ID of the parent page to be excluded from the search.', 'yahman-add-ons'); ?>
          <br>
          <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
          <br>
          <?php esc_html_e( 'Child pages that belong to the parent page are also included.', 'yahman-add-ons'); ?>
          <br>
        </div>
      </div>
      <input name="yahman_addons[robot][parent_not_in]" type="text" id="robot_parent_not_in" value="<?php echo $robot['parent_not_in']; ?>" class="widefat" />
    </div>





  </div>




  <?php
}
