<?php
$worklists = get_posts( array(
	'post_type'   => 'plgnoptmzr_work',
	'post_status' => [ 'publish', 'trash' ],
	'numberposts' => - 1,
) );
?>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header("Worklist", "worklist"); ?>
    
    <div class="sos-content">
        <div class="row justify-content-between global-information">
        
            <div class="col-3 left_information">
                <div id="bulk_actions">
                    <select id="check_all_elements">
                        <option value="default">Bulk actions</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button id="btn_apply" class="po_secondary_button">Apply</button>
                </div>
            </div>
            
            <?php SOSPO_Admin_Helper::content_part__filter_options( $worklists ); ?>
            
            <div class="col-3 quantity">
                <span id="all_elements" class="filtered">Published</span> (<span id="count_all_elements"><?php echo wp_count_posts( 'plgnoptmzr_work' )->publish; ?></span>)
                |
                <span id="trash_elements">Trashed</span> (<span id="count_trash_elements"><?php echo wp_count_posts( 'plgnoptmzr_work' )->trash; ?></span>)
            </div>
            
        </div>
        
        <div class="row col-12">
            <div class="col-12">
                <table class="po_table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"></th>
                            <th class="left-10 align-left">Title</th>
                            <th class="left-10 align-left">Permalink</th>
                            <th>Date Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="the-list" class="filter_on__status_publish">
                        <?php SOSPO_Admin_Helper::list_content__works( $worklists ); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
