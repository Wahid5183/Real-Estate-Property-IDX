<?php

/**
 * Add admin menu page.
 */
function mpf_admin_menu() {
    add_menu_page(
        'MLS Property Fetcher',  // Page title.
        'MLS Fetcher',           // Menu title.
        'manage_options',        // Capability.
        'mpf-fetcher',           // Menu slug.
        'mpf_admin_page',        // Function to display the page.
        'dashicons-download',    // Icon.
        6                        // Position.
    );
}
add_action( 'admin_menu', 'mpf_admin_menu' );

/**
 * Display the admin page with a form to set the fetch limit.
 */
function mpf_admin_page() {
    ?>
    <div class="wrap">
        <h1>MLS Property Fetcher</h1>
        <?php if ( isset( $_GET['mpf_message'] ) ) : ?>
            <div class="updated notice">
                <p><?php echo esc_html( $_GET['mpf_message'] ); ?></p>
            </div>
        <?php endif; ?>
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <input type="hidden" name="action" value="fetch_mls_properties">
            <?php wp_nonce_field( 'fetch_mls_properties_nonce' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="mpf_limit">Number of Properties to Fetch:</label>
                    </th>
                    <td>
                        <input type="number" name="mpf_limit" id="mpf_limit" value="10" min="1" />
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Fetch Data' ); ?>
        </form>
    </div>
    <?php
}
/**
 * Handle the form submission to fetch MLS property data.
 */
function mpf_fetch_mls_properties() {
    // Check user capabilities and nonce.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized user' );
    }
    check_admin_referer( 'fetch_mls_properties_nonce' );
    
    $limit = isset( $_POST['mpf_limit'] ) ? intval( $_POST['mpf_limit'] ) : 10;
    $skiped = get_option( 'total_property_added')? get_option( 'total_property_added') : 0;
    
    // Build the API endpoint URL.
    $url = 'https://api.bridgedataoutput.com/api/v2/OData/riar/Property?access_token=' . MLS_ACCESS_TOKEN  . '&$top=' . $limit .'&$skip=' . $skiped   ;
    
    $response = wp_remote_get( $url );
    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        wp_redirect( admin_url( 'admin.php?page=mpf-fetcher&mpf_message=' . urlencode( 'Error fetching data: ' . $error_message ) ) );
        exit;
    }
    
    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );
    
    // Assume the data is in the 'value' key as is common with OData responses.
    if ( ! isset( $data['value'] ) || empty( $data['value'] ) ) {
        wp_redirect( admin_url( 'admin.php?page=mpf-fetcher&mpf_message=' . urlencode( 'No properties found.' ) ) );
        exit;
    }
    
    $properties = $data['value'];
    $count = 0;
    
    foreach ( $properties as $property ) {
        // Use ListingId as the unique identifier.
        $listing_id = isset( $property['ListingId'] ) ? $property['ListingId'] : '';
        if ( empty( $listing_id ) ) {
            continue;
        }
        
        // Check if a property with this ListingId already exists.
        $existing = get_posts( array(
            'post_type'   => 'property',
            'meta_key'    => 'mls_listing_id',
            'meta_value'  => $listing_id,
            'numberposts' => 1,
        ) );
        
        // Create a title using BuildingName and City.
        $title = isset( $property['BuildingName'] ) ? $property['BuildingName'] : 'Property';
        if ( isset( $property['City'] ) ) {
            $title .= ' - ' . $property['City'];
        }
        
        // Use PublicRemarks as the post content.
        $content = isset( $property['PublicRemarks'] ) ? $property['PublicRemarks'] : '';
        
        $post_data = array(
            'post_title'   => $title,
            'post_status'  => 'publish',
            'post_type'    => 'property',
        );
        
        if ( $existing ) {
            $post_data['ID'] = $existing[0]->ID;
            $post_id = wp_update_post( $post_data );
        } else {
            $post_id = wp_insert_post( $post_data );
        }

        
        
        if ( ! is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, 'property_description', $content );
            update_post_meta( $post_id, 'mls_listing_id', $listing_id );
            if ( isset( $property['ListPrice'] ) ) {
                update_post_meta( $post_id, 'property_price', $property['ListPrice'] );
            }
            if ( isset( $property['MlsStatus'] ) ) {
                update_post_meta( $post_id, 'property_status', $property['MlsStatus'] );
            }
            if ( isset( $property['LivingArea'] ) ) {
                update_post_meta( $post_id, 'property_home_area', $property['LivingArea'] );
            }
            if ( isset( $property['LotSizeSquareFeet'] ) ) {
                update_post_meta( $post_id, 'property_lot_area', $property['LotSizeSquareFeet'] );
            }
            if ( isset( $property['PropertyType'] ) ) {
                update_post_meta( $post_id, 'property_type', $property['PropertyType'] );
            }
            if ( isset( $property['BedroomsTotal'] ) ) {
                update_post_meta( $post_id, 'property_beds', $property['BedroomsTotal'] );
            }
            if ( isset( $property['BathroomsTotalDecimal'] ) ) {
                update_post_meta( $post_id, 'property_baths', $property['BathroomsTotalDecimal'] );
            }
            if ( isset( $property['YearBuilt'] ) ) {
                update_post_meta( $post_id, 'property_year_built', $property['YearBuilt'] );
            }
            if ( isset( $property['GarageSpaces'] ) ) {
                update_post_meta( $post_id, 'property_garages', $property['GarageSpaces'] );
            }
            // Construct an address from available fields.
            $address_parts = array();
            if ( isset( $property['StreetNumber'] ) ) {
                $address_parts[] = $property['StreetNumber'];
            }
            if ( isset( $property['StreetName'] ) ) {
                $address_parts[] = $property['StreetName'];
            }
            if ( isset( $property['City'] ) ) {
                $address_parts[] = $property['City'];
            }
            if ( isset( $property['StateOrProvince'] ) ) {
                $address_parts[] = $property['StateOrProvince'];
            }
            if ( isset( $property['PostalCode'] ) ) {
                $address_parts[] = $property['PostalCode'];
            }
            $address = implode(', ', $address_parts);
            update_post_meta( $post_id, 'property_address', $address );
            
            $count++;
        }
    }
    
    wp_redirect( admin_url( 'admin.php?page=mpf-fetcher&mpf_message=' . urlencode( "Successfully fetched $count properties." ) ) );
    if(!get_option( 'total_property_added' )){
        add_option( 'total_property_added', $count );
    }else{
        $total_added_post = get_option( 'total_property_added' );
        update_option('total_property_added', $total_added_post + $count );
    }
    
    
    exit;
}
add_action( 'admin_post_fetch_mls_properties', 'mpf_fetch_mls_properties' );