jQuery(document).ready(function() {

    jQuery('.cart-wrapper .cart-views').click(function() {
        jQuery('.cart-container').toggleClass('active');
    });

    jQuery('.user-target').click(function() {
        jQuery('.user-wrapper').toggleClass('active');
    });
});