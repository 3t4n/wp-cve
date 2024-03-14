<?php
// ===============  [tab_group ] ======================
function wvr_compat_do_tab_group( $args, $content ) {
    extract( shortcode_atts( array(
	'border_color' => '',		// tab and pane bodder color - default #888
	'tab_bg' => '',			// normal bg color of tab (default #CCC)
	'tab_selected_color' => '',	// color of tab when selected (default #EEE)
	'pane_min_height' => '',	// min height of a pane to help make all even if needed
	'pane_bg' => ''			// bg color of pane
    ), $args ) );

    if (isset($GLOBALS['wvr_compat_in_tab_container']) && $GLOBALS['wvr_compat_in_tab_container']) {
	return __('<strong>Sorry, you cannot nest tab_containers.</strong>','weaver-xtreme' /*adm*/);
    }

    // enqueue the theme support jslib only now when it will actually be needed!

    if ( !isset( $GLOBALS['wvr_compat_tab_id'] ) )
        $GLOBALS['wvr_compat_tab_id'] = 1;
    else
        ++$GLOBALS['wvr_compat_tab_id'];

    $group_id = 'wvr-tab-group-' . $GLOBALS['wvr_compat_tab_id'];

    $css = '';	// default styles
    $add_style = '';
    if ($border_color != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-pane,#' .
            $group_id . '.wvr-tabs-style .wvr-tabs-nav span {border-color:' . $border_color . ";}\n";

    if ($pane_min_height != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-pane {min-height:' . $pane_min_height . ";}\n";

    if ($pane_bg != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-pane {background-color:' . $pane_bg . ";}\n";

    if ($tab_bg != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-nav span {background-color:' . $tab_bg . ";}\n";

    if ($tab_selected_color != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-nav span.wvr-tabs-current,#' .
            $group_id . '.wvr-tabs-style .wvr-tabs-nav span:hover {background-color:' . $tab_selected_color . ";}\n";

    if ($css != '') {	// specified some style...
        $add_style = "<style type=\"text/css\">\n" . $css . "</style>\n";
    }

    $GLOBALS['wvr_compat_in_tab_container'] = true;
    $GLOBALS['wvr_compat_num_tabs'] = 0;

    do_shortcode( $content );	// process the tabs on this

    $out = '*** Unclosed or mismatched [tab_group] shortcodes ***';

    if ( isset( $GLOBALS['wvr_compat_tabs'] ) && is_array( $GLOBALS['wvr_compat_tabs'] ) ) {
        foreach ( $GLOBALS['wvr_compat_tabs'] as $tab ) {
            $tabs[] = '<span>' . $tab['title'] . '</span>'. "\n";
            $panes[] = "\n" .'<div class="wvr-tabs-pane">' . $tab['content'] . '</div>';
        }
        $out = '<div id="' . $group_id . '" class="wvr-tabs wvr-tabs-style"> <!-- tab_group -->' . "\n"
            . '<div class="wvr-tabs-nav">' . "\n"
            . implode( '', $tabs ) . '</div>' . "\n"
            . '<div class="wvr-tabs-panes">'
            . implode( '', $panes ) . "\n"
            . '</div><div class="wvr-tabs-clear"></div>' . "\n"
            . '</div> <!-- end tab_group -->' . "\n";
    }

    // Forget globals we generated
    unset( $GLOBALS['wvr_compat_in_tab_container'],$GLOBALS['wvr_compat_tabs'],$GLOBALS['wvr_compat_num_tabs']);

    return $add_style . $out;
}

function wvr_compat_do_tab( $args, $content ) {
    extract( shortcode_atts( array(
	'title' => 'Tab %d'
    ), $args ) );

    if ( ! isset( $GLOBALS['wvr_compat_num_tabs'] ) ) {
        $GLOBALS['wvr_compat_num_tabs'] = 0;
    }
    $cur = $GLOBALS['wvr_compat_num_tabs'];
    $GLOBALS['wvr_compat_tabs'][$cur] = array(
        'title' => sprintf( $title, $GLOBALS['wvr_compat_num_tabs'] ),		// the title with number
        'content' => do_shortcode( $content ) );
    $GLOBALS['wvr_compat_num_tabs']++;
}

?>
