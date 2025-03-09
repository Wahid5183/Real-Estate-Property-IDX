<?php
/**
 * Template for displaying single Property posts.
 */
get_header();
?>

<div class="single-property">
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="property-header">
                <h1 class="property-title"><?php the_title(); ?></h1>
            </header>

            <div class="property-content">
                <?php the_content(); ?>
            </div>

            <div class="property-details">
                <p><strong>Description:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_description', true ) ); ?></p>
                <p><strong>Location:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_location', true ) ); ?></p>
                <p><strong>Listing ID:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_listing_id', true ) ); ?></p>
                <p><strong>Home Area:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_home_area', true ) ); ?></p>
                <p><strong>Rooms:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_rooms', true ) ); ?></p>
                <p><strong>Baths:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_baths', true ) ); ?></p>
                <p><strong>Price ($):</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_price', true ) ); ?></p>
                <p><strong>Status:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_status', true ) ); ?></p>
                <p><strong>Beds:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_beds', true ) ); ?></p>
                <p><strong>Garages:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_garages', true ) ); ?></p>
                <p><strong>Lot Dimensions:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_lot_dimensions', true ) ); ?></p>
                <p><strong>Year Built:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_year_built', true ) ); ?></p>
                <p><strong>Lot Area:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), 'property_lot_area', true ) ); ?></p>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
