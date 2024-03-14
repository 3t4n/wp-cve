<?php
/**
 * @author boxtal <api@boxtal.com>
 * @copyright 2018 Boxtal
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Boxtal\BoxtalPhp\Exception;

use Exception;

/**
 * Class ApiClientException
 * @package Boxtal\BoxtalPhp\Exception
 *
 *  Api client exception.
 */
class ApiClientException extends Exception
{

    public function __construct()
    {
        parent::__construct('Request to Boxtal API failed', 'bad_request');
    }
}
