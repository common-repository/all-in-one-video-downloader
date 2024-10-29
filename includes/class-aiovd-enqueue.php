<?php

namespace AIOVD;


/** block direct access */
defined( 'ABSPATH' ) || exit();

/** check if class `AIOVD_Enqueue` not exists yet */
if ( ! class_exists( 'AIOVD\Enqueue' ) ) {
	class Enqueue {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * AIOVD_Enqueue constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		}

		/**
		 * Frontend Scripts
		 *
		 * @param $hook
		 */
		public function frontend_scripts( $hook ) {

			/** frontend-css */
			wp_enqueue_style( 'aiovd-frontend',
			                  AIOVD_ASSETS . '/css/frontend.css',
			                  [],
			                  aiovd()->version );

			/** frontned-js */
			wp_enqueue_script( 'aiovd-frontend',
			                   AIOVD_ASSETS . '/js/frontend.min.js',
			                   [ 'jquery', 'wp-util', ],
			                   aiovd()->version,
			                   true );


			/* localized script attached to 'aiovd-frontend' */
			wp_localize_script( 'aiovd-frontend',
			                    'aiovd',
			                    [
				                    'ajax_url' => admin_url( 'admin-ajax.php' ),
			                    ] );
		}

		/**
		 * Admin scripts
		 *
		 * @param $hook
		 */
		public function admin_scripts( $hook ) {

			/** admin css */
			wp_enqueue_style( 'aiovd-admin', AIOVD_ASSETS . '/css/admin.css', false, AIOVD_VERSION );


			/** timer js */
			wp_enqueue_script( 'jquery.syotimer',
			                   AIOVD_ASSETS . '/vendor/jquery.syotimer.min.js',
			                   [ 'jquery'],
			                   AIOVD_VERSION,
			                   true );

			/** admin js */
			wp_enqueue_script( 'aiovd-admin',
			                   AIOVD_ASSETS . '/js/admin.min.js',
			                   [ 'jquery', 'wp-util' ],
			                   AIOVD_VERSION,
			                   true );

			$localize_array = array(
				'adminUrl' => admin_url(),
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'aiovd' ),
				'i18n'     => array(),
				'is_pro'   => aiovd_fs()->can_use_premium_code__premium_only(),
			);

			wp_localize_script( 'aiovd-admin', 'aiovd', $localize_array );

		}

		/**
		 * @return Enqueue|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}




