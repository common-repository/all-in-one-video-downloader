<?php

namespace AIOVD;

if ( ! class_exists( 'AIOVD\Hooks' ) ) {
	class Hooks {
		/** @var null */
		private static $instance = null;

		/**
		 * Hooks constructor.
		 */
		public function __construct() {
			add_action( 'wp_ajax_aiovd_download', [ $this, 'download' ] );
			add_action( 'wp_ajax_nopriv_aiovd_download', [ $this, 'download' ] );

			add_action( 'wpmilitary_settings/after_content', [ $this, 'pro_promo' ] );
			add_action( 'update_option_aiovd_api_settings', [ $this, 'update_cookie' ] );
		}

		public function update_cookie() {

			if ( ! empty( $_REQUEST['aiovd_api_settings']['facebook_cookie'] ) ) {
				$fb_coockie_file = AIOVD_INCLUDES . '/module/vendor/fb-cookie.txt';
				file_put_contents( $fb_coockie_file, $_REQUEST['aiovd_api_settings']['facebook_cookie'] );
			}
			if ( ! empty( $_REQUEST['aiovd_api_settings']['instagram_cookie'] ) ) {
				$fb_coockie_file = AIOVD_INCLUDES . '/module/vendor/ig-cookie.txt';
				file_put_contents( $fb_coockie_file, $_REQUEST['aiovd_api_settings']['instagram_cookie'] );
			}
		}

		public function pro_promo(){
			$is_hidden   = true;
			include AIOVD_INCLUDES . '/admin/views/promo.php';
		}

		public function download() {

			if ( empty( $_REQUEST["source"] ) || empty( $_REQUEST["index"] ) ) {
				return;
			}

			//todo
			$bandwidth_saving = false;
			$download_suffix  = '';

			set_time_limit( 0 );
			ini_set( "zlib.output_compression", "Off" );

			aiovd_start_session();

			$current_result = $_SESSION['result'][ $_SESSION["token"] ];

			$i = (int) base64_decode( $_REQUEST["index"] );

			$parsed_remote_url = parse_url( $current_result["links"][ $i ]["url"] );
			$remote_domain = str_ireplace( "www.", "", $parsed_remote_url["host"] );
			$local_domain = str_ireplace( "www.", "", parse_url( site_url(), PHP_URL_HOST ) );

			if ( filter_var( $current_result["links"][ $i ]["url"] ?? "", FILTER_VALIDATE_URL ) ) {
				if ( $_REQUEST["source"] != "ok.ru" && $bandwidth_saving || $remote_domain == "dailymotion.aiovideodl.ml" ) {
					wp_redirect( $current_result["links"][ $i ]["url"] );
				} else {

					if ( ! empty( $download_suffix ) ) {
						$download_suffix = "-" . $download_suffix;
					}

					if ( $local_domain == $remote_domain ) {
						aiovd_force_download_legacy( __DIR__ . $parsed_remote_url['path'],
						                       $current_result["title"] . $download_suffix,
						                       $current_result["links"][ $i ]["type"] );
					} else {
						aiovd_force_download( $current_result["links"][ $i ]["url"],
						                $current_result["title"] . $download_suffix,
						                $current_result["links"][ $i ]["type"] );
					}
				}
			} else {
				die( 'Video can\'t be downloaded!' );
			}
		}

		/**
		 * @return Hooks|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

Hooks::instance();