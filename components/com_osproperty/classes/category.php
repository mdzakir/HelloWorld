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

// No direct access.
defined('_JEXEC') or die;

class OspropertyCategories{
	static function display($option,$task){
		global $mainframe,$configClass;
		$cid = JRequest::getInt('cid',0);
		$id = JRequest::getInt('id',0);
		$show_top_menus_in = $configClass['show_top_menus_in'];
		$show_top_menus_in = explode("|",$show_top_menus_in);
		if(in_array('category',$show_top_menus_in)){
			echo HelperOspropertyCommon::buildToolbar('category');
		}
		switch ($task){
			case "category_listing":
				OspropertyCategories::listCategories($option);
			break;
			case "category_details":
				OspropertyCategories::categoryDetails($option,$id);
			break;
		}
		HelperOspropertyCommon::loadFooter($option);
	}
	
	/**
	 * List categories
	 *
	 * @param unknown_type $option
	 */
	static function listCategories($option){
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		OSPHelper::generateHeading(1,$configClass['general_bussiness_name']." - ".JText::_('OS_LIST_CATEGORIES'));
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		//access
		$access = (intval($user->id) > 0 ? '': 'and `access` = "0"');
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		$limit = JRequest::getInt('limit',20);
		$limitstart = JRequest::getInt('limitstart',0);
		$query = "select count(id) from #__osrs_categories where published = '1' $access and parent_id = '0'";
		$db->setQuery($query);
		$total = $db->loadResult();
		$pageNav = new OSPJPagination($total,$limitstart,$limit);
		$query = "select * from #__osrs_categories where published = '1' and parent_id = '0' $access order by ordering";
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$total = 0;
				$total = OspropertyCategories::countProperties($row->id,$total);
				//$db->setQuery("Select count(id) from #__osrs_properties where approved = '1' and published = '1' and category_id = '$row->id'");
				$row->nlisting = $total;
			}
		}
		HTML_OspropertyCategories::listCategories($option,$rows,$pageNav,$configs);
	}
	
	/**
	 * Count properties of the category
	 *
	 */
	static function countProperties($id,&$total){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select count(id) from #__osrs_properties where approved = '1' and published = '1' and id in (select pid from #__osrs_property_categories where category_id = '$id')");
		$count = $db->loadResult();
		$total += $count;
		//echo $total;
		$db->setQuery("Select * from #__osrs_categories where parent_id = '$id'");
		$categories = $db->loadObjectList();
		for($i=0;$i<count($categories);$i++){
			$cat = $categories[$i];
			$total = OspropertyCategories::countProperties($cat->id,$total);
		}
		return $total;
	}
	
	
	/**
	 * Category details
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	static function categoryDetails($option,$id){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		
		$user = JFactory::getUser();
		//access
		$access = (intval($user->id) > 0 ? "": "and `access` = '0'");
		$db->setQuery("Select count(id) from #__osrs_categories where id = '$id' and published = '1' $access");
		$count = $db->loadResult();
		if($count == 0){
			$mainframe->redirect("index.php",JText::_('OS_YOU_DO_NOT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
		}
		$db->setQuery("Select * from #__osrs_categories where id = '$id' and published = '1' $access");
		$cat = $db->loadObject();
		
		//pathway
		$pathway = $mainframe->getPathway();
		if($cat->parent_id > 0){
			$db->setQuery("Select category_name from #__osrs_categories where id = '$cat->parent_id'");
			$parent_category_name = $db->loadResult();
			$pathway->addItem($parent_category_name,Jroute::_('index.php?option=com_osproperty&task=category_details&id='.$cat->parent_id.'&Itemid='.JRequest::getInt('Itemid',0)));
		}
		$pathway->addItem($cat->category_name,Jroute::_('index.php?option=com_osproperty&task=category_details&id='.$cat->id.'&Itemid='.JRequest::getInt('Itemid',0)));
		
		$document = JFactory::getDocument();
		$document->setTitle($configClass['general_bussiness_name']." - ".JText::_('OS_CATEGORY')." - ".OSPHelper::getLanguageFieldValue($cat,'category_name'));
		
		//get the subcates
		$query = "select * from #__osrs_categories where published = '1' and parent_id = '$id' $access order by ordering";
		$db->setQuery($query);
		$subcats = $db->loadObjectList();
		if(count($subcats) > 0){
			for($i=0;$i<count($subcats);$i++){
				$row = $subcats[$i];
				$db->setQuery("Select count(id) from #__osrs_properties where approved = '1' and published = '1' and id in (select pid from #__osrs_property_categories where category_id = '$row->id')");
				$row->nlisting = $db->loadResult();
			}
		}
		
		HTML_OspropertyCategories::categoryDetailsForm($option,$cat,$subcats,$configs);
	}
	/**
	 * Show category details
	 * And 
	 * Show properties of the category
	 *
	 * @param unknown_type $option
	 */
	static function listProperties($option){
		global $mainframe,$_jversion;
		$db = JFactory::getDBO();
		$id = JRequest::getInt('id',0);
		if($id == 0){
			$mainframe->redirect("index.php",JText::_(OS_YOU_DO_NOT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA));
		}
		$user = JFactory::getUser();
		$db->setQuery("Select * from #__osrs_categories where id = '$id'");
		$category = $db->loadObject();
		$access = $category->access;
		if(!HelperOspropertyCommon::checkAccessPersmission($access)){
			$mainframe->redirect("index.php",JText::_(OS_YOU_DO_NOT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA));
		}
		
		OspropertyListing::listProperties($option,0,$id,0,0,'',0,0,0,0,0,'a.isFeatured desc,a.id desc',0,20);
	}
}
?>