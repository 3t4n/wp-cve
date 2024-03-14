<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * Modified by __root__ on 06-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Mailster\Gmail\Google\Service\Gmail;

class WatchResponse extends \Mailster\Gmail\Google\Model
{
  /**
   * @var string
   */
  public $expiration;
  /**
   * @var string
   */
  public $historyId;

  /**
   * @param string
   */
  public function setExpiration($expiration)
  {
    $this->expiration = $expiration;
  }
  /**
   * @return string
   */
  public function getExpiration()
  {
    return $this->expiration;
  }
  /**
   * @param string
   */
  public function setHistoryId($historyId)
  {
    $this->historyId = $historyId;
  }
  /**
   * @return string
   */
  public function getHistoryId()
  {
    return $this->historyId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(WatchResponse::class, 'Google_Service_Gmail_WatchResponse');
