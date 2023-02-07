// initialize slides

jQuery(document).ready(function(){
    jQuery('.bookerville_property_slider').each(function(){
        var target = jQuery(this).data('target');
    
        var settings = jQuery(this).data('settings');
        var settingsClean = (settings.substring(1, settings.length-1)).replace(/\\/g, "");
    
        var data = JSON.parse(settingsClean);

    
        jQuery('#bookerville_slider_'+target).slick({
            slidesToShow: data['slidesToShow'],
            slidesToScroll: 1,
            dots: false,
            autoPlay: false,
            inifiniteScroll: true,
            prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
            nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>"
        });
    });


    // Slider for property page
    jQuery('.bookerville_property_main_carousel').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.bookerviller_property_thumbnails'
    });

    jQuery('.bookerviller_property_thumbnails').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.bookerville_property_main_carousel',
        dots: true,
        centerMode: true,
        focusOnSelect: true,
        prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
            nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>"
      });
});