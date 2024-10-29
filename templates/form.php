<?php
$label             = aiovd_get_settings( 'label', 'Insert video link to download', 'aiovd_display_settings' );
$placeholder       = aiovd_get_settings( 'placeholder', 'Paste/ Insert the video URL', 'aiovd_display_settings' );
$download_btn_text = aiovd_get_settings( 'download_btn_text', 'Download', 'aiovd_display_settings' );
?>

<div class="aiovd-form-wrap">
    <div class="aiovd-form">


		<?php
		echo ! empty( $label ) ? sprintf( '<label for="video_url">%s</label>', $label ) : '';
		?>

        <p class="aiovd-form-error"></p>

        <div class="aiovd-form-group">
            <input type="text" id="video_url" placeholder="<?php echo $placeholder; ?>" name="video_url"/>
            <button id="aiovd_submit" class="aiovd-form-button" type="button">
                <img src="<?php echo AIOVD_ASSETS.'/images/loading.webp'; ?>" alt="Downloading...">
				<?php echo $download_btn_text; ?>
            </button>
        </div>

    </div>
</div>

<div id="aiovd-download"></div>
