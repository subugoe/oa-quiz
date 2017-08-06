(function ($) {
	// Check cookies and JS
	document.cookie = "test=test";
	var cookiesEnabled = document.cookie.indexOf("test=") > -1;
	if (cookiesEnabled) $('.quiz_warning').hide();
	// Get rid of test cookie
	document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";

	var $submit = $('.quiz [type=submit]');
	$submit.prop('disabled', true);
	$('.quiz [type=radio]').click(function () {
		$submit.prop('disabled', false);
	});

	var $time = $('.quiz_clock');
	var time = parseFloat($time.data('start'));
	var timer = setInterval(function () {
		time += 1;
		var hours = Math.floor(time / 3600);
		var minutes = Math.floor(time % 3600 / 60);
		var seconds = Math.floor(time % 3600 % 60);
		$time.text(
			hours
			+ ':' + (minutes < 10 ? '0' : '') + minutes
			+ ':' + (seconds < 10 ? '0' : '') + seconds
		);
	}, 1000);

	$submit.click(function () {
		clearInterval(timer);
	});
}(jQuery));
