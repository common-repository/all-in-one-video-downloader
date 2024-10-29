<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Liveleak {
	public $enable_proxies = false;

	private $source = "liveleak";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	function media_info( $url ) {
		$curl_content = aiovd_url_get_contents( $url, $this->enable_proxies );
		if ( preg_match_all( '<meta property="og:description" content="(.*?)"/>', $curl_content, $match ) ) {
			$data["title"] = $match[1][0];
		}
		if ( preg_match_all( '<meta property="og:image" content="(.*?)"/>', $curl_content, $match ) ) {
			$data["thumbnail"] = $match[1][0];
		}
		$data["links"] = array();
		if ( preg_match_all( '/src="(.*?)" (default|) label="(720p|360p|HD|SD)"/', $curl_content, $matches ) ) {
			$i = 0;
			foreach ( $matches[1] as $match ) {
				$data["links"][ $i ]["url"]     = $match;
				$data["links"][ $i ]["type"]    = "mp4";
				$data["links"][ $i ]["quality"] = $matches[3][ $i ];
				$data["links"][ $i ]["size"]    = aiovd_get_file_size( $data["links"][ $i ]["url"], $this->enable_proxies );
				$i ++;
			}
			$data["links"]      = array_reverse( $data["links"] );
			$data["source_key"] = "liveleak";
			$data["source_url"] = $url;

			return $data;
		} else if ( preg_match_all( '/youtube.com\/embed\/(.*?)\?autoplay=1/', $curl_content, $matches ) ) {
			$video_id = $matches[1][0];
			require_once AIOVD_INCLUDES . "/module/youtube.php";
			$download = new youtube();

			return $download->media_info( "https://www.youtube.com/watch?v=" . $video_id );
		}

		return false;
	}
}