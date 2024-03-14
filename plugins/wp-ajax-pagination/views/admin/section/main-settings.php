<?php

if ( ! defined('WPINC')) {
    die;
}

use  AjaxPagination\Admin\Settings;

echo '<h3>'.__('Appearance settings', 'wp-ajax-pagination').' <a href="' .
    wp_kses(esc_url(add_query_arg(array(
        'autofocus' => array(
            'panel' => 'ajax_pagination_settings',
        ),
        'url' => home_url(),
    ), admin_url('customize.php'))), array(
        'a' => array(
            'href' => array(),
            'title' => array(),
        ),
    ))

    . '" aria-label="' . esc_attr__('View Ajax Pagination settings', 'wp-ajax-pagination') . '">' . esc_html__('in the Customizer', 'wp-ajax-pagination') . '</a></h3>';
?>
<table class="form-table ajax-pagonation">
    <tbody>

    <tr>
        <th scope="row"><?php _e('Type of pagination','wp-ajax-pagination') ?></th>

        <td>
            <label>
                <input type="radio" name="<?php echo Settings::OPTIONS; ?>[paginationType]" value="ajax" <?php checked('ajax', $paginationType); ?>>
                <?php
                _e('Ajax pagination', 'wp-ajax-pagination');
                ?>
            </label><br>
            <label>
                <input type="radio" name="<?php echo Settings::OPTIONS; ?>[paginationType]" value="loadmore-ajax" <?php checked('loadmore-ajax', $paginationType); ?>>
                <?php
                _e('"Load more" button and pagination', 'wp-ajax-pagination');
                ?>
            </label><br>
            <label>
                <input type="radio" name="<?php echo Settings::OPTIONS; ?>[paginationType]" value="loadmore" <?php checked('loadmore', $paginationType); ?>>
                <?php
                _e('"Load more" button', 'wp-ajax-pagination');
                ?>
            </label><br>
            <label>
                <input type="radio" name="<?php echo Settings::OPTIONS; ?>[paginationType]" value="infinite-scroll" <?php checked('infinite-scroll', $paginationType); ?>>
                <?php
                _e('Infinite Scroll', 'wp-ajax-pagination');
                ?>
            </label>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php _e('CSS Selectors','wp-ajax-pagination') ?><br>
            <a href="https://processby.com/ajax-pagination-plugin-wordpress/#help" target="_blank" rel="noopener noreferrer"><?php _e('Help','wp-ajax-pagination') ?></a>

        </th>
<!--        <th scope="row">--><?php //_e('Posts Selector','wp-ajax-pagination') ?><!--</th>-->
        <td>
            <div class="col-1">
                <?php _e('Posts Selector','wp-ajax-pagination') ?>
                <div class="posts-selector-wr">
                    <?php
                    $i = 1;
                    foreach ( $postsSelector as $item) { ?>
                        <span class="posts-selector" data-id="<?= $i; ?>" id="post-selector-<?= $i; ?>">
                        <input type="text"  name="<?=Settings::OPTIONS?>[postsSelector][]" value="<?php echo esc_attr( $item ) ?>" required/>
                    </span>
                    <?php
                    $i++;
                    } ?>

                </div>
                <p class="description">
                    <?php _e('The selector that wraps all of the posts.','wp-ajax-pagination') ?>
                </p>
            </div>

            <div class="col-2">
                <?php _e('Navigation Selector','wp-ajax-pagination') ?>
                <div class="navigation-selector-wr">
                    <?php
                    $i = 1;
                    foreach ( $navigationSelector as $item) { ?>
                        <span class="navigation-selector" data-id="<?= $i; ?>" id="<?= $i; ?>">
                   <input type="text"  name="<?=Settings::OPTIONS?>[navigationSelector][]" value="<?php echo esc_attr( $item ) ?>" required/>
                            <?php if($i == 1){
                                echo '<span class="dashicons dashicons-plus-alt add-posts-selector"></span>';
                            }else{
                               echo '<span class="dashicons dashicons-trash remove-posts-selector"></span>';
                            } ?>

                </span>
                        <?php
                        $i++;
                    } ?>
                </div>


                <p class="description">
                    <?php _e('The selector of the navigation.','wp-ajax-pagination') ?>
                </p>
            </div>

        </td>
    </tr>


    <tr>
        <th scope="row"><?php _e('JS Code','wp-ajax-pagination') ?></th>
        <td>
            <textarea cols="64" rows="9" name="<?=Settings::OPTIONS?>[jsCode]"/><?php echo esc_attr( $jsCode ) ?></textarea>
            <p class="description">
                <?php _e('Code that is called after posts are loaded with ajax.','wp-ajax-pagination') ?>
            </p>
        </td>

    </tr>

    <tr>
        <th scope="row"><?php _e('Generate paging URLs','wp-ajax-pagination') ?></th>

        <td>
            <label>
                <input type="checkbox" name="<?php echo Settings::OPTIONS; ?>[pagingUrl]" value="1" <?php checked(true, $pagingUrl); ?>>
                <?php
                _e('enable', 'wp-ajax-pagination');
                ?>
            </label>
        </td>
    </tr>



<!--    <tr>-->
<!--        <th scope="row">--><?php //_e('Sidebar swipe','wp-ajax-pagination') ?><!--</th>-->
<!---->
<!--        <td>-->
<!--            <label>-->
<!--                <input type="checkbox" name="--><?php //echo Settings::OPTIONS; ?><!--[sidebarSwipe]" value="1" --><?php //checked(true, $sidebarSwipe); ?><!-->
<!--                --><?php
//                _e('enable', 'wp-ajax-pagination');
//                ?>
<!--            </label>-->
<!--        </td>-->
<!--    </tr>-->

    </tbody>
</table>
