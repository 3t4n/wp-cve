<?php
/**
 * Core component
 *
 * @package    BoldBlocks
 * @author     Phi Phan <mrphipv@gmail.com>
 * @copyright  Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( CoreComponent::class ) ) :
	/**
	 * Create/edit custom content blocks.
	 */
	abstract class CoreComponent {
		/**
		 * The plugin instance
		 *
		 * @var ContentBlocksBuilder
		 */
		protected $the_plugin_instance;

		/**
		 * A constructor
		 */
		public function __construct( $the_plugin_instance ) {
			$this->the_plugin_instance = $the_plugin_instance;
		}

		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		abstract public function run();
	}
endif;
