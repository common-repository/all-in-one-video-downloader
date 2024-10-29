<?php

namespace AIOVD\Module;

defined( 'ABSPATH' ) || exit();

class Bandcamp {
	public $enable_proxies = false;

	private $source = "bandcamp";

	public function __construct() {
		if ( ! aiovd_source_active( $this->source ) ) {
			wp_send_json_success( [ 'error' => 'Doesn\'t support downloading from this website.' ] );
		}
	}

	function media_info( $url ) {
		$web_page  = aiovd_url_get_contents( $url, $this->enable_proxies );
		$embed_url = aiovd_get_string_between( $web_page, 'property="twitter:player" content="', '"' );
		if ( empty( $embed_url ) ) {
			return false;
		}
		$video["title"]      = aiovd_get_string_between( $web_page, '<title>', '</title>' );
		$video["source_key"] = "bandcamp";
		$video["source_url"] = $url;
		$video["thumbnail"]  = aiovd_get_string_between( $web_page, 'property="og:image" content="', '"' );
		$video["duration"]   = aiovd_format_seconds( aiovd_get_string_between( $web_page, 'itemprop="duration" content="', '"' ) );
		$embed_page          = aiovd_url_get_contents( $embed_url, $this->enable_proxies );
		$player_data         = aiovd_get_string_between( $embed_page, 'var playerdata =', ';' );
		$player_data         = json_decode( $player_data, true );
		$audio_url           = $player_data["tracks"][0]["file"]["mp3-128"];
		if ( empty( $audio_url ) ) {
			return false;
		}
		$video["links"][0]["url"]     = $audio_url;
		$video["links"][0]["type"]    = "mp3";
		$video["links"][0]["size"]    = aiovd_get_file_size( $audio_url, $this->enable_proxies );
		$video["links"][0]["quality"] = "128kbps";
		$video["links"][0]["mute"]    = false;

		return $video;
	}
}