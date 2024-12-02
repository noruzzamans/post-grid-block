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

if ( ! class_exists( 'PostGridBlock_Helpers' ) ) {

	/**
	 * Post Grid Block Registration Class
	 *
	 * @since 1.0.0
	 * @package PostGridBlock
	 */
	class PostGridBlock_Helpers {

		/**
		 * PostGridBlock_Helpers Instance
		 *
		 * @since 1.0.0
		 * @var PostGridBlock_Helpers
		 */
		private static $instance = null;

        public static function get_post_types() {
            $post_types     = get_post_types(
                [
                    'public'            => true,
                    'show_in_rest'      => true,
                    'show_in_nav_menus' => true,
                ],
                'objects'
            );
            $post_types     = wp_list_pluck($post_types, 'label', 'name');
            $excluded_types = apply_filters(
                'post_grid_block_exclude_post_type',
                [
                    'attachment'        => 'Attachment',
                    'elementor_library' => 'Elementor Library',
                    'e-landing-page'    => 'Landing Page',
                ]
            );
            return array_diff_key($post_types, $excluded_types);
        }

		/**
		 * PostGridBlock_Helpers Instance
		 *
		 * @since 1.0.0
		 * @return PostGridBlock_Helpers
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
PostGridBlock_Helpers::get_instance();
