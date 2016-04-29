(function(mw, $) {
	mw.loader.using('skins.minerva.toggling').then(function() {
		if (window.mobileatfmrec && window.mobileatfmrec.length) {
			var headings = $("#bodyContent").find("h1, h2, h3, h4, h5, h6");
			if ($(headings).length > 2) {
				$(headings[2]).before($('<div>').addClass('mobileatfmrec').append(window.mobileatfmrec));
			}
		}
		$('body').append($("<div>").attr("id", "cdm-zone-end"));
	});
}(mediaWiki, jQuery));