$('#toggleAdmin').on('click', function() {
    $('#adminTools').slideToggle();
});

$('#imageToggler').on('click', function () {
    $('#imageIframe').slideToggle();
});

$('#mobileMenuToggle').on('click', function() {
    $('#coreSocials').slideToggle(150);
});

$(document).ready(function(){
    $('#caruselle').slick({
        prevArrow: false,
        nextArrow: false,
        dots: true,
        autoplaySpeed:3000,
        autoplay: true,
        mobileFirst: true
    });
});