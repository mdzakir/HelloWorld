<?php
/*------------------------------------------------------------------------
# pricegroup.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyPricegroup{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));
		switch ($task){
			case "pricegroup_list":
				OspropertyPricegroup::pricegroup_list($option);
			break;
			case "pricegroup_unpublish":
				OspropertyPricegroup::pricegroup_change_publish($option,$cid,0);	
			break;
			case "pricegroup_publish":
				OspropertyPricegroup::pricegroup_change_publish($option,$cid,1);
			break;
			case "pricegroup_remove":
				OspropertyPricegroup::pricegroup_remove($option,$cid);
			break;
			case "pricegroup_orderup":
				OspropertyPricegroup::pricegroup_change_order($option,$cid[0],-1);
			break;
			case "pricegroup_orderdown":
				OspropertyPricegroup::pricegroup_change_order($option,$cid[0],1);
			break;
			case "pricegroup_saveorder":
				OspropertyPricegroup::pricegroup_saveorder($option,$cid);
			break;
			case "pricegroup_add":
				OspropertyPricegroup::pricegroup_edit($option,0);
			break;
			case "pricegroup_edit":
				OspropertyPricegroup::pricegroup_edit($option,$cid[0]);
			break;
			case 'pricegroup_cancel':
				$mainframe->redirect("index.php?option=$option&task=pricegroup_list");
			break;	
			case "pricegroup_save":
				OspropertyPricegroup::pricegroup_save($option);
			break;	
		}
	}
	
	/**
	 * Pricegroup list
	 *
	 * @param unknown_Pricegroup $option
	 */
	function pricegroup_list($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$lists = array();
		$condition = '';
		
		$filter_order = JRequest::getVar('filter_order','ordering');
		$filter_order_Dir = JRequest::getVar('filter_order_Dir','');
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$keyword = JRequest::getVar('keyword','');
		if($keyword != ""){
			$condition .= " AND (a.price LIKE '%$keyword%')";
		}
		
		$count = "SELECT count(id) FROM #__osrs_pricegroups WHERE 1=1";
		$count .= $condition;
		$db->setQuery($count);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		
		$list  = "SELECT a.*,b.type_name FROM #__osrs_pricegroups as a";
		$list .= " LEFT JOIN #__osrs_types as b on b.id = a.type_id";
		$list .= " WHERE 1=1 ";
		$list .= $condition;
		$list .= " ORDER BY b.type_name,$filter_order $filter_order_Dir";
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		
		HTML_OspropertyPricegroup::pricegroup_list($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * publish or Unpublish
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function pricegroup_change_publish($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("UPDATE #__osrs_pricegroups SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->redirect("index.php?option=$option&task=pricegroup_list");
	}
	
	/**
	 * remove price group
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function pricegroup_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__osrs_pricegroups WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->redirect("index.php?option=$option&task=pricegroup_list");
	}
	
	/**
	 * change order price group
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $direction
	 */
	function pricegroup_change_order($option,$id,$direction){
		global $mainframe;
		
		$row = &JTable::getInstance('Pricegroup','OspropertyTable');
		$row->load($id);
		$row->move( $direction, ' published >= 0 ' );
		$mainframe->redirect("index.php?option=$option&task=pricegroup_list");
	}
	
	/**
	 * save new order
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function pricegroup_saveorder($option,$cid){
		global $mainframe;
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);
		$row = &JTable::getInstance('Pricegroup','OspropertyTable');
		
		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->type_id;
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$msg = JText::_( 'OS_ERROR_SAVING_ORDERING' );
					$mainframe->redirect("index.php?option=$option&task=pricegroup_list",$msg);
				}
			}
		}
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder(' type_id = '.(int) $group.' AND published = 1');
		}
		$msg = JText::_( 'OS_NEW_ORDERING_SAVED' );
		$mainframe->redirect("index.php?option=$option&task=pricegroup_list",$msg);
	}
	
	
	/**
	 * Pricegroup Detail
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function pricegroup_edit($option,$id){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$lists = array();
		
		$row = &JTable::getInstance('Pricegroup','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		
		//$lists['state'] = JHTML::_('select.booleanlist', 'published', 'class="input-mini"', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['state']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);
		
		$db->setQuery("Select id as value, type_name as text from #__osrs_types where published = '1' order by type_name");
		$types = $db->loadOBjectList();
		$typeArr = array();
		$typeArr[] = JHTML::_('select.option','',JText::_('OS_ALL_TYPES'));
		$typeArr   = array_merge($typeArr,$types);
		$lists['type'] = JHTML::_('select.genericlist',$typeArr,'type_id','class="input-large"','value','text',$row->type_id);
		// build the html select list for ordering
		$query = 'SELECT ordering AS value, CONCAT(price_from,"-",price_to) AS text'
			. ' FROM #__osrs_pricegroups'
			. ' ORDER BY ordering';
		$lists['ordering'] = JHTML::_('list.ordering', 'ordering', $query ,'',$row->ordering);
	
		HTML_OspropertyPricegroup::editHTML($option,$row,$lists);
	}
	
	/**
	 * save Pricegroup
	 *
	 * @param unknown_type $option
	 */
	function pricegroup_save($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$type_id = JRequest::getVar('type_id',0);
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row = &JTable::getInstance('Pricegroup','OspropertyTable');
		$row->bind($post);
		if (!$row->id){
			if (empty($row->ordering)){
				$db->setQuery("SELECT MAX(ordering) FROM #__osrs_pricegroups where type_id = '$type_id'");
				$row->ordering = $db->loadResult() + 1;
			}
		}
		$row->check();
		$msg = JText::_('OS_ITEM_HAS_BEEN_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING'); ;		 			 	
		 }
		
		$mainframe->redirect("index.php?option=$option&task=pricegroup_list",$msg);
	}
}
?>