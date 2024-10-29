<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Ninegag {
	public $enable_proxies = false;

	private $source = "9gag";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	public function media_info( $url ) {
		$videoId = Ninegag::get_id( $url );
		if ( $videoId != false && $videoId != "" ) {
			$video["title"] = "9GAG Video";
			$videoUrl       = "https://img-9gag-fun.9cache.com/photo/" . $videoId . "_460sv.mp4";
			$videoSize      = aiovd_get_file_size( $videoUrl, $this->enable_proxies, false );
			if ( $videoSize > 1000 ) {
				$video["links"][0]["url"]     = $videoUrl;
				$video["links"][0]["type"]    = "mp4";
				$video["links"][0]["size"]    = aiovd_format_size( $videoSize );
				$video["links"][0]["quality"] = "HD";
				$video["links"][0]["mute"]    = false;
			}
			$video["thumbnail"]  = "http://images-cdn.9gag.com/photo/" . $videoId . "_460s.jpg";
			$video["source_key"] = "9gag";
			$video["source_url"] = $url;

			return $video;
		} else {
			return false;
		}
	}

	public function media_info_beta( $url ) {
		$web_page       = file_get_contents( $url );
		$json_data      = aiovd_get_string_between( $web_page, 'JSON.parse("', '")' );
		$json_data      = str_replace( '\"', '"', $json_data );
		$data           = json_decode( $json_data, true )["data"];
		$video["title"] = $data["post"]["title"];
		foreach ( $data["post"]["images"] as $image ) {
			if ( isset( $image["duration"] ) != "" ) {
				$video["links"][0]["url"]     = $this->convert_url( $image["url"] );
				$video["links"][0]["type"]    = "mp4";
				$video["links"][0]["size"]    = aiovd_get_file_size( $video["links"][0]["url"] );
				$video["links"][0]["quality"] = min( $image["height"], $image["width"] ) . "p";
				$video["links"][0]["mute"]    = false;
				$video["duration"]            = aiovd_format_seconds( $image["duration"] );
			} else if ( isset( $video["thumbnail"] ) ) {
				$video["thumbnail"] = $this->convert_url( $image["url"] );
			}
		}
		$video["source_key"] = "9gag";
		$video["source_url"] = $url;

		return $video;
	}

	public static function get_id( $url ) {
		preg_match( '/gag\/(\w+)/', $url, $output );

		return isset( $output[1] ) != "" ? $output[1] : false;
	}

	private function convert_url( $url ) {
		return str_replace( "\\", "", $url );
	}

	public function media_info_legacy( $url ) {
		$path          = parse_url( $url, PHP_URL_PATH );
		$pieces        = explode( '/', $path );
		$id            = $pieces[2];
		$result        = aiovd_url_get_contents( $url );
		$video_HD_link = "https://img-9gag-fun.9cache.com/photo/" . $id . "_460sv.mp4";
		$video_SD_link = "https://img-9gag-fun.9cache.com/photo/" . $id . "_460svwm.webm";
		if ( $video_SD_link ) {
			$data['found'] = 1;
			$data['id']    = $id;
			$links         = array();
			$links['SD']   = $video_SD_link;
			if ( ! empty( $video_HD_link ) ) {
				$links['HD'] = $video_HD_link;
			}
			$image         = "http://images-cdn.9gag.com/photo/" . $id . "_460s.jpg";
			$data['image'] = $image;
			if ( $result ) {
				$title = aiovd_get_string_between( $result, '<meta property="og:title" content="', '" />' );;
				$data['title'] = $title;
			}
			$format_codes = array(
				"SD" => array(
					"order"      => "1",
					"height"     => "{{height}}",
					"ext"        => "mp4",
					"resolution" => "SD",
					"video"      => "true",
					"video_only" => "false"
				),
				"HD" => array(
					"order"      => "2",
					"height"     => "{{height}}",
					"ext"        => "mp4",
					"resolution" => "HD",
					"video"      => "true",
					"video_only" => "false"
				)
			);
			$videos       = array();
			foreach ( $format_codes as $format_id => $format_data ) {
				if ( isset( $links[ $format_id ] ) ) {
					$link             = array();
					$link['data']     = $format_data;
					$link['formatId'] = $format_id;
					$link['order']    = $format_data['order'];
					$link['url']      = $links[ $format_id ];
					$link['title']    = $title . "." . $format_data['ext'];
					array_push( $videos, $link );
				}
			}
			$data['videos'] = $videos;
		}
		$media_info          = $data;
		$video["source_key"] = "9gag";
		$video["source_url"] = "9gag";
		$video["title"]      = $media_info["title"];
		$video["thumbnail"]  = $media_info["image"];
		$i                   = 0;
		foreach ( $media_info["videos"] as $current ) {
			$video["links"][ $i ]["url"]     = $current["url"];
			$video["links"][ $i ]["type"]    = "mp4";
			$video["links"][ $i ]["size"]    = aiovd_get_file_size( $video["links"][ $i ]["url"] );
			$video["links"][ $i ]["quality"] = $current["formatId"];
			$video["links"][ $i ]["mute"]    = false;
			$i ++;
		}

		return $video;
	}
}