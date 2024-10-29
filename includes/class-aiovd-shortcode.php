<?php

namespace AIOVD;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'AIOVD\ShortCode' ) ) {
	class ShortCode {

		/**
		 * @var null
		 */
		private static $instance = null;

		/* constructor */
		public function __construct() {
			add_shortcode( 'aiovd', array( $this, 'aiovd' ) );
			add_shortcode( 'aiovd_supported_websites', array( $this, 'supported_websites' ) );
			add_shortcode( 'aiovd_how_to_download', array( $this, 'how_to_download' ) );
		}

		public function how_to_download( $atts ) {
			ob_start();
			aiovd()->get_template( 'how-to-download' );

			return ob_get_clean();
		}

		public function supported_websites( $atts ) {
			ob_start();
			aiovd()->get_template( 'supported-websites' );

			return ob_get_clean();
		}

		public function aiovd( $atts ) {
			ob_start();
			aiovd()->get_template( 'form' );

			return ob_get_clean();
		}

		/**
		 * @return ShortCode|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}