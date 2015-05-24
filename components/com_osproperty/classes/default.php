<?php
/*------------------------------------------------------------------------
# default.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OspropertyDefault{
	/**
	 * Osproperty default
	 *
	 * @param unknown_type $option
	 */
	static function display($option,$task){
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		
		switch ($task){
			case "default_getstate":
				OspropertyDefault::defaultgetstate($option);
			break;
			
			default:
				$show_top_menus_in = $configClass['show_top_menus_in'];
				$show_top_menus_in = explode("|",$show_top_menus_in);
				if(in_array('frontpage',$show_top_menus_in)){
					echo HelperOspropertyCommon::buildToolbar('default');
				}
				OspropertyDefault::defaultLayout($option);
				HelperOspropertyCommon::loadFooter($option);
			break;
		}
	}
	
	/**
	 * Default layout
	 *
	 * @param unknown_type $option
	 */
	static function defaultLayout($option){
		global $mainframe,$configs,$configClass,$lang_suffix;
		$db = JFactory::getDBO();
		
		$document = JFactory::getDocument();
		OSPHelper::generateHeading(1,$configClass['general_bussiness_name']);

        if($configClass['show_random_feature'] == 1) {
            $query = "Select a.*, b.state_name$lang_suffix as state_name,e.type_name$lang_suffix as type_name from #__osrs_properties as a"
                . " inner join #__osrs_states as b on a.state = b.id"
                . " inner join #__osrs_types as e on e.id = a.pro_type"
                . " where a.published = '1' and a.approved = '1' and b.published = '1' and e.published = '1' and a.isFeatured = '1' order by rand() limit 1";
            $db->setQuery($query);
            $property = $db->loadObject();
            if ($property->id > 0) {
                $db->setQuery("Select * from #__osrs_photos where pro_id = '$property->id'");
                $property->photos = $db->loadObjectList();
            }
        }
		
		$lists = array();
		
		$lists['country'] = HelperOspropertyCommon::makeCountryList('','country_id','onchange="change_country_state(this.value)"',JText::_('OS_SELECT_COUNTRY'),'style="width:150px;"');

		if(OSPHelper::userOneState()){
			$lists['state'] = "<input type='hidden' name='state_id' id='state_id' value='".OSPHelper::returnDefaultState()."'/>";
		}else{
			$lists['state'] = HelperOspropertyCommon::makeStateList('','','state_id','onchange="javascript:loadCity(this.value,\'\')"',JText::_('OS_SELECT_STATE'),'');
		}
		
		$default_state = 0;
		if(OSPHelper::userOneState()){
			$default_state = OSPHelper::returnDefaultState();
		}else{
			$default_state = 0;
		}
		$lists['city'] = HelperOspropertyCommon::loadCity($option,$default_state,0);

		//property types
		$db->setQuery("SELECT id as value,type_name$lang_suffix as text FROM #__osrs_types where published = '1' ORDER BY ordering");
		$typeArr = $db->loadObjectList();
		array_unshift($typeArr,JHTML::_('select.option','',JText::_('OS_PROPERTY_TYPE')));
		$lists['type'] = JHTML::_('select.genericlist',$typeArr,'property_type','class="input-large"','value','text');
		
		HTML_OspropertyDefault::defaultLayout($option,$property,$lists,$configs);
	}
	
	/**
	 * 
	 *
	 * @param unknown_type $option
	 */
	static  function defaultgetstate($option){
		global $mainframe,$lang_suffix;
		$db = JFactory::getDBO();
		$country_id = JRequest::getInt('country_id',0);
		$option_state = array();
		$option_state[]= JHTML::_('select.option',0,' - '.JText::_(OS_ANY).' - ');
		
		if ($country_id){
			$db->setQuery("SELECT id AS value, state_name$lang_suffix AS text FROM #__osrs_states WHERE `country_id` = '$country_id' ORDER BY state_name$lang_suffix");		
			$states = $db->loadObjectList();
			if (count($states)){
				$option_state = array_merge($option_state,$states);
			}
			$disable = '';
		}else{
			$disable = 'disabled="disabled"';
		}
		
		echo JHTML::_('select.genericlist',$option_state,'state_id','onChange="javascript:loadCity(this.value,0)" class="input-small" '.$disable,'value','text');
	}
}
?>