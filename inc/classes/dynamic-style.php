<?php
/**
 * Post Grid Block - Generate and Manage Page-Specific Dynamic Styles with Minification
 *
 * @since 1.0.0
 * @package PostGridBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'PostGridBlock_Dynamic_Style' ) ) {

    /**
     * Post Grid Block Dynamic Style Class
     *
     * @since 1.0.0
     * @package PostGridBlock
     */
    class PostGridBlock_Dynamic_Style {

        /**
         * Stores all dynamic styles
         *
         * @var array
         */
        private static $styles = array();

        /**
         * The directory for storing CSS files
         *
         * @var string
         */
        private $css_dir;

        /**
         * The URL for the CSS directory
         *
         * @var string
         */
        private $css_url;

        /**
         * Singleton instance
         *
         * @var PostGridBlock_Dynamic_Style
         */
        private static $instance;

        /**
         * Constructor
         *
         * @since 1.0.0
         * @return void
         */
        private function __construct() {
            $this->setup_directories();
            $this->init();
        }

        /**
         * PostGridBlock_Dynamic_Style Instance
         *
         * @since 1.0.0
         * @return PostGridBlock_Dynamic_Style
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Setup directories
         *
         * @since 1.0.0
         * @return void
         */
        private function setup_directories() {
            $upload_dir = wp_upload_dir();
            $this->css_dir = $upload_dir['basedir'] . '/post-grid-block/css';
            $this->css_url = $upload_dir['baseurl'] . '/post-grid-block/css';

            if ( ! file_exists( $this->css_dir ) ) {
                wp_mkdir_p( $this->css_dir );
            }
        }

        /**
         * Initialize the Class
         *
         * @since 1.0.0
         * @return void
         */
        private function init() {
            add_filter( 'render_block', array( $this, 'collect_dynamic_styles' ), 10, 2 );
            add_action( 'wp_footer', array( $this, 'generate_css_file' ), 10 );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dynamic_styles' ) );
        }

        /**
         * Collect Dynamic Styles
         *
         * @since 1.0.0
         * @param string $block_content Block Content.
         * @param array  $block Block Attributes.
         * @return string
         */
        public function collect_dynamic_styles( $block_content, $block ) {
            if ( isset( $block['blockName'] ) && str_contains( $block['blockName'], 'post-grid-block/' ) ) {
                do_action( 'post_grid_block_render_block', $block );
                if ( isset( $block['attrs']['blockStyle'] ) ) {
                    $block_id = isset( $block['attrs']['blockId'] ) ? $block['attrs']['blockId'] : 'post-grid-block-' . md5( serialize( $block['attrs'] ) );
                    self::$styles[$block_id] = $block['attrs']['blockStyle'];
                }
            }
            return $block_content;
        }

        /**
         * Generate CSS File
         *
         * @since 1.0.0
         * @return void
         */
        public function generate_css_file() {
            if ( empty( self::$styles ) ) {
                return;
            }

            $page_id = get_queried_object_id();
            $css_filename = 'post-grid-block-dynamic-styles-' . $page_id . '.min.css';
            $css_file = $this->css_dir . '/' . $css_filename;

            $css_content = '';
            foreach ( self::$styles as $block_id => $style ) {
                $css_content .= "$style";
            }

            $css_content = "/* Post Grid Block Dynamic Styles - Page ID: $page_id - Generated on " . current_time( 'mysql' ) . " */\n" . $this->minify_css($css_content);

            // Only write the file if the content has changed
            $existing_content = '';
            if ( file_exists( $css_file ) ) {
                global $wp_filesystem;
                if ( empty( $wp_filesystem ) ) {
                    require_once( ABSPATH . '/wp-admin/includes/file.php' );
                    WP_Filesystem();
                }
                $existing_content = $wp_filesystem->get_contents( $css_file );
            }

            if ( $existing_content !== $css_content ) {
                global $wp_filesystem;
                if ( empty( $wp_filesystem ) ) {
                    require_once( ABSPATH . '/wp-admin/includes/file.php' );
                    WP_Filesystem();
                }
                $wp_filesystem->put_contents( $css_file, $css_content, FS_CHMOD_FILE );
            }

            // Clear the styles array after generating the file
            self::$styles = array();
        }

        /**
         * Enqueue Dynamic Styles
         *
         * @since 1.0.0
         * @return void
         */
        public function enqueue_dynamic_styles() {
            $page_id = get_queried_object_id();
            $css_filename = 'post-grid-block-dynamic-styles-' . $page_id . '.min.css';
            $css_file_path = $this->css_dir . '/' . $css_filename;
            $css_file_url = $this->css_url . '/' . $css_filename;

            if ( file_exists( $css_file_path ) ) {
                wp_enqueue_style( 
                    'post-grid-block-dynamic-styles-' . $page_id, 
                    $css_file_url, 
                    array(), 
                    filemtime( $css_file_path ) 
                );
            }
        }

        /**
         * Minify CSS
         *
         * @since 1.0.0
         * @param string $css The CSS to minify.
         * @return string Minified CSS.
         */
        private function minify_css( $css ) {
            // Remove comments
            $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
            // Remove space after colons
            $css = str_replace(': ', ':', $css);
            // Remove whitespace
            $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
            return $css;
        }

        /**
         * Clean up generated files
         *
         * @since 1.0.0
         * @return void
         */
        public static function cleanup_files() {
            $upload_dir = wp_upload_dir();
            $css_dir = $upload_dir['basedir'] . '/post-grid-block/css';

            if ( file_exists( $css_dir ) ) {
                global $wp_filesystem;
                if ( empty( $wp_filesystem ) ) {
                    require_once( ABSPATH . '/wp-admin/includes/file.php' );
                    WP_Filesystem();
                }

                $files = glob( $css_dir . '/*' );
                foreach ( $files as $file ) {
                    if ( is_file( $file ) ) {
                        $wp_filesystem->delete( $file );
                    }
                }
                $wp_filesystem->rmdir( $css_dir );
            }

            // Remove parent directory if it's empty
            $parent_dir = dirname( $css_dir );
            if ( file_exists( $parent_dir ) && ( count( glob( "$parent_dir/*" ) ) === 0 ) ) {
                $wp_filesystem->rmdir( $parent_dir );
            }
        }
    }
}

PostGridBlock_Dynamic_Style::get_instance(); // Initialize the Dynamic Style class
