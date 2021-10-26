var $jQbody = false;

jQuery(document).ready(function() {
    $jQbody = jQuery('body');

    /* Set mobile nav toggle */
    jQuery('.nav-toggle').on('click', function(e) {
        e.preventDefault();
        $jQbody.toggleClass('has--opened-main-menu');
    });

});
