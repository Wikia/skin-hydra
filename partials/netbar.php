<?php
/**
 * Curse Inc.
 * Hydra Skin
 * Footer Partial
 *
 * @package   HydraSkin
 * @author    Curse
 * @copyright (c) 2012 Curse Inc.
 * @license   GPL-2.0-or-later
 * @link      https://gitlab.com/hydrawiki/
**/

global $wgUser, $nrHydraVersion, $wgStylePath;

$personalTools = $skin->getPersonalTools();

// Ensure we have a log out link, regardless of platform
if (!isset($personalTools['logout'])) {
	// Add a log out link that works with Fandom UCP
	$personalTools['logout'] = [
		'text' => wfMessage( 'pt-userlogout' )->text(),
		'href' => SkinTemplate::makeSpecialURL('UserLogout')
	];
}

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
		<div class="netbar-box left logo"><a href="https://www.gamepedia.com">Gamepedia</a></div>
		<?php if ($wgUser->isAnon()) { ?>
		<div class="netbar-box left"><a href="https://support.gamepedia.com/"><?php echo wfMessage('netbar-help')->escaped() ?></a></div>
		<?php } else { ?>
		<div class="netbar-box left links has-drop">
			<span class="cog"></span>
			<ul class="dropdown">
				<li><a href="https://www.gamepedia.com/pro" class="premium"><?php echo wfMessage('netbar-pro')->escaped() ?></a></li>
				<li><a href="https://support.gamepedia.com/"><?php echo wfMessage('netbar-help')->escaped() ?></a></li>
			</ul>
		</div>
		<?php } ?>
		<?php if (!empty($nrHydraVersion) && $nrHydraVersion != 'stable') {
			$showHost = true;
			?>
		<div class="netbar-box left"><span class="label-development"><?php echo strtoupper($nrHydraVersion) ?></span></div>
		<?php } ?>
		<?php if (strtolower($_SERVER['WIKIA_ENVIRONMENT']) != 'prod') {
			$showHost = true;
			?>
		<div class="netbar-box left"><span class="label-development"><?php echo strtoupper($_SERVER['WIKIA_ENVIRONMENT']) ?></span></div>
		<?php } ?>
		<?php if ($showHost) { ?>
		<div class="netbar-box left"><span class="label-hostname"><?php echo htmlspecialchars(gethostname()) ?></span></div>
		<?php } ?>
		<?php if (ConfigFactory::getDefaultInstance()->makeConfig('hydraskin')->get('OfficialWiki') == true) { ?>
		<div class="netbar-box left officialwiki"><a href="/index.php?title=Special:AllSites&amp;filter=official"><img src="<?php echo $wgStylePath; ?>/Hydra/images/netbar/official-wiki.svg" width="90"></a></div>
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
		<div class="netbar-box right"><a href="<?php echo $personalTools['login']['links'][0]['href'] ?>" id="login-link" class="aqua-link"><?php echo wfMessage('netbar-signin')->escaped() ?></a></div>
			<?php if ($wgUser->isAllowed('createaccount')) { 
				
				// Ensure we have a create account link, regardless of platform
				if (!isset($personalTools['createaccount'])) {
					// Add a create account link that works with Fandom UCP
					$personalTools['createaccount'] = [
						'links' => [['href' => SkinTemplate::makeSpecialURL('CreateAccount'), 'text' => wfMessage( 'pt-createaccount' )->text()]],
						'id' => 'pt-createaccount'
					];
				}
				
			?>
		<div class="netbar-box right"><a href="<?php echo $personalTools['createaccount']['links'][0]['href'] ?>" id="register-link" class="aqua-link"><?php echo wfMessage('netbar-register')->escaped() ?></a></div>
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
									" . wfMessage('netbar-user-page')->escaped() . "
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
