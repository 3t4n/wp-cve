<div class="tab-main-container vca-tab-container tab-design-1"> 
    
    <ul class="nav nav-tabs tab-marker tab-marker-left <?php echo $vca_tabs_icon_position; ?>" data-trigger="<?php echo $vca_tabs_trigger; ?>">
        <?php foreach (array_combine($tab_icons_array, $tab_title_array) as $tab_icon => $tab_title): ?>
           <li>
                <a data-toggle="tab" href="" data-mina="ripple"><i class="<?php echo $tab_icon; ?>"></i><strong> <?php echo $tab_title; ?> </strong><span></span></a>
            </li>
        <?php endforeach ?>
    </ul>
    <div class="tab-content tab-content-side">
        <?php do_shortcode( $content ); ?>
    </div>  

</div> 