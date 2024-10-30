<?php
if (!function_exists('add_action')) {
    $wp_root = '../../..';
    
    if (file_exists($wp_root.'/wp-load.php')) {
        require_once( $wp_root.'/wp-load.php' );
    } else {
        require_once( $wp_root.'/wp-config.php' );
    }
}

// Get referer to back in case of error
$referer = ( !empty( $_SERVER['HTTP_REFERER'] ) ) ? $_SERVER['HTTP_REFERER'] : get_bloginfo('url');

// Returns to referer or blog URL if the offer ID not getted
if( !isset( $_GET['offer_id'] ) )
    wp_redirect( $referer );

// Get offer ID and link
$offer_id   = esc_html( $_GET['offer_id'] );
$offer_link = $plugin_lomadee_wp_related_offers->get_offer_link( $offer_id );

wp_redirect( $offer_link );