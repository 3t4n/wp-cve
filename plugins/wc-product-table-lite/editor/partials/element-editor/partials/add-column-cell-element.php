<h2>Add Element To Cell Template</h2>
<?php
  wcpt_elm_type_list( 
    apply_filters(
      'wcpt_add_column_cell_element_array',
      array(
        'Title',
        'Product Image',
        'Price',
        'Rating',
        'Short description',
        'Content',
        'Quantity',
        'Button',
        'Remove [pro]',    
        'Custom Field',
        'Attribute',
        'Tags',
        'Taxonomy [pro]',    
        'Property List [pro]',
        'ToolTip [pro]',
        'Shortcode [pro]',
        'Select Variation [pro]',
        'Cart Form [pro]',
        'Checkbox [pro]',
        'Total [pro]',
        'Gallery [pro]',
        'On Sale [pro]',
        'Availability [pro]',
        'Dimensions [pro]',
        'Stock',
        'SKU',
        'Product ID',
        'Category',
        'Product Link',
        'Date',
        'Line separator',
        'Text__Col',
        'HTML__Col',
        'Space__Col',
        'Dot__Col',
        'Icon__Col [pro]',
        'Media Image__Col [pro]',
      )
    ) 
  );

  do_action('wcpt_after_add_column_cell_element_buttons');
?>
