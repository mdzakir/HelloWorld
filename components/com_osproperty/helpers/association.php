<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content Component Association Helper
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       3.0
 */
abstract class OspropertyHelperAssociation
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   integer  $id    Id of the item
	 * @param   string   $view  Name of the view
	 *
	 * @return  array   Array of associations for the item
	 *
	 * @since  3.0
	 */

	public static function getAssociations($id = 0, $view = null)
	{
		include_once(JPATH_COMPONENT.'/helpers/helper.php');
		$languages = OSPHelper::getAllLanguages();
		$current_language = JFactory::getLanguage();
		$current_language = $current_language->getTag();
		$default_language = OSPHelper::getDefaultLanguage();
		jimport('helper.route', JPATH_COMPONENT_SITE);

		$app = JFactory::getApplication();
		$jinput = $app->input;
		$task = is_null($task) ? $jinput->get('task') : $task;
		$id = empty($id) ? $jinput->getInt('id') : $id;
		$return = array();
		if ($task == 'property_details')
		{
			if ($id)
			{
				foreach($languages as $language){
					$langcode = $language->lang_code;
					$prefix = explode("-",$langcode);
					$prefix = '_'.$prefix[0];

					$needs = array();
					$needs[0] = "property_details";
					$needs[1] = $id;
					$needs[2] = $language->lang_code;
					$itemid   = OSPRoute::getItemid($needs);
					$return[$language->lang_code] = 'index.php?option=com_osproperty&task=property_details&id='.$id.'&l='.$prefix.'&Itemid='.$itemid;
				}
				return $return;
			}
		}
		return array();

	}
}
