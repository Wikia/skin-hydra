(function(mw, $) {
	mw.loader.using('mobile.toggling').then(function() {
		if (window.mobileatfmrec && window.mobileatfmrec.length) {
			var headings = $("#content").find("h1, h2, h3, h4, h5, h6");
			if ($("#content").find(".content_block").length > 2) {
				$('div#content_block_1').after($('<div>').addClass('mobileatfmrec').append(window.mobileatfmrec));
			} else if ($(headings).length > 2) {
				$(headings[2]).before($('<div>').addClass('mobileatfmrec').addClass('noborder').append(window.mobileatfmrec));
			}
			$('div#footer').append($('<div>').addClass('mobilebtfmrec').append($("<div>").attr("id", "cdm-zone-03")));
			$('body').append($("<div>").attr("id", "cdm-zone-end"));
		}
	});
}(mediaWiki, jQuery));