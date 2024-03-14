<?php

function redirect()
{

}
add_action('wp_ajax_nopriv_redirect', 'redirect');

if(isset($_REQUEST['id']) && isset($_REQUEST['url']))
{
    // get banner
    $banner = data::get_banner( $_REQUEST['id'] );

    // count click
    data::add_stat_clic( $_REQUEST['id'] );

    // redirect
    header(sprintf('Location: %s', $banner->link));

    die();
}
