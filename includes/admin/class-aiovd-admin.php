<?php

namespace AIOVD\Admin;

defined('ABSPATH') || exit();

if(!class_exists('AIOVD\Admin\Admin')){
	class Admin{
		/** @var null  */
		private static $instance = null;

		/**
		 * Admin constructor.
		 */
		public function __construct() {
			Settings::instance();

			//add_action( 'admin_init', [ $this, 'create_page' ] );
			add_action( 'admin_init', [ $this, 'display_notices' ], 11 );

			add_action( 'wp_ajax_aiovd_create_page', [ $this, 'create_page' ] );
			add_action( 'wp_ajax_aiovd_view_page', [ $this, 'view_page' ] );
		}

		public function display_notices() {

			if ( get_page_by_title( 'Video Downloader' ) ) {
				return;
			}

			$is_created = 'yes' == get_option( 'aiovd_page_created' );
			$is_viewd   = 'yes' == get_option( 'aiovd_page_viewd' );

			if ( ! $is_created ) {
				ob_start();
				include AIOVD_INCLUDES . '/admin/views/notices/create-page.php';
				$notice = ob_get_clean();
				aiovd()->add_notice( 'info aiovd_page_notice', $notice );
			}

			if ( $is_created && ! $is_viewd ) {
				$page_id = get_option( 'aiovd_page' );
				ob_start();
				$page_link = get_the_permalink( $page_id );

				include AIOVD_INCLUDES . '/admin/views/notices/view-page.php';
				$notice = ob_get_clean();
				aiovd()->add_notice( 'info aiovd_page_notice', $notice );
			}

		}

		public function view_page() {

			if ( empty( $_REQUEST['notice_action'] ) ) {
				return;
			}

			$action = wp_unslash( $_REQUEST['notice_action'] );

			if ( 'view_pge' == $action ) {
				update_option( 'aiovd_page_viewed', 'yes' );
			}


		}

		/**
		 * Create page
		 *
		 * @since 1.0.2
		 */
		public function create_page() {

			if ( empty( $_REQUEST['notice_action'] ) ) {
				return;
			}

			$action = wp_unslash( $_REQUEST['notice_action'] );

			if ( 'not_create_page' == $action ) {
				delete_option( 'aiovd_page_created' );

				wp_send_json_success( [ 'hide' => true ] );
			} elseif ( 'create_page' == $action ) {

				$is_created = 'yes' == get_option( 'aiovd_page_created', 'no' );

				if ( $is_created ) {
					wp_send_json_success( [ 'hide' => true ] );
				}

				update_option( 'aiovd_page_created', 'yes' );

				$id = wp_insert_post( array(
					'post_type'    => 'page',
					'post_title'   => 'Video Downloader',
					'post_content' => '[aiovd][aiovd_how_to_download][aiovd_supported_websites]',
					'post_status'  => 'publish',
				) );

				if ( ! is_wp_error( $id ) ) {
					update_option( 'aiovd_page', $id );
				}

				ob_start();
				$page_link = get_the_permalink( $id );
				include AIOVD_INCLUDES . '/admin/views/notices/view-page.php';
				$html = ob_get_clean();

				wp_send_json_success( [ 'html' => $html ] );

			}

		}

		/**
		 * @return Admin|null
		 */
		public static function instance(){
			if(is_null(self::$instance)){
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

Admin::instance();