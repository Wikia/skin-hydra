<?php
global $wgUser, $wgScriptPath;
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
?>
<footer id="footer" role="complimentary" <?= $showAds ? 'class="show-ads"' : 'class="hide-ads"' ?>>
	<div class="footer-links">
		<?php if (!$showAds) { ?>
		<div class="top">
		<?php } ?>
			<a href="http://www.gamepedia.com" class="curse">Gamepedia powered by Curse</a>
			<ul class="social">
				<li><a href="https://www.facebook.com/CurseGamepedia" class="fb"><?= wfMessage('footer-Facebook')->text() ?></a></li>
				<li><a href="https://twitter.com/CurseGamepedia" class="tw"><?= wfMessage('footer-Twitter')->text() ?></a></li>
				<li><a href="http://youtube.com/CurseEntertainment" class="yt"><?= wfMessage('footer-Youtube')->text() ?></a></li>
				<li><a href="http://help.gamepedia.com/How_To_Contact_Gamepedia" class="nl"><?= wfMessage('footer-Contact_Us')->text() ?></a></li>
			<?php if ($showAds && isset($footerLinks[0])) { ?>
				<li><a href="<?= $footerLinks[0]['url'] ?>" class="advertise"><?= htmlentities($footerLinks[0]['text']) ?></a></li>
			<?php } ?>
			</ul>
		<?php if (!$showAds) { ?>
		</div>
		<ul class="links">
		<?php } else { ?>
		<ul class="you">
		<?php } ?>
			<?php if ($wgUser->isAnon()) { ?>
			<li><a href="<?= $personalUrls['login']['href'] ?>" id="login-link" class="sign-in"><?= wfMessage('footer-Sign_In')->text() ?></a></li>
				<?php if ($wgUser->isAllowed( 'createaccount' )) { ?>
			<li><a href="<?= $personalUrls['createaccount']['href'] ?>" class="register" id="register-link"><?= wfMessage('footer-Register')->text() ?></a></li>
				<?php } ?>
			<?php } else { ?>
			<li><a href="<?= wfExpandUrl($wgScriptPath."/Special:Preferences"); ?>" class="account"><?= wfMessage('footer-My_Account')->text() ?></a></li>
			<li><a href="https://www.gamepedia.com/pro" class="premium"><?= wfMessage('footer-Pro')->text() ?></a></li>
			<?php } ?>
			<li><a href="http://www.curseinc.com/careers" class="careers"><?= wfMessage('footer-Careers')->text() ?></a></li>
			<li><a href="http://support.gamepedia.com/" class="help"><?= wfMessage('footer-Help')->text() ?></a></li>
		<?php if ($showAds && isset($footerLinks[1])) { ?>
			<li><a href="<?= $footerLinks[1]['url'] ?>" class="advertise"><?= htmlentities($footerLinks[1]['text']) ?></a></li>
		</ul>
		<ul class="more">
		<?php } ?>
			<li><a href="http://www.curseinc.com/" class="about"><?= wfMessage('footer-About_Curse')->text() ?></a></li>
			<li><a href="http://www.curseinc.com/audience" class="advertise"><?= wfMessage('footer-Advertise')->text() ?></a></li>
			<li><a href="http://www.curse.com/terms" class="tos"><?= wfMessage('footer-Terms_of_Service')->text() ?></a></li>
			<li><a href="http://www.curse.com/privacy" class="privacy-policy"><?= wfMessage('footer-Privacy_Policy')->text() ?></a></li>
		<?php if ($showAds && isset($footerLinks[2])) { ?>
			<li><a href="<?= $footerLinks[2]['url'] ?>" class="advertise"><?= htmlentities($footerLinks[2]['text']) ?></a></li>
		<?php } ?>
		</ul>
		<?php if ($showAds) { ?>
		<div class="ad-placement ad-main-med-rect-footer">
			<?= HydraHooks::getAdBySlot('footermrec') ?>
		</div>
		<?php } ?>
		<span class="copyright">Copyright 2005-<?= date('Y') ?>, Curse Inc.</span>
	</div>
</footer>

<?= HydraHooks::getAdBySlot('analytics') ?>
