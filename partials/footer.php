<?php
global $wgUser, $wgScriptPath;
$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
$showAds = !HydraHooks::isMobileSkin() && HydraHooks::showAds($skin) && $config->get('HydraSkinShowFooterAd') && !empty(HydraHooks::getAdBySlot('footermrec'));
$curseUser = CurseAuthUser::getInstance($wgUser);
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
			<li><a href="http://help.gamepedia.com/How_To_Contact_Gamepedia" class="nl"><?= wfMessage('footer-Newsletter')->text() ?></a></li>
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
				<?php if ($curseUser->isPremium()) { ?>
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
if (!empty(HydraHooks::getAdBySlot('googleanalyticsid'))) {
	$tags = explode("\n", HydraHooks::getAdBySlot('googleanalyticsid'));
	$tags = array_map('trim', $tags);
	foreach ($tags as $index => $tag) {
		if (empty($tag)) {
			unset($tags[$index]);
		}
	}
	if (!empty($tags)) { ?>
<script type="text/javascript">
(function(i, s, o, g, r, a, m) {
	i['GoogleAnalyticsObject'] = r;
	i[r] = i[r] || function() {
		(i[r].q = i[r].q || []).push(arguments)
	}

	, i[r].l = 1 * new Date();
	a = s.createElement(o),
		m = s.getElementsByTagName(o)[0];
	a.async = 1;
	a.src = g;
	m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
<?php
	$creates = '';
	$sends = '';
	foreach ($tags as $index => $tag) {
		$creates .= "ga('create', '{$tag}', 'auto', 'tracker{$index}');\n";
		$sends .= "ga('tracker{$index}.send', 'pageview');\n";
	}
	echo $creates.$sends;
?>
</script>

<?php
	}
}
?>

<?= HydraHooks::getAdBySlot('analytics') ?>
