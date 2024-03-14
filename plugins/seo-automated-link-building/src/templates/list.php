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
    <h1 class="wp-heading-inline"><?php print $linksHeadline ?></h1>
    <a href="admin.php?page=seo-automated-link-building-add-link" class="page-title-action"><?php print $addNewHeadline ?></a>
    <hr class="wp-header-end">
    <h2 class="screen-reader-text">Seitenliste filtern</h2>
    <ul class="subsubsub">
        <li class="all"><a href="?page=seo-automated-link-building-all-links" <?php if(!$hasActiveFlag): ?>class="current"<?php endif ?>><?php print $allTitle ?> <span class="count">(<?php print $linksCount ?>)</span></a> |</li>
        <li class="active"><a href="?page=seo-automated-link-building-all-links&active=1" <?php if($onlyActive): ?>class="current"<?php endif ?>><?php print $activeTitle ?> <span class="count">(<?php print $activeLinksCount ?>)</span></a> |</li>
        <li class="deactivated"><a href="?page=seo-automated-link-building-all-links&active=0" <?php if($onlyInactive): ?>class="current"<?php endif ?>><?php print $inactiveTitle ?> <span class="count">(<?php print $inactiveLinksCount ?>)</span></a></li>
    </ul>
    <form method="post">
        <?php $list->display() ?>
    </form>
</div>
