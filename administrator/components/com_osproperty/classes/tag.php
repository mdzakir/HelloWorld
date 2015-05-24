<?php
/*------------------------------------------------------------------------
# tag.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyTag{
	/**
	 * Default function
	 *
	 * @param unknown_tag $option
	 */
	function display($option,$task){
		global $mainframe;
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));
		switch ($task){
			case "tag_list":
				OspropertyTag::tag_list($option);
			break;
			case "tag_unpublish":
				OspropertyTag::tag_change_publish($option,$cid,0);	
			break;
			case "tag_publish":
				OspropertyTag::tag_change_publish($option,$cid,1);
			break;
			case "tag_remove":
				OspropertyTag::tag_remove($option,$cid);
			break;
			case "tag_add":
				OspropertyTag::tag_edit($option,0);
			break;
			case "tag_edit":
				OspropertyTag::tag_edit($option,$cid[0]);
			break;
			case 'tag_cancel':
				$mainframe->redirect("index.php?option=$option&task=tag_list");
			break;	
			case "tag_save":
				OspropertyTag::tag_save($option,1);
			break;	
			case "tag_apply":
				OspropertyTag::tag_save($option,0);
			break;
		}
	}
	
	/**
	 * Tag list
	 *
	 * @param unknown_tag $option
	 */
	function tag_list($option){
		global $mainframe,$configClass;
		$limit = JRequest::getInt('limit',20);
		$limitstart = JRequest::getInt('limitstart',0);
		$keyword = JRequest::getVar('keyword','');
		$status = JRequest::getInt('state',-1);
		$filter_order = JRequest::getVar('filter_order','count_tag');
		$filter_order_Dir = JRequest::getVar('filter_order_Dir','');
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$db = JFactory::getDbo();
		$query = "Select count(id) from #__osrs_tags where 1=1";
		if($status >= 0){
			$query .= " and published = '1'";
		}
		if($keyword != ""){
			$query .= " and keyword like '%$keyword%'";
		}
		$db->setQuery($query);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		$query = "Select a.id,a.keyword,a.published,count(b.id) as count_tag from #__osrs_tags as a left join #__osrs_tag_xref as b on b.tag_id = a.id where 1=1";
		if($status >= 0){
			$query .= " and a.published = '1'";
		}
		if($keyword != ""){
			$query .= " and a.keyword like '%$keyword%'";
		}
		$query .= " group by a.id";
		$query .= " ORDER BY $filter_order $filter_order_Dir";
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',-1,JText::_('OS_ALL_STATUS'));
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_PUBLISHED'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_UNPUBLISHED'));
		$lists['status'] = JHTML::_('select.genericlist',$optionArr,'state','class="input-medium" onChange="javascript:document.adminForm.submit()"','value','text',$status);
		HTML_OspropertyTag::listTags($option,$rows,$lists,$pageNav);
	}
	
	/**
	 * publish or unpublish tag
	 *
	 * @param unknown_tag $option
	 * @param unknown_tag $cid
	 * @param unknown_tag $state
	 */
	function tag_change_publish($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("Update #__osrs_tags SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$msg = JText::_("OS_ITEM_STATUS_HAS_BEEN_CHANGED");
		$mainframe->redirect("index.php?option=$option&task=tag_list",$msg);
	}
	
	/**
	 * remove tag
	 *
	 * @param unknown_tag $option
	 * @param unknown_tag $cid
	 */
	function tag_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__osrs_tags WHERE id IN ($cids)");
			$db->query();
			
			$db->setQuery("Delete from #__osrs_tag_xref where tag_id in ($cids)");
			$db->query();
		}
		$msg = JText::_("OS_ITEM_HAS_BEEN_DELETED");
		$mainframe->redirect("index.php?option=$option&task=tag_list",$msg);
	}
	
	/**
	 * Type Detail
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function tag_edit($option,$id){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$lists = array();
		
		$row = &JTable::getInstance('Tag','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		
		//$lists['state'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['state']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OspropertyTag::editHTML($option,$row,$lists,$translatable);
	}
	
	/**
	 * save tag
	 *
	 * @param unknown_type $option
	 */
	function tag_save($option,$save){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row = &JTable::getInstance('Tag','OspropertyTable');
		$row->bind($post);		 
		$row->check();
		$msg = JText::_('OS_ITEM_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING'); ;		 			 	
		}
		$id = JRequest::getInt('id',0);
		if($id == 0){
			$id = $db->insertID();
		}
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language){	
				$sef = $language->sef;
				$tag_language = JRequest::getVar('keyword_'.$sef,'');
				if($tag_language == ""){
					$tag_language = $row->keyword;
				}
				if($tag_language != ""){
					$tag = &JTable::getInstance('Tag','OspropertyTable');
					$tag->id = $id;
					$tag->{'keyword_'.$sef} = $tag_language;
					$tag->store();
				}
			}
		}
		if($save == 1){
			$mainframe->redirect("index.php?option=$option&task=tag_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=$option&task=tag_edit&cid[]=".$id,$msg);
		}
	}
}
?>