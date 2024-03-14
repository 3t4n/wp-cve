<?php

use WcMipConnector\Manager\ConfigurationOptionManager;

defined('ABSPATH') || exit;

if (empty(ConfigurationOptionManager::getLastStockUpdate())) {
    ConfigurationOptionManager::setLastStockUpdate();
}