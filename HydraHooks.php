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
		global $curseDisclaimer, $wgUser;

		if (!isset($template->data) || !isset($template->data['headelement'])) {
			return true;
		}
		if (isset($template->data['headelement'])) {
			$template->set(
						'headelement',
						str_replace('<title>'.htmlspecialchars(wfMessage('pagetitle', wfMessage('mainpage')->escaped())->escaped()).'</title>', '<title>'.htmlspecialchars(wfMessage('Pagetitle-view-mainpage')->escaped()).'</title>', $template->data['headelement'])
			);
			if (!self::isMobileSkin($template->getSkin())) {
				$netbar = CurseSkinPartials::get('netbar', ['skin' => $template]);
				$template->set('headelement', $template->data['headelement'].$netbar);
			}
		}
		if (isset($template->data['bottomscripts'])) {
			if (!self::isMobileSkin($template->getSkin())) {
				$footer = CurseSkinPartials::get(
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
			$copyright = $template->data['copyright'];
			$copyright = $copyright."  ".nl2br($curseDisclaimer);
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
	static public function onOutputPageBodyAttributes($out, $sk, &$bodyAttrs){
		global $curseBodyName;

		$bodyAttrs['class'] .= ' site-'.$curseBodyName;
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
}
