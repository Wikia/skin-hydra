(function(mw, $) {
	var sideRail = $('#siderail');
	if ($(sideRail).length > 0 && $(sideRail).outerHeight() < $('#bodyContent').outerHeight()) {
		window.sideRailStartTop = $(sideRail).offset().top;
		window.sideRailMarginTop = $(sideRail).css('margin-top');
		updateMaxSideRailMarginTop(sideRail);
		$('#bodyContent').on('DOMSubtreeModified', function() {
			updateMaxSideRailMarginTop(sideRail);
		});
		$(sideRail).on('DOMSubtreeModified', function() {
			updateMaxSideRailMarginTop(sideRail);
		});
		$(window).resize(function() {
			updateMaxSideRailMarginTop(sideRail);
		});
		$(window).scroll(function() {
			var offset = 5;
			if (Math.ceil(window.sideRailStartTop - offset) < Math.ceil($(document).scrollTop())) {
				var calcMarginTop = $(document).scrollTop() - window.sideRailStartTop + offset;
				$(sideRail).css('margin-top', (calcMarginTop < window.maxsideRailMarginTop ? calcMarginTop : window.maxsideRailMarginTop) + 'px');
			} else if (window.sideRailStartTop >= $(document).scrollTop()) {
				$(sideRail).css('margin-top', window.sideRailMarginTop);
			}
		});
	}

	function updateMaxSideRailMarginTop(sideRail) {
		window.maxsideRailMarginTop = $('#bodyContent').outerHeight() - $(sideRail).outerHeight();
	}

	/* Hide siderail when we are editing with VE */
	$("#ca-ve-edit span a").click(function(){
		sideRail.hide();
	});
	/* If a state is popped (back button pressed) we are most likely leaving VE, so show the sideRail */
	window.onpopstate = function(){
		sideRail.show();
	}
}(mediaWiki, jQuery));