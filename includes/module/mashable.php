<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Mashable {
	public $enable_proxies = false;

	private $source = "mashable";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	function media_info( $url ) {
		$data["source_key"] = "mashable";
		$data["source_url"] = $url;
		$curl_content       = aiovd_url_get_contents( $url, $this->enable_proxies );
		preg_match_all( '@<script class="playerMetadata" type="application/json">(.*?)</script>@si',
		                $curl_content,
		                $match );
		preg_match_all( '/<script type="application\/ld\+json">{"@context": "https:\/\/schema.org", "@type": "VideoObject",(.*?)<\/script>/',
		                $curl_content,
		                $output );
		if ( ! empty( $match[1][0] ) ) {
			$json              = json_decode( $match[1][0], true );
			$data["title"]     = $json["player"]["title"];
			$data["thumbnail"] = $json["player"]["image"];
			$i                 = 0;
			foreach ( $json["player"]["sources"] as $url ) {
				if ( preg_match_all( "@/(.*?).mp4@si", $url["file"], $match ) ) {
					$data["links"][ $i ]["url"]     = $url["file"];
					$data["links"][ $i ]["type"]    = "mp4";
					$data["links"][ $i ]["quality"] = $match[1][1] . "P";
					$data["links"][ $i ]["size"]    = aiovd_get_file_size( $data["links"][ $i ]["url"],
					                                                 $this->enable_proxies );
					$i ++;
				}
			}
			$data["links"] = array_reverse( $data["links"] );
		} else if ( ! empty( $output[0][0] ) ) {
			$json                        = aiovd_get_string_between( $output[0][0],
			                                                   '<script type="application/ld+json">',
			                                                   '</script>' );
			$json                        = json_decode( $json, true );
			$data["title"]               = $json["name"];
			$data["thumbnail"]           = $json["thumbnailUrl"];
			$data["links"][0]["url"]     = $json["contentUrl"];
			$data["links"][0]["type"]    = "mp4";
			$data["links"][0]["quality"] = "HD";
			$data["links"][0]["size"]    = aiovd_get_file_size( $data["links"][0]["url"], $this->enable_proxies );
		} else {
			return false;
		}

		return $data;
	}
}