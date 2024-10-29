<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Streamable {
	public $enable_proxies = false;

	private $source = "streamable";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	public function media_info( $url ) {
		$web_page   = aiovd_url_get_contents( $url, $this->enable_proxies );
		$video_data = aiovd_get_string_between( $web_page, 'var videoObject =', ';' );
		$video_data = json_decode( $video_data, true );
		if ( empty( $video_data ) ) {
			return false;
		}
		$video["title"]      = $video_data["title"];
		$video["source_key"] = "streamable";
		$video["source_url"] = $url;
		$video["thumbnail"]  = $video_data["thumbnail_url"];
		$video["duration"]   = aiovd_format_seconds( (int) ceil( $video_data["duration"] ) );
		$video["links"]      = array();
		foreach ( $video_data["files"] as $key => $data ) {
			$url = "https:" . $data["url"];
			array_push( $video["links"],
			            array(
				            "url"     => $url,
				            "type"    => pathinfo( parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ),
				            "size"    => aiovd_get_file_size( $url, $this->enable_proxies ),
				            "quality" => $data["height"] . "p",
				            "mute"    => false
			            ) );
		}
		usort( $video["links"], 'aiovd_sort_by_quality' );

		return $video;
	}
}