<?php
/**
 * Plugin Name: All In One Video Downloader
 * Plugin URI:  https://wpmilitary.com/all-in-one-video-downloader
 * Description: Download Videos from Websites easily from 35+ video websites
 * Version:     1.0.2
 * Author:      WP Military
 * Author URI:  http://wpmilitary.com
 * Text Domain: aiovd
 * Domain Path: /languages/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/** don't call the file directly */
defined( 'ABSPATH' ) || wp_die( __( 'You can\'t access this page', 'aoivd' ) );

define( 'AIOVD_VERSION', '1.0.2' );
define( 'AIOVD_FILE', __FILE__ );
define( 'AIOVD_PATH', dirname( AIOVD_FILE ) );
define( 'AIOVD_INCLUDES', AIOVD_PATH . '/includes' );
define( 'AIOVD_URL', plugins_url( '', AIOVD_FILE ) );
define( 'AIOVD_ASSETS', AIOVD_URL . '/assets' );
define( 'AIOVD_TEMPLATES', AIOVD_PATH . '/templates' );
define( 'AIOVD_PRICING', 'admin.php?page=all-in-one-video-downloader-pricing' );

define( 'AIOVD_USER_AGENT',
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36" );


require AIOVD_INCLUDES . '/base.php';