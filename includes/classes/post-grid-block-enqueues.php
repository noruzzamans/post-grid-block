<?php
/**
 * Post Grid Block Enqueues
 *
 * @since 1.0.0
 * @package PostGridBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PostGridBlock_Enqueues' ) ) {

	/**
	 * Post Grid Block Enqueues Class
	 *
	 * @since 1.0.0
	 * @package PostGridBlock
	 */
	class PostGridBlock_Enqueues {

		/**
		 * PostGridBlock_Enqueues Instance
		 *
		 * @since 1.0.0
		 * @var PostGridBlock_Enqueues
		 */
		private static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			add_action( 'enqueue_block_editor_assets', array( $this, 'postgridblock_editor_assets_loader' ) );
		}

        public function postgridblock_editor_assets_loader() {
            /**
             * Enqueue editor assets only in the admin area
             */
            if ( ! is_admin() ) {
                return;
            }

			/**
			 * Fetch asset dependencies and version from the asset manifest
			 */
			$script_asset_path = POST_GRID_BLOCK_PLUGIN_DIR . 'build/index.asset.php';

			if ( file_exists( $script_asset_path ) ) {
				$script_dependency = include $script_asset_path;
			} else {
				$script_dependency = [
					'dependencies' => [],
					'version'      => POST_GRID_BLOCK_VERSION,
				];
			}

			/**
			 * Enqueue the JavaScript file
			 */
			wp_enqueue_script(
				'post-grid-block-script',
				trailingslashit( POST_GRID_BLOCK_URL ) . 'build/index.js',
				$script_dependency['dependencies'],
				$script_dependency['version'],
				true
			);

			/**
			 * This function is used to localize the JavaScript files for the Post Grid Block plugin
			 */
			wp_localize_script( 'post-grid-block-script', 'postGridBlockParams', array(
                'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
                'post_types'                => PostGridBlock_Helpers::get_post_types(),
                'home_url'                  => home_url(),
                'post_grid_block_nonce'     => wp_create_nonce( 'post-grid-block-nonce' ),
                'post_grid_block_version'   => POST_GRID_BLOCK_VERSION,
                'admin_email'               => get_option( 'admin_email' ),
            ) );
        }

		/**
		 * PostGridBlock_Enqueues Instance
		 *
		 * @since 1.0.0
		 * @return PostGridBlock_Enqueues
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
PostGridBlock_Enqueues::get_instance();
