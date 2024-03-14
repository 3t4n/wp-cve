<?php 
if(!function_exists('it_epoll_default_view_reports')){
   
    add_action('it_epoll_results_view_detailed_reports','it_epoll_default_view_reports');
    function it_epoll_default_view_reports(){
        do_action('it_epoll_default_view_reports_filter_form');
        do_action('it_epoll_default_view_voter_details');
    }
}


if(!function_exists('it_epoll_default_view_voter_details_body')){
    add_action('it_epoll_default_view_voter_details','it_epoll_default_view_voter_details_body');
    function it_epoll_default_view_voter_details_body(){
        include_once('results/view_reports.php');
    }
}