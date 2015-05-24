<?php
/*------------------------------------------------------------------------
# email.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyEmail{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe,$languages;
		JHTML::_('behavior.modal');
		$languages = OSPHelper::getLanguages();
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));
		switch ($task){
			case "email_list":
				OspropertyEmail::email_list($option);
			break;
			case "email_unpublish":
				OspropertyEmail::email_change_publish($option,$cid,0);	
			break;
			case "email_publish":
				OspropertyEmail::email_change_publish($option,$cid,1);
			break;
			case "email_remove":
				OspropertyEmail::email_remove($option,$cid);
			break;
			case "email_edit":
				OspropertyEmail::email_edit($option,$cid[0]);
			break;
			case 'email_cancel':
				$mainframe->redirect("index.php?option=$option&task=email_list");
			break;	
			case "email_save":
				OspropertyEmail::email_save($option,1);
			break;
			case "email_apply":
				OspropertyEmail::email_save($option,0);
			break;
		}
	}
	
	/**
	 * email list
	 *
	 * @param unknown_type $option
	 */
	function email_list($option){
		global $mainframe;
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$lists = array();
		$condition = '';
		
		// filte sort
			$filter_order = JRequest::getVar('filter_order','id');
			$filter_order_Dir = JRequest::getVar('filter_order_Dir','');
			$order_by = " ORDER BY $filter_order $filter_order_Dir";
			$lists['order'] = $filter_order;
			$lists['order_Dir'] = $filter_order_Dir;
		
		// filter page
			$limit = JRequest::getVar('limit',20);
			$limitstart = JRequest::getVar('limitstart',0);
		
		// search 
			$keyword = JRequest::getVar('keyword','');
			if($keyword != ""){
				$condition .= " AND (";
				$condition .= " `email_key`  LIKE '%$keyword%'";
				$condition .= " OR `email_title` LIKE '%$keyword%'";
				$condition .= " OR `email_content` LIKE '%$keyword%'";
				$condition .= " )";
			}
			
		$count = "SELECT count(id) FROM #__osrs_emails WHERE 1=1";
		$count .= $condition;
		$db->setQuery($count);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		
		$list  = "SELECT * FROM #__osrs_emails "
				."\n WHERE 1=1 ";
		$list .= $condition;
		$list .= $order_by;
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		
		HTML_OspropertyEmail::email_list($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * publish or unpublish email
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function email_change_publish($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("UPDATE #__osrs_emails SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->redirect("index.php?option=$option&task=email_list");
	}
	
	/**
	 * remove email
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function email_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__osrs_emails WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->redirect("index.php?option=$option&task=email_list");
	}
	
	
	
	/**
	 * email Detail
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function email_edit($option,$id){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Email','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		
		// creat published
		//$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['published']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages); 	
		
		HTML_OspropertyEmail::editHTML($option,$row,$lists,$translatable);
	}
	
	/**
	 * save email
	 *
	 * @param unknown_type $option
	 */
	function email_save($option,$save){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$post = JRequest::get('post',JREQUEST_ALLOWRAW);
		$row = &JTable::getInstance('Email','OspropertyTable');
		$row->bind($post);		 
		//print_r($_POST);
		$email_content = $_POST['email_content'];
		$row->email_content = $email_content;
		foreach ($languages as $language){												
			$sef = $language->sef;
			$email_content_name    		= 'email_content_'.$sef;
			$email_content_value   		= $_POST[$email_content_name];
			$row->{$email_content_name} = $email_content_value;
		}
		
		//print_r($row);
		//die();
		$row->check();
		$msg = JText::_('OS_ITEM_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('ERROR_SAVING'); ;		 			 	
		 }
		$id = JRequest::getVar('id',0);
		if($id == 0){
			$id = $db->insertID();
		}
		if($save == 1){
			$mainframe->redirect("index.php?option=$option&task=email_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=$option&task=email_edit&cid[]=".$id,$msg);
		}
	}
	
	
	/**
	 * Send activated email
	 *
	 * @param unknown_type $option
	 * @param unknown_type $emailOpt
	 */
	function sendActivedEmail($option,$id,$email_type,$emailopt){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		
		$emailfrom = $configClass['general_bussiness_email'];
		$sitename  = $configClass['general_bussiness_name'];
		
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		
		$db->setQuery("Select * from #__osrs_properties where id = '$id'");
		$property = $db->loadObject();
		$agent_id = $property->agent_id;
		$db->setQuery("Select user_id from #__osrs_agents where id = '$agent_id'");
		$user_id = $db->loadResult();
		if($user_id > 0){
			$user_language = OSPHelper::getUserLanguage($user_id);
			$language_prefix = OSPHelper::getFieldSuffix($user_language);
			
			$db->setQuery("Select * from #__osrs_emails where email_key like '$email_type' and published = '1'");
			$email = $db->loadObject();
			if($email->id > 0){
				$subject = $email->{'email_title'.$language_prefix};
				$content = stripslashes($email->{'email_content'.$language_prefix});
				if(!OSPHelper::isEmptyMailContent($subject,$content)){
					$subject = $email->{'email_title'};
					$content = stripslashes($email->{'email_content'});
				}
				
				$subject = str_replace("{site_name}",$sitename,$subject);
				$message = $content;
				$message = str_replace("{username}",$emailopt['agentname'],$message);
				$message = str_replace("{link}",$emailopt['link'],$message);
				$message = str_replace("{listing}",$emailopt['property'],$message);
				$message = str_replace("{site_name}",$sitename,$message);
				$mailer  = JFactory::getMailer();
				$mailer->sendMail($emailfrom,$sitename,$emailopt['agentemail'],$subject,$message,1);
			}
		}
	}
	
	/**
	 * Send Agent activate email
	 *
	 * @param unknown_type $option
	 * @param unknown_type $emailOpt
	 */
	function sendAgentActiveEmail($option,$emailOpt){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		
		$emailfrom = $configClass['general_bussiness_email'];
		$sitename  = $configClass['general_bussiness_name'];
		
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		
		$db->setQuery("Select user_id from #__osrs_agents where id = '".$emailOpt['agentid']."'");
		$user_id = $db->loadResult();
		
		if($user_id > 0){
			$user_language = OSPHelper::getUserLanguage($user_id);
			$language_prefix = OSPHelper::getFieldSuffix($user_language);
			
			$db->setQuery("SELECT * FROM #__osrs_emails WHERE `email_key` LIKE 'approval_agent_request' AND published = '1'");
			$email = $db->loadObject();
			if($email->id > 0){
				$subject = $email->{'email_title'.$language_prefix};
				$content = stripslashes($email->{'email_content'.$language_prefix});
				if(!OSPHelper::isEmptyMailContent($subject,$content)){
					$subject = $email->{'email_title'};
					$content = stripslashes($email->{'email_content'});
				}
				$message = $content;
				$subject = str_replace("{site_name}",$sitename,$subject);
				$message = str_replace("{agent}",$emailOpt['agentname'],$message);
				$message = str_replace("{site_name}",$sitename,$message);
				$mailer  = JFactory::getMailer();
				$mailer->sendMail($emailfrom,$sitename,$emailOpt['agentemail'],$subject,$message,1);
			}
		}
	}
	
	/**
	 * Send the activate email to user who create the company profile
	 * In case field : auto_approval_company_register = 0;
	 *
	 * @param unknown_type $company_id
	 */
	function sendActivateCompany($company_id){
		global $mainframe,$configs,$configClass;
		$db = JFactory::getDbo();
		
		$db->setQuery("Select * from #__osrs_companies where id = '$company_id'");
		$company = $db->loadObject();
		
		$user_id = $company->user_id;
		
		if($user_id > 0){
			$user = JFactory::getUser($user_id);
			$user_language = OSPHelper::getUserLanguage($user_id);
			$language_prefix = OSPHelper::getFieldSuffix($user_language);
			
			$emailfrom = $configClass['general_bussiness_email'];
			if($emailfrom == ""){
				$config = new JConfig();
				$emailfrom = $config->mailfrom;
			}
			$sitename  = $configClass['general_bussiness_name'];
			$notify_email = $configClass['notify_email'];
			
			$db->setQuery("Select * from #__osrs_emails where email_key like 'your_company_has_been_approved' and published = '1'");
			$email = $db->loadObject();
			if($email->id > 0){
				$subject = $email->{'email_title'.$language_prefix};
				$message = stripslashes($email->{'email_content'.$language_prefix});
				if(!OSPHelper::isEmptyMailContent($subject,$message)){
					$subject = $email->{'email_title'};
					$message = stripslashes($email->{'email_content'});
				}
				$message = str_replace("{company_admin}",$user->name,$message);
				$message = str_replace("{company_name}",$company->company_name,$message);
				$link = "<a href='".JURI::root()."index.php?option=com_osproperty&task=company_edit'>".JURI::root()."index.php?option=com_osproperty&task=company_edit</a>";
				$message = str_replace("{company_edit_profile}",$link,$message);
				$mailer = JFactory::getMailer();
				if($company->email == ""){
					$company->email = $user->email;
				}
				$mailer->sendMail($emailfrom,$sitename,$company->email,$subject,$message,1);
			}
		}
	}
}
?>