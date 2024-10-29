<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Kwai {
	public $enable_proxies = false;

	private $source = "kwai";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	public function media_info( $url ) {
		$web_page                     = aiovd_url_get_contents( $url, $this->enable_proxies );
		$video_url                    = aiovd_get_string_between( $web_page, '<video src="', '"' );
		$video["title"]               = aiovd_get_string_between( $web_page, '"userName":"', '","headUrl"' );
		$video["source_key"]          = "kwai";
		$video["source_url"]          = $url;
		$video["thumbnail"]           = aiovd_get_string_between( $web_page, 'poster="', '"' );
		$video["links"][0]["url"]     = $video_url;
		$video["links"][0]["type"]    = "mp4";
		$video["links"][0]["size"]    = aiovd_get_file_size( $video_url, $this->enable_proxies );
		$video["links"][0]["quality"] = "HD";
		$video["links"][0]["mute"]    = false;

		return $video;
	}
}