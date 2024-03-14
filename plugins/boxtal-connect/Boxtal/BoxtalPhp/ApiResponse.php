<?php
namespace Boxtal\BoxtalPhp;

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

/**
 * Class ApiResponse
 * @package Boxtal\BoxtalPhp
 *
 *  Api Response.
 */
class ApiResponse
{

    public $status;
    public $response;

    public function __construct($status, $response)
    {
        $this->status = $status;
        $this->response = $response;
    }

    public function isError()
    {
        return 0 !== strpos($this->status, '2');
    }
}
