<?php

namespace AIOVD;

/** block direct access */
defined( 'ABSPATH' ) || exit;
/** check if class `Form_Handler` not exists yet */
if ( !class_exists( 'AIOVD\\Form_Handler' ) ) {
    /**
     * Class Form_Handler
     */
    class Form_Handler
    {
        /**
         * @var null
         */
        private static  $instance = null ;
        /**
         * Do the activation stuff
         *
         * @return void
         * @since 1.0.0
         */
        public function __construct()
        {
            add_action( 'wp_ajax_aiovd_form', [ $this, 'handle_form' ] );
            add_action( 'wp_ajax_nopriv_aiovd_form', [ $this, 'handle_form' ] );
        }
        
        /**
         * Handle Video Download Form
         */
        public function handle_form()
        {
            $video_url = ( !empty($_REQUEST['video_url']) ? esc_url( $_REQUEST['video_url'] ) : '' );
            $host = parse_url( $video_url, PHP_URL_HOST );
            $domain = str_ireplace( "www.", "", $host );
            $main_domain = aiovd_get_main_domain( $host );
            aiovd_start_session();
            $_SESSION['video'][$_SESSION["token"]] = $video_url;
            $_SESSION['ip'][$_SESSION["token"]] = aiovd_get_client_ip();
            
            if ( in_array( $domain, [ 'youtube.com', 'm.youtube.com', 'youtu.be' ] ) ) {
                $download = new Module\Youtube();
            } elseif ( in_array( $domain, [ 'vk.com', 'm.vk.com' ] ) ) {
                $download = new Module\Vk();
            } elseif ( in_array( $domain, [ '9gag.com', 'm.9gag.com' ] ) ) {
                $download = new Module\Ninegag();
            } elseif ( in_array( $domain, [ 'imdb.com', 'm.imdb.com' ] ) ) {
                $download = new Module\Imdb();
            } elseif ( in_array( $domain, [
                'www.twitch.tv',
                'twitch.tv',
                'm.twitch.tv',
                'clips.twitch.tv'
            ] ) ) {
                $download = new Module\Twitch();
            } elseif ( in_array( $domain, [ 'kwai.com', 'kw.ai' ] ) ) {
                $download = new Module\Kwai();
            } elseif ( 'izlesene.com' == $domain ) {
                $download = new Module\Izlesene();
            } elseif ( 'bandcamp.com' == $domain ) {
                $download = new Module\Bandcamp();
            } elseif ( 'liveleak.com' == $domain ) {
                $download = new Module\Liveleak();
            } elseif ( 'ted.com' == $domain ) {
                $download = new Module\Ted();
            } elseif ( 'mashable.com' == $domain ) {
                $download = new Module\Mashable();
            } elseif ( 'break.com' == $domain ) {
                $download = new Module\Breakcom();
            } elseif ( 'ok.ru' == $domain ) {
                $download = new Module\Ok();
            } elseif ( 'v.douyin.com' == $domain ) {
                $download = new Module\Douyin();
            } elseif ( 'streamable.com' == $domain ) {
                $download = new Module\Streamable();
            } elseif ( 'bitchute.com' == $domain ) {
                $download = new Module\Bitchute();
            } elseif ( 'akilli.tv' == $domain ) {
                $download = new Module\Akillitv();
            } else {
                wp_send_json_success( [
                    'error' => 'Doesn\'t support downloading from this website.',
                ] );
            }
            
            $video = $download->media_info( $video_url );
            aiovd_return_json( $video );
        }
        
        /**
         * @return Form_Handler|null
         */
        public static function instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
    
    }
}