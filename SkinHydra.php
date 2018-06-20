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

		$out->addModules( [ 'skins.hydra.banner.js', 'skins.hydra.advertisements.js', 'skins.hydra.footer.js' ] );
	}
}
