<?php
/*------------------------------------------------------------------------
# helper.php - mod_ospropertysearch
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

defined('_JEXEC') or die('Restricted access');

class modOspropertySearchHelper
{
	public static function loadCity($option,$state_id,$city_id,$random_id){
		global $mainframe;
		$db = JFactory::getDBO();
        $configClass = OSPHelper::loadConfig();
        $show_available_states_cities = $configClass['show_available_states_cities'];
		$cityArr = array();
		$cityArr[]= JHTML::_('select.option',0,' - '.JText::_('OS_SEARCH_CITY').' - ');
        $availSql = "";
        if ($show_available_states_cities == 1) {
            $availSql = " and id in (Select city from #__osrs_properties where approved = '1' and published = '1')";
        }
		if($state_id > 0){
			$db->setQuery("Select id as value, city as text from #__osrs_cities where  published = '1' and state_id = '$state_id' $availSql order by city");
			$cities = $db->loadObjectList();
			$cityArr   = array_merge($cityArr,$cities);
			$disabled  = "";
		}else{
			$disabled  = "disabled";
		}
		return JHTML::_('select.genericlist',$cityArr,'city'.$random_id,'class="input-medium" '.$disabled,'value','text',$city_id);
	}
		
	static function listCategories($category_ids,$onChangeScript,$inputbox_width_site){
		global $mainframe;
        if($inputbox_width_site != ""){
            $width_style = "width: ".$inputbox_width_site."px !important;";
        }
		$parentArr = self::loadCategoryOptions($category_ids,$onChangeScript);
		$output = JHTML::_('select.genericlist', $parentArr, 'category_id', 'style="'.$width_style.'" class="input-medium " '.$onChangeScript, 'value', 'text', $category_ids );
		return $output;
	}
	
	
	static function loadCategoryOptions($category_ids,$onChangeScript){
		global $mainframe;
		$db = JFactory::getDBO();
		$lang_suffix = OSPHelper::getFieldSuffix();
		// get a list of the menu items
		// excluding the current cat item and its child elements
		$query = 'SELECT *, category_name'.$lang_suffix.' AS title,category_name'.$lang_suffix.' as category_name,parent_id as parent ' .
				 ' FROM #__osrs_categories ' .
				 ' WHERE published = 1' ;
		$user = JFactory::getUser();
		if(intval($user->id) > 0){
			$special = HelperOspropertyCommon::checkSpecial();
			if($special){
				$query .= " and `access` in (0,1,2) ";
			}else{
				$query .= " and `access` in (0,1) ";
			}
		}else{
			$query .= " and `access` = '0' ";
		}
		$query.= ' ORDER BY parent_id, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();

		// establish the hierarchy of the menu
		$children = array();

		if ( $mitems )
		{
			// first pass - collect children
			foreach ( $mitems as $v )
			{
				$pt 	= $v->parent_id;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		// assemble menu items to the array
		$parentArr 	= array();
		$parentArr[] = JHTML::_('select.option',  '',JText::_('OS_SEARCH_CATEGORIES'));
		foreach ( $list as $item ) {
			if($item->treename != ""){
				//$item->treename = str_replace("&nbsp;","",$item->treename);
			}
			$var = explode("-",$item->treename);
			$treename = "";
			for($i=0;$i<count($var)-1;$i++){
				$treename .= " - ";
			}
			$text = $item->treename;
			$parentArr[] = JHTML::_('select.option',  $item->id,$text);
		}
		return $parentArr;
	}
}
?>
