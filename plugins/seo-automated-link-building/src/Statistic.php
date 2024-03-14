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

namespace SeoAutomatedLinkBuilding;


use wp_activerecord\ActiveRecord;

/**
 * @property integer id
 * @property integer link_id
 * @property string title
 * @property string source_url
 * @property string destination_url
 * @property DateTime created_at
 */
class Statistic extends ActiveRecord
{
    protected static $table_name = 'seo_automated_link_building_statistic';

    protected static $casts = [

    ];
}
