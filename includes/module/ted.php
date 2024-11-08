<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Ted {
	public $enable_proxies = false;

	private $source = "ted";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	function media_info( $url ) {
		$curl_content = aiovd_url_get_contents( $url, $this->enable_proxies );
		preg_match_all( '/"__INITIAL_DATA__":(.*?)}\)/', $curl_content, $match );
		if ( empty( $match[1][0] ) ) {
			return false;
		}
		$json               = json_decode( $match[1][0], true );
		$data["source_key"] = "ted";
		$data["source_url"] = $url;
		$data["title"]      = $json["name"];
		$data["thumbnail"]  = $json["talks"][0]["hero"];
		$data["duration"]   = gmdate( ( $json["talks"][0]["duration"] > 3600 ? "H:i:s" : "i:s" ),
			$json["talks"][0]["duration"] );
		$i                  = 0;
		if ( ! empty( $json["talks"][0]["downloads"]["nativeDownloads"] ) ) {
			foreach ( $json["talks"][0]["downloads"]["nativeDownloads"] as $quality => $url ) {
				$data["links"][ $i ]["url"]     = $url;
				$data["links"][ $i ]["type"]    = "mp4";
				$data["links"][ $i ]["quality"] = $quality;
				$data["links"][ $i ]["size"]    = aiovd_get_file_size( $data["links"][ $i ]["url"], $this->enable_proxies );
				$i ++;
			}
		} else if ( ! empty( $json["talks"][0]["player_talks"][0]["resources"]["h264"] ) ) {
			$data["links"][ $i ]["url"]     = $json["talks"][0]["player_talks"][0]["resources"]["h264"][0]["file"];
			$data["links"][ $i ]["type"]    = "mp4";
			$data["links"][ $i ]["quality"] = "sd";
			$data["links"][ $i ]["size"]    = aiovd_get_file_size( $data["links"][ $i ]["url"], $this->enable_proxies );
			$i ++;
		}
		if ( ! empty( $json["talks"][0]["downloads"]["audioDownload"] ) ) {
			$data["links"][ $i ]["url"]     = aiovd_unshorten( $json["talks"][0]["downloads"]["audioDownload"],
			                                             $this->enable_proxies );
			$data["links"][ $i ]["type"]    = "mp3";
			$data["links"][ $i ]["quality"] = "128 Kbps";
			$data["links"][ $i ]["size"]    = aiovd_get_file_size( $data["links"][ $i ]["url"], $this->enable_proxies );
		}

		return $data;
	}
}