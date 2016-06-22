function showCpmStarSlots(cpmStar) {
	var adContainer = document.getElementById(window.cpmStarDivId);
	if (adContainer) {
		var i = 0;
		var flexes = '';
		while (cpmStar.adsLeft()) {
			i++;
			var imageUrl = cpmStar.getImageUrl(140, 80);
			imageUrl = imageUrl.replace('http://cdn2.cpmstar.com', '/skins/Hydra/js/cpmstar.php?/cdn2');
			imageUrl = imageUrl.replace('http://cdn3.cpmstar.com', '/skins/Hydra/js/cpmstar.php?/cdn3');
			flexes = flexes + "<div id='slot"+i+"' class='slot flex-item'>"+
				"<a href='" + cpmStar.getLink() + "' title='" + cpmStar.getTitle() + "' target='_blank'>"+
					"<div class='image_holder middle'><img src='" + imageUrl + "'></div>"+
					"<span class='tagline'>" + cpmStar.getTitle() + "</span>"+
				"</a>"+
			"</div>";
			cpmStar.nextAd();
		}
		var ad = document.createElement('div');
		ad.setAttribute("class", "flex-container");
		ad.innerHTML = flexes;
		adContainer.appendChild(ad);
	}
}

window.cpmstar_dynamic_editorials = {
	editorial_1: {
		cpmstar_pid: 72135,	// Curse Gaming Dynamic Editorial Pool ID
		cpmstar_multi: 4, // Number of ads to show
		cpmstar_callback: showCpmStarSlots
	}
};

window.cpmstar_editorials = function(j) {
	var k = [],
		l = function() {
			this.cpmStarMultiAdInfo = [];
			this.adCounter = 0
		};
	l.prototype = {
		getCurrentAdInfo: function() {
			return this.cpmStarMultiAdInfo[this.adCounter]
		},
		getAuthor: function() {
			return "undefined" == typeof this.getCurrentAdInfo().author ? "" : this.getCurrentAdInfo().author
		},
		getImageUrl: function(a, c) {
			for (var f = this.getCurrentAdInfo().imgs, m = a / c, g = a * c, n = {
				url: "",
				width: a,
				height: c
			}, h = -1, i = 0; i < f.length; i++) {
				var b = f[i],
					e, d = b;
				e = 10 * (Math.min(d.width / d.height, m) / Math.max(d.width /
					d.height, m));
				d = Math.min(d.width * d.height, g) / Math.max(d.width * d.height, g);
				e = e + d || 0;
				e > h && (h = e, n = b)
			}
			return n.url
		},
		getLink: function() {
			return this.getCurrentAdInfo().link
		},
		getTitle: function() {
			return this.getCurrentAdInfo().title
		},
		getDescription: function(a) {
			var a = "undefined" == typeof a ? 255 : a,
				c = this.getCurrentAdInfo().desc;
			"undefined" != typeof c && c.length > a && (c = c.substr(0, a - 3) + "...");
			return c
		},
		nextAd: function() {
			this.adCounter++
		},
		numAds: function() {
			return this.cpmStarMultiAdInfo.length
		},
		adsLeft: function() {
			return this.cpmStarMultiAdInfo.length -
				this.adCounter
		}
	};
	var s = function(a) {
			a.callback = function(c) {
				var g = new l,
					f = a.cpmstar_callback;
				if ("undefined" != typeof c.creatives)
					for (var h = 0; h < c.creatives.length; h++) {
						var i = g,
							b = c.creatives[h],
							e = [];
						e.author = b.creativemacros.AUTHOR;
						e.title = b.creativemacros.TITLE;
						e.desc = b.creativemacros.DESC;
						e.link = b.click;
						var d = [];
						"" != b.creativemacros.IMAGE60X90 && d.push({
							url: b.creativemacros.IMAGE60X90,
							width: 60,
							height: 90
						});
						"" != b.creativemacros.IMAGE90X60 && d.push({
							url: b.creativemacros.IMAGE90X60,
							width: 90,
							height: 60
						});
						"" !=
						b.creativemacros.IMAGE90X90 && d.push({
							url: b.creativemacros.IMAGE90X90,
							width: 90,
							height: 90
						});
						"" != b.creativemacros.IMAGE140X105 && d.push({
							url: b.creativemacros.IMAGE140X105,
							width: 140,
							height: 105
						});
						"" != b.creativemacros.IMAGE140X140 && d.push({
							url: b.creativemacros.IMAGE140X140,
							width: 140,
							height: 140
						});
						"" != b.creativemacros.IMAGE180X100 && d.push({
							url: b.creativemacros.IMAGE180X100,
							width: 180,
							height: 100
						});
						"" != b.creativemacros.IMAGE180X250 && d.push({
							url: b.creativemacros.IMAGE180X250,
							width: 180,
							height: 250
						});
						e.imgs = d;
						i.cpmStarMultiAdInfo.push(e)
					}
				k.push(g);
				a.adContainer ? p = setInterval(function() {
					if (document.getElementById(a.adContainer)) {
						clearInterval(p);
						g.adContainer = a.adContainer;
						var b = a.cpmstar_callback;
						b(g)
					}
				}, 10) : f(g)
			};
			var c = Math.round(999999 * Math.random()),
				f = document.createElement("script");
			f.type = "text/javascript";
			f.async = !0;
			f.src = "undefined" != typeof a.cpmstar_subpoolid ? "//rigby.gamepedia.com/view.aspx?poolid=" + a.cpmstar_pid + "&subpoolid=" + a.cpmstar_subpoolid + "&multi=" + a.cpmstar_multi + "&json=nc_editorial&callback=this.cpmstar_dynamic_editorials." +
				a.key + ".callback&rnd=" + c : "//rigby.gamepedia.com/view.aspx?poolid=" + a.cpmstar_pid + "&multi=" + a.cpmstar_multi + "&json=nc_editorial&callback=this.cpmstar_dynamic_editorials." + a.key + ".callback&rnd=" + c;
			c = document.getElementsByTagName("script")[0];
			c.parentNode.insertBefore(f, c)
		},
		p;
	if ("undefined" != typeof j)
		for (var q in j) {
			var r = j[q];
			r.key = q;
			s(r)
		}
	return {
		totalEditorials: function() {
			return k.length
		}
	}
};