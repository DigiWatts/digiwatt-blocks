<?php
/**
 * DWB class
 *
 * @package dwb
 * @since   0.1.0
 */

/**
 * Final DWB class.
 *
 * @final
 */
final class DWB {

    /**
     * Version
     *
     * (default value: '0.1.0')
     *
     * @var string
     * @access public
     */
    public $version = '0.2.1';

    /**
     * _instance
     *
     * (default value: null)
     *
     * @var mixed
     * @access protected
     * @static
     */
    protected static $_instance = null;

    /**
     * Instance function.
     *
     * @access public
     * @static
     * @return instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define constants.
     *
     * @access private
     * @return void
     */
    private function define_constants() {
        $this->define( 'DWB_ABSPATH', dirname( DWB_PLUGIN_FILE ) . '/' );
        $this->define( 'DWB_VERSION', $this->version );
        $this->define( 'DWB_PATH', plugin_dir_path( __FILE__ ) );
        $this->define( 'DWB_ABSURL', plugin_dir_url( DWB_PLUGIN_FILE ) );
        $this->define( 'DWB_URL', plugin_dir_url( __FILE__ ) );
        $this->define( 'DWB_ASSETS_URL', plugin_dir_url( __DIR__ ) . 'assets/' );
    }

    /**
     * Custom define function.
     *
     * @access private
     * @param mixed $name string.
     * @param mixed $value string.
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Include plugin files.
     *
     * @access public
     * @return void
     */
    public function includes() {
        include_once( DWB_ABSPATH . 'includes/functions.php' );
                
        include_once( DWB_ABSPATH . 'blocks/about.php' );
        include_once( DWB_ABSPATH . 'blocks/home-grid.php' );
        include_once( DWB_ABSPATH . 'blocks/post-header.php' );
        include_once( DWB_ABSPATH . 'blocks/read-time.php' );
        include_once( DWB_ABSPATH . 'blocks/tagline.php' );
    }

    /**
     * Init hooks for plugin.
     *
     * @access private
     * @return void
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'init' ), 0 );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts_styles' ) );
    }

    /**
     * Initialize stuff.
     *
     * @access public
     * @return void
     */
    public function init() {}

    /**
     * Frontend scripts/styles.
     *
     * @access public
     * @return void
     */
    public function frontend_scripts_styles() {}

}

/**
 * DWB function.
 *
 * @access public
 * @return instance
 */
function dwb() {
    return DWB::instance();
}

// Global for backwards compatibility.
$GLOBALS['dwb'] = dwb();
