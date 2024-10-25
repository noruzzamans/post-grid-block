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
			$blocks = [
				'post-grid',
			];

			$build_dir = POST_GRID_BLOCK_PLUGIN_DIR . 'build/blocks/';

			if ( file_exists( $build_dir ) ) {
				foreach ( $blocks as $block ) {
					$block_dir = $build_dir . $block . '/';
					if ( file_exists( $block_dir ) ) {
						register_block_type( $block_dir );
					} else {
						error_log( "Post Grid Block: Build directory for {$block} block does not exist." );
					}
				}
			} else {
				error_log( 'Post Grid Block: Main build directory does not exist.' );
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
