<?php
/**
 * Plugin Name: Realty Smart Homes
 * Description: A WordPress IDX plugin to fetch MLS listings from RealtyFeed via the Bridge Interactive API and store them as custom posts.
 * Version: 1.0.0
 * Author: Samuel
 */

 if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define your access token (replace with your actual token)
define( 'MLS_ACCESS_TOKEN', 'd26d2f161be61675e195b86a4e1587e2' );

include_once plugin_dir_path( __FILE__ ) . 'property-post/register-property-post.php';
include_once plugin_dir_path( __FILE__ ) . 'property-post/post-meta.php';
include_once plugin_dir_path( __FILE__ ) . 'Admin-menu/property-fetch-menu.php';

function add_bootstrap_property(){
    if ( is_singular( 'property' ) ) {
        wp_enqueue_style( 'add_bootstrap_style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3', 'all' );
        wp_enqueue_script( 'add_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '1.0.0', true );
    }
}

add_action( 'wp_enqueue_scripts', 'add_bootstrap_property' );

