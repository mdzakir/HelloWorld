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
// No direct access.
defined('_JEXEC') or die;

class OspropertyEmail{
	/**
	 * Display Email default class
	 *
	 */
	function display(){
		//nothing
	}
	
	/**
	 * Send email
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $email_key
	 */
	function sendEmail($pid,$email_key,$sendto){
		global $mainframe,$configClass;
		include_once(JPATH_ROOT."/components/com_osproperty/helpers/helper.php");
		$db = JFactory::getDbo();
		$notify_email = $configClass['notify_email'];
		
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		
		$auto_approval = $configs[12]->fieldvalue;
		$db->setQuery("Select * from #__osrs_properties where id = '$pid'");
		$property = $db->loadObject();
		
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		
		if($sendto == 0){
			$agent_id = $property->agent_id;
			$db->setQuery("Select user_id from #__osrs_agents where id = '$agent_id'");
			$user_id = $db->loadResult();
			$user_language = OSPHelper::getUserLanguage($user_id);
			$language_prefix = OSPHelper::getFieldSuffix($user_language);
			$db->setQuery("Select * from #__osrs_agents where user_id = '$user_id'");
			$agent = $db->loadObject();
			$emailto = $agent->email;
			$user = JFactory::getUser($user_id);
			if($emailto != ""){
				$emailto = $user->email;
			}
		}else{
			$emailto = $notify_email;
		}
		
		if($emailto != ""){
			$db->setQuery("Select * from #__osrs_emails where email_key like '$email_key' and published = '1'");
			$email = $db->loadObject();
			if($email->id > 0){
				$subject = $email->{'email_title'.$language_prefix};
				$content = stripslashes($email->{'email_content'.$language_prefix});
				if(!OSPHelper::isEmptyMailContent($subject,$content)){
					$subject = $email->{'email_title'};
					$content = stripslashes($email->{'email_content'});
				}
				
				
				$db->setQuery("Select name from #__osrs_agents where id = '$property->agent_id'");
				$agent_name = $db->loadResult();
				ob_start();
				OspropertyListing::propertyDetails($pid);
				$body = ob_get_contents();
				ob_end_clean();
				//replace details
				$content = str_replace("{property_details}",$body,$content);
				//replace customer
				$content = str_replace("{customer}",$agent_name,$content);
				
				//replace link
				$link = JURI::root()."administrator/index.php?option=com_osproperty&task=properties_edit&cid[]=".$pid;
				$content = str_replace("{link}",$link,$content);
				
				if($auto_approval == 0){
					$information = JText::_("OS_WE_WILL_CHECK_THE_PROPERTY_AS_SOON_AS_POSSIBLE");
				}else{
					$information = JText::_("OS_THE_PROPERTY_HAS_BEEN_PUBLISHED");
				}
				$content = str_replace("{information}",$information,$content);
				
				$site_name = $configClass['general_bussiness_name'];
				
				$content = str_replace("{site_name}",$site_name,$content);
				
				$detail_link = JURI::root()."index.php?option=com_osproperty&task=property_details&id=".$pid;
				$content = str_replace("{details_link}",$detail_link,$content);
				$mailer = JFactory::getMailer();
				$mailer->sendMail($emailfrom,$site_name,$emailto,$subject,$content,1);
			}
		}
	}
	
	/**
	 * Send contact form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $contact
	 */
	function sendContactEmail($option,$contact){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$language_prefix = OSPHelper::getFieldSuffix();
		
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		$sitename  = $configClass['general_bussiness_name'];
		
		$db->setQuery("Select * from #__osrs_emails where id = '12' and published = '1'");
		$email = $db->loadObject();
		
		if($email->id > 0){
			$subject = $email->{'email_title'.$language_prefix};
			$message = stripslashes($email->{'email_content'.$language_prefix});
			if(!OSPHelper::isEmptyMailContent($subject,$message)){
				$subject = $email->{'email_title'};
				$message = stripslashes($email->{'email_content'});
			}
			
			$subject = str_replace("{visitor_name}",$contact['author'],$subject);
			$subject = str_replace("{site_name}",$sitename,$subject);
			
			$message = str_replace("{visitor_name}",$contact['author'],$message);
			$message = str_replace("{site_name}",$sitename,$message);
			$message = str_replace("{subject}",$contact['title'],$message);
			$message = str_replace("{contact_email}",$contact['email'],$message);
			$message = str_replace("{message}",$contact['message'],$message);
			$message = str_replace("{site_url}",JURI::root(),$message);
			$message = str_replace("{received_name}",$contact['receiver'],$message);
			$mailer = JFactory::getMailer();
			$mailer->sendMail($emailfrom,$sitename,$contact['emailto'],$subject,$message,1);
		}
	}
	
	/**
	 * Send email to friend
	 *
	 * @param unknown_type $option
	 * @param unknown_type $emailopt
	 */
	function sendFriendEmail($option,$emailopt){
		global $configClass;
		$db = JFactory::getDbo();
		$language_prefix = OSPHelper::getFieldSuffix();
		
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		$sitename  = $configClass['general_bussiness_name'];
		
		$db->setQuery("Select * from #__osrs_emails where id = '8' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			$subject = $email->{'email_title'.$language_prefix};
			$message = stripslashes($email->{'email_content'.$language_prefix});
			if(!OSPHelper::isEmptyMailContent($subject,$message)){
				$subject = $email->{'email_title'};
				$message = stripslashes($email->{'email_content'});
			}

			$message = str_replace("{friend_name}",$emailopt['friend_name'],$message);
			$message = str_replace("{name}",$emailopt['your_name'],$message);
			$message = str_replace("{link}",$emailopt['link'],$message);
			$message = str_replace("{message}",$emailopt['message'],$message);
			$message = str_replace("{site_name}",$emailopt['site_name'],$message);
			$mailer = JFactory::getMailer();
			$mailer->sendMail($emailfrom,$sitename,$emailopt['friend_email'],$subject,$message,1);
		}
	}
	
	
	/**
	 * Send comment email
	 *
	 * @param unknown_type $option
	 * @param unknown_type $emailopt
	 */
	function sendCommentEmail($option,$emailopt){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$language_prefix = OSPHelper::getFieldSuffix();
		
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		$sitename  = $configClass['general_bussiness_name'];
		
		$db->setQuery("Select * from #__osrs_emails where id = '5' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			$subject = $email->{'email_title'.$language_prefix};
			$message = stripslashes($email->{'email_content'.$language_prefix});
			if(!OSPHelper::isEmptyMailContent($subject,$message)){
				$subject = $email->{'email_title'};
				$message = stripslashes($email->{'email_content'});
			}
			
			
			$message = str_replace("{username}",$emailopt['agentname'],$message);
			$message = str_replace("{author}",$emailopt['author'],$message);
			$message = str_replace("{title}",$emailopt['title'],$message);
			$message = str_replace("{message}",$emailopt['message'],$message);
			$message = str_replace("{rate}",$emailopt['rate'],$message);
			$message = str_replace("{link}",$emailopt['link'],$message);
			$message = str_replace("{site_name}",$sitename,$message);
			$mailer = JFactory::getMailer();
			$mailer->sendMail($emailfrom,$sitename,$emailopt['agentemail'],$subject,$message,1);
		}
	}
	
	
	function sendAdministratorCommentEmail($option,$emailopt){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$language_prefix = OSPHelper::getFieldSuffix();
		
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		$sitename  = $configClass['general_bussiness_name'];
		$db->setQuery("Select * from #__osrs_emails where id = '19' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			$subject = $email->{'email_title'.$language_prefix};
			$message = stripslashes($email->{'email_content'.$language_prefix});
			if(!OSPHelper::isEmptyMailContent($subject,$message)){
				$subject = $email->{'email_title'};
				$message = stripslashes($email->{'email_content'});
			}
			
			$message = str_replace("{listing}",$emailopt['pro_name'],$message);
			$message = str_replace("{author}",$emailopt['author'],$message);
			$message = str_replace("{title}",$emailopt['title'],$message);
			$message = str_replace("{message}",$emailopt['message'],$message);
			$message = str_replace("{rate}",$emailopt['rate'],$message);
			$message = str_replace("{link}",$emailopt['link'],$message);
			$message = str_replace("{site_name}",$sitename,$message);
			$mailer = JFactory::getMailer();
			$mailer->sendMail($emailfrom,$sitename,$configClass['notify_email'],$subject,$message,1);
		}
	}
	
	
	/**
	 * Send Approximates Email
	 *
	 * @param unknown_type $option
	 * @param unknown_type $pid
	 */
	function sendApproximatesEmail($option,$pid){
		global $mainframe,$configClass;
		include_once(JPATH_ROOT."/components/com_osproperty/helpers/helper.php");
		$db = JFactory::getDbo();
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		$sitename  = $configClass['general_bussiness_name'];
		
		$db->setQuery("Select * from #__osrs_emails where id = '13' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			$db->setQuery("Select a.id,a.user_id,a.name,a.email from #__osrs_agents as a inner join #__osrs_properties as b on b.agent_id = a.id where b.id = '$pid'");
			$agent = $db->loadObject();
			$agentname 	= $agent->name;
			$agentemail = $agent->email;
			$agentid	= $agent->id;
			$user_id 	= $agent->user_id;
			
			if($user_id > 0){
				$user_language = OSPHelper::getUserLanguage($user_id);
				$language_prefix = OSPHelper::getFieldSuffix($user_language);
			
				$subject = $email->{'email_title'.$language_prefix};
				$message = stripslashes($email->{'email_content'.$language_prefix});
				if(!OSPHelper::isEmptyMailContent($subject,$message)){
					$subject = $email->{'email_title'};
					$message = stripslashes($email->{'email_content'});
				}
				
				$days = $configs[92]->fieldvalue;
				$db->setQuery("Select * from #__osrs_expired where pid = '$pid'");
				$expired = $db->loadObject();
				
				$details_link = "<a href='".JURI::root()."index.php?option=com_osproperty&task=property_details&id=$pid'>".JURI::root()."index.php?option=com_osproperty&task=property_details&id=$pid</a>";
				
				$update_link = JURI::root()."index.php?option=com_ospoprerty&task=agent_editprofile";
				$update_link = "<a href='".$update_link."'>".JText::_('OS_UPDATE_LINK')."</a>";
				
				$message = str_replace("{username}",$agentname,$message);
				$message = str_replace("{update_link}",$update_link,$message);
				$message = str_replace("{details_link}",$details_link,$message);
				$message = str_replace("{days}",$days,$message);
				$message = str_replace("{expire_date}",HelperOspropertyCommon::loadTime($expired->expired_time,2),$message);
				$message = str_replace("{site_name}",$sitename,$message);
				$mailer = JFactory::getMailer();
				if($mailer->sendMail($emailfrom,$sitename,$agentemail,$subject,$message,1)){
					//after send email, update the send inform
					$db->setQuery("Update #__osrs_expired set send_inform = '1' where pid = '$pid'");
					$db->query();
					$db->setQuery("Delete from #__osrs_queue where id = '$row->id'");
					$db->query();
				}
			}
		}
	}
	
	/**
	 * Send expired email
	 *
	 * @param unknown_type $option
	 * @param unknown_type $pid
	 */
	function sendExpiredEmail($option,$pid){
		global $mainframe,$configClass;
		include_once(JPATH_ROOT."/components/com_osproperty/helpers/helper.php");
		$db = JFactory::getDbo();
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		$sitename  = $configClass['general_bussiness_name'];
		
		$db->setQuery("Select * from #__osrs_emails where id = '4' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			
			$db->setQuery("Select a.id, a.user_id,a.name,a.email,b.pro_name from #__osrs_agents as a inner join #__osrs_properties as b on b.agent_id = a.id where b.id = '$pid'");
			$agent = $db->loadObject();
			$agentname 	= $agent->name;
			$agentid	= $agent->id;
			$user_id	= $agent->user_id;
			$agentemail = $agent->email;
			$property 	= $agent->pro_name;
					
			if($user_id > 0){
				$user_language = OSPHelper::getUserLanguage($user_id);
				$language_prefix = OSPHelper::getFieldSuffix($user_language);
			
				$subject = $email->{'email_title'.$language_prefix};
				$message = stripslashes($email->{'email_content'.$language_prefix});
				if(!OSPHelper::isEmptyMailContent($subject,$message)){
					$subject = $email->{'email_title'};
					$message = stripslashes($email->{'email_content'});
				}
				
				
				$details_link = "<a href='".JURI::root()."index.php?option=com_osproperty&task=property_details&id=$pid'>".JURI::root()."index.php?option=com_osproperty&task=property_details&id=$pid</a>";
				
				$link = JURI::root()."index.php?option=com_osproperty&task=agent_editprofile";
				$update_link = "<a href='".$link."'>".JText::_('OS_UPDATE_LINK')."</a>";
				
				$message = str_replace("{username}",$agentname,$message);
				$message = str_replace("{update_link}",$update_link,$message);
				$message = str_replace("{details_link}",$details_link,$message);
				$message = str_replace("{site_name}",$sitename,$message);
				$message = str_replace("{listing}",$property,$message);
				$mailer = JFactory::getMailer();
				if($mailer->sendMail($emailfrom,$sitename,$agentemail,$subject,$message,1)){
					$db->setQuery("Update #__osrs_expired set send_expired = '1' where pid = '$pid'");
					$db->query();
					$db->setQuery("Delete from #__osrs_queue where id = '$row->id'");
					$db->query();
				}
			}
		}
	}
	
	
	/**
	 * Send expired feature email
	 *
	 * @param unknown_type $option
	 * @param unknown_type $pid
	 */
	function sendExpiredFeatureEmail($option,$pid){
		global $mainframe,$configClass;
		include_once(JPATH_ROOT."/components/com_osproperty/helpers/helper.php");
		$db = JFactory::getDbo();
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		$sitename  = $configClass['general_bussiness_name'];
		
		$db->setQuery("Select * from #__osrs_emails where id = '9' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			//$subject = $email->email_title;
			//$message = stripslashes(nl2br($email->email_content));
			$db->setQuery("Select a.id,a.user_id, a.name,a.email,b.pro_name from #__osrs_agents as a inner join #__osrs_properties as b on b.agent_id = a.id where b.id = '$pid'");
			$agent 		= $db->loadObject();
			$agentid 	= $agent->id;
			$agentname 	= $agent->name;
			$agentemail = $agent->email;
			$user_id	= $agent->user_id;
			$property = $agent->pro_name;
			
			if($user_id > 0){
				$user_language = OSPHelper::getUserLanguage($user_id);
				$language_prefix = OSPHelper::getFieldSuffix($user_language);
			
				$subject = $email->{'email_title'.$language_prefix};
				$message = stripslashes($email->{'email_content'.$language_prefix});
				if(!OSPHelper::isEmptyMailContent($subject,$message)){
					$subject = $email->{'email_title'};
					$message = stripslashes($email->{'email_content'});
				}
				
				$details_link = "<a href='".JURI::root()."index.php?option=com_osproperty&task=property_details&id=$pid'>".JURI::root()."index.php?option=com_osproperty&task=property_details&id=$pid</a>";
				
				$link = JURI::root()."index.php?option=com_osproperty&task=agent_editprofile";
				$update_link = "<a href='".$link."'>".JText::_('OS_UPDATE_LINK')."</a>";
				
				$message = str_replace("{username}",$agentname,$message);
				$message = str_replace("{update_link}",$update_link,$message);
				$message = str_replace("{details_link}",$details_link,$message);
				$message = str_replace("{site_name}",$sitename,$message);
				$message = str_replace("{listing}",$property,$message);
				$mailer = JFactory::getMailer();
				if($mailer->sendMail($emailfrom,$sitename,$agentemail,$subject,$message,1)){
					$db->setQuery("Update #__osrs_expired set send_featured = '1' where pid = '$pid'");
					$db->query();
					$db->setQuery("Delete from #__osrs_queue where id = '$row->id'");
					$db->query();
				}
			}
		}
	}
	
	/**
	 * Send Payment Complete Information
	 *
	 * @param unknown_type $option
	 * @param unknown_type $order
	 * @param unknown_type $items
	 * @param unknown_type $coupon
	 */
	function sendPaymentCompleteEmail($option,$order,$items,$coupon){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		$emailfrom = $configClass['general_bussiness_email'];
		if($emailfrom == ""){
			$config = new JConfig();
			$emailfrom = $config->mailfrom;
		}
		$sitename  = $configClass['general_bussiness_name'];
		
		$db->setQuery("Select * from #__osrs_emails where id = '1' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			$subject = $email->email_title;
			$message = stripslashes($email->email_content);
			
			$agent_id = $order->agent_id;
			$db->setQuery("Select * from #__osrs_agents where id = '$agent_id'");
			$agent = $db->loadObject();
			$agentname  = $agent->name;
			$agentemail = $agent->email;
			
			$message = str_replace("{username}",$agentname,$message);
			$message = str_replace("{gateway}",JText::_('Paypal'),$message);
			$message = str_replace("{txn}",$order->transaction_id,$message);
			$message = str_replace("{item}",JText::_('Upgrade features for')." ".$order->items,$message);
			$message = str_replace("{price}",OSPHelper::generatePrice($configClass['general_currency_default'],$order->price),$message);
			$message = str_replace("{date}",HelperOspropertyCommon::loadTime($order->created_on,2),$message);
			$message = str_replace("{site_name}",$sitename,$message);
			$mailer = JFactory::getMailer();
			$mailer->sendMail($emailfrom,$sitename,$agentemail,$subject,$message,1);
		}
	}
	
	/**
	 * Send agent approval request
	 *
	 * @param unknown_type $option
	 * @param unknown_type $emailOpt
	 */
	function sendAgentApprovalRequest($option,$emailOpt){
		global $mainframe,$configs,$configClass;
		$db = JFactory::getDbo();
		$emailfrom = $configClass['general_bussiness_email'];
		$sitename  = $configClass['general_bussiness_name'];
		$notify_email = $configClass['notify_email'];
		$db->setQuery("Select * from #__osrs_emails where id = '14' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			$subject = $email->email_title;
			$message = stripslashes($email->email_content);
			$message = str_replace("{customer}",$emailOpt[0]->customer,$message);
			$link = "<a href='".JURI::root()."administrator/index.php?option=com_osproperty&task=agent_edit&cid[]=".$emailOpt[0]->agent_id."'>".JURI::root()."administrator/index.php?option=com_osproperty&task=agent_edit&cid[]=".$emailOpt[0]->agent_id."</a>";
			$message = str_replace("{link}",$link,$message);
			$mailer = JFactory::getMailer();
			$mailer->sendMail($emailfrom,$sitename,$notify_email,$subject,$message,1);
		}
	}
	
	/**
	 * Send properties approval request
	 *
	 * @param unknown_type $option
	 * @param unknown_type $emailOpt
	 */
	function sendPropertyApprovalRequest($option,$emailOpt){
		global $mainframe,$configs,$configClass;
		$db = JFactory::getDbo();
		$emailfrom = $configClass['general_bussiness_email'];
		$sitename  = $configClass['general_bussiness_name'];
		$notify_email = $configClass['notify_email'];
		$db->setQuery("Select * from #__osrs_emails where id = '15' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			$subject = $email->email_title;
			$message = stripslashes($email->email_content);
			$message = str_replace("{customer}",$emailOpt[0]->customer,$message);
			$message = str_replace("{property}",$emailOpt[0]->property,$message);
			$link = "<a href='".JURI::root()."administrator/index.php?option=com_osproperty&task=properties_list'>".JURI::root()."administrator/index.php?option=com_osproperty&task=properties_list</a>";
			$message = str_replace("{link}",$link,$message);
			$mailer = JFactory::getMailer();
			$mailer->sendMail($emailfrom,$sitename,$notify_email,$subject,$message,1);
		}
	}
	
	/**
	 * Send the notification to administrator when user create new company at frontend of OS Property
	 * In case field : auto_approval_company_register = 0;
	 *
	 * @param unknown_type $company_id
	 */
	function sendCompanyRequestApproval($company_id){
		global $mainframe,$configs,$configClass;
		$db = JFactory::getDbo();
		
		$db->setQuery("Select * from #__osrs_companies where id = '$company_id'");
		$company = $db->loadObject();
		
		$user_id = $company->user_id;
		$user = JFactory::getUser($user_id);
		
		$emailfrom = $configClass['general_bussiness_email'];
		$sitename  = $configClass['general_bussiness_name'];
		$notify_email = $configClass['notify_email'];
		
		$db->setQuery("Select * from #__osrs_emails where email_key like 'new_company_registration' and published = '1'");
		$email = $db->loadObject();
		if($email->id > 0){
			$subject = $email->email_title;
			$message = stripslashes($email->email_content);
			$message = str_replace("{user}",$user->name,$message);
			$message = str_replace("{company_name}",$company->company_name,$message);
			$link = "<a href='".JURI::root()."administrator/index.php?option=com_osproperty&task=companies_edit&cid[]=".$company_id."'>".JURI::root()."administrator/index.php?option=com_osproperty&task=companies_edit&cid[]=".$company_id."</a>";
			$message = str_replace("{company_backend_url}",$link,$message);
			$mailer = JFactory::getMailer();
			$mailer->sendMail($emailfrom,$sitename,$notify_email,$subject,$message,1);
		}
	}
	
	
}


?>