<?php

namespace Watchful\App;

interface Alert
{
    const LEVEL_INFO = 1;
    const LEVEL_ERROR = 2;

    /** @return string */
    public function getMessage();

    /** @return int */
    public function getLevel();

    /** @return string|null */
    public function getParameter1();

    /** @return string|null */
    public function getParameter2();

    /** @return string|null */
    public function getParameter3();
}
