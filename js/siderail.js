(function(mw, $) {
	var sideRail = $('#siderail');
	if ($(sideRail).length > 0 && $(sideRail).outerHeight() < $('#bodyContent').outerHeight()) {
		window.sideRailStartTop = $(sideRail).offset().top;
		window.sideRailMarginTop = $(sideRail).css('margin-top');
		window.maxsideRailMarginTop = $('#bodyContent').outerHeight() - $(sideRail).outerHeight();
		$('#bodyContent').on('DOMSubtreeModified', function() {
			window.maxsideRailMarginTop = $('#bodyContent').outerHeight() - $(sideRail).outerHeight();
		});
		$(window).scroll(function() {
			var offset = 10;
			if (Math.ceil(window.sideRailStartTop - offset) < Math.ceil($(document).scrollTop())) {
				var clamp = false;
				var calcMarginTop = $(document).scrollTop() - window.sideRailStartTop + offset;
				$(sideRail).css('margin-top', (calcMarginTop < window.maxsideRailMarginTop ? calcMarginTop : window.maxsideRailMarginTop) + 'px');
			} else if (window.sideRailStartTop >= $(document).scrollTop()) {
				$(sideRail).css('margin-top', window.sideRailMarginTop);
			}
		});
	}
}(mediaWiki, jQuery));