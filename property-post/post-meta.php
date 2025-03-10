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
        'Property Specifications:',          // Title of the meta box.
        'pcmp_property_housing_meta_box_html', // Callback function to display the meta box.
        'property',                  // Post type.
        'normal',                    // Context (normal, side, etc.).
        'high'                       // Priority.
    );
    //metabox for property Financial & Status Details
    add_meta_box(
        'pcmp_property_Financial_Status__details',     // Unique ID for the meta box.
        'Financial & Status Details',          // Title of the meta box.
        'pcmp_property_Financial_Status_box_html', // Callback function to display the meta box.
        'property',                  // Post type.
        'normal',                    // Context (normal, side, etc.).
        'high'                       // Priority.
    );
    add_meta_box(
        'amenities_features_metabox',
        'Amenities & Features',
        'amenities_features_metabox_html',
        'property', // Change 'post' to 'property' if you're using a custom post type
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pcmp_register_custom_meta_box');


function amenities_features_metabox_html($post) {
    wp_nonce_field(basename(__FILE__), 'custom_fields_nonce');
    $custom_fields = get_post_meta($post->ID, 'custom_fields', true);
    ?>
    <div id="custom-fields-container">
        <?php if (!empty($custom_fields)) {
            foreach ($custom_fields as $field) { ?>
                <div class="custom-field">
                    <input type="text" name="custom_field_name[]" placeholder="Fethure Name e.g., flooring" value="<?php echo esc_attr($field['name']); ?>" />
                    <input type="text" name="custom_field_value[]" placeholder="Feathures e.g., Hardwood, Carpet" value="<?php echo esc_attr($field['value']); ?>" />
                    <button type="button" class="remove-field button">Remove</button>
                </div>
            <?php }
        } ?>
    </div>
    <button type="button" id="add-field" class="button">Add Field</button>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let container = document.getElementById("custom-fields-container");
            let addButton = document.getElementById("add-field");

            addButton.addEventListener("click", function() {
                let newField = document.createElement("div");
                newField.classList.add("custom-field");
                newField.innerHTML = `
                    <input type="text" name="custom_field_name[]" placeholder="Fethure Name e.g., flooring" />
                    <input type="text" name="custom_field_value[]" placeholder="Feathures e.g., Hardwood, Carpet" />
                    <button type="button" class="remove-field button">Remove</button>
                `;
                container.appendChild(newField);
            });

            container.addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-field")) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
    <style>
        .custom-field { margin-bottom: 10px; display: flex; gap: 10px; }
        .custom-field input { width: 45%; }
    </style>
    <?php
}

function save_custom_fields($post_id) {
    if (!isset($_POST['custom_fields_nonce']) || !wp_verify_nonce($_POST['custom_fields_nonce'], basename(__FILE__))) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $custom_fields = [];
    if (isset($_POST['custom_field_name']) && isset($_POST['custom_field_value'])) {
        $names = $_POST['custom_field_name'];
        $values = $_POST['custom_field_value'];

        for ($i = 0; $i < count($names); $i++) {
            if (!empty($names[$i]) && !empty($values[$i])) {
                $custom_fields[] = [
                    'name' => sanitize_text_field($names[$i]),
                    'value' => sanitize_text_field($values[$i])
                ];
            }
        }
    }

    update_post_meta($post_id, 'custom_fields', $custom_fields);
}
add_action('save_post', 'save_custom_fields');


function pcmp_property_basic_meta_box_html( $post ) {
    // Retrieve current meta field values.
    $description = get_post_meta( $post->ID, 'property_description', true );
    $location    = get_post_meta( $post->ID, 'property_address', true );
    
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
    $year_built    = get_post_meta( $post->ID, 'property_year_built', true );
    $Status    = get_post_meta( $post->ID, 'property_Status', true );
    $lot_area    = get_post_meta( $post->ID, 'property_lot_area', true );
    $lot_dimensions    = get_post_meta( $post->ID, 'property_lot_dimensions', true );
    $property_type    = get_post_meta( $post->ID, 'property_type', true );
    $property_subtype    = get_post_meta( $post->ID, 'property_subtype', true );
    $property_storiestotal    = get_post_meta( $post->ID, 'property_storiestotal', true );
    // Repeat for other meta fields...

    ?>
    <p>
        <label for="pcmp_property_home_area">Home Area:</label>
        <input type="number" id="pcmp_property_home_area" name="pcmp_property_home_area" value="<?php echo esc_attr($home_area); ?>" style="width:30%;" />
    </p>
    <p>
        <label for="pcmp_property_baths">Baths:</label>
        <input type="number" id="pcmp_property_baths" name="pcmp_property_baths" value="<?php echo esc_attr($baths); ?>" style="width:22%;" />
        <label for="pcmp_property_beds">Beds:</label>
        <input type="number" id="pcmp_property_beds" name="pcmp_property_beds" value="<?php echo esc_attr($beds); ?>" style="width:22%;" />
        <label for="pcmp_property_garages">Garages:</label>
        <input type="number" id="pcmp_property_garages" name="pcmp_property_garages" value="<?php echo esc_attr($garages); ?>" style="width:22%;" />
        
    </p>
        <label for="pcmp_property_year_built">Year Built:</label>
        <input type="number" min=1900 id="pcmp_property_year_built" name="pcmp_property_year_built" value="<?php echo esc_attr($year_built) ; ?>" style="width:30%;" />
        <label for="pcmp_property_Status">Status:</label>
        <select name="pcmp_property_Status" id="pcmp_property_Status">
            <option value="For_Rent" <?php $Status = "Rent" ? 'selected' : "" ;  ?> >For Rent</option>
            <option value="For_Sale"  <?php $Status = "Sold" ? 'selected' : "" ;  ?> >For Sale</option>
        </select>
        <p>
        
        <label for="pcmp_property_lot_area">Lot Area:</label>
        <input type="number" min=100 id="pcmp_property_lot_area" name="pcmp_property_lot_area" value="<?php echo esc_attr($lot_area) ; ?>" style="width:30%;" />
        </p>

    <p>
        <label for="pcmp_property_type">Property Type:</label>
        <input type="text" id="pcmp_property_type" name="pcmp_property_type" value="<?php echo esc_attr($property_type) ; ?>" style="width:30%;" />
        <label for="pcmp_property_subtype">Property SubType:</label>
        <input type="text" id="pcmp_property_subtype" name="pcmp_property_subtype" value="<?php echo esc_attr($property_subtype) ; ?>" style="width:30%;" />
    </p>
    <label for="pcmp_property_storiestotal">Number of Floor:</label>
        <input type="number" id="pcmp_property_storiestotal" name="pcmp_property_storiestotal" value="<?php echo esc_attr($property_storiestotal) ; ?>" style="width:30%;" />
    <!-- Repeat for additional fields -->
    <?php
}
function pcmp_property_Financial_Status_box_html( $post ) {
    // Retrieve current meta field values.
    $price    = get_post_meta( $post->ID, 'property_price', true );
    $close_price    = get_post_meta( $post->ID, 'property_close_price', true );
    $daysonmarket    = get_post_meta( $post->ID, 'property_daysonmarket', true );
    $taxannualamount    = get_post_meta( $post->ID, 'property_taxannualamount', true );
    $taxassessedvalue    = get_post_meta( $post->ID, 'property_taxassessedvalue', true );
    
    // Repeat for other meta fields...

    ?>
    <p>
        <label for="pcmp_property_price">List Price($):</label>
        <input type="number" id="pcmp_property_price" name="pcmp_property_price" value="<?php echo esc_attr($price);  ?>" style="width:30%;" />
        <label for="pcmp_property_close_price">Close Price($):</label>
        <input type="number" id="pcmp_property_close_price" name="pcmp_property_close_price" value="<?php echo esc_attr($daysonmarket);  ?>" style="width:30%;" />
        
        
    </p>
    <p>
        <label for="pcmp_property_daysonmarket">Days On Markets:</label>
        <input type="number" id="pcmp_property_daysonmarket" name="pcmp_property_daysonmarket" value="<?php echo esc_attr($daysonmarket);  ?>" style="width:30%;" />
        <label for="pcmp_property_taxannualamount">Tax Annual Amount($):</label>
        <input type="number" id="pcmp_property_taxannualamount" name="pcmp_property_taxannualamount" value="<?php echo esc_attr($taxannualamount);  ?>" style="width:30%;" />
    </p>
    <p>
        <label for="pcmp_property_taxassessedvalue">Tax Assessed Value($):</label>
        <input type="number" id="pcmp_property_taxassessedvalue" name="pcmp_property_taxassessedvalue" value="<?php echo esc_attr($taxassessedvalue);  ?>" style="width:30%;" />
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
    if ( array_key_exists( 'pcmp_property_type', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_type',
            sanitize_text_field( $_POST['pcmp_property_type'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_subtype', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_storiestotal',
            sanitize_text_field( $_POST['pcmp_property_subtype'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_storiestotal', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_subtype',
            sanitize_text_field( $_POST['pcmp_property_storiestotal'] )
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
    //save Financial data
    if ( array_key_exists( 'pcmp_property_price', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_price',
            sanitize_text_field( $_POST['pcmp_property_price'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_close_price', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_close_price',
            sanitize_text_field( $_POST['pcmp_property_close_price'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_daysonmarket', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_daysonmarket',
            sanitize_text_field( $_POST['pcmp_property_daysonmarket'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_taxannualamount', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_taxannualamount',
            sanitize_text_field( $_POST['pcmp_property_taxannualamount'] )
        );
    }
    if ( array_key_exists( 'pcmp_property_taxassessedvalue', $_POST ) ) {
        update_post_meta(
            $post_id,
            'property_taxassessedvalue',
            sanitize_text_field( $_POST['pcmp_property_taxassessedvalue'] )
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
