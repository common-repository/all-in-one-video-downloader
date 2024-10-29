<?php

/** don't call the file directly */
defined( 'ABSPATH' ) || wp_die( __( 'You can\'t access this page', 'aoivd' ) );

/**
 * if class `AIOVD` doesn't exists yet.
 */
if ( ! class_exists( 'AIOVD' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 * Main initiation class
	 *
	 * @since 1.0.0
	 */
	final class AIOVD {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Plugin version.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var string
		 */
		public $version = AIOVD_VERSION;

		/**
		 * Minimum PHP version required
		 *
		 * @var string
		 */
		private static $min_php = '5.6.0';

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @return void
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
			if ( $this->check_environment() ) {

				$this->instances();

				add_action( 'init', [ $this, 'lang' ] );
				add_action( 'admin_notices', [ $this, 'print_notices' ], 15 );
				add_filter( 'plugin_action_links_' . plugin_basename( AIOVD_FILE ),
				            array( $this, 'plugin_action_links' ) );

				register_activation_hook( AIOVD_FILE, [ $this, 'activation' ] );
			}
		}

		public function activation(){
		    AIOVD\Install::instance();
        }


		/**
		 * Ensure theme and server variable compatibility
		 *
		 * @return boolean
		 * @since  1.0.0
		 * @access private
		 */
		private function check_environment() {

			$return = true;

			/** Check the PHP version compatibility */
			if ( version_compare( PHP_VERSION, self::$min_php, '<=' ) ) {
				$return = false;

				$notice = sprintf( esc_html__( 'Unsupported PHP version Min required PHP Version: "%s"', 'aoivd' ),
				                   self::$min_php );
			}

			/** Add notice and deactivate the plugin if the environment is not compatible */
			if ( ! $return ) {

				add_action( 'admin_notices',
					function () use ( $notice ) { ?>
                        <div class="notice is-dismissible notice-error">
                           <?php echo $notice; ?>
                        </div>
					<?php } );

				return $return;
			} else {
				return $return;
			}

		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function instances() {

			include AIOVD_INCLUDES.'/freemius.php';
			include AIOVD_INCLUDES.'/functions.php';

			AIOVD\Form_Handler::instance();
			AIOVD\Enqueue::instance();
			AIOVD\ShortCode::instance();
			AIOVD\Hooks::instance();

			//admin instance
			if ( is_admin() ) {
				AIOVD\Admin\Admin::instance();
			}

		}

		/**
		 * Initialize plugin for localization
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		public function lang() {
			load_plugin_textdomain( 'aiovd', false, dirname( plugin_basename( AIOVD_FILE ) ) . '/languages/' );
		}

		/**
		 * Plugin action links
		 *
		 * @param array $links
		 *
		 * @return array
		 */
		public function plugin_action_links( $links ) {
			$links[] = sprintf( '<a href="%1$s">%2$s</a>',
			                    admin_url( 'admin.php?page=all-in-one-video-downloader' ), __( 'Settings', 'aoivd' ) );

			return $links;
		}

		/**
		 * Get template files
		 *
		 * since 1.0.0
		 *
		 * @param        $template_name
		 * @param array $args
		 * @param string $template_path
		 * @param string $default_path
		 *
		 * @return void
		 */
		public function get_template( $template_name, $args = array(), $template_path = 'all-in-one-video-downloader', $default_path = '' ) {

			/* Add php file extension to the template name */
			$template_name = $template_name . '.php';

			/* Extract the args to variables */
			if ( $args && is_array( $args ) ) {
				extract( $args );
			}



			/* Look within passed path within the theme - this is high priority. */
			$template = locate_template( array( trailingslashit( $template_path ) . $template_name ) );



			/* Get default template. */
			if ( ! $template ) {
				$default_path = $default_path ? $default_path : AIOVD_TEMPLATES;
				if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
					$template = trailingslashit( $default_path ) . $template_name;
				}
			}

			// Return what we found.
			include( apply_filters( 'aiovd_locate_template', $template, $template_name, $template_path ) );

		}

		/**
		 * add admin notices
		 *
		 * @param           $class
		 * @param           $message
		 * @param string $only_admin
		 *
		 * @return void
		 */
		public function add_notice( $class, $message ) {

			$notices = get_option( sanitize_key( 'aiovd_notices' ), [] );
			if ( is_string( $message ) && is_string( $class ) && ! wp_list_filter( $notices,
			                                                                       array( 'message' => $message ) ) ) {

				$notices[] = array(
					'message'    => $message,
					'class'      => $class,
				);

				update_option( sanitize_key( 'aiovd_notices' ), $notices );
			}

		}

		/**
		 * Print the admin notices
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function print_notices() {
			$notices = get_option( sanitize_key( 'aiovd_notices' ), [] );
			foreach ( $notices as $notice ) { ?>

                <div class="notice notice-large is-dismissible notice-<?php echo $notice['class']; ?>"><?php echo $notice['message']; ?></div>

                <?php
				update_option( sanitize_key( 'aiovd_notices' ), [] );
			}
		}

		/**
		 * Main
		 * AIOVD Instance.
		 *
		 * Ensures only one instance of
		 * AIOVD is loaded or can be loaded.
		 *
		 * @return
		 * AIOVD - Main instance.
		 * @since 1.0.0
		 * @static
		 */
		public static function instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

/** if function `aiovd` doesn't exists yet. */
if ( ! function_exists( 'aiovd' ) ) {
	function aiovd() {
		return AIOVD::instance();
	}
}

/** fire off the plugin */
aiovd();