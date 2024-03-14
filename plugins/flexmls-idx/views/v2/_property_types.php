<?php if ($property_types_selected[0] != ''): ?>

  <div class='flexmls_connect__search_field flexmls_connect__search_property_type flexmls_connect__search_new_property_type
    flexmls_connect__search_new_field_group'>


    <?php
      if ($property_type_enabled == "on" and count($good_prop_types) > 0):
    ?>
      <label class='flexmls_connect__search_new_label'>Property Type</label>
    <?php
      foreach ($good_prop_types as $type):
        if(is_array($user_selected_property_types) && in_array($type, $user_selected_property_types))
          $checked = 'checked="checked"';
        else
          $checked = '';
    ?>
        <input id="property_type_value_<?php echo esc_attr( $type ); ?>" type='checkbox' name='PropertyType[]' value='<?php echo $type; ?>'
          class='flexmls_connect__search_new_checkboxes' <?php echo $checked; ?> >
        <label for="property_type_value_<?php echo esc_attr( $type ); ?>"><?php echo flexmlsConnect::nice_property_type_label($type); ?></label>
        <br>
    <?php
      endforeach;
    else:
    ?>
      <input type='hidden' name='PropertyType' value='<?php echo implode(",", $good_prop_types); ?>' />
    <?php endif; ?>


    <?php //  property sub type ?>

    <?php foreach ($property_sub_types as $property_code => $sub_types): ?>
      <div id="flexmls_connect__search_new_subtypes_for_<?php echo $property_code; ?>"
        class="flexmls_connect__search_new_subtypes">
        <label class='flexmls_connect__search_new_label'>Property Sub Types</label>
        <?php foreach ($sub_types as $sub_type): ?>
            <?php
                /* WP-542: Unchecking criteria from IDX Search Widget In Search Results Doesn't Stay */
                // This creates a list of checkboxes for each sub type *for each property type*
                // This means that the hidden boxes maintain the `checked` attribute, even if the visible list is unchecked
                // This `if` statement only checks a box if the property type *and* sub-property type is checked
                if (
                    in_array($sub_type["Value"], $user_selected_property_sub_types)
                    and in_array($property_code, array_values($user_selected_property_types))
                ) {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
            ?>
          <input type='checkbox' name='PropertySubType[]' value='<?php echo $sub_type["Value"]; ?>' class='flexmls_connect__search_new_checkboxes' <?php echo $checked; ?>>
          <?php echo $sub_type["Name"]; ?><br>
        <?php endforeach;  ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
