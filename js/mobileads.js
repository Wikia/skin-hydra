/* This is now inline

if (window.mobileatflb && window.mobileatflb.length) {
	var atfLbUnit = document.createElement('div');
	atfLbUnit.id = 'mobileatflb';
	atfLbUnit.insertAdjacentHTML('afterbegin', window.mobileatflb);
	document.getElementById('content').prepend(atfLbUnit);
}

var headings = document.querySelector("#bodyContent #mw-content-text").querySelectorAll(".mw-parser-output > h1, .mw-parser-output > h2, .mw-parser-output > h3, .mw-parser-output > h4, .mw-parser-output > h5, .mw-parser-output > h6");
if (window.mobileatfmrec && window.mobileatfmrec.length) {
	var adUnit = document.createElement('div');
	adUnit.id = 'mobileatfmrec';
	adUnit.insertAdjacentHTML('afterbegin', window.mobileatfmrec);
	
	if (headings.length > 1) {
		headings[1].parentNode.insertBefore(adUnit, headings[1]);
		headings[1].classList.add('open-block');
		//document.getElementById('content-collapsible-block-1').classList.add('open-block');
	} else if (headings.length > 0) {
		document.getElementById('bodyContent').insertBefore(adUnit, document.getElementById('bodyContent').firstChild);
	}
}

if (window.mobilebtfmrec && window.mobilebtfmrec.length) {
	if (headings.length > 5) {
		var btfMrecUnit = document.createElement('div');
		btfMrecUnit.id = 'mobilebtfmrec';
		btfMrecUnit.insertAdjacentHTML('afterbegin', window.mobilebtfmrec);
		headings[4].parentNode.insertBefore(btfMrecUnit, headings[4]);
		headings[4].classList.add('open-block');
		//document.getElementById('content-collapsible-block-4').classList.add('open-block');
	}
}

//var zoneEnd = document.createElement('div');
//zoneEnd.id = 'cdm-zone-end';
//document.body.appendChild(zoneEnd);

if (typeof factorem !== 'undefined') {
	var count = 0;
	var interval = setInterval(function () {

		count++;
		if (count > 25) {
			clearInterval(interval);
		}

		if (window.pbjs.initAdserverSet === true) {
			clearInterval(interval);
			var zone01Flag = false;
			for (var i = 0; i < factorem.adZones.length; i++) {
				if (factorem.adZones[i].zone == '01') {
					zone01Flag = true;
					break;
				}
			}
			
			factorem.gpt.setupDeferredSlots();
			if (zone01Flag === false) {
				factorem.refreshAds([1, 2, 6], true);
			}
			else {
				factorem.refreshAds([2, 6], true);
			}
		}
	}, 200);
}*/
