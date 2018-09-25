<?php
/**
 * SkinTemplate class for Hydra skin
 * @ingroup Skins
 */
class SkinHydra extends SkinVector {
	public $skinname = 'hydra';
	public $stylename = 'Hydra';
	public $template = 'HydraTemplate';

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param OutputPage $out Object to initialize
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		$out->addModules( [ 'skins.hydra.advertisements.js', 'skins.hydra.footer.js' ] );
	}

	/**
	 * Loads skin and user CSS files.
	 *
	 * @param OutputPage $out
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		if ($this instanceof SkinHydraDark) {
			$out->addModuleStyles(
				[
					'skins.z.hydra.dark.styles'
				]
			);
		} elseif ($this instanceof SkinHydra) {
			$out->addModuleStyles(
				[
					'skins.z.hydra.light.styles'
				]
			);
		}
		$out->addModuleStyles(
			[
				'skins.hydra.netbar',
				'skins.hydra.footer',
				'skins.hydra.advertisements.styles'
			]
		);
	}
}
