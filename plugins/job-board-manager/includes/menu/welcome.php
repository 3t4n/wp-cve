<?php	
if ( ! defined('ABSPATH')) exit;  // if direct access

wp_enqueue_script('welcome-tabs');
wp_enqueue_style( 'welcome-tabs' );


$job_bm_settings_tab = array();


$job_bm_settings_tab[] = array(
    'id' => 'start',
    'title' => sprintf(__('%s Welcome','job-board-manager'),'<i class="far fa-thumbs-up"></i>'),
    'priority' => 1,
    'active' => true,
);

$job_bm_settings_tab[] = array(
    'id' => 'general',
    'title' => sprintf(__('%s General','job-board-manager'),'<i class="fas fa-list-ul"></i>'),
    'priority' => 2,
    'active' => false,
);


$job_bm_settings_tab[] = array(
    'id' => 'create_pages',
    'title' => sprintf(__('%s Create Pages','job-board-manager'),'<i class="far fa-copy"></i>'),
    'priority' => 3,
    'active' => false,
);




$job_bm_settings_tab[] = array(
    'id' => 'done',
    'title' => sprintf(__('%s Done','job-board-manager'),'<i class="fas fa-pencil-alt"></i>'),
    'priority' => 4,
    'active' => false,
);


$job_bm_settings_tab = apply_filters('job_bm_welcome_tabs', $job_bm_settings_tab);

$tabs_sorted = array();
foreach ($job_bm_settings_tab as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
array_multisort($tabs_sorted, SORT_ASC, $job_bm_settings_tab);



wp_enqueue_style('font-awesome-5');









?>
<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div>
    <h2></h2>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', esc_url_raw($_SERVER['REQUEST_URI'])); ?>">
	        <input type="hidden" name="job_bm_hidden" value="Y">
            <?php
            if(!empty($_POST['job_bm_hidden'])){

                $nonce = sanitize_text_field($_POST['_wpnonce']);


                if(wp_verify_nonce( $nonce, 'job_bm_nonce' ) && $_POST['job_bm_hidden'] == 'Y') {


                    do_action('job_bm_welcome_submit', $_POST);

                    ?>

                    <?php


                }
            }else{
                ?>
                <div class="welcome-tabs">
                    <ul class="tab-navs">
                        <?php
                        foreach ($job_bm_settings_tab as $tab){
                            $id = $tab['id'];
                            $title = $tab['title'];
                            $active = $tab['active'];
                            $data_visible = isset($tab['data_visible']) ? $tab['data_visible'] : '';
                            $hidden = isset($tab['hidden']) ? $tab['hidden'] : false;
                            ?>
                            <li <?php if(!empty($data_visible)):  ?> data_visible="<?php echo $data_visible; ?>" <?php endif; ?> class="tab-nav <?php if($hidden) echo 'hidden';?> <?php if($active) echo 'active';?>" data-id="<?php echo $id; ?>"><?php echo $title; ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                    foreach ($job_bm_settings_tab as $tab){
                        $id = $tab['id'];
                        $title = $tab['title'];
                        $active = $tab['active'];
                        ?>

                        <div class="tab-content <?php if($active) echo 'active';?>" id="<?php echo $id; ?>">

                            <?php
                            do_action('job_bm_welcome_tabs_content_'.$id, $tab);
                            ?>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="next-prev">
                        <div class="prev"><span><?php echo sprintf(__('%s Previous','job-board-manager'),'&longleftarrow;')?></span></div>
                        <div class="next"><span><?php echo sprintf(__('Next %s','job-board-manager'),'&longrightarrow;')?></span></div>

                    </div>



                </div>



                <div class="clear clearfix"></div>
                <?Php

            }
            ?>


		</form>
</div>
