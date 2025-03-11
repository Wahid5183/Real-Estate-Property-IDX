<?php
/**
 * Template for displaying single Property posts.
 */
get_header();
?>

<div class="single-property">
    <div class="container">
        <?php custom_breadcrumb()?>
        <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <h1 class="property-title"></h1>
                    
        <div class="property-main">
            <div class="property-header">
                <h1 class="fs-3 fw-bold"><?php the_title(); ?></h1>
            </div>
            <div class="property-address">
            <p class="fs-6"><?php echo esc_html( get_post_meta( get_the_ID(), 'property_address', true ) ); ?></p>
            </div>
            <div class="property-details">
                <div class="basic-details d-flex justify-content-between">
                    <div class="house-details d-flex gap-3 ">
                        <p class="fs-5 gap-2"><i class="fa-light fa-bed"></i><span><?php echo get_post_meta(get_the_ID(), 'property_beds', true); ?> Beds</span></p>
                        <p class="fs-5 gap-2"><i class="fa-light fa-bath"></i></i><span><?php echo get_post_meta(get_the_ID(), 'property_baths', true); ?> Baths</span></p>
                        <p class="fs-5 gap-2"><i class="fa-light fa-warehouse"></i><span><?php echo get_post_meta(get_the_ID(), 'property_garages', true); ?> Garages</span></p>
                        <p class="fs-5 gap-2"><i class="fa-light fa-ruler-triangle"></i><span><?php echo get_post_meta(get_the_ID(), 'property_home_area', true); ?>sqft </span></p>
                        <p class="fs-5 gap-2"><i class="fa-light fa-bed"></i><span><?php echo get_post_meta(get_the_ID(), 'property_year_built', true); ?> Year built </span></p>
                    </div>
                    <div class="pricing text-right">
                        <p class="h5 text-primary">
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
                        </p> 

                    </div>

                    
                
                </div>
                <div class="property-images-gallery">
                            <?php  
                                $image_srcs = get_post_meta(get_the_ID(), '_property_image_src', true);
                                $image_srcs = !empty($image_srcs) ? explode(',', $image_srcs) : []; 
                                $property_status = get_post_meta(get_the_ID(), 'property_status', true);
                            ?>

                            <?php if (!empty($image_srcs)) : ?>
                                <div class="row">
                                    <div class="col-sm-7 col-xs-12">
                                        <div class="gallery-property-main-detail">
                                            <img src="<?php echo esc_url($image_srcs[0]); ?>" alt="Main Property Image" width="912" height="548">
                                            
                                            <!-- Property Status Tag -->
                                            <?php if (!empty($property_status)) : ?>
                                                <span class="property-status"><?php echo esc_html($property_status); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 col-xs-12">
                                        <div class="row row-10">
                                            <?php foreach (array_slice($image_srcs, 1, 3) as $image_src) : ?>
                                                <div class="col-sm-4 col-xs-4">
                                                    <img src="<?php echo esc_url($image_src); ?>" alt="Property Image" width="100%" height="auto">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <p>No images available for this property.</p>
                            <?php endif; ?>
                        </div>

                    </div>
            </div>
            <div class="row property-v-wrapper">
                <div class="col-xs-12 property-detail-main col-md-8 col-lg-9">
                    <div class="description inner">
                        <h3 class="title">Overview</h3>
                        <div class="description-inner show-less">
                            <div class="description-inner-wrapper">
                                <p><?php echo esc_html( get_post_meta( get_the_ID(), 'property_description', true ) ); ?></p>
                            </div>
                            <div class="show-more-less-wrapper">
                                <a  class="show-more">Show more</a>
                                <a  class="show-less" style="display: none;">Show less</a>
                            </div>
                        </div>
                    </div>


                </div> 
                <div class="col-xs-12 col-md-4 col-lg-3 sidebar-property sidebar-wrapper sticky-this"></div>
            </div>
            <div class="property-detail-detail">
                
            </div>
            
        </div>
</article>
</div>
    
    <?php endwhile; ?>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".description").forEach(function(description) {
        const descriptionWrapper = description.querySelector(".description-inner-wrapper");
        const showMoreBtn = description.querySelector(".show-more");
        const showLessBtn = description.querySelector(".show-less");

        showMoreBtn.addEventListener("click", function() {
            descriptionWrapper.style.maxHeight = "none";
            showMoreBtn.style.display = "none";
            showLessBtn.style.display = "inline";
        });

        showLessBtn.addEventListener("click", function() {
            descriptionWrapper.style.maxHeight = "100px"; // Set this to the collapsed height
            showMoreBtn.style.display = "inline";
            showLessBtn.style.display = "none";
        });
    });
});

</script>

</div>

<?php get_footer(); ?>
