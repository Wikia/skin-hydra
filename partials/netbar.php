<?php
global $wgUser;
$personalTools = $skin->getPersonalTools();
//Push the userpage stuff to the beginning, always.
if (array_key_exists('userpage', $personalTools)) {
	$_pt = [];
	$_pt['userpage'] = $personalTools['userpage'];
	unset($personalTools['userpage']);
	$personalTools = array_merge($_pt, $personalTools);
}
?>
<div id="netbar">
	<ul class="netbar-left">
		<li><a class="curse" href="http://www.gamepedia.com">Gamepedia</a></li>
		<?php if ($wgUser->isAnon()) { ?>
			<li><a href="http://support.curse.com/"><?= wfMessage('netbar-help')->text() ?></a></li>
		<?php } else { ?>
			<li class="settings">
				<i class="cog"></i>
				<ul>
					<li><a href="http://www.gamepedia.com/giveaways/"><?= wfMessage('netbar-giveaways')->text() ?></a></li>
					<li><a href="http://www.curse.com/premium/"><?= wfMessage('netbar-premium')->text() ?></a></li>
					<li><a href="http://support.curse.com/"><?= wfMessage('netbar-help')->text() ?></a></li>
				</ul>
			</li>
		<?php } ?>
		<?php if ($_SERVER['PHP_ENV'] == 'development') { ?>
			<li><span class="label-development">DEVELOPMENT</span></li>
			<li><span class="label-hostname"><?= htmlspecialchars(gethostname()) ?></span></li>
		<?php } ?>
	</ul>
	<ul class="netbar-right">
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
