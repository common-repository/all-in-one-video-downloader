<?php

defined( 'ABSPATH' ) || exit();

const _REQUEST_USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36";

spl_autoload_register( 'aiovd_autoload' );

function aiovd_autoload( $class_name ) {
	$class_name = strtolower( str_replace( [ '\\', '_' ], [ '-', '-' ], $class_name ) );

	if ( stristr( $class_name, 'module' ) ) {
		$class_name = str_replace( 'aiovd-module-', '', $class_name );

		if ( isset( source_map( $class_name )['pro'] ) ) {
			$class_name .= '__premium_only';
		}

		$file       = AIOVD_INCLUDES . '/module/' . $class_name . '.php';
	} elseif ( stristr( $class_name, 'admin' ) ) {
		$class_name = str_replace( 'admin-', '', $class_name );
		$file       = AIOVD_INCLUDES . '/admin/class-' . $class_name . '.php';
	} else {
		$file = AIOVD_INCLUDES . '/class-' . $class_name . '.php';
	}

	if ( file_exists( $file ) ) {
		include $file;
	}
}

function source_map( $key = null ) {
	$sources = [
		'facebook'    => [
			'name' => 'Facebook',
			'icon' => 'fab fa-facebook',
			'pro'  => true,
		],
		'akillitv'    => [
			'name' => 'AkilliTV',
			'icon' => 'fas fa-film',
		],
		'bandcamp'    => [
			'name' => 'Bandcamp',
			'icon' => 'fab fa-bandcamp',
		],
		'bitchute'    => [
			'name' => 'Bitchute',
			'icon' => 'fas fa-volleyball-ball',
		],
		'blogger'     => [
			'name' => 'Blogger',
			'icon' => 'fas fa-th-large',
			'pro'  => true,

		],
		'break'       => [
			'name' => 'Break.com',
			'icon' => 'fas fa-tv',
		],
		'buzzfeed'    => [
			'name' => 'Buzzfeed',
			'icon' => 'fas fa-wifi',
			'pro'  => true,

		],
		'dailymotion' => [
			'name' => 'Dailymotion',
			'icon' => 'fas fa-play-circle',
			'pro'  => true,

		],
		'douyin'      => [
			'name' => 'Douyin',
			'icon' => 'fas fa-play-circle',
		],
		'espn'        => [
			'name' => 'ESPN',
			'icon' => 'fas fa-football-ball',
			'pro'  => true,

		],
		'flickr'      => [
			'name' => 'Flickr',
			'icon' => 'fab fa-flickr',
			'pro'  => true,

		],
		'gaana'       => [
			'name' => 'Gaana',
			'icon' => 'fas fa-play-circle',
			'pro'  => true,

		],
		'imdb'        => [
			'name' => 'IMDB',
			'icon' => 'fab fa-imdb',
		],
		'imgur'       => [
			'name' => 'Imgur',
			'icon' => 'fas fa-image',
			'pro'  => true,

		],
		'instagram'   => [
			'name' => 'Instagram',
			'icon' => 'fab fa-instagram',
			'pro'  => true,

		],
		'izlesene'    => [
			'name' => 'Izlesene',
			'icon' => 'fas fa-play-circle',
		],
		'kwai'        => [
			'name' => 'Kwai',
			'icon' => 'fas fa-file-video',
		],
		'likee'       => [
			'name' => 'Likee',
			'icon' => 'fas fa-thumbs-up',
			'pro'  => true,

		],
		'linkedin'    => [
			'name' => 'Linkedin',
			'icon' => 'fab fa-linkedin',
			'pro'  => true,

		],
		'liveleak'    => [
			'name' => 'LiveLeak',
			'icon' => 'fas fa-play-circle',
		],
		'mashable'    => [
			'name' => 'Mashable',
			'icon' => 'fab fa-simplybuilt',
		],
		'9gag'        => [
			'name' => '9GAG',
			'icon' => 'fab fa-gg',
		],
		'ok.ru'       => [
			'name' => 'Ok.ru',
			'icon' => 'fas fa-play-circle',
		],
		'pinterest'   => [
			'name' => 'Pinterest',
			'icon' => 'fab fa-pinterest',
			'pro'  => true,

		],
		'reddit'      => [
			'name' => 'Reddit',
			'icon' => 'fab fa-reddit',
			'pro'  => true,

		],
		'soundcloud'  => [
			'name' => 'Soundcloud',
			'icon' => 'fab fa-soundcloud',
			'pro'  => true,

		],
		'streamable'  => [
			'name' => 'Streamable',
			'icon' => 'fas fa-play-circle',
		],
		'ted'         => [
			'name' => 'Ted',
			'icon' => 'fas fa-play-circle',
		],
		'tiktok'      => [
			'name' => 'Tiktok',
			'icon' => 'fas fa-music',
			'pro'  => true,

		],
		'tumblr'      => [
			'name' => 'Tumblr',
			'icon' => 'fab fa-tumblr',
			'pro'  => true,

		],
		'twitch'      => [
			'name' => 'Twitch',
			'icon' => 'fab fa-twitch',
		],
		'twitter'     => [
			'name' => 'Twitter',
			'icon' => 'fab fa-twitter',
			'pro'  => true,

		],
		'vimeo'       => [
			'name' => 'Vimeo',
			'icon' => 'fab fa-vimeo',
			'pro'  => true,

		],
		'vk'   => [
			'name' => 'VK',
			'icon' => 'fab fa-vk',
		],
		'youtube'     => [
			'name' => 'Youtube',
			'icon' => 'fab fa-youtube',
		],
	];

	ksort( $sources );

	return ! empty( $key ) ? $sources[ $key ] : $sources;
}

function aiovd_source_active( $key ) {
	$sources = aiovd_get_settings( 'source', [], 'aiovd_source_settings' );
	if ( empty( $sources[ $key ] ) ) {
		return true;
	}

	if ( 'off' == $sources[ $key ] ) {
		return false;
	}

	return true;
}

function aiovd_get_settings( $field, $default = '', $section = 'aiovd_general_settings' ) {
	$settings = get_option( $section, [] );

	return ! empty( $settings[ $field ] ) ? $settings[ $field ] : $default;
}

function aiovd_start_session() {

	if ( ! session_id() ) {
		session_start();
	}

	if ( empty( $_SESSION['token'] ) ) {
		$_SESSION['token'] = aiovd_generate_csrf_token();
	}
}

/**-- AIOV DOWNLOAD --**/

function option( $option_name = "general_settings", $echo = false ) {
	$option_value = database::find_option( $option_name )["option_value"];
	if ( $echo === true ) {
		echo $option_value;
	} else {
		return $option_value;
	}
}

function aiovd_get_proxy() {
	$proxy = database::find_random_proxy();
	if ( ! empty( $_SESSION["proxy"]["ip"] ?? null ) ) {
		return $_SESSION["proxy"];
	} else if ( ! empty( $proxy["ip"] ) ) {
		$_SESSION["proxy"] = $proxy;

		return $proxy;
	} else {
		return false;
	}
}

function aiovd_generate_csrf_token() {
	if ( defined( 'PHP_MAJOR_VERSION' ) && PHP_MAJOR_VERSION > 5 ) {
		return bin2hex( random_bytes( 32 ) );
	} else {
		if ( function_exists( 'mcrypt_create_iv' ) ) {
			return bin2hex( mcrypt_create_iv( 32, MCRYPT_DEV_URANDOM ) );
		} else {
			return bin2hex( openssl_random_pseudo_bytes( 32 ) );
		}
	}
}

function aiovd_return_json( $video ) {

	unset( $_SESSION['result'][ $_SESSION["token"] ] );

	if ( empty( $video["links"]["0"]["url"] ) ) {
		wp_send_json_success(['error' => 'No video links found!']);
	} else {
		$video["video_url"] = $_SESSION['video'][ $_SESSION["token"] ];
		$video["client_ip"] = $_SESSION['ip'][ $_SESSION["token"] ];

		//database::create_log( $video );

		$_SESSION["result"][ $_SESSION["token"] ] = $video;

		ob_start();
		aiovd()->get_template( 'download', $video );
		$html = ob_get_clean();

		wp_send_json_success( ['html' => $html] );
	}
}

function aiovd_format_seconds( $seconds ) {
	return gmdate( ( $seconds > 3600 ? "H:i:s" : "i:s" ), $seconds );
}

function aiovd_get_string_between( $string, $start, $end ) {
	$string = ' ' . $string;
	$ini    = strpos( $string, $start );
	if ( $ini == 0 ) {
		return '';
	}
	$ini += strlen( $start );
	$len = strpos( $string, $end, $ini ) - $ini;

	return substr( $string, $ini, $len );
}

function aiovd_sort_by_quality( $a, $b ) {
	return (int) $a['quality'] - (int) $b['quality'];
}

function aiovd_format_size( $bytes ) {
	switch ( $bytes ) {
		case $bytes < 1024:
			$size = $bytes . " B";
			break;
		case $bytes < 1048576:
			$size = round( $bytes / 1024, 2 ) . " KB";
			break;
		case $bytes < 1073741824:
			$size = round( $bytes / 1048576, 2 ) . " MB";
			break;
		case $bytes < 1099511627776:
			$size = round( $bytes / 1073741824, 2 ) . " GB";
			break;
	}
	if ( ! empty( $size ) ) {
		return $size;
	} else {
		return "";
	}
}

function aiovd_get_proxy_type( $id ) {
	switch ( $id ?? 0 ) {
		case 1:
			$type = CURLPROTO_HTTPS;
			break;
		case 2:
			$type = CURLPROXY_SOCKS4;
			break;
		case 3:
			$type = CURLPROXY_SOCKS5;
			break;
		default:
			$type = CURLPROXY_HTTP;
			break;
	}

	return $type;
}

function aiovd_url_get_contents( $url, $enable_proxies = false ) {
	$cookie_file_name = $_SESSION["token"] . ".txt";
	$cookie_file      = join( DIRECTORY_SEPARATOR, [ sys_get_temp_dir(), $cookie_file_name ] );
	$ch               = curl_init();
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt( $ch, CURLOPT_USERAGENT, _REQUEST_USER_AGENT );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
	if ( $enable_proxies ) {
		if ( ! empty( $_SESSION["proxy"] ?? null ) ) {
			$proxy = $_SESSION["proxy"];
		} else {
			$proxy             = aiovd_get_proxy();
			$_SESSION["proxy"] = $proxy;
		}
		curl_setopt( $ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port'] );
		curl_setopt( $ch, CURLOPT_PROXYTYPE, aiovd_get_proxy_type( $proxy['type'] ) );
		if ( ! empty( $proxy['username'] ) && ! empty( $proxy['password'] ) ) {
			curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password'] );
		}
		$chunkSize = 1000000;
		curl_setopt( $ch, CURLOPT_TIMEOUT, (int) ceil( 3 * ( round( $chunkSize / 1048576, 2 ) / ( 1 / 8 ) ) ) );
	}
	curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_file );
	if ( file_exists( $cookie_file ) ) {
		curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie_file );
	}
	$data = curl_exec( $ch );
	curl_close( $ch );

	return $data;
}

function aiovd_unshorten( $url, $enable_proxies = false, $max_redirs = 3 ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_HEADER, true );
	curl_setopt( $ch, CURLOPT_NOBODY, true );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch, CURLOPT_MAXREDIRS, $max_redirs );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 15 );
	curl_setopt( $ch, CURLOPT_USERAGENT, _REQUEST_USER_AGENT );
	curl_setopt( $ch, CURLOPT_URL, $url );
	if ( $enable_proxies ) {
		if ( ! empty( $_SESSION["proxy"] ?? null ) ) {
			$proxy = $_SESSION["proxy"];
		} else {
			$proxy             = aiovd_get_proxy();
			$_SESSION["proxy"] = $proxy;
		}
		curl_setopt( $ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port'] );
		curl_setopt( $ch, CURLOPT_PROXYTYPE, aiovd_get_proxy_type( $proxy['type'] ) );
		if ( ! empty( $proxy['username'] ) && ! empty( $proxy['password'] ) ) {
			curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password'] );
		}
		$chunkSize = 1000000;
		curl_setopt( $ch, CURLOPT_TIMEOUT, (int) ceil( 3 * ( round( $chunkSize / 1048576, 2 ) / ( 1 / 8 ) ) ) );
	}
	curl_exec( $ch );
	$url = curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL );
	curl_close( $ch );

	return $url;
}

function aiovd_get_file_size( $url, $enable_proxies = false, $format = true ) {
	$cookie_file_name = $_SESSION["token"] . ".txt";
	$cookie_file      = join( DIRECTORY_SEPARATOR, [ sys_get_temp_dir(), $cookie_file_name ] );
	$result           = - 1;  // Assume failure.
	// Issue a HEAD request and follow any redirects.
	$curl = curl_init( $url );
	curl_setopt( $curl, CURLOPT_NOBODY, true );
	curl_setopt( $curl, CURLOPT_HEADER, true );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_REFERER, '' );
	//curl_setopt($curl, CURLOPT_INTERFACE, '');
	curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
	curl_setopt( $curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $curl, CURLOPT_USERAGENT, _REQUEST_USER_AGENT );
	if ( $enable_proxies ) {
		if ( ! empty( $_SESSION["proxy"] ?? null ) ) {
			$proxy = $_SESSION["proxy"];
		} else {
			$proxy             = aiovd_get_proxy();
			$_SESSION["proxy"] = $proxy;
		}
		curl_setopt( $curl, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port'] );
		curl_setopt( $curl, CURLOPT_PROXYTYPE, aiovd_get_proxy_type( $proxy['type'] ) );
		if ( ! empty( $proxy['username'] ) && ! empty( $proxy['password'] ) ) {
			curl_setopt( $curl, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password'] );
		}
		$chunkSize = 1000000;
		curl_setopt( $curl, CURLOPT_TIMEOUT, (int) ceil( 3 * ( round( $chunkSize / 1048576, 2 ) / ( 1 / 8 ) ) ) );
	}
	if ( file_exists( $cookie_file ) ) {
		curl_setopt( $curl, CURLOPT_COOKIEFILE, $cookie_file );
	}
	$headers = curl_exec( $curl );
	if ( curl_errno( $curl ) == 0 ) {
		$result = (int) curl_getinfo( $curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD );
	}
	curl_close( $curl );
	if ( $result > 100 ) {
		switch ( $format ) {
			case true:
				return aiovd_format_size( $result );
				break;
			case false:
				return $result;
				break;
			default:
				return aiovd_format_size( $result );
				break;
		}
	} else {
		return "";
	}
}

function aiovd_beautify_filename( $filename ) {
	// reduce consecutive characters
	$filename = preg_replace( array(
		                          // "file   name.zip" becomes "file-name.zip"
		                          '/ +/',
		                          // "file___name.zip" becomes "file-name.zip"
		                          '/_+/',
		                          // "file---name.zip" becomes "file-name.zip"
		                          '/-+/'
	                          ),
	                          '-',
	                          $filename );
	$filename = preg_replace( array(
		                          // "file--.--.-.--name.zip" becomes "file.name.zip"
		                          '/-*\.-*/',
		                          // "file...name..zip" becomes "file.name.zip"
		                          '/\.{2,}/'
	                          ),
	                          '.',
	                          $filename );
	// lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
	$filename = mb_strtolower( $filename, mb_detect_encoding( $filename ) );
	// ".file-name.-" becomes "file-name"
	$filename = trim( $filename, '.-' );

	return $filename;
}

function aiovd_filter_filename( $filename, $beautify = true ) {
	// sanitize filename
	$filename = preg_replace( '~
        [<>:"/\\|?*]|            # file system reserved https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
        [\x00-\x1F]|             # control characters http://msdn.microsoft.com/en-us/library/windows/desktop/aa365247%28v=vs.85%29.aspx
        [\x7F\xA0\xAD]|          # non-printing characters DEL, NO-BREAK SPACE, SOFT HYPHEN
        [#\[\]@!$&\'()+,;=]|     # URI reserved https://tools.ietf.org/html/rfc3986#section-2.2
        [{}^\~`]                 # URL unsafe characters https://www.ietf.org/rfc/rfc1738.txt
        ~x',
	                          '-',
	                          $filename );
	// avoids ".", ".." or ".hiddenFiles"
	$filename = ltrim( $filename, '.-' );
	// optional beautification
	if ( $beautify ) {
		$filename = aiovd_beautify_filename( $filename );
	}
	// maximize filename length to 255 bytes http://serverfault.com/a/9548/44086
	$ext      = pathinfo( $filename, PATHINFO_EXTENSION );
	$filename = mb_strcut( pathinfo( $filename, PATHINFO_FILENAME ),
	                       0,
	                       255 - ( $ext ? strlen( $ext ) + 1 : 0 ),
	                       mb_detect_encoding( $filename ) ) . ( $ext ? '.' . $ext : '' );

	return $filename;
}

function aiovd_sanitize_filename( $string, $ftype ) {
	return ( aiovd_filter_filename( $string ) ?? "video" ) . "." . $ftype;
}

function aiovd_get_main_domain( $host ) {
	$main_host = strtolower( trim( $host ) );
	$count     = substr_count( $main_host, '.' );
	if ( $count === 2 ) {
		if ( strlen( explode( '.', $main_host )[1] ) > 3 ) {
			$main_host = explode( '.', $main_host, 2 )[1];
		}
	} else if ( $count > 2 ) {
		$main_host = aiovd_get_main_domain( explode( '.', $main_host, 2 )[1] );
	}

	return $main_host;
}

function aiovd_force_download( $remoteURL, $vidName, $ftype ) {
	$fsize = aiovd_get_file_size( $remoteURL, false, false );
	header( 'Content-Description: File Transfer' );
	header( 'Content-Type: application/octet-stream' );
	header( 'Content-Disposition: attachment; filename="' . htmlspecialchars_decode( aiovd_sanitize_filename( $vidName,
	                                                                                                    $ftype ) ) . '"' );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Pragma: public' );
	if ( $fsize > 0 ) {
		header( 'Content-Length: ' . $fsize );
	}
	header( 'Connection: Close' );
	ob_clean();
	flush();

	// Activate flush
	if ( function_exists( 'apache_setenv' ) ) {
		apache_setenv( 'no-gzip', 1 );
	}

	@ini_set( 'zlib.output_compression', false );
	ini_set( 'implicit_flush', true );

	// CURL Process
	$ch               = curl_init();
	$chunkEnd         = $chunkSize = 1000000;  // 1 MB in bytes
	$tries            = $count = $chunkStart = 0;
	$cookie_file_name = $_SESSION["token"] . ".txt";
	$cookie_file      = join( DIRECTORY_SEPARATOR, [ sys_get_temp_dir(), $cookie_file_name ] );
	while ( $fsize > $chunkStart ) {
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_URL, $remoteURL );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_BINARYTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_USERAGENT, _REQUEST_USER_AGENT );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_RANGE, $chunkStart . '-' . $chunkEnd );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_BUFFERSIZE, $chunkSize );
		if ( ! empty( $_SESSION["proxy"] ) ) {

			$proxy = $_SESSION["proxy"];
			curl_setopt( $ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port'] );
			curl_setopt( $ch, CURLOPT_PROXYTYPE, aiovd_get_proxy_type( $proxy['type'] ) );
			if ( ! empty( $proxy['username'] ) && ! empty( $proxy['password'] ) ) {
				curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password'] );
			}
			$chunkSize = 1000000;
			curl_setopt( $ch, CURLOPT_TIMEOUT, (int) ceil( 3 * ( round( $chunkSize / 1048576, 2 ) / ( 1 / 8 ) ) ) );
		}
		if ( file_exists( $cookie_file ) ) {
			curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_file );
		}
		//curl_setopt($ch, CURLOPT_MAX_RECV_SPEED_LARGE, "100");
		$output   = curl_exec( $ch );
		$curlInfo = curl_getinfo( $ch );
		if ( $curlInfo['http_code'] != "206" && $tries < 10 ) {
			$tries ++;
			continue;
		} else {
			$tries = 0;
			echo $output;
			flush();
			ob_implicit_flush( true );
			if ( ob_get_length() > 0 ) {
				ob_end_flush();
			}
		}
		$chunkStart += $chunkSize;
		$chunkStart += ( $count == 0 ) ? 1 : 0;
		$chunkEnd   += $chunkSize;
		$count ++;
		//sleep(10);
	}
	curl_close( $ch );
	exit;
}

function aiovd_force_download_legacy( $url, $title, $type ) {
	$context_options = array(
		"ssl" => array(
			"verify_peer"      => false,
			"verify_peer_name" => false,
		)
	);
	header( 'Content-Description: File Transfer' );
	header( 'Content-Type: application/octet-stream' );
	header( 'Content-Disposition: attachment; filename="' . aiovd_sanitize_filename( $title, $type ) . '"' );
	header( "Content-Transfer-Encoding: binary" );
	header( 'Expires: 0' );
	header( 'Pragma: public' );
	$file_size = aiovd_get_file_size( $url, false, false );
	if ( $file_size > 100 ) {
		header( 'Content-Length: ' . $file_size );
	}
	if ( isset( $_SERVER['HTTP_REQUEST_USER_AGENT'] ) && strpos( $_SERVER['HTTP_REQUEST_USER_AGENT'],
	                                                             'MSIE' ) !== false ) {
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Pragma: public' );
	}
	header( 'Connection: Close' );
	ob_clean();
	flush();
	readfile( $url, "", stream_context_create( $context_options ) );
	exit;
}

function aiovd_get_client_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}