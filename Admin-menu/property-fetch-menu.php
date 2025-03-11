
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

        
        
        if (!is_wp_error($post_id)) {
            $img_src = [];

        if (!empty($property['Media']) && is_array($property['Media'])) {
            foreach ($property['Media'] as $media) {
                if (!empty($media['MediaURL']) && filter_var($media['MediaURL'], FILTER_VALIDATE_URL)) {
                    $img_src[] = esc_url_raw($media['MediaURL']);
                }
            }
        }

        // Store the image URLs as a comma-separated string in post meta
        if (!empty($img_src)) {
            update_post_meta($post_id, '_property_image_src', implode(',', $img_src));
        }

           

            $custom_fields = [];
            $feathure_meta_feilds = [
                'Heating' => $property['Heating'],
                'Cooling' => $property['Cooling'],
                'Flooring' => $property['Flooring'],
                'ExteriorFeatures' => $property['ExteriorFeatures'],
                'InteriorFeatures' => $property['InteriorFeatures'],
                'InteriorFeatures' => $property['InteriorFeatures'],
                'SecurityFeatures' => $property['SecurityFeatures'],
                'WaterSource' => $property['WaterSource'],
                'Sewer' => $property['Sewer'],
                'DoorFeatures' => $property['DoorFeatures'],
                'Roof' => $property['Roof'],
            ];

           
        
            // Handle "Heating" as a custom field (if exists)
            foreach($feathure_meta_feilds as $feilds_key => $feilds_value){
                if(!empty($feilds_value)){
                    $custom_fields[] = [
                    'name' => $feilds_key,
                    'value' => sanitize_text_field(implode(', ', $feilds_value)),
                    'featured' => 1
                ];
                }
                
            }
            
        
            // Update custom fields meta
            update_post_meta($post_id, 'custom_fields', $custom_fields);
        
            // Meta fields mapping
            $meta_fields = [
                'property_description'    => $content,
                'mls_listing_id'          => $listing_id,
                'property_price'          => $property['ListPrice'] ?? null,
                'property_close_price'    => $property['ClosePrice'] ?? null,
                'property_daysonmarket'   => $property['DaysOnMarket'] ?? null,
                'property_taxannualamount'=> $property['TaxAnnualAmount'] ?? null,
                'property_taxassessedvalue'=> $property['TaxAssessedValue'] ?? null,
                'property_status'         => $property['MlsStatus'] ?? null,
                'property_home_area'      => $property['LivingArea'] ?? null,
                'property_lot_area'       => $property['LotSizeSquareFeet'] ?? null,
                'property_type'           => $property['PropertyType'] ?? null,
                'property_subtype'        => $property['PropertySubType'] ?? null,
                'property_storiestotal'   => $property['StoriesTotal'] ?? null,
                'property_beds'           => $property['BedroomsTotal'] ?? null,
                'property_baths'          => $property['BathroomsTotalDecimal'] ?? null,
                'property_year_built'     => $property['YearBuilt'] ?? null,
                'property_garages'        => $property['GarageSpaces'] ?? null
            ];
        
            // Bulk update all meta fields
            foreach ($meta_fields as $meta_key => $value) {
                if ($value !== null) {
                    update_post_meta($post_id, $meta_key, $value);
                }
            }
        
            // Construct and update property address
            $address_parts = array_filter([
                $property['StreetNumber'] ?? null,
                $property['StreetName'] ?? null,
                $property['City'] ?? null,
                $property['StateOrProvince'] ?? null,
                $property['PostalCode'] ?? null
            ]);
        
            if (!empty($address_parts)) {
                update_post_meta($post_id, 'property_address', implode(', ', $address_parts));
            }
        
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