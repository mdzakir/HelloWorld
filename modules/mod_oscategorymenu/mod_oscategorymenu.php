<?php
/*------------------------------------------------------------------------
# mod_oscategorymenu.php - mod_oscategorymenu
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
error_reporting(E_COMPILE_ERROR | E_ERROR | E_PARSE | E_CORE_ERROR);
// Include helper functions only once
require_once( dirname(__FILE__).DIRECTORY_SEPARATOR .'helper.php' );
require_once(JPATH_ROOT.'/components/com_osproperty/helpers/route.php');
require_once(JPATH_ROOT.'/components/com_osproperty/helpers/helper.php');
$needs[] = "category_listing";
$needs[] = "lcategory";
$itemid = OSPRoute::getItemid($needs);
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root().'modules/mod_oscategorymenu/css/style.css');
require( JModuleHelper::getLayoutPath( 'mod_oscategorymenu' ) );
?>