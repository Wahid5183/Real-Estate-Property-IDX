<?php

/**
 * Activation hook: Register the custom post type and flush rewrite rules.
 */
function pcmp_activate_plugin() {
    // Register the post type.
    // Flush rewrite rules to make sure our custom post type URLs work.
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Contact Agent submissions table
    $table_name = $wpdb->prefix . 'property_contacts';
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        property_id bigint(20) NOT NULL,
        name varchar(100) NOT NULL,
        phone varchar(50) NOT NULL,
        email varchar(100) NOT NULL,
        message text NOT NULL,
        submission_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // Tour requests table
    $table_name_tours = $wpdb->prefix . 'property_tours';
    $sql_tours = "CREATE TABLE $table_name_tours (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        property_id bigint(20) NOT NULL,
        tour_date date NOT NULL,
        tour_time time NOT NULL,
        name varchar(100) NOT NULL,
        phone varchar(50) NOT NULL,
        email varchar(100) NOT NULL,
        message text NOT NULL,
        submission_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql_tours);

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pcmp_activate_plugin' );
register_deactivation_hook( __FILE__, 'pcmp_deactive_plugin' );

function pcmp_deactive_plugin(){
    flush_rewrite_rules();

}


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
        'not_found'          => 'No properties found.',
        'not_found_in_trash' => 'No properties found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'       => 'dashicons-building',

        'rewrite'            => array( 'slug' => 'property' ),
        // We only need the title here (Property Name). Everything else is in meta.
        'supports'           => array( 'title'),
        'show_in_rest'       => true, // Enables Gutenberg and REST API support.
    );

    register_post_type( 'property', $args );
}
add_action( 'init', 'pcmp_register_property_post_type' );
