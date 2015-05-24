<?php
/*------------------------------------------------------------------------
# theme.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class OspropertyTheme{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));
		switch ($task){
			case "theme_list":
				OspropertyTheme::theme_list($option);
			break;
			case "theme_edit":
				OspropertyTheme::theme_modify($option,$cid[0]);
			break;
			case "theme_apply":
				OspropertyTheme::theme_save($option,0);
			break;
			case "theme_save":
				OspropertyTheme::theme_save($option,1);
			break;
			case "theme_install":
				OspropertyTheme::install();
			break;
			case "theme_publish":
				OspropertyTheme::theme_state($option,$cid[0],1);
			break;
			case "theme_unpublish":
				OspropertyTheme::theme_state($option,$cid[0],0);
			break;
			case "theme_remove":
				OspropertyTheme::removeTheme($cid);
			break;
			case "theme_copy":
				OspropertyTheme::copyTheme($cid[0]);
			break;
			case "theme_gotolist":
				$mainframe->redirect("index.php?option=com_osproperty&task=theme_list");
			break;
		}
	}
	
	function copyTheme($id){
		global $mainframe,$config;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_themes where id = '$id'");
		$theme = $db->loadObject();
		
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		$newtemplatename = date("d",time()).date("m",time()).date("Y",time()).date("H",time()).date("i",time()).date("s",time()).$theme->name;
		
		$row = &JTable::getInstance('Theme','OspropertyTable');
		$row->id 					= 0;
		$row->name 					= $newtemplatename;
		$row->title 				= date("d-m-y H:i:s",time()).$theme->title;
		$row->author 				= $theme->author;
		$row->creation_date 		= date("d-m-y H:i:s",time());
		$row->copyright 			= $theme->copyright;
		$row->author_email 			= $theme->author_email;
		$row->author_url 			= $theme->author_url;
		$row->version 				= $theme->version;
		$row->description 			= $theme->description;
		$row->params 				= $theme->params;
		$row->support_mobile_device = $theme->support_mobile_device;
		$row->published 			= 0;
		//save
		$row->store();
		//create new template folder
		JFolder::copy($theme->name,$newtemplatename,JPATH_ROOT.DS."components".DS."com_osproperty".DS."templates");
		//rename xml file
		JFile::copy(JPATH_ROOT.DS."components".DS."com_osproperty".DS."templates".DS.$newtemplatename.DS.$theme->name.".xml",JPATH_ROOT.DS."components".DS."com_osproperty".DS."templates".DS.$newtemplatename.DS.$newtemplatename.".xml");
		//remove old file
		JFile::delete(JPATH_ROOT.DS."components".DS."com_osproperty".DS."templates".DS.$newtemplatename.DS.$theme->name.".xml");
		$msg = "Theme has been copied";
		$mainframe->redirect("index.php?option=com_osproperty&task=theme_list",$msg);
	}
	
	/**
	 * Manage themes
	 *
	 * @param unknown_type $option
	 */
	function theme_list($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$db->setQuery("Select count(id) from #__osrs_themes");
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		$db->setQuery("Select * from #__osrs_themes order by id",$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		HTML_OspropertyTheme::listThemes($option,$rows,$pageNav);
	}
	
	/**
	 * Theme modify
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function theme_modify($option,$id){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_themes where id = '$id'");
		$item = $db->loadObject();
		$optionState[] = JHTML::_('select.option',1,JText::_('OS_PUBLISH'));
		$optionState[] = JHTML::_('select.option',0,JText::_('OS_UNPUBLISH'));
		//$lists['published'] = JHtml::_('select.genericlist',$optionState,'published','class="input-small"','value','text',$row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['published']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$item->published);
		
		$registry = new JRegistry();
		$registry->loadString($item->params);
		$data = new stdClass();
		$data->params = $registry->toArray();
		$form = JForm::getInstance('osproperty', JPATH_ROOT . '/components/com_osproperty/templates/' . $item->name . '/' . $item->name . '.xml', array(), false, '//config');
		$form->bind($data);
		
		$root = JFactory::getXML( JPATH_ROOT . '/components/com_osproperty/templates/' . $item->name . '/' . $item->name . '.xml', true);
		HTML_OspropertyTheme::editTheme($option,$item,$lists,$form,$root);
	}
	
	/**
	 * Save theme
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 * @return unknown
	 */
	function theme_save($option,$save){
		global $mainframe;
		$db = JFactory::getDbo();
		$id = JRequest::getVar('id',0);
		$row = & JTable::getInstance('Theme', 'OspropertyTable');
		$data = JRequest::get('post');
		if ($id > 0)
			$row->load($id);									
		if (!$row->bind($data)) {
			return false;
		}				
		//Save parameters
		$params		= JRequest::getVar( 'params', null, 'post', 'array' );		
		if (is_array($params))
		{
			$txt = array ();
			foreach ($params as $k => $v) {
				if (is_array($v)) {
					$v = implode(',', $v);	
				}
				$v =  str_replace("\r\n", '@@', $v) ;				
				$txt[] = "$k=\"$v\"";
			}
			$row->params = implode("\n", $txt);
		}
		
		
		if (!$row->store()) {
			return false;
		}

		$data['id'] = $row->id ;
		
		$published = JRequest::getVar('published',0);
		if($published == 1){
			$db->setQuery("Update #__osrs_themes set published = '0' where id <> '$row->id'");
			$db->query();
		}
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osproperty&task=theme_list",JText::_('OS_ITEM_HAS_BEEN_SAVED'));
		}else{
			$mainframe->redirect("index.php?option=com_osproperty&task=theme_edit&cid[]=$row->id",JText::_('OS_ITEM_HAS_BEEN_SAVED'));
		}
	}
	
	/**
	 * Theme installation
	 *
	 */
	function install(){
		global $mainframe,$configClass;
		global $mainframe;
		$db = & JFactory::getDBO();
		jimport('joomla.filesystem.archive');
		$db = JFactory::getDBO();
		$plugin = JRequest::getVar('theme_package', null, 'files');
		if ($plugin['error'] || $plugin['size'] < 1)
		{
			JRequest::setVar('msg', JText::_('Upload plugin package error'));
			
			return false;
		}
		$config = new JConfig();
		$dest = $config->tmp_path . '/' . $plugin['name'];
		$uploaded = JFile::upload($plugin['tmp_name'], $dest);
		if (!$uploaded)
		{
			JRequest::setVar('msg', JText::_('OS_THEME_UPLOAD_FAILED'));
			
			return false;
		}
		// Temporary folder to extract the archive into
		$tmpdir = uniqid('install_');
		$extractdir = JPath::clean(dirname($dest) . '/' . $tmpdir);
		$result = JArchive::extract($dest, $extractdir);
		if (!$result)
		{
			JRequest::setVar('msg', JText::_('OS_EXTRACT_THEME_ERROR'));
			return false;
		}
		$dirList = array_merge(JFolder::files($extractdir, ''), JFolder::folders($extractdir, ''));
		if (count($dirList) == 1)
		{
			if (JFolder::exists($extractdir . '/' . $dirList[0]))
			{
				$extractdir = JPath::clean($extractdir . '/' . $dirList[0]);
			}
		}
		//Now, search for xml file
		$xmlfiles = JFolder::files($extractdir, '.xml$', 1, true);
		if (empty($xmlfiles))
		{
			JRequest::setVar('msg', JText::_('OS_COULD_NOT_FIND_XML_FILE'));
			return false;
		}
		$file = $xmlfiles[0];
		$root = JFactory::getXML($file, true);
		$pluginType = $root->attributes()->type;
		$pluginGroup = $root->attributes()->group;
		if ($root->getName() !== 'install')
		{
			JRequest::setVar('msg', JText::_('OS_INVALID_XML_FILE'));
			return false;
		}
		if ($pluginType != 'osptheme')
		{
			JRequest::setVar('msg', JText::_('OS_INVALID_OSP_THEME'));
			return false;
		}
		$name = (string) $root->name;
		$title = (string) $root->title;
		$author = (string) $root->author;
		$creationDate = (string) $root->creationDate;
		$copyright = (string) $root->copyright;
		$license = (string) $root->license;
		$authorEmail = (string) $root->authorEmail;
		$authorUrl = (string) $root->authorUrl;
		$version = (string) $root->version;
		$description = (string) $root->description;
		$mobile_device = (int) $row->mobiledevice;
		$row = & JTable::getInstance('Theme', 'OspropertyTable') ;		
		$sql = 'SELECT id FROM #__osrs_themes WHERE name="'.$name.'"';
		$db->setQuery($sql);
		$pluginId = (int) $db->loadResult();
		if ($pluginId)
		{
			$row->load($pluginId);
			$row->name = $name;
			$row->title = $title;
			$row->author = $author;
			$row->creation_date = $creationDate;
			$row->copyright = $copyright;
			$row->license = $license;
			$row->author_email = $authorEmail;
			$row->author_url = $authorUrl;
			$row->version = $version;
			$row->description = $description;
			$row->support_mobile_device = $mobile_device;
		}
		else
		{
			$row->name = $name;
			$row->title = $title;
			$row->author = $author;
			$row->creation_date = $creationDate;
			$row->copyright = $copyright;
			$row->license = $license;
			$row->author_email = $authorEmail;
			$row->author_url = $authorUrl;
			$row->version = $version;
			$row->description = $description;
			$row->support_mobile_device = $mobile_device;
			$row->published = 0;
		}
		$row->store();
		$pluginDir = JPATH_ROOT . '/components/com_osproperty/templates';
		$result = JArchive::extract($dest, $pluginDir);
		JFolder::delete($extractdir);
				
		$msg = JText::_('OS_THEME_HAS_BEEN_INSTALLED_SUCCESSFULLY');
		$mainframe->redirect("index.php?option=com_osproperty&task=theme_list",$msg);
	}
	
	/**
	 * Theme state
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 * @param unknown_type $state
	 */
	function theme_state($option,$id,$state){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		if($state == 1){
			$db->setQuery("Update #__osrs_themes set published = '0'");
			$db->query();
			$db->setQuery("Update #__osrs_themes set published = '1' where id = '$id'");
			$db->query();
		}else{
			$db->setQuery("Update #__osrs_themes set published = '0'");
			$db->query();
			$db->setQuery("Update #__osrs_themes set published = '1' where name like 'default'");
			$db->query();
		}
		$msg = JText::_('OS_THEME_HAS_BEEN_CHANGE_STATUS');
		$mainframe->redirect("index.php?option=com_osproperty&task=theme_list",$msg);
	}
	
	/**
	 * Remove theme
	 *
	 * @param unknown_type $cid
	 */
	function removeTheme($cid){
		global $mainframe;
		$db = JFactory::getDbo();
		jimport('joomla.filesystem.folder') ;
		$row = & JTable::getInstance('Theme', 'OspropertyTable');				
		$pluginDir = JPATH_ROOT.DS.'components'.DS.'com_osproperty'.DS.'templates' ;
		foreach ($cid as $id) {
			$row->load($id);
			if($row->published != 1){
				$name = $row->name ;
				JFolder::delete($pluginDir."/".$name);
				$db->setQuery("Delete from #__osrs_themes where id = '$id'");
				$db->query();
			}
		}
		$msg = JText::_('OS_ITEM_HAVE_BEEN_REMOVED');
		if(count($cid) == 1){
			$row->load($id);
			if($row->published == 1){
				$msg = JText::_('OS_THIS_THEME_CANNOT_BE_REMOVED');		
			}
		}
		$mainframe->redirect("index.php?option=com_osproperty&task=theme_list",$msg);
	}
}
?>