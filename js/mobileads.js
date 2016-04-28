(function(mw, $) {
	mw.loader.using('mobile.toggling').then(function() {
		if (window.mobileatfmrec && window.mobileatfmrec.length) {
			var headings = $("#bodyContent").find("h1, h2, h3, h4, h5, h6");
			if ($("#content").find(".content_block").length > 2) {
				$('div#content_block_1').after($('<div>').addClass('mobileatfmrec').append(window.mobileatfmrec));
			} else if ($(headings).length > 2) {
				$(headings[2]).before($('<div>').addClass('mobileatfmrec').addClass('noborder').append(window.mobileatfmrec));
			}
		}
	});
}(mediaWiki, jQuery));