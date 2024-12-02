<?php
/**
 * Post Grid Block Registration
 *
 * @since 1.0.0
 * @package PostGridBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PostGridBlock_Registration' ) ) {

	/**
	 * Post Grid Block Registration Class
	 *
	 * @since 1.0.0
	 * @package PostGridBlock
	 */
	class PostGridBlock_Registration {

		/**
		 * PostGridBlock_Registration Instance
		 *
		 * @since 1.0.0
		 * @var PostGridBlock_Registration
		 */
		private static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Initialize the Class
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function init() {
			add_action( 'init', array( $this, 'register_blocks' ) );
		}

		/**
		 * Register Blocks
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function register_blocks() {

			$block_dir = POST_GRID_BLOCK_PLUGIN_DIR . 'build/';

			if ( file_exists( $block_dir ) ) {
				register_block_type( $block_dir );
			}
		}

		/**
		 * PostGridBlock_Registration Instance
		 *
		 * @since 1.0.0
		 * @return PostGridBlock_Registration
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

// Initialize the class
PostGridBlock_Registration::get_instance();
