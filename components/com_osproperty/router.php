<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OS Property
 * @author  	Dang Thuc Dam
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
error_reporting(E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR);
/**
 *
 * Build the route for the com_osproperty component
 * @param	array	An array of URL arguments
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 * @since	1.5
 */
function OspropertyBuildRoute(&$query)
{
    $db = JFactory::getDbo();
    $segments = array();
    require_once JPATH_ROOT . '/components/com_osproperty/helpers/helper.php';
    $db = JFactory::getDbo();
    $queryArr = $query;
    if (isset($queryArr['option']))
        unset($queryArr['option']);
    if (isset($queryArr['Itemid']))
        unset($queryArr['Itemid']);
    //Store the query string to use in the parseRouter method
    $queryString = http_build_query($queryArr);
    $app		= JFactory::getApplication();
    $menu		= $app->getMenu();

    //We need a menu item.  Either the one specified in the query, or the current active one if none specified
    if (empty($query['Itemid']))
        $menuItem = $menu->getActive();
    else
        $menuItem = $menu->getItem($query['Itemid']);

    if (empty($menuItem->query['view']))
    {
        $menuItem->query['view'] = '';
    }
    //Are we dealing with an article or category that is attached to a menu item?
    //if (($menuItem instanceof stdClass) && isset($query['view']) && isset($query['id']) && $menuItem->query['view'] == $query['view'] && isset($query['id']) && $menuItem->query['id'] == intval($query['id']))
    //{
    //unset($query['view']);
    //if (isset($query['catid']))
    //	unset($query['catid']);
    //unset($query['id']);
    //}
    /*
    if (($menuItem instanceof stdClass) && $menuItem->query['task'] == 'property_type' && isset($query['category_id']) && $menuItem->query['category_id'] == intval($query['catid']))
    {
        if (isset($query['category_id']))
            unset($query['category_id']);

        if (isset($query['type_id']))
            unset($query['type_id']);

        if (isset($query['country_id']))
            unset($query['country_id']);

        if (isset($query['company_id']))
            unset($query['company_id']);

    }
    */

    $view = isset($query['view']) ? $query['view'] : '';
    $id = 	isset($query['id']) ? (int) $query['id'] : 0;
    $task = isset($query['task']) ? $query['task'] : '';

    if($task == ""){
        switch ($view)
        {
            case "lcategory":
                $task = "category_listing";
                break;
            case "lagents":
                $task = "agent_layout";
                break;
            case "lcompanies":
                $task = "company_listing";
                break;
            case "ldefault":
                $task = "default_page";
                break;
            case "lsearch":
                $task = "locator_search";
                break;
            case "aaddproperty":
                $task = "property_new";
                break;
            case "aeditdetails":
                $task = "agent_default";
                break;
            case "rfavoriteproperties":
                $task = "property_favorites";
                break;
            case "ltype":
                $task = "property_type";
                break;
            case "lcity":
                $task = "property_city";
                break;
            case "ccompanydetails":
                $task = "company_edit";
                break;
            case "ladvsearch":
                $task = "property_advsearch";
                break;
            case "rsearchlist":
                $task = "property_searchlist";
                break;
            case "aagentregistration":
                $task = "agent_register";
                break;
            case "rcompare":
                $task = "compare_properties";
                break;
            case "ccompanyregistration":
                $task = "company_register";
                break;
        }
    }
    switch ($task){
        //category
        case "category_listing":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[] = JText::_('OS_LIST_CATEGORIES');
                unset($query['Itemid']);
            }
            break;
        case "category_details":
            $id = $query['id'];
            $db->setQuery("Select * from #__osrs_categories where id = '$id' ");
            $category = $db->loadObject();
            $category_alias = OSPHelper::getLanguageFieldValue($category,'category_alias');
            $category_name  = OSPHelper::getLanguageFieldValue($category,'category_name');
            if($category_alias != ""){
                $segments[] = $category_alias;
            }else{
                $segments[] = $category_name;
            }

            unset($query['id']);
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;

        //company
        case "company_register":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[] = JText::_('OS_COMPANY_REGISTRATION');
                unset($query['Itemid']);
            }
            break;
        case "company_listing":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_COMPANIES_LISTING');
                unset($query['Itemid']);
            }
            break;
        case "company_info":
            $id = $query['id'];
            $db->setQuery("Select id, company_alias, company_name from #__osrs_companies where id = '$id' ");
            $company = $db->loadObject();
            $company_alias = $company->company_alias;
            $company_name = $company->company_name;
            if($company_alias != ""){
                $segments[] = $company_alias;
            }else{
                $segments[] = $company_name;
            }

            unset($query['id']);
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;
        case "company_listproperties":
            $id = $query['id'];
            $db->setQuery("Select id, company_alias, company_name from #__osrs_companies where id = '$id' ");
            $company = $db->loadObject();
            $company_alias = $company->company_alias;
            $company_name = $company->company_name;
            if($company_alias != ""){
                $company_name = $company_alias;
            }
            $segments[] = JText::_('OS_LIST_PROPERTIES')."-".$company_name;
            unset($query['id']);
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;
        case "company_edit":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_EDIT_COMPANY');
                unset($query['Itemid']);
            }
            break;

        case "company_agent":
            $segments[]	= JText::_('OS_MANAGE_AGENTS');
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;

        case "company_addnew":
            $segments[]	= JText::_('OS_ASSIGN_AGENTS_TO_COMPANY');
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;

        case "company_properties":
            $segments[]	= JText::_('OS_MANAGE_PROPERTIES');
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;

        case "company_plans":
            $segments[]	= JText::_('OS_MY_PLANS');
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;

        case "company_addagents":
            $segments[]	= JText::_('OS_ADD_AGENT');
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;
        case "company_editagent":
            $segments[] = JText::_('OS_MODIFY_AGENT');
            $id = $query['id'];
            $db->setQuery("Select id, alias, name from #__osrs_agents where id = '$id' ");
            $agent = $db->loadObject();
            $agent_alias = $agent->alias;
            $agent_name = $agent->name;
            if($agent_alias != ""){
                $segments[] = $agent_alias;
            }else{
                $segments[] = $agent_name;
            }

            unset($query['id']);
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;
        //agent
        case "agent_default":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_EDIT_MY_PROFILE');
                unset($query['Itemid']);
            }
            break;
        case "agent_layout":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_LIST_AGENTS');
                unset($query['Itemid']);
            }
            break;
        case "agent_register":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_AGENT_REGISTER');
                unset($query['Itemid']);
            }
            break;
        case "agent_info":
            $id = $query['id'];
            $db->setQuery("Select id, alias, name from #__osrs_agents where id = '$id' ");
            $agent = $db->loadObject();
            $agent_alias = $agent->alias;
            $agent_name = $agent->name;
            if($agent_alias != ""){
                $segments[] = $agent_alias;
            }else{
                $segments[] = $agent_name;
            }

            unset($query['id']);
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;
        case "agent_editprofile":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_EDIT_PROFILE');
                unset($query['Itemid']);
            }
            break;
        case "agent_completeregistration":
            $id = $query['id'];
            $segments[]	= JText::_('OS_COMPLETE_REGISTRATION');
            unset($query['id']);
            break;
        //property
        case "property_new":
            $segments[]	= JText::_('OS_ADD_PRO');
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;
        case "property_details":
            include_once(JPATH_ROOT.'/components/com_osproperty/helpers/helper.php');
            $configClass = OSPHelper::loadConfig();
            $default_language = OSPHelper::getDefaultLanguage();
            $default_language = explode("-",$default_language);
            $default_language = $default_language[0];
            $default_language = "_".$default_language;
            $lang = isset($query['l']) ? $query['l'] : "";
            $id = $query['id'];
            if($lang != ""){
                if($default_language == $lang){
                    $db->setQuery("Select ref,pro_name,pro_alias from #__osrs_properties where id = '$id'");
                }else{
                    $db->setQuery("Select ref,pro_name$lang as pro_name,pro_alias$lang as pro_alias from #__osrs_properties where id = '$id'");
                }
                $property = $db->loadObject();
                $pro_alias  = $property->pro_alias;
                $pro_name   = $property->name;
                if(($pro_alias == "") AND ($pro_name == "")){
                    $db->setQuery("Select * from #__osrs_properties where id = '$id'");
                    $property   = $db->loadObject();
                    $pro_alias  = OSPHelper::getLanguageFieldValue($property,'pro_alias');
                    $pro_name   = OSPHelper::getLanguageFieldValue($property,'pro_name');
                }
            }else{
                $db->setQuery("Select * from #__osrs_properties where id = '$id'");
                $property   = $db->loadObject();
                $pro_alias  = OSPHelper::getLanguageFieldValue($property,'pro_alias');
                $pro_name   = OSPHelper::getLanguageFieldValue($property,'pro_name');
            }
            unset($query['l']);

            if($pro_alias != ""){
                $segs = $pro_alias;
            }else{
                $segs = $pro_name;
            }
            if($configClass['sef_configure'] == 1){
                $segs = $property->ref." ".$segs;
            }elseif($configClass['sef_configure'] == 2){
                $segs = $property->ref." ".$segs." ".$id;
            }
            $segments[] = $segs;
            unset($query['id']);
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;
        case "property_city":
            $id = $query['id'];
            if (!isset($query['Itemid'])){
                $db->setQuery("Select id, city from #__osrs_cities where id = '$id'");
                $city = $db->loadObject();
                $segments[] = $city->city;
            }
            unset($query['id']);
            break;
        case "property_favorites":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_MY_FAVORITES');
                unset($query['Itemid']);
            }
            break;
        case "property_tag":
            if(isset($query['tag_id'])){
                $db = JFactory::getDbo();
                $db->setQuery("Select keyword from #__osrs_tags where id = '".$query['tag_id']."'");
                $keyword = $db->loadResult();
                $segments[] = JText::_('OS_LIST_PROPERTIES_BY_TAG').' '.$keyword;
            }
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            unset($query['tag_id']);
            break;
        case "property_listing":
        case "property_type":
            //print_r($query);
            $segmentArr = array();
            $allproperty = 1;
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                if((isset($query['catIds'])) and (is_array($query['catIds'])) and (count($query['catIds']) > 0)){
                    $allproperty = 0;
                    $db->setQuery("Select id,category_alias,category_name from #__osrs_categories where id in (".implode(",",$query['catIds']).")");
                    $categories = $db->loadObjectList();
                    foreach ($categories as $category){
                        if($category->category_alias != ""){
                            $category_alias[] = $category->category_alias;
                        }else{
                            $category_alias[] = $category->category_name;
                        }
                    }
                    if(count($category_alias) > 0){
                        $segmentArr[] = JText::_('OS_CATEGORY')."_".implode("-",$category_alias);
                    }
                    unset($query['catIds']);
                }
                if(isset($query['type_id'])){
                    $allproperty = 0;
                    $db->setQuery("Select id,type_alias,type_name from #__osrs_types where id = '".$query['type_id']."'");
                    $type = $db->loadObject();
                    $type_alias = $type->type_alias;
                    $type_name = $type->type_name;
                    if($type_alias != ""){
                        $segmentArr[] = JText::_('OS_TYPE')."_".$type_alias;
                    }else{
                        $segmentArr[] = JText::_('OS_TYPE')."_".$type_name;
                    }
                    unset($query['type_id']);
                }
            }
            if (!isset($query['Itemid']) or ($query['Itemid'] > 0) or ($query['Itemid'] != 99999) or ($query['Itemid'] != 9999)){
                if((isset($query['catIds'])) and (is_array($query['catIds'])) and (count($query['catIds']) > 0)){
                    $allproperty = 0;
                }
                if(isset($query['type_id'])){
                    $allproperty = 0;
                }
                if(isset($query['country_id'])){
                    $allproperty = 0;
                }
                if(isset($query['company_id'])){
                    $allproperty = 0;
                }
            }
            if(isset($query['company_id'])){
                $allproperty = 0;
                $db->setQuery("Select id, company_alias,company_name from #__osrs_companies where id = '".$query['company_id']."'");
                $company = $db->loadObject();
                $company_alias =$company->company_alias;
                $company_name = $company->company_name;
                if($company_alias != ""){
                    $segmentArr[] = JText::_('OS_COMPANY')."_".$company_alias;
                }else{
                    $segmentArr[] = JText::_('OS_COMPANY')."_".$company_name;
                }
                unset($query['company_id']);
            }
            if(isset($query['country_id'])){
                $allproperty = 0;
                $db->setQuery("Select id, country_name from #__osrs_countries where id = '".$query['country_id']."'");
                $country = $db->loadObject();
                $segmentArr[] = JText::_('OS_COUNTRY')."_".$country->country_name;
                unset($query['country_id']);
            }
            if(isset($query['state_id'])){
                $allproperty = 0;
                $db->setQuery("Select id, state_name from #__osrs_states where id = '".$query['state_id']."'");
                $state = $db->loadObject();
                $segmentArr[] = JText::_('OS_STATE')."_".$state->state_name;
                unset($query['state_id']);
            }
            if(isset($query['city'])){
                $allproperty = 0;
                $db->setQuery("Select id, city from #__osrs_cities where id = '".$query['city']."'");
                $city = $db->loadObject();
                $segmentArr[] = JText::_('OS_CITY')."_".$city->city;
                unset($query['city']);
            }
            if($allproperty == 1){
                $segments[] = JText::_('OS_ALL_PROPERTIES');
            }else{
                $segments[] = implode(" ".JText::_('OS_AND')." ",$segmentArr);
            }
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            unset($query['type_id']);
            unset($query['catIds']);
            unset($query['category']);
            unset($query['company_id']);
            unset($query['country_id']);
            unset($query['state_id']);
            unset($query['city']);
            break;
        //advanced search
        case "property_advsearch":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_ADV_SEARCH');
                unset($query['Itemid']);
            }
            if(isset($query['adv_type'])){
                $db->setQuery("Select * from #__osrs_types where id = '".$query['adv_type']."'");
                $type = $db->loadObject();
                $type_alias = OSPHelper::getLanguageFieldValue($type,'type_alias');
                $type_name = OSPHelper::getLanguageFieldValue($type,'type_name');
                if($type_alias != ""){
                    $segments[] = "adv_".$type_alias;
                }else{
                    $segments[] = "adv_".$type_name;
                }
                unset($query['adv_type']);
            }
            if(isset($query['keyword'])){
                $segments[] = JText::_('OS_KEYWORD').' '.$query['keyword'];
                unset($query['keyword']);
            }
            break;
        case "property_edit":
            $id = $query['id'];
            $db->setQuery("Select id, pro_alias, pro_name from #__osrs_properties where id = '$id'");
            $property   = $db->loadObject();
            $pro_alias  = $property->pro_alias;
            $pro_name   = $property->pro_name;

            if($pro_alias != ""){
                $segs = JText::_('OS_EDIT_PROPERTY')."-".$pro_alias;
            }else{
                $segs = JText::_('OS_EDIT_PROPERTY')."-".$pro_name;
            }
            $segments[] = $segs;
            unset($query['id']);
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;

        //compare properties
        case "compare_layout":
        case "compare_properties":
        case "compare_list":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_COMPARE_PROPERTIES');
                unset($query['Itemid']);
            }
            break;

        case "locator_search":
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                $segments[]	= JText::_('OS_LOCATOR_SEARCHING');
                unset($query['Itemid']);
            }
            if(isset($query['locator_type'])){
                $db->setQuery("Select id,type_alias,type_name from #__osrs_types where id = '".$query['locator_type']."'");
                $type = $db->loadObject();
                $type_alias = $type->type_alias;
                $type_name = $type->type_name;
                if($type_alias != ""){
                    $segments[] = JText::_('OS_LOCATOR_SEARCHING')."-".$type_alias;
                }else{
                    $segments[] = JText::_('OS_LOCATOR_SEARCHING')."-".$type_name;
                }
                unset($query['locator_type']);
            }
            break;

        case "direction_map":
            $segments[]	= JText::_('OS_GET_DIRECTIONS');
            $id = $query['id'];
            $db->setQuery("Select id, pro_alias, pro_name from #__osrs_properties where id = '$id'");
            $property   = $db->loadObject();
            $pro_alias  = $property->pro_alias;
            $pro_name   = $property->pro_name;

            if($pro_alias != ""){
                $segs = $pro_alias;
            }else{
                $segs = $pro_name;
            }
            $segments[] = $segs;
            if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
                unset($query['Itemid']);
            }
            break;
    }

    if (isset($query['start']) || isset($query['limitstart']))
    {
        $limit = $app->getUserState('limit');
        $limitStart = isset($query['limitstart']) ? (int)$query['limitstart'] : (int)$query['start'];
        $page = ceil(($limitStart + 1) / $limit);
        $segments[] = JText::_('OS_PAGE').'-'.$page;
    }

    if (isset($query['task']))
        unset($query['task']);

    if (isset($query['view']))
        unset($query['view']);

    if (isset($query['id']))
        unset($query['id']);

    if (isset($query['category_id']))
        unset($query['category_id']);

    if (isset($query['type_id']))
        unset($query['type_id']);

    if (isset($query['country_id']))
        unset($query['country_id']);

    if (isset($query['state_id']))
        unset($query['state_id']);

    if (isset($query['layout']))
        unset($query['layout']);

    if (isset($query['start']))
        unset($query['start']);

    if (isset($query['limitstart']))
        unset($query['limitstart']);


    if (count($segments))
    {
        $segments = array_map('JApplication::stringURLSafe', $segments);
        //print_r($segments);
        $key = md5(implode('/', $segments));
        $q = $db->getQuery(true);
        $q->select('COUNT(*)')
            ->from('#__osrs_urls')
            ->where('md5_key="'.$key.'"');
        $db->setQuery($q);
        $total = $db->loadResult();
        if (!$total)
        {
            $q->clear();
            $q->insert('#__osrs_urls')
                ->columns('md5_key, `query`')
                ->values("'$key', '$queryString'");
            $db->setQuery($q);
            $db->query();
        }
    }

    return $segments;
}

/**
 *
 * Parse the segments of a URL.
 * @param	array	The segments of the URL to parse.
 * @return	array	The URL attributes to be used by the application.
 * @since	1.5
 */
function OspropertyParseRoute($segments)
{
    $vars = array();
    if (count($segments))
    {
        $db = JFactory::getDbo();
        $key = md5(str_replace(':', '-', implode('/', $segments)));
        $query = $db->getQuery(true);
        $query->select('`query`')
            ->from('#__osrs_urls')
            ->where('md5_key="'.$key.'"');
        $db->setQuery($query);
        $queryString = $db->loadResult();

        if ($queryString)
            parse_str($queryString, $vars);
    }

    $app		= JFactory::getApplication();
    $menu		= $app->getMenu();
    if ($item = $menu->getActive())
    {
        foreach ($item->query as $key=>$value)
        {
            if ($key != 'option' && $key != 'Itemid' && !isset($vars[$key]))
                $vars[$key] = $value;
        }
    }
    return $vars;
}