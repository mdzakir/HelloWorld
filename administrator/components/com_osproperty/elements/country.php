<?php
/*------------------------------------------------------------------------
# country.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

$version = new JVersion();
global $mainframe;
$mainframe = JFactory::getApplication();

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
class JFormFieldCountry extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'country';
	
	function getInput()
	{    
		$typeArr[] = JHTML::_('select.option','','Select country');
       	$db = JFactory::getDbo();
       	$db->setQuery("Select id as value, country_name as text from #__osrs_countries order by country_name");
       	$typeObjects = $db->loadObjectList();
       	$typeArr = array_merge($typeArr,$typeObjects);
		return JHtml::_('select.genericlist',$typeArr, $this->name, array(
		    'option.text.toHtml' => false ,
		    'option.value' => 'value', 
		    'option.text' => 'text', 
		    'list.attr' => ' class="inputbox" ',
		    'list.select' => $this->value    		        		
		));	
	}
}