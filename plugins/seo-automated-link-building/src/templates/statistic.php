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
    <h1 class="wp-heading-inline"><?php _e('Statistic', 'seo-automated-link-building') ?></h1>
    <?php if($hasEntries): ?>
        <a href="#" class="tab-link">30 <?php print _e('Days', 'seo-automated-link-building') ?></a>
        <a href="#" class="tab-link"><?php print _e('Overall', 'seo-automated-link-building') ?></a>
        <div class="tab">
            <div style="position: relative; height: 400px; width: 100%; margin-top: 20px">
                <canvas id="daysChart"></canvas>
            </div>
            <form method="post">
                <?php $list->display() ?>
            </form>
        </div>
        <div class="tab">
            <div style="position: relative; height: 400px; width: 100%; margin-top: 20px">
                <canvas id="bestChart"></canvas>
            </div>
        </div>
    <?php endif ?>
</div>
