jQuery(document).ready(function($) {
	$('.sip-count').each(function () {
    	$('.sip-count').show();
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 2000,
            easing: 'swing',
            step: function (now) {
                 $(this).text( +(now).toFixed(2) );
            }
        });
    });
});