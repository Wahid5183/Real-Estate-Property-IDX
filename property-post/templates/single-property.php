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
            <div class="property-main">
                <!-- Property Header Section -->
                <div class="property_header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="property-title"><?php the_title(); ?></h1>
                            <div class="d-flex align-items-center text-muted">
                                <span style="color: #696969;"><i class="fas fa-map-marker-alt"></i> 
                                    <?php echo esc_html( get_post_meta( get_the_ID(), 'property_address', true ) ); ?>
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <h3 class="pricing mb-0">
                                <?php 
                                    $property_price = get_post_meta(get_the_ID(), 'property_price', true);
                                    echo is_numeric($property_price) ? "$" . number_format($property_price) : "N/A";
                                    if (get_post_meta(get_the_ID(), 'property_status', true) === 'For Rent') {
                                        echo "<small class='text-muted d-block'>per month</small>";
                                    }
                                ?>
                            </h3>
                        </div>
                    </div>
                    <div class="feathures d-flex mt-2">
                    <?php 
                                $details = [

                                    ['icon' => 'bath', 'label' => 'Baths', 'value' => get_post_meta(get_the_ID(), 'property_baths', true)],
                                    ['icon' => 'bed', 'label' => 'Beds', 'value' => get_post_meta(get_the_ID(), 'property_beds', true)],
                                    ['icon' => 'car', 'label' => 'Garages', 'value' => get_post_meta(get_the_ID(), 'property_garages', true)],
                                    ['icon' => 'calendar', 'label' => 'Year Built', 'value' => get_post_meta(get_the_ID(), 'property_year_built', true)],
                                    // Add other details
                                ];
                                foreach ($details as $detail) : ?>
                                    <div class=" feathure h-100">
                                        <i class=" fas fa-<?php echo $detail['icon']; ?>  me-2 feathure-icon"></i>
                                        <span class="fw-medium"><?php echo $detail['value']; ?></span>
                                        <span class="text-muted"><?php echo $detail['label']; ?></span>
                                        
                                </div>
                                
                                <?php endforeach; ?>
                    </div>
                </div>

                <!-- Image Gallery Section -->
                <div class="property-gallery mb-5">
    <?php  
        $image_srcs = get_post_meta(get_the_ID(), '_property_image_src', true);
        $image_srcs = !empty($image_srcs) ? explode(',', $image_srcs) : []; 
    ?>
    <div class="row g-3">
        <!-- Main Image -->
        <div class="col-md-7">
            <div class="position-relative ratio ratio-16x9 rounded-4 overflow-hidden">
                <a href="<?php echo esc_url($image_srcs[0]); ?>" data-fancybox="gallery" data-caption="Property Image">
                    <img src="<?php echo esc_url($image_srcs[0]); ?>" class="object-fit-cover main_img" alt="Main Property Image">
                </a>
                <div class="position-absolute top-3 end-3">
                    <span class="badge bg-white text-dark">
                        <i class="fas fa-camera me-2"></i><?php echo count($image_srcs); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Thumbnails -->
        <div class="col-md-5">
            <div class="row g-3 h-100">
                <?php  
                $max_images = 8;
                $total_images = count($image_srcs);
                foreach (array_slice($image_srcs, 1, $max_images) as $index => $image_src) :
                    $is_last_visible = ($index === $max_images - 1 && $total_images > $max_images);
                ?>
                <div class="col-4 position-relative">
                    <a href="<?php echo esc_url($image_src); ?>" data-fancybox="gallery" data-caption="Property Image" class="d-block">
                        <div class="ratio ratio-1x1 rounded-3 overflow-hidden">
                            <img src="<?php echo esc_url($image_src); ?>" class="object-fit-cover w-100 h-100" alt="Property Image">
                            
                            <?php if ($is_last_visible): ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center text-white text-decoration-none fw-bold">
                                +<?php echo $total_images - $max_images; ?> More
                            </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

                <!-- Main Content Area -->
                <div class="row g-5">
                    <div class="col-lg-8">
                        <!-- Description Section -->
                        <div class="description mb-5">
                            <h3 class="mb-4">Property Description</h3>
                            <div class="content-collapse">
                                    <div class="content-collapse-inner">
                                        <?php echo wpautop(get_post_meta( get_the_ID(), 'property_description', true )) ?>
                                    </div>
                                    <button class="btn btn-link p-0 text-primary read-more-btn mt-3 see-more"></button>
                                </div>
                        </div>

                        <!-- Details Section -->
                        <div class="property-details mb-5">
                            <h3 class="mb-4">Property Details</h3>
                            <div class="row row-cols-2 row-cols-md-3 g-4">
                                <?php 
                                $details = [
                                    ['icon' => 'clipboard-check', 'label' => 'ID', 'value' => get_post_meta(get_the_ID(), 'mls_listing_id', true)],
                                    ['icon' => 'home', 'label' => 'Home Area', 'value' => get_post_meta(get_the_ID(), 'property_home_area', true) . ' sqft'],
                                    ['icon' => 'layer-group', 'label' => 'Property Type', 'value' => get_post_meta(get_the_ID(), 'property_type', true)],
                                    ['icon' => 'bath', 'label' => 'Baths', 'value' => get_post_meta(get_the_ID(), 'property_baths', true)],
                                    ['icon' => 'expand', 'label' => 'Lot Area', 'value' => get_post_meta(get_the_ID(), 'property_lot_area', true).' sqft'],
                                    ['icon' => 'bed', 'label' => 'Beds', 'value' => get_post_meta(get_the_ID(), 'property_beds', true)],
                                    ['icon' => 'car', 'label' => 'Garages', 'value' => get_post_meta(get_the_ID(), 'property_garages', true)],
                                    ['icon' => 'calendar', 'label' => 'Year Built', 'value' => get_post_meta(get_the_ID(), 'property_year_built', true)],
                                    ['icon' => 'square-check', 'label' => 'Status', 'value' => get_post_meta(get_the_ID(), 'property_status', true)],
                                    // Add other details
                                ];
                                foreach ($details as $detail) : ?>
                                <div class="col">
                                    <div class="bg-light rounded-3 p-3 h-100">
                                        <i class="fas fa-<?php echo $detail['icon']; ?> text-primary me-2"></i>
                                        <span class="text-muted"><?php echo $detail['label']; ?>:</span>
                                        <span class="fw-medium"><?php echo $detail['value']; ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Features Section -->
                        <div class="property-features-list mb-5">
                            <h3 class="mb-4">Features</h3>
                            <div class="row row-cols-md-3 g-2">
                                <?php 
                                $features = get_post_meta( get_the_ID(), 'custom_fields', true );
                                foreach ($features as $feilds) : ?>
                                <div class="col-md-6 mb-3 ">
                                    <div class="d-flex align-items-center fs-6 gap-2">
                                        <strong><?php echo $feilds['name'] ?> :</strong>
                                        <span><?php echo $feilds['value']; ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php 
$video_url = get_post_meta(get_the_ID(), 'property_video', true);
if (!empty($video_url)) : 
    // Convert YouTube watch URL to embed format
    $video_url = str_replace(
        ['youtube.com/watch?v=', 'youtu.be/'],
        ['youtube.com/embed/', 'youtube.com/embed/'],
        $video_url
    );
    
    // Remove any URL parameters except the video ID
    $video_url = preg_replace('/\?.*/', '', $video_url);
?>
    <div class="property-video mb-5">
        <h3 class="mb-4">Property Video</h3>
        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
            <iframe 
                src="<?php echo esc_url($video_url); ?>" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
                loading="lazy"
            ></iframe>
        </div>
    </div>
<?php endif; ?>
                                                    <!-- Contact Form -->
                                                    <div class="schedule-tour mb-5 p-4 bg-light rounded-4 shadow-sm">
                    <h3 class="mb-4">Schedule a Tour</h3>
                    <form action="" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Date</label>
                                <input type="date" class="form-control" name="tour_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Time</label>
                                <input type="time" class="form-control" name="tour_time" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="tour_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="tour_phone" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="tour_email" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" name="tour_message" rows="3" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">Request a Tour</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="property-finance-list mb-5">
                            <h3 class="mb-4">Other Property Info</h3>
                            <div class="row row-cols-md-3 g-2">
                                <?php 
                                $finance_meta = [
                                    "Listing Price ($)" => 'property_price',
                                    "Close Price ($)" => 'property_close_price',
                                    "Days On Market" => 'property_daysonmarket',
                                    "Tex Annual Amount ($)" => 'property_taxannualamount',
                                    "Tex Assessed ($)" => 'property_taxassessedvalue',
                                    "Floor or Stroies" => 'property_storiestotal',

                                ];
foreach ($finance_meta as $key => $value) : ?>
                                <div class="col-md-6 mb-3 ">
                                    <div class="d-flex align-items-center fs-6 gap-2">
                                        <strong><?php echo $key ?> :</strong>
                                        <span><?php echo get_post_meta( get_the_ID(), $value, true ) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Section -->
                    <div class="col-lg-4">
                        <div class="property-sidebar sticky-top">


                            <!-- Agent Info Section -->
                            <div class="bg-light rounded-4 p-4 shadow-sm">
                                <div class="text-center mb-4">
                                    <img src="agent-avatar.jpg" class="rounded-circle" width="100" alt="Agent">
                                    <h5>Realty Smart Homes</h5>
                                    <p class="text-muted mb-0">401-405-8288</p>
                                    <p class="text-muted">grey@realtysmart.homes</p>
                                </div>
                                <!-- Contact Form -->
                                <?php if (isset($_GET['contact_sent'])) : ?>
                                    <div class="alert alert-success">Message sent successfully!</div>
                                <?php endif; ?>

                                <form method="POST">
                                    <?php wp_nonce_field('property_contact_form', 'contact_nonce'); ?>

                                    <div class="mb-3">
                                        <label for="contactName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="contactName" name="contactName" placeholder="Your Name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactPhone" class="form-label">Phone</label>
                                        <input type="tel" class="form-control" id="contactPhone" name="contactPhone" placeholder="Your Phone" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="contactEmail" name="contactEmail" placeholder="Your Email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactMessage" class="form-label">Message</label>
                                        <textarea class="form-control" id="contactMessage" name="contactMessage" rows="3" placeholder="Your Message" required></textarea>
                                    </div>
                                    <input type="hidden" class="form-control" id="property_id" name="property_id" value="<?php echo get_the_ID(); ?>">
                                    <button type="submit" name="contact_submit" class="btn btn-primary w-100">Send Message</button>
                                </form>

                                    
                                <!-- Contact Buttons -->
                                <div class="d-grid gap-2 mt-3">
                                    <a href="tel:+123456789" class="btn btn-outline-primary"><i class="fas fa-phone me-2"></i> Contact Property</a>
                                    <a href="https://wa.me/123456789" target="_blank" class="btn btn-success"><i class="fab fa-whatsapp me-2"></i> WhatsApp Contact</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </article>
        <?php endwhile; ?>
    </div>
</div>

<style>
.property-gallery img {
    transition: transform 0.3s ease;
    cursor: pointer;
}

.property-gallery img:hover {
    transform: scale(1.03);
}

.icon-box {
    transition: transform 0.3s ease;
}

.content-collapse-inner {
    position: relative;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.read-more-btn:after {
    content: '▼';
    font-size: 0.8em;
    margin-left: 0.5em;
    transition: transform 0.3s ease;
}

.read-more-btn.active:after {
    transform: rotate(180deg);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Read More/Less functionality
    document.querySelectorAll('.read-more-btn').forEach(btn => {
        const content = btn.previousElementSibling;
        const maxHeight = 150;
        
        if (content.scrollHeight > maxHeight) {
            content.style.maxHeight = `${maxHeight}px`;
            btn.style.display = 'block';
            
            btn.addEventListener('click', () => {
                content.style.maxHeight = 
                    content.style.maxHeight ? null : `${maxHeight}px`;
                btn.classList.toggle('active');
                btn.textContent = btn.classList.contains('active') ? 'Show Less' : 'Read More';
            });
        } else {
            btn.style.display = 'none';
        }
    });
})
</script>

<?php get_footer(); ?>