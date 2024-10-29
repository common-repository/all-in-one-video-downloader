<div class="aiovd-supported-websites">
    <h3 class="aiovd-supported-websites-title">Supported <span>Websites</span></h3>

	<?php
	$sources        = aiovd_get_settings( 'source', [], 'aiovd_source_settings' );
	$active_sources = array_filter( $sources, function ( $val ) {
		return 'on' == $val;
	} );

	?>

    <div class="websites-group">

		<?php foreach ( $active_sources as $key => $val ) {
			$name = source_map( $key )['name'] ?? $key;

			$bg = ['bg_green', 'bg_purple', 'bg_pink']
			?>
            <div class="website <?php echo $bg[array_rand($bg)]; ?>">
                <img src="<?php echo AIOVD_ASSETS . '/images/socials/' . $key . '.png' ?>" alt="<?php echo $name; ?>">
                <span><?php echo $name; ?></span>
            </div>
		<?php } ?>

    </div>

</div>