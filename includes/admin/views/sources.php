<h3 style="margin: 0;">Enable/ Disable Video Download Sources:</h3>

<p class="description">Toggle the switch to enable/ disable the source.</p>

<?php
$sources        = aiovd_get_settings( 'source', [], 'aiovd_source_settings' );
$all_count      = count( source_map() );

$active_count = count( array_filter( $sources, function ( $source ) {
	return $source == 'on';
} ) );

$inactive_count = $all_count - $active_count;

?>

<div class="tab-info">
    <span class="all current">All Sources <span>(<?php echo $all_count; ?>)</span></span> |
    <span class="active">Active <span>(<?php echo $active_count; ?>)</span></span> |
    <span class="inactive">Inactive <span>(<?php echo $inactive_count; ?>)</span></span>
</div>

<div class="video-sources">
	<?php


	foreach ( source_map() as $key => $source ) {
		$is_pro    = isset( $source['pro'] );
		$is_active = !empty($sources[ $key ]) && 'on' == $sources[ $key ];


		?>
        <div class="video-source <?php echo $is_pro && !aiovd_fs()->can_use_premium_code__premium_only() ? 'aiovd_pro'
			: ''; ?> <?php echo $is_active ? 'active' : 'inactive'; ?>">

	        <?php

	        if ( $is_pro ) {
	        	printf('<span class="pro-badge">PRO</span>');
	        }

	        ?>

            <img src="<?php echo AIOVD_ASSETS . "/images/socials/$key.png"; ?>" alt="<?php echo $key ?>">
            <span class="source-name"><?php echo $source['name']; ?></span>

            <div class="switch">
                <div class="wp-military-switch">
                    <input type="hidden" name="aiovd_source_settings[source][<?php echo $key; ?>]" value="off"/>
                    <input
                            type="checkbox"
                            name="aiovd_source_settings[source][<?php echo $key; ?>]"
                            id="aiovd_source_settings[source][<?php echo $key ?>]"
                            <?php echo $is_active ? 'checked' : '' ?>
                            value="on"/>
                    <div>
                        <label for="aiovd_source_settings[source][<?php echo $key; ?>]"></label>
                    </div>
                </div>
            </div>

        </div>

	<?php } ?>
</div>