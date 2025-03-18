<?php
// Handle form submissions
function psp_handle_form_submissions() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return; // Exit if not a POST request
    }

    global $wpdb;

    // Process Contact Agent form
    if (isset($_POST['contact_submit'])) {
        // Verify nonce
        if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'property_contact_form')) {
            wp_die('Security check failed');
        }

        // Sanitize data
        $data = [
            'property_id' => sanitize_text_field($_POST['property_id']),
            'name' => sanitize_text_field($_POST['contactName']),
            'phone' => sanitize_text_field($_POST['contactPhone']),
            'email' => sanitize_email($_POST['contactEmail']),
            'message' => sanitize_textarea_field($_POST['contactMessage']),
            'submission_date' => current_time('mysql')
        ];

        // Insert into database
        $wpdb->insert($wpdb->prefix . 'property_contacts', $data);

        // Store redirect URL in session to prevent early headers issue
        set_transient('psp_redirect_url', add_query_arg('contact_sent', '1', get_permalink()), 30);
    }

    // Process Tour Request form
    if (isset($_POST['tour_submit'])) {
        // Verify nonce
        if (!isset($_POST['tour_nonce']) || !wp_verify_nonce($_POST['tour_nonce'], 'property_tour_form')) {
            wp_die('Security check failed');
        }

        // Sanitize data
        $data = [
            'property_id' => sanitize_text_field($_POST['property_id']),
            'tour_date' => sanitize_text_field($_POST['tour_date']),
            'tour_time' => sanitize_text_field($_POST['tour_time']),
            'name' => sanitize_text_field($_POST['tour_name']),
            'phone' => sanitize_text_field($_POST['tour_phone']),
            'email' => sanitize_email($_POST['tour_email']),
            'message' => sanitize_textarea_field($_POST['tour_message']),
            'submission_date' => current_time('mysql')
        ];

        // Insert into database
        $wpdb->insert($wpdb->prefix . 'property_tours', $data);

        // Store redirect URL in session
        set_transient('psp_redirect_url', add_query_arg('tour_requested', '1', get_permalink()), 30);
    }
}
add_action('init', 'psp_handle_form_submissions');

// Perform the redirect safely
function psp_safe_redirect() {
    if ($redirect_url = get_transient('psp_redirect_url')) {
        delete_transient('psp_redirect_url');
        wp_redirect($redirect_url);
        exit;
    }
}
add_action('template_redirect', 'psp_safe_redirect');
