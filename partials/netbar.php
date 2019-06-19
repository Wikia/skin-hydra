<?php
global $wgUser, $nrHydraVersion;

$personalTools = $skin->getPersonalTools();
// Push the userpage stuff to the beginning, always.
if (array_key_exists('userpage', $personalTools)) {
	$_pt = [];
	$_pt['userpage'] = $personalTools['userpage'];
	unset($personalTools['userpage']);
	$personalTools = array_merge($_pt, $personalTools);
}
$showHost = false;
?>

<div id="netbar">
	<div class="netbar-flex">
		<div class="netbar-box left logo"><a href="http://www.gamepedia.com">Gamepedia</a></div>
		<?php if ($wgUser->isAnon()) { ?>
		<div class="netbar-box left"><a href="http://support.gamepedia.com/"><?php echo wfMessage('netbar-help')->text() ?></a></div>
		<?php } else { ?>
		<div class="netbar-box left links has-drop">
			<span class="cog"></span>
			<ul class="dropdown">
				<li><a href="https://www.gamepedia.com/pro" class="premium"><?php echo wfMessage('netbar-pro')->text() ?></a></li>
				<li><a href="http://support.gamepedia.com/"><?php echo wfMessage('netbar-help')->text() ?></a></li>
			</ul>
		</div>
		<?php } ?>
		<?php if (!empty($nrHydraVersion) && $nrHydraVersion != 'stable') {
			$showHost = true;
			?>
		<div class="netbar-box left"><span class="label-development"><?php echo strtoupper($nrHydraVersion) ?></span></div>
		<?php } ?>
		<?php if (strtolower($_SERVER['PHP_ENV']) != 'production') {
			$showHost = true;
			?>
		<div class="netbar-box left"><span class="label-development"><?php echo strtoupper($_SERVER['PHP_ENV']) ?></span></div>
		<?php } ?>
		<?php if ($showHost) { ?>
		<div class="netbar-box left"><span class="label-hostname"><?php echo htmlspecialchars(gethostname()) ?></span></div>
		<?php } ?>
		<?php if (ConfigFactory::getDefaultInstance()->makeConfig('hydraskin')->get('IsOfficialWiki') == true) { ?>
		<div class="netbar-box left officialwiki"><a href="/index.php?title=Special:AllSites&amp;filter=official"><img src="/skins/Hydra/images/netbar/official-wiki.svg" width="90"></a></div>
		<?php } ?>
		<?php
		/* $items['new-item'] = $rawHtml;
		 * Item key should be suitable as a CSS class name.
		 * HTML should be wrapped in a <span> or <a> for single elements.
		 * Otherwise <span> or <a> should be the first element in the HTML for drop down lists.
		*/
		$items = [];
		Hooks::run('NetbarLeftEnd', [&$items]);
		if (is_array($items) && count($items)) {
			foreach ($items as $key => $item) {
				echo "<div class='netbar-box left " . htmlentities($key) . "'>" . $item . "</div>";
			}
		}
		?>
		<div class="netbar-spacer">&nbsp;</div>
		<?php
		/* $items['new-item'] = $rawHtml;
		 * Item key should be suitable as a CSS class name.
		 * HTML should be wrapped in a <span> or <a> for single elements.
		 * Otherwise <span> or <a> should be the first element in the HTML for drop down lists.
		*/
		$items = [];
		Hooks::run('NetbarRightBegin', [&$items]);
		if (is_array($items) && count($items)) {
			foreach ($items as $key => $item) {
				echo "<div class='netbar-box left " . htmlentities($key) . "'>" . $item . "</div>";
			}
		}
		?>
		<?php if ($wgUser->isAnon()) { ?>
		<div class="netbar-box right"><a href="<?php echo $personalTools['login']['links'][0]['href'] ?>" id="login-link" class="aqua-link"><?php echo wfMessage('netbar-signin')->text() ?></a></div>
			<?php if ($wgUser->isAllowed('createaccount')) { ?>
		<div class="netbar-box right"><a href="<?php echo $personalTools['createaccount']['links'][0]['href'] ?>" id="register-link" class="aqua-link"><?php echo wfMessage('netbar-register')->text() ?></a></div>
			<?php } ?>
		<?php } else { ?>
			<?php if (!$wgUser->isAnon() && isset($personalTools['notifications-alert'])) {
				?>
		<div class="netbar-box right echo">
				<?php
				echo $skin->makeListItem('notifications-alert', $personalTools['notifications-alert']);
				unset($personalTools['notifications-alert']);
				?>
		</div>
				<?php
			} ?>
			<?php if (!$wgUser->isAnon() && isset($personalTools['notifications-notice'])) {
				?>
		<div class="netbar-box right echo">
				<?php
				echo $skin->makeListItem('notifications-notice', $personalTools['notifications-notice']);
				unset($personalTools['notifications-notice']);
				?>
		</div>
				<?php
			} ?>
		<div class="netbar-box right user has-drop">
			<?php $attribs = Linker::tooltipAndAccesskeyAttribs($personalTools['userpage']['links'][0]['single-id']); ?>
			<a title="<?php echo $attribs['title'] ?>" accesskey="<?php echo $attribs['accesskey'] ?>" href="<?php echo htmlspecialchars($wgUser->getUserPage()->getLinkURL()) ?>"><img src="//www.gravatar.com/avatar/<?php echo md5(strtolower(trim($wgUser->getEmail()))) ?>?d=mm&amp;s=20" class="avatar" alt="<?php echo htmlspecialchars($wgUser->getName(), ENT_QUOTES) ?>" /><span><?php echo htmlspecialchars($wgUser->getName()) ?></span></a>
			<ul class="dropdown">
				<?php foreach ($personalTools as $key => $item) {
					if (in_array($key, ['logout', 'notifications'])) {
						continue;
					} elseif ($item['id'] == 'pt-userpage') {
						echo "<li class='user'>
								<a href='{$item['links'][0]['href']}'>
									" . wfMessage('netbar-user-page')->text() . "
								</a>
							</li>";
					} else {

						echo $skin->makeListItem($key, $item);
					}
				} ?>
				<?php echo $skin->makeListItem('logout', $personalTools['logout']) ?>
			</ul>
		</div>
		<?php } ?>
	</div>
</div>