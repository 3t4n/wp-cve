<div class="tab-main-container vca-tab-container tab-design-8"> 

    <ul class="nav nav-tabs tab-marker <?php echo $vca_tabs_icon_position; ?> margin-bottom-half" data-trigger="<?php echo $vca_tabs_trigger; ?>">
        <?php foreach (array_combine($tab_icons_array, $tab_title_array) as $tab_icon => $tab_title): ?>
           <li>
                <a data-toggle="tab" href=""><i class="<?php echo $tab_icon; ?>"></i><strong> <?php echo $tab_title; ?> </strong> 
                </a> 
            </li>
        <?php endforeach ?>
    </ul>
    <div class="tab-content rounded overflow-visible" style="clear: both;">
        <?php do_shortcode( $content ); ?>
    </div>  

</div> 