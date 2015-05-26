<?php
/**
 * OSPROPERTY AJAX SEARCH
 * 
 * @package    mod_ospropertyajaxsearch
 * @subpackage Modules
 * @link http://www.joomdonation.com
 * @license        GNU/GPL, see LICENSE.php
 */

 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

global $_jversion;
$db = JFactory::getDBO();
$db->setQuery("Select * from #__osrs_configuration");
$configs = $db->loadObjectList();

$version = new JVersion();
$current_joomla_version = $version->getShortVersion();
global $mainframe;
$mainframe = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."modules/mod_ospropertyajaxsearch/lib/style/style.css");

$searchformurl = JURI::root()."index.php?option=com_osproperty&no_html=1&task=property_ajax_search";

$ordering 			= $params->get('ordering','category');
$show_introtext 	= $params->get('show_introtext','1');
$show_address		= $params->get('show_address','1');
$show_cost 			= $params->get('show_cost','1');
$show_agent 		= $params->get('show_agent','1');
$orderby 			= $params->get('orderby','listdate');
$ordertype 			= $params->get('ordertype','desc');
$search_name 		= $params->get('search_name','1');
$search_address 	= $params->get('search_address','1');
$search_agent 		= $params->get('search_agent','1');
$search_desc 		= $params->get('search_desc','1');
$use_ssl			= $params->get('use_ssl',0);
if($use_ssl == 0){
	$document->addScript(JURI::root().'modules/mod_ospropertyajaxsearch/lib/js/script.js');
}else{
	$document->addScript(JURI::root().'modules/mod_ospropertyajaxsearch/lib/js/scriptssl.js');
}
$document->addScript(JURI::root().'modules/mod_ospropertyajaxsearch/lib/js/javascript.js'); 

$searchformurl.= "&ordering=$ordering&show_introtext=$show_introtext&show_address=$show_address&show_cost=$show_cost&show_agent=$show_agent&orderby=$orderby&ordertype=$ordertype&search_name=$search_name&search_address=$search_address&search_agent=$search_agent&search_desc=$search_desc";


$ossearchform		= $params->get('form_width','170');
$number_property 	= $params->get('number_item',10);
$min_chars 			= $params->get('number_characters',2);
$scrolling 			= $params->get('mouse_scroll',1);
$resultwidth 		= $params->get('result_width',250);
$search_caption 	= JText::_('OS_SEARCH_CAPTION');
$no_matches 		= JText::_('OS_NO_MATCHES_CAPTION');
$no_matches_caption = JText::_('OS_NO_MATCHES_TITLE_CAPTION');
$show_desc 			= 1;
$element_height		= $params->get('element_height','10');

$document->addScriptDeclaration("
dojo.addOnLoad(function(){
  var ajaxSearch = new OSPajaxsearching({
    node : dojo.byId('offlajn-ajax-search'),
    searchBoxCaption : '".$search_caption."',
    noResultsTitle : '".$no_matches."',
    noResults : '".$no_matches_caption."',
    productsPerPlugin : ".$number_property.",
    searchRsWidth : ".$resultwidth.",
    resultElementHeight : ".$element_height.",
    minChars : ".$min_chars.",
    searchFormUrl : '$searchformurl',
    enableScroll : '".$scrolling."',
    showIntroText: '".$show_desc."'
  })
});" 
);

require( JModuleHelper::getLayoutPath( 'mod_ospropertyajaxsearch' ) );


?>