<div class="wrap">
  <h2>Review Stream Settings</h2>
  <form method="post" action="options.php">
    <?php @settings_fields('wprs_group');?>
    <?php @do_settings_fields('wprs_group', '');?>
    <?php
      $type = get_option('rs_type');
      if(!$type) {
        // Default
        $type = 'LocalBusiness';
      }
      $format = get_option('rs_schema');
      if(!$format) {
        // Default
        $format = 'jsonld';
      }
      $schema_direct_only = get_option('rs_schema_direct_only');
      if($schema_direct_only === null) {
        $schema_direct_only = false;
      }
      $show_aggregate_rating = get_option('rs_show_aggregate_rating');
      if($show_aggregate_rating === null) {
        // Default
        $show_aggregate_rating = true;
      }
      $last_initial = get_option('rs_last_initial');
      if($last_initial === null) {
        $last_initial = false;
      }
      $show_reviews = get_option('rs_show_reviews');
      if($show_reviews === null) {
        // Default
        $show_reviews = true;
      }
      $include_empty = get_option('rs_include_empty', true);
      if($include_empty != true) {
        $include_empty = false;
      }
      $stream_only = get_option('rs_stream_only', true);
      if($stream_only != true) {
        $stream_only = false;
      }
      $display = get_option('rs_review_display');
      if(!$type) {
        // Default
        $type = 'List';
      }
      $pager = get_option('rs_pager', false);
      if($pager != true) {
        $pager = false;
      }
      $show_powered_by = get_option('rs_show_powered_by');
      if($show_powered_by === null) {
        // Default
        $show_powered_by = true;
      }
    ?>

    <p style="font-size:1.2em">Embed the Review Stream into your content using the shortcode [reviewstream]. The shortcode supports the following attributes, which will override the global settings below: <strong>count</strong>, <strong>path</strong>, <strong>show_aggregate_rating</strong>, <strong>last_initial</strong>, <strong>display</strong>, <strong>show_reviews</strong>, <strong>include_empty</strong>, <strong>show_pager</strong>, <strong>stream_only</strong> and <strong>show_powered_by</strong></p>
    <p style="font-size:1.2em">For example, use <strong>[reviewstream path="custompath" show_aggregate_rating="true" show_reviews="false"]</strong> to display only the aggregate rating for the profile at <em>custompath</em>.</p>

    <table class="form-table">
      <tr valign="top">
        <th scope="row"><label for="rs_type">Entity type</label></th>
        <td><select name="rs_type" id="rs_type">
          <option value="LocalBusiness"<?php echo $type == 'LocalBusiness'?' selected="selected"':'';?>>Local Business</option>
          <option value="Product"<?php echo $type == 'Product'?' selected="selected"':'';?>>Product</option>
        </select></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="rs_schema">Review schema</label></th>
        <td><select name="rs_schema" id="rs_schema">
          <option value="jsonld"<?php echo $format == 'jsonld'?' selected="selected"':'';?>>JSON+LD</option>
          <option value="plain"<?php echo $format == 'plain'?' selected="selected"':'';?>>No schema (disable Rich Results)</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="rs_schema_direct_only" id="rs_schema_direct_only" value="true"<?php echo $schema_direct_only?'checked="checked"':'';?> /><label for="rs_schema_direct_only">Use schema only on reviews collected directly?</label>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="rs_api_token">API token</label></th>
        <td><input type="text" name="rs_api_token" id="rs_api_token" value="<?php echo get_option('rs_api_token');?>" style="min-width: 300px;" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="rs_path"><?php echo $this->brand;?> path or shortname</label></th>
        <td><input type="text" name="rs_path" id="rs_path" value="<?php echo get_option('rs_path');?>" style="min-width: 300px;" /><br /><small>(e.g. for <?php echo $this->brand_domain;?>/yourname use <strong><em>yourname</em></strong>)</small></td>
      </tr>
      <tr valign="top">
        <th scope="row">What to show</th>
        <td><input type="checkbox" name="rs_show_aggregate_rating" id="rs_show_aggregate_rating" value="true"<?php echo $show_aggregate_rating?' checked="checked"':'';?> /><label for="rs_show_aggregate_rating">Aggregate rating</label>
        &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="rs_show_reviews" id="rs_show_reviews" value="true"<?php echo $show_reviews?' checked="checked"':'';?> /><label for="rs_show_reviews">Reviews</label>
        &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="rs_show_powered_by" id="rs_show_powered_by" value="true"<?php echo $show_powered_by?' checked="checked"':'';?> /><label for="rs_show_powered_by">"Powered by" footer</label>
        &nbsp;&nbsp;&nbsp;<br><br>
        <input type="checkbox" name="rs_include_empty" id="rs_include_empty" value="true"<?php echo $include_empty?' checked="checked"':'';?> /><label for="rs_include_empty">Include empty reviews?</label>
        &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="rs_stream_only" id="rs_stream_only" value="true"<?php echo $stream_only?' checked="checked"':''; ?> /><label for="rs_stream_only">Include only approved reviews?</label>
        &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="rs_last_initial" id="rs_last_initial" value="true"<?php echo $last_initial?'checked="checked"':'';?> /><label for="rs_last_initial">Use reviewer's last initial?</label>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="rs_default_count">Default review count</label></th>
        <td><input type="text" name="rs_default_count" id="rs_default_count" value="<?php echo get_option('rs_default_count');?>" /><br /><small>Must be between 1 and 50</small></td>
      </tr>
      <tr valign="top">
        <th scope="row">Display reviews as</th>
        <td><select name="rs_review_display" id="rs_review_display">
          <option value="List"<?php echo $display == 'List'?' selected="selected"':'';?>>List</option>
          <option value="Carousel"<?php echo $display == 'Carousel'?' selected="selected"':''?>>Carousel</option>
        </select></td>
      </tr>
      <tr valign="top">
        <th scope="row">Pagination ("List" view)</th>
        <td><input type="checkbox" name="rs_pager" id="rs_pager" value="true"<?php echo $pager?'checked="checked"':'';?> /><label for="rs_pager">Show previous/next pager</label></td>
      </tr>
    </table>
    <?php @submit_button(); ?>
  </form>
</div>
