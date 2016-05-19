(function(mw, $) {
	var sideRail = $('#siderail');
	if ($(sideRail).length > 0 && $(sideRail).outerHeight() < $('#bodyContent').outerHeight()) {
		window.sideRailStartTop = $(sideRail).offset().top;
		window.sideRailLeft = $(sideRail).offset().left;
		window.sideRailMarginTop = $(sideRail).css('top');
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
		$(siderail).css('left', window.sideRailLeft);
		$(window).scroll(function() {
			if (Math.ceil(window.sideRailStartTop) < Math.ceil($(document).scrollTop())) {
				var calcMarginTop = $(document).scrollTop() - window.sideRailStartTop;
				//console.log($(document).scrollTop());
				//console.log(window.sideRailStartTop);
				if ($(document).scrollTop() < window.maxsideRailMarginTop) {
					$(siderail).css('top', '10px');
					$(sideRail).addClass('fixed');
				} else {
					$(siderail).css('top', window.maxsideRailMarginTop + 'px');
				}
				//$(sideRail).css('margin-top', (calcMarginTop < window.maxsideRailMarginTop ? calcMarginTop : window.maxsideRailMarginTop) + 'px');
			} else if (window.sideRailStartTop >= $(document).scrollTop()) {
				$(siderail).css('top', 'auto');
				$(sideRail).removeClass('fixed');
			}
		});
	}

	function updateMaxSideRailMarginTop(sideRail) {
		console.log($('#bodyContent').outerHeight());
		console.log($(sideRail).outerHeight());
		window.maxsideRailMarginTop = $('#bodyContent').outerHeight() - $(sideRail).outerHeight() + window.sideRailStartTop;
		console.log(window.maxsideRailMarginTop);
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