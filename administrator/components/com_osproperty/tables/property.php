<?php
/*------------------------------------------------------------------------
# property.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

/**
 * Banner table
 *
 * @package		Joomla.Administrator
 * @subpackage	com_osproperty
 * @since		1.5
 */
class OspropertyTableProperty extends JTable
{
	var $id = null;
	var $ref = null;
	var $pro_name = null;
	var $pro_alias = null;
	var $agent_id = null;
	var $category_id = null;
	var $price = null;
	var $price_original = null;
	var $curr = null;
	var $pro_small_desc = null;
	var $pro_full_desc = null;
	var $pro_type = null;
	var $isFeatured = null;
	var $isSold = null;
	var $soldOn = null;
	var $note = null;
	var $lat_add = null;
	var $long_add = null;
	var $gbase_address = null;
	var $price_call = null;
	var $gbase_url = null;
	var $pro_video = null;
	var $address = null;
	var $city = null;
	var $state = null;
	var $country = null;
	var $region = null;
	var $province = null;
	var $postcode = null;
	var $pro_pdf = null;
	var $pro_pdf_file = null;
	var $bed_room = null;
	var $bath_room = null;
	var $rooms = null;
	var $parking = null;
	var $energy = null;
	var $climate = null;
	var $show_address = null;
	var $rent_time = null;
	var $square_feet = null;
	var $lot_size = null;
	var $number_of_floors = null;
	var $hits = null;
	var $number_votes = null;
	var $total_points = null;
	var $metadesc = null;
	var $metakey = null;
	var $created = null;
	var $created_by = null;
	var $modified = null;
	var $modified_by = null;
	var $access = null;
	var $publish_up = null;
	var $publish_down = null;
	var $remove_date = null;
	var $published = null;
	var $approved = null;
	var $request_to_approval = null; //in the case after expired time, the property has been unapproved, agent can request to approved the property. 
	var $request_featured = null; //0 : normal; 1: new property ->feature; 2: old property -> feature
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	
	function __construct(&$_db)
	{
		parent::__construct('#__osrs_properties', 'id', $_db);
	}
}