<?php
/*------------------------------------------------------------------------
# companies.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

define('PATH_STORE_PHOTO_COMPANY_FULL',JPATH_ROOT.DS.'images'.DS.'osproperty'.DS.'company');
define('PATH_STORE_PHOTO_COMPANY_THUMB',PATH_STORE_PHOTO_COMPANY_FULL.DS.'thumbnail');
define('PATH_URL_PHOTO_COMPANY_FULL',str_replace(DS,'/',str_replace(JPATH_SITE,JURI::root(),PATH_STORE_PHOTO_COMPANY_FULL)).'/');
define('PATH_URL_PHOTO_COMPANY_THUMB',str_replace(DS,'/',str_replace(JPATH_SITE,JURI::root(),PATH_STORE_PHOTO_COMPANY_THUMB)).'/');

class OspropertyCompanies{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	// for company
	
	function display($option,$task){
		global $mainframe;
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."components/com_osproperty/js/lib.js");
		JHTML::_('behavior.modal');
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));
		switch ($task){
			case "companies_list":
				OspropertyCompanies::companies_list($option);
				HelperOspropertyCommon::loadFooter($option);
			break;
			case "companies_unpublish":
				OspropertyCompanies::companies_change_publish($option,$cid,0);	
			break;
			case "companies_publish":
				OspropertyCompanies::companies_change_publish($option,$cid,1);
			break;
			case "companies_remove":
				OspropertyCompanies::companies_remove($option,$cid);
			break;
			case "companies_add":
				OspropertyCompanies::companies_edit($option,0);
				HelperOspropertyCommon::loadFooter($option);
			break;
			case "companies_edit":
				OspropertyCompanies::companies_edit($option,$cid[0]);
				HelperOspropertyCommon::loadFooter($option);
			break;
			case 'companies_cancel':
				$mainframe->redirect("index.php?option=$option&task=companies_list");
			break;	
			case "companies_save":
				OspropertyCompanies::companies_save($option,1);
			break;
			case "companies_apply":
				OspropertyCompanies::companies_save($option,0);
			break;
			case "companies_getstate":
				OspropertyCompanies::companies_getstate($option);
			break;
		}
	}
	
	/**
	 * Companies list
	 *
	 * @param unknown_type $option
	 */
	function companies_list($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$lists = array();
		$condition = '';
		
		$filter_order = JRequest::getVar('filter_order','id');
		$filter_order_Dir = JRequest::getVar('filter_order_Dir','');
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$keyword = JRequest::getVar('keyword','');
		if($keyword != ""){
			$condition .= " AND (";
			$condition .= " company_name LIKE '%$keyword%' ";
			$condition .= " OR address LIKE '%$keyword%' ";
			//$condition .= " OR state LIKE '%$keyword%' ";
			//$condition .= " OR city LIKE '%$keyword%' ";
			//$condition .= " OR country LIKE '%$keyword%' ";
			$condition .= " OR company_description LIKE '%$keyword%' ";
			$condition .= ")";
		}
		
		$count = "SELECT count(id) FROM #__osrs_companies WHERE 1=1";
		$count .= $condition;
		$db->setQuery($count);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		
		$list  = "SELECT p.*, c.country_name, s.state_name FROM #__osrs_companies AS p"
				." LEFT JOIN #__osrs_countries AS c ON c.id = p.country"
				." LEFT JOIN #__osrs_states AS s ON s.id = p.state"
				." WHERE 1=1 ";
		
		$list .= $condition;
		$list .= " ORDER BY $filter_order $filter_order_Dir";
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		
		
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$alias = $row->company_alias;
				if($alias == ""){
					$alias = OSPHelper::generateAlias('company',$row->id);
					$db->setQuery("Update #__osrs_companies set company_alias = '$alias' where id = '$row->id'");
					$db->query();
					$row->company_alias = $alias;
				}
			}
		}
		
		HTML_OspropertyCompanies::companies_list($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * publish or unpublish companies
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function companies_change_publish($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("Update #__osrs_companies SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$msg = JText::_('OS_ITEM_STATUS_HAS_BEEN_CHANGED');
		$mainframe->redirect("index.php?option=$option&task=companies_list",$msg);
	}
	
	/**
	 * remove companies
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function companies_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("SELECT photo FROM #__osrs_companies WHERE id IN ($cids)");
			$photos = $db->loadResultArray();
			if (count($photos)){
				foreach ($photos as $photo) {
					if (is_file(PATH_STORE_PHOTO_COMPANY_FULL.DS.$photo)) unlink(PATH_STORE_PHOTO_COMPANY_FULL.DS.$photo);
					if (is_file(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$photo)) unlink(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$photo);
				}
			}
			$db->setQuery("DELETE FROM #__osrs_companies WHERE id IN ($cids)");
			$db->query();
		}
		$msg = JText::_('OS_ITEM_HAS_BEEN_DELETED');
		$mainframe->redirect("index.php?option=$option&task=companies_list",$msg);
	}
	
	
	/**
	 * companies Detail
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function companies_edit($option,$id){
		global $mainframe,$configClass,$languages;
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Companies','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		
		//$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['published']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);
		
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_NO'));
		$lists['approval']   = JHTML::_('select.genericlist',$optionArr,'approval','class="input-mini"','value','text',$row->request_to_approval);
		
		$lists['country'] = HelperOspropertyCommon::makeCountryList($row->country,'country','onchange="loadStateBackend(this.value,\''.$row->state.'\',\''.$row->city.'\')"','','');
		
		$lists['states'] = HelperOspropertyCommon::makeStateList($row->country,$row->state,'state','onchange="loadCityBackend(this.value,'.intval($row->city).')" class="input-medium"',JText::_('OS_SELECT_STATE'),'');
		$lists['city'] = HelperOspropertyCommon::loadCity($option,$row->state,$row->city);
		
		// build the html select list for user
		$option_user = array();
		$option_user[] = JHtml::_('select.option',0,' - '.JText::_('OS_SELECT_COMPANY_ADMIN').' - ');
		$db->setQuery("SELECT id, name, username FROM #__users where block = '0' and id not in (Select user_id from #__osrs_agents where agent_type = '0')");
		$users = $db->loadObjectList();
		foreach ($users as $user) {
			$option_user[] = JHtml::_('select.option',$user->id,$user->username.' ['.$user->name.' - '.$user->id.']');
		}
		$lists['user_id'] = JHtml::_('select.genericlist',$option_user,'user_id','class="input-small"','value','text',$row->user_id);
		
		$query = "Select a.id as value, a.name as text from #__osrs_agents as a"
				." inner join #__users as b on b.id = a.user_id"
				." where b.block = '0' and agent_type = '0'"
				." and b.id not in (Select user_id from #__osrs_companies)"
				." and a.id not in (Select id from #__osrs_agents where company_id > 0 and published = '1') order by a.name";
		$db->setQuery($query);
		$lists['agentsnotinCompany'] = $db->loadObjectList();
		
		if($row->id > 0){
			$query = "Select a.id as value, a.name as text from #__osrs_agents as a where a.company_id = '$id' and a.agent_type='0' order by a.name";
			$db->setQuery($query);
			$lists['agentinCompany'] = $db->loadObjectList();
		}else{
			$lists['agentinCompany'] = array();
		}
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OspropertyCompanies::editHTML($option,$row,$lists,$translatable);
	}
	
	
	function getUserInput($user_id)
	{
		// Initialize variables.
		$html = array();
		//$groups = $this->getGroups();
		//$excluded = $this->getExcluded();
		$link = 'index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;field=user_id';
			//. (isset($groups) ? ('&amp;groups=' . base64_encode(json_encode($groups))) : '')
			//. (isset($excluded) ? ('&amp;excluded=' . base64_encode(json_encode($excluded))) : '');

		// Initialize some field attributes.
		$attr = ' class="inputbox"';
		//$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		//$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		JHtml::_('behavior.modal');
		JHtml::_('behavior.modal', 'a.modal_user_id');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectUser_user_id(id, title) {';
		$script[] = '		var old_id = document.getElementById("user_id").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("user_id").value = id;';
		$script[] = '			document.getElementById("user_id_name").value = title;';
		$script[] = '			' . $onchange;
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current username if available.
		$table = JTable::getInstance('user');
		
		if ($user_id)
		{
			$table->load($user_id);
		}
		else
		{
			$table->username = JText::_('OS_SELECT_COMPANY_ADMIN');
		}

		// Create a dummy text field with the user name.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="user_id_name" value="'.htmlspecialchars($table->name, ENT_COMPAT, 'UTF-8') .'" disabled="disabled" size="35" /><a class="modal btn" title="'.JText::_('JLIB_FORM_CHANGE_USER').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('JLIB_FORM_CHANGE_USER').'</a>';
		$html[] = '</span>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="user_id" name="user_id" value="'.$user_id.'" />';

		return implode("\n", $html);
	}
	
	/**
	 * save companies
	 *
	 * @param unknown_type $option
	 */
	function companies_save($option,$save){
		global $mainframe,$configClass,$languages;
		$db = JFactory::getDBO();
		$country = JRequest::getVar('country',$configClass['show_country_id']);
		//check to see if user uploaded new state
		
		jimport('joomla.filesystem.file');
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		
		//PATH_STORE_PHOTO_COMPANY_FULL store full image;
		//PATH_STORE_PHOTO_COMPANY_THUMB store thumbnail image
		
		// check folder to upload file
		if (!JFolder::exists(PATH_STORE_PHOTO_COMPANY_THUMB)) JFolder::create(PATH_STORE_PHOTO_COMPANY_THUMB);
		
		// remove if you want
		if (isset($post['remove_photo'])){
			if (is_file(PATH_STORE_PHOTO_COMPANY_FULL.DS.$post['photo'])) unlink(PATH_STORE_PHOTO_COMPANY_FULL.DS.$post['photo']);
			if (is_file(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$post['photo'])) unlink(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$post['photo']);
			$post['photo'] = '';
		}
		
		// upload file
		if(!HelperOspropertyCommon::checkIsPhotoFileUploaded('file_photo')){
			//return to previous page
			?>
			<script language="javascript">
			window.history(-1);
			</script>
			<?php
		}else{
			if ( !empty($_FILES['file_photo']['name']) && $_FILES['file_photo']['error'] == 0 &&  $_FILES['file_photo']['size'] > 0 ) {
				$imagename = OSPHelper::processImageName(time()."".str_replace(" ","",$_FILES['file_photo']['name']));
				if (move_uploaded_file($_FILES['file_photo']['tmp_name'],PATH_STORE_PHOTO_COMPANY_FULL.DS.$imagename)){
					
					// copy image before resize
					copy(PATH_STORE_PHOTO_COMPANY_FULL.DS.$imagename,PATH_STORE_PHOTO_COMPANY_THUMB.DS.$imagename);
					// resize image just copy and replace it selft
					require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'image.php');
					$image = new SimpleImage();
					$image->load(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$imagename);
					$imagesize = getimagesize(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$imagename);
					$owidth = $imagesize[0];
					$oheight = $imagesize[1];
					$nwidth = $configClass['images_thumbnail_width'];
					if($nwidth < $owidth){ //only resize when the image width is smaller
						$nheight = round(($nwidth*$oheight)/$owidth);
					    $image->resize($nwidth,$nheight);
					    $image->save(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$imagename,$configClass['images_quality']);
					}
					    
					// remove old image
					if (is_file(PATH_STORE_PHOTO_COMPANY_FULL.DS.$post['photo'])) unlink(PATH_STORE_PHOTO_COMPANY_FULL.DS.$post['photo']);
					if (is_file(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$post['photo'])) unlink(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$post['photo']);
						
				    // set new name
				    $post['photo'] = $imagename;
				}
			}
		}
	
		// standard file name
		$filename = str_replace('  ',' ',$post['company_name']);
		$filename = strtolower(str_replace(' ','_',$filename)).'_';
		$filename = uniqid($filename).'.';	
		if ($post['photo'] != ''){
			$filename .= JFile::getExt(PATH_STORE_PHOTO_COMPANY_FULL.DS.$post['photo']);
			if (is_file(PATH_STORE_PHOTO_COMPANY_FULL.DS.$post['photo']))	
				rename(PATH_STORE_PHOTO_COMPANY_FULL.DS.$post['photo'],PATH_STORE_PHOTO_COMPANY_FULL.DS.$filename);
				
			if (is_file(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$post['photo']))
				rename(PATH_STORE_PHOTO_COMPANY_THUMB.DS.$post['photo'],PATH_STORE_PHOTO_COMPANY_THUMB.DS.$filename);
			$post['photo'] = $filename;
		}
		
		// store data
		$row = &JTable::getInstance('Companies','OspropertyTable');
		$row->bind($post);

		if($configClass['auto_approval_company_register_request'] == 0){
			$approval = JRequest::getInt('approval',0);
			if($approval == 0){
				$row->request_to_approval = 0;
				$row->published			  = JRequest::getInt('published',0);
			}else{
				$row->request_to_approval = 1;
				$row->published			  = 0;
			}
		}else{
			$row->published			  = JRequest::getInt('published',0);
			$row->request_to_approval = 0;
		}
		 
		$row->check();
		$msg = JText::_('OS_ITEM_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING');
		}
		$id = JRequest::getVar('id',0);
		if($id == 0){
			$id = $db->insertID();
		}
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language) {	
				$sef = $language->sef;
				$company_description_language = $_POST['company_description_'.$sef];
				if($company_description_language == ""){
					$company_description_language = $row->company_description;
					if($company_description_language != ""){
						$company = &JTable::getInstance('Companies','OspropertyTable');
						$company->id = $id;
						$company->user_id = $row->user_id;
						$company->{'company_description_'.$sef} = $company_description_language;
						$company->store();
					}
				}
			}
		}
		
		$company_alias = JRequest::getVar('company_alias','');
		$company_alias = OSPHelper::generateAlias('company',$id,$company_alias);
		$db->setQuery("Update #__osrs_companies set company_alias = '$company_alias' where id = '$id'");
		$db->query();
		
		if(intval($configClass['company_joomla_group_id']) > 0){
			$user_id = $row->user_id;
			$db->setQuery("Select count(user_id) from #__user_usergroup_map where user_id = '$user_id' and group_id = '".$configClass['company_joomla_group_id']."'");
			$count = $db->loadResult();
			if($count == 0){
				$db->setQuery("Insert into #__user_usergroup_map (user_id,group_id) values ('$user_id','".$configClass['company_joomla_group_id']."')");
				$db->query();
			}
		}
		
		$db->setQuery("Update #__osrs_agents set company_id = '0' where company_id = '$id'");
		$db->query();
		
		$users_selected  = JRequest::getVar('users_selected');
		if(count($users_selected) > 0){
			for($i=0;$i<count($users_selected);$i++){
				$db->setQuery("Update #__osrs_agents set company_id = '$id' where id = '".$users_selected[$i]."'");
				$db->query();
			}
		}
		
		//check company admin 
		$company_admin = $row->user_id;
		//is agent
		
		$db->setQuery("Select count(id) from #__osrs_agents where user_id = '$company_admin' and agent_type='0'");
		$count = $db->loadResult();
		$isagent = ($count > 0 ? 1:0);
			 
		//is admin of other company
		$db->setQuery("Select count(id) from #__osrs_companies where user_id = '$company_admin' and id <> '$id'");
		$count = $db->loadResult();
		$isdifferentcompany = ($count > 0 ? 1:0);
		
		if(($isagent == 1) or ($isdifferentcompany == 1)){
			$db->setQuery("Update #__osrs_companies set user_id = '0' where id = '$id'");
			$db->query();
			$msg = JText::_('OS_COMPANY_ADMIN_IS_NOT_ALLOWED');
			$mainframe->redirect("index.php?option=$option&task=companies_edit&cid[]=$id",$msg);
		}
		
		if($configClass['auto_approval_company_register_request'] == 0){
			if($row->request_to_approval == 0){ 
				//send the notification to user
				OspropertyEmail::sendActivateCompany($id);
			}
		}
		
		if($save == 1){
			// redirect list company
			$mainframe->redirect("index.php?option=$option&task=companies_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=$option&task=companies_edit&cid[]=$id",$msg);
		}
	}
	
	/**
	 * get state when change country
	 *
	 * @param unknown_type $option
	 */
	function companies_getstate($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$country_id = JRequest::getInt('country_id',0);
		$company_id = JRequest::getInt('company_id',0);
		
		if ($company_id){
			$db->setQuery("SELECT state FROM #__osrs_companies WHERE `id` = '$company_id' ");
			$select_state = $db->loadResult();
		}else{
			$select_state = null;
		}
		
		$option_state = array();
		$option_state[]= JHTML::_('select.option',0,' - '.JText::_('OS_SELECT_STATE').' - ');
		
		if ($country_id){
			$db->setQuery("SELECT id AS value, state_name AS text FROM #__osrs_states WHERE `country_id` = '$country_id' ORDER BY state_name");		
			$states = $db->loadObjectList();
			if (count($states)){
				$option_state = array_merge($option_state,$states);
			}
			$disable = '';
		}else{
			$disable = 'disabled="disabled"';
		}
		
		echo JHTML::_('select.genericlist',$option_state,'state','class="input-small" '.$disable,'value','text',$select_state);
		?>
		<font class="small_text"><?php echo JText::_('New state')?>:</font> <input type="text" name="nstate" id="nstate" size="10" class="input-small">
		<?php
	}
}
?>