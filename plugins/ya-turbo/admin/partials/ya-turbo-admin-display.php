<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.1
 *
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/admin/partials
 */

/**
 * @var array $data
 */

?><?php

    // If this file is called directly, abort.
    defined( 'ABSPATH' ) || exit;

    $admin_add = add_query_arg(array(
	    'page' => YATURBO_FEED . '-add'
    ), admin_url('admin.php'));

?><div class="wrap">

	<?php @include YATURBO_PATH . '/admin/partials/ya-turbo-admin-promo.php'; ?>

	<h1 class="wp-heading-inline">
        <?php _e('Yandex Turbo', YATURBO_FEED); ?>
    </h1>

	<a href="<?php print $admin_add; ?>" class="page-title-action">
        <?php _e('Add new', YATURBO_FEED);?>
    </a>

    <hr />

	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
                <th><?php _e( 'Title', YATURBO_FEED ); ?></th>
                <th><?php _e( 'Status', YATURBO_FEED ); ?></th>
                <th><?php _e( 'Slug', YATURBO_FEED ); ?></th>
                <th><?php _e( 'Language', YATURBO_FEED ); ?></th>
                <th><?php _e( 'Limit', YATURBO_FEED ); ?></th>
			</tr>
		</thead>

		<tbody id="the-list">
        <?php if(!$data):?>
            <tr class="no-items">
                <td class="colspanchange" colspan="6">
                    <?php _e('No feeds found', YATURBO_FEED); ?>.
                    <a href="<?php print $admin_add; ?>"><?php _e('Add new', YATURBO_FEED); ?></a>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ( $data as $item ): ?>
                <?php

                    $link = add_query_arg(array(
	                    'feed' => YATURBO_FEED,
	                    'name' => $item->slug
                    ), get_site_url());

                    $view_link =  add_query_arg(array(
	                    'feed' => YATURBO_FEED,
	                    'name' => $item->slug,
                    ), get_site_url());

                    $admin_link = $admin_edit = add_query_arg(array(
	                    'page' => YATURBO_FEED . '-edit',
	                    'id' => $item->id
                    ), admin_url('admin.php'));

		            $admin_del = wp_nonce_url(
		                    admin_url('admin.php?page=' . YATURBO_FEED . '-del&id=' . $item->id),
                            'feed-del',
                            YATURBO_FEED);

                    $status = __( (
                            ( $item->status == YATURBO_FEED_STATUS_ACTIVE )
                                ? 'Active'
                                : 'Disabled' ),
	                        YATURBO_FEED );
                ?>
                <tr>
                    <td>
                        <a class="row-title" href="<?php print $admin_link; ?>">
                            <?php print esc_html( $item->title ); ?>
                        </a>
                        <div class="row-actions">
                            <a href="<?php print $admin_edit; ?>" >
                                <?php _e('Edit', YATURBO_FEED); ?></a> |

                            <span class="trash"><a href="<?php print $admin_del; ?>">
                                    <?php _e('trash', YATURBO_FEED); ?></a></span> |

                            <a href="<?php print $view_link; ?>" class="delete" target="_blank">
                                <?php _e('View', YATURBO_FEED); ?></a>
                        </div>
                    </td>
                    <td><?php print esc_html( $status ); ?></td>
                    <td><?php print esc_url( $link); ?></td>
                    <td><?php print esc_html( $item->language ); ?></td>
                    <td><?php print esc_html( $item->limit ); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
                <th><?php _e( 'Title', YATURBO_FEED ); ?></th>
                <th><?php _e( 'Status', YATURBO_FEED ); ?></th>
                <th><?php _e( 'Slug', YATURBO_FEED ); ?></th>
                <th><?php _e( 'Language', YATURBO_FEED ); ?></th>
                <th><?php _e( 'Limit', YATURBO_FEED ); ?></th>
			</tr>
		</tfoot>
	</table>
</div>