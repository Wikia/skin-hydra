(function(mw, $) {
	mw.loader.using('skins.minerva.toggling').then(function() {
		if (window.mobileatflb && window.mobileatflb.length) {
			var atfLbUnit = $('<div id="mobileatflb">').append(window.mobileatflb);
			$("#content").prepend(atfLbUnit);
		}

		var headings = $("#bodyContent #mw-content-text").find("> h1, > h2, > h3, > h4, > h5, > h6");
		if (window.mobileatfmrec && window.mobileatfmrec.length) {
			var adUnit = $('<div id="mobileatfmrec">').append(window.mobileatfmrec);
			if ($(headings).length > 1) {
				$(headings[1]).before(adUnit);
				$(headings[1]).addClass('open-block');
				$('#content-collapsible-block-1').addClass('open-block');
			} else if ($(headings).length > 0) {
				$("#bodyContent").prepend(adUnit);
			}
		}
		if (window.mobilebtfmrec && window.mobilebtfmrec.length) {
			if ($(headings).length > 5) {
				var btfMrecUnit = $('<div id="mobilebtfmrec">').append(window.mobilebtfmrec);
				$(headings[4]).before(btfMrecUnit);
				$(headings[4]).addClass('open-block');
				$('#content-collapsible-block-4').addClass('open-block');
			}
		}
		$('body').append($("<div>").attr("id", "cdm-zone-end"));
	});
}(mediaWiki, jQuery));