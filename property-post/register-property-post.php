<?php

/**
 * Activation hook: Register the custom post type and flush rewrite rules.
 */
function pcmp_activate_plugin() {
    // Register the post type.
    pcmp_register_property_post_type();
    // Flush rewrite rules to make sure our custom post type URLs work.
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pcmp_activate_plugin' );

/**
 * Register custom post type "property".
 */
function pcmp_register_property_post_type() {
    $labels = array(
        'name'               => 'Properties',
        'singular_name'      => 'Property',
        'menu_name'          => 'Properties',
        'name_admin_bar'     => 'Property',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Property',
        'new_item'           => 'New Property',
        'edit_item'          => 'Edit Property',
        'view_item'          => 'View Property',
        'all_items'          => 'All Properties',
        'search_items'       => 'Search Properties',
        'menu_icon'       => 'dashicons-building',
        'not_found'          => 'No properties found.',
        'not_found_in_trash' => 'No properties found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'property' ),
        // We only need the title here (Property Name). Everything else is in meta.
        'supports'           => array( 'title'),
        'show_in_rest'       => true, // Enables Gutenberg and REST API support.
    );

    register_post_type( 'property', $args );
}
add_action( 'init', 'pcmp_register_property_post_type' );