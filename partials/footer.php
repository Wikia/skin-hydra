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
<footer id="curse-footer" role="complimentary" <?php echo $showAds ? 'class="show-ads"' : 'class="hide-ads"' ?>>
	<div class="footer-flex">
		<div class="footer-box">
			<a href="http://www.gamepedia.com" class="curse">Gamepedia powered by Curse</a>
		</div>
		<div class="footer-box">
			<ul class="social">
				<li><a href="https://www.facebook.com/CurseGamepedia" class="fb"><?php echo wfMessage('footer-Facebook')->text() ?></a></li>
				<li><a href="https://twitter.com/CurseGamepedia" class="tw"><?php echo wfMessage('footer-Twitter')->text() ?></a></li>
				<li><a href="http://youtube.com/CurseEntertainment" class="yt"><?php echo wfMessage('footer-Youtube')->text() ?></a></li>
				<li><a href="http://help.gamepedia.com/How_To_Contact_Gamepedia" class="nl"><?php echo wfMessage('footer-Contact_Us')->text() ?></a></li>
			<?php if ($showAds && isset($footerLinks[0])) { ?>
				<li><a href="<?php echo $footerLinks[0]['url'] ?>" class="advertise"><?php echo htmlentities($footerLinks[0]['text']) ?></a></li>
			<?php } ?>
			</ul>
		</div>
		<div class="footer-box">
			<ul class="you">
			<?php if ($wgUser->isAnon()) { ?>
				<li><a href="<?php echo $personalUrls['login']['href'] ?>" id="login-link" class="sign-in"><?php echo wfMessage('footer-Sign_In')->text() ?></a></li>
				<?php if ($wgUser->isAllowed('createaccount')) { ?>
				<li><a href="<?php echo $personalUrls['createaccount']['href'] ?>" class="register" id="register-link"><?php echo wfMessage('footer-Register')->text() ?></a></li>
				<?php } ?>
			<?php } else { ?>
				<li><a href="<?php echo wfExpandUrl($wgScriptPath . "/Special:Preferences"); ?>" class="account"><?php echo wfMessage('footer-My_Account')->text() ?></a></li>
				<li><a href="https://www.gamepedia.com/pro" class="premium"><?php echo wfMessage('footer-Pro')->text() ?></a></li>
			<?php } ?>
				<li><a href="https://www.curse.com/careers" class="careers"><?php echo wfMessage('footer-Careers')->text() ?></a></li>
				<li><a href="http://support.gamepedia.com/" class="help"><?php echo wfMessage('footer-Help')->text() ?></a></li>
			<?php if ($showAds && isset($footerLinks[1])) { ?>
				<li><a href="<?php echo $footerLinks[1]['url'] ?>" class="advertise"><?php echo htmlentities($footerLinks[1]['text']) ?></a></li>
			<?php } ?>
			</ul>
		<?php if ($showAds) { ?>
		</div>
		<div class="footer-box">
		<?php } ?>
			<ul class="more">
				<li><a href="https://www.curse.com/" class="about">About Curse</a></li>
				<li><a href="https://www.curse.com/advertising" class="advertise">Advertise</a></li>
				<li><a href="https://www.curse.com/terms-of-service" class="tos">Terms of Service</a></li>
				<li><a href="https://www.curse.com/privacy-policy" class="privacy-policy">Privacy Policy</a></li>
			<?php if ($showAds && isset($footerLinks[2])) { ?>
				<li><a href="<?php echo $footerLinks[2]['url'] ?>" class="advertise"><?php echo htmlentities($footerLinks[2]['text']) ?></a></li>
			<?php } ?>
			</ul>
		</div>
		<?php if ($showAds) { ?>
		<div class="footer-box">
			<div class="ad-placement ad-main-med-rect-footer">
				<?php echo HydraHooks::getAdBySlot('footermrec'); ?>
			</div>
		</div>
		<?php } ?>
	</div>
	<span class="copyright">Copyright <?php echo date('Y') ?> Wikia, Inc. | Powered by Fandom Games</span>
</footer>

<?php echo HydraHooks::getAdBySlot('analytics');
