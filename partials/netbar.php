<?php
global $wgUser, $nrHydraVersion;

$personalTools = $skin->getPersonalTools();
//Push the userpage stuff to the beginning, always.
if (array_key_exists('userpage', $personalTools)) {
	$_pt = [];
	$_pt['userpage'] = $personalTools['userpage'];
	unset($personalTools['userpage']);
	$personalTools = array_merge($_pt, $personalTools);
}
$showHost = false;
?>
<div id="netbar">
	<ul class="netbar-left">
		<li><a class="curse" href="http://www.gamepedia.com">Gamepedia</a></li>
		<?php if ($wgUser->isAnon()) { ?>
			<li><a href="http://support.gamepedia.com/"><?= wfMessage('netbar-help')->text() ?></a></li>
		<?php } else { ?>
			<li class="settings">
				<i class="cog"></i>
				<ul>
					<li><a href="http://www.gamepedia.com/giveaways/"><?= wfMessage('netbar-giveaways')->text() ?></a></li>
					<li><a href="https://www.gamepedia.com/pro" class="premium"><?= wfMessage('netbar-pro')->text() ?></a></li>
					<li><a href="http://support.gamepedia.com/"><?= wfMessage('netbar-help')->text() ?></a></li>
				</ul>
			</li>
		<?php } ?>
		<?php if (!empty($nrHydraVersion) && $nrHydraVersion != 'stable') {
			$showHost = true;
		?>
			<li><span class="label-development"><?php echo strtoupper($nrHydraVersion) ?></span></li>
		<?php } ?>
		<?php if ($_SERVER['PHP_ENV'] == 'development') {
			$showHost = true;
		?>
			<li><span class="label-development">DEVELOPMENT</span></li>
		<?php } ?>
		<?php if ($showHost) { ?>
			<li><span class="label-hostname"><?= htmlspecialchars(gethostname()) ?></span></li>
		<?php } ?>

		<?php if (ConfigFactory::getDefaultInstance()->makeConfig('hydraskin')->get('IsOfficialWiki') == true || true) { ?>
			<li><span id="OfficialWiki"><img src="/skins/Hydra/images/netbar/official-wiki.svg" /></span></li>
		<?php } ?>
		<?php
		/* $items['new-item'] = $rawHtml;
		 * Item key should be suitable as a CSS class name.
		 * HTML should be wrapped in a <span> or <a> for single elements.
		 * Otherwise <span> or <a> should be the first element in the HTML for drop down lists.
		*/
		$items = [];
		Hooks::run('NetbarLeftEnd', [&$items, $skin->getSkin()->getOutput()]);
		if (is_array($items) && count($items)) {
			foreach ($items as $key => $item) {
				echo "<li class=".htmlentities($key).">".$item."</li>";
			}
		}
		?>
	</ul>
	<ul class="netbar-right">
		<?php
		/* $items['new-item'] = $rawHtml;
		 * Item key should be suitable as a CSS class name.
		 * HTML should be wrapped in a <span> or <a> for single elements.
		 * Otherwise <span> or <a> should be the first element in the HTML for drop down lists.
		*/
		$items = [];
		Hooks::run('NetbarRightBegin', [&$items, $skin->getSkin()->getOutput()]);
		if (is_array($items) && count($items)) {
			foreach ($items as $key => $item) {
				echo "<li class=".htmlentities($key).">".$item."</li>";
			}
		}
		?>
		<?php if (!$wgUser->isAnon() && $personalTools['notifications-alert']) {
			echo $skin->makeListItem('notifications-alert', $personalTools['notifications-alert']);
			unset($personalTools['notifications-alert']);
		} ?>
		<?php if (!$wgUser->isAnon() && $personalTools['notifications-message']) {
			echo $skin->makeListItem('notifications-message', $personalTools['notifications-message']);
			unset($personalTools['notifications-message']);
		} ?>
		<?php if ($wgUser->isAnon()) { ?>
			<li><a href="<?= $personalTools['login']['links'][0]['href'] ?>" id="login-link"><?= wfMessage('netbar-signin')->text() ?></a></li>
			<?php if ($wgUser->isAllowed( 'createaccount' )) { ?>
				<li><a href="<?= $personalTools['createaccount']['links'][0]['href'] ?>" id="register-link"><?= wfMessage('netbar-register')->text() ?></a></li>
			<?php } ?>
		<?php } else { ?>
			<li class="user">
				<?php $attribs = Linker::tooltipAndAccesskeyAttribs($personalTools['userpage']['links'][0]['single-id']); ?>
				<a title="<?= $attribs['title'] ?>" accesskey="<?= $attribs['accesskey'] ?>" href="<?= htmlspecialchars($wgUser->getUserPage()->getLinkURL()) ?>"><img src="//www.gravatar.com/avatar/<?= md5(strtolower(trim($wgUser->getEmail()))) ?>?d=mm&amp;s=19" class="avatar" alt="<?= htmlspecialchars($wgUser->getName(), ENT_QUOTES) ?>" /><?= htmlspecialchars($wgUser->getName()) ?></a>
				<ul>
					<li>
						<ul>
							<?php foreach($personalTools as $key => $item) {
                                if (in_array($key, ['logout', 'notifications'])) {
                                    continue;
                                } elseif ($item['id'] == 'pt-userpage') {
                                    echo "<li class='user'>
                                            <a href='{$item['links'][0]['href']}'>
                                                ".wfMessage('netbar-user-page')->text()."
                                            </a>
                                        </li>";
                                } else {

                                    echo $skin->makeListItem($key, $item);
								}
							} ?>
						</ul>
					</li>
					<?= $skin->makeListItem('logout', $personalTools['logout']) ?>
				</ul>
			</li>
			<?php // $skin->makeListItem('logout', $personalTools['logout'], ['class'=>'mobile-sign-out']) ?>
		<?php } ?>
	</ul>
</div>