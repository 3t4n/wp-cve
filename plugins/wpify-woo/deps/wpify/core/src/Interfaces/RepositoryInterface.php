<?php

namespace WpifyWooDeps\Wpify\Core\Interfaces;

use WpifyWooDeps\Doctrine\Common\Collections\ArrayCollection;
/**
 * @package Wpify\Core
 */
interface RepositoryInterface
{
    public function all() : ArrayCollection;
    public function get($id);
}
