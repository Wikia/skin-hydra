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
	 * Should Show Advertisements
	 *
	 * @var boolean
	 */
	private static $showAds = null;

	/**
	 * Already processed modifications.
	 *
	 * @var boolean
	 */
	private static $beforeExecDone = false;

	/**
	 * Handle Minvera side bar.
	 *
	 * @access	public
	 * @param	string	Menu Type
	 * @param	object	MenuBuilder Object
	 * @param	object	SkinTemplate Object
	 * @return	boolean True
	 */
	static public function onMobileMenu($type, &$menu, &$skin) {
		if ($type === 'discovery' && $skin !== null) {
			$sidebar = $skin->buildSidebar();
			if (is_array($sidebar) && count($sidebar)) {
				foreach ($sidebar as $menus) {
					if (is_array($menus) && count($menus)) {
						foreach ($menus as $details) {
							try {
								$menu->insert($details['id'])->addComponent(
									$details['text'],
									$details['href']
								);
							} catch (DomainException $e) {
								//Already exists in the menu.
								continue;
							}
						}
					}
				}
			}
		}
		return true;
	}

	/**
	 * Add Hydra CSS modules to page.
	 *
	 * @access	public
	 * @param	object	SkinTemplate Object
	 * @param	array	Array of Styles to Modify
	 * @return	boolean True
	 */
	static public function onSkinVectorStyleModules($skin, &$styles) {
		if ($skin instanceof SkinHydraDark) {
			$styles[] = 'skins.z.hydra.dark';
		}
		if ($skin instanceof SkinHydra) {
			$styles[] = 'skins.z.hydra.light';
		}
		$styles[] = 'skins.hydra.netbar';
		$styles[] = 'skins.hydra.footer';
		$styles[] = 'skins.hydra.advertisements.styles';

		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
		if (self::showAds($skin) && $config->get('HydraSkinShowAnchorAd') && !empty(self::getAdBySlot('anchor'))) {
			$skin->getOutput()->addModuleScripts('skins.hydra.anchor.apu.js');
		}
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
		$modules[] = 'skins.hydra.advertisements.styles';
		$modules[] = 'skins.hydra.footer';
		$modules[] = 'skins.hydra.smartbanner';
		$modules[] = 'skins.hydra.mobile.apu.js';

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
	 *
	 * @access	public
	 * @param	object	SkinTemplate Object
	 * @param	object	Initialized SkinTemplate Object
	 * @return	boolean True
	 */
	static public function onSkinTemplateOutputPageBeforeExec(&$te, &$template) {
		global $wgUser, $wgRequest;

		if (defined('MW_API') && MW_API === true) {
			return true;
		}

		if (self::$beforeExecDone || !isset($template->data)) {
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
			if ($showAds) {
				$jsTop = (self::isMobileSkin() ? 'mobile' : '').'jstop';
				if (!empty(self::getAdBySlot($jsTop))) {
					$template->set('headelement', $template->data['headelement'].self::getAdBySlot($jsTop));
				}
			}

			if (!empty(self::getAdBySlot('googleanalyticsid'))) {
				$tags = explode("\n", self::getAdBySlot('googleanalyticsid'));
				$tags = array_map('trim', $tags);
				foreach ($tags as $index => $tag) {
					if (empty($tag)) {
						unset($tags[$index]);
					}
				}
				if (!empty($tags)) {
					$extraTracks = '';
					if ($wgUser->getId()) {
						$lookup = CentralIdLookup::factory();
						$globalId = $lookup->centralIdFromLocalUser($wgUser);
						if ($globalId) {
							$extraTracks['userId'] = $globalId;
						}
					}

					$creates = '';
					$sends = '';
					foreach ($tags as $index => $tag) {
						$creates .= "		ga('create', '{$tag}', 'auto', 'tracker{$index}', ".json_encode($extraTracks).");\n";
						if ($tag != 'UA-35871056-4') {
							$sends .= "
		if (window.cdnprovider) {
			ga(
				'tracker{$index}.send',
				'pageview',
				{
					'dimension1':  window.cdnprovider
				}
			);
		} else {
			ga('tracker{$index}.send', 'pageview');
		}\n";
						} else {
							$sends .= "		ga('tracker{$index}.send', 'pageview');\n";
						}
					}

					$gaTag = "	<script type=\"text/javascript\">
		(function(i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function() {
				(i[r].q = i[r].q || []).push(arguments)
			}

			, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
{$creates}{$sends}
	</script>\n";

					$template->set('headelement', $template->data['headelement'].$gaTag);
				}
			}

			//Netbar on desktop only.
			if (!self::isMobileSkin()) {
				$netbar = self::getPartial('netbar', ['skin' => $template]);
				$template->set('headelement', $template->data['headelement'].$netbar);
			}

			$addSmartBanner = false;
			//Show smart banner for iOS.
			if (self::isMobileSkin() && !empty(self::getAdBySlot('iosappid'))) {
				$addSmartBanner = true;
				$template->set(
					'headelement',
					str_replace('</title>', "</title>\n<meta name=\"apple-itunes-app\" content=\"app-id=".self::getAdBySlot('iosappid').", app-argument=".htmlentities($wgRequest->getRequestURL())."\">", $template->data['headelement'])
				);
			}
			//Show smart banner for Android.
			if (self::isMobileSkin() && !empty(self::getAdBySlot('androidpackage'))) {
				$addSmartBanner = true;
				$template->set(
					'headelement',
					str_replace('</title>', "</title>\n<meta name=\"google-play-app\" content=\"app-id=".self::getAdBySlot('androidpackage').", app-argument=".htmlentities($wgRequest->getRequestURL())."\">", $template->data['headelement'])
				);
			}

			if ($addSmartBanner && !empty(self::getAdBySlot('mobilebannerjs'))) {
				$outputPage = RequestContext::getMain()->getOutput();
				$wrappedJS = ResourceLoader::makeInlineScript(self::getAdBySlot('mobilebannerjs'));
				$template->set('bottomscripts', $template->data['bottomscripts'].$wrappedJS);
			}
		}

		if (isset($template->data['bottomscripts'])) {
			global $wgWikiCategory, $wgWikiTags;

			$_bottomExtra = '';

			$footerLinks = $template->data['footerlinks'];
			//Add Footer to desktop and mobile.
			$footer = self::getPartial(
				'footer',
				[
					'skin'			=> $template->getSkin(),
					'personalUrls'	=> $template->data['personal_urls']
				]
			);

			$template->set('privacy', null);

			if (self::isMobileSkin()) {
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

				if ($showAds && !empty(self::getAdBySlot('mobileatflb'))) {
					$_bottomExtra .= "
					<script type=\"text/javascript\">
						window.mobileatflb = '".str_replace("'", "\\'", self::getAdBySlot('mobileatflb'))."';
					</script>";
				}
				if ($showAds && !empty(self::getAdBySlot('mobileatfmrec'))) {
					$_bottomExtra .= "
					<script type=\"text/javascript\">
						window.mobileatfmrec = '".str_replace("'", "\\'", self::getAdBySlot('mobileatfmrec'))."';
					</script>";
				}
				if ($showAds && !empty(self::getAdBySlot('mobilebtfmrec'))) {
					$_bottomExtra .= "
					<script type=\"text/javascript\">
						window.mobilebtfmrec = '".str_replace("'", "\\'", self::getAdBySlot('mobilebtfmrec'))."';
					</script>";
				}
			} else {
				$_bottomExtra .= $footer;

				//Advertisements closer for desktop.  For mobile, please see mobileads.js.
				$_bottomExtra .= "<div id='cdm-zone-end'></div>";
			}

			//"Javascript" Bottom Advertisement Stuff
			if ($showAds) {
				$jsBottom = (self::isMobileSkin() ? 'mobile' : '').'jsbot';
				if ($showAds && !empty(self::getAdBySlot($jsBottom))) {
					$_bottomExtra .= self::getAdBySlot($jsBottom);
				}
			}

			//Wiki Category Helper
			$_bottomExtra .= "
			<script type=\"text/javascript\">
				window.genreCategory = '{$wgWikiCategory}';
				window.wikiTags = ".json_encode($wgWikiTags).";
			</script>";

			$template->set('bottomscripts', $template->data['bottomscripts'].$_bottomExtra);
		}

		if (self::isMobileSkin()) {
			$cpHolder = 'mobile-license';
		} else {
			$cpHolder = 'copyright';
		}
		$copyright = (isset($template->data[$cpHolder]) ? $template->data[$cpHolder] : '');
		$copyright = $copyright."<br/>".nl2br($config->get('HydraSkinDisclaimer'));
		$template->set($cpHolder, $copyright);

		$template->set('showads', $showAds);

		self::$beforeExecDone = true;

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
			if ($skin->getContext()->getConfig()->has('GroupMasterDomain') && !empty($skin->getContext()->getConfig()->get('GroupMasterDomain'))) {
				//Make a URL out of the domain.
				$info = wfParseUrl('//'.$skin->getContext()->getConfig()->get('GroupMasterDomain'));
			} else {
				$info = wfParseUrl($skin->getContext()->getConfig()->get('Server'));
			}
			$parts = explode('.', $info['host']);
			array_pop($parts); //Remove the TLD.
			$bodyName = implode('-', $parts);
		}

		//Add body class for advertisement targetting.
		$bodyAttrs['class'] .= ' site-'.$bodyName;

		$showAds = self::showAds($skin);
		//Anchor Advertisement
		if ($showAds && $config->get('HydraSkinShowAnchorAd') && !empty(self::getAdBySlot('anchor'))) {
			$bodyAttrs['data-site-identifier'] = self::getAdBySlot('anchor');
		}

		//Add body class for advertisement toggling.
		if ($showAds && self::getAdBySlot('footermrec')) {
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

		if (self::$showAds !== null) {
			return self::$showAds;
		}

		$isPremium = false;
		if (class_exists('\Hydra\Subscription') && !empty($wgUser) && $wgUser->getId()) {
			$subscription = \Hydra\Subscription::newFromUser($wgUser);
			if ($subscription !== false) {
				$isPremium = $subscription->hasSubscription();
			}
		}

		$showAds = false;
		if (!$isPremium && $skin->getRequest()->getVal('action') != 'edit' && $skin->getRequest()->getVal('veaction') != 'edit' && $skin->getTitle()->getNamespace() != NS_SPECIAL) {
			$showAds = true;
		}
		self::$showAds = $showAds;

		return self::$showAds;
	}

	/**
	 * Should this page show the ATF MREC Advertisement?
	 *
	 * @access	public
	 * @param	object	Skin
	 * @return	boolean	Show ATF MREC Advertisement
	 */
	static public function showSideRailAPUs($skin) {
		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');

		$wgHydraSkinHideSideRailPages = $config->get('HydraSkinHideSideRailPages');

		$disallowedNamespaces = [
			NS_USER,
			NS_USER_TALK,
			NS_MEDIAWIKI,
			NS_MEDIAWIKI_TALK
		];

		$show = false;

		$title = $skin->getTitle();
		if (
			$config->get('HydraSkinShowSideRail')
			&& self::showAds($skin)
			&& !in_array($title->getNamespace(), $disallowedNamespaces)
			&& $title->getText() != str_replace("_", " ", wfMessage('mainpage')->inContentLanguage()->text())
			&& (!is_array($wgHydraSkinHideSideRailPages) || !in_array($title->getFullText(), $wgHydraSkinHideSideRailPages))
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
	static public function getAdBySlot($slot) {
		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
		$siteAdvertisements = array_merge($config->get('SiteAdSlots'), $config->get('SiteMiscSlots'));

		if (is_array($siteAdvertisements) && array_key_exists($slot, $siteAdvertisements) && !empty(trim($siteAdvertisements[$slot]))) {
			return $siteAdvertisements[$slot];
		}
		return false;
	}

	/**
	 * Inject and modify certain LESS variables.
	 *
	 * @access	public
	 * @param	array	LESS Variables
	 * @return	boolean	True
	 */
	static public function onResourceLoaderGetLessVars(&$lessVars) {
		$config = ConfigFactory::getDefaultInstance()->makeConfig('hydraskin');
		$sideRailCollapseWidth = intval($config->get('SideRailCollapseWidth'));

		$lessVars['sideRailCollapseWidth'] = (!$sideRailCollapseWidth ? 1350 : $sideRailCollapseWidth).'px';
		return true;
	}

	/**
	 * Insert placements into the siderail.
	 *
	 * @access	public
	 * @param	array	Placements array to modify.
	 * @return	boolean	True
	 */
	static public function onSideRailPlacements(&$placements) {
		global $wgScriptPath;

		if (($placement = self::getAdBySlot('atfmrec')) !== false) {
			$placements['atfmrec'] = $placement;
		}

		if (($placement = self::getAdBySlot('zergnetsiderail')) !== false) {
			$placements['zergnetsiderail_container'] = "<div id=\"zzergnetsiderail\">".$placement."</div>";
		}

		$placements['gp-pro-upsell'] = "<a href='https://www.gamepedia.com/pro'><img src=".wfExpandUrl($wgScriptPath."/skins/Hydra/images/gppro/gpproupsellshort.png")."></a>";

		if (($placement = self::getAdBySlot('btfmrec')) !== false) {
			$placements['btfmrec'] = $placement;
		}
		return true;
	}

	/**
	 * Insert placements into the bottom
	 *
	 * @access	public
	 * @param	array	Placements array to modify.
	 * @param	object	VectorTemplate - The template.
	 * @return	boolean	True
	 */
	static public function onBottomPlacements(&$placements, &$template) {
		if (!$template->getSkin()->getContext()->getUser()->isLoggedIn()
			&& self::showSideRailAPUs($template->getSkin())
			&& $template->data['showads']
			&& self::getAdBySlot('zergnet')) {
				$placements['zergnetContainer'] = "<div id=\"zergnet_container\">".self::getAdBySlot('zergnet')."</div>";
		}

		if ($template->data['showads'] && self::getAdBySlot('btflb')) {
			$placements['btflb'] = self::getAdBySlot('btflb');
		}
		return true;
	}

	/**
	 * Hook right during Skin::initPage().
	 *
	 * @access	public
	 * @param	object	SkinTemplate
	 * @param	array	Links
	 * @return	boolean True
	 */
	static public function onSkinTemplateNavigation(SkinTemplate &$skinTemplate, array &$links) {
		if (isset($links['actions'])) {
			$title = $skinTemplate->getRelevantTitle();
			if ( $title->exists() && $title->quickUserCan( 'purge', $skinTemplate->getSkin()->getContext()->getUser() ) ) {
				$links['actions']['purge'] = [
					'class' => false,
					'text' => wfMessageFallback( "{$skinTemplate->skinname}-action-purge", 'purge' )
						->setContext( $skinTemplate->getContext() )->text(),
					'href' => $title->getLocalURL( 'action=purge' )
				];
			}
		}

		return true;
	}
}