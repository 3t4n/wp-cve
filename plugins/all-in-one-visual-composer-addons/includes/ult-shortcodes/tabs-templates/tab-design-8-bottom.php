    
<div class="tab-main-container vca-tab-container tab-design-8"> 
    <div class="tab-content rounded overflow-visible">
        <?php do_shortcode( $content ); ?>
    </div> 
    <ul class="nav nav-tabs tab-marker tab-marker-bottom <?php echo $vca_tabs_icon_position; ?> margin-top-half" data-trigger="<?php echo $vca_tabs_trigger; ?>">
        <?php foreach (array_combine($tab_icons_array, $tab_title_array) as $tab_icon => $tab_title): ?>
           <li>
                <a data-toggle="tab" href="" data-mina="ripple"><i class="<?php echo $tab_icon; ?> fa-fg"></i><strong> <?php echo $tab_title; ?> </strong>
                </a> 
            </li>
        <?php endforeach ?> 
    </ul> 
     

</div> 