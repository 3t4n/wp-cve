<?php
/*****************************************************************

  Page template for IDX Search Widget, Version 2

******************************************************************/
?>

<?php echo $before_widget; ?>

<div class="flexmls_connect__search flexmls-v2-widget <?php echo esc_attr( $wrapper_class ); ?>
  flexmls_connect__search_v2_<?php echo $orientation; ?>"
  style="
    color: <?php echo $field_text_color; ?>;
    max-width: <?php echo $width; ?>px;
    <?php if ( $field_font ) : ?>
      font-family: <?php echo $field_font; ?>, sans-serif;
      <?php endif; ?>
    <?php echo $border_radius; ?>
    background-color: <?php echo $background_color ?>;
  ">

  <?php if ($destination == "remote") { ?>
    <form action='<?php echo $_SERVER['REQUEST_URI'] ?>' method='post' <?php echo $this_target ?> >
  <?php } else { ?>
    <form action="<?php echo flexmlsConnect::make_nice_tag_url('search'); ?>" method='get'
      <?php echo $this_target; ?> >
  <?php } ?>

    <?php // title ?>
    <div class='flexmls_connect__search_v2_title' style="color: <?php echo $title_text_color; ?>;
      <?php if ( $title_font ) : ?>
        font-family: <?php echo $title_font; ?>, sans-serif;
      <?php endif; ?>
      ">
      <?php echo $title; ?>
    </div>

    <?php
      // property types for vertical layout
      if($orientation == 'vertical') { require(__DIR__ . '/_property_types.php'); }
    ?>
    <?php if($default_view == "map"){   ?>
    <input type="hidden" name="view" value="map" />
    <?php } ?>
    <?php // Location Search ?>

    <?php if ($location_search == "on") { ?>
      <div class='flexmls_connect__search_field location'>
        <label>Location</label>
        <select class="flexmlsLocationSearch" data-portal-slug="<?= $portal_slug ?>" multiple="true">
          <?php
            foreach ($location_fields as $field => $value) {
              $option_value = $field . '_' . stripslashes($value);
              $displayName = $value . " ($field)";

              echo '<option value="' . $option_value . '" selected="selected">' . stripslashes($displayName) . '</option>';
            }
          ?>
        </select>
      </div>
    <?php
        $search_fields[] = "Location";
      }
    ?>
  <div class="flexmls_connect__filters_wrapper">
      <?php if ($std_fields_selected[0] != '') { ?>

        <div class='flexmls_connect__search_v2_min_max flexmls_connect__search_v2_field_group'>

          <?php

            foreach ($std_fields_selected as $fi) {

              fmcSearch::create_min_max_row($fi);

            }
          ?>
        </div>
      <?php } ?>

  <div class="flexmls_connect__righthand_filters_wrapper">
      <?php
        // property types for horizontal layout
        if($orientation == 'horizontal' || empty( $orientation ) ) { require(__DIR__ . '/_property_types.php'); }
      ?>

      <?php if ($destination == "local" and $user_sorting == "on") { ?>

        <div class='flexmls_connect__search_field flexmls_connect__search_v2_sort_by
          flexmls_connect__search_v2_field_group'>
          <label>Sort By</label>
          <select name='OrderBy' size='1'>
            <option value='-ListPrice'>List price (High to Low)</option>
            <option value='ListPrice'>List price (Low to High)</option>
            <option value='-BedsTotal'># Bedrooms</option>
            <option value='-BathsTotal'># Bathrooms</option>
            <option value='-YearBuilt'>Year Built</option>
            <option value='-BuildingAreaTotal'>Square Footage</option>
            <option value='-ModificationTimestamp'>Recently Updated</option>
          </select>
        </div>
      <?php } ?>

  <?php if($allow_sold_searching == "on" || $allow_pending_searching == "on") : ?>
        <div class='flexmls_connect__search_field flexmls_connect__search_v2_field_group'>
          <label class='flexmls_connect__search_v2_label'>Listing Status</label>
  <div class='flexmls_sold_pending_search_wrapper'>
          <input id="flexmls_status_active" type='checkbox' name='StandardStatus[]' value='Active'
            class='flexmls_connect__search_v2_checkboxes' checked="checked" > <label for="flexmls_status_active">Active</label>
        <?php if($allow_sold_searching == "on"){?>
          <input id="flexmls_status_closed" type='checkbox' name='StandardStatus[]' value='Closed'
            class='flexmls_connect__search_v2_checkboxes'> <label for="flexmls_status_closed">Sold</label>
        <?php } ?>
      <?php if($allow_pending_searching == "on"){?>
          <input id="flexmls_status_pending" type='checkbox' name='StandardStatus[]' value='Pending'
            class='flexmls_connect__search_v2_checkboxes'> <label for="flexmls_status_pending">Pending</label>
      <?php } ?>
  </div>
        </div>
      <?php endif; ?>
  </div>
  </div>

    <?php echo $submit_return; ?>

  </form>
</div>

<?php echo $after_widget; ?>
