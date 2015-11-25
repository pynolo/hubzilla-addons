<?php
/**
 * Name: Eorzea locations
 * Description: Derived from Random Planet, Empirial Version. Sample Friendica plugin/addon.
 * Version: 1.0
 * Author: Mike Macgirvin <http://macgirvin.com/profile/mike>
 * Author: Tony Baldwin <https://free-haven.org/profile/tony>
 * Author: Wave <https://hubzilla.it/profile/wave>>
 */


function eorzea_load() {

	/**
	 * 
	 * Our demo plugin will attach in three places.
	 * The first is just prior to storing a local post.
	 *
	 */

	register_hook('post_local', 'addon/eorzea/eorzea.php', 'eorzea_post_hook');

	/**
	 *
	 * Then we'll attach into the plugin settings page, and also the 
	 * settings post hook so that we can create and update
	 * user preferences.
	 *
	 */

	register_hook('feature_settings', 'addon/eorzea/eorzea.php', 'eorzea_settings');
	register_hook('feature_settings_post', 'addon/eorzea/eorzea.php', 'eorzea_settings_post');

	logger("loaded eorzea");
}


function eorzea_unload() {

	/**
	 *
	 * unload unregisters any hooks created with register_hook
	 * during load. It may also delete configuration settings
	 * and any other cleanup.
	 *
	 */

	unregister_hook('post_local',    'addon/eorzea/eorzea.php', 'eorzea_post_hook');
	unregister_hook('feature_settings', 'addon/eorzea/eorzea.php', 'eorzea_settings');
	unregister_hook('feature_settings_post', 'addon/eorzea/eorzea.php', 'eorzea_settings_post');


	logger("removed eorzea");
}



function eorzea_post_hook($a, &$item) {

	/**
	 *
	 * An item was posted on the local system.
	 * We are going to look for specific items:
	 *      - A status post by a profile owner
	 *      - The profile owner must have allowed our plugin
	 *
	 */

	logger('eorzea invoked');

	if(! local_channel())   /* non-zero if this is a logged in user of this system */
		return;

	if(local_channel() != $item['uid'])    /* Does this person own the post? */
		return;

	if($item['parent'])   /* If the item has a parent, this is a comment or something else, not a status post. */
		return;

	/* Retrieve our personal config setting */

	$active = get_pconfig(local_channel(), 'eorzea', 'enable');

	if(! $active)
		return;

	/**
	 *
	 * OK, we're allowed to do our stuff.
	 * Here's what we are going to do:
	 * load the list of timezone names, and use that to generate a list of world planets.
	 * Then we'll pick one of those at random and put it in the "location" field for the post.
	 *
	 */

	$locations = array(
		'Ul\'dah Steps of Nald','Ul\'dah Steps of Thal','Ceruleum Processing Plant','Bluefog','Castrum Meridianum','Drybone','Black Brush Station','Highbridge','The Burning Wall','Halatali','Copperbel Mines','Horizon','Vesper Bay','Little Ala Mhigo','The Sunken Temple of Qarn','Forgotten Springs','The Gold Saucer',
		'Limsa Lominsa Lower Decks','Limsa Lominsa Upper Decks','Overlook','Bronze Lake','Wanderer\'s Palace','Aleport','Satasha Seagrot','Swiftperch','Pharos Sirius','Summerford Farms','Wineport','Castrum Occidens','Costa del Sol','Moraby Drydocks',
		'New Gridania','Old Gridania','The Lost City of Amdapor','Camp Tranquil','Quarrymill','Toto-Rak','Tam-Tara Deepcroft','Bentbranch Meadows','Hakkuke Manor','The Hawtorne Hut','Sylphlands','The Sanctum of the Twelve','Little Solace','Fallgourd Float','Hyrstmill',
		'The Holy See Of Ishgard','Camp Dragonhead','First Dicasterial Observatorium of Aetherial and Astrological Phenomena','Whitebrim Front','Dzemael Darkhold','The Aurum Vale','Stone Vigil','Snowcloak','Falcon\'s Nest',
		'Mor Dhona','Revenant\'s Toll','Saint Coinach\'s Find','Castrum Centri','Crystal Tower');

	$location = array_rand($locations,1);
	$item['location'] = '#[url=http://eu.finalfantasyxiv.com/]' . $locations[$location] . '[/url]';

	return;
}




/**
 *
 * Callback from the settings post function.
 * $post contains the $_POST array.
 * We will make sure we've got a valid user account
 * and if so set our configuration setting for this person.
 *
 */

function eorzea_settings_post($a,$post) {
	if(! local_channel())
		return;
	if($_POST['eorzea-submit']) {
		set_pconfig(local_channel(),'eorzea','enable',intval($_POST['eorzea']));
		info( t('Eorzea Settings updated.') . EOL);
	}
}


/**
 *
 * Called from the Plugin Setting form. 
 * Add our own settings info to the page.
 *
 */



function eorzea_settings(&$a,&$s) {

	if(! local_channel())
		return;

	/* Add our stylesheet to the page so we can make our settings look nice */

	//$a->page['htmlhead'] .= '<link rel="stylesheet"  type="text/css" href="' . $a->get_baseurl() . '/addon/planets/planets.css' . '" media="all" />' . "\r\n";

	/* Get the current state of our config variable */

	$enabled = get_pconfig(local_channel(),'eorzea','enable');

	$checked = (($enabled) ? 1 : false);

	/* Add some HTML to the existing form */

	$sc .= replace_macros(get_markup_template('field_checkbox.tpl'), array(
		'$field'	=> array('eorzea', t('Enable Eorzea Plugin'), $checked, '', array(t('No'),t('Yes'))),
	));

	$s .= replace_macros(get_markup_template('generic_addon_settings.tpl'), array(
		'$addon' 	=> array('eorzea',t('Eorzea Settings'), '', t('Submit')),
		'$content'	=> $sc
	));
}
