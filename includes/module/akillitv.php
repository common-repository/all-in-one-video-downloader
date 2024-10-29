<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Akillitv {
	public $enable_proxies = false;

	private $source = "akillitv";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	public function media_info( $url ) {
		$web_page            = aiovd_url_get_contents( $url, $this->enable_proxies );
		$video["title"]      = aiovd_get_string_between( $web_page, '<title>', '</title>' );
		$video["source_key"] = "akillitv";
		$video["source_url"] = $url;
		$video["thumbnail"]  = $this->clean_url( aiovd_get_string_between( $web_page,
		                                                             'property="og:image" content="',
		                                                             '"' ) );
		preg_match_all( '/<source src="(.*?)" type="video\/mp4" data-quality="(.*?)"/', $web_page, $matches );
		if ( ! isset( $matches[1] ) || ! isset( $matches[2] ) ) {
			return false;
		}
		for ( $i = 0; $i < count( $matches[1] ); $i ++ ) {
			$video_url                       = $this->clean_url( $matches[1][ $i ] );
			$video["links"][ $i ]["url"]     = $video_url;
			$video["links"][ $i ]["type"]    = "mp4";
			$video["links"][ $i ]["size"]    = aiovd_get_file_size( $video_url, $this->enable_proxies );
			$video["links"][ $i ]["quality"] = $matches[2][ $i ];
			$video["links"][ $i ]["mute"]    = false;
		}
		usort( $video["links"], 'aiovd_sort_by_quality' );

		return $video;
	}

	private function clean_url( $url ) {
		return str_replace( "////", "https://", $url );
	}
}