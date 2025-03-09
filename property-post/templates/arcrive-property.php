<?php
/*
Template Name: Property Archive
Description: A custom archive page to display property posts with filter options via URL parameters.
*/
get_header();

// Retrieve filter parameters from the URL
$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : '';
$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : '';
$status    = isset($_GET['status'])    ? sanitize_text_field($_GET['status']) : '';

// Build meta query based on provided filters.
$meta_query = array();
if ( $price_min !== '' ) {
    $meta_query[] = array(
        'key'     => 'property_price',
        'value'   => $price_min,
        'type'    => 'NUMERIC',
        'compare' => '>='
    );
}
if ( $price_max !== '' ) {
    $meta_query[] = array(
        'key'     => 'property_price',
        'value'   => $price_max,
        'type'    => 'NUMERIC',
        'compare' => '<='
    );
}
if ( $status !== '' ) {
    $meta_query[] = array(
        'key'     => 'property_status',
        'value'   => $status,
        'compare' => '='
    );
}

// Set up pagination.
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

// Query the 'property' posts with the meta query.
$args = array(
    'post_type'      => 'property',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'meta_query'     => $meta_query,
);
$query = new WP_Query( $args );
?>

<div class="property-archive">
    <h1>Property Archive</h1>
    
    <!-- Filter form (optional) -->
    <form method="get" action="">
        <label for="price_min">Price Min:</label>
        <input type="number" name="price_min" id="price_min" value="<?php echo esc_attr( $price_min ); ?>" />
        
        <label for="price_max">Price Max:</label>
        <input type="number" name="price_max" id="price_max" value="<?php echo esc_attr( $price_max ); ?>" />
        
        <label for="status">Status:</label>
        <input type="text" name="status" id="status" value="<?php echo esc_attr( $status ); ?>" placeholder="e.g., Active" />
        
        <input type="submit" value="Filter" />
    </form>
    
    <?php if ( $query->have_posts() ) : ?>
        <ul class="property-list">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <li class="property-item">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><strong>Price:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_price', true ) ); ?></p>
                    <p><strong>Status:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_status', true ) ); ?></p>
                    <p><strong>Living Area:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_living_area', true ) ); ?></p>
                    <!-- Add additional details as needed -->
                </li>
            <?php endwhile; ?>
        </ul>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            echo paginate_links( array(
                'total' => $query->max_num_pages
            ) );
            ?>
        </div>
    <?php else : ?>
        <p>No properties found.</p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</div>

<?php get_footer(); ?>
