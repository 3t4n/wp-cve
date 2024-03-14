<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 



wp_enqueue_style( 'font-awesome-5' );
//wp_enqueue_script( 'settings-tabs' );
wp_enqueue_style( 'settings-tabs' );
wp_enqueue_script('chart.js');




$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'job_posting';


$stats_tabs = array();

$stats_tabs[] = array(
    'id' => 'job_posting',
    'title' => sprintf(__('%s Job Posting','job-board-manager'),'<i class="fas fa-pencil-ruler"></i>'),
    'priority' => 1,
    'active' => ($tab == 'job_posting') ? true : false,
);



$stats_tabs[] = array(
    'id' => 'application',
    'title' => sprintf(__('%s Application','job-board-manager'),'<i class="fas fa-envelope-open-text"></i>'),
    'priority' => 2,
    'active' => ($tab == 'application') ? true : false,
);
//
//$stats_tabs[] = array(
//    'id' => 'invitation',
//    'title' => sprintf(__('%s Invitation','job-board-manager'),'<i class="far fa-copy"></i>'),
//    'priority' => 2,
//    'active' => ($tab == 'invitation') ? true : false,
//);
//
//$stats_tabs[] = array(
//    'id' => 'resume',
//    'title' => sprintf(__('%s Resume','job-board-manager'),'<i class="far fa-copy"></i>'),
//    'priority' => 2,
//    'active' => ($tab == 'resume') ? true : false,
//);

$stats_tabs = apply_filters('job_bm_stats_tabs', $stats_tabs);

$tabs_sorted = array();
foreach ($stats_tabs as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
array_multisort($tabs_sorted, SORT_ASC, $stats_tabs);



?>
<div class="wrap">
    <h2><?php echo __('Job Board Manager - Stats','job-board-manager'); ?></h2><br>

    <div class="settings-tabs vertical">

        <ul class="tab-navs">
            <?php
            foreach ($stats_tabs as $tab){
                $id = $tab['id'];
                $title = $tab['title'];
                $active = $tab['active'];
                $data_visible = isset($tab['data_visible']) ? $tab['data_visible'] : '';
                $hidden = isset($tab['hidden']) ? $tab['hidden'] : false;
                ?>
                <li <?php if(!empty($data_visible)):  ?> data_visible="<?php echo $data_visible; ?>" <?php endif; ?> class="tab-nav <?php if($hidden) echo 'hidden';?> <?php if($active) echo 'active';?>" data-id="<?php echo $id; ?>">
                    <a href="<?php echo admin_url().'edit.php?post_type=job&page=job_bm_stats&tab='.$id;?>"><?php echo $title; ?></a>

                </li>
                <?php
            }
            ?>
        </ul>



        <?php
        foreach ($stats_tabs as $tab){
            $id = $tab['id'];
            $title = $tab['title'];
            $active = $tab['active'];
            ?>

            <div class="tab-content <?php if($active) echo 'active';?>" id="<?php echo $id; ?>">
                <?php //echo $id; ?>

                <?php
                do_action('job_bm_stats_tabs_content_'.$id, $tab);
                ?>


            </div>

            <?php
        }
        ?>

    </div>


</div>


<style type="">

    .settings-tabs .tab-navs {
        background: #fafafa;

    }
    .settings-tabs .tab-nav {
        background: #ececec !important;

    }

    .settings-tabs .tab-nav.active {
        background: #fafafa !important;
    }

    .settings-tabs .tab-nav a {
        display: block;
        text-decoration: none;

    }

    .date-range{
        padding: 15px 20px;
        margin-bottom:15px ;
    }
    .date-range a{
        padding: 5px 10px;
        text-decoration: none;
        margin: 0px 0 5px 0;
        display: inline-block;
    }
    .date-range a.active{
        padding: 5px 10px;
    }


    .date-range .active{
        background: #ececec;
        padding: 2px 10px;
        border-radius: 3px;
        border: 1px solid #d7d8da;
    }

    .date-range-custom{}

    .date-range-custom input[type="text"]{
        width: 130px !important;
    }

</style>
		
		
		