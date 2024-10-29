<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Izlesene {
	public $enable_proxies = false;

	private $source = "izlesene";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	function media_info( $url ) {
		$web_page = aiovd_url_get_contents( $url, $this->enable_proxies );
		if ( preg_match_all( '/videoObj\s*=\s*({.+?})\s*;\s*\n/', $web_page, $match ) ) {
			$player_json        = $match[1][0];
			$player_data        = json_decode( $player_json, true );
			$data["title"]      = $player_data["videoTitle"];
			$data["source_key"] = "izlesene";
			$data["source_url"] = $url;
			$data["thumbnail"]  = $player_data["posterURL"];
			$data["duration"]   = gmdate( ( $player_data["duration"] / 1000 > 3600 ? "H:i:s" : "i:s" ),
				$player_data["duration"] / 1000 );
			if ( ! empty( $player_data["media"]["level"] ) ) {
				$i = 0;
				foreach ( $player_data["media"]["level"] as $video ) {
					$data["links"][ $i ]["url"]     = $video["source"];
					$data["links"][ $i ]["type"]    = "mp4";
					$data["links"][ $i ]["size"]    = aiovd_get_file_size( $video["source"], $this->enable_proxies );
					$data["links"][ $i ]["quality"] = $video["value"] . "p";
					$data["links"][ $i ]["mute"]    = false;
					$i ++;
				}

				return $data;
			}
		}
	}
}