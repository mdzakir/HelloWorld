<?php
/*------------------------------------------------------------------------
# mod_ospropertyrandom.php - mod_ospropertyrandom
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
error_reporting(E_ERROR | E_PARSE | E_COMPILE_ERROR);
define('DS',DIRECTORY_SEPARATOR);
JHTML::_('behavior.tooltip');
include_once(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."common.php");
include_once(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."helper.php");
include_once(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."route.php");
require_once( dirname(__FILE__).DS.'helper.php' );
global $lang_suffix;
$lang_suffix = OSPHelper::getFieldSuffix();
if (version_compare(JVERSION, '3.0', 'lt')) {
	OSPHelper::loadBootstrapStylesheet();
}else{
	$db = Jfactory::getDBO();
	$db->setQuery("Select fieldvalue from #__osrs_configuration where fieldname like 'load_bootstrap'");
	$loadbootstrap = $db->loadResult();
	if($loadbootstrap == 1){
		OSPHelper::loadBootstrapStylesheet();	
	}
}

$configClass = OSPHelper::loadConfig();

// Grab the products
$helper = new modOSpropertyramdomHelper( $params ); 
//list($properties,$check_search) = $helper->getProperty();
$properties = $helper->getProperty();

$width = $params->get('width',50);
$show_small_desc = $params->get('show_small_desc',0);
$show_address = $params->get('show_address',0);
$show_price = $params->get('show_price',0);
$limit_word = $params->get('limit_word',50);
$limit_title_word = $params->get('limit_title_word',0);
$show_photo = $params->get('show_photo',1);
$featured = $params->get('featured',0);
$category = $params->get('category','');
$type = $params->get('type','');
$style = $params->get('mstyle',0);
if($style == 1){
    $properties_per_row = $params->get('properties_per_row',4);
    $divstyle = 12/$properties_per_row;
}
$element_width = $params->get('element_width',180);
$element_height = $params->get('element_height',200);
$show_bathrooms = $params->get('show_bathrooms',0);
$show_bedrooms = $params->get('show_bedrooms',0);
$show_rooms = $params->get('show_rooms',0);
$show_category = $params->get('show_catgoryname',0);
$show_type = $params->get('show_typename',0);
$bstyle = $params->get('bstyle','white');
$sold = $params->get('sold',0);
$max_properties = $params->get('max_properties','');


$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'modules/mod_ospropertyrandom/asset/style.css');
include_once(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."common.php");

require( JModuleHelper::getLayoutPath( 'mod_ospropertyrandom' ) );