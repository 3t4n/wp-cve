<?php

namespace Baqend\WordPress\Controller;

use Baqend\WordPress\Loader;
use Baqend\WordPress\Plugin;
use Psr\Log\LoggerInterface;

/**
 * Class Controller created on 19.07.17.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Controller
 */
abstract class Controller {

    /**
     * @var Plugin
     */
    protected $plugin;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct( Plugin $plugin, LoggerInterface $logger ) {
        $this->plugin = $plugin;
        $this->logger = $logger;
    }

    /**
     * Registers methods of this controller on a given loader.
     *
     * @param Loader $loader The loader to register.
     */
    public function register( Loader $loader ) {
    }
}
