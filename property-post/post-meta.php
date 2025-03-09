<?php


function pcmp_register_custom_meta_box() {
    //metabox for property basic details
    add_meta_box(
        'pcmp_property_basic_details',     // Unique ID for the meta box.
        'Basic Details',          // Title of the meta box.
        'pcmp_property_basic_meta_box_html', // Callback function to display the meta box.
        'property',                  // Post type.
        'normal',                    // Context (normal, side, etc.).
        'high'                       // Priority.
    );
    //metabox for property Housing details
    add_meta_box(
        'pcmp_property_housing_details',     // Unique ID for the meta box.
        'Housing Details',          // Title of the meta box.
        'pcmp_property_housing_meta_box_html', // Callback function to display the meta box.
        'property',                  // Post type.
        'normal',                    // Context (normal, side, etc.).
        'high'                       // Priority.
    );
}
add_action('add_meta_boxes', 'pcmp_register_custom_meta_box');

function pcmp_property_basic_meta_box_html( $post ) {
    // Retrieve current meta field values.
    $description = get_post_meta( $post->ID, 'property_description', true );
    $location    = get_post_meta( $post->ID, 'property_address', true );
    $price    = get_post_meta( $post->ID, 'property_price', true );
    $year_built    = get_post_meta( $post->ID, 'property_year_built', true );
    $Status    = get_post_meta( $post->ID, 'property_Status', true );
    $lot_area    = get_post_meta( $post->ID, 'property_lot_area', true );
    $lot_dimensions    = get_post_meta( $post->ID, 'property_lot_dimensions', true );
    $property_type    = get_post_meta( $post->ID, 'property_type', true );
    // Repeat for other meta fields...

    ?>
    <p>
        <label for="pcmp_property_description">Description:</label>
        <textarea id="pcmp_property_description" name="pcmp_property_description" rows="4" style="width:100%;"><?php echo esc_textarea($description); ?></textarea>
    </p>
    <p>
        <label for="pcmp_property_location">Location:</label>
        <input type="text" id="pcmp_property_location" name="pcmp_property_location" value="<?php echo esc_attr($location); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="pcmp_property_price">Price($):</label>
        <input type="number" id="pcmp_property_price" name="pcmp_property_price" value="<?php echo esc_attr($price);  ?>" style="width:30%;" />
        
        <label for="pcmp_property_year_built">Year Built:</label>
        <input type="number" min=1900 id="pcmp_property_year_built" name="pcmp_property_year_built" value="<?php echo esc_attr($year_built) ; ?>" style="width:30%;" />
    </p>
    <p>
        <label for="pcmp_property_Status">Status:</label>
        <select name="pcmp_property_Status" id="pcmp_property_Status">
            <option value="For_Rent" <?php $Status = "Rent" ? 'selected' : "" ;  ?> >For Rent</option>
            <option value="For_Sale"  <?php $Status = "Sold" ? 'selected' : "" ;  ?> >For Sale</option>
        </select>
        <label for="pcmp_property_lot_area">Lot Area:</label>
        <input type="number" min=100 id="pcmp_property_lot_area" name="pcmp_property_lot_area" value="<?php echo esc_attr($lot_area) ; ?>" style="width:30%;" />
    </p>
    <p>
        <label for="pcmp_property_lot_dimensions">Lot Dimentation:</label>
        <input type="number" min=100 id="pcmp_property_lot_dimensions" name="pcmp_property_lot_dimensions" value="<?php echo esc_attr($lot_dimensions) ; ?>" style="width:30%;" />
    </p>
    <p>
        <label for="pcmp_property_type">Property Type:</label>
        <input type="text" id="pcmp_property_type" name="pcmp_property_type" value="<?php echo esc_attr($property_type) ; ?>" style="width:30%;" />
    </p>
    <!-- Repeat for additional fields -->
    <?php
}
function pcmp_property_housing_meta_box_html( $post ) {
    // Retrieve current meta field values.
    $home_area = get_post_meta( $post->ID, 'property_home_area', true );
    $rooms = get_post_meta( $post->ID, 'property_rooms', true );
    $baths = get_post_meta( $post->ID, 'property_baths', true );
    $beds = get_post_meta( $post->ID, 'property_beds', true );
    $garages = get_post_meta( $post->ID, 'property_garages', true );
    // Repeat for other meta fields...

    ?>
    <p>
        <label for="pcmp_property_home_area">Home Area:</label>
        <input type="number" id="pcmp_property_home_area" name="pcmp_property_home_area" value="<?php echo esc_attr($home_area); ?>" style="width:30%;" />
        <label for="pcmp_property_rooms">Rooms:</label>
        <input type="number" id="pcmp_property_rooms" name="pcmp_property_rooms" value="<?php echo esc_attr($rooms); ?>" style="width:30%;" />
    </p>
    <p>
        <label for="pcmp_property_baths">Baths:</label>
        <input type="number" id="pcmp_property_baths" name="pcmp_property_baths" value="<?php echo esc_attr($baths); ?>" style="width:22%;" />
        <label for="pcmp_property_beds">Beds:</label>
        <input type="number" id="pcmp_property_beds" name="pcmp_property_beds" value="<?php echo esc_attr($beds); ?>" style="width:22%;" />
        <label for="pcmp_property_garages">Garages:</label>
        <input type="number" id="pcmp_property_garages" name="pcmp_property_garages" value="<?php echo esc_attr($garages); ?>" style="width:22%;" />
        
    </p>
    <!-- Repeat for additional fields -->
    <?php
}

function pcmp_save_property_meta( $post_id ) {
    //Handle Property Basics Details
    if ( array_key_exists( 'pcmp_property_description', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_description',
            sanitize_textarea_field( $_POST['pcmp_property_description'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_location', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_address',
            sanitize_text_field( $_POST['pcmp_property_location'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_price', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_price',
            sanitize_text_field( $_POST['pcmp_property_price'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_year_built', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_year_built',
            sanitize_text_field( $_POST['pcmp_property_year_built'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_Status', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_Status',
            sanitize_text_field( $_POST['pcmp_property_Status'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_lot_area', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_lot_area',
            sanitize_text_field( $_POST['pcmp_property_lot_area'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_lot_dimensions', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_lot_dimensions',
            sanitize_text_field( $_POST['pcmp_property_lot_dimensions'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_type', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_type',
            sanitize_text_field( $_POST['pcmp_property_type'] )
        );
    }

    //handle Property Housing Destails
    if ( array_key_exists( 'pcmp_property_home_area', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_home_area',
            sanitize_textarea_field( $_POST['pcmp_property_home_area'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_rooms', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_rooms',
            sanitize_textarea_field( $_POST['pcmp_property_rooms'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_baths', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_baths',
            sanitize_textarea_field( $_POST['pcmp_property_baths'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_beds', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_beds',
            sanitize_textarea_field( $_POST['pcmp_property_beds'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_garages', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_garages',
            sanitize_textarea_field( $_POST['pcmp_property_garages'] )
        );
    }
    // Save additional fields similarly...
}
add_action( 'save_post', 'pcmp_save_property_meta' );

function psp_template_include( $template ) {
    //Check is it Single property page
    if ( is_singular( 'property' ) ) {
        //Add New template for single property
        $plugin_template = plugin_dir_path( __FILE__ ) . 'templates/single-property.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        } else {
            error_log( 'Template file does not exist: ' . $plugin_template );
        }
    }
    if(is_archive( 'property' )){
        //Add New template for single property
        $plugin_template = plugin_dir_path( __FILE__ ) . 'templates/arcrive-property.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        } else {
            error_log( 'Template file does not exist: ' . $plugin_template );
        }
    }
    return $template;
}
add_filter( 'template_include', 'psp_template_include' );
