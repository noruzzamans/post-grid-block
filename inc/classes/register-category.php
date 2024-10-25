<?php
/**
 * Post Grid Block Category
 *
 * @since 1.0.0
 * @package PostGridBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PostGridBlock_Category' ) ) {

	/**
	 * Post Grid Block Category Class
	 *
	 * @since 1.0.0
	 * @package PostGridBlock
	 */
	class PostGridBlock_Category {

		/**
		 * PostGridBlock_Category Instance
		 *
		 * @since 1.0.0
		 * @var PostGridBlock_Category
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
			add_filter( 'block_categories_all', array( $this, 'register_category' ), 10, 2 );
		}

		/**
		 * Register Category
		 *
		 * @since 1.0.0
		 * @param array $categories Existing categories.
		 * @return array Modified categories.
		 */
		public function register_category( $categories ) {
			return array_merge(
				array(
					array(
						'slug'  => 'post-grid-block',
						'title' => __( 'Post Grid Block', 'post-grid-block' ),
					),
				),
				$categories
			);
		}

		/**
		 * PostGridBlock_Category Instance
		 *
		 * @since 1.0.0
		 * @return PostGridBlock_Category
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

// Initialize the category class
PostGridBlock_Category::get_instance();
