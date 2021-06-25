<?php
/**
 * SkinTemplate class for Hydra skin
 *
 * @ingroup Skins
 */

class SkinHydra extends SkinVector {
	public $skinname = 'hydra';

	public $stylename = 'Hydra';

	public $template = 'HydraTemplate';

	/**
	 * Initializes output page and sets up skin-specific parameters
	 *
	 * @param OutputPage $out Object to initialize
	 */
	public function initPage(OutputPage $out) {
		parent::initPage($out);

		$out->addModules(['skins.hydra.advertisements.js', 'skins.hydra.footer.js']);

		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
		if (HydraHooks::showAds($this) && $config->get('HydraSkinShowAnchorAd') && !empty(HydraHooks::getAdBySlot('anchor'))) {
			$out->addModuleScripts('skins.hydra.anchor.apu.js');
		}

		$out->addModuleStyles(
			[
				'skins.z.hydra.light.styles',
				'skins.hydra.googlefont.styles',
				'skins.hydra.netbar',
				'skins.hydra.footer',
				'skins.hydra.advertisements.styles',
				'skins.hydra.oasisOverrides',
				'skins.hydra.theme'
			]
		);
	}
}
