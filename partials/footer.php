<?php
global $wgUser, $wgScriptPath, $wgSitename;
$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
$showAds = !HydraHooks::isMobileSkin() && HydraHooks::showAds($skin) && $config->get('HydraSkinShowFooterAd') && !empty(HydraHooks::getAdBySlot('footermrec'));

if ($showAds && !empty(HydraHooks::getAdBySlot('footerlinks'))) {
	$footerLinks = str_replace(['[', ']'], '', trim(HydraHooks::getAdBySlot('footerlinks')));
	$footerLinks = explode("\n", $footerLinks);
	foreach ($footerLinks as $key => $value) {
		list($url, $text) = explode(' ', $value, 2);
		if (!filter_var($url, FILTER_VALIDATE_URL) || empty($text)) {
			unset($footerLinks[$key]);
			continue;
		}
		$footerLinks[$key] = ['url' => $url, 'text' => $text];
	}
}

if(HydraHooks::isMobileSkin()) {
	$switchViewMessage = wfMessage('footer-view-desktop')->escaped();
	$switchViewURL = $skin->getTitle()->getFullURL(array('mobileaction' => 'toggle_view_desktop'));
	$switchViewURL = htmlspecialchars(MobileContext::singleton()->getMobileUrl( $switchViewURL ));
}
else {
	$switchViewMessage = wfMessage('footer-view-mobile')->escaped();
	$switchViewURL = $skin->getTitle()->getFullURL(array('mobileaction' => 'toggle_view_mobile'));
	$switchViewURL = htmlspecialchars(MobileContext::singleton()->getMobileUrl( $switchViewURL ));
}

?>

<link href="https://fonts.googleapis.com/css?family=Rubik:400,700&amp;display=swap&amp;subset=cyrillic,latin-ext" rel="stylesheet">
<div id="footer-and-prefooter">
	<div id="gamepedia-footer">
		<div class="footer-wrapper-gp">
			<div class="footer-box footer-logo">
				<a href="http://www.gamepedia.com"><img src="/skins/Hydra/images/footer/premium-logo-light.svg" class="footer-gp-logo"/></a>
			</div>
			<div class="footer-box footer-social">
				<ul class="social">
					<li><a href="https://www.facebook.com/CurseGamepedia" title="<?php echo wfMessage('footer-Facebook')->escaped() ?>"><svg width="10" height="21" xmlns="http://www.w3.org/2000/svg"><path d="M9.364531 3.5096969H7.651507c-1.370419 0-1.598822.7261441-1.598822 1.6943364v2.2994565h3.311846l-.342605 3.6307209H6.281088v9.3188503H2.85504v-9.3188503H0V7.5034898h2.85504V4.8409612C2.85504 1.8153604 4.568064 0 7.080499 0c1.142016 0 2.16983.121024 2.512435.121024v3.3886729h-.228403z" fill-rule="evenodd"/></svg></a></li>
					<li><a href="https://twitter.com/CurseGamepedia" title="<?php echo wfMessage('footer-Twitter')->escaped() ?>"><svg width="23" height="18" xmlns="http://www.w3.org/2000/svg"><path d="M19.822727 4.2972696v.5729693c0 5.8729351-4.518416 12.6053241-12.826471 12.6053241-2.623596 0-4.955681-.7162116-6.996256-2.0053925.437266 0 .728777.1432423 1.166043.1432423 2.040575 0 4.081149-.7162116 5.684458-1.8621501-1.894819 0-3.643883-1.2891809-4.226905-3.151331.291511 0 .583022.1432423.874532.1432423.437266 0 .437266 0 1.020288-.1432423-2.186331-.429727-4.08115-2.2918772-4.08115-4.440512 0 .429727 1.603309.429727 2.332086.5729693C1.603309 5.8729351.874532 4.5837542.874532 3.0080887c0-.8594539.291511-1.5756655.728777-2.2918771 2.18633 2.7216041 5.684458 4.4405119 9.328342 4.7269965-.145756-.4297269-.145756-.7162116-.145756-1.0026962C10.785895 2.0053925 12.82647 0 15.304311 0c1.311798 0 2.477841.429727 3.352373 1.4324232 1.020287-.2864846 1.894819-.5729693 2.769351-1.1459386-.437266 1.1459386-1.166042 1.8621502-1.894819 2.4351195.874532-.1432423 1.894819-.429727 2.623596-.7162116-.728777.8594539-1.457553 1.7189078-2.332085 2.2918771z" fill-rule="evenodd"/></svg></a></li>
					<li><a href="http://youtube.com/CurseEntertainment" title="<?php echo wfMessage('footer-Youtube')->escaped() ?>"><svg width="24" height="17" xmlns="http://www.w3.org/2000/svg"><path d="M23.8 3.6s-.2-1.7-1-2.4c-.9-1-1.9-1-2.4-1C17 0 12 0 12 0S7 0 3.6.2c-.5.1-1.5.1-2.4 1-.7.7-1 2.4-1 2.4S0 5.5 0 7.5v1.8c0 1.9.2 3.9.2 3.9s.2 1.7 1 2.4c.9 1 2.1.9 2.6 1 1.9.2 8.2.2 8.2.2s5 0 8.4-.3c.5-.1 1.5-.1 2.4-1 .7-.7 1-2.4 1-2.4s.2-1.9.2-3.9V7.4c0-1.9-.2-3.8-.2-3.8zM9.5 11.5V4.8L16 8.2l-6.5 3.3z" fill-rule="evenodd"/></svg></a></li>
				</ul>
			</div>
			<div class="footer-box footer-links mobile-split">
				<ul>
					<li><a href="http://support.gamepedia.com"><?php echo wfMessage('footer-support')->escaped() ?></a></li>
					<li><a href="https://help.gamepedia.com/How_to_contact_Gamepedia"><?php echo wfMessage('footer-Contact_Us_Short')->escaped() ?></a></li>
					<li><a href="https://www.gamepedia.com/pro"><?php echo wfMessage('footer-Pro')->escaped() ?></a></li>
				</ul>
			</div>
		</div>
	</div>

	<footer id="curse-footer" role="complimentary" <?php echo $showAds ? 'class="show-ads"' : 'class="hide-ads"' ?>>
		<div class="footer-wrapper">
			<?php if ($showAds) { ?>
			<div class="footer-box footer-ad">
				<div class="ad-placement ad-main-med-rect-footer">
					<?php echo HydraHooks::getAdBySlot('footermrec'); ?>
				</div>
			</div>
			<?php } ?>
			<div class="footer-box footer-logo">
				<a href="https://www.fandom.com" target="_blank"><img src="/skins/Hydra/images/footer/fandom-logo.svg" /></a>
			</div>
			<div class="footer-box footer-properties">
				<h2><?php echo wfMessage('footer-headers-explore')->escaped() ?></h2>
				<ul class="properties mobile-split">
					<li><a href="https://www.fandom.com"><?php echo wfMessage('footer-Fandom')->escaped() ?></a></li>
					<li><a href="https://www.gamepedia.com"><?php echo wfMessage('footer-Gamepedia')->escaped() ?></a></li>
					<li><a href="http://www.dndbeyond.com"><?php echo wfMessage('footer-DDB')->escaped() ?></a></li>
					<li><a href="http://www.muthead.com"><?php echo wfMessage('footer-Muthead')->escaped() ?></a></li>
					<li><a href="http://www.futhead.com"><?php echo wfMessage('footer-Futhead')->escaped() ?></a></li>
				<?php if ($showAds && isset($footerLinks[0])) { ?>
					<li><a href="<?php echo $footerLinks[0]['url'] ?>" class="advertise"><?php echo htmlentities($footerLinks[0]['text']) ?></a></li>
				<?php } ?>
				</ul>
			</div>
			<div class="footer-box footer-social">
				<h2><?php echo wfMessage('footer-headers-follow')->escaped() ?></h2>
				<ul class="social">
					<li><a href="https://www.facebook.com/getfandom" title="<?php echo wfMessage('footer-Facebook')->escaped() ?>"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="11" height="24" viewBox="0 0 11 24"><path d="M10.7 4L8.8 4C7.2 4 6.9 4.9 6.9 6L6.9 8.6 10.7 8.6 10.3 12.8 7.2 12.8 7.2 23.5 3.3 23.5 3.3 12.8 0 12.8 0 8.6 3.3 8.6 3.3 5.6C3.3 2.1 5.2 0 8.1 0 9.4 0 10.6 0.1 11 0.1L11 4 10.7 4Z"/></svg></a></li>
					<li><a href="https://twitter.com/getfandom" title="<?php echo wfMessage('footer-Twitter')->escaped() ?>"><svg width="24" height="19" xmlns="http://www.w3.org/2000/svg"><path d="M20.957 4.543v.606c0 6.209-4.777 13.327-13.56 13.327-2.774 0-5.24-.758-7.397-2.12.462 0 .77.15 1.233.15 2.157 0 4.314-.756 6.01-1.968-2.004 0-3.853-1.363-4.47-3.332.309 0 .617.152.925.152.463 0 .463 0 1.079-.152C2.466 10.752.462 8.783.462 6.512c0 .454 1.695.454 2.466.606C1.695 6.209.925 4.846.925 3.18c0-.908.308-1.666.77-2.423 2.311 2.878 6.01 4.695 9.862 4.998-.154-.455-.154-.758-.154-1.06C11.403 2.12 13.56 0 16.18 0c1.387 0 2.62.454 3.544 1.514 1.079-.302 2.004-.605 2.928-1.211-.462 1.211-1.233 1.969-2.003 2.574a14.785 14.785 0 0 0 2.774-.757c-.77.909-1.541 1.817-2.466 2.423z" fill-rule="evenodd"/></svg></a></li>
					<li><a href="https://www.youtube.com/channel/UC988qTQImTjO7lUdPfYabgQ" title="<?php echo wfMessage('footer-Youtube')->escaped() ?>"><svg width="24" height="17" xmlns="http://www.w3.org/2000/svg"><path d="M23.8 3.6s-.2-1.7-1-2.4c-.9-1-1.9-1-2.4-1C17 0 12 0 12 0S7 0 3.6.2c-.5.1-1.5.1-2.4 1-.7.7-1 2.4-1 2.4S0 5.5 0 7.5v1.8c0 1.9.2 3.9.2 3.9s.2 1.7 1 2.4c.9 1 2.1.9 2.6 1 1.9.2 8.2.2 8.2.2s5 0 8.4-.3c.5-.1 1.5-.1 2.4-1 .7-.7 1-2.4 1-2.4s.2-1.9.2-3.9V7.4c0-1.9-.2-3.8-.2-3.8zM9.5 11.5V4.8L16 8.2l-6.5 3.3z" fill-rule="evenodd" /></svg></a></li>
					<li><a href="https://www.instagram.com/getfandom/" title="<?php echo wfMessage('footer-Instagram')->escaped() ?>"><svg width="20" height="20" xmlns="http://www.w3.org/2000/svg"><path d="M17.510373 0H2.406639C1.078838 0 0 1.0788382 0 2.3236515v15.1867219c0 1.3278009 1.078838 2.406639 2.406639 2.406639h15.186722C18.921162 19.9170124 20 18.8381743 20 17.593361V2.3236515C19.917012 1.0788382 18.838174 0 17.510373 0zm-2.572614 2.4896266h1.659751c.497926 0 .829876.3319502.829876.8298755v1.659751c0 .4979253-.33195.8298755-.829876.8298755h-1.659751c-.497925 0-.829875-.3319502-.829875-.8298755v-1.659751c0-.4979253.33195-.8298755.829875-.8298755zM9.958506 6.1410788c2.074689 0 3.817428 1.7427386 3.817428 3.8174274s-1.742739 3.8174274-3.817428 3.8174274c-2.074689 0-3.817427-1.7427386-3.817427-3.8174274s1.742738-3.8174274 3.817427-3.8174274zm6.639004 11.2863071H3.319502c-.497925 0-.829875-.3319502-.829875-.8298755V8.2987552h1.659751c-.248963.9128631-.331951 1.9917012-.082988 2.9875519.497925 2.3236514 2.406639 4.1493775 4.73029 4.5643153 3.900415.746888 7.302905-2.1576763 7.302905-5.8921162 0-.5809128-.165975-1.1618257-.248963-1.659751h1.659751v8.2987552c-.082987.4979253-.414937.8298755-.912863.8298755z" fill-rule="evenodd" /></svg></a></li>
					<li><a href="https://www.linkedin.com/company/fandomwikia/" title="<?php echo wfMessage('footer-Linkedin')->escaped() ?>"><svg width="19" height="19" xmlns="http://www.w3.org/2000/svg"><path d="M3.859375 19h-3.5625V5.9375h3.5625V19zM2.078125 4.43175C.931 4.43175 0 3.493625 0 2.337S.931.24225 2.078125.24225 4.15625 1.180375 4.15625 2.337s-.929812 2.09475-2.078125 2.09475zM18.109375 19h-3.5625v-6.65475c0-3.9995-4.75-3.6966875-4.75 0V19h-3.5625V5.9375h3.5625v2.0959375c1.65775-3.070875 8.3125-3.2976875 8.3125 2.94025V19z" fill-rule="nonzero" /></svg></a></li>
				</ul>
			</div>
			<div class="footer-box footer-overview">
				<h2><?php echo wfMessage('footer-headers-overview')->escaped() ?></h2>
				<ul class="mobile-split">
					<li><a href="https://www.fandom.com/about"><?php echo wfMessage('footer-about')->escaped() ?></a></li>
					<li><a href="https://www.fandom.com/careers"><?php echo wfMessage('footer-careers')->escaped() ?></a></li>
					<li><a href="https://www.fandom.com/press"><?php echo wfMessage('footer-press')->escaped() ?></a></li>
					<li><a href="https://www.fandom.com/about#contact"><?php echo wfMessage('footer-Contact_Us')->escaped() ?></a></li>
					<li><a href="https://www.fandom.com/curse-terms-of-service"><?php echo wfMessage('footer-terms')->escaped() ?></a></li>
					<li><a href="https://www.fandom.com/curse-privacy-policy"><?php echo wfMessage('footer-privacy')->escaped() ?></a></li>
				</ul>
			</div>
			<div class="footer-box footer-community">
				<h2><?php echo wfMessage('footer-headers-community')->escaped() ?></h2>
				<ul class="mobile-split">
					<li><a href="https://community.fandom.com/wiki/Community_Central"><?php echo wfMessage('footer-community')->escaped() ?></a></li>
					<li><a href="https://fandom.zendesk.com/hc/en-us"><?php echo wfMessage('footer-support')->escaped() ?></a></li>
					<li><a href="https://community.fandom.com/wiki/Help:Contents"><?php echo wfMessage('footer-help')->escaped() ?></a></li>
				</ul>
			</div>
			<div class="footer-box footer-advertise">
				<h2><?php echo wfMessage('footer-headers-advertise')->escaped() ?></h2>
				<ul class="mobile-split">
					<li><a href="https://www.fandom.com/mediakit"><?php echo wfMessage('footer-media-kit')->escaped() ?></a></li>
					<li><a href="https://www.fandom.com/mediakit#contact"><?php echo wfMessage('footer-Contact_Us')->escaped() ?></a></li>
				</ul>
			</div>
		</div>
		<div class="footer-post">
			<? echo $wgSitename; ?> is a Fandom Gaming Community.
			<hr />
			<span class="footer-post-mobile"><a href="<? echo $switchViewURL; ?>"><? echo $switchViewMessage; ?></a></span>
		</div>
	</footer>
</div>

<?php echo HydraHooks::getAdBySlot('analytics');
