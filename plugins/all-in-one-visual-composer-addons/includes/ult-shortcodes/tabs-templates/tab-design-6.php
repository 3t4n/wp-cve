<div class="tab-main-container vca-tab-container tab-design-6"> 

    <ul class="nav nav-tabs tab-marker <?php echo $vca_tabs_icon_position; ?>" data-trigger="<?php echo $vca_tabs_trigger; ?>">
        <?php foreach (array_combine($tab_icons_array, $tab_title_array) as $tab_icon => $tab_title): ?>
           <li>
                <a data-toggle="tab" href="" data-mina="ripple"><i class="<?php echo $tab_icon; ?>"></i><strong> <?php echo $tab_title; ?> </strong>
                    <svg viewBox="0 0 73 68" preserveAspectRatio="xMinYMin meet">
                        <path d="M0 0v68.1h72.67c0 0-13.75-4.75-26.25-14.25S24.37 30.85 17.92 21C13.04 14.21 4.08 2 0 0z"></path>
                    </svg>
                </a> 
            </li>
        <?php endforeach ?>
    </ul>
    <div class="tab-content">
        <?php do_shortcode( $content ); ?>
    </div>  

</div> 