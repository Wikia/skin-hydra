(function(mw, $) {
	var sideRail = $('#siderail');
	if ($(sideRail).length > 0) {
		window.sideRailStartTop = $(sideRail).offset().top;
		window.sideRailMarginTop = $(sideRail).css('margin-top');
		var maxsideRailMarginTop = $('#bodyContent').innerHeight() - $(sideRail).outerHeight();
		$(window).scroll(function() {
			var offset = 10;
			if (Math.ceil(window.sideRailStartTop - offset) < Math.ceil($(document).scrollTop())) {
				var calcMarginTop = $(document).scrollTop() - window.sideRailStartTop + offset;
				$(sideRail).css('margin-top', (calcMarginTop < maxsideRailMarginTop ? calcMarginTop : maxsideRailMarginTop) + 'px');
			} else if (window.sideRailStartTop >= $(document).scrollTop()) {
				$(sideRail).css('margin-top', window.sideRailMarginTop);
			}
		});
	}
}(mediaWiki, jQuery));