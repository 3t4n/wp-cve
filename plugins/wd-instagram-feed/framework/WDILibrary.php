<?php

class WDILibrary {

  public function __construct() {
  }

  /**
   * Get request value.
   *
   * @param string $key
   * @param string $default_value
   * @param string $callback by default sanitize according to "sanitize_text_field"
   *
   * @return string|array
   */
  public static function get($key, $default_value = '', $callback = 'sanitize_text_field', $type = 'DEFAULT') {
    //we do nonce verification after get() function call immediately, in part of code, where get() function called
    //we sanitize all input variables in the end of get() function
    switch ($type) {
      case 'REQUEST' :
        /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
        if (isset($_REQUEST[$key])) {
          /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended */
          $value = $_REQUEST[$key];
        }
        break;
      case 'DEFAULT' :
      case 'POST' :
      /* phpcs:ignore WordPress.Security.NonceVerification.Missing */
        if (isset($_POST[$key])) {
          /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing */
          $value = $_POST[$key];
        }
        if ( 'POST' === $type ) break;
      case 'GET' :
        /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
        if (isset($_GET[$key])) {
          /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended */
          $value = $_GET[$key];
        }
        break;
    }
    if ( !isset($value) ) {
      if( $default_value === NULL ) {
        return NULL;
      } else {
        $value = $default_value;
      }
    }

    if( is_bool($value) ) {
      return $value;
    }

    if (is_array($value)) {
      // $callback should be third parameter of the validate_data function, so there is need to add unused second parameter to validate_data function.
      array_walk_recursive($value, array('self', 'validate_data'), $callback);
    }
    else {
      self::validate_data($value, 0, $callback);
    }

    return $value;
  }

  /**
   * Validate data.
   *
   * @param $value
   * @param $key
   * @param $callback
   */
  private static function validate_data(&$value, $key, $callback) {
    $value = stripslashes($value);
    if($key==="url"){
      $callback = "esc_url_raw";
    }
    if ( $callback && function_exists($callback) ) {
      $value = $callback($value);
    }
  }


  public static function message_id( $message_id,  $type = 'updated' ) {
    if ( $message_id ) {
      switch ( $message_id ) {
        case 1:
        {
          $message = 'Item Succesfully Saved.';
          $type = 'updated';
          break;
        }
        case 2:
        {
          $message = 'Error. Please install plugin again.';
          $type = 'error';
          break;
        }
        case 3:
        {
          $message = 'Item Succesfully Deleted.';
          $type = 'updated';
          break;
        }
        case 4:
        {
          $message = "You can't delete default theme";
          $type = 'error';
          break;
        }
        case 5:
        {
          $message = 'Items Succesfully Deleted.';
          $type = 'updated';
          break;
        }
        case 6:
        {
          $message = 'You must select at least one item.';
          $type = 'error';
          break;
        }
        case 7:
        {
          $message = 'The item is successfully set as default.';
          $type = 'updated';
          break;
        }
        case 8:
        {
          $message = 'Options Succesfully Saved.';
          $type = 'updated';
          break;
        }
        case 9:
        {
          $message = 'Item Succesfully Published.';
          $type = 'updated';
          break;
        }
        case 10:
        {
          $message = 'Items Succesfully Published.';
          $type = 'updated';
          break;
        }
        case 11:
        {
          $message = 'Item Succesfully Unpublished.';
          $type = 'updated';
          break;
        }
        case 12:
        {
          $message = 'Items Succesfully Unpublished.';
          $type = 'updated';
          break;
        }
        case 13:
        {
          $message = 'Ordering Succesfully Saved.';
          $type = 'updated';
          break;
        }
        case 14:
        {
          $message = 'A term with the name provided already exists.';
          $type = 'error';
          break;
        }
        case 15:
        {
          $message = 'Name field is required.';
          $type = 'error';
          break;
        }
        case 16:
        {
          $message = 'The slug must be unique.';
          $type = 'error';
          break;
        }
        case 17:
        {
          $message = 'Changes must be saved.';
          $type = 'error';
          break;
        }
        case 18:
        {
          $message = 'Item successfully duplicated.';
          $type = 'updated';
          break;
        }
        case 19:
        {
          $message = 'Items successfully Duplicated.';
          $type = 'updated';
          break;
        }
        case 20:
        {
          $message = 'Failed.';
          $type = 'error';
          break;
        }
        case 21:
        {
          $message = 'You cannot delete default theme.';
          $type = 'error';
          break;
        }
        case 22:
        {
          $message = 'Cannot Write on database. Check database permissions.';
          $type = 'error';
          break;
        }
        case 23:
        {
          $message = 'Changes have been successfully applied.';
          $type = 'updated';
          break;
        }
        case 24:
        {
          $message = 'You have not made new changes.';
          $type = 'updated';
          break;
        }
        case 25:
        {
          $message = "Style file generation failed. Maybe you don't have write permissions on wp-content/uploads folder. Your Instagram feed theme styles will be written inline.";
          $type = 'error';
          break;
        }
        case 26: {
          $message = __('No business pages were selected. Please uninstall the \'10Web Hashtag Feed\' app from \'Facebook > Settings > Security and login > Business integrations or Apps and websites\' section and reinstall. Then choose the business page/s and re-connect.', 'wd-instagram-feed');
          $type = 'error';
          break;
        }
        case 27: {
          $message = __('Cannot find connected Instagram business page. Either you do not have Instagram business account or it is not connected to current Facebook user\'s page.', 'wd-instagram-feed');
          $type = 'error';
          break;
        }
        case 28: {
          $message = __('Connected successfully.', 'wd-instagram-feed');
          $type = 'updated';
          break;
        }
        case 29: {
          $message = __('Connected successfully.', 'wd-instagram-feed');
          $type = 'updated';
          break;
        }
      }

      return '<div style="width:99%"><div class="' . $type . ' inline"><p><strong>' . $message . '</strong></p></div></div>';
    }
  }

  public static function message( $message, $type = 'error' ) {
    // temporary
    if ( !empty($message) && !is_numeric($message) ) {
      ob_start();
      ?><div style="width:99%" class="<?php echo esc_attr($type); ?> inline">
      <p>
        <strong><?php echo esc_html($message); ?></strong>
      </p>
      </div><?php
      $message = ob_get_clean();
    }
    else if ( is_numeric($message) ) {
      $message = self::message_id($message);
    }
    else {
      $message = '';
    }

    return $message;
  }

  public static function search($search_by, $search_value, $form_id) {
    ?>
      <script>
        function wdi_spider_search() {
          var wdi_form = jQuery('#<?php echo esc_attr($form_id); ?>');
          var wdi_search_text = jQuery("#search_value").val();
          var wdi_new_url = wdiChangeParamByName(window.location.href, "search", wdi_search_text);
          wdi_form.attr("action", wdi_new_url);
          wdi_form.submit();
        }
        function wdi_spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          if (document.getElementById("search_select_value")) {
            document.getElementById("search_select_value").value = 0;
          }
          document.getElementById("<?php echo esc_attr($form_id); ?>").submit();
        }
        function check_search_key(e, that) {
          var key_code = (e.keyCode ? e.keyCode : e.which);
          if (key_code == 13) { /*Enter keycode*/
            wdi_spider_search();
            return false;
          }
          return true;
        }
      </script>
        <p class="search-box">
        <input type="search" id="search_value" name="search_value" class="wdi_spider_search_value" onkeypress="return check_search_key(event, this);" value="<?php echo esc_attr($search_value); ?>" style="<?php echo (get_bloginfo('version') > '3.7') ? ' height: 28px;' : ''; ?>" />
        <input type="button" value="<?php _e('Search','wd-instagram-feed');?>" onclick="wdi_spider_search()" class="button-secondary action">
        </p>
    <?php
  }

  public static function html_page_nav($count_items, $page_number, $form_id, $items_per_page = 20) {
    $limit = 20;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    ?>
    <script type="text/javascript">
      var items_county = <?php echo esc_attr($items_county); ?>;
      function wdi_spider_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        document.getElementById('<?php echo esc_attr($form_id); ?>').submit();
      }
      function check_enter_key(e , _this) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) {
          var wdi_form = jQuery('#<?php echo esc_attr($form_id); ?>');
          /*Enter keycode*/
          var to_page = jQuery(_this).val();
          var wdi_new_url = wdiChangeParamByName(window.location.href, "paged", to_page);
          wdi_form.attr("action", wdi_new_url);
          wdi_form.submit();
        }
        return true;
      }
      function wdiChangeParamByName(url, paramName, paramValue){
        var pattern = new RegExp('(\\?|\\&)('+paramName+'=).*?(&|$)');
        var newUrl=url;
        if(url.search(pattern)>=0){
          if(paramValue===""){
            newUrl = url.replace(pattern,'');
          }else {
            newUrl = url.replace(pattern,'$1$2' + paramValue + '$3');
          }
        }
        else{
          newUrl = newUrl + (newUrl.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue
        }
        return newUrl
      }
    </script>
    <div class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo esc_attr($count_items); ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      $next_last_page = true;
      $first_prev_page = true;
      if ($count_items > $items_per_page) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_prev_page = false;
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $next_last_page = false;
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <?php if($first_prev_page):?>
          <?php
          // WDILibrary::get_pagination_page function returns get_page_link, which in his turn returns escaped data
          // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <a class="<?php echo esc_attr($first_page); ?>" title="Go to the first page" href="<?php echo WDILibrary::get_pagination_page($items_county ,  $page_number,-2);?>">«</a>
        <?php
          // WDILibrary::get_pagination_page function returns get_page_link, which in his turn returns escaped data
          // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <a class="<?php echo esc_attr($prev_page); ?>" title="Go to the previous page" href="<?php echo WDILibrary::get_pagination_page($items_county ,  $page_number,-1);?>">‹</a>
        <?php else:?>
          <span class="tablenav-pages-navspan" aria-hidden="true">«</span>
          <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
        <?php endif;?>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo esc_attr($page_number); ?>" onkeypress="return check_enter_key(event ,this)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo esc_html($items_county); ?>
          </span>
        </span>
        <?php if($next_last_page):?>
          <?php
          // WDILibrary::get_pagination_page function returns get_page_link, which in his turn returns escaped data
          // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <a class="<?php echo esc_attr($next_page) ?>" title="Go to the next page" href="<?php echo WDILibrary::get_pagination_page($items_county ,  $page_number,1);?>">›</a>
        <?php
          // WDILibrary::get_pagination_page function returns get_page_link, which in his turn returns escaped data
          // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <a class="<?php echo esc_attr($last_page) ?>" title="Go to the last page" href="<?php echo WDILibrary::get_pagination_page($items_county ,  $page_number,2);?>">»</a>
        <?php else:?>
          <span class="tablenav-pages-navspan" aria-hidden="true">›</span>
          <span class="tablenav-pages-navspan" aria-hidden="true">»</span>
        <?php endif;?>
        <?php
      }
      ?>
      </span>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo esc_attr(self::get('page_number', 1)); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo esc_attr(self::get('search_or_not')); ?>"/>
    <?php
  }

  public static function get_pagination_page($items_county, $x, $y){
    switch ($y) {
      case 1:
        if ($x >= $items_county) {
          $page_number = $items_county;
        }
        else {
          $page_number = $x + 1;
        }
        break;
      case 2:
        $page_number = $items_county;
        break;
      case -1:
        if ($x == 1) {
          $page_number = 1;
        }
        else {
          $page_number = $x - 1;
        }
        break;
      case -2:
        $page_number = 1;
        break;
      default:
        $page_number = 1;
    }

    $page_link_data = array(
      "paged" => $page_number
    );

    $search = self::get('search');
    $order_by = self::get('order_by');
    $order = self::get('order');
    if ( !empty($search) ) {
      $page_link_data['search'] = $search;
    }
    if ( !empty($order_by) ) {
      $page_link_data['order_by'] = $order_by;
    }
    if ( !empty($order) ) {
      $page_link_data['order'] = $order;
    }
    return self::get_page_link(array($page_link_data));

  }

  public static function ajax_search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function wdi_spider_search() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          wdi_spider_ajax_save('<?php echo esc_attr($form_id); ?>');
        }
        function wdi_spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          wdi_spider_ajax_save('<?php echo esc_attr($form_id); ?>');
        }        
        function check_search_key(e, that) {
          var key_code = (e.keyCode ? e.keyCode : e.which);
          if (key_code == 13) { /*Enter keycode*/
            wdi_spider_search();
            return false;
          }
          return true;
        }
      </script>
      <div class="alignleft actions" style="">
        <label for="search_value" style="font-size:14px; width:60px; display:inline-block;"><?php echo esc_html($search_by); ?>:</label>
        <input type="text" id="search_value" name="search_value" class="wdi_spider_search_value" onkeypress="return check_search_key(event, this);" value="<?php echo esc_attr($search_value); ?>" style="width: 150px;<?php echo (get_bloginfo('version') > '3.7') ? ' height: 28px;' : ''; ?>" />
      </div>
      <div class="alignleft actions">
        <input type="button" value="Search" onclick="wdi_spider_search()" class="button-secondary action">
        <input type="button" value="Reset" onclick="wdi_spider_reset()" class="button-secondary action">
      </div>
    </div>
    <?php
  }

  public static function ajax_html_page_nav($count_items, $page_number, $form_id, $items_per_page = 20, $pager = 0) {
    $limit = $items_per_page;

    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    if (!$pager) {
    ?>
    <script type="text/javascript">
      var items_county = <?php echo esc_attr($items_county); ?>;
      function wdi_spider_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        wdi_spider_ajax_save('<?php echo esc_attr($form_id); ?>');
      }
      function check_enter_key(e, that) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery(that).val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery(that).val();
          }
          wdi_spider_ajax_save('<?php echo esc_attr($form_id); ?>');
          return false;
        }
       return true;		 
      }
    </script>
    <?php } ?>
    <div id="tablenav-pages" class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo esc_attr($count_items); ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $limit) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo esc_attr($first_page); ?>" title="Go to the first page" onclick="wdi_spider_page(<?php echo esc_attr($page_number); ?>,-2)">«</a>
        <a class="<?php echo esc_attr($prev_page); ?>" title="Go to the previous page" onclick="wdi_spider_page(<?php echo esc_attr($page_number); ?>,-1)">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo esc_attr($page_number); ?>" onkeypress="return check_enter_key(event)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo esc_attr($items_county); ?>
          </span>
        </span>
        <a class="<?php echo esc_attr($next_page) ?>" title="Go to the next page" onclick="wdi_spider_page(<?php echo esc_attr($page_number); ?>,1)">›</a>
        <a class="<?php echo esc_attr($last_page) ?>" title="Go to the last page" onclick="wdi_spider_page(<?php echo esc_attr($page_number); ?>,2)">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <?php if (!$pager) { ?>
      <input type="hidden" id="page_number" name="page_number" value="<?php echo esc_attr(self::get('page_number', 1)); ?>" />
      <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo esc_attr(self::get('search_or_not')); ?>"/>
    <?php
    }
  }
  
  public static function ajax_html_frontend_search_box($form_id, $current_view, $cur_gal_id, $images_count, $search_box_width = 180) {
    $wdi_search = self::get('wdi_search_' . $current_view, '');
    $type = self::get('type_' . $current_view, 'album');
    $album_gallery_id = self::get('album_gallery_id_' . $current_view, 0, 'intval' );
    ?>
    <style>
      .wdi_search_container_1 {
        display: inline-block;
        width: 100%;
        text-align: right;
        margin: 0 5px 20px 5px;
        background-color: rgba(0,0,0,0);
      }
      .wdi_search_container_2 {
        display: inline-block;
        position: relative;
        border-radius: 4px;
        box-shadow: 0 0 3px 1px #CCCCCC;
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        width: <?php echo esc_attr($search_box_width); ?>px;
        max-width: 100%;
      }
      #wdi_search_container_1_<?php echo esc_attr($current_view); ?> #wdi_search_container_2_<?php echo esc_attr($current_view); ?> .wdi_search_input_container {
        display: block;
        margin-right: 45px;
      }
      #wdi_search_container_1_<?php echo esc_attr($current_view); ?> #wdi_search_container_2_<?php echo esc_attr($current_view); ?> .wdi_search_loupe_container {
        display: inline-block; 
        margin-right: 1px;
        vertical-align: middle;
        float: right;
        padding-top: 3px;
      }
      #wdi_search_container_1_<?php echo esc_attr($current_view); ?> #wdi_search_container_2_<?php echo esc_attr($current_view); ?> .wdi_search_reset_container {
        display: inline-block;
        margin-right: 5px;
        vertical-align: middle;
        float: right;
        padding-top: 3px;
      }
      #wdi_search_container_1_<?php echo esc_attr($current_view); ?> #wdi_search_container_2_<?php echo esc_attr($current_view); ?> .wdi_search,
      #wdi_search_container_1_<?php echo esc_attr($current_view); ?> #wdi_search_container_2_<?php echo esc_attr($current_view); ?> .wdi_reset {
        font-size: 18px;
        color: #CCCCCC;
        cursor: pointer;
      }
      #wdi_search_container_1_<?php echo esc_attr($current_view); ?> #wdi_search_container_2_<?php echo esc_attr($current_view); ?> .wdi_search_input_<?php echo esc_attr($current_view); ?>,
      #wdi_search_container_1_<?php echo esc_attr($current_view); ?> #wdi_search_container_2_<?php echo esc_attr($current_view); ?> .wdi_search_input_<?php echo esc_attr($current_view); ?>:focus {
        color: hsl(0, 1%, 3%);
        outline: none;
        border: none;
        box-shadow: none;
        background: none;
        padding: 0 5px;
        font-family: inherit;
        width: 100%;
      }
    </style>
    <script type="text/javascript">
      function clear_input_<?php echo esc_attr($current_view); ?> (current_view) {
        jQuery("#wdi_search_input_" + current_view).val('');
      }
      function check_enter_key(e) {
        var key_code = e.which || e.keyCode;
        if (key_code == 13) {
          wdi_spider_frontend_ajax('<?php echo esc_attr($form_id); ?>', '<?php echo esc_attr($current_view); ?>', '<?php echo esc_attr($cur_gal_id); ?>', <?php echo esc_attr($album_gallery_id); ?>, '', '<?php echo esc_attr($type); ?>', 1);
          return false;
        }
        return true;
      }
    </script>
    <div class="wdi_search_container_1" id="wdi_search_container_1_<?php echo esc_attr($current_view); ?>">
      <div class="wdi_search_container_2" id="wdi_search_container_2_<?php echo esc_attr($current_view); ?>">
        <span class="wdi_search_reset_container" >
          <i title="<?php _e('Reset', 'wd-instagram-feed'); ?>" class="wdi_reset tenweb-i tenweb-i-times" onclick="clear_input_<?php echo esc_attr($current_view); ?>('<?php echo esc_attr($current_view); ?>'),wdi_spider_frontend_ajax('<?php echo esc_attr($form_id); ?>', '<?php echo esc_attr($current_view); ?>', '<?php echo esc_attr($cur_gal_id); ?>', <?php echo esc_attr($album_gallery_id); ?>, '', '<?php echo esc_attr($type); ?>', 1)"></i>
        </span>
        <span class="wdi_search_loupe_container" >
          <i title="<?php _e('Search', 'wd-instagram-feed'); ?>" class="wdi_search tenweb-i tenweb-i-search" onclick="wdi_spider_frontend_ajax('<?php echo esc_attr($form_id); ?>', '<?php echo esc_attr($current_view); ?>', '<?php echo esc_attr($cur_gal_id); ?>', <?php echo esc_attr($album_gallery_id); ?>, '', '<?php echo esc_attr($type); ?>', 1)"></i>
        </span>
        <span class="wdi_search_input_container">
          <input id="wdi_search_input_<?php echo esc_attr($current_view); ?>" class="wdi_search_input_<?php echo esc_attr($current_view); ?>" type="text" onkeypress="return check_enter_key(event ,this)" name="wdi_search_<?php echo esc_attr($current_view); ?>" value="<?php echo esc_attr($wdi_search); ?>" >
          <input id="wdi_images_count_<?php echo esc_attr($current_view); ?>" class="wdi_search_input" type="hidden" name="wdi_images_count_<?php echo esc_attr($current_view); ?>" value="<?php echo esc_attr($images_count); ?>" >
        </span>
      </div>
    </div>
    <?php
  }

  public static function ajax_html_frontend_sort_box($form_id, $current_view, $cur_gal_id, $sort_by = '', $search_box_width = 180) {
    $wdi_search = self::get('wdi_search_' . $current_view, '');
    $type = self::get('type_' . $current_view, 'album');
    $album_gallery_id = self::get('album_gallery_id_' . $current_view, 0, 'intval' );

    ?>
    <style>
      .wdi_order_cont_<?php echo esc_attr($current_view); ?> {
        background-color: rgba(0,0,0,0);
        display: block;
        margin: 0 5px 20px 5px;
        text-align: right;
        width: 100%;
      }
      .wdi_order_label_<?php echo esc_attr($current_view); ?> {
        border: none;
        box-shadow: none;
        color: #BBBBBB;
        font-family: inherit;
        font-weight: bold;
        outline: none;
      }
      .wdi_order_<?php echo esc_attr($current_view); ?> {
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        box-shadow: 0 0 3px 1px #CCCCCC;
        border-radius: 4px;
        max-width: 100%;
        width: <?php echo esc_attr($search_box_width); ?>px;
      }
    </style>
    <div class="wdi_order_cont_<?php echo esc_attr($current_view); ?>">
      <span class="wdi_order_label_<?php echo esc_attr($current_view); ?>"><?php _e('Order by: ', 'wd-instagram-feed'); ?></span>
      <select class="wdi_order_<?php echo esc_attr($current_view); ?>" onchange="wdi_spider_frontend_ajax('<?php echo esc_attr($form_id); ?>', '<?php echo esc_attr($current_view); ?>', '<?php echo esc_attr($cur_gal_id); ?>', <?php echo esc_attr($album_gallery_id); ?>, '', '<?php echo esc_attr($type); ?>', 1, '', this.value)">
        <option <?php if ($sort_by == 'default') echo 'selected'; ?> value="default"><?php _e('Default', 'wd-instagram-feed'); ?></option>
        <option <?php if ($sort_by == 'filename') echo 'selected'; ?> value="filename"><?php _e('Filename', 'wd-instagram-feed'); ?></option>
        <option <?php if ($sort_by == 'size') echo 'selected'; ?> value="size"><?php _e('Size', 'wd-instagram-feed'); ?></option>
        <option <?php if ($sort_by == 'RAND()') echo 'selected'; ?> value="random"><?php _e('Random', 'wd-instagram-feed'); ?></option>
      </select>
    </div>
    <?php
  }

  public static function wdi_spider_hex2rgb($colour) {
    if ($colour[0] == '#') {
      $colour = substr( $colour, 1 );
    }
    if (strlen($colour) == 6) {
      list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    }
    else if (strlen($colour) == 3) {
      list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    }
    else {
      return FALSE;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array('red' => $r, 'green' => $g, 'blue' => $b);
  }

  public static function wdi_spider_redirect( $url = '' ) {
    ?>
    <script>
      window.location = "<?php echo esc_url_raw($url); ?>";
    </script>
    <?php
    exit();
  }

  /**
   * Redirect.
   *
   * @param array $url
   */
  public static function redirect( $url = array() ) {
    $url = html_entity_decode($url);
    ?>
    <script>window.location = "<?php echo esc_url_raw($url); ?>";</script>
    <?php
    exit();
  }

 /**
  *  If string argument passed, put it into delimiters for AJAX response to separate from other data.
  */

  public static function delimit_wd_output($data) {
    
    if(is_string ( $data )){
      return "WD_delimiter_start". $data . "WD_delimiter_end";
    }
    else{
      return $data;
    }
  }

  public static function verify_nonce($page){

    $nonce_verified = false;
    $wdi_nonce = self::get('wdi_nonce');
    $wp_nonce =  self::get('_wpnonce');

    if ( wp_verify_nonce( $wdi_nonce, $page ) ) {
        $nonce_verified = true;
    } elseif ( wp_verify_nonce( $wp_nonce, $page )) {
        $nonce_verified = true;
    }
    return $nonce_verified;
  }


  public static function arrayToObject($d) {
    if (is_array($d)) {
    /*
    * Return array converted to object
    * Using __FUNCTION__ (Magic constant)
    * for recursive call
    */
      return (object) array_map(array('WDILibrary','arrayToObject'), $d);
    }
    else {
      // Return object
    return $d;
    }
  }

  public static function objectToArray($d) {
    if (is_object($d)) {
      // Gets the properties of the given object
      // with get_object_vars function
      $d = get_object_vars($d);
    }
   
    if (is_array($d)) {
    /*
    * Return array converted to object
    * Using __FUNCTION__ (Magic constant)
    * for recursive call
    */
      return array_map(array('WDILibrary','objectToArray'), $d);
    }
    else {
      // Return array
      return $d;
    }
  }


  public static function keep_only_self_user($feed_row){
    global $wdi_options;

    if( isset($feed_row['liked_feed']) && $feed_row['liked_feed'] == 'liked' ) {
      $feed_row['nothing_to_display'] = '1';
      return $feed_row;
    }

    if( !empty($feed_row['feed_users']) ) {
      $users = json_decode($feed_row['feed_users'], TRUE);
      $new_users_list = array();
      $users_list = self::get_users_list();
      if ( is_array($users) ) {
        foreach ( $users as $i => $user ) {
          if ( substr($user['username'], 0, 1) === '#' || $user['username'] === $wdi_options['wdi_user_name'] || !empty($users_list[$user['username']]) ) {
            $new_users_list[] = $user;
          }
        }
      }
      $feed_row['nothing_to_display'] = (empty($new_users_list)) ? '1' : '0';
      $feed_row['feed_users'] = wp_json_encode($new_users_list);
    }
    return $feed_row;
  }

  /**
   * @param $color
   * @param $named
   * @return false|mixed|string color if valid color, otherwise return false
   */

  public static function regexColor($color, $named) {

    if ($named) {

      $named = array('transparent','aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');
      if (in_array(strtolower($color), $named)) {
        /* A color name was entered instead of a Hex Value, so just exit function */
        return $color;
      }
    }

    //checking rgb format
    if(self::is_rgb($color) == true){
      return $color;
    }
    if(substr($color,0,1) == '#' && strlen($color) === 4){
      $color.=substr($color,1,strlen($color));
    }

    //Check for a hex color string '#c1c2b4'
    if(preg_match('/^#[a-f0-9]{6}$/i', $color)) //hex color is valid
    {
      return $color;
    }

    //Check for a hex color string without hash 'c1c2b4'
    if(preg_match('/^[a-f0-9]{6}$/i', $color)) //hex color is valid
    {
      $fix_color = '#' . $color;
      return $fix_color;
    }
    return false;
  }

  /**
   * Check is rgb color value
   * @param   string
   * @return  boolean
   */
  public static function is_rgb($val){
      //checking for rgb
      $rgbFlag = false;
      $rgbaFlag = false;
        if(substr($val,0,4) === 'rgb('){
          $values = explode(',',substr($val,4,strlen($val)-5));
          if(count($values) !== 3){
            return false;
          }
          foreach ($values as $value) {
            if(!is_numeric($value) || $value<0 || $value>255){
              $rgbFlag = true;
            }
          }
          if($rgbFlag === false){
            return true;
          }
        }
        else if(substr($val,0,5) === 'rgba('){
          $values = explode(',',substr($val,5,strlen($val)-6));
          if(count($values) !== 4){
            return false;
          }
          foreach ($values as $value) {
            if(!is_numeric($value) || $value<0 || $value>255){
              $rgbaFlag = true;
            }
          }
          if($rgbaFlag === false){
            return true;
          }
        }else{
          return false;
        }
  }

  public static function get_page_link( $data ){
    $page = self::get('page');
    $url = esc_url(add_query_arg(array( 'page' => $page, $data ), admin_url('admin.php')));
    return $url;
  }

  public static function minify_styles($string){
    // comments
    $string = preg_replace('!/\*.*?\*/!s', '', $string);
    $string = preg_replace('/\n\s*\n/', "\n", $string);

    // space
    $string = preg_replace('/[\n\r \t]/', ' ', $string);
    $string = preg_replace('/ +/', ' ', $string);
    $string = preg_replace('/ ?([,:;{}]) ?/', '$1', $string);

    // trailing;
    $string = preg_replace('/;}/', '}', $string);
    return $string;
  }

  /**
   * Get shortcode data.
   *
   * @return false|string
   */
  public static function get_shortcode_data() {
    require_once WDI_DIR . '/admin/models/WDIModelEditorShortcode.php';
    $model = new WDIModelEditorShortcode();
    $rows = $model->get_row_data();
    $gb_row = array();
    foreach ( $rows as $row ) {
      $obj = new stdClass();
      $obj->id = $row->id;
      $obj->name = htmlspecialchars_decode($row->feed_name, ENT_QUOTES);
      $gb_row[] = $obj;
    }
    $data = array();
    $data['shortcode_prefix'] = 'wdi_feed';
    $data['inputs'][] = array(
      'type' => 'select',
      'id' => 'wdi_id',
      'name' => 'wdi_id',
      'shortcode_attibute_name' => 'id',
      'options' => $gb_row,
    );

    return wp_json_encode($data);
  }

  /**
   * Add auth button.
   *
   * @param string $text
   */
  public static function add_auth_button( $text = "" ) {
    $app_config = WDILibrary::instagram_app_config();
    $href = $app_config['basic_authorize_url'] . '?app_id=' . $app_config['basic_app_id'] . '&redirect_uri=' . $app_config['basic_redirect_uri'] . '&response_type=code&scope=user_profile,user_media&state=' . admin_url('admin.php?wdi_settings');
    ?>
    <a href="<?php echo esc_url($href); ?>" onclick="document.cookie='wdi_autofill=true'" class="wdi_sign_in_button"><i class="wdi-instagram-icon"></i><?php echo esc_html($text); ?></a>
    <?php
  }

  public static function get_users_list() {
    global $wdi_options;
    $users_list = array();
    if ( !empty($wdi_options['wdi_authenticated_users_list']) ) {
      $users_list = json_decode($wdi_options['wdi_authenticated_users_list'], TRUE);
      if ( !is_array($users_list) ) {
        $users_list = array();
      }
    }

    return $users_list;
  }

  public static function get_user_access_token($users){
    global $wdi_options;
    $users_list = self::get_users_list();
    foreach($users as $user) {
      if(substr($user->username, 0, 1) === '#') {
        continue;
      }

      if(!empty($users_list[$user->username])) {
        return $users_list[$user->username]['access_token'];
      }
    }

    return $wdi_options['wdi_access_token'];
  }

  public static function localize_script($object_name, $l10n){
    foreach ( (array) $l10n as $key => $value ) {
      if ( !is_scalar($value) )
        continue;

      $l10n[$key] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8');
    }

    $script = "var $object_name = " . wp_json_encode( $l10n ) . ';';
    $script = "<script>" . $script . "</script>";
    echo wp_kses($script, array('script' => array()));
  }

  public static function elementor_is_active(){
    $action = self::get('action', '', 'sanitize_text_field', 'REQUEST');
    $elementor_preview = self::get('elementor-preview', '', 'sanitize_text_field', 'REQUEST');
    if ( in_array($action, array('elementor', 'elementor_ajax')) || ($elementor_preview != '') ) {
      return true;
    }

    return false;
  }

  public static function is_ajax() {
    if ( defined('DOING_AJAX') && DOING_AJAX ) {
      return TRUE;
    }

    return FALSE;
  }

  public static function instagram_app_config() {
    $config = array(
      'basic_app_id' => '1196241780901628',
      'basic_authorize_url' => 'https://api.instagram.com/oauth/authorize/',
      'basic_response_type' => 'code',
      'basic_scope' => 'user_profile,user_media',
      'basic_redirect_uri' => 'https://instagram-api.10web.io/instagram/personal/',
      'access_token' => 'https://api.instagram.com/oauth/access_token/',
      'refresh_access_token' => 'https://graph.instagram.com/refresh_access_token/',
      'graph_app_id' => '639261367121596',
      'graph_authorize_url' => 'https://www.facebook.com/dialog/oauth/',
      'graph_scope' => 'instagram_basic,pages_show_list,pages_read_engagement',
      'graph_redirect_uri' => 'https://api.web-dorado.com/instagram/business/',
    );

    return $config;
  }

  /**
   * Refresh access token.
   *
   * @return array|bool|mixed|WP_Error
   */
  public static function refresh_instagram_access_token() {
    $wdi_options = wdi_get_options();
    if(is_array($wdi_options) && isset($wdi_options["wdi_authenticated_users_list"])){
      $wdi_authenticated_users_list = json_decode($wdi_options["wdi_authenticated_users_list"], TRUE);
      foreach ($wdi_authenticated_users_list as $wdi_user){
       if(isset($wdi_user["user_name"])){
         self::refresh_instagram_account($wdi_user["user_name"]);
       }
      }
    }
  }

  public static function refresh_instagram_account($user_name){
    $return_data = array(
      'success'=>FALSE
    );
    $wdi_user_data = NULL;
    $instagram_app_config = self::instagram_app_config();
    $wdi_instagram_options = get_option("wdi_instagram_options");
    if(is_array($wdi_instagram_options) && isset($wdi_instagram_options["wdi_authenticated_users_list"])){
      $wdi_authenticated_users_list = json_decode($wdi_instagram_options["wdi_authenticated_users_list"], TRUE);
      if(is_array($wdi_authenticated_users_list) && isset($wdi_authenticated_users_list[$user_name])){
        $wdi_user = $wdi_authenticated_users_list[$user_name];
        if(isset($wdi_user["access_token"]) && isset($wdi_user["type"])){
          $wdi_user_type = $wdi_user["type"];
          $wdi_user_token = $wdi_user["access_token"];
          $wdi_user_id = $wdi_user["user_id"];
          $url = '';
          if($wdi_user_type === "personal"){
            $url = $instagram_app_config["basic_redirect_uri"];
          }
          elseif ($wdi_user_type === "business"){
            //wp_remote_get is a native WordPress function
            /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get, WordPress.DB.DirectDatabaseQuery.DirectQuery */
            $wdi_user_data = wp_remote_get('https://graph.facebook.com/v12.0/' . $wdi_user_id . '?fields=id,ig_id,username,name,biography,profile_picture_url,followers_count,follows_count,media_count,website&access_token=' . $wdi_user_token);
            if(!is_wp_error( $wdi_user_data ) && isset($wdi_user_data["body"])){
              $wdi_user_data = json_decode($wdi_user_data["body"], TRUE);
            }
            $url = $instagram_app_config["graph_redirect_uri"];
          }
          $args = array(
            'body'    => array( 'action'=>'wdi_account_refresh','wdi_user_token' => $wdi_user_token),
          );
          $response = wp_remote_post( $url, $args );
          if(!is_wp_error( $response ) && isset($response["body"])){
            $wdi_user_access_token = json_decode($response["body"], TRUE);
            if(isset($wdi_user_access_token["access_token"])){
              $wdi_user_token = sanitize_text_field($wdi_user_access_token["access_token"]);
              $wdi_authenticated_users_list[$user_name]["access_token"] = $wdi_user_token;
              if(isset($wdi_user_data)){
                if(isset($wdi_user_data["id"])){
                  $wdi_authenticated_users_list[$user_name]["user_id"] = $wdi_user_data["id"];
                }
                if(isset($wdi_user_data["username"])){
                  $wdi_authenticated_users_list[$user_name]["user_name"] = $wdi_user_data["username"];
                }
                if(isset($wdi_user_data["biography"])){
                  $wdi_authenticated_users_list[$user_name]["biography"] = $wdi_user_data["biography"];
                }
                if(isset($wdi_user_data["profile_picture_url"])){
                  $wdi_authenticated_users_list[$user_name]["profile_picture_url"] = $wdi_user_data["profile_picture_url"];
                }
                if(isset($wdi_user_data["followers_count"])){
                  $wdi_authenticated_users_list[$user_name]["followers_count"] = $wdi_user_data["followers_count"];
                }
                if(isset($wdi_user_data["follows_count"])){
                  $wdi_authenticated_users_list[$user_name]["follows_count"] = $wdi_user_data["follows_count"];
                }
                if(isset($wdi_user_data["media_count"])){
                  $wdi_authenticated_users_list[$user_name]["media_count"] = $wdi_user_data["media_count"];
                }
                if(isset($wdi_user_data["website"])){
                  $wdi_authenticated_users_list[$user_name]["website"] = $wdi_user_data["website"];
                }
              }
              $wdi_instagram_options["wdi_authenticated_users_list"] = wp_json_encode($wdi_authenticated_users_list);
              $updated = update_option("wdi_instagram_options" , $wdi_instagram_options);
              if($updated){
                delete_option('wdi_token_error_flag');
                $return_data["success"] = TRUE;
                $return_data["user"] = $user_name;
                $return_data["token"] = $wdi_user_token;
              }
            }
          }
        }
      }
    }
    // @ToDo if the type is business, change all businesses.
    return $return_data;
  }

  /**
   * User feed info.
   * @param array $args
   *
   * @return false|string
   */
  public static function user_feed_header_info( $args = array() ) {
    if ( !empty($args['user']) ) {
      $user = $args['user'];
      $settings = $args['settings'];
      $business = $user['type'] === 'business';
      ob_start();
      ?>
      <div class="wdi_single_user">
        <div class="wdi_header_user_text ">
          <?php if ( !empty($settings['show_usernames']) && $settings['show_usernames'] == 1 ) { ?>
            <?php if ( $business ) { ?>
              <div class="wdi_user_img_wrap">
                <img src="<?php echo esc_url($user['profile_picture_url']); ?>" alt="<?php echo esc_attr($user['user_name']); ?>">
              </div>
            <?php } ?>
            <h3 onclick="window.open('//instagram.com/<?php echo esc_attr($user['user_name']); ?>','_blank')"><?php echo esc_html($user['user_name']); ?></h3>
          <?php } ?>
          <?php if ( !empty($settings['show_follow']) && $settings['show_follow'] == 1 ) { ?>
            <div class="wdi_user_controls">
              <div class="wdi_follow_btn"
                   onclick="window.open('//instagram.com/<?php echo esc_attr($user['user_name']); ?>','_blank')">
                <span><?php _e('Follow', 'wd-instagram-feed'); ?></span>
              </div>
            </div>
          <?php } ?>
          <?php if ( $business && !empty($settings['show_usernames']) && !empty($settings['media_followers']) && $settings['media_followers'] == 1 ) { ?>
            <div class="wdi_media_info">
              <p class="wdi_posts">
                <span class="tenweb-i tenweb-i-camera-retro"></span><?php echo esc_html($user['media_count']); ?>
              </p>
              <p class="wdi_followers">
                <span class="tenweb-i tenweb-i-user"></span><?php echo esc_html($user['followers_count']); ?>
              </p>
            </div>
          <?php } ?>
          <?php if ( $business && !empty($settings['show_usernames']) && !empty($settings['biography_website']) && $settings['biography_website'] == 1 ) { ?>
            <div class="wdi_clear"></div>
            <div class="wdi_bio"><?php echo esc_html($user['biography']); ?></div>
            <div class="wdi_website">
              <a target="_blank" href="<?php echo esc_url($user['website']); ?>"><?php echo esc_html($user['website']); ?></a>
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="wdi_clear"></div>
      <?php
      return ob_get_clean();
    }
  }

  /**
   * Generate top bar.
   *
   * @return string Top bar html.
   */
  public static function topbar() {
    $page = self::get('page', '', 'sanitize_text_field', 'GET');
    $user_guide_link = 'https://help.10web.io/hc/en-us/articles/';
    $show_guide_link = true;
    $description = "";
    switch ($page) {
      case 'wdi_feeds': {
        $user_guide_link .= '360016497251-Creating-Instagram-Feed';
        $description .= __('This section allows you to create, edit and delete Feeds.', WDI_PREFIX);
        break;
      }
      case 'wdi_themes': {
        $user_guide_link .= '360016277832';
        $description .= __('This section allows you to create, edit and delete Themes.', WDI_PREFIX);
        break;
      }
      case 'wdi_settings': {
        $user_guide_link .= '360016277532-Configuring-Instagram-Access-Token';
        $description .= __('This section allows you to set API parameters.', WDI_PREFIX);
        break;
      }
      default: {
        return '';
        break;
      }
    }
    $user_guide_link .= '?utm_source=instagram_feed&amp;utm_medium=free_plugin';
    $support_forum_link = 'https://wordpress.org/support/plugin/wd-instagram-feed/#new-post';
    $premium_link = 'https://10web.io/plugins/wordpress-instagram-feed/?utm_source=instagram_feed&amp;utm_medium=free_plugin';
    wp_enqueue_style(WDI_PREFIX . '-roboto');
    wp_enqueue_style(WDI_PREFIX . '-pricing');
    ob_start();
    ?>
    <div class="wrap">
      <h1 class="head-notice">&nbsp;</h1>
      <div class="topbar-container">
        <?php
        if ( WDI_IS_FREE ) {
          ?>
          <div class="topbar topbar-content">
            <div class="topbar-content-container">
              <div class="topbar-content-title">
                <?php esc_html_e('Instagram Feed by 10Web Premium', WDI_PREFIX); ?>
              </div>
              <div class="topbar-content-body">
                <?php echo esc_html($description); ?>
              </div>
            </div>
            <div class="topbar-content-button-container">
              <a href="<?php echo esc_url($premium_link); ?>" target="_blank" class="topbar-upgrade-button"><?php esc_html_e( 'Upgrade',WDI_PREFIX ); ?></a>
            </div>
          </div>
          <?php
        }
        ?>
        <div class="topbar_cont">
          <?php
          if ( $show_guide_link ) {
            ?>
            <div class="topbar topbar-links">
              <div class="topbar-links-container">
                <a href="<?php echo esc_url($user_guide_link); ?>" target="_blank" class="topbar_user_guid">
                  <div class="topbar-links-item">
                    <?php esc_html_e('User guide', WDI_PREFIX); ?>
                  </div>
                </a>
              </div>
            </div>
            <?php
          }
          if ( WDI_IS_FREE ) {
            ?>
            <div class="topbar topbar-links topbar_support_forum">
              <div class="topbar-links-container">
                <a href="<?php echo esc_url($support_forum_link); ?>" target="_blank" class="topbar_support_forum">
                  <div class="topbar-links-item">
                    <img src="<?php echo esc_url(WDI_URL) . '/images/help.svg'; ?>" class="help_icon" />
                    <?php esc_html_e('Ask a question', WDI_PREFIX); ?>
                  </div>
                </a>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
    </div>
    <?php
    // all data already escaped
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo ob_get_clean();
  }

  /**
   * Get user ip.
   *
   * @return mixed|void
   */
  public static function get_user_ip() {
    if ( !empty($_SERVER['HTTP_CLIENT_IP']) ) {
      /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */
      $ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
    }
    /* phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders */
    elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
      /* phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders */
      $ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
    }
    /* phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders */
    elseif ( !empty($_SERVER['REMOTE_ADDR']) ) {
      /* phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders, WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__REMOTE_ADDR__ */
      $ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
    } else {
      $ip = '';
    }

    return apply_filters('wdi_get_ip', $ip);
  }
}