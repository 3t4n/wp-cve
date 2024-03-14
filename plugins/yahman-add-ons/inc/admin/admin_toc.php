<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin TOC Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_toc($option,$option_key,$option_checkbox){


  foreach ($option_key['toc'] as $key => $value  ) {
    $toc[$key] = $option['toc'][$key];
  }
  foreach ($option_checkbox['toc'] as $key => $value  ) {
    $toc[$key] = isset($option['toc'][$key]) ? true: false;
  }


  ?>

  <div id="ya_toc_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Table of contents','yahman-add-ons'); ?></h2>


    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="toc_post">
          <?php esc_html_e('Automatically display toc in post','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Insert the table of contents if the conditions are met.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
       <input name="yahman_addons[toc][post]" type="checkbox" id="toc_post"<?php checked(true, $toc['post']); ?> class="ya_checkbox" />
       <label for="toc_post"></label>
     </div>
   </div>

   <div class="ya_setting_content">
    <div class="ya_tooltip_wrap">
      <label for="toc_page">
        <?php esc_html_e('Automatically display toc in page','yahman-add-ons'); ?>
      </label>
      <div class="ya_tooltip">
        <?php esc_html_e('Insert the table of contents if the conditions are met.','yahman-add-ons'); ?>
      </div>
    </div>
    <div class="ya_checkbox">
     <input name="yahman_addons[toc][page]" type="checkbox" id="toc_page"<?php checked(true, $toc['page']); ?> class="ya_checkbox" />
     <label for="toc_page"></label>
   </div>
 </div>

 <div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_title"><?php esc_html_e('Title','yahman-add-ons'); ?></label>
    <div class="ya_tooltip">
      <?php esc_html_e( 'Toc heading title.', 'yahman-add-ons'); ?>
    </div>
  </div>
  <input name="yahman_addons[toc][title]" type="text" id="toc_title" value="<?php echo esc_attr($toc['title']); ?>" class="ya_textbox" />
</div>

<div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_dc">
      <?php esc_html_e('lower limit the heading which toc is displayed','yahman-add-ons'); ?>
    </label>
    <div class="ya_tooltip">
      <?php esc_html_e('The table of contents is displayed when the number of headings in the content exceeds this number.', 'yahman-add-ons'); ?>
    </div>
  </div>
  <select name="yahman_addons[toc][dc]" id="toc_dc">
    <?php
    for( $i = 2; $i <= 10; ++$i ){
      echo '<option value="'.$i.'"'. selected( $toc['dc'], $i ,false ).'>'.$i.'</option>';
    } ?>
  </select>
</div>

<div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_dp">
      <?php esc_html_e('Display position','yahman-add-ons'); ?>
    </label>
    <div class="ya_tooltip">
      <?php esc_html_e('Select the position to display the table of contents.', 'yahman-add-ons'); ?>
    </div>
  </div>
  <select name="yahman_addons[toc][dp]" id="toc_dp">
    <?php
    $toc_dp = array(
      esc_html__('Before the first heading', 'yahman-add-ons'),
      esc_html__('After the first heading', 'yahman-add-ons'),
      esc_html_x('Top', 'TOC' ,'yahman-add-ons'),
    );
    for( $i = 1; $i <= 3; ++$i ){
      echo '<option value="'.$i.'"'. selected( $toc['dp'], $i ,false ).'>'.$toc_dp[$i-1].'</option>';
    } ?>
  </select>
</div>

<div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_post_not_in"><?php esc_html_e('Enter the post ID that does not use this function','yahman-add-ons'); ?></label>
    <div class="ya_tooltip">
      <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
    </div>
  </div>
  <input name="yahman_addons[toc][post_not_in]" type="text" id="toc_post_not_in" value="<?php echo $toc['post_not_in']; ?>" class="widefat" />
</div>

<div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_parent_not_in">
      <?php esc_html_e('Enter the ID of the parent page that does not use this function','yahman-add-ons'); ?>
    </label>
    <div class="ya_tooltip">
      <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
      <br>
      <?php esc_html_e( 'Child pages that belong to the parent page are also included.', 'yahman-add-ons'); ?>
      <br>
    </div>
  </div>
  <input name="yahman_addons[toc][parent_not_in]" type="text" id="toc_parent_not_in" value="<?php echo $toc['parent_not_in']; ?>" class="widefat" />
</div>


<div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_hierarchical">
      <?php esc_html_e('Hierarchical view','yahman-add-ons'); ?>
    </label>
    <div class="ya_tooltip">
      <?php esc_html_e('Display the table of contents hierarchically.','yahman-add-ons'); ?>
    </div>
  </div>
  <div class="ya_checkbox">
   <input name="yahman_addons[toc][hierarchical]" type="checkbox" id="toc_hierarchical"<?php checked(true, $toc['hierarchical']); ?> class="ya_checkbox" />
   <label for="toc_hierarchical"></label>
 </div>
</div>

<div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_numerical">
      <?php esc_html_e('Numerical display','yahman-add-ons'); ?>
    </label>
    <div class="ya_tooltip">
      <?php esc_html_e('Assign a number to the table of contents heading.','yahman-add-ons'); ?>
    </div>
  </div>
  <div class="ya_checkbox">
   <input name="yahman_addons[toc][numerical]" type="checkbox" id="toc_numerical"<?php checked(true, $toc['numerical']); ?> class="ya_checkbox" />
   <label for="toc_numerical"></label>
 </div>
</div>

<div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_hide">
      <?php esc_html_e('Initially hide table of contents','yahman-add-ons'); ?>
    </label>
    <div class="ya_tooltip">
      <?php esc_html_e('Immediately after opening the screen, fold the table of contents.','yahman-add-ons'); ?>
    </div>
  </div>
  <div class="ya_checkbox">
   <input name="yahman_addons[toc][hide]" type="checkbox" id="toc_hide"<?php checked(true, $toc['hide']); ?> class="ya_checkbox" />
   <label for="toc_hide"></label>
 </div>
</div>

<div class="ya_setting_content">
  <div class="ya_tooltip_wrap">
    <label for="toc_nextpage">
      <?php esc_html_e('Include next page','yahman-add-ons'); ?>
    </label>
    <div class="ya_tooltip">
      <?php esc_html_e('The headings of all pages divided by page breaks are included in the table of contents.','yahman-add-ons'); ?>
    </div>
  </div>
  <div class="ya_checkbox">
   <input name="yahman_addons[toc][nextpage]" type="checkbox" id="toc_nextpage"<?php checked(true, $toc['nextpage']); ?> class="ya_checkbox" />
   <label for="toc_nextpage"></label>
 </div>
</div>

<div class="ya_hr"></div>


<label class="ya_link_color" for="ya_widget" onclick="to_top();">
  <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
  &rsaquo; <?php esc_html_e('Table of contents widget', 'yahman-add-ons'); ?><br>
</label>



</div>




<?php
}
