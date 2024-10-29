<?php

if ( !function_exists( 'aiovd_fs' ) ) {
    // Create a helper function for easy SDK access.
    function aiovd_fs()
    {
        global  $aiovd_fs ;
        
        if ( !isset( $aiovd_fs ) ) {
            // Include Freemius SDK.
            require_once AIOVD_PATH . '/freemius/start.php';
            $aiovd_fs = fs_dynamic_init( array(
                'id'             => '7435',
                'slug'           => 'all-in-one-video-downloader',
                'type'           => 'plugin',
                'public_key'     => 'pk_baab8c7119d9fd46d154d96ac5470',
                'is_premium'     => false,
                'premium_suffix' => 'PRO',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'    => 'all-in-one-video-downloader',
                'contact' => true,
                'support' => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $aiovd_fs;
    }
    
    // Init Freemius.
    aiovd_fs();
    // Signal that SDK was initiated.
    do_action( 'aiovd_fs_loaded' );
}
