<?php 
// Template for single property page
    get_header(); 

    global $post;
    $id = $post->ID;

    $featured_image = get_the_post_thumbnail_url($id, 'full');
    $name = get_the_title($id);
    $content = get_the_content($id);

    $bed = get_field('bed', $id);
    $bath = get_field('bath', $id);
    $sleeps = get_field('maximum_occupancy', $id);

    $address = get_field('address', $id);
    $map_address = get_field('map_address', $id);
    $check_in = get_field('check_in', $id);
    $check_out = get_field('check_out', $id);
    $gallery_photos = get_field('gallery_photos', $id);
?>


<div class="bookerville_page_container single-property single-property-<?= $id; ?>">
    <div class="bookerville_property_hero" style="background-image: url(<?= $featured_image; ?>); background-size: cover; background-position: center center;">
        <div class="bookerville_wrapper">
            <?php 
                $templates = new BV_Template_Loader;
                $templates->get_template_part( 'content', 'form' );
            ?>
        </div>
    </div>
    <div class="bookerville_wrapper">
        <div class="bookerville_property_wrapper">
            <div class="bookerville_page_left_content">
                <?php if($gallery_photos) { ?>
                <div class="bookerville_property_carousel">
                    <div class="bookerville_property_main_carousel">
                        <?php foreach($gallery_photos as $photo) { ?>
                            <img src="<?php echo $photo['full_image_url']; ?>" alt="<?php echo $photo['title']; ?>">
                        <?php } ?>
                    </div>
                    <div class="bookerviller_property_thumbnails">
                        <?php foreach($gallery_photos as $photo) { ?>
                            <img src="<?php echo $photo['full_image_url']; ?>" alt="<?php echo $photo['title']; ?>">
                        <?php } ?>
                    </div>
                </div>
                <?php } else { ?>
                <div class="bookerville_property_featured_image">
                    <img src="<?= $featured_image; ?>" alt="<?= $name; ?>">
                </div>
                <?php } ?>

                <h1 class="bookerville_property_title"><?= $name; ?></h1>
                <div class="property_meta">
                    <i class="fa-solid fa-map"><span>Address: <?= $address; ?></span></i>
                </div>
                <p class="bookerville_property_content">
                    <?= $content; ?>
                </p>
            </div>

            <div class="bookerville_page_right_content">
                <div class="bookerville_property_info">
                    <h4>Property Details</h4>
                    <div class="property_meta">
                        <i class="fa-solid fa-bed"><span>Bedrooms: <?= $bed; ?></span></i>
                        <i class="fa-solid fa-bath"><span>Bathroom: <?= $bath; ?></span></i>
                        <i class="fa-solid fa-user-group"><span>Good for <?= $sleeps; ?> people</span></i>
                        <i class="fa-solid fa-clock"><span>Check in time: <?= $check_in; ?></span></i>
                        <i class="fa-solid fa-clock"><span>Check out time: <?= $check_out; ?></span></i>
                    </div>

                    <h4>Map Location</h4>
                    <div class="property_map">
                        <?= $map_address; ?>
                    </div>

                    <h4>Booking Widget Here</h4>
                    <div class="bookerville_booking_widget">
                        *insert booking widget*
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    get_footer(); 
?>