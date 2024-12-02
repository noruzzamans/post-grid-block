<?php
/**
 * Plugin Name: Post Grid Block
 * Description: Enhance your site with dynamic, customizable post grid blocks to create a more engaging and functional experience.
 * Author: 		Noruzzaman
 * Plugin URI: 	https://github.com/noruzzamans/post-grid-block
 * Author URI: 	https://github.com/noruzzamans/
 * Version: 	1.0.0
 * Text Domain: post-grid-block
 * Domain Path: /languages
 * License: 	GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package PostGridBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'PostGridBlock' ) ) {

	/**
	 * PostGridBlock Final Class
	 *
	 * @since 1.0.0
	 * @package PostGridBlock
	 */
	final class PostGridBlock {

		/**
		 * PostGridBlock Instance
		 *
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * PostGridBlock Constructor
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function __construct() {
			$this->define_constants();
			$this->init();
			$this->includes();
		}

		/**
		 * PostGridBlock Define Constants
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function define_constants() {
			if ( ! defined( 'POST_GRID_BLOCK_VERSION' ) ) {
				define( 'POST_GRID_BLOCK_VERSION', '1.0.0' );
			}

			if ( ! defined( 'POST_GRID_BLOCK__FILE__' ) ) {
				define( 'POST_GRID_BLOCK__FILE__', __FILE__ );
			}

			if ( ! defined( 'POST_GRID_BLOCK_URL_FILE' ) ) {
				define( 'POST_GRID_BLOCK_URL_FILE', plugin_dir_url( POST_GRID_BLOCK__FILE__ ) );
			}

			if ( ! defined( 'POST_GRID_BLOCK_PLUGIN_DIR' ) ) {
				define( 'POST_GRID_BLOCK_PLUGIN_DIR', plugin_dir_path( POST_GRID_BLOCK__FILE__ ) );
			}

			if ( ! defined( 'POST_GRID_BLOCK_URL' ) ) {
				define( 'POST_GRID_BLOCK_URL', plugins_url( '/', POST_GRID_BLOCK_PLUGIN_DIR ) );
			}
		}

		/**
		 * PostGridBlock Init
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function init() {
			add_action( 'init', array( $this, 'load_textdomain' ) );
		}

		/**
		 * PostGridBlock Load Text Domain
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'post-grid-block', false, basename( POST_GRID_BLOCK_PLUGIN_DIR ) . '/languages' );
		}

		/**
		 * PostGridBlock Instance
		 *
		 * @since 1.0.0
		 * @return PostGridBlock
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * PostGridBlock Includes Files
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function includes() {
			require_once trailingslashit( POST_GRID_BLOCK_PLUGIN_DIR ) . 'includes/post-grid-block-loader.php';
		}
	}

}

/**
 * PostGridBlock
 *
 * @since 1.0.0
 * @return PostGridBlock
 */
function postgrid_block() {
	return PostGridBlock::get_instance();
}
postgrid_block(); // Initialize the PostGridBlock class
