<?php

$source = source_map( $source_key );

?>

<div class="aiovd-download">

    <div class="download-info">
        <div class="download-source">
            <span>Source:</span>
            <a href="<?php echo $source_url; ?>" target="_blank">
                <img class="source-icon" src="<?php echo AIOVD_ASSETS . '/images/socials/' . $source_key.'.png'; ?>" alt="<?php echo $source['name']; ?>">
                <span> <?php echo $source['name']; ?></span>
                <img class="open-external" src="<?php echo AIOVD_ASSETS . '/images/external.png'; ?>" alt="Open Source">
            </a>
        </div>

        <h4 class="download-title"><?php echo $title; ?></h4>
    </div>

	<?php

	$video_url = '';

	$audio_html = '';
	$mute_html  = '';
	$image_html = '';
	$video_html = '';

	$template
		= '<div class="download-link %1$s" data-link="%3$s"> <img class="media" src="%2$s" alt=""> <span class="quality">%4$s</span><span class="type">%5$s</span><span class="size">(%6$s)</span> 
<img class="video_download" src="'.AIOVD_ASSETS.'/images/download.svg'.'" alt="Download"></div>';

	$i = 0;
	foreach ( $links as $link ) {

		extract( $link );

		if ( empty( $url ) ) {
			return;
		}

		$media         = 'video';
		$download_url = add_query_arg( [
			                               'action' => 'aiovd_download',
			                               'source' => $source_key,
			                               'index'  => base64_encode( $i )
		                               ] );

		if ( $type == "m4a" || $type == "mp3" || stristr( $quality, 'kbps' ) ) {
			$media = 'audio';

			$audio_html .= sprintf( $template, $media, AIOVD_ASSETS."/images/media/$media.svg", $download_url, $quality, $type, $size );

		} elseif ( ( $source_key == "youtube" && $mute ) || ( $type == "mp4" && $mute ) ) {
			$media = 'mute';

			$mute_html .= sprintf( $template, $media, AIOVD_ASSETS."/images/media/$media.svg", $download_url, $quality, $type, $size );

		} elseif ( $type == "jpg" ) {
			//$media = 'image';

			$image_html .= sprintf( $template, $media, AIOVD_ASSETS."/images/media/$media.svg", $download_url, $quality, $type, $size );

		} else {

		    if(empty($video_url)){
		        $video_url = $url;
            }

			$video_html .= sprintf( $template, $media, AIOVD_ASSETS."/images/media/$media.svg", $download_url, $quality, $type, $size );
		}

		$i ++;
	}


	?>

    <div class="download-video">
        <video controls poster="<?php echo $thumbnail; ?>" src="<?php echo $video_url; ?>">
    </div>

	<?php
	$layout = aiovd_get_settings( 'download_layout', 'grid', 'aiovd_display_settings' );
	?>
    <div class="download-links layout-<?php echo $layout; ?>">
		<?php echo $video_html . $audio_html . $mute_html . $image_html; ?>
    </div>
</div>