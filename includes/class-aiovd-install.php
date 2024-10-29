<?php

namespace AIOVD;

/** block direct access */
defined( 'ABSPATH' ) || exit;

/** check if class `AIOVD_Install` not exists yet */
if ( ! class_exists( 'AIOVD\Install' ) ) {
	/**
	 * Class Install
	 */
	class Install {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Do the activation stuff
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function __construct() {
			self::create_default_data();
		}


		/**
		 * create default data
		 *
		 * @since 2.0.8
		 */
		private static function create_default_data() {

			update_option( 'aiovd_version', AIOVD_VERSION );
			update_option( 'aiovd_page_created', 'no' );
			update_option( 'aiovd_page_viewed', 'no' );


			$install_date = get_option( 'aiovd_install_time' );

			if ( empty( $install_date ) ) {
				update_option( 'aiovd_install_time', time() );

				if ( ! empty( source_map() ) ) {
					$sources = [];

					foreach ( source_map() as $key => $source ) {

						if ( ! aiovd_fs()->can_use_premium_code__premium_only() && isset( $source['pro'] ) ) {
							$sources['source'][ $key ] = 'off';
						} else {
							$sources['source'][ $key ] = 'on';
						}
					}

					update_option( 'aiovd_source_settings', $sources );
				}
			}

		}

		/**
		 * @return Install|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}