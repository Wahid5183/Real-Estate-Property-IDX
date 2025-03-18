<?php
/*
Template Name: Property Archive
Description: A custom archive page to display property posts with filter options via URL parameters.
*/
get_header();

// Retrieve filter parameters from the URL
$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : 0;
$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : 1000000;
$keyword = isset($_GET['property_keyword']) ? sanitize_text_field($_GET['property_keyword']) : '';
$location = isset($_GET['property_location']) ? sanitize_text_field($_GET['property_location']) : '';
$status    = isset($_GET['property_status'])    ? sanitize_text_field($_GET['property_status']) : '';
$types    = isset($_GET['property_substatus'])    ? sanitize_text_field($_GET['property_substatus']) : '';
$bedrooms  = isset($_GET['bedrooms'])  ? intval($_GET['bedrooms']) : '';
$bathrooms = isset($_GET['bathrooms']) ? intval($_GET['bathrooms']) : '';
$sqft_min = isset($_GET['sqft_min']) ? intval($_GET['sqft_min']) : 0;
$sqft_max = isset($_GET['sqft_max']) ? intval($_GET['sqft_max']) : 2500;

$meta_query = array('relation' => 'AND');

// Keyword search (search in multiple fields)
if (!empty($keyword)) {
    $meta_query[] = array(
        'relation' => 'OR',
        array(
            'key' => 'property_address',
            'value' => $keyword,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'property_description',
            'value' => $keyword,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'property_status',
            'value' => $keyword,
            'compare' => 'LIKE'
        )
    );
}

if (!empty($price_min) && $price_min > 0) {
    $meta_query[] = array(
        'key'     => 'property_price',
        'value'   => $price_min,
        'type'    => 'NUMERIC',
        'compare' => '>='
    );
}
if (!empty($price_max) && $price_max > 0) {
    $meta_query[] = array(
        'key'     => 'property_price',
        'value'   => $price_max,
        'type'    => 'NUMERIC',
        'compare' => '<='
    );
}
if (!empty($status)) {
    $meta_query[] = array(
        'key'     => 'property_status',
        'value'   => $status,
        'compare' => '='
    );
}
if (!empty($types)) {
    $meta_query[] = array(
        'key'     => 'property_subtype',
        'value'   => $types,
        'compare' => '='
    );
}
if (!empty($location)) {
    $meta_query[] = array(
        'key'     => 'property_address',
        'value'   => $location,
        'compare' => 'LIKE'
    );
}
if (!empty($bedrooms) && $bedrooms > 0) {
    $meta_query[] = array(
        'key'     => 'property_beds',
        'value'   => $bedrooms,
        'type'    => 'NUMERIC',
        'compare' => '>='
    );
}
if (!empty($bathrooms) && $bathrooms > 0) {
    $meta_query[] = array(
        'key'     => 'property_baths',
        'value'   => $bathrooms,
        'type'    => 'NUMERIC',
        'compare' => '>='
    );
}

if (!empty($sqft_min) && $sqft_min > 0) {
    $meta_query[] = array(
        'key'     => 'property_home_area',
        'value'   => $sqft_min,
        'type'    => 'NUMERIC',
        'compare' => '>='
    );
}
if (!empty($sqft_max) && $sqft_max > 0) {
    $meta_query[] = array(
        'key'     => 'property_home_area',
        'value'   => $sqft_max,
        'type'    => 'NUMERIC',
        'compare' => '<='
    );
}

// Set up pagination.
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Query the 'property' posts with the meta query.
$args = array(
    'post_type'      => 'property',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'meta_query'     => $meta_query,
);
$query = new WP_Query($args);
?>
<div class="property-archive">
    <div class="container py-4">
        <div class="heading text-center">
            <h2 class='fs-1 mb-3'>Properties</h2>
            <p class="text-center">  <?php custom_breadcrumb(); ?></p>

            <div class="property-search-box row">
                <!-- Search Filters Column -->
                     <div class="col-lg-3 col-md-4 mb-4">
                        <div class="bg-white sideba rounded-4 p-3">
                            <form method="GET" action="">
                                <!-- Price Range -->
                               
     
                                <!-- Status Select -->
                                <div class="form-group position-relative">
                                    <input type="text" name="property_keyword" placeholder="Enter Keyword" class="property_keyword form-select border-0 py-3 px-4" 
                                    <?php echo $keyword == '' ? '' : 'value = ' . $keyword ; ?> style="background-image: none;"
                                    
    
    >
                                </div>
                                <div class="form-group position-relative">
                                    <input type="text" name="property_location" placeholder="Enter Location" class="property_keyword form-select border-0 py-3 px-4" 
                                    <?php echo $location == '' ? '' : 'value = ' . $location ; ?> style="background-image: none;"
                                    
    
    >
                                </div>
                                <!-- Type Select -->
                                <div class="form-group position-relative">
                                    <select name="property_substatus" class="form-select border-0 py-3 px-4">
                                        <option value="">All Types</option>
                                        <option value="Single Family Residence" <?php selected($types, 'Single Family Residence'); ?>>Single Family Residence</option>
                                        <option value="Commercial Sale" <?php selected($types, 'Commercial Sale'); ?>>Commercial Sale</option>
                                        <option value="Rental,Multi Family" <?php selected($types, 'Rental,Multi Family'); ?>>Rental,Multi Family</option>
                                        <option value="Multi Family" <?php selected($types, 'Multi Family'); ?>>Multi Family</option>
                                    </select>
                                </div>
                                <!-- Status Select -->
                                <div class="form-group position-relative">
                                    <select name="property_status" class="form-select border-0 py-3 px-4">
                                        <option value="">All Status</option>
                                        <option value="Rented/Leased" <?php selected($status, 'Rented/Leased'); ?>>For Rent</option>
                                        <option value="Sold" <?php selected($status, 'Sold'); ?>>For Sale</option>
                                    </select>
                                </div>
                                
                                <!-- Bedrooms Select -->
                                <div class="form-group position-relative">
                                    <select name="bedrooms" class="form-select border-0 py-3 px-4">
                                        <option value="">Bedrooms</option>
                                        <?php for($i=1; $i<=10; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php selected($bedrooms, $i); ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                        <option value="10+" <?php selected($bedrooms, '10+'); ?>>10+</option>
                                    </select>
                                </div>
                                
                                <!-- Bathrooms Select -->
                                <div class="form-group position-relative">
                                    <select name="bathrooms" class="form-select border-0 py-3 px-4">
                                        <option value="">Bathrooms</option>
                                        <?php for($i=1; $i<=10; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php selected($bathrooms, $i); ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                        <option value="10+" <?php selected($bathrooms, '10+'); ?>>10+</option>
                                    </select>
                                </div>
                                
                                <!-- Garage Select -->
                                <div class="form-group position-relative mb-3">
                                    <select name="garage" class="form-select border-0 py-3 px-4">
                                        <option value="">Garages</option>
                                        <?php for($i=0; $i<=5; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php if(isset($_GET['garage'])) selected($_GET['garage'], $i); ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                        <option value="5+" <?php if(isset($_GET['garage'])) selected($_GET['garage'], '5+'); ?>>5+</option>
                                    </select>
                                </div>

                                <!-- Price Range Slider -->
<div class="range-slider-container mb-4">
    <label for="price_range">Price Range</label>
    <div class="range-slider" style="position: relative; height: 50px;">
        <div class="range-track" style="position: absolute; top: 50%; left: 0; right: 0; height: 4px; background: #ddd; transform: translateY(-50%);"></div>
        <div class="range-highlight" id="range-highlight" style="position: absolute; top: 50%; left: 0%; right: 0%; height: 4px; background: #0d6efd;z-index:999; transform: translateY(-50%);"></div>
        
        <input type="range" 
            name="price_min" 
            class="form-range" 
            min="0" 
            max="1000000" 
            step="10000" 
            value="<?php echo esc_attr($price_min); ?>" 
            id="price_min">
        
        <input type="range" 
            name="price_max" 
            class="form-range" 
            min="0" 
            max="1000000" 
            step="10000" 
            value="<?php echo esc_attr($price_max); ?>" 
            id="price_max">
    </div>
    <div class="d-flex justify-content-between">
        <span id="price_min_value">$<?php echo number_format(esc_attr($price_min)); ?></span>
        <span id="price_max_value">$<?php echo number_format(esc_attr($price_max)); ?></span>
    </div>
</div>

<!-- Home Area Slider (Corrected) -->
<div class="range-slider-container mb-4">
    <label for="home_area">Home Area</label>
    <div class="range-slider" style="position: relative; height: 50px;">
        <div class="range-track" style="position: absolute; top: 50%; left: 0; right: 0; height: 4px; background: #ddd; transform: translateY(-50%);"></div>
        <div class="range-highlight-home" id="range-highlight-home" style="position: absolute; top: 50%; left: 0%; right: 0%; height: 4px; background: #0d6efd;z-index:999; transform: translateY(-50%);"></div>
        
        <input type="range" 
            name="sqft_min" 
            class="form-range" 
            min="0" 
            max="6000" 
            step="100" 
            value="<?php echo esc_attr($sqft_min); ?>" 
            id="sqft_min"> <!-- Changed ID -->
        
        <input type="range" 
            name="sqft_max" 
            class="form-range" 
            min="0" 
            max="6000" 
            step="100" 
            value="<?php echo esc_attr($sqft_max); ?>" 
            id="sqft_max"> <!-- Changed ID -->
    </div>
    <div class="d-flex justify-content-between">
        <span id="home_min_value"><?php echo number_format(esc_attr($sqft_min)); ?> sqft</span> <!-- Removed $ -->
        <span id="home_max_value"><?php echo number_format(esc_attr($sqft_max)); ?> sqft</span> <!-- Removed $ -->
    </div>
</div>
                                
                                <!-- Search Button -->
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i> Search Properties
                                </button>
                            </form>
                            <div class="reset-btn text-start">
                                <a  class=" py-3 mt-2 " onclick="window.location.href=window.location.pathname">
                                                                <i class="fa-solid fa-rotate-right"></i>Reset Search
                            </a>

                            </div>
                            
                        </div>
                    </div>


    <!-- Properties Listing Column -->
                <div class="col-lg-9 col-md-4">
                    <div class="row g-4">
                                            <?php if ($query->have_posts()) : ?>
                                                <?php
                                    $paged = max(1, get_query_var('paged'));
                                    $per_page = get_query_var('posts_per_page', 9); // Adjust this based on your query
                                    $total_posts = $query->found_posts;

                                    $start = ($paged - 1) * $per_page + 1;
                                    $end = min($start + $per_page - 1, $total_posts);

                                    if ($total_posts > 0) {
                                        echo "<p class='property-count'>Showing {$start} – {$end} of {$total_posts} results</p>";
                                    }
                                    ?>

                            <?php while ($query->have_posts()) : $query->the_post(); ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card shadow-sm h-100">
                                        <div id="carousel-<?php echo get_the_ID(); ?>" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner slide_display_img">
                                                <?php  
                                                    $image_srcs = get_post_meta($post->ID, '_property_image_src', true);
                                                    $image_srcs = !empty($image_srcs) ? explode(',', $image_srcs) : []; 
                                                    $property_status = get_post_meta(get_the_ID(), 'property_status', true);

                                                ?>
                                                <?php if (!empty($image_srcs)) : ?>
                                                    <?php foreach ($image_srcs as $index => $image_src): ?>
                                                        <?php if (!empty($image_src)) : ?>
                                                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" data-bs-interval="10000000">
                                                                <div class="top_badge">
                                                                    
                                                               
                                                                <p class="property_status_tag">
                                                                    <?php echo ($property_status == 'Sold') ? 'For Sale' : 'For Rent'; ?>
                                                                
                                                                </p>
                                                                <p class="property_status_tag"
                                                                style="background-color: black;"
                                                                >
                                                                    Feathured
                                                                
                                                                </p>
                                                             </div>
                                                             <a href="<?php echo the_permalink( ) ?>">
                                                                <img src="<?php echo esc_url($image_src); ?>" class="d-block w-100 " alt="Property Image" height='200px'></a>
                                                                <div class="property_camera_tag">
                                                                    
                                                                <i class="fa-solid fa-camera"></i>
                                                                <span style=""> </span>
                                                                <p class="">
                                                                    <?php echo " " . count($image_srcs); ?>
                                                                
                                                                </p>
                                                                
                                                             </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <div class="carousel-item active">
                                                        <img src="https://via.placeholder.com/400" class="d-block w-100" alt="No Image Available">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <button class="carousel-control-prev per_img" type="button" data-bs-target="#carousel-<?php echo get_the_ID(); ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon " aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next next_img" type="button" data-bs-target="#carousel-<?php echo get_the_ID(); ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon " aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title text-start">
                                            <a class="property_name"href="<?php echo the_permalink(); ?>">    
                                            <?php the_title(); ?>
                                            </a>
                                            </h5>
                                            <p class="property_address"><?php echo get_post_meta(get_the_ID(), 'property_address', true); ?></p>

                                            <div class="d-flex houes-info">
                                                <div class="card-text text-muted">
                                                    <i class="fa-solid fa-bed"></i>
                                                    <p><span><?php echo get_post_meta(get_the_ID(), 'property_beds', true); ?> Beds</span></p>
                                                </div>
                                                <div class="card-text text-muted">
                                                    <i class="fa-solid fa-bath"></i>
                                                    <p><span><?php echo get_post_meta(get_the_ID(), 'property_baths', true); ?> Baths</span></p>
                                                </div>
                                                <div class="card-text text-muted">
                                                    <i class="fa-solid fa-warehouse"></i>
                                                    <p><span><?php echo get_post_meta(get_the_ID(), 'property_garages', true); ?> Garages</span></p>
                                                </div>
                                                <div class="card-text text-muted">
                                                    <i class="fa-solid fa-ruler-combined"></i>
                                                    <p><span><?php echo get_post_meta(get_the_ID(), 'property_home_area', true); ?> Sqft</span></p>
                                                </div>
                                            </div>

                                            
                                        </div>
                                        <div class="d-flex card_footer justify-content-between align-items-center">
                                                
                                                <span class="list_price text-primary">
                                                    <?php 
                                                        $property_price = get_post_meta(get_the_ID(), 'property_price', true);
                                                        $property_status = get_post_meta(get_the_ID(), 'property_status', true);

                                                        // Display price if it's numeric, otherwise show "N/A"
                                                        echo is_numeric($property_price) ? "$" . number_format($property_price) : "N/A";

                                                        // Append "/mo" if status is "Rented/Leased"
                                                        if ($property_status === 'Rented/Leased') {
                                                            echo " /mo";
                                                        }
                                                    ?>
                                                </span>

                                                <a href="<?php the_permalink(); ?>" class=""><i class="fa fa-expand" aria-hidden="true"></i>

                                                </a>
                                            </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <div class="pagination justify-content-center gap-2">
                            <?php
                            echo paginate_links( array(
                                'total'     => $query->max_num_pages,
                                'current'   => $paged,
                                'format'    => '?paged=%#%',
                                'add_args'  => $_GET, // Retains existing query parameters (filters)
                                'prev_text' => __('&laquo; Previous'),
                                'next_text' => __('Next &raquo;'),
                            ) );
                            ?>
                        </div>

                        <?php else : ?>
                            <p>No properties found.</p>
                        <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.sideba form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams();

            // Helper function to check default values
            const shouldIncludeParam = (name, value, defaultValue) => {
                return value !== defaultValue && value !== '' && value !== null;
            };

            // Get form values
            const formData = new FormData(form);
            
            // Price range handling
            const priceMin = parseInt(formData.get('price_min')) || 0;
            const priceMax = parseInt(formData.get('price_max')) || 1000000;
            
            if (shouldIncludeParam('price_min', priceMin, 0)) {
                params.set('price_min', priceMin);
            }
            if (shouldIncludeParam('price_max', priceMax, 1000000)) {
                params.set('price_max', priceMax);
            }

            // Square footage handling
            const sqftMin = parseInt(formData.get('sqft_min')) || 0;
            const sqftMax = parseInt(formData.get('sqft_max')) || 2500;
            
            if (shouldIncludeParam('sqft_min', sqftMin, 0)) {
                params.set('sqft_min', sqftMin);
            }
            if (shouldIncludeParam('sqft_max', sqftMax, 2500)) {
                params.set('sqft_max', sqftMax);
            }

            // Handle other parameters
            ['property_keyword','property_location', 'property_status','property_substatus', 'bedrooms', 'bathrooms', 'garage'].forEach(name => {
                const value = formData.get(name);
                if (value && value !== '') {
                    params.set(name, value);
                }
            });

            // Build and navigate to new URL
            const newUrl = window.location.pathname + (params.toString() ? `?${params}` : '');
            window.location.href = newUrl;
        });
    }
});
</script>

<?php get_footer(); ?>
