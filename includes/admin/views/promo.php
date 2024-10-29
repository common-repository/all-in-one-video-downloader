<?php

$is_hidden = isset( $is_hidden ) ? $is_hidden : true;

$transient_key  = 'aiovd_promo_time';
$countdown_time = get_transient( $transient_key );

if ( !$countdown_time ) {

	$date = date( 'Y-m-d-H-i', strtotime( '+ 14 hours' ) );

	$date_parts = explode( '-', $date );

	$countdown_time = [
		'year'   => $date_parts[0],
		'month'  => $date_parts[1],
		'day'    => $date_parts[2],
		'hour'   => $date_parts[3],
		'minute' => $date_parts[4],
	];

	set_transient( $transient_key, $countdown_time, 14 * HOUR_IN_SECONDS );

}

$title = 'Unlock the PRO features';

?>

<style>
    .syotimer {
        text-align: center;
        margin: 30px auto 0;
        padding: 0 0 10px;
    }

    .syotimer-cell {
        display: inline-block;
        margin: 0 5px;
        width: 60px;
        background: url(<?php echo AIOVD_ASSETS.'/images/timer.png'; ?>) no-repeat;
        background-size: contain;
    }

    .syotimer-cell__value {
        font-size: 24px;
        height: 60px;
        line-height: 2;
        margin: 0 0 5px;
        color: orangered;

    }

    .syotimer-cell__unit {
        font-family: Arial, serif;
        font-size: 12px;
        text-transform: uppercase;
        color: #F8D83B;
    }
</style>
<div class="aiovd-promo <?php echo $is_hidden ? 'hidden' : ''; ?>">
    <div class="aiovd-promo-inner">

		<?php if ( $is_hidden ) { ?>
            <span class="close-promo">&times;</span>
		<?php } ?>


        <img src="<?php echo AIOVD_ASSETS . '/images/crown.svg'; ?>" class="promo-img">

        <h3><?php echo $title; ?></h3>
        <h3 class="discount-text">50% OFF</h3>
        <h3 style="font-size: 18px;">LIMITED TIME ONLY</h3>
        <div class="simple_timer"></div>
        <a href="<?php echo AIOVD_PRICING; ?>" >GET PRO</a>

    </div>
</div>

<script>
    (function ($) {
        $(document).ready(function () {

            $(document).on('click', '.close-promo', function () {
                $(this).closest('.aiovd-promo').addClass('hidden');
            });

            if (typeof window.timer_set === 'undefined') {
                window.timer_set = $('.simple_timer').syotimer({
                    year: <?php echo $countdown_time['year']; ?>,
                    month: <?php echo $countdown_time['month']; ?>,
                    day: <?php echo $countdown_time['day']; ?>,
                    hour: <?php echo $countdown_time['hour']; ?>,
                    minute: <?php echo $countdown_time['minute']; ?>
                });
            }
        })
    })(jQuery);
</script>