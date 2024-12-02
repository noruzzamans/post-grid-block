<?php
/**
 * Post Grid Block Loader
 *
 * @since 1.0.0
 * @package PostGridBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'PostGridBlockLoader' ) ) {

	/**
	 * Post Grid Block Loader Class
	 *
	 * @since 1.0.0
	 * @package PostGridBlock
	 */
	class PostGridBlockLoader {

		/**
		 * PostGridBlockLoader Instance
		 *
		 * @since 1.0.0
		 * @var PostGridBlockLoader
		 */
		private static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function __construct() {
			$this->includes();
		}

		/**
		 * Include Files
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function includes() {
			require_once trailingslashit( POST_GRID_BLOCK_PLUGIN_DIR ) . '/includes/classes/register-blocks.php';
			require_once trailingslashit( POST_GRID_BLOCK_PLUGIN_DIR ) . '/includes/classes/post-grid-block-enqueues.php';
			require_once trailingslashit( POST_GRID_BLOCK_PLUGIN_DIR ) . '/includes/Helpers/post-grid-block-helpers.php';


		}

		/**
		 * PostGridBlockLoader Instance
		 *
		 * @since 1.0.0
		 * @return PostGridBlockLoader
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

// Initialize the loader class
PostGridBlockLoader::get_instance();
