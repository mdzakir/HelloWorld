<?php
/*------------------------------------------------------------------------
# upload.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyUpload{
	function display($option,$task){
		global $mainframe;
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$id = JRequest::getInt('id',0);
		if(((!HelperOspropertyCommon::isAgent()) and (!HelperOspropertyCommon::isCompanyAdmin())) or ($id == 0)){
			JError::raiseError( 500, JText::_('OS_YOU_DO_NOT_HAVE_PERMISION_TO_GO_TO_THIS_AREA'));	
		}
		if($id > 0){
			if((!HelperOspropertyCommon::isOwner($id)) and (HelperOspropertyCommon::isAgent())){
				JError::raiseError( 500, JText::_('OS_YOU_DO_NOT_HAVE_PERMISION_TO_GO_TO_THIS_AREA'));	
			}
			if((!HelperOspropertyCommon::isCompanyAdmin()) and (HelperOspropertyCommon::isCompanyOwner($id))){
				JError::raiseError( 500, JText::_('OS_YOU_DO_NOT_HAVE_PERMISION_TO_GO_TO_THIS_AREA'));	
			}
		}
		switch ($task){
			case "upload_ajaxupload":
				OspropertyUpload::ajaxUploadForm($id);
			break;
			case "upload_doajaxupload":
				OspropertyUpload::doAjaxUploadForm();
			break;
			case "upload_gotoproperty":
				$mainframe->redirect("index.php?option=com_osproperty&task=properties_edit&cid[]=".$id);
			break;
		}
	}
	
	function ajaxUploadForm($id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_properties where id = '$id'");
		$property = $db->loadObject();
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('var jg_filenamewithjs = true;');
	    // Load Fine Uploader resources
	    $document->addStyleSheet(JURI::root().'components/com_osproperty/js/ajaxupload/fineuploader.css');
	    $document->addScript(JURI::root().'components/com_osproperty/js/ajaxupload/js/fineuploader.js');
	    $post_max_size = @ini_get('post_max_size');
	    if(!empty($post_max_size))
	    {
	      $post_max_size   = OSPHelper::iniToBytes($post_max_size);
	      $chunkSize = (int) min(500000, (int)(0.8 * $post_max_size));
	    }
	    $upload_max_filesize = @ini_get('upload_max_filesize');
	    if(!empty($upload_max_filesize))
	    {
	      $upload_max_filesize = OSPHelper::iniToBytes($upload_max_filesize);
	      $fileSizeLimit = $upload_max_filesize;
	    }
	    HTML_OspropertyUpload::ajaxUploadForm($property,$chunkSize,$fileSizeLimit);
	}
	
	function doAjaxUploadForm(){
		$pid = JRequest::getInt('id',0);
		$result = array('error' => false);

	    require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/upload.php';
	
	    $uploader = new JoomUpload();
	    
		$image = $uploader->upload("ajax",$pid);
	    if($image === false)
	    {
		      if($error = $uploader->getError())
		      {
		        $result['error'] = $error;
		      }
		      else
		      {
		        $result['error'] = JText::_('OS_UPLOAD_ERROR_FILE_NOT_UPLOADED');
		      }
	    }
	    else
	    {
		      $result['success'] = true;
		      if(is_object($image))
		      {
		        $result['id'] = 1;
		        $result['imgtitle'] = $image->imgtitle;
		        $result['thumb_url'] = $image->url;
		      }
	    }
	
	    if($debug_output = $uploader->getDebugOutput())
	    {
	      $result['debug_output'] = $debug_output;
	    }
	
	    ob_clean();
	    echo json_encode($result);
	    exit();
	    
	    
	}
}
?>