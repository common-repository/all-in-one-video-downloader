<?php


namespace AIOVD\Admin;

/** if class `AIOVD\Admin\Settings` not exists yet */
if ( ! class_exists( 'AIOVD\Admin\Settings' ) ) {

	class Settings {
		private static $instance = null;
		private static $settings_api = null;

		public function __construct() {
			add_action( 'admin_init', array( $this, 'settings_fields' ) );
			add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		}

		/**
		 * Registers settings section and fields
		 */
		public function settings_fields() {

			$sections = array(

				array(
					'id'    => 'aiovd_display_settings',
					'title' => sprintf( __( '%s Display Settings', 'aoivd' ), '<i class="dashicons dashicons-laptop"></i>' ),
				),

				array(
					'id'    => 'aiovd_source_settings',
					'title' => sprintf( __( '%s Source Settings', 'aoivd' ), '<i class="dashicons dashicons-feedback"></i>' ),
				),

				array(
					'id'    => 'aiovd_api_settings',
					'title' => sprintf( __( '%s API Settings', 'aoivd' ), '<i class="dashicons dashicons-admin-network"></i>' ),
				),

				array(
					'id'    => 'aiovd_shortcode_doc',
					'title' => sprintf( __( '%s Shortcodes', 'aoivd' ), '<i class="dashicons dashicons-shortcode"></i>' ),
				),
			);

			$fields = array(
				'aiovd_display_settings' => apply_filters( 'aiovd_general_settings', [
					'label'             => [
						'name'    => 'label',
						'label'   => 'Form Label Text',
						'type'    => 'text',
						'default' => 'Insert video link to download',
						'desc'    => 'Enter the download form label text',
					],
					'placeholder'       => [
						'name'    => 'placeholder',
						'label'   => 'Form Placeholder Text',
						'type'    => 'text',
						'default' => 'Paste/ Insert the video link',
						'desc'    => 'Enter the download form field placeholder text',
					],
					'download_btn_text' => [
						'name'    => 'download_btn_text',
						'label'   => 'Download Button Text',
						'type'    => 'text',
						'default' => 'Download',
						'desc'    => 'Enter the form download button text',
					],
					[
						'name'    => 'download_layout',
						'label'   => __( 'Download Links Layout :', 'aoivd' ),
						'desc'    => __( 'Choose the download links layout view. Left (Grid List View), Right (List View)', 'aoivd' ),
						'type'    => 'image_choose',
						'default' => 'grid',
						'options' => [
							'grid' => AIOVD_ASSETS . '/images/grid.png',
							'list' => AIOVD_ASSETS . '/images/list.png',
						],
					],
				] ),
				'aiovd_source_settings'  => apply_filters( 'aiovd_source_settings', [
					                                           'sources' => [
						                                           'name'    => 'sources',
						                                           'default' => [ $this, 'sources' ],
						                                           'type'    => 'cb_function'
					                                           ]
				                                           ] ),
				'aiovd_api_settings'     => apply_filters( 'aiovd_api_settings', [
					'soundcloud_key' => [
						'name'  => 'soundcloud_key',
						'label' => 'Soundcloud API Key : ',
						'type'  => 'text',
						'desc'  => 'Enter the soundcloud API key.',
					],
					'facebook_cookie'   => [
						'name'  => 'facebook_cookie',
						'label' => 'Facebook Cookie Data : ',
						'type'  => 'textarea',
						'desc'  => 'Enter the facebook cookie file data. Learn more <a href="#" target="_blank">How to get facebook cookie file data</a>',
					],
					'instagram_cookie'  => [
						'name'  => 'instagram_cookie',
						'label' => 'Instagram Cookie Data : ',
						'desc'  => 'Enter the instagram cookie file data. Learn more <a href="#" target="_blank">How to get instagram cookie file data</a>',
						'type'  => 'textarea',
					],
				] ),
				'aiovd_shortcode_doc'    => [
					[
						'name'    => 'shortcode_doc',
						'default' => [ $this, 'shortcode_doc' ],
						'type'    => 'cb_function',
					],
				],
			);

			include AIOVD_INCLUDES . '/admin/class-settings-api.php';
			self::$settings_api = new \WP_Military_Settings_API();

			//set sections and fields
			self::$settings_api->set_sections( $sections );
			self::$settings_api->set_fields( $fields );

			//initialize them
			self::$settings_api->admin_init();
		}

		public function shortcode_doc() { ?>
			<h3>All In One Video Downloader provides the below shortcodes:</h3>
			<div class="aiovd-shortcode-doc">
				<p><b>✅</b>
					<b><code>[aiovd]</code></b> - Display the video download form where users can enter the video URL to download.
				</p>

				<p><b>✅</b>
					<b><code>[aiovd_how_to_download]</code></b> - Display the how to download section
				</p>

				<p><b>✅</b>
					<b><code>[aiovd_supported_websites]</code></b> - Display the Supported Websites section
				</p>
			</div>
		<?php }

		public function sources() {
			include_once AIOVD_INCLUDES.'/admin/views/sources.php';
		}

		/**
		 * Register the plugin page
		 */
		public function settings_menu() {
			add_menu_page( 'All in One Video Downloader',
			                  'Video Downloader',
			                  'manage_options',
			                  'all-in-one-video-downloader',
										[ $this, 'settings_page' ],
				AIOVD_ASSETS . '/images/icon-20x20.png',
				100 );
		}

		/**
		 * Display the plugin settings options page
		 */
		public function settings_page() {
			echo '<div class="wrap">';
			settings_errors();

			echo '<div class="wrap">';
			echo sprintf( "<h2>%s</h2>", __( 'All in One Video Downloader Settings', 'aoivd' ) );
			self::$settings_api->show_settings();
			echo '</div>';

			echo '</div>';
		}

		/**
		 * @return Settings|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

Settings::instance();