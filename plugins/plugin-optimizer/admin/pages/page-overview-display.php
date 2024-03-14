<?php

$tabs = apply_filters( "plgnoptmzr_overview_tabs", $tabs );

$completed_tabs = get_user_meta( get_current_user_id(), "completed_overview_tabs", true );

foreach( $tabs as $index => $tab ){
    
    $tabs[ $index ]["completed"] = in_array( $index, $completed_tabs );
}

$one_tab_opened = false;

foreach( $tabs as $index => $tab ){
    
    $class_completed = $tab["completed"] ? " done" : "";
    $button_complete = $tab["completed"] ? "" : '<div class="tab_complete_wrapper"><button class="po_green_button mark_tab_complete">Mark Complete</button></div>';
    $class_opened    = "trigger_closed";
    $content_opened  = "closed";
    
    if( ! $tab["completed"] && ! $one_tab_opened ){
        $class_opened    = "trigger_opened";
        $content_opened  = "opened";
        $one_tab_opened  = true;
    }
    
    echo <<<EOF

    <div class="tab tab-overview" id="tab_{$index}">
        <div class="tab_header">
            <div class="info-content">
                <span class="info-passage{$class_completed}"></span>
                <span class="title">{$tab["title"]}</span>
            </div>
            <span class="trigger {$class_opened}"></span>
        </div>
        <div class="container hidden-info_overview {$content_opened}" data-id="tab_{$index}">
            <div class="container">
                {$tab["content"]}
                {$button_complete}
            </div>
        </div>
    </div>

EOF;

}
