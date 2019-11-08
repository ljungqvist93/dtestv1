$('#create_menu').click(function(){
	$('#create_options').slideToggle('fast', function(){});
});
$('#create_menu').click(function(){
	$('#admintools').slideToggle('fast', function(){});
});
$('#mobile_toggle').click(function(){
	$('#responsive').slideToggle('fast', function(){});
});


var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('#topper_mobile').outerHeight();

$(window).scroll(function(event){
    didScroll = true;
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 20);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $('#topper_mobile').addClass('visible');
        $('#mobile').addClass('visible');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('#topper_mobile').removeClass('visible');
            $('#mobile').removeClass('visible');
        }
    }
    
    lastScrollTop = st;
}