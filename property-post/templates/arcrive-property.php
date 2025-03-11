<?php
/*
Template Name: Property Archive
Description: A custom archive page to display property posts with filter options via URL parameters.
*/
get_header();

// Retrieve filter parameters from the URL
$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : 0;
$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : 1000000;
$status    = isset($_GET['property_Status'])    ? sanitize_text_field($_GET['property_Status']) : '';
$bedrooms  = isset($_GET['bedrooms'])  ? intval($_GET['bedrooms']) : '';
$bathrooms = isset($_GET['bathrooms']) ? intval($_GET['bathrooms']) : '';
$property_type = isset($_GET['property_type']) ? sanitize_text_field($_GET['property_type']) : '';
$sqft_min = isset($_GET['sqft_min']) ? intval($_GET['sqft_min']) : 0;
$sqft_max = isset($_GET['sqft_max']) ? intval($_GET['sqft_max']) : 2500;

$meta_query = array('relation' => 'AND');

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
if (!empty($property_type)) {
    $meta_query[] = array(
        'key'     => 'property_type',
        'value'   => $property_type,
        'compare' => '='
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

print_r($meta_query);
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
            <h2 class='fs-5 mb-3'>Properties</h2>
            <?php custom_breadcrumb(); ?>

            <div class="property-search-box row">
                <!-- Search Filters Column -->
                     <div class="col-lg-4 mb-4">
                        <div class="bg-white shadow-sm rounded-4 p-3 h-100">
                            <form method="GET" action="">
                                <!-- Price Range -->
                                <div class="form-group position-relative mb-4">
                                    <label>Min Price: <span id="price_min_value"><?php echo esc_attr($price_min); ?></span></label>
                                    <input type="range" name="price_min" class="form-control border-0 py-3 px-4" min="0" max="1000000" step="1" value="<?php echo esc_attr($price_min); ?>" oninput="document.getElementById('price_min_value').innerText = this.value">
                                </div>
                                <div class="form-group position-relative mb-4">
                                    <label>Max Price: <span id="price_max_value"><?php echo esc_attr($price_max); ?></span></label>
                                    <input type="range" name="price_max" class="form-control border-0 py-3 px-4" min="0" max="1000000" step="1" value="<?php echo esc_attr($price_max); ?>" oninput="document.getElementById('price_max_value').innerText = this.value">
                                </div>
                                
                                <!-- Status Select -->
                                <div class="form-group position-relative mb-4">
                                    <select name="status" class="form-select border-0 py-3 px-4">
                                        <option value="">All Status</option>
                                        <option value="for_rent" <?php selected($status, 'for_rent'); ?>>For Rent</option>
                                        <option value="for_sale" <?php selected($status, 'for_sale'); ?>>For Sale</option>
                                    </select>
                                </div>
                                
                                <!-- Bedrooms Select -->
                                <div class="form-group position-relative mb-4">
                                    <select name="bedrooms" class="form-select border-0 py-3 px-4">
                                        <option value="">Bedrooms</option>
                                        <?php for($i=1; $i<=10; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php selected($bedrooms, $i); ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                        <option value="10+" <?php selected($bedrooms, '10+'); ?>>10+</option>
                                    </select>
                                </div>
                                
                                <!-- Bathrooms Select -->
                                <div class="form-group position-relative mb-4">
                                    <select name="bathrooms" class="form-select border-0 py-3 px-4">
                                        <option value="">Bathrooms</option>
                                        <?php for($i=1; $i<=10; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php selected($bathrooms, $i); ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                        <option value="10+" <?php selected($bathrooms, '10+'); ?>>10+</option>
                                    </select>
                                </div>
                                
                                <!-- Garage Select -->
                                <div class="form-group position-relative mb-4">
                                    <select name="garage" class="form-select border-0 py-3 px-4">
                                        <option value="">Garages</option>
                                        <?php for($i=0; $i<=5; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php if(isset($_GET['garage'])) selected($_GET['garage'], $i); ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                        <option value="5+" <?php if(isset($_GET['garage'])) selected($_GET['garage'], '5+'); ?>>5+</option>
                                    </select>
                                </div>
                                
                                <!-- Home Area Range -->
                                <div class="form-group position-relative mb-4">
                                    <label>Min Home Area (Sqft): <span id="sqft_min_value"><?php echo esc_attr($sqft_min); ?></span></label>
                                    <input type="range" name="sqft_min" class="form-control border-0 py-3 px-4" min="0" max="2500" step="50" value="<?php echo esc_attr($sqft_min); ?>" oninput="document.getElementById('sqft_min_value').innerText = this.value">
                                </div>
                                <div class="form-group position-relative mb-4">
                                    <label>Max Home Area (Sqft): <span id="sqft_max_value"><?php echo esc_attr($sqft_max); ?></span></label>
                                    <input type="range" name="sqft_max" class="form-control border-0 py-3 px-4" min="0" max="2500" step="50" value="<?php echo esc_attr($sqft_max); ?>" oninput="document.getElementById('sqft_max_value').innerText = this.value">
                                </div>
                                
                                <!-- Search Button -->
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i> Search Properties
                                </button>
                            </form>
                            <div class=" text-start">
                                <a  class="reset-btn py-3 mt-2 " onclick="window.location.href=window.location.pathname">
                                     Reset Search
                            </a>

                            </div>
                            
                        </div>
                    </div>


    <!-- Properties Listing Column -->
                <div class="col-lg-8">
                    <div class="row g-4">
                                            <?php if ($query->have_posts()) : ?>
                                                <?php
                                    global $wp_query;
                                    $paged = max(1, get_query_var('paged'));
                                    $per_page = get_query_var('posts_per_page', 9); // Adjust this based on your query
                                    $total_posts = $wp_query->found_posts;

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
                                            <div class="carousel-inner">
                                                <?php  
                                                    $image_srcs = get_post_meta($post->ID, '_property_image_src', true);
                                                    $image_srcs = !empty($image_srcs) ? explode(',', $image_srcs) : []; 
                                                    $property_status = get_post_meta(get_the_ID(), 'property_status', true);

                                                ?>
                                                <?php if (!empty($image_srcs)) : ?>
                                                    <?php foreach ($image_srcs as $index => $image_src): ?>
                                                        <?php if (!empty($image_src)) : ?>
                                                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" data-bs-interval="10000000">
                                                                <p class="property_status_tag"><?php echo $property_status; ?></p>
                                                                <img src="<?php echo esc_url($image_src); ?>" class="d-block w-100" alt="Property Image" height='200px'>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <div class="carousel-item active">
                                                        <img src="https://via.placeholder.com/400" class="d-block w-100" alt="No Image Available">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo get_the_ID(); ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo get_the_ID(); ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
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

                                            <div class="d-flex gap-4">
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

                                            <div class="d-flex justify-content-between align-items-center">
                                                
                                            <span class="h5 text-primary">
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

<?php get_footer(); ?>
