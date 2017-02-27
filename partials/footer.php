<?php
global $wgUser, $wgScriptPath;
$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
$showAds = !HydraHooks::isMobileSkin() && HydraHooks::showAds($skin) && $config->get('HydraSkinShowFooterAd') && !empty(HydraHooks::getAdBySlot('footermrec'));
?>
<footer id="footer" role="complimentary" <?= $showAds ? 'class="show-ads"' : 'class="hide-ads"' ?>>
	<div class="footer-links">
		<div style="width: 100%; text-align: center; background-color: #121212; color: #aeaeae;">As you may have heard, on February 23, 2017, Cloudflare reported a security incident. <a style="text-decoration: underline;" href="https://gamepedia.zendesk.com/hc/en-us/articles/115003581628-Cloudflare-Security-Incident-">Click here for more information.</a></div>
		<?php if (!$showAds) { ?>
		<div class="top">
		<?php } ?>
			<a href="http://www.gamepedia.com" class="curse">Gamepedia powered by Curse</a>
			<ul class="social">
				<li><a href="https://www.facebook.com/CurseGamepedia" class="fb"><?= wfMessage('footer-Facebook')->text() ?></a></li>
				<li><a href="https://twitter.com/CurseGamepedia" class="tw"><?= wfMessage('footer-Twitter')->text() ?></a></li>
				<li><a href="http://youtube.com/CurseEntertainment" class="yt"><?= wfMessage('footer-Youtube')->text() ?></a></li>
				<li><a href="http://help.gamepedia.com/How_To_Contact_Gamepedia" class="nl"><?= wfMessage('footer-Contact_Us')->text() ?></a></li>
			<?php if ($showAds) { ?>
				<li><a href="https://ghostreconwildlands.gamepedia.com" class="advertise">Ghost Recon</a></li>
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
		<?php if ($showAds) { ?>
			<li><a href="http://www.hearthpwn.com/guides/1480-how-to-get-cheaper-hearthstone-packs-buy-amazon" class="about">HS Packs</a></li>
		</ul>
		<ul class="more">
		<?php } ?>
			<li><a href="http://www.curseinc.com/" class="about"><?= wfMessage('footer-About_Curse')->text() ?></a></li>
			<li><a href="http://www.curseinc.com/audience" class="advertise"><?= wfMessage('footer-Advertise')->text() ?></a></li>
			<li><a href="http://www.curse.com/terms" class="tos"><?= wfMessage('footer-Terms_of_Service')->text() ?></a></li>
			<li><a href="http://www.curse.com/privacy" class="privacy-policy"><?= wfMessage('footer-Privacy_Policy')->text() ?></a></li>
		<?php if ($showAds) { ?>
			<li><a href="https://forhonor.gamepedia.com" class="tos">For Honor</a></li>
		<?php } ?>
		</ul>
		<span class="copyright">Copyright 2005-<?= date('Y') ?>, Curse Inc.</span>
	</div>
	<?php if ($showAds) { ?>
		<div class="ad-placement ad-main-med-rect-footer">
			<?= HydraHooks::getAdBySlot('footermrec') ?>
		</div>
	<?php } ?>
</footer>

<?= HydraHooks::getAdBySlot('analytics') ?>
