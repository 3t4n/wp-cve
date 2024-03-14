<h2>Add Navigation Element</h2>
<?php
  wcpt_elm_type_list( 
    apply_filters(
      'wcpt_add_navgiation_header_element_array',
      array(
        'Sort By',
        'Result Count',
        'Results per page',
        'Category Filter',
        'Clear Filters',
        'Price Filter',
        'Search',
        'Apply / Reset [pro]',
        'Text',
        'HTML',
        'Space',
        'Icon [pro]',
        'Media Image [pro]',
        'Tags Filter [pro]',    
        'Attribute Filter [pro]',
        'Custom Field Filter [pro]',
        'Taxonomy Filter [pro]',
        'Availability Filter [pro]',
        'On Sale Filter [pro]',
        'Rating Filter [pro]',
        'Date Picker Filter [pro]',
        'Add Selected To Cart [pro]',
        'Download CSV [pro]',
      )
    ) 
  );
?>
