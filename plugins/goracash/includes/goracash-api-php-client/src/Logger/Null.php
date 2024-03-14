<?php
/**
 * Copyright 2015 Goracash
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
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Goracash\Logger;

/**
 * Null logger based on the PSR-3 standard.
 *
 * This logger simply discards all messages.
 */
class Null extends Primary
{
    /**
     * {@inheritdoc}
     */
    public function shouldHandle($level)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function write($message, array $context = array())
    {

    }
}