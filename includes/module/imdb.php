<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Imdb {
	public $enable_proxies = false;

	private $source = "imdb";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	function orderArray( $arrayToOrder, $keys ) {
		$ordered = array();
		foreach ( $keys as $key ) {
			if ( isset( $arrayToOrder[ $key ] ) ) {
				$ordered[ $key ] = $arrayToOrder[ $key ];
			}
		}

		return $ordered;
	}

	function find_video_id( $url ) {
		preg_match( '/vi\d{4,20}/', $url, $match );

		return $match[0];
	}

	function media_info( $url ) {
		$video_id            = $this->find_video_id( $url );
		$embed_url           = "https://www.imdb.com/video/imdb/$video_id/imdb/embed";
		$embed_source        = aiovd_url_get_contents( $embed_url, $this->enable_proxies );
		$video_data          = aiovd_get_string_between( $embed_source,
		                                           '<script class="imdb-player-data" type="text/imdb-video-player-json">',
		                                           '</script>' );
		$video_data          = json_decode( $video_data, true );
		$video["title"]      = aiovd_get_string_between( $embed_source, '<meta property="og:title" content="', '"/>' );
		$video["source_key"] = "imdb";
		$video["source_url"] = $url;
		$video["thumbnail"]  = aiovd_get_string_between( $embed_source, '<meta property="og:image" content="', '">' );
		if ( $video["title"] != "" ) {
			$streams = $video_data["videoPlayerObject"]["video"]["videoInfoList"];
			$i       = 0;
			foreach ( $streams as $stream ) {
				if ( $stream["videoMimeType"] == "video/mp4" ) {
					$video["links"][ $i ]["url"]     = $stream["videoUrl"];
					$video["links"][ $i ]["type"]    = "mp4";
					$video["links"][ $i ]["size"]    = aiovd_get_file_size( $video["links"][ $i ]["url"],
					                                                  $this->enable_proxies );
					$video["links"][ $i ]["quality"] = "hd";
					$video["links"][ $i ]["mute"]    = false;
					$i ++;
				}
			}

			return $video;
		} else {
			return false;
		}
	}
}