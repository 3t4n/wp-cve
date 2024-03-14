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

namespace Goracash\Http;

class Response
{
    public $url = null;
    public $method = null;
    public $requestHeaders = array();

    public $code = 0;
    public $status = 0;
    public $contentType = null;
    public $headers = array();
    public $body = null;

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeader($name)
    {
        $name = static::normalizeHeader($name);
        if (array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        }
        return null;
    }

    public static function normalizeHeader($name)
    {
        $name = trim($name);
        $name = strtr(strtolower($name), '-', ' ');
        $name = strtr(ucwords($name), ' ', '-');
        return $name;
    }

    public function setRequestHeaders($headers)
    {
        $headers = explode("\n", trim($headers));
        // Skip first line
        for ($i = 1, $len = count($headers); $i < $len; $i++) {
            list($name, $value) = explode(':', $headers[$i], 2);
            $name = self::normalizeHeader($name);
            $this->requestHeaders[$name] = trim($value);
        }
    }

    public function setContentType($contentType)
    {
        $value = explode(';', $contentType);
        $this->contentType = $value[0];
    }

    public function length()
    {
        $length = $this->getHeader('Content-Length');
        if (!$length) {
            $length = strlen($this->body);
        }
        return $length;
    }

}