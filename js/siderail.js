(function(mw, $) {
	var sideRail = $('#siderail');
	if ($(sideRail).length > 0 && $(sideRail).outerHeight() < $('#bodyContent').outerHeight()) {
		window.sideRailStartTop = $(sideRail).offset().top;
		window.sideRailCssTop = $(sideRail).css('top');
		window.sideRailAtTop = true;
		window.sideRailAtBottom = false;
		window.sideRailVisible = $(sideRail).is(':visible');

		/*$('#bodyContent').on('DOMSubtreeModified', function() {
			updateMaxSideRailTop(sideRail);
        });
		$(sideRail).on('DOMSubtreeModified', function() {
			updateMaxSideRailTop(sideRail);
		});*/
		$(window).resize(function() {
			updateSideRailPosition(sideRail);
		});

		$(window).scroll(function() {
			if ($(sideRail).is(':hidden')) {
				return;
			}
			if (window.sideRailStartTop >= $(document).scrollTop()) {
				$(sideRail).css('top', window.sideRailCssTop);
				$(sideRail).css('position', 'relative');
				$(sideRail).css('left', 'auto');
				window.sideRailAtTop = true;
			} else {
				window.sideRailAtTop = false;
			}
			if ($('#bodyContent').outerHeight() > $(sideRail).outerHeight() && Math.ceil($(document).scrollTop()) >= $('#bodyContent').outerHeight() + $('#bodyContent').offset().top - $(sideRail).outerHeight()) {
				// && Math.ceil($('#bodyContent').outerHeight() - $(sideRail).outerHeight()) > Math.ceil($(document).scrollTop())
				$(sideRail).css('top', ($('#bodyContent').outerHeight() - $(sideRail).outerHeight()) + 'px');
				$(sideRail).css('position', 'relative');
				$(sideRail).css('left', 'auto');
				window.sideRailAtBottom = true;
			} else {
				window.sideRailAtBottom = false;
			}

			if ($('#bodyContent').outerHeight() > $(sideRail).outerHeight() && !window.sideRailAtTop && !window.sideRailAtBottom) {
				$(sideRail).css('top', window.sideRailCssTop);
				$(sideRail).css('left', $(sideRail).offset().left+'px');
				$(sideRail).css('position', 'fixed');
			}
		});

		function updateSideRailPosition(sideRail) {
			if (!window.sideRailVisible && $(sideRail).is(':visible')) {
				window.sideRailVisible = true;
				window.sideRailStartTop = $(sideRail).offset().top;
				window.sideRailCssTop = $(sideRail).css('top');
			}
			if (!window.sideRailAtTop && !window.sideRailAtBottom && $(sideRail).css('position') == 'fixed') {
				$(sideRail).css('position', 'relative');
				$(sideRail).css('left', 'auto');
				$(sideRail).css('left', $(sideRail).offset().left+'px');
				$(sideRail).css('position', 'fixed');
			}
		}
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