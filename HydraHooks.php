<?php
/**
 * Curse Inc.
 * Hydra Skin
 * HydraHooks
 *
 * @author		Telshin
 * @copyright	(c) 2012 Curse Inc.
 * @license		All Rights Reserved
 * @package		Hydra Skin
 * @link		http://www.curse.com/
 *
**/

if (!defined('MEDIAWIKI')) {
	echo("This is an extension to the MediaWiki software and is not a valid entry point.\n");
	die(-1);
}

class HydraHooks {
	/**
	 * Currently in mobile view.
	 *
	 * @var boolean
	 */
	private static $isMobile = null;

	/**
	 * Add Hydra CSS modules to page.
	 *
	 * @access	public
	 * @param	object	SkinTemplate Object
	 * @param	array	Array of Styles to Modify
	 * @return	boolean True
	 */
	static public function onSkinVectorStyleModules($skin, &$styles) {
		$styles[] = 'skins.z.hydra.'.($skin instanceof SkinHydraDark ? 'dark' : 'light');
		$styles[] = 'skins.hydra.netbar';
		$styles[] = 'skins.hydra.footer';
		$styles[] = 'skins.hydra.advertisements';

		return true;
	}

	/**
	 * Add Hydra CSS modules to mobile page.
	 *
	 * @access	public
	 * @param	object	SkinTemplate Object
	 * @param	array	Array of modules to Modify
	 * @return	boolean True
	 */
	static public function onSkinMinervaDefaultModules($skin, &$modules) {
		//$modules[] = 'skins.hydra.netbar';
		$modules[] = 'skins.hydra.advertisements';
		$modules[] = 'skins.hydra.footer';

		return true;
	}

	/**
	 * Hook right during Skin::initPage().
	 *
	 * @access	public
	 * @param	array	Title Objects
	 * @param	object	Skin
	 * @return	boolean True
	 */
	static public function onSkinPreloadExistence(array &$titles, Skin $skin) {
		if (class_exists('MobileContext')) {
			$mobileContext = MobileContext::singleton();
			if ($mobileContext->shouldDisplayMobileView()) {
				return true;
			}
		}

		return true;
	}

	/**
	 * Modifications to title, and copyright for Curse Disclaimer
	 * Wiki Managers must change Pagetitle-view-mainpage templates to utilize the extension.
	 *
	 * @access	public
	 * @param	object	SkinTemplate Object
	 * @param	object	Initialized SkinTemplate Object
	 * @return	boolean True
	 */
	static public function onSkinTemplateOutputPageBeforeExec(&$te, &$template) {
		global $wgUser;

		if (!isset($template->data)) {
			return true;
		}

		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
		$showAds = self::showAds($template->getSkin());

		if (isset($template->data['headelement'])) {
			//Custom Title Replacement
			$template->set(
						'headelement',
						str_replace('<title>'.htmlspecialchars(wfMessage('pagetitle', wfMessage('mainpage')->escaped())->escaped()).'</title>', '<title>'.htmlspecialchars(wfMessage('Pagetitle-view-mainpage')->escaped()).'</title>', $template->data['headelement'])
			);

			//Main Advertisement Javascript
			$jstop = (self::isMobileSkin() ? 'mobile' : '').'jstop';
			if (!empty(self::getAdBySlot($jstop)) && $template->getSkin()->getRequest()->getVal('action') != 'edit' && $template->getSkin()->getTitle()->getNamespace() != NS_SPECIAL) {
				$template->set('headelement', $template->data['headelement'].self::getAdBySlot($jstop));
			}

			//Netbar on desktop only.
			if (!self::isMobileSkin()) {
				$netbar = self::getPartial('netbar', ['skin' => $template]);
				$template->set('headelement', $template->data['headelement'].$netbar);
			}
		}

		if (isset($template->data['bottomscripts'])) {
			global $wgWikiCategory;

			$footerLinks = $template->data['footerlinks'];
			//Add Footer to desktop and mobile.
			$footer = self::getPartial(
				'footer',
				[
					'skin'			=> $template->getSkin(),
					'personalUrls'	=> $template->data['personal_urls']
				]
			);
			if (self::isMobileSkin()) {
				$template->set('privacy', null);

				if ($showAds && $config->get('HydraSkinShowFooterAd') && !empty(self::getAdBySlot('footermrec'))) {
					$template->set('footermrec', "<div id='footermrec'>".self::getAdBySlot('footermrec')."</div>");
					$footerLinks = array_merge(['ad' => ['footermrec']], $footerLinks);
				}

				if (isset($footerLinks['places'])) {
					foreach ($footerLinks['places'] as $key => $value) {
						if ($value == 'privacy') {
							unset($footerLinks['places'][$key]);
							break;
						}
					}
				}
				$template->set('hydrafooter', $footer);
				$footerLinks['hydra'][] = 'hydrafooter';
				$template->set('footerlinks', $footerLinks);
			} else {
				$_bottomExtra .= $footer;
			}

			//"Javascript" Bottom Advertisement Stuff
			if ($showAds && !empty(self::getAdBySlot('jsbot'))) {
				$_bottomExtra .= self::getAdBySlot('jsbot');
			}

			//Anchor Advertisement
			if ($showAds && $config->get('HydraSkinShowAnchorAd') && !empty(self::getAdBySlot('anchor'))) {
				$_bottomExtra .= self::getAdBySlot('anchor');
			}

			//Wiki Category Helper
			$_bottomExtra .= "
			<script type=\"text/javascript\">
				window.genreCategory = '{$wgWikiCategory}';
			</script>";

			//Advertisements closer.
			$_bottomExtra .= "<div id='cdm-zone-end'></div>";

			$template->set('bottomscripts', $template->data['bottomscripts'].$_bottomExtra);
		}

		if (self::isMobileSkin()) {
			$cpHolder = 'mobile-license';
		} else {
			$cpHolder = 'copyright';
		}
		$copyright = $template->data[$cpHolder];
		$copyright = $copyright."<br/>".nl2br($config->get('HydraSkinDisclaimer'));
		$template->set($cpHolder, $copyright);

		$template->set('showads', $showAds);

		return true;
	}

	/**
	 * Add in the mobile ATF MREC.
	 *
	 * @access	public
	 * @param	object	SkinTemplate Object
	 * @return	void
	 */
	static public function onMinervaPreRender($template) {
		if (self::isMobileSkin() && self::getAdBySlot('mobileatfmrec')) {
			$template->set('bodytext', "<div id='mobileatfmrec' class='atfmrec'>".self::getAdBySlot('mobileatfmrec')."</div>".$template->data['bodytext']);
		}
	}

	/**
	 * Body Class Change
	 *
	 * @access	public
	 * @param	object	OutputPage Object
	 * @param	object	Skin Object
	 * @param	array	Array of body attributes.  Example: array('class' => 'lovely');  Attributes should be concatenated to prevent overwriting.
	 * @return	boolean True
	 */
	static public function onOutputPageBodyAttributes($out, $skin, &$bodyAttrs){
		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');

		$bodyName = $config->get('HydraSkinBodyName');

		if (empty($bodyName)) {
			$info = wfParseUrl($skin->getContext()->getConfig()->get('Server'));
			$parts = explode('.', $info['host']);
			array_pop($parts); //Remove the TLD.
			$bodyName = implode('-', $parts);
		}

		//Add body class for advertisement targetting.
		$bodyAttrs['class'] .= ' site-'.$bodyName;

		//Add body class for advertisement toggling.
		if (self::showAds($skin) && self::getAdBySlot('footermrec')) {
			$bodyAttrs['class'] .= ' show-ads';
		} else {
			$bodyAttrs['class'] .= ' hide-ads';
		}

		return true;
	}

	/**
	 * The real check if we are using a mobile skin
	 *
	 * @access	public
	 * @return	boolean
	 */
	static public function isMobileSkin() {
		if (self::$isMobile !== null) {
			return self::$isMobile;
		}
		if (class_exists('MobileContext')) {
			$mobileContext = MobileContext::singleton();
			self::$isMobile = $mobileContext->shouldDisplayMobileView();
			return self::$isMobile;
		}
		return false;
	}

	/**
	 * Gets the contents of a partial file
	 *
	 * @param	string	the name (without extension) of a file in the partials folder
	 * @param	array	var_name -> value map of variables that should be available in the scope of the partial
	 * @return	string	the output of the specified partial
	 */
	public static function getPartial($__p, $__v) {
		$file = __DIR__."/partials/$__p.php";
		if (!file_exists($file)) {
			throw new MWException("Partial not found");
		}
		extract($__v, EXTR_SKIP);
		ob_start();
		require_once($file);
		return ob_get_clean();
	}

	/**
	 * Should this page show advertisements?
	 *
	 * @access	public
	 * @param	object	Skin
	 * @return	boolean	Advertisements Visible
	 */
	static public function showAds($skin) {
		global $wgUser;

		$curseUser = CurseAuthUser::getInstance($wgUser);

		$showAds = false;
		if (!$curseUser->isPremium() && $skin->getRequest()->getVal('action') != 'edit' && $skin->getRequest()->getVal('veaction') != 'edit' && $skin->getTitle()->getNamespace() != NS_SPECIAL && $_SERVER['HTTP_X_MOBILE'] != 'yes') {
			$showAds = true;
		}
		return $showAds;
	}

	/**
	 * Should this page show the ATF MREC Advertisement?
	 *
	 * @access	public
	 * @param	object	Skin
	 * @return	boolean	Show ATF MREC Advertisement
	 */
	static public function showAtfMrecAd($skin) {
		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');

		$wgHydraSkinSkipAtfMrecPages = $config->get('HydraSkinSkipAtfMrecPages');

		$disallowedNamespaces = [
			NS_USER,
			NS_USER_TALK,
			NS_MEDIAWIKI,
			NS_MEDIAWIKI_TALK
		];

		$show = false;

		$title = $skin->getTitle();
		if (
			$config->get('HydraSkinDisplayAtfMrec')
			&& self::showAds($skin)
			&& !in_array($title->getNamespace(), $disallowedNamespaces)
			&& $title->getText() != str_replace("_", " ", wfMessage('mainpage')->inContentLanguage()->text())
			&& (!is_array($wgHydraSkinSkipAtfMrecPages) || !in_array($title->getFullText(), $wgHydraSkinSkipAtfMrecPages))
			&& (!is_array($skin->getOutput()->getModules()) || !in_array('ext.curseprofile.profilepage', $skin->getOutput()->getModules()))
		) {
			$show = true;
		}
		return $show;
	}

	/**
	 * Return an advertisement by slot name.
	 *
	 * @access	public
	 * @return	void
	 */
	static function getAdBySlot($slot) {
		global $curseAdvertisements;

		if (is_array($curseAdvertisements) && array_key_exists($slot, $curseAdvertisements) && !empty($curseAdvertisements[$slot])) {
			return $curseAdvertisements[$slot];
		}
		return false;
	}
}
