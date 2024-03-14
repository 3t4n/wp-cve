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
 * @var array $feed
 */

/**
 * @var array $data
 */

/**
 * @var array $error
 */

/**
 * @var array $message
 */

?><?php

    // If this file is called directly, abort.
    defined( 'ABSPATH' ) || exit;

?><div class="wrap">

	<?php @include YATURBO_PATH . '/admin/partials/ya-turbo-admin-promo.php'; ?>

	<h1 class="wp-heading-inline">
        <?php _e('Edit feed', YATURBO_FEED); ?>
    </h1>

    <?php if( !$feed ):?>
        <div class="notice notice-error">
            <ul>
                <li><?php _e( 'Invalid feed id', YATURBO_FEED ); ?></li>
            </ul>
        </div>
    </div><!-- /.wrap -->
    <?php wp_die(); ?>
    <?php endif; ?>

    <?php

    $admin_action = add_query_arg(array(
	    'page' => YATURBO_FEED . '-edit',
        'id' => $feed->id,
    ), admin_url('admin.php'));  ?>

    <?php @include YATURBO_PATH . '/admin/partials/ya-turbo-message.php'; ?>

    <hr />

    <form method="post" class="validate" action="<?php print $admin_action; ?>">

        <input type="hidden" name="id" value="<?php print $feed->id; ?>" />

        <table class="form-table">
            <tbody>

                <!-- Feed slug -->
                <tr>
                    <th scope="row">
                        <label for="slug"><?php _e( 'Slug', YATURBO_FEED ); ?></label>
                    </th>
                    <td>
                        <div class="form-required">
                            <input name="slug" type="text" value="<?php print $feed->slug; ?>" id="slug" class="regular-text" aria-required="true">
                        </div>
                        <p class="description"><?php
	                        print add_query_arg(array(
		                        'feed' => YATURBO_FEED,
		                        'name' => '<b>#slug#</b>',
	                        ), get_site_url()); ?></p>
                    </td>
                </tr>
                <!-- /Feed slug -->

                <!-- Feed title -->
                <tr>
                    <th scope="row">
                        <label for="title"><?php _e( 'Title', YATURBO_FEED ); ?></label>
                    </th>
                    <td>
                        <div class="form-required">
                            <input name="title" type="text" value="<?php print $feed->title; ?>" id="title" class="regular-text">
                        </div>
                    </td>
                </tr>
                <!-- /Feed title -->

                <!-- Feed language -->
                <tr>
                    <th scope="row">
                        <label for="language"><?php _e( 'Language', YATURBO_FEED ); ?></label>
                    </th>
                    <td><input name="language" type="text" id="language" value="<?php print $feed->language; ?>"  class="regular-text"></td>
                </tr>
                <!-- /Feed language -->

                <!-- Feed cache -->
                <tr>
                    <th scope="row">
                        <label for="cache"><?php _e( 'Cache TTL', YATURBO_FEED ); ?></label>
                    </th>
                    <td>
                        <input  name="cache"
                                type="text"
                                id="cache"
                                value="<?php print $feed->settings['cache']; ?>"
                                class="regular-text">
                        <p class="description">
                            <?php _e( 'Cache time to live, minutes', YATURBO_FEED); ?>
                        </p>
                    </td>
                </tr>
                <!-- /Feed cache -->

                <!-- Feed limit -->
                <tr>
                    <th scope="row">
                        <label for="limit"><?php _e( 'Limit records', YATURBO_FEED ); ?></label>
                    </th>
                    <td>
                        <input
                                name="limit"
                                type="text"
                                id="limit"
                                value="<?php print $feed->limit; ?>"
                                class="regular-text" >
                    </td>
                </tr>
                <!-- /Feed limit -->

                <!-- Feed post -->
                <tr>
                    <th scope="row"><?php _e( 'Post types', YATURBO_FEED ); ?></th>
                    <td>
                        <fieldset>
                            <?php foreach ($data['post_types'] as $post_type):?>
                                <?php
                                    $checked = in_array($post_type, $feed->settings['post']);
                                ?>
                                <label>
                                    <input name="post[]"
                                           type="checkbox"
                                           id="<?php print $field;?> "
                                           value="<?php print $post_type;?>"
                                           <?php print ($checked ? 'checked="checked"' : ''); ?> >
	                                <?php print $post_type;?></label>
                            <br>
                            <?php $idx++; ?>
                            <?php endforeach; ?>
                        </fieldset>
                    </td>
                </tr>
                <!-- /Feed post -->

                <!-- Exclude post id -->
                <tr>
                    <th scope="row">
                        <label for="nopostid"><?php _e( 'Exclude post IDs', YATURBO_FEED ); ?></label>
                    </th>
                    <td>
                        <input name="nopostid"
                               type="text"
                               id="nopostid"
                               value="<?php print implode( ',', $feed->settings['nopostid'] ); ?>"
                               class="regular-text">
                        <p class="description"><?php _e('Comma separated post IDs, Example: 111,222,333', YATURBO_FEED ); ?></p>
                    </td>
                </tr>
                <!-- /Exclude post id -->

                <!-- Feed order by -->
                <tr>
                    <th scope="row">
                        <label for="orderby"><?php _e( 'Order by', YATURBO_FEED ); ?></label>
                    </th>
                    <td>
                        <?php $orderby = $feed->settings['orderby']; ?>
                        <select name="orderby" id="orderby">
                            <option value="date" <?php print !("date" == $orderby) ?: $selected; ?>>
                                <?php _e( 'Post date', YATURBO_FEED ); ?></option>

                            <option value="modified" <?php print !("modified" == $orderby) ?: $selected; ?>>
                                <?php _e( 'Date modified', YATURBO_FEED ); ?></option>

                            <option value="rand" <?php print !("rand" == $orderby) ?: $selected; ?>>
                                <?php _e( 'Random', YATURBO_FEED ); ?></option>

                            <option value="id" <?php print !("id" == $orderby) ?: $selected; ?>>
                                <?php _e( '# Id ', YATURBO_FEED ); ?></option>
                        </select>
                    </td>
                </tr>
                <!-- Feed order by -->

                <!-- Feed order -->
                <tr>
                    <th scope="row">
                        <label for="order"><?php _e( 'Order', YATURBO_FEED ); ?></label>
                    </th>
                    <td>
	                    <?php $order = $feed->settings['order']; ?>
                        <select name="order" id="order">
                            <option value="DESC" <?php print !("DESC" == $order) ?: $selected; ?>>
                                <?php _e( 'DESC', YATURBO_FEED ); ?></option>

                            <option value="ASC" <?php print !("ASC" == $order) ?: $selected; ?>>
                                <?php _e( 'ASC', YATURBO_FEED ); ?></option>
                        </select>
                    </td>
                </tr>
                <!-- Feed order -->

                <!-- Feed status -->
                <tr>
                    <th scope="row">
                        <label for="status"><?php _e( 'Status', YATURBO_FEED ); ?></label>
                    </th>
                    <td>
		                <?php $status = $feed->status; ?>
                        <select name="status" id="status">
                            <option
                                    value="<?php print YATURBO_FEED_STATUS_ACTIVE; ?>"
                                    <?php print !(YATURBO_FEED_STATUS_ACTIVE == $status) ?: $selected; ?>>
				                <?php _e( 'Active', YATURBO_FEED ); ?></option>

                            <option
                                    value="<?php print YATURBO_FEED_STATUS_DISABLED; ?>"
		                        <?php print !(YATURBO_FEED_STATUS_DISABLED == $status) ?: $selected; ?>>
		                        <?php _e( 'Disabled', YATURBO_FEED ); ?></option>
                        </select>
                    </td>
                </tr>
                <!-- Feed status -->

            </tbody>
        </table>

        <!-- Feed description -->
        <h2 class="title"><?php _e( 'Description', YATURBO_FEED ); ?></h2>
        <textarea name="description" class="large-text code" rows="3"><?php
          print $feed->description;
        ?></textarea>
        <!-- /Feed description -->

	    <?php wp_nonce_field( YATURBO_FEED ); ?>
        <?php submit_button( __('Save', YATURBO_FEED ) ); ?>
    </form>
</div>