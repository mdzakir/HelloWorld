<?php
/**
 * ------------------------------------------------------------------------
 * ospropertyplg.php Ossolution Osproperty System plugin for Joomla 1.7
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 Ossolution Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: Ossolution Co., Ltd
 * Websites: http://www.joomdonation.com
 * ------------------------------------------------------------------------
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.application.module.helper');

/**
 * plgSystemOsProperty class
 *
 * @package Osproperty
 */
class plgSystemOspropertyplg extends JPlugin
{
    var $plugin = null;

    /**
     * Implement after render event
     *
     * @return null
     */
    function onAfterInitialise ()
    {
    	global $mainframe,$configClass;
        $mainframe = JFactory::getApplication();
		//define('DS', DIRECTORY_SEPARATOR);
        if(!$mainframe->isAdmin()){
        	require_once(JPATH_ROOT."/components/com_osproperty/classes/cron.php");
			require_once(JPATH_ROOT."/components/com_osproperty/classes/email.php");
			require_once(JPATH_ROOT."/components/com_osproperty/helpers/common.php");
	        $db  = JFactory::getDBO();
	        $db->setQuery("Select * from #__osrs_configuration");
			$configs = $db->loadObjectList();
			foreach ($configs as $config) {
				$configClass[$config->fieldname] = $config->fieldvalue;
			}
			if($configClass['general_use_expiration_management'] == 1){
				$db->setQuery("Select runtime from #__osrs_lastcron");
				$lastcron = $db->loadResult();
				$lastcron = intval($lastcron);
				
				$current_time = time();
				
				//run every hour
				if($lastcron < $current_time - 3600){
					OspropertyCron::checklist();
				}
			}
        }
    }
}