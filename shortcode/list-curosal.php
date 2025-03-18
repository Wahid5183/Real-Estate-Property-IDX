<?php
// Shortcode: [property_horizontal posts_per_page="12" status="rent|sold"]
function property_horizontal_shortcode( $atts ) {
    // Parse shortcode attributes
    $atts = shortcode_atts( array(
        'posts_per_page' => 12,
        'order'          => 'DESC',
        'status'         => '' // Default is empty (fetch recent properties)
    ), $atts, 'property_horizontal' );

    $meta_query = array();
    if ( !empty($atts['status']) ) {
        if ( $atts['status'] === 'rent' ) {
            $meta_query[] = array(
                'key'   => 'property_status',
                'value' => 'Rented/Leased',
            );
        } elseif ( $atts['status'] === 'sold' ) {
            $meta_query[] = array(
                'key'   => 'property_status',
                'value' => 'Sold',
            );
        }
    }

    $args = array(
        'post_type'      => 'property',
        'posts_per_page' => intval($atts['posts_per_page']),
        'order'          => sanitize_text_field($atts['order']),
        'meta_query'     => $meta_query
    );

    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) {
        return '<p>No properties found.</p>';
    }

    ob_start();
    ?>
    <div class="property-carousel slick-slider" style="max-width: 100%;">
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="property-slide mx-2">
                <div class="card shadow-sm h-100">
                    <div id="carousel-<?php echo get_the_ID(); ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner slide_display_img">
                            <?php  
                                $image_srcs = get_post_meta(get_the_ID(), '_property_image_src', true);
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
                                                <p class="property_status_tag" style="background-color: black;">Featured</p>
                                            </div>
                                            <a href="<?php echo the_permalink(); ?>">
                                                <img src="<?php echo esc_url($image_src); ?>" class="d-block w-100 " alt="Property Image" height='200px' style="height: 280px;">
                                            </a>
                                            <div class="property_camera_tag">
                                                <i class="fa-solid fa-camera"></i>
                                                <p><?php echo " " . count($image_srcs); ?></p>
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
                        </button>
                        <button class="carousel-control-next next_img" type="button" data-bs-target="#carousel-<?php echo get_the_ID(); ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon " aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-start">
                            <a class="property_name" href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a>
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
                                echo is_numeric($property_price) ? "$" . number_format($property_price) : "N/A";
                                if ($property_status === 'Rented/Leased') {
                                    echo " /mo";
                                }
                            ?>
                        </span>
                        <a href="<?php the_permalink(); ?>" class=""><i class="fa fa-expand" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.property-carousel').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 100000000000000,
            arrows: true,
            dots: false,
            responsive: [
                { breakpoint: 1024, settings: { slidesToShow: 3 } },
                { breakpoint: 600, settings: { slidesToShow: 2 } },
                { breakpoint: 480, settings: { slidesToShow: 1 } }
            ]
        });
    });
    </script>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('property_horizontal', 'property_horizontal_shortcode');
?>
