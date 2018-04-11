<?php
/**
 * Curse Inc.
 * Hydra Skin
 * ResourceLoaderLessVariableModule
 *
 * @author		Alexia E. Smith
 * @copyright	(c) 2018 Curse Inc.
 * @license		All Rights Reserved
 * @package		Hydra Skin
 * @link		http://www.curse.com/
 *
**/

class ResourceLoaderLessVariableModule extends ResourceLoaderFileModule {
	/**
	 * Get language-specific LESS variables for this module.
	 *
	 * @access	protected
	 * @param	object	ResourceLoaderContext
	 * @return	array
	 */
	protected function getLessVars(ResourceLoaderContext $context) {
		$lessVars = parent::getLessVars($context);

		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');

		$sideRailWidth = intval($config->get('SideRailWidth'));
		$lessVars['sideRailWidth'] = ($sideRailWidth < 1 ? 300 : $sideRailWidth).'px';

		$sideRailCollapseWidth = intval($config->get('SideRailCollapseWidth'));
		$lessVars['sideRailCollapseWidth'] = (!$sideRailCollapseWidth ? 1350 : $sideRailCollapseWidth).'px';

		//Future stuff.
		/*$vars['font-color-bold'] = '#ffffff';
		$vars['font-color-subdued'] = '#E0E0E0';
		$vars['background-primary'] = '#101010';
		$vars['background-secondary'] = '#202020';
		$vars['background-tertiary'] = '#282828';*/

		return $lessVars;
	}
}
