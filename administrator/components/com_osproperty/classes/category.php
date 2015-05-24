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
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyCategories{
	/**
	 * Display
	 *
	 * @param unknown_type $option
	 * @param unknown_type $task
	 */
	function display($option,$task){
		global $mainframe,$languages;
		$languages = OSPHelper::getLanguages();
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."components/com_osproperty/js/lib.js");
		$cid = JRequest::getVar('cid');
		switch ($task){
			case "categories_list":
				OspropertyCategories::categories_list($option);
			break;
			case "categories_add":
				OspropertyCategories::categories_edit($option,0);
			break;
			case "categories_edit":
				OspropertyCategories::categories_edit($option,$cid[0]);
			break;
			case "categories_save":
				OspropertyCategories::save($option,1);
			break;
			case "categories_apply":
				OspropertyCategories::save($option,0);
			break;
			case "categories_gotolist":
				OspropertyCategories::gotolist($option);
			break;
			case "categories_remove":
				OspropertyCategories::removeList($option,$cid);
			break;
			case "categories_publish":
				OspropertyCategories::changState($option,$cid,1);
			break;
			case "categories_unpublish":
				OspropertyCategories::changState($option,$cid,0);
			break;
			case "categories_saveorder":
				OspropertyCategories::saveorder($option);
			break;
			case "categories_orderup":
				OspropertyCategories::orderup($option);
			break;
			case "categories_orderdown":
				OspropertyCategories::orderdown($option);
			break;
		}		
	}
	
	/**
	 * Categories list
	 *
	 * @param unknown_type $option
	 */
	function categories_list($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$limitstart       	= JRequest::getVar('limitstart',0);
		$limit      	  	= JRequest::getVar('limit',20);
		$keyword    	 	= JRequest::getVar('keyword','');
		$filter_order 	 	= JRequest::getVar('filter_order','ordering');
		$filter_order_Dir 	= JRequest::getVar('filter_order_Dir','');
		
		$lists['order'] 	= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		$levellimit 	  = 10;
		
		$query = "Select *, category_name AS title from #__osrs_categories where 1=1";
		if($keyword != ""){
			$query .= " and category_name  like '%$keyword%'";
		}
		$query .= " order by $filter_order $filter_order_Dir";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach ($rows as $v )
		{
			$pt = $v->parent_id;			
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v );
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, max( 0, $levellimit-1 ) );
		$total = count( $list );
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		// slice out elements based on limits
		$list = array_slice( $list, $pageNav->limitstart, $pageNav->limit);
		$rows = $list;
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$alias = $row->category_alias;
				if($alias == ""){
					$alias = OSPHelper::generateAlias('category',$row->id);
					$db->setQuery("Update #__osrs_categories set category_alias = '$alias' where id = '$row->id'");
					$db->query();
					$row->category_alias = $alias;
				}
			}
		}
		
		HTML_OspropertyCategories::listCategories($option,$rows,$pageNav,$lists,$children);
	}
	
	
	/**
	 * Category edit
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function categories_edit($option,$id){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Category','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
			$row->access = 0;
		}
		//$lists['state'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['state']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);
		
		$accessArr[] = JHTML::_('select.option','0',JText::_('Public'));
		$accessArr[] = JHTML::_('select.option','1',JText::_('Registered'));
		$accessArr[] = JHTML::_('select.option','2',JText::_('Special'));
		$lists['access'] = JHTML::_('select.genericlist',$accessArr,'access','class="input-small"','value','text',$row->access);
		$lists['parent'] = OspropertyCategories::listParentCategories($row);
		
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_HOLIDAY'));
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_PROPERTY'));
		$lists['price_type'] = JHTML::_('select.genericlist',$optionArr,'price_type','class="input-small"','value','text',$row->price_type);
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OspropertyCategories::editCategory($option,$row,$lists,$translatable);
	}
	
	/**
	 * Save data
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function save($option,$save){
		global $mainframe,$configClass,$languages;
		$db = JFactory::getDBO();
		jimport('joomla.filesystem.file');
		$remove_photo = JRequest::getInt('remove_photo',0);
		
		$row = &JTable::getInstance('Category','OspropertyTable');
		$post = JRequest::get('post');
		if(is_uploaded_file($_FILES['photo']['tmp_name'])){
			if(!HelperOspropertyCommon::checkIsPhotoFileUploaded('photo')){
				//return to previous page
				?>
				<script language="javascript">
				window.history(-1);
				</script>
				<?php
			}else{
				$filename = OSPHelper::processImageName(time()."_".$_FILES['photo']['name']);
				$dest     = JPATH_ROOT.DS."images".DS."osproperty".DS."category".DS.$filename;
				$thumb	  = JPATH_ROOT.DS."images".DS."osproperty".DS."category".DS."thumbnail".DS.$filename;
				JFile::upload($_FILES['photo']['tmp_name'],$dest);
				//resize
				@copy($dest,$thumb);
				$nwidth = $configClass['images_thumbnail_width'];
				$nheight = $configClass['images_thumbnail_height'];
				OSPHelper::resizePhoto($thumb,$nwidth,$nheight);
				$row->category_image = $filename;
			}
		}elseif($remove_photo == 1){
			$row->category_image = "";
		}
		$row->bind($post);
		$category_description = $_POST['category_description'];
		$row->category_description = $category_description;
		$id = JRequest::getVar('id',0);
		if($id == 0){
			//get the ordering
			$db->setQuery("Select ordering from #__osrs_categories where parent_id = '$row->parent_id' order by ordering desc limit 1");
			$ordering = $db->loadResult();
			$row->ordering = $ordering + 1;
		}
		$row->store();
		if($id == 0){
			$id = $db->insertID();
		}
		$category_alias = JRequest::getVar('category_alias');
		$category_alias = OSPHelper::generateAlias('category',$id,$category_alias);
		$db->setQuery("Update #__osrs_categories set category_alias = '$category_alias' where id = '$id'");
		$db->query();
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language){	
				$sef = $language->sef;
				$category_name_language = JRequest::getVar('category_name_'.$sef,'');
				$category_description_language = $_POST['category_description_'.$sef];
				if($category_name_language == ""){
					$category_name_language = $row->category_name;
				}
				if($category_name_language != ""){
					$category = &JTable::getInstance('Category','OspropertyTable');
					$category->id = $id;
					$category->access = $row->access;
					$category->{'category_name_'.$sef} = $category_name_language;
					$category->store();
				}
				if($category_description_language == ""){
					$category_description_language = $row->category_description;
				}
				if($category_description_language != ""){
					$category = &JTable::getInstance('Category','OspropertyTable');
					$category->id = $id;
					$category->access = $row->access;
					$category->{'category_description_'.$sef} = $category_description_language;
					$category->store();
				}
				
				$category_alias = JRequest::getVar('category_alias_'.$sef);
				$category_alias = OSPHelper::generateAliasMultipleLanguages('category',$id,$category_alias,$sef);
				$db->setQuery("Update #__osrs_categories set category_alias_".$sef." = '$category_alias' where id = '$id'");
				$db->query();
			}
		}
		$msg = JText::_('OS_ITEM_HAS_BEEN_SAVED');
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osproperty&task=categories_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=com_osproperty&task=categories_edit&cid[]=$id",$msg);
		}
	}
	
	
	/**
	 * Remove field groups
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function removeList($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if($cid){
			for($i=0;$i<count($cid);$i++){
				$id = $cid[$i];
				$db->setQuery("Select category_image from #__osrs_categories where id = '$id'");
				$category_image = $db->loadResult();
				$imagelink = JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."category";
				unlink($imagelink.DS.$category_image);
				unlink($imagelink.DS."thumbnail".DS.$category_image);
			}
			$cids = implode(",",$cid);
			$db->setQuery("Delete from #__osrs_categories where id in ($cids)");
			$db->query();
			//remove fields
			//$db->setQuery("Delete from #__osrs_properties where category_in in ($cids)");
			//$db->query();
			$db->setQuery("Select id from #__osrs_properties where category_id in ($cids)");
			$rows = $db->loadObjectList();
			$property_id_array = array();
			if(count($rows) > 0){
				for($i=0;$i<count($rows);$i++){
					$property_id_array[$i] = $rows[$i]->id;
				}
				OspropertyProperties::remove($option,$property_id_array);
			}
		}
		$msg = JText::_('OS_ITEM_HAS_BEEN_DELETED');
		$mainframe->redirect("index.php?option=com_osproperty&task=categories_list",$msg);
	}
	
	/**
	 * Change status of the field group(s)
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function changState($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if($cid){
			$cids = implode(",",$cid);
			$db->setQuery("Update #__osrs_categories set published = '$state' where id in ($cids)");
			$db->query();
		}
		$msg = JText::_("OS_ITEM_STATUS_HAS_BEEN_CHANGED");
		$mainframe->redirect("index.php?option=com_osproperty&task=categories_list",$msg);
	}
	
	
	/**
	 * Save order
	 *
	 * @param unknown_type $option
	 */
	function saveorder($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$msg = JText::_( 'New ordering saved' );
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
	
		$row = &JTable::getInstance('Category','OspropertyTable');
		
		$groupings	= array();

		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for( $i=0; $i < count($cid); $i++ ) {
			$row->load( $cid[$i] );
			// track parents
			$groupings[] = $row->parent_id;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($row->getError());
					return false;
				}
			} // if
		} // for

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder(' parent_id = '.(int) $group.' AND published = 1');
		}
		
		$mainframe->redirect("index.php?option=com_osproperty&task=categories_list",$msg);
	}
	
	
	
	/**
	 * Order up
	 *
	 * @return unknown
	 */
	function orderup(){
		global $mainframe,$_jversion;

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect(
				'index.php?option=com_osproperty&task=categories_list',
				JText::_('OS_NO_ITEM_SELECTED')
			);
			return false;
		}

		if (OspropertyCategories::orderItem($id, -1)) {
			$msg = JText::_( 'OS_MENU_ITEM_MOVED_UP' );
		} else {
			$msg = $model->getError();
		}
		
		$mainframe->redirect("index.php?option=com_osproperty&task=categories_list",$msg);
	}
	
	/**
	 * Order down
	 *
	 * @param unknown_type $option
	 */
	function orderdown($option){
		global $mainframe,$_jversion;

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect(
				'index.php?option=com_osproperty&task=categories_list',
				JText::_('OS_NO_ITEM_SELECTED')
			);
			return false;
		}

		if (OspropertyCategories::orderItem($id, 1)) {
			$msg = JText::_( 'OS_MENU_ITEM_MOVED_DOWN' );
		} else {
			$msg = $model->getError();
		}
		
		$mainframe->redirect("index.php?option=com_osproperty&task=categories_list",$msg);
	}
	
	/**
	 * Order Item
	 *
	 * @param unknown_type $item
	 * @param unknown_type $movement
	 * @return unknown
	 */
	function orderItem($item, $movement){
		$row = &JTable::getInstance('Category','OspropertyTable');
		$row->load( $item );
		if (!$row->move( $movement, ' parent_id = '.(int) $row->parent_id )) {
			$this->setError($row->getError());
			return false;
		}
		return true;
	}
	
	/**
	 * Build the select list for parent menu item
	 */
	function listParentCategories( $row ){
		$db =& JFactory::getDBO();

		// If a not a new item, lets set the menu item id
		if ( $row->id ) {
			$id = ' AND id != '.(int) $row->id;
		} else {
			$id = null;
		}

		// In case the parent was null
		if (!$row->parent_id) {
			$row->parent_id = 0;
		}

		// get a list of the menu items
		// excluding the current cat item and its child elements
		$query = 'SELECT *, category_name AS title ' .
				 ' FROM #__osrs_categories ' .
				 ' WHERE published = 1' .
				 $id .
			 	 ' ORDER BY parent_id, ordering';
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
		$parentArr[] 	= JHTML::_('select.option',  '0', JText::_( 'Top' ) );
		
		foreach ( $list as $item ) {
			if($item->treename != ""){
				$item->treename = str_replace("&nbsp;","",$item->treename);
			}
			$var = explode("-",$item->treename);
			$treename = "";
			for($i=0;$i<count($var)-1;$i++){
				$treename .= " - ";
			}
			$text = $item->treename;
			$parentArr[] = JHTML::_('select.option',  $item->id,$text);
		}
		$output = JHTML::_('select.genericlist', $parentArr, 'parent_id', 'class="inputbox" size="10"', 'value', 'text', $row->parent_id );
		return $output;
	}
	
	
	/**
	 * Go to list
	 *
	 * @param unknown_type $option
	 */
	function gotolist($option){
		global $mainframe;
		$mainframe->redirect("index.php?option=com_osproperty&task=categories_list");
	}
}
?>