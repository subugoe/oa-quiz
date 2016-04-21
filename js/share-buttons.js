( function($){
	var buttonsContainer = $('.shariff');

	new Shariff(buttonsContainer, {
		'backend-url': null,
		services: ['twitter', 'facebook', 'googleplus', 'tumblr'],
		url: location.protocol + '//' + location.host + location.pathname
	});
}(jQuery));
