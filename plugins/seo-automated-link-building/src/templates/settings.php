<?php
/**
 * Internal Links Manager
 * Copyright (C) 2021 webraketen GmbH
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You can read the GNU General Public License here: <https://www.gnu.org/licenses/>.
 * For questions related to this program contact post@webraketen-media.de
 */
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php print $settingsHeadline ?></h1>
    <form method="post" action="<?php print $adminPostUrl ?>">
        <table class="form-table">
            <tbody>
                <tr>
                    <th><?php print $whitelistHeadline ?></th>
                    <td>
                        <textarea rows="5" name="whitelist" placeholder="<?php print $whitelistHeadline ?>"><?php print $whitelist ?></textarea>
                        <p class="description"><?php print $whitelistDescription ?></p>
                        <p class="description"><?php print $inputDescription ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php print $blacklistHeadline ?></th>
                    <td>
                        <textarea rows="5" name="blacklist" placeholder="<?php print $blacklistHeadline ?>"><?php echo $blacklist ?></textarea>
                        <p class="description"><?php print $blacklistDescription ?></p>
                        <p class="description"><?php print $inputDescription ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php print $postTypesHeadline ?></th>
                    <td>
                    <p class="posttypes-chooser"><?php print $postTypesLabel ?>: <?php print join(', ', array_map(function($item) {
                        return "<code class='post-type-option'>$item</code>";
                    }, $availablePostTypes)) ?></p>
                    </p>
                        <textarea rows="5" name="posttypes" placeholder="<?php print $postTypesHeadline ?>"><?php print $postTypes ?></textarea>
                        <p class="description"><?php print $postTypesDescription ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php print $excludeHeadline ?></th>
                    <td>
                        <textarea rows="5" name="exclude" placeholder="<?php print $excludeExample ?>"><?php print $exclude ?></textarea>
                        <p class="description"><?php print $excludeDescription ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php print $disableStatisticsHeadline ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="disableStatistics" <?php if($disableStatistics): ?>checked<?php endif; ?> />
                            <?php print $disableStatisticsDescription ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th><?php print $disableAdminTrackingHeadline ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="disableAdminTracking" <?php if($disableAdminTracking): ?>checked<?php endif; ?> />
                            <?php print $disableAdminTrackingDescription ?>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="action" value="seo_automated_link_building_settings" />
        <?php wp_nonce_field( 'seo_automated_link_building_settings', 'nonce' ); ?>
        <input type="submit" class="button button-primary" value="<?php _e('Save') ?>">
    </form>
</div>
