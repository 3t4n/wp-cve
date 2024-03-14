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
    <h1 class="wp-heading-inline"><?php print $importHeadline ?></h1>
    <form id="import-form" method="post" action="<?php print $adminPostUrl ?>" class="seo-automated-link-building-form dropzone">
        <div>
            <input type="radio" name="mode" value="addMissing" id="addMissing" checked />
            <label for="addMissing"><?php print $addMissingDescription ?></label>
        </div>
        <div>
            <input type="radio" name="mode" value="update" id="update" />
            <label for="update"><?php print $addMissingAndUpdateDescription ?></label>
        </div>
        <div>
            <input type="radio" name="mode" value="add" id="add" />
            <label for="add"><?php print $addAlwaysDescription ?></label>
        </div>
        <input type="hidden" name="action" value="seo_automated_link_building_import_links" />
        <?php wp_nonce_field( 'seo_automated_link_building_import_links', 'nonce' ); ?>
    </form>
    <h1 class="wp-heading-inline"><?php print $exportHeadline ?></h1>
    <div>
        <input type="radio" name="ext" value="csv" id="csv" checked />
        <label for="csv">CSV</label>
    </div>
    <div>
        <input type="radio" name="ext" value="json" id="json" />
        <label for="json">JSON</label>
    </div>
    <div>
      <a class="export"><?php print $exportDescription ?></a>
    </div>
</div>
