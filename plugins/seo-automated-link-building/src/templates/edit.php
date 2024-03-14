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
    <h1 class="wp-heading-inline"><?php echo $pageTitle ?></h1>
    <form method="post" action="<?php print $adminPostUrl ?>" class="seo-automated-link-building-form">
        <table class="form-table">
            <tbody>
                <tr>
                    <th><?php print $internalTitleHeadline ?>*</th>
                    <td>
                        <input type="text" name="title" placeholder="<?php print $internalTitleHeadline ?>" value="<?php print $title ?>" />
                        <p class="description"><?php print $internalTitleDescription ?></p>
                    </td>
                </tr>
                <tr style="display: <?php print $shouldDisplayPageInput ? 'table-row' : 'none' ?>">
                    <th><?php print $pageHeadline ?>*</th>
                    <td>
                        <div id="page-result">
                            <span class="text"></span>
                            <span id="page-result-exit">&times;</span>
                        </div>
                        <div id="no-page-result">
                            <input type="text" name="pagesearch" placeholder="<?php print $pageHeadline ?>" />
                            <input type="hidden" name="page" value="<?php print $$pageId ?>" />
                            <p class="description"><?php print $pageDescription ?></p>
                            <br />
                            <a href="#" id="useCustomUrl"><?php print $urlSwitch ?></a>
                        </div>
                    </td>
                </tr>
                <tr style="display: <?php print $shouldDisplayPageInput ? 'none' : 'table-row' ?>">
                    <th><?php print $urlHeadline ?>*</th>
                    <td>
                        <input type="text" name="url" placeholder="<?php print $urlHeadline ?>" value="<?php print $url ?>" />
                        <p class="description"><?php print $urlDescription ?></p>
                        <br />
                        <a href="#" id="useWebsitePage"><?php print $pageSwitch ?></a>
                    </td>
                </tr>
                <tr>
                    <th><?php print $keywordsHeadline ?>*</th>
                    <td>
                        <div id="seo-automated-link-building-keywords"></div>
                        <p class="description"><?php print $keywordsDescription ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php print $caseSensitiveHeadline ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="case_sensitive" <?php if($caseSensitive): ?>checked<?php endif; ?>>
                            <?php print $caseSensitiveDescription ?>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <br />
        <h2><?php print $settingsHeadline ?></h2>
        <table class="form-table">
            <tbody>
            <tr>
                <th><?php print $priorityHeadline ?></th>
                <td>
                    <input type="number" name="priority" placeholder="<?php print $priorityHeadline ?>" value="<?php print $priority ?>" />
                    <p class="description"><?php print $priorityDescription ?></p>
                </td>
            </tr>
            <tr>
                <th><?php print $titleattrHeadline ?></th>
                <td>
                    <input type="text" name="titleattr" placeholder="<?php print $titleattrHeadline ?>" value="<?php print $titleattr ?>" />
                    <p class="description"><?php print $titleattrDescription ?></p>
                    <br>
                    <label>
                        <input type="checkbox" name="notitle" <?php if($notitle): ?>checked<?php endif; ?> />
                        <?php print $notitleDescription ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php print $numberOfLinksHeadline ?></th>
                <td>
                    <input type="number" name="num" min="-1" placeholder="<?php print $numberOfLinksHeadline ?>" value="<?php print $num ?>" />
                    <input type="button" value="<?php print $unlimitiedHint ?>" class="button numToUnlimited" />
                    <p class="description"><?php print $numberOfLinksDescription ?></p>
                </td>
            </tr>
            <tr>
                <th><?php print $followHeadline ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="follow" <?php if($follow): ?>checked<?php endif; ?>>
                        <?php print $followDescription ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php print $targetHeadline ?></th>
                <td>
                    <div>
                        <label>
                            <input type="radio" name="target" value="_self" <?php if($target === '_self'): ?>checked<?php endif; ?>>
                            <?php print $targetSameTabDescription ?>
                        </label>
                    </div>
                    <br>
                    <div>
                        <label>
                            <input type="radio" name="target" value="_blank" <?php if($target === '_blank'): ?>checked<?php endif; ?>>
                            <?php $targetNewTabDescription ?> <code>( target="_blank" )</code>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <th><?php print $partialReplacementHeadline ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="partly_match" <?php if($partlyMatch): ?>checked<?php endif; ?> />
                        <?php print $partialReplacementDescription ?>
                    </label>
                </td>
            </tr>
            </tbody>
        </table>
        <br />
        <?php if($id): ?>
        <input type="hidden" name="id" value="<?php print $id ?>" />
        <?php endif; ?>
        <input type="hidden" name="action" value="seo_automated_link_building_add_link" />
        <?php wp_nonce_field( 'seo_automated_link_building_add_link', 'nonce' ); ?>
        <input type="submit" class="button button-primary" value="<?php print $saveTitle ?>" />
    </form>
</div>
