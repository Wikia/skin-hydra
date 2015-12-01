<?php
global $wgUser, $wgDisplayFooterMREC, $wgScriptPath;
$showAds = HydraHooks::showAds($skin) && $wgDisplayFooterMREC && HydraHooks::getAdBySlot('footermrec') !== false;
?>
<footer id="footer" role="complimentary" <?= $showAds ? '' : 'class="no-ads"' ?>>
	<div class="footer-links">
		<?php if (!$showAds) { ?>
			<div class="top">
		<?php } ?>
		<a href="http://www.gamepedia.com" class="curse">Gamepedia powered by Curse</a>
		<ul class="social">
			<li><a href="https://www.facebook.com/CurseGamepedia" class="fb"><?= wfMessage('footer-Facebook')->text() ?></a></li>
			<li><a href="https://twitter.com/CurseGamepedia" class="tw"><?= wfMessage('footer-Twitter')->text() ?></a></li>
			<li><a href="https://www.youtube.com/CurseGP" class="yt"><?= wfMessage('footer-Youtube')->text() ?></a></li>
			<li><a href="http://www.curse.com/newsletter" class="nl"><?= wfMessage('footer-Newsletter')->text() ?></a></li>
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
				<?php if ($wgUser->curse_premium) { ?>
					<li><a href="http://www.curse.com/premium" class="premium"><?= wfMessage('footer-Premium')->text() ?></a></li>
				<?php } else { ?>
					<li><a href="http://www.curse.com/premium" class="premium"><?= wfMessage('footer-Get_Premium')->text() ?></a></li>
				<?php } ?>
			<?php } ?>
			<li><a href="http://www.curseinc.com/careers" class="careers"><?= wfMessage('footer-Careers')->text() ?></a></li>
			<li><a href="http://support.curse.com" class="help"><?= wfMessage('footer-Help')->text() ?></a></li>
		<?php if ($showAds) { ?>
			</ul>
			<ul class="more">
		<?php } ?>
			<li><a href="http://www.curseinc.com/" class="about"><?= wfMessage('footer-About_Curse')->text() ?></a></li>
			<li><a href="http://www.curseinc.com/audience" class="advertise"><?= wfMessage('footer-Advertise')->text() ?></a></li>
			<li><a href="http://www.curse.com/terms" class="tos"><?= wfMessage('footer-Terms_of_Service')->text() ?></a></li>
			<li><a href="http://www.curse.com/privacy" class="privacy-policy"><?= wfMessage('footer-Privacy_Policy')->text() ?></a></li>
		</ul>
		<span class="copyright">Copyright 2005-<?= date('Y') ?>, Curse Inc.</span>
	</div>
	<?php if ($showAds) { ?>
		<div class="ad-placement ad-main-med-rect-footer">
			<?= HydraHooks::getAdBySlot('footermrec') ?>
		</div>
	<?php } ?>
</footer>

<?php
global $curseGoogleAnalytics;
echo $curseGoogleAnalytics;
?>

<?= HydraHooks::getAdBySlot('analytics') ?>
