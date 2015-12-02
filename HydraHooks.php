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
	 * Add Netbar CSS to page.
	 *
	 * @access	public
	 * @param	object	SkinTemplate Object
	 * @param	array	Array of Styles to Modify
	 * @return	boolean True
	 */
	static public function onSkinVectorStyleModules($skin, &$styles) {
		$styles[] = 'skins.hydra.netbar';
		$styles[] = 'skins.hydra.footer';

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
		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
		if ($config->get('HydraSkinUseDark')) {
			$skin->skinname = 'hydradark';
			$skin->stylename = 'HydraDark';
			$skin->template = 'HydraTemplate';
		} else {
			$skin->skinname = 'hydra';
			$skin->stylename = 'Hydra';
			$skin->template = 'HydraTemplate';
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

		if (isset($template->data['headelement'])) {
			//Custom Title Replacement
			$template->set(
						'headelement',
						str_replace('<title>'.htmlspecialchars(wfMessage('pagetitle', wfMessage('mainpage')->escaped())->escaped()).'</title>', '<title>'.htmlspecialchars(wfMessage('Pagetitle-view-mainpage')->escaped()).'</title>', $template->data['headelement'])
			);
			if (!self::isMobileSkin($template->getSkin())) {
				$netbar = self::getPartial('netbar', ['skin' => $template]);
				$template->set('headelement', $template->data['headelement'].$netbar);
			}
		}

		//Add Footer
		if (isset($template->data['bottomscripts'])) {
			if (!self::isMobileSkin($template->getSkin())) {
				$footer = self::getPartial(
					'footer',
					[
						'skin'			=> $template->getSkin(),
						'personalUrls'	=> $template->data['personal_urls']
					]
				);
				$template->set('bottomscripts', $template->data['bottomscripts'].$footer);
			}
		}

		if ($template->data['copyright'] != '') {
			$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');

			$copyright = $template->data['copyright'];
			$copyright = $copyright."  ".nl2br($config->get('HydraSkinDisclaimer'));
			$template->set('copyright', $copyright);
		}
		
		return true;
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
	 * @param Skin
	 * @return boolean
	 */
	static public function isMobileSkin(Skin $skin) {
		return $skin->getSkinName() == 'minerva';
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

		$showAds = false;
		if (!$wgUser->curse_premium && $skin->getRequest()->getVal('action') != 'edit' && $skin->getRequest()->getVal('veaction') != 'edit' && $skin->getTitle()->getNamespace() != NS_SPECIAL && $_SERVER['HTTP_X_MOBILE'] != 'yes') {
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
		global $wgDisplayMREC, $wgPagesWithNoAtfMrec;

		$disallowedNamespaces = [
			NS_USER,
			NS_USER_TALK,
			NS_MEDIAWIKI,
			NS_MEDIAWIKI_TALK
		];

		$show = false;

		$title = $skin->getTitle();
		if (
			$wgDisplayMREC
			&& self::showAds()
			&& !in_array($title->getNamespace(), $disallowedNamespaces)
			&& $title->getText() != str_replace("_", " ", wfMessage('mainpage')->inContentLanguage()->text())
			&& (!is_array($wgPagesWithNoAtfMrec) || !in_array($title->getFullText(), $wgPagesWithNoAtfMrec))
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
