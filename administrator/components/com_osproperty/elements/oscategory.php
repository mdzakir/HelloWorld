<?php
/*------------------------------------------------------------------------
# category.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
global $mainframe;
error_reporting(E_ERROR||E_PARSE);
$mainframe = JFactory::getApplication();
define('DS', DIRECTORY_SEPARATOR);
include_once(JPATH_ADMINISTRATOR.DS."components".DS."com_osproperty".DS."classes".DS."property.php");

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');

class JFormFieldOsCategory extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'oscategory';
	
	function getInput()
	{    
		//print_r($this->element['value']);
		//die();
		if ($this->element['value'] > 0) {
    	    $selectedValue = $this->element['value'] ;
    	} else {
    	    $selectedValue = $this->value ;
    	} 
    	//print_r($this->value);
    	//die();
		$categories = OspropertyProperties::loadCategoryOptions('','');
		return JHtml::_('select.genericlist',$categories, $this->name.'[]', array(
		    'option.text.toHtml' => false ,
		    'option.value' => 'value', 
		    'option.text' => 'text', 
		    'list.attr' => ' class="inputbox" multiple',
		    'list.select' => $selectedValue    		        		
		));	
	}
}

?>