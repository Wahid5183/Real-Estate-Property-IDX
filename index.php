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

function custom_breadcrumb() {
    echo '<nav aria-label="breadcrumb">';
    echo '<ol class="breadcrumb">';

    echo '<li class="breadcrumb-item"><a href="' . home_url() . '">Home</a></li>';

    if (is_post_type_archive('property')) {
        echo '<li class="breadcrumb-item active" aria-current="page">Properties</li>';
    } elseif (is_singular('property')) {
        echo '<li class="breadcrumb-item"><a href="' . get_post_type_archive_link('property') . '">Properties</a></li>';
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_category() || is_tax('property_category')) {
        $category = get_queried_object();
        echo '<li class="breadcrumb-item active" aria-current="page">' . $category->name . '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}



// Define your access token (replace with your actual token)
define( 'MLS_ACCESS_TOKEN', 'd26d2f161be61675e195b86a4e1587e2' );

include_once plugin_dir_path( __FILE__ ) . 'property-post/register-property-post.php';
include_once plugin_dir_path( __FILE__ ) . 'property-post/post-meta.php';
include_once plugin_dir_path( __FILE__ ) . 'Admin-menu/property-fetch-menu.php';

function add_bootstrap_property(){
    if ( is_singular( 'property' ) ) {
        wp_enqueue_style( 'add_bootstrap_style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3', 'all' );

        wp_enqueue_style( 'custom_style', plugin_dir_url( __FILE__ ) . '/property-post/templates/assets/single.css', array(), '4.0', 'all' );
        wp_enqueue_style( 'add_icon_style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1', 'all' );

        
        wp_enqueue_script( 'add_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3', true );
    }
    if ( is_archive( 'property' ) ) {
        wp_enqueue_style( 'add_bootstrap_style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3', 'all' );
        wp_enqueue_style( 'custom_style', plugin_dir_url( __FILE__ ) . '/property-post/templates/assets/archive.css', array(), '4.0', 'all' );
        wp_enqueue_style( 'add_icon_style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1', 'all' );
        wp_enqueue_script( 'add_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3', true );
    }
}

add_action( 'wp_enqueue_scripts', 'add_bootstrap_property' );

