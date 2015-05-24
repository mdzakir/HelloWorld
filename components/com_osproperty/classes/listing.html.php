<?php
/*------------------------------------------------------------------------
# listing.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OspropertyListing{
	/**
	 * Show search list
	 *
	 * @param unknown_type $option
	 * @param unknown_type $lists
	 */
	static function searchList($option,$lists){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$adv_type_ids = $configClass['adv_type_ids'];
		if($adv_type_ids != "0"){
			$adv_type_ids = explode("|",$adv_type_ids);
		}
		$add_adv_type  = 0;
		if(count($adv_type_ids) > 0){
			//add adv_type
			$add_adv_type = 1;
		}
		?>
        <form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty" name="searchlistform" id="searchlistform">
            <div class="row-fluid">
                <div>
                    <div class="componentheading">
                        <?php
                            echo JText::_('OS_YOUR_SEARCH_LIST');
                        ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button type="button" class="btn hasTooltip" title="<?php echo JText::_('OS_DELETE');?>" onclick="javascript:removeList('property_removesearchlist');">
                                <i class="osicon-remove"></i> <?php echo JText::_('OS_DELETE');?>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <table class="adminlist table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th width="5%" >
                                </th>
                                <th width="45%" >
                                    <?php echo JText::_('OS_LIST_NAME')?>
                                </th>
                                <?php
                                if($configClass['active_alertemail'] == 1){
                                ?>
                                <th width="25%" >
                                    <?php echo JText::_('OS_RECEIVE_EMAIL_WITH_NEW_PROPERTIES')?>
                                </th>
                                <?php } ?>
                                <th width="25%" >
                                    <?php echo JText::_('OS_CREATED_ON')?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $select = "";
                        $k = 0;
                        for($i=0;$i<count($lists);$i++){
                            $list = $lists[$i];
                            if($i % 2 == 0){
                                $bgcolor = "white";
                            }else{
                                $bgcolor = "#efefef";
                            }
                            $suffixLink  = "";
                            if($add_adv_type ==1){
                                $db->setQuery("Select a.search_param from #__osrs_user_list_details as a inner join #__osrs_user_list as b on b.id = a.list_id where a.field_id like 'type' and b.id = '$list->id'");
                                $adv_type = $db->loadResult();
                                $suffixLink = "&adv_type=".$adv_type;
                            }
                            $select.= "<option value='$list->id'>".$list->id."</option>";
                            ?>
                            <tr class="row<?php echo $k;?>">
                                <td width="5%" style="background-color:<?php echo $bgcolor?>;text-align:center;padding:3px;">
                                    <?php echo $i + 1?>
                                </td>
                                <td width="5%" style="background-color:<?php echo $bgcolor?>;text-align:center;padding:3px;">
                                    <input type="checkbox" name="chkid" class="inputbox" onclick="javascript:updateSelectList('<?php echo $list->id?>')" />
                                </td>
                                <td width="45%" style="background-color:<?php echo $bgcolor?>;text-align:left;padding:3px;padding-left:20px;">
                                    <div id="div_list_<?php echo $i?>" style="display:relative;">
                                        <a href="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_advsearch&list=1&list_id=<?php echo $list->id?><?php echo $suffixLink?>&Itemid=<?php echo JRequest::getInt('Itemid',0)?>" title="<?php echo JText::_('OS_GET_SEARCH_RESULT')?>">
                                            <?php echo $list->list_name?>
                                        </a>
                                        <a href="javascript:showInputbox('<?php echo $i?>')">
                                            <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/edit.png" />
                                        </a>
                                        <div id="div_input_<?php echo $i?>" style="display:none;">
                                            <input type="text" name="list_name_<?php echo $i?>" id="list_name_<?php echo $i?>" class="inputbox" value="<?php echo htmlspecialchars($list->list_name);?>" size="40" />
                                            <a href="javascript:saveListName('<?php echo $i?>','<?php echo $list->id?>')" title="<?php echo JText::_('OS_SAVE')?>">
                                                <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/savefavorites.png" />
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <?php
                                if($configClass['active_alertemail'] == 1){
                                ?>
                                <td width="25%" style="background-color:<?php echo $bgcolor?>;text-align:center;padding:3px;padding-left:20px;">
                                    <div id="div_send_status_<?php echo $list->id; ?>">
                                        <?php
                                        if($list->receive_email == 0){
                                            ?>
                                            <a href="javascript:updateSendEmailStatus(<?php echo $list->id?>,1);" title="<?php echo JText::_('OS_CLICK_HERE_TO_RECEIVE_ALERT_EMAIL_WHEN_NEW_PROPERTIES_ARE_ADDED');?>">
                                                <img src="<?php echo JUri::root()?>components/com_osproperty/images/assets/publish_x.png"/>
                                            </a>
                                            <?php
                                        }else{
                                            ?>
                                            <a href="javascript:updateSendEmailStatus(<?php echo $list->id;?>,0);" title="<?php echo JText::_('OS_IF_YOU_DONT_WANT_TO_RECEIVE_ALERT_EMAIL_PLEASE_CLICK_HERE');?>">
                                                <img src="<?php echo JUri::root()?>components/com_osproperty/images/assets/tick.png"/>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                                <?php } ?>
                                <td width="30%" style="background-color:<?php echo $bgcolor?>;text-align:center;padding:3px;">
                                    <?php echo $list->created_on?>
                                </td>
                            </tr>
                            <?php
                            $k = 1 - $k;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <select name="cid[]" id="cidchkid" multiple style="display:none;">
            <?php echo $select?>
            </select>
            <input type="hidden" name="current_item" id="current_item" value="" />
            <input type="hidden" name="option" value="com_osproperty" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
            <input type="hidden" name="live_site" id="live_site" value="<?php echo Juri::root(); ?>" />
            <input type="hidden" name="select_item" id="select_item" value=""/>

		</form>
		<script language="javascript">
            function updateSendEmailStatus(list_id,send_status){
                var select_item = document.getElementById('select_item');
                select_item.value = list_id;
                updateSendEmailStatusAjax(list_id,send_status,'<?php echo Juri::root();?>');
            }

		function showInputbox(id){
			var div_input = document.getElementById('div_input_' + id);
			if(div_input != null){
				if(div_input.style.display == "inline"){
					div_input.style.display = "none";
				}else{
					div_input.style.display = "inline";
				}
			}
		}
		
		function saveListName(id,list_id){
			var list_name = document.getElementById('list_name_' + id);
			var current_item = document.getElementById('current_item');
			current_item.value = id;
			if(list_name != null){
				saveListNameAjax(id,list_id,list_name.value,'<?php echo JURI::root();?>','<?php echo JRequest::getInt('Itemid',0)?>');
			}
		}
		
		function removeList(){
			var form = document.searchlistform;
			check = 0;
			var cid = document.getElementById('cidchkid');
			var length = cid.options.length;
			for(i=0;i<length;i++){
				if(cid.options[i].selected == true){
					check = 1;
				}
			}
			if(check == 0){
				alert("<?php echo JText::_('OS_PLEASE_SELECT_ITEM_TO_REMOVE')?>");
			}else{
				var answer = confirm("<?php echo JText::_('DO_YOU_WANT_TO_REMOVE_SEARCH_LISTS')?>");
				if(answer == 1){
					form.task.value = "property_removesearchlist";
					form.submit();
				}
			}
		}
		
		function updateSelectList(list_id){
			var cidchkid = document.getElementById('cidchkid');
			var length = cidchkid.options.length;
			for(i=0;i<length;i++){
				if(cidchkid.options[i].value == list_id){
					if(cidchkid.options[i].selected == true){
						cidchkid.options[i].selected = false;
					}else{
						cidchkid.options[i].selected = true;
					}
				}
			}
		}
		</script>
		<?php
	}
	/**
	 * Advance search function
	 *
	 * @param unknown_type $option
	 */
	static function advSearchForm($option,$groups,$lists,$rows,$pageNav,$param,$adv_type,$dosearch){
		global $mainframe,$configClass,$ismobile;
		$db = JFactory::getDbo();
		?>
		
		<?php 
		OSPHelper::generateHeading(2,JText::_('OS_ADVSEARCH'));
		?>
		<div class="clearfix"></div>
		<form method="GET" action="<?php echo JRoute::_("index.php?option=com_osproperty&view=ladvsearch");?>" name="ftForm" id="ftForm">
		<div class="row-fluid">
			<div class="span12">
			<?php
			$show_advancesearchform = JRequest::getInt('show_advancesearchform',1);
			if($show_advancesearchform == 0){
				?>
				<div style="display:none;">
				<?php
				}
				if(($configClass['adv_type_ids'] == "0") or ($configClass['adv_type_ids'] == "")){
					//HelperOspropertyCommon::advsearchForm($groups,$lists,0);
					//if(!$ismobile){
						HelperOspropertyCommon::advsearchForm($groups,$lists,0);
					//}else{
						//HelperOspropertyCommon::advsearchFormMobile($groups,$lists,0);
					//}
				}else{
					$adv_type_ids = $configClass['adv_type_ids'];
					$adv_type_idsArr = explode("|",$adv_type_ids);
					?>
					<div class="row-fluid">
						<ul class="nav nav-tabs">
							<?php
							for($i=0;$i<count($adv_type_idsArr);$i++){
								$tid = $adv_type_idsArr[$i];
								$db->setQuery("Select * from #__osrs_types where id = '$tid'");
								$ptype = $db->loadObject();
								$type_name = OSPHelper::getLanguageFieldValue($ptype,'type_name');
								if($adv_type > 0){
									if($tid == $adv_type){
										$active = "class='active'";
									}else{
										$active = "";
									}
								}else{
									if($i ==0){
										$active = "class='active'";
										$adv_type = $adv_type_idsArr[0];
									}else{
										$active = "";
									}
								}
								?>
								<li <?php echo $active;?>><a href="<?php echo JRoute::_('index.php?option=com_osproperty&view=ladvsearch&adv_type='.$tid.'&Itemid='.JRequest::getInt('Itemid',0))?>"><?php echo $type_name;?></a></li>
								<?php
							}
							?>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="<?php echo strtolower(str_replace(" ","_",$type_name))?>" style="overflow:hidden;">
								<?php
								HelperOspropertyCommon::advsearchForm($groups,$lists,$adv_type);
								?>
							</div>
						</div>
					</div>
					<?php
				}
				if($show_advancesearchform == 0){
				?>
				</div>
				<?php
				}
				?>
			</div>
			<div id="search_list_div"></div>
			<div class="clearfix"></div>
			<?php
			if($dosearch  == 0){
				?>
				<div>
					<strong><?php echo JText::_('OS_PLEASE_SELECT_AT_LEAST_ONE_CRITERIA')?></strong>
				</div>
				<?php
			}else{
				if(count($rows) > 0){
					for($i=0;$i<count($rows);$i++){
						$row = $rows[$i];
						$otherInforArr = array();
						//$rows[$i]->city = HelperOspropertyCommon::loadCityName($rows[$i]->city);
						if(!$ismobile){
							if($configClass['listing_show_view'] == 1){
								 $otherInforArr[count($otherInforArr)] = JText::_('OS_TOTAL_VIEWING').": <strong>".$row->hits."</strong>";
							}
						}
						if(($configClass['listing_show_rating'] == 1) and ($configClass['show_rating'] == 1)){
							$otherInforArr[count($otherInforArr)] = JText::_('OS_RATE').": <strong>".$row->rating."</strong>";
						}
						if($configClass['listing_show_ncomments'] == 1){
							$otherInforArr[count($otherInforArr)] = JText::_('OS_COMMENTS').": <strong>".$row->comment."</strong>";
						}
						$row->other_information = implode(", ",$otherInforArr);
					}
				?>
				<div class="clearfix"></div>
				<div id="listings">
					<div class="block_caption">
							<?php
							echo JText::_('OS_RESULTS');
							echo " ";
							echo $pageNav->limitstart." - ";
							if($pageNav->total < $pageNav->limit){
								echo $pageNav->total." ";
							}else{
								echo $pageNav->limitstart + $pageNav->limit." ";
							}
							echo JText::_('OS_OF');
							echo " ".$pageNav->total;
							?>
					</div>
					<?php
					$document = JFactory::getDocument();
					$tpl = new OspropertyTemplate();
					//for($i=0;$i<count($rows);$i++){
						//$rows[$i]->city = HelperOspropertyCommon::loadCityName($rows[$i]->city);
					//}
					$tpl->set('rows',$rows);
					$tpl->set('pageNav',$pageNav);
					$tpl->set('temp_path_img',JURI::root()."components/com_osproperty/templates/default/img");
					$tpl->set('configClass',$configClass);
					//$tpl->set('symbol',$symbol);
					$tpl->set('ismobile',$ismobile);
					
					$db->setQuery("Select * from #__osrs_themes where published = '1'");
					$theme = $db->loadObject();
					$themename = ($theme->name!= "")? $theme->name:"default";
					$tpl->set('themename',$themename);
					$db->setQuery("Select * from #__osrs_themes where name like '$themename'");
					$themeobj = $db->loadObject();
					$params = $themeobj->params;
					$params = new JRegistry($params) ;
					$listview = 1;
					if($themename == "default"){
						$default_list_view = $params->get('default_layout',1);
						switch ($default_list_view){
							case "1":
								$listview = "1";
							break;
							case "2":
								$listview = "3";
							break;
							case "3":
								$listview = "2";
							break;
						}
					}
					
					$view_type_cookie = JRequest::getVar('listviewtype',$listview);
					if($_COOKIE['viewtypecookie'] == 0){
						$_COOKIE['viewtypecookie'] = $listview;
					}
					if($view_type_cookie == 0){
						$view_type_cookie = $_COOKIE['viewtypecookie'];	
					}
					
					$tpl->set('params',$params);
					
					if($view_type_cookie == 1){
						$body = $tpl->fetch("results.html.tpl.php");
					}elseif($view_type_cookie == 3){
						if(file_exists(JPATH_COMPONENT.DS."templates".DS.$themename.DS."results.grid.html.tpl.php")){
							$body = $tpl->fetch("results.grid.html.tpl.php");
						}else{
							$body = $tpl->fetch("results.html.tpl.php");	
						}
					}elseif($view_type_cookie == 2){
						if(file_exists(JPATH_COMPONENT.DS."templates".DS.$themename.DS."results.map.html.tpl.php")){
							$body = $tpl->fetch("results.map.html.tpl.php");
						}else{
							$body = $tpl->fetch("results.html.tpl.php");	
						}
					}
					echo $body;
					?>
				</div>
				<?php
				}else{
					?>
					<div class="clearfix"></div>
					<div>
						<strong><?php echo JText::_('OS_NO_RESULT')?></strong>
					</div>
					<?php
				}
			}
			?>
			</div>
		
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="property_advsearch" >
		<input type="hidden" name="show_more_div" id="show_more_div" value="0" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
		<input type="hidden" name="search_param" id="search_param" value="<?php echo implode("_",$param);?>" />
		<input type="hidden" name="list_id" id="list_id" value="<?php echo JRequest::getInt('list_id',0)?>" />
		<input type="hidden" name="adv_type" id="adv_type" value="<?php echo $adv_type?>" />
		<input type="hidden" name="show_advancesearchform" value="<?php echo $show_advancesearchform;?>" />
		<?php 
		$db->setQuery("Select id from #__osrs_types");
        $types = $db->loadObjectList();
        if(count($types) > 0){
        	foreach ($types as $type){
        		$db->setQuery("Select fid from #__osrs_extra_field_types where type_id = '$type->id'");
        		$type->fields = $db->loadColumn(0);
        		?>
        		<input type="hidden" name="advtype_id_<?php echo $type->id?>" id="advtype_id_<?php echo $type->id?>" value="<?php echo implode(",",$type->fields);?>"/>
        		<?php
        	}
        }
		?>
		</form>
		<script language="javascript">
		var more_option_span = document.getElementById('more_option_span');
		jQuery('#more_option_span').click(function() {
		  var show_more_div = document.getElementById('show_more_div');
		  show_more_div_value = show_more_div.value;
		  if(show_more_div_value == 0){
		  	more_option_span.innerHTML = "<?php echo JText::_('OS_LESS_OPTION')?>&nbsp; <i class='osicon-chevron-up'></i>";
		  	show_more_div.value = 1;
		  	jQuery('#more_option_div').show('slow');
		  }else{
		  	more_option_span.innerHTML = "<?php echo JText::_('OS_MORE_OPTION')?>&nbsp; <i class='osicon-chevron-down'></i>";
		  	show_more_div.value = 0;
		  	jQuery('#more_option_div').hide('slow');  
		  }
		});
		function change_country_company(country_id,state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoStateCityLocator(country_id,state_id,city_id,'country','state_id',live_site);
		}
		function change_state(state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoCity(state_id,city_id,'state_id',live_site);
		}
		function saveSearchList(){
			//var live_site = '<?php echo JURI::root()?>';
			//saveSearchListAjax(document.advForm.search_param.value,live_site,'<?php echo JRequest::getInt('Itemid',0)?>');
			document.ftForm.task.value = "property_saveSearchList";
			document.ftForm.submit();
		}
		
		function updateSearchList(){
			document.ftForm.task.value = "property_updateSearchList";
			document.ftForm.submit();
		}
		</script>
		
		<?php
	}
	
	static function showPropertyCityListing($option,$type,$category,$show_filter_agent,$show_filter_state,$show_filter_keyword,$show_filter_bed,$show_filter_bath,$show_filter_price,$show_filter_room,$menu,$city_id,$state_id,$country_id){
		global $mainframe,$configClass;
		$filterParams = array();
		//show cat
		$filterParams[0] = 0;
		//agent
		$filterParams[1] = $show_filter_agent;
		//keyword
		$filterParams[2] = $show_filter_keyword;
		//bed
		$filterParams[3] = $show_filter_bed;
		//bath
		$filterParams[4] = $show_filter_bath;
		//rooms
		$filterParams[5] = $show_filter_rooms;
		//price
		$filterParams[6] = $show_filter_state;
		//property type 
		$filterParams[7] = 0;
		//state
		$filterParams[8] = $show_filter_state;
		
		$category_id 	= $cat->id;
		$property_type	= JRequest::getInt('property_type',0);
		$keyword		= JRequest::getVar('keyword','');
		$nbed			= JRequest::getInt('nbed',0);
		$nbath			= JRequest::getInt('nbath',0);
		$isfeatured		= JRequest::getInt('isfeatured',0);
		$nrooms			= JRequest::getInt('nrooms',0);
		$orderby		= JRequest::getVar('orderby','a.id');
		$ordertype		= JRequest::getVar('ordertype','desc');
		$limitstart		= JRequest::getInt('limitstart',0);
		$limit			= JRequest::getInt('limit',$configClass['general_number_properties_per_page']);
		$favorites		= JRequest::getInt('favorites',0);
		$price			= JRequest::getInt('price',0);
		$agent_id		= JRequest::getInt('agent_id',0);
		?>
		<div class="componentheading">
			<?php
			OSPHelper::generateHeading(1,'');
			OSPHelper::generateHeading(2,'');
			?>
		</div>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_city&id='.$id.'&Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm">
		<?php
		OspropertyListing::listProperties($option,'',null,$agent_id,$property_type,$keyword,$nbed,$nbath,$isfeatured,0,$nrooms,$orderby,$ordertype,$limitstart,$limit,$favorites,$price,$filterParams,$city_id,$state_id,$country_id,0,1,-1);
		?>
		<input type="hidden" name="option" value="<?php echo $option?>" />
		<input type="hidden" name="task" value="property_city" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
		<input type="hidden" name="id" value="<?php echo $city_id?>" />
		<input type="hidden" name="view" id="view" value="<?php echo JRequest::getVar('view','');?>" />
		</form>
		<?php
	}
	/**
	 * List all the properties of one property type
	 *
	 * @param unknown_type $option
	 * @param unknown_type $type_id
	 */
	static function showPropertyTypeListing($option,$type,$category,$show_filter_agent,$show_filter_state,$show_filter_keyword,$show_filter_bed,$show_filter_bath,$show_filter_price,$show_filter_room,$menu,$city_id,$state_id,$country_id,$isFeatured,$isSold,$orderby,$ordertype,$company_id,$max_properties,$show_filterform,$show_categoryfilter,$show_propertytypefilter,$show_locationfilter,$show_keywordfilter,$show_pricefilter,$show_agenttypefilter,$agenttype){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$filterParams = array();
		//show cat
		$filterParams[0] = 0;
		//agent
		$filterParams[1]  = $show_filter_agent;
		//keyword
		$filterParams[2]  = $show_filter_keyword;
		//bed
		$filterParams[3]  = $show_filter_bed;
		//bath
		$filterParams[4]  = $show_filter_bath;
		//rooms
		$filterParams[5]  = $show_filter_rooms;
		//price
		$filterParams[6]  = $show_filter_state;
		//property type 
		$filterParams[7]  = 0;
		//state
		$filterParams[8]  = $show_filter_state;
		
		$filterParams[9]  = $show_categoryfilter;
		
		$filterParams[10] = $show_propertytypefilter;
		
		$filterParams[11] = $show_locationfilter;
		
		$filterParams[12] = $show_keywordfilter;
		
		$filterParams[13] = $show_pricefilter;
		
		$filterParams[14] = $show_agenttypefilter;
		
		//$category_id 	= $category->id;
		$property_type	= JRequest::getInt('property_type',0);
		if($property_type == 0){
			$property_type = JRequest::getInt('type_id',0);
		}
		$keyword		= OSPHelper::getStringRequest('keyword','','');//JRequest::getVar('keyword','');
		$nbed			= JRequest::getInt('nbed',0);
		$nbath			= JRequest::getInt('nbath',0);
		//$isfeatured		= JRequest::getVar('isFeatured','');
		$nrooms			= JRequest::getInt('nrooms',0);
		//$orderby		= JRequest::getVar('orderby','a.id');
		//$ordertype		= JRequest::getVar('ordertype','desc');
		$limitstart		= JRequest::getInt('limitstart',0);
		$limit			= JRequest::getInt('limit',$configClass['general_number_properties_per_page']);
		$favorites		= JRequest::getInt('favorites',0);
		$price			= JRequest::getInt('price',0);
		$agent_id		= JRequest::getInt('agent_id',0);
		//$city_id		= JRequest::getVar('city','');
		//$state_id		= JRequest::getVar('state_id');
		//$country_id		= JRequest::getVar('country_id',HelperOspropertyCommon::getDefaultCountry());
		OSPHelper::generateHeading(1,'');
		OSPHelper::generateHeading(2,'');
		
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$itemid     = JRequest::getInt('Itemid');
		$type_url = "";
		$cat_url = "";
		if($itemid > 0){
			$active = $menus->getActive();
			$url = $active->link;
			//if((strpos($url,"type_id=".$property_type) !== false) and ($property_type > 0)){
				//$type_url = "&type_id=".$property_type;
			//}
			//if((strpos($url,"category=".$category->id) !== false) and ($category->id > 0)){
				//$cat_url = "&category=".$category->id;
			//}
			//print_r($active);
			//echo "<BR />";
			//$params = $active->params;
			//$params = new JParameter($params);
			//$params = new JRegistry();
			//$params->loadString($active->params);
			
			//$params->get('param_name');
			//echo $params->get('type_id');
			//print_r($active->query['catIds']);
			//die();
			$type_id_menu = $active->query['type_id'];
			if(($property_type > 0) and (intval($type_id_menu) > 0) and ($property_type == $type_id_menu)){
				$type_url = "&type_id=".$property_type;
			}
			$catIds_menu = $active->query['catIds'];
			if((count($catIds_menu) > 0) and (count($category) > 0) and (OSPHelper::array_equal($catIds_menu,$category))){
				//$cat_url = "&type_id=".$property_type;
				$cat_url = "";
				foreach ($catIds_menu as $catid){
					$cat_url .= "&catIds[]=".$catid;
				}
			}
			
			$country_id_menu = $active->query['country_id'];
			if(($country_id > 0) and (intval($country_id_menu) > 0) and ($country_id == $country_id_menu)){
				$country_url = "&country_id=".$country_id_menu;
			}
			
			$company_id_menu = $active->query['company_id'];
			if(($company_id > 0) and (intval($company_id_menu) > 0) and ($company_id == $company_id_menu)){
				$company_url = "&company_id=".$company_id;
			}
			
		}else{
			if($property_type > 0){
				$type_url = "&type_id=".$property_type;
			}
			//if($category->id > 0){
				//$cat_url = "&category=".$category->id;
			//}
		}

		$db->setQuery("Select count(id) from #__osrs_property_listing_layout where id = '$itemid'");
		$count = $db->loadResult();
		if($count == 0){
			$db->setQuery("Insert into #__osrs_property_listing_layout (id,itemid,category_id,type_id,country_id,company_id, featured,sold,state_id,agenttype) values (NULL,'$itemid','$category_id','$property_type','$country_id','$company_id','$isFeatured','$isSold','$state_id','$agenttype')");
			$db->query();
		}
		
		?>
		<div class="clearfix"></div>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&view=ltype'.$cat_url.$type_url.$country_url.$company_url.'&Itemid='.$itemid)?>" name="ftForm" id="ftForm">
		<?php
		OspropertyListing::listProperties($option,$company_id,$category,$agent_id,$property_type,$keyword,$nbed,$nbath,$isFeatured,$isSold,$nrooms,$orderby,$ordertype,$limitstart,$limit,$favorites,$price,$filterParams,$city_id,$state_id,$country_id,$max_properties,$show_filterform,$agenttype);
		?>
		<input type="hidden" name="option" value="<?php echo $option?>" />
		<input type="hidden" name="task" value="property_type" />
		<input type="hidden" name="Itemid" id="Itemid" value="<?php echo JRequest::getInt('Itemid',0);?>" />
		<input type="hidden" name="view" id="view" value="<?php echo OSPHelper::getStringRequest('view','','');?>" />
		<input type="hidden" name="max_properties" id="max_properties" value="<?php echo $max_properties;?>" />
		<input type="hidden" name="show_filterform" id="show_filterform" value="<?php echo $show_filterform;?>" />
		<input type="hidden" name="show_categoryfilter" id="show_categoryfilter" value="<?php echo $show_categoryfilter;?>" />
		<input type="hidden" name="show_propertytypefilter" id="show_propertytypefilter" value="<?php echo $show_propertytypefilter;?>" />
		<input type="hidden" name="show_locationfilter" id="show_locationfilter" value="<?php echo $show_locationfilter;?>" />
		<input type="hidden" name="show_pricefilter" id="show_pricefilter" value="<?php echo $show_pricefilter;?>" />
		<input type="hidden" name="show_keywordfilter" id="show_keywordfilter" value="<?php echo $show_keywordfilter;?>" />
		<input type="hidden" name="show_agenttypefilter" id="show_agenttypefilter" value="<?php echo $show_agenttypefilter;?>" />	
		</form>
		<?php
		
	}
	/**
	 * List Properties
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 */
	static function listProperties($option,$rows,$pageNav,$lists,$filterParams,$configs){
		global $mainframe,$configClass,$symbol;
		$db = JFactory::getDbo();
		$document = JFactory::getDocument();
		//$document->addStyleSheet(OspropertyTemplate::livePath()."/style/style.css");
		$tpl = new OspropertyTemplate();
		for($i=0;$i<count($rows);$i++){
			$row = $rows[$i];
			$otherInforArr = array();
			if(!$ismobile){
				if($configClass['listing_show_view'] == 1){
					 $otherInforArr[count($otherInforArr)] = JText::_('OS_TOTAL_VIEWING').": <strong>".$row->hits."</strong>";
				}
			}
			if(($configClass['listing_show_rating'] == 1) and ($configClass['show_rating'] == 1)){
				$otherInforArr[count($otherInforArr)] = JText::_('OS_RATE').": <strong>".$row->rating."</strong>";
			}
			
			if($configClass['listing_show_ncomments'] == 1){
				$otherInforArr[count($otherInforArr)] = JText::_('OS_COMMENTS').": <strong>".$row->comment."</strong>";
			}
			
			$row->other_information = implode(", ",$otherInforArr);
		}
		$tpl->set('rows',$rows);
		$tpl->set('pageNav',$pageNav);
		$tpl->set('task',JRequest::getVar('task',''));
		$tpl->set('temp_path_img',OspropertyTemplate::livePath()."/img");
		
		//$tpl->set('filterParams',$filterParams);
		$showcat = $filterParams[0];
		$tpl->set('showcat',$showcat);
		$showagent = $filterParams[1];
		$tpl->set('showagent',$showagent);
		$showkeyword = $filterParams[2];
		$tpl->set('showkeyword',$showkeyword);
		$showbed = $filterParams[3];
		$tpl->set('showbed',$showbed);
		$showbath = $filterParams[4];
		$tpl->set('showbath',$showbath);
		$showrooms = $filterParams[5];
		$tpl->set('showrooms',$showrooms);
		$showprice = $filterParams[6];
		$showtype = $filterParams[7];
		$tpl->set('showtype',$showtype);
		$showstate = $filterParams[8];
		$tpl->set('showstate',$showstate);
		$show_categoryfilter = $filterParams[9];
		$tpl->set('show_categoryfilter',$show_categoryfilter);
		$show_propertytypefilter = $filterParams[10];
		$tpl->set('show_propertytypefilter',$show_propertytypefilter);
		$show_locationfilter = $filterParams[11];
		$tpl->set('show_locationfilter',$show_locationfilter);
		$show_keywordfilter = $filterParams[12];
		$tpl->set('show_keywordfilter',$show_keywordfilter);
		$show_pricefilter = $filterParams[13];
		$tpl->set('show_pricefilter',$show_pricefilter);
		$show_agenttypefilter = $filterParams[14];
		$tpl->set('show_agenttypefilter',$show_agenttypefilter);
		
		$lists['show_categoryfilter'] 		= $show_categoryfilter;
		$lists['show_propertytypefilter'] 	= $show_propertytypefilter;
		$lists['show_keywordfilter'] 		= $show_keywordfilter;
		$lists['show_pricefilter'] 			= $show_pricefilter;
		$lists['show_locationfilter'] 		= $show_locationfilter;
		$lists['show_agenttypefilter'] 		= $show_agenttypefilter;
		
		$tpl->set('lists',$lists);
		$tpl->set('showprice',$showprice);
		$tpl->set('configs',$configs);
		$tpl->set('configClass',$configClass);
		$tpl->set('symbol',$symbol);
		$tpl->set('ismobile',$ismobile);
		
		$db->setQuery("Select * from #__osrs_themes where published = '1'");
		$theme = $db->loadObject();
		$themename = ($theme->name!= "")? $theme->name:"default";
		$tpl->set('themename',$themename);
		//echo $themename;
		$db->setQuery("Select * from #__osrs_themes where name like '$themename'");
		$themeobj = $db->loadObject();
		$params = $themeobj->params;
		$params = new JRegistry($params) ;
		$listview = 1;
		if($themename == "default"){
			$default_list_view = $params->get('default_layout',1);
			switch ($default_list_view){
				case "1":
					$listview = "1";
				break;
				case "2":
					$listview = "3";
				break;
				case "3":
					$listview = "2";
				break;
			}
		}
		
		$view_type_cookie = JRequest::getVar('listviewtype',$listview);
		//echo $view_type_cookie;
		if($_COOKIE['viewtypecookie'] == 0){
			$_COOKIE['viewtypecookie'] = $listview;
		}
		if($view_type_cookie == 0){
			$view_type_cookie = $_COOKIE['viewtypecookie'];	
		}
		
		$tpl->set('params',$params);
		//echo JPATH_COMPONENT.DS."templates".DS.$themename.DS."grid.html.tpl.php";
		if($view_type_cookie == 1){
			$body = $tpl->fetch("listing.html.tpl.php");
		}elseif($view_type_cookie == 3){
			if(file_exists(JPATH_COMPONENT.DS."templates".DS.$themename.DS."grid.html.tpl.php")){
				$body = $tpl->fetch("grid.html.tpl.php");
			}else{
				$body = $tpl->fetch("listing.html.tpl.php");	
			}
		}elseif($view_type_cookie == 2){
			if(file_exists(JPATH_COMPONENT.DS."templates".DS.$themename.DS."map.html.tpl.php")){
				$body = $tpl->fetch("map.html.tpl.php");
			}else{
				$body = $tpl->fetch("listing.html.tpl.php");	
			}
		}
		echo $body;
	}
	
	public static function _getSchoolData($values) 
    {
		$key	 = $values['key'];
		$radius	 = $values['radius'];
		$min	 = $values['min'];
		$lat	 = $values['latitude'];
		$lon	 = $values['longitude'];
		$zip	 = $values['zip'];
        $city    = urlencode($values['city']);
        $state   = $values['state'];

		$query_string = "";
		$query_string .= "key=" . $key;
		$query_string .= "&v=3";
		$query_string .= "&f=system.multiCall";
		$query_string .= "&resf=php";

		// do the school search
		$query_string .= "&methods[0][f]=schoolSearch";
		$query_string .= "&methods[0][sn]=sf";
		$query_string .= "&methods[0][key]=" . $key;
		if($lat != 0 && $lon != 0) {
			$query_string .= "&methods[0][latitude]=" . $lat;
			$query_string .= "&methods[0][longitude]=" . $lon;
			$query_string .= "&methods[0][distance]=" . $radius;
		} elseif (($lat = 0 && $lon = 0) && $zip != 0) {
			$query_string .= "&methods[0][zip]=" . $zip;
		}
		$query_string .= "&methods[0][minResult]=" . $min;
		$query_string .= "&methods[0][fid]=F1";

		// do the branding search
		$query_string .= "&methods[1][f]=gbd";
                $query_string .= "&methods[1][city]=" . $city;
                if($state) $query_string .= "&methods[1][state]=" . $state;
        $query_string .= "&methods[1][sn]=sf";
		$query_string .= "&methods[1][key]=" . $key;
		$query_string .= "&methods[1][fid]=F2";

		$result = self::_curlContents($query_string);

		$schoolinfo = unserialize($result);

		return $schoolinfo;
	}
	
	public static function _curlContents($u)
    {
    	$url = "http://api.education.com/service/service.php?" . $u;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
    }
	
	/**
	 * Property details
	 *
	 * @param unknown_type $option
	 * @param unknown_type $property
	 */
	static function propertyDetails($option,$row,$configs,$owner){
		global $mainframe,$configClass,$ismobile,$lang_suffix,$languages;
		$db = JFactory::getDbo();
		$document = JFactory::getDocument();
		//$document->addScript(JURI::root()."components/com_osproperty/js/counter.js");
		$document->addScript(JURI::root()."components/com_osproperty/js/ajax.js");
		
		$db->setQuery("Select * from #__osrs_themes where published = '1'");
		$theme = $db->loadObject();
		$themename = ($theme->name!= "")? $theme->name:"default";
		?>
		<script language="javascript">
		function submitForm(form_id){
			var form = document.getElementById(form_id);
			var temp1,temp2;
			var cansubmit = 1;
			var require_field = form.require_field;
			require_field = require_field.value;
			var require_label = form.require_label;
			require_label = require_label.value;
			var require_fieldArr = require_field.split(",");
			var require_labelArr = require_label.split(",");
			for(i=0;i<require_fieldArr.length;i++){
				temp1 = require_fieldArr[i];
				temp2 = document.getElementById(temp1);
				
				if(temp2 != null){
					if(temp2.value == ""){
						alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY_FIELD')?>");
						temp2.focus();
						cansubmit = 0;
						return false;
					}else if(temp1 == "comment_security_code"){
						var captcha_str = document.getElementById('captcha_str');
						captcha_str = captcha_str.value;
						if(captcha_str != temp2.value){
							alert(" <?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
							temp2.focus();
							cansubmit = 0;
							return false;
						}
					}else if(temp1 == "request_security_code"){
						var captcha_str = document.getElementById('captcha_str');
						captcha_str = captcha_str.value;
						if(captcha_str != temp2.value){
							alert("<?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
							temp2.focus();
							cansubmit = 0;
							return false;
						}
					}else if(temp1 == "sharing_security_code"){
						var captcha_str = document.getElementById('captcha_str');
						captcha_str = captcha_str.value;
						if(captcha_str != temp2.value){
							alert("<?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
							temp2.focus();
							cansubmit = 0;
							return false;
						}
					}
				}
			}
			if(cansubmit == 1){
				form.submit();
			}
		}
		</script>
		<?php
		echo HelperOspropertyCommon::buildToolbar('property');
		//location
		$location = "";
		if($row->show_address == 1){
			ob_start();
			?>
			<table  width="100%">
				<tr>
					<td class="left_details_col" style="">
						<?php echo JText::_('OS_ADDRESS')?>
					</td>
				<?php
					if($ismobile){
						echo "</tr><tr>";
					}
				?>
					<td class="right_details_col" style="">
						<?php
						echo OSPHelper::generateAddress($row);
						?>
					</td>
				</tr>
			</table>
			<?php
			$location = ob_get_contents();
			ob_end_clean();
		}
		$row->location = $location;
		//end location
		
		//property information
		ob_start();
		?>
		<table  width="100%">
			<?php
			if($row->ref != ""){
				?>
				<tr>
					<td class="left_details_col" style="">
						<?php echo JText::_('Ref #')?>
					</td>
					<td class="right_details_col" style="">
						<strong><?php echo $row->ref;?></strong>
					</td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_TITLE')?>
				</td>
				<td class="right_details_col" style="">
					<strong><?php echo OSPHelper::getLanguageFieldValue($row,'pro_name');?></strong>
				</td>
			</tr>
			<tr>
				<td class="left_details_col">
					<?php echo JText::_('OS_CATEGORY')?>
				</td>
				<td class="right_details_col">
					<a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=category_details&id='.$row->category_id)?>" title="<?php echo OSPHelper::getLanguageFieldValue($row,'category_name');?>">
						<?php echo OSPHelper::getLanguageFieldValue($row,'category_name');?>
					</a>
				</td>
			</tr>
			<?php
			if($row->type_name != ""){
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_PROPERTY_TYPE')?>
				</td>
				<td class="right_details_col" style="">
					<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_type&type_id=".$row->pro_type);?>">
						<?php echo OSPHelper::getLanguageFieldValue($row,'type_name');?>
					</a>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td class="left_details_col">
					<?php echo JText::_('OS_FEATURED')?>
			<?php
			if(!$ismobile){
			?>
				</td>
				<td class="right_details_col">
			<?php }  ?>
					<font style="font-weight:normal;">
					<?php
					if($row->isFeatured == 1){
						echo JText::_('OS_YES');
					}else{
						echo JText::_('OS_NO');
					}
					?>
					</font>
				</td>
			</tr>
			<?php
			
			if($row->rent_time != ""){
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_RENT_TIME_FRAME')?>
					<font style="font-weight:normal;">
					<?php echo JText::_($row->rent_time);?>
					</font>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_DESCRIPTION')?>
				</td>
				<td class="right_details_col" style="">
					<?php echo OSPHelper::getLanguageFieldValue($row,'pro_small_desc');?>
					<BR />
					<?php 
						$row->pro_full_desc =  JHtml::_('content.prepare', $row->pro_full_desc);
						echo stripslashes(OSPHelper::getLanguageFieldValue($row,'pro_full_desc'));
					?>
				</td>
			</tr>
			<?php
			if($configClass['energy'] == 1){
				if(($row->energy > 0) or ($row->climate > 0)){
					if($row->energy == "0.00"){
						$row->energy = "null";
					}
					if($row->climate == "0.00"){
						$row->climate = "null";
					}
					?>
					<tr>
						<td class="left_details_col" style="">
							<?php echo JText::_('OS_DPE')?>
						</td>
						<td class="right_details_col" style="">
							<?php
							echo HelperOspropertyCommon::drawGraph($row->energy, $row->climate);
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>
			<?php
			if(($row->note != "") and (HelperOspropertyCommon::isOwner($row->id))){
			?>
			<tr>
				<td class="left_details_col">
					<?php echo JText::_('OS_NOTE')?>
				</td>
				<td class="right_details_col">
					<?php echo $row->note;?>
				</td>
			</tr>
			<?php
			}
			if(($row->pro_pdf != "") or ($row->pro_pdf_file != "")){
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>
				</td>
			<?php
			if($ismobile){
				echo "</tr><tr>";
			}
			?>
				<td class="right_details_col" style="">
				
					<?php
					if($row->pro_pdf != ""){
						?>
						<a href="<?php echo $row->pro_pdf?>" title="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" alt="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" target="_blank">
							<img src="<?php echo JURI::root()."components/com_osproperty/images/assets"; ?>/pdf.png" />
						</a>
						&nbsp;&nbsp;
						<?php
					}
					?>
					<?php
					if($row->pro_pdf_file != ""){
						?>
						<a href="<?php echo JURI::root()."components/com_osproperty/document/";?><?php echo $row->pro_pdf_file?>" title="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" alt="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" target="_blank">
							<img src="<?php echo JURI::root()."components/com_osproperty/images/assets"; ?>/pdf.png" />
						</a>
						<?php
					}
					?>
				</td>
			</tr>
			<?php } 
			if($configClass['listing_show_view'] == 1){
			?>
			<tr>
				<td class="left_details_col">
					<?php echo JText::_('OS_TOTAL_VIEWING')?>
				
				</td>
				<td class="right_details_col">
				
					<font style="font-weight:normal;"><?php echo $row->hits;?></font>
				</td>
			</tr>
			<?php
			}
			if($configClass['listing_show_rating'] == 1){
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_RATE')?>
				
				</td>
				<td class="right_details_col" style="">
					<?php
					if($row->number_votes > 0){
						$points = round($row->total_points/$row->number_votes);
						?>
						<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star-<?php echo $points;?>.jpg" />
						<?php
					}else{
						?>
						<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star-0.png" />
						<?php
					}
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
		<?php
		$info = ob_get_contents();
		ob_end_clean();
		$row->info = $info;
		//property information END
		
		ob_start();
		if($row->number_votes > 0){
			$points = round($row->total_points/$row->number_votes);
			?>
			<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/stars-<?php echo $points;?>.png" />	
			<?php
		}else{
			?>
			<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/stars-0.png" />	
			<?php
		}
		$ratingvalue = ob_get_contents();
		ob_end_clean();
		$row->ratingvalue = $ratingvalue;
		if($row->number_votes > 0){
			$rate = round($row->total_points/$row->number_votes,2);
			if($rate <= 1){
				$row->cmd = JText::_('OS_POOR');
			}elseif($rate <= 2){
				$row->cmd = JText::_('OS_BAD');
			}elseif($rate <= 3){
				$row->cmd = JText::_('OS_AVERGATE');
			}elseif($rate <= 4){
				$row->cmd = JText::_('OS_GOOD');
			}elseif($rate <= 5){
				$row->cmd = JText::_('OS_EXCELLENT');
			}
			$row->rate = $rate;
		}else{
			$row->rate = '';
			$row->cmd  = JText::_('OS_NOT_SET');
		}
		//price
		$db = JFactory::getDbo();
		$db->setQuery("Select id as value, currency_code as text from #__osrs_currencies where id <> '$row->curr' order by currency_code");
		$currencies   = $db->loadObjectList();
		$currenyArr[] = JHTML::_('select.option','',JText::_('OS_SELECT'));
		$currenyArr   = array_merge($currenyArr,$currencies);
		if($themename == "default"){
			$show_price_text = 1;
		}else{
			$show_price_text = 0;
		}
		$lists['curr'] = JHTML::_('select.genericlist',$currenyArr,'curr','onChange="javascript:convertCurrency('.$row->id.',this.value,'.$show_price_text.')" class="input-small"','value','text');
		
		
		
		//featured
		ob_start();
		?>
		<table  width="100%">
			<?php
			if($configClass['use_rooms'] == 1){
			?>
			<tr>
				<td class="left_details_col" style="width:50%">
					<?php echo JText::_('OS_NUMBER_ROOMS')?>
				</td>
				<td class="right_details_col" style="width:50%">
					<strong><?php echo $row->rooms;?></strong>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($configClass['use_bedrooms'] == 1){
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_BEDROOM')?>
				</td>
				<td class="right_details_col" style="">
					<?php echo $row->bed_room;?>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($configClass['use_bathrooms'] == 1){
			?>
			<tr>
				<td class="left_details_col">
					<?php echo JText::_('OS_BATHROOM')?>
				</td>
				<td class="right_details_col">
					<?php echo OSPHelper::showBath($row->bath_room);?>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($configClass['use_parking'] == 1){
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_PARKING')?>
				</td>
				<td class="right_details_col" style="">
					<?php echo $row->parking;?>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($configClass['use_squarefeet'] == 1){
			?>
			<tr>
				<td class="left_details_col">
					<?php echo OSPHelper::showSquareLabels(); // JText::_('OS_SQUARE_FEET')?>
				</td>
				<td class="right_details_col">
					<?php echo $row->square_feet;?>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($configClass['use_nfloors'] == 1){
			?>
			<tr>
				<td class="left_details_col" style="">
					<?php echo JText::_('OS_NUMBER_OF_FLOORS')?>
				</td>
				<td class="right_details_col" style="">
					<?php echo $row->number_of_floors;?>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
		<?php
		$featured = ob_get_contents();
		ob_end_clean();
		$row->featured = $featured;
		//end featured
		
		//collect agent information
		//photo
		if($configs[40]->fieldvalue == 1){
			ob_start();
			$agent_photo = $row->agent->photo;
			$agent_photo_array = explode(".",$agent_photo);
			$ext = $agent_photo_array[count($agent_photo_array)-1];
			if(($agent_photo != "") and ((strtolower($ext) == "jpg") or (strtolower($ext) == "jpeg"))){
				?>
				<img src="<?php echo JURI::root()?>images/osproperty/agent/<?php echo $row->agent->photo?>" width="60" />
				<?php
			}else{
				?>
				
				<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/user.jpg" style="border:1px solid #CCC;" width="60" />
				<?php
			}
			$photo = ob_get_contents();
			ob_end_clean();
			$row->agentphoto = $photo;
		}
		
		$row->agent_name = $row->agent->name;
		$row->agent_phone = $row->agent->phone;
		$row->agent_mobile = $row->agent->mobile;
		
		//agent
		ob_start();
		?>
		<div style="float:left;">
			<div class="row-fluid">
				<div class="span12">
					<table width="100%" style="border-collapse:separate;border:0px !important;">
						<?php
						if($configClass['show_agent_image'] == 1){
						?>
						<tr>
							<td class="left_details_col" valign="top" style="width:40%">
								<?php 
								if($row->agent->agent_type == 0){
									echo JText::_('OS_AGENT_PHOTO');
								}else{
									echo JText::_('OS_OWNER_PHOTO');
								}
								
								?>
							</td>
							<td class="right_details_col" style="width:60%">
								<?php
								$agent_photo = $row->agent->photo;
								$agent_photo_array = explode(".",$agent_photo);
								$ext = $agent_photo_array[count($agent_photo_array)-1];
								if(($agent_photo != "") and ((strtolower($ext) == "jpg") or (strtolower($ext) == "jpeg"))){
									?>
									<img src="<?php echo JURI::root()?>images/osproperty/agent/<?php echo $row->agent->photo?>" width="100" />
									<?php
								}else{
									?>
									
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/user.jpg" style="border:1px solid #CCC;" width="100" />
									<?php
								}
								?>
							</td>
						</tr>
						<?php
						}
						?>
						<tr>
							<td class="left_details_col" style="">
								<?php echo JText::_('OS_NAME')?>
							</td>
							<td class="right_details_col" style="">
								<strong><?php echo $row->agent->name;?></strong>
							</td>
						</tr>
						<?php
						
						if($configClass['show_agent_address'] == 1){
						?>
						<tr>
							<td class="left_details_col">
								<?php echo JText::_('OS_ADDRESS')?>
							</td>
							<td class="right_details_col">
								<?php echo $row->agent->address;?>
							</td>
						</tr>
						
						<tr>
							<td class="left_details_col" style="">
								<?php echo JText::_('OS_STATE')?>
							</td>
							<td class="right_details_col" style="">
								<?php echo $row->agent->state_name;?>
							</td>
						</tr>
						<tr>
							<td class="left_details_col">
								<?php echo JText::_('OS_COUNTRY')?>
							</td>
							<td class="right_details_col">
								<?php echo $row->agent->country_name;?>
							</td>
						</tr>
						<?php
						}
						if($configClass['show_license'] == 1){
						?>
						<tr>
							<td class="left_details_col" style="">
								<?php echo JText::_('OS_LICENSE')?>
							</td>
							<td class="right_details_col" style="">
								<?php echo $row->agent->license;?>
							</td>
						</tr>
						<?php
						}
						?>
					</table>
				</div>
			</div>
		</div>
		<div style="float:left;">
			<div class="row-fluid">
				<div class="span12">
					<table  width="100%" style="border-collapse:separate;border:0px !important;">
						<?php
						if(($row->agent->phone != "") and ($configClass['show_agent_phone'] == 1)){
						?>
						<tr>
							<td class="left_details_col" style="">
								<div class="agent_phone" style="width:100px;">
									<strong><?php echo JText::_('OS_PHONE')?>:</strong>
								</div>
							</td>
							<td class="right_details_col" style="">
								<?php echo $row->agent->phone;?>
							</td>
						</tr>
						<?php
						}
						if(($row->agent->mobile != "") and ($configClass['show_agent_mobile'] == 1)){
						?>
						<tr>
							<td class="left_details_col">
								<div class="agent_mobile" style="width:100px;">
									<strong><?php echo JText::_('OS_MOBILE')?>:</strong>
								</div>
							</td>
							<td class="right_details_col">
								<?php echo $row->agent->mobile;?>
							</td>
						</tr>
						<?php
						}
						if(($row->agent->fax != "")and ($configClass['show_agent_fax'] == 1)){
						?>
						<tr>
							<td class="left_details_col" style="">
								<div class="agent_fax" style="width:100px;">
									<strong><?php echo JText::_('OS_FAX')?>:</strong>
								</div>
							</td>
							<td class="right_details_col" style="">
								<?php echo $row->agent->fax;?>
							</td>
						</tr>
						<?php
						}
						if(($row->agent->yahoo != "")and ($configClass['show_agent_yahoo'] == 1)){
						?>
						<tr>
							<td class="left_details_col" >
								<div class="agent_yahoo" style="width:100px;">
									<strong><?php echo JText::_('OS_YAHOO')?>:</strong>
								</div>
							</td>
							<td class="right_details_col">
								<?php echo $row->agent->yahoo;?>
							</td>
						</tr>
						<?php
						}
						if(($row->agent->gtalk != "")and ($configClass['show_agent_gtalk'] == 1)){
						?>
						<tr>
							<td class="left_details_col" style="">
								<div class="agent_gtalk" style="width:100px;">
									<strong><?php echo JText::_('OS_GTALK')?>:</strong>
								</div>
							</td>
							<td class="right_details_col" style="">
								<?php echo $row->agent->gtalk;?>
							</td>
						</tr>
						<?php
						}
						if(($row->agent->skype != "")and ($configClass['show_agent_skype'] == 1)){
						?>
						<tr>
							<td class="left_details_col">
								<div class="agent_skype" style="width:100px;">
									<strong><?php echo JText::_('OS_SKYPE')?>:</strong>
								</div>
							</td>
							<td class="right_details_col">
								<?php echo $row->agent->skype;?>
							</td>
						</tr>
						<?php
						}
						if(($row->agent->msn != "")and ($configClass['show_agent_msn'] == 1)){
						?>
						<tr>
							<td class="left_details_col" style="">
								<div class="agent_msn" style="width:100px;">
									<strong><?php echo JText::_('OS_MSN')?>:</strong>
								</div>
							</td>
							<td class="right_details_col" style="">
								<?php echo $row->agent->msn;?>
							</td>
						</tr>
						<?php
						}
						if(($row->agent->facebook != "")and ($configClass['show_agent_facebook'] == 1)){
						?>
						<tr>
							<td class="left_details_col">
								<div class="agent_facebook" style="width:100px;">
									<strong><?php echo JText::_('OS_FACEBOOK')?>:</strong>
								</div>
							</td>
							<td class="right_details_col">
								<a href="<?php echo $row->agent->facebook;?>" target="_blank"><?php echo $row->agent->facebook;?></a>
							</td>
						</tr>
						<?php
						}
						?>
					</table>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row-fluid">
			<div class="span12">
				<?php
				$link = JRoute::_("index.php?option=com_osproperty&task=agent_info&id=".$row->agent_id);
				?>
				<a href="<?php echo $link?>">
					<?php echo JText::_('OS_LISTING')?> (<?php echo $row->agent->countlisting?>)
				</a>
				
				&nbsp;|&nbsp;
				<a href="<?php echo $link?>">
					<?php
					if($row->agent->agent_type == 0){
					?>
					<?php echo JText::_('OS_AGENT_INFO')?>
					<?php
					}else{
						echo JText::_('OS_OWNER_INFO');
					}
					?>
				</a>
				<?php
				if($configClass['show_agent_contact'] == 1){
				?>
				&nbsp;|&nbsp;
				<a href="<?php echo $link?>">
					<?php
					if($row->agent->agent_type == 0){
					?>
					<?php echo JText::_('OS_CONTACT_AGENT');?>
					<?php
					}else{
						echo JText::_('OS_CONTACT_OWNER');
					}
					?>
				</a>
				<?php
				}
				?>
			</div>
		</div>
		<?php
		$agent = ob_get_contents();
		ob_end_clean();
		$row->agenttype = $row->agent->agent_type;
		$row->agent = $agent;
		
		//end featured
		
		ob_start();
		if(count($row->comments) > 0){
			$comments = $row->comments;
			?>
			<div class="row-fluid">
				<div class="span12">
					<table  width="100%" style="border-collapse:separate;border:0px !important;">
						<tr>
							<td width="100%" style="padding:0px;">
								<div class="block_caption">
									<strong><?php echo JText::_('OS_COMMENTS')?></strong>
								</div>
							</td>
						</tr>
						<?php
						for($i=0;$i<count($comments);$i++){
						
						$comment = $comments[$i];
						?>
						<tr>
							<td width="100%" align="left" valign="top" style="padding:0px;padding-bottom:20px;background:url(<?php echo JURI::root()?>components/com_osproperty/images/assets/bg_content.gif);background-repeat:repeat-x;">
								<div style="font-weight:bold;">
									<table  width="100%" style="background:url(<?php echo JURI::root()?>components/com_osproperty/images/assets/bg_top_bar.gif);height:34px;">
										<tr>
											<td valign="middle" style="width:120px;padding:0px;">
												<?php
												for($j=1;$j<=$comment->rate;$j++){
													?>
													<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star_10.png" />
													<?php
												}
												$k = $j;
												
												
												for($j=$k;$j<=5;$j++){
													?>
													<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png" />
													<?php
												}
												
												?>
											</td>
											<td align="left" style="padding-left:20px;">
												<?php echo $comment->title?>
											</td>
										</tr>
									</table>
								</div>
								<div style="padding-left:0px;padding-top:5px;">
								<?php
									echo nl2br($comment->content);
								?></div>
							</td>
						</tr>
						<tr>
							<td width="100%" align="right" style="padding:0px;"> 
								<?php echo JText::_('OS_AUTHOR')?>: <strong><?php echo $comment->name?></strong> 
								<?php
								if(file_exists(JPATH_ROOT.'/media/com_osproperty/flags/'.$comment->country.'.png')){
								?>
									<img src="<?php echo JURI::root()?>media/com_osproperty/flags/<?php echo $comment->country?>.png"/>
								<?php
								}
								?>
								&nbsp;|
								&nbsp;
								<?php echo JText::_("OS_POST_DATE")?>: <strong><?php echo HelperOspropertyCommon::loadTime($comment->created_on,$configs[36]->fieldvalue);?></strong>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<?php
						}
						?>
					</table>
				</div>
			</div>
			<?php
		}else{
			?>
			<div style="text-align:center;padding:5px;">
				<?php echo JText::_('OS_THERE_ARE_NO_COMMENT_THERE');?>
			</div>
			<?php
		}
		$comments = ob_get_contents();
		ob_end_clean();
		$row->comments = $comments;
		
		$socialUrl = JRoute::_("index.php?option=com_osproperty&task=property_details&id=$row->id");
		$firstChar = substr($url,0,1);
		if($firstChar == "/"){
			$socialUrl = substr($socialUrl,1);
		}
		$socialUrl = JURI::root().$socialUrl;
		
		ob_start();
		if ($configs[89]->fieldvalue) {
		?>
			<div id="itp-social-buttons-box">
				<div id="eb_share_button">
					<?php
					$title = "";
					if($row->ref != ""){
						$title = $row->ref.", ";
					}
					
					$title.= OSPHelper::getLanguageFieldValue($row,'pro_name');
					$html  = HelperOspropertyCommon::getDeliciousButton( $title, $socialUrl );
	        		$html .= HelperOspropertyCommon::getDiggButton( $title, $socialUrl );
			        $html .= HelperOspropertyCommon::getFacebookButton( $title, $socialUrl );
			        $html .= HelperOspropertyCommon::getGoogleButton( $title, $socialUrl );
			        $html .= HelperOspropertyCommon::getStumbleuponButton( $title, $socialUrl );
			        $html .= HelperOspropertyCommon::getTechnoratiButton( $title, $socialUrl );
			        $html .= HelperOspropertyCommon::getTwitterButton( $title, $socialUrl );
			        echo $html ;
					?>
				</div>
				<div style="clear: both;">&nbsp;</div>
			</div>		
			<div style="clear: both;">&nbsp;</div>
<!-- End social sharing -->
		<?php	
		}
		$share = ob_get_contents();
		ob_end_clean();
		$row->share = $share;
		
		$db = JFactory::getDbo();
		$query = "Select count(a.id)from #__osrs_neighborhood as a"
				." inner join #__osrs_neighborhoodname as b on b.id = a.neighbor_id"
				." where a.pid = '$row->id'";
		$db->setQuery($query);
		$count_neighborhood = $db->loadResult();
		if($count_neighborhood > 0){
			ob_start();
			HelperOspropertyCommon::loadNeighborHood($row->id);
			$neighborhood = ob_get_contents();
			ob_end_clean();
			$row->neighborhood = $neighborhood;
			
			ob_start();
			HelperOspropertyCommon::loadNeighborHood1($row->id);
			$neighborhood1 = ob_get_contents();
			ob_end_clean();
			$row->neighborhood1 = $neighborhood1;
		}else{
			$row->neighborhood = "";
		}
		//Google map
		ob_start();
		$geocode = array();
		$geocode[0]->lat = $row->lat_add;
		$geocode[0]->long = $row->long_add;
		$geocode[0]->text = OSPHelper::getLanguageFieldValue($row,'pro_name');
		HelperOspropertyGoogleMap::loadGoogleMap($geocode,"map","");
		$google_header_js = ob_get_contents();
		ob_end_clean();
		$row->google_header_js = $google_header_js;

		//Random string
		$RandomStr = md5(microtime());// md5 to generate the random string
		$ResultStr = substr($RandomStr,0,5);//trim 5 digit 
		$row->ResultStr = $ResultStr;
		
		$row->relate = "";
		jimport('joomla.filesystem.file');
		if(($configClass['relate_properties'] == 1) and (count($row->relate_properties) > 0)){
			ob_start();
			$relates = $row->relate_properties;
			if(JFile::exists(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/relateproperties.php')){
				$tpl = new OspropertyTemplate(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/');
			}else{
				$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
			}
			$tpl->set('mainframe',$mainframe);
			$tpl->set('relates',$relates);
			$tpl->set('configClass',$configClass);
			$tpl->set('title',JText::_('OS_RELATE_PROPERTIES'));
			echo $tpl->fetch("relateproperties.php");
			$relate = ob_get_contents();
			ob_end_clean();
			$row->relate = $relate;
		}
		if(($configClass['relate_property_type'] == 1) and (count($row->relate_type_properties) > 0)){
			ob_start();
			$relates = $row->relate_type_properties;
			if(JFile::exists(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/relateproperties.php')){
				$tpl = new OspropertyTemplate(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/');
			}else{
				$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
			}
			$tpl->set('mainframe',$mainframe);
			$tpl->set('relates',$relates);
			$tpl->set('configClass',$configClass);
			$tpl->set('title',JText::_('OS_PROPERTIES_SAME_TYPES'));
			echo $tpl->fetch("relateproperties.php");
			$relate = ob_get_contents();
			ob_end_clean();
			$row->relate .= $relate;
		}
		
		if($configClass['show_walkscore'] == 1){
			if($configClass['ws_id'] != ""){
		        ob_start();
		        
		        $address	= $row->address;
				$address   .= " ".HelperOspropertyCommon::loadCityName($row->city);
				if($row->postcode != ""){
					$address .= " ".$row->postcode;
				}
				$address   .= " ".$row->state_name;
				$address   .= " ".$row->country_name;
				
				$latitude	= $row->lat_add;
				$longitude	= $row->long_add;
		        ?>
		        <script type='text/javascript'>
				var ws_wsid    = '<?php echo $configClass['ws_id'];?>';
				var ws_address = '<?php echo urlencode($address); ?>';
				var ws_lat     = '<?php echo $latitude ; ?>';
				var ws_lon     = '<?php echo $longitude ; ?>';
				var ws_height  = '<?php echo $configClass['ws_height'] ; ?>';
				<?php
				if($ismobile){
				?>
				var ws_width   = '230';
				var ws_layout  = 'vertical';
				<?php
				}else{
				?>
				var ws_width   = '<?php echo $configClass['ws_width'] ; ?>';
				var ws_layout  = 'horizontal';
				<?php
				}
				?>
				var ws_distance_units = '<?php echo $configClass['ws_unit'] ; ?>';
				</script>
				<style type='text/css'>
				#ws-walkscore-tile{position:relative;text-align:left}
				#ws-walkscore-tile *{float:none;}
				#ws-footer a,#ws-footer a:link{font:11px Verdana,Arial,Helvetica,sans-serif;margin-right:6px;white-space:nowrap;padding:0;color:#000;font-weight:bold;text-decoration:none}
				#ws-footer a:hover{color:#777;text-decoration:none}
				#ws-footer a:active{color:#b14900}
				</style>
				<div id='ws-walkscore-tile'>
				<div id='ws-footer' style='position:absolute;top:268px;left:8px;width:588px'>
				<form id='ws-form'>
				<a id='ws-a' href='www.walkscore.com/' target='_blank'>Find out your home's Walk Score:</a>
				<input type='text' id='ws-street' style='position:absolute;top:0px;left:225px;width:331px' />
				<input type='image' id='ws-go' src='http://www2.walkscore.com/images/tile/go-button.gif' height='15' width='22' border='0' alt='get my Walk Score' style='position:absolute;top:0px;right:0px' />
				</form>
				</div>
				</div>
				<script type='text/javascript' src='http://www.walkscore.com/tile/show-walkscore-tile.php'></script>
		        <?php
		        $walked_score = ob_get_contents();
		        ob_end_clean();
		        $row->ws = $walked_score;
			}
		}
		
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_photos where pro_id = '$row->id' and image <> '' order by ordering");
		$photos = $db->loadObjectList();
		
		$user = JFactory::getUser();
		$can_add_cmt = 0;
		if($user->id > 0){
			$db->setQuery("Select count(id) from #__osrs_comments where pro_id = '$row->id' and user_id = '$user->id'");
			$already_add_comment = $db->loadResult();
			if($already_add_comment > 0){
				$can_add_cmt = 0;
			}else{
				$can_add_cmt = 1;
			}
		}
		//add google map
		$db->setQuery("Select type_icon from #__osrs_types where id = '$row->pro_type'");
		$type_icon = $db->loadResult();
		if($type_icon == ""){
			$type_icon = "1.png";
		}
		$map_house_icon     = '/components/com_osproperty/images/assets/googlemapicons/'.$type_icon;
		
		$document  = JFactory::getDocument(); 
		$document->addScript('http://maps.google.com/maps/api/js?libraries=places&sensor=false');
		$gscript = '
	            var map;
	            window.addEvent("domready", function(){
	            	';
				for($i=0;$i<count($photos);$i++){
					$gscript .=	' 
	            	$$("#thumb'.$i.'").addEvent("click", function(e){
						';
					for($j=0;$j<count($photos);$j++){
						if($j != $i){
							$gscript .=	'$$("#img'.$j.'").hide();';
						}
					}
					$gscript .=	'$$("#img'.$i.'").show();';
					$gscript .= '
					});';
					
				}
		$google_map_overlay = $configClass['goole_map_overlay'];
		if($google_map_overlay == ""){
			$google_map_overlay = "ROADMAP";
		}
		$google_map_resolution = $configClass['goole_map_resolution'];
		if($google_map_resolution == 0){
			$google_map_resolution = 15;
			$population = 150;
		}elseif(($google_map_resolution > 0) and ($google_map_resolution <= 5)){
			$population = 400000;
		}elseif(($google_map_resolution > 5) and ($google_map_resolution <= 10)){
			$population = 2000;
		}elseif(($google_map_resolution > 10) and ($google_map_resolution <= 15)){
			$population = 150;
		}else{
			$population = 100;
		}
		
		
        $gscript .= ' 
                var zoom = '.$google_map_resolution.';
                var ipbaseurl = "' . JURI::root(true) . '";
                var coord = new google.maps.LatLng(' . $row->lat_add . ', ' . $row->long_add . ');
                var citymap = {};
			    citymap["chicago"] = {
			       center: new google.maps.LatLng('.$row->lat_add.', '.$row->long_add.'),population: '.$population.'
			    };
                var icon_url = ipbaseurl+"'.$map_house_icon.'";
                var streetview = new google.maps.StreetViewService();

                var mapoptions = {
                                    zoom: zoom,
                                    center: coord,
                                    //draggable: false,
                                    mapTypeControl: true,
                                    navigationControl: true,
                                    streetViewControl: false,
                                    mapTypeId: google.maps.MapTypeId.'.$google_map_overlay.',
                                    maxZoom: 21
                                }

                map = new google.maps.Map($("map_canvas"), mapoptions);
				';
        if($row->show_address == 1){
	        $gscript .= ' 
	                var marker  = new google.maps.Marker({
	                    position: coord,
	                    visible: true,
	                    flat: true,
	                    clickable: false,
	                    map: map,
	                    icon: icon_url
	                });
	                ';
        }else{
        	$gscript .= ' 
	                for (var city in citymap) {
			    	    var populationOptions = {
			    	      strokeColor: "#FF0000",
			    	      strokeOpacity: 0.8,
			    	      strokeWeight: 2,
			    	      fillColor: "#FF0000",
			    	      fillOpacity: 0.35,
			    	      map: map,
			    	      center: citymap[city].center,
			    	      radius: Math.sqrt(citymap[city].population) * 100
			    	    };
			    	    // Add the circle for this city to the map.
			    	    cityCircle = new google.maps.Circle(populationOptions);
			    	  }
        ';
        }
        
		 $gscript .= '
                $$("#agooglemap").addEvent("click", function(e){
                    setTimeout(function() {
                        google.maps.event.trigger(map, "resize");
                        map.setZoom( map.getZoom() );
                        map.setCenter(coord);
                    }, (10));
                });';
	    if($configClass['show_streetview'] == 1){
	    	$gscript .= '
                    var panoramaElement = $("pano");
                        streetview.getPanoramaByLocation(coord, 25, function(data, status){
                            switch(status){
                                case google.maps.StreetViewStatus.OK:
                                    $$("#astreetview").addEvent("click", function(e){
                                        setTimeout(function() {
                                            var panorama = new google.maps.StreetViewPanorama(panoramaElement, {
                                                position: coord
                                            });
                                            google.maps.event.trigger(panorama, "resize");
                                        }, (10));
                                    });
                                    break;
                                case google.maps.StreetViewStatus.ZERO_RESULTS:
                                    $$("#astreetview").setStyle("display", "none");
                                    break;
                                default:
                                    $$("#astreetview").setStyle("display", "none");
                            }
                        }); ';
	    }
		$gscript .= '
            });';
	
		if($themename == "default"){
			$document->addScriptDeclaration( $gscript );
		}
		
		if($configClass['show_gallery_tab'] == 1){
			ob_start();
			?>
			<?php
			HelperOspropertyCommon::slimboxGallery($row->id,$photos);
			?>
			<?php
			$gallery = ob_get_contents();
			ob_end_clean();
		}else{
			$gallery =  "";
		}
		
		$row->gallery = $gallery;
		
		
		//get subtitle
		if($row->show_address == 1){
			$row->subtitle = OSPHelper::generateAddress($row);
		}else{
			$addInfo = array();
			$addInfo1 = array();
			if($row->price > 0){
				$addInfo1[] = OSPHelper::generatePrice($row->curr,$row->price);
			}
			if(($row->bed_room > 0) and ($configClass['use_bedrooms'] == 1)){
				$addInfo[] = $row->bed_room." ".JText::_('OS_BEDROOMS');
			}
			if(($row->bath_room > 0) and ($configClass['use_bathrooms'] == 1)){
				$addInfo[] = OSPHelper::showBath($row->bath_room)." ".JText::_('OS_BATHROOMS');
			}
			if(($row->rooms > 0) and ($configClass['use_rooms'] == 1)){
				$addInfo[] = $row->rooms." ".JText::_('OS_ROOMS');
			}
			if(($row->square_feet > 0) and ($configClass['use_squarefeet'] == 1)){
				$addInfo[] = $row->square_feet." ".OSPHelper::showSquareSymbol();
			}
			$addInfo1[] = implode(", ",$addInfo);
			$row->subtitle = implode(" | ",$addInfo1);
		}
		
		ob_start();
		if($row->price_call == 0){
			if($row->price > 0){
				echo OSPHelper::generatePrice($row->curr,$row->price);
				if($row->rent_time != ""){
					echo "/".JText::_($row->rent_time);
				}
			}
		}elseif($row->price_call == 1){
			echo JText::_('OS_CALL_FOR_DETAILS_PRICE');
		}
		$price = ob_get_contents();
		ob_end_clean();
		$row->price_raw = $price;
		
		//price pure
		ob_start();
		if($row->price_call == 0){
			if($row->price > 0){
				?>
				<div id="currency_div" style="padding:0px;">
					<?php
					if(file_exists(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."oscalendar.php")){
						if($configClass['integrate_oscalendar'] == 1){
							echo JText::_('OS_FROM')." ";
						}
					}
					echo OSPHelper::generatePrice($row->curr,$row->price);
					if($row->rent_time != ""){
						?>
						/ <span id="mthpayment"><?php echo JText::_($row->rent_time);?></span>
						<?php
					}
					if($configClass['show_convert_currency'] == 1){
					?>
						<BR />
						<span style="font-size:11px;">
						<?php echo JText::_('OS_CONVERT_CURRENCY')?>: <?php echo $lists['curr']?>
						</span>
					<?php
					}
					?>
				</div>
				<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
				<input type="hidden" name="currency_item" id="currency_item" value="" />
				<?php
			}
		}elseif($row->price_call == 1){
			echo " <font style='font-size:16px;'>".JText::_('OS_CALL_FOR_DETAILS_PRICE')."</font>";
		}
		$price = ob_get_contents();
		ob_end_clean();
		$row->price1 = $price;
		//end price
		
		//price
		ob_start();
		echo "<span style='font-weight:bold;font-size:15px;'>";
		if($row->price_call == 0){
			if($row->price > 0){
				?>
				<div id="currency_div" style="padding:0px;">
					<?php
					echo JText::_('OS_PRICE');
					echo ": ";
					echo OSPHelper::generatePrice($row->curr,$row->price);
					if($row->rent_time != ""){
						echo "/".JText::_($row->rent_time);
					}
					if($configClass['show_convert_currency'] == 1){
					?>
					<BR />
					<span style="font-size:11px;">
					<?php echo JText::_('OS_CONVERT_CURRENCY')?>: <?php echo $lists['curr']?>
					</span>
					<?php
					}
					?>
				</div>
				<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
				<input type="hidden" name="currency_item" id="currency_item" value="" />
				<?php
			}
		}elseif($row->price_call == 1){
			echo " ".JText::_('OS_CALL_FOR_DETAILS_PRICE');
		}
		echo "</span>";
		$price = ob_get_contents();
		ob_end_clean();
		$row->price = $price;
		//end price
		
		$needs = array();
		$needs[] = "property_details";
		$need = $row->id;
		$itemid = OSPRoute::getItemid($needs);
		
		if($configClass['integrate_education'] == 1){
			if(!$row->postcode && ( !$row->latitude || !$row->longitude )) {
				//do nothing
			}else{
				$values = array();
	
		        $values['zip']			= $row->postcode;
		        $values['latitude']		= $row->lat_add;
		        $values['longitude']    = $row->long_add;
		        $values['key']			= 'dad04b84073a265e5244ba6db8892348';
		        $values['radius']		= $configClass['education_radius'];
		        $values['min']			= $configClass['education_min'];
		        $values['city']         = HelperOspropertyCommon::loadCityName($row->city);
		        $db->setQuery("Select state_name$lang_suffix as state_name from #__osrs_states where id = '$row->state'");
		        $values['state']        = $db->loadResult();
		        $max				    = $configClass['education_max'];
		        $debug				    = 0;
		
		        $i = 1;
		        $result = self::_getSchoolData($values);
		        
		        if(isset($result[0]['methodResponse']['faultString']) && $debug == 0) {
		        	//do nothing
		        }else{
		        	ob_start();
		        	?>
		        	<table class="table table-striped">
		                <thead>
		                    <tr>
		                        <th width="25%"><?php echo JText::_('OS_SCHOOL_NAME');?></th>
		                        <th width="25%" class="hidden-phone"><?php echo JText::_('OS_GRADE_LEVEL');?></th>
		                        <th width="25%"><?php echo JText::_('OS_DISTANCE_FROM_LISTING');?></th>
		                        <th width="25%" class="hidden-phone"><?php echo JText::_('OS_ENROLLMENT');?></th>
		                    </tr>
		                </thead>
		                <tbody>
		                    <?php
		                    if(isset($result[0]['methodResponse']['faultString'])) {
		                        echo '<tr><td colspan="4" align="center"><b>Education.com Error:</b> '.$result[0]['methodResponse']['faultString'].'</td></tr>';
		                        $no_results = true;
		                    } elseif( count($result[0]['methodResponse']) < 1 ){
		                        echo '<tr><td colspan="4" align="center"><b>'.JText::_('OS_NO_RESULTS_FOUND').'.</b></td></tr>';
		                        $no_results = true;
		                    } else {
		                        $k = 0;
		                        foreach ($result[0]['methodResponse'] as $school){
		                            echo '
		                            <tr>
		                                <td><a href="'.$school['school']['url'].'" target="_blank">'.$school['school']['schoolname'].'</a></td>
		                                <td class="hidden-phone">'.$school['school']['gradelevel'].'</td>
		                                <td>'.round($school['school']['distance'], 2).' miles</td>
		                                <td class="hidden-phone">'.$school['school']['enrollment'].'</td>
		                            </tr>';
		
		                            if ($i >= $max) break;
		                            $i++;
		                            $k = 1 - $k;
		                            $no_results = false;
		                        }
		                    }
		                    ?>
		                </tbody>
		                <tfoot>
		                    <tr>
		                        <td colspan="4" class="small" style="text-align: center;">
		                            Schools provided by: <a href="http://www.education.com/schoolfinder/" target="_blank"><img src ="<?php echo $result[1]['methodResponse']['logosrc']; ?>" alt="" /></a><br />
		                            <?php if(!$no_results) echo '<a href="'.$result[1]['methodResponse']['lsc'].'" target="_blank">See more information on '.$property->city.' schools from Education.com</a><br />'; ?>
		                            <?php echo $result[1]['methodResponse']['disclaimer']; ?>
		                        </td>
		                    </tr>  
		                </tfoot>
		            </table> 
		        	<?php
		        	$education = ob_get_contents();
		        	ob_end_clean();
		        	$row->education = $education;
		        }
			}
		}
		$db->setQuery("Select * from #__osrs_themes where name like '$themename'");
		$themeobj = $db->loadObject();
		
		$params = $themeobj->params;
		$params = new JRegistry($params) ;
		$report_image = "";
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		
		if($translatable){
			$language = Jfactory::getLanguage();
			$language = $language->getTag();
			$language = explode("-",$language);
			$langfolder = $language[0];
			if(file_exists(JPATH_ROOT."/components/com_osproperty/images/assets/".$langfolder."/isfeatured.png")){
				$feature_image = JURI::root()."components/com_osproperty/images/assets/".$langfolder."/isfeatured.png";
			}else{
				$feature_image = JURI::root()."components/com_osproperty/images/assets/isfeatured.png";
			}
			if(file_exists(JPATH_ROOT."/components/com_osproperty/images/assets/".$langfolder."/justadd.png")){
				$justadd_image = JURI::root()."components/com_osproperty/images/assets/".$langfolder."/justadd.png";
			}else{
				$justadd_image = JURI::root()."components/com_osproperty/images/assets/justadd.png";
			}
			if(file_exists(JPATH_ROOT."/components/com_osproperty/images/assets/".$langfolder."/justupdate.png")){
				$justupdate_image = JURI::root()."components/com_osproperty/images/assets/".$langfolder."/justupdate.png";
			}else{
				$justupdate_image = JURI::root()."components/com_osproperty/images/assets/justupdate.png";
			}
			if($configClass['enable_report'] == 1){
				if(file_exists(JPATH_ROOT."/components/com_osproperty/images/assets/".$langfolder."/report.png")){
					$report_image = JURI::root()."components/com_osproperty/images/assets/".$langfolder."/report.png";
				}else{
					$report_image = JURI::root()."components/com_osproperty/images/assets/report.png";
				}
			}
		}else{
			$feature_image = JURI::root()."components/com_osproperty/images/assets/isfeatured.png";
			$justadd_image = JURI::root()."components/com_osproperty/images/assets/justadd.png";
			$justupdate_image = JURI::root()."components/com_osproperty/images/assets/justupdate.png";
			$report_image = JURI::root()."components/com_osproperty/images/assets/report.png";
		}
		
		$fb = "";
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		//if($translatable){
			$language = JFactory::getLanguage();
			$ltag = $language->getTag();
			$ltag = str_replace("-","_",$ltag);
			$ltag = "&amp;locale=".$ltag;
		//}else{
			//$ltag = "";
		//}
		$facebook_like = $configClass['show_fb_like'];
		$url = JRoute::_("index.php?option=com_osproperty&task=property_details&id=$row->id");
		$url = JUri::getInstance()->toString(array('scheme', 'user', 'pass', 'host')).$url;
		$url = urlencode($url);
		if($facebook_like == 1){
			$facebook_api = $configClass['facebook_api'];
			$facebook_height = $configClass['facebook_height'];
			if($facebook_api == ""){
				$facebook_height = "10150130831010177";
			}
			if($facebook_height == ""){
				$facebook_height = "80";
			}
			ob_start();
			?>
			<div id="facebook" style="float:left; padding-left:5px;">
			<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo $url;?><?php echo $ltag;?>&amp;width&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=60&amp;appId=<?php echo $facebook_api;?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:<?php echo $facebook_height;?>px;" allowTransparency="true"></iframe></div>
			<?php
			$fb = ob_get_contents();
			ob_end_clean();
		}
		$document = JFactory::getDocument();
		if($configClass['show_twitter'] == 1){
			$tweet_via = $configClass['twitter_via'];
			$tweet_hash = $configClass['twitter_hash'];
			$title = "Tweet";
			$language = JFactory::getLanguage();
			$activate_lang = $language->getTag();
			$activate_lang = explode("-",$activate_lang);
			$activate_lang = $activate_lang[0];
			// make sure the hashtags are trimmed properly
	        $tags = array_map('trim', explode(",", $tweet_hash));
	        $tags = implode(',', $tags);
	        $tweetscript = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';
	        $document->addScriptDeclaration( $tweetscript );
	        $tweet_div = '<div style="position: relative; top: 10px; right: 10px;float:left;">            
			                   <a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$url.'" data-via="'.$tweet_via.'" data-lang="'.$activate_lang.'" data-hashtags="'.$tags.'">'.$title.'</a>
			               </div>';
	        $row->tweet_div = $tweet_div;	
		}
		
		if($configClass['google_plus'] == 1){
			$document->addScript("https://apis.google.com/js/plusone.js");
	        $gplus_div = '<div style="position: relative; top: 10px; right: 10px;float:left;">
		                    <g:plusone></g:plusone>
		                </div>';
	        $row->gplus_div = $gplus_div;
		}
		
		if($configClass['pinterest'] == 1){
			$description = urlencode(OSPHelper::getLanguageFieldValue($row,'pro_name'));
	        // make sure we have good path for image
	        $db->setQuery("Select image from #__osrs_photos where pro_id = '$row->id' order by ordering limit 1");
	        $default_image = $db->loadResult();
	        if($default_image != ""){
	        	$pin_image = JUri::root()."images/osproperty/properties/".$row->id."/thumb/".$default_image;
	        }else{
	        	$pin_image = JUri::root()."components/com_osproperty/images/assets/nopropertyphoto.png";
	        }
	        $pin_image = urlencode($pin_image);
	        $pinpath = "http://pinterest.com/pin/create/button/?url=".$url."&media=".$pin_image."&description=".$description;
	        // create javascript for pinterest request
	        $document->addScript( "//assets.pinterest.com/js/pinit.js" );
	        $pinterest =  '
                <div style="float:left;position: relative; top: 10px; right: 10px; width: 43px; height: 21px;">
                    <a href="'.$pinpath.'" class="pin-it-button" count-layout="horizontal" target="_blank">
                        <img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" alt="Pin It" />
                    </a>
                </div>';
	        $row->pinterest = $pinterest;
		}
		
		$db->setQuery("Select count(extension_id) from #__extensions where `type` like 'plugin' and `element` like 'jcomments' and `folder` like 'osproperty' and enabled = '1'");
		$countPlugin = $db->loadResult();
		
		jimport('joomla.filesystem.folder');
		if((JFolder::exists(JPATH_ROOT.'/components/com_jcomments')) and ($countPlugin > 0)){
			$integrateJComments = 1;
		}else{
			$integrateJComments = 0; 
		}
		//load plugin
		JPluginHelper::importPlugin('osproperty');
		$dispatcher 	= JDispatcher::getInstance();
		$topPlugin 		= $dispatcher->trigger('onTopPropertyDetails', array($row));
		$middlePlugin 	= $dispatcher->trigger('onMiddlePropertyDetails', array($row));
		$bottomPlugin 	= $dispatcher->trigger('onBottomPropertyDetails', array($row));
		
		$db->setQuery("Select a.id,a.keyword$lang_suffix as keyword from #__osrs_tags as a inner join #__osrs_tag_xref as b on b.tag_id = a.id where a.published = '1' and b.pid = '$row->id'");
		$tags = $db->loadObjectList();
		if(count($tags) > 0){
			$tagArr = array();
			for($i=0;$i<count($tags);$i++){
				$tag = $tags[$i];
				$link = JRoute::_('index.php?option=com_osproperty&task=property_tag&tag_id='.$tag->id.'&Itemid='.JRequest::getInt('Itemid',0));
				$tagArr[] = "<a href='$link'><span class='label label-important tagkeyword'>".$tag->keyword."</span></a>";
			}
		}
		
		if($configClass['use_property_history'] == 1){
			$query = $db->getQuery(true);
			$query->select("*")->from("#__osrs_property_price_history")->where("pid = '$row->id'")->order("`date` desc");
			$db->setQuery($query);
			$prices = $db->loadObjectList();
			
			if(count($prices) > 0){
				ob_start();
				?>
				<div class="row-fluid">
					<div class="span12" style="margin-left:0px;">
						<h3>
							<?php echo JText::_('OS_PROPERTY_HISTORY');?>
						</h3>
					</div>
					<div class="span12" style="margin-left:0px;">
						<table class="table">
							<thead>
								<tr>
									<th>
										<?php echo JText::_('OS_DATE');?>
									</th>
									<th>
										<?php echo JText::_('OS_EVENT');?>
									</th>
									<th>
										<?php echo JText::_('OS_PRICE');?>
									</th>
									<th>
										<?php echo JText::_('OS_SOURCE');?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach ($prices as $price){
									?>
									<tr>
										<td>
											<?php echo $price->date;?>
										</td>
										<td>
											<?php echo $price->event;?>
										</td>
										<td>
											<?php echo OSPHelper::generatePrice('',$price->price);?>
										</td>
										<td>
											<?php echo $price->source;?>
										</td>
									</tr>
									<?php 
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php 
				$price_history = ob_get_contents();
				ob_end_clean();
			}
			$row->price_history = $price_history;
			
			$query = $db->getQuery(true);
			$query->select("*")->from("#__osrs_property_history_tax")->where("pid = '$row->id'")->order("`tax_year` desc");
			$db->setQuery($query);
			$taxes = $db->loadObjectList();
			if(count($taxes) > 0){
				ob_start();
				?>
				<div class="row-fluid">
					<div class="span12" style="margin-left:0px;">
						<h3>
							<?php echo JText::_('OS_PROPERTY_TAX');?>
						</h3>
					</div>
					<div class="span12" style="margin-left:0px;">
						<table class="table">
							<thead>
								<tr>
									<th>
										<?php echo JText::_('OS_YEAR');?>
									</th>
									<th>
										<?php echo JText::_('OS_TAX');?>
									</th>
									<th>
										<?php echo JText::_('OS_CHANGE');?>
									</th>
									<th>
										<?php echo JText::_('OS_TAX_ASSESSMENT');?>
									</th>
									<th>
										<?php echo JText::_('OS_TAX_ASSESSMENT_CHANGE');?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach ($taxes as $tax){
									?>
									<tr>
										<td>
											<?php echo $tax->tax_year;?>
										</td>
										<td>
											<?php echo OSPHelper::generatePrice('',$tax->property_tax);?>
										</td>
										<td>
											<?php 
											if($tax->tax_change != ""){
											?>
												<?php echo $tax->tax_change;?> %
											<?php }else { ?>
												--
											<?php } ?>
										</td>
										<td>
											<?php echo OSPHelper::generatePrice('',$tax->tax_assessment);?>
										</td>
										<td>
											<?php 
											if($tax->tax_assessment_change != ""){
											?>
												<?php echo $tax->tax_assessment_change;?> %
											<?php }else { ?>
												--
											<?php } ?>
										</td>
									</tr>
									<?php 
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php 
				$tax = ob_get_contents();
				ob_end_clean();
			}
			$row->tax = $tax;
		}
		if($configClass['use_open_house'] == 1){
			$query = $db->getQuery(true);
			$query->select("*")->from("#__osrs_property_open")->where("pid = '$row->id'")->order("start_from desc");
			$db->setQuery($query);
			$opens = $db->loadObjectList();
			ob_start();
			?>
	   		<div class="row-fluid img-polaroid inspectiontimes">
		   		<strong><?php echo Jtext::_('OS_OPEN_FOR_INSPECTION_TIMES')?></strong>
		   		<div class="clearfix"></div>
		   		<div class="span12" style="margin-left:0px;font-size:x-small;">
		    		<?php 
		    		if(count($opens) > 0){
		    			foreach ($opens as $info){
		     			?>
		     			<?php echo JText::_('OS_FROM')?>: <?php echo date($configClass['general_date_format'],strtotime($info->start_from));?>
		     			-
		     			<?php echo JText::_('OS_TO')?>: <?php echo date($configClass['general_date_format'],strtotime($info->end_to));?>
		     			<div class="clearfix"></div>
		     			<?php
		    			} 
		    		}else{
		    			echo JText::_('OS_NO_INSPECTIONS_ARE_CURRENTLY_SCHEDULED');
		    		}
		    		?>
		   		</div>
	   		</div>
			<?php
			$open_hours = ob_get_contents();
			ob_end_clean();
			
		}
		$row->open_hours = $open_hours;
		
		if($user->id > 0){
			$db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
			$count = $db->loadResult();
			if($count > 0){
				$inFav = 1;
			}else{
				$inFav = 0;
			}
		}
        $db->setQuery("Select * from #__osrs_themes where name like '$themename'");
        $themeobj = $db->loadObject();
        $params = $themeobj->params;
        $params = new JRegistry($params) ;
		$tpl = new OspropertyTemplate();
		$tpl->set('integrateJComments',$integrateJComments);
		$tpl->set('facebook_like',$fb);
		$tpl->set('feature_image',$feature_image);
		$tpl->set('justadd_image',$justadd_image);
		$tpl->set('justupdate_image',$justupdate_image);
		$tpl->set('report_image',$report_image);
		$tpl->set('params',$params);
		$tpl->set('row',$row);
		$tpl->set('itemid',$itemid);
		$tpl->set('configs',$configs);	
		$tpl->set('socialUrl',$socialUrl);
		$tpl->set('configClass',$configClass);
		$tpl->set('owner',$owner);
		$tpl->set('can_add_cmt',$can_add_cmt);
		$tpl->set('photos',$photos);
		$tpl->set('ismobile',$ismobile);
		$tpl->set('topPlugin',$topPlugin);
		$tpl->set('middlePlugin',$middlePlugin);
		$tpl->set('bottomPlugin',$bottomPlugin);
		$tpl->set('tagArr',$tagArr);
		$tpl->set('themename',$themename);
        $tpl->set('params',$params);
		$tpl->set('inFav',$inFav);
		$tpl->set('temp_path_img',JURI::root()."components/com_osproperty/templates/$themename/img");
		JHTML::_('behavior.modal','a.osmodal');
		$body = $tpl->fetch("details.html.tpl.php");	
		echo $body;	
	}
	
	/**
	 * Listing photos
	 *
	 * @param unknown_type $option
	 * @param unknown_type $property_id
	 * @param unknown_type $photos
	 */
	static function listingPhotos($option,$property_id,$photos){
		global $mainframe;
		$document = JFactory::getDocument();
		$tpl = new OspropertyTemplate();
		$tpl->set('property_id',$property_id);
		$tpl->set('photos',$photos);
		$tpl->set('temp_path_img',JURI::root()."components/com_osproperty/templates/default/img");
		$body = $tpl->fetch("photos.html.tpl.php");
		return $body;
	}
	
	/**
	 * Edit Property
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 * @param unknown_type $amenities
	 * @param unknown_type $amenitylists
	 * @param unknown_type $groups
	 */
	static function editProperty($option,$row,$lists,$amenities,$amenitylists,$groups){
		global $mainframe;
		$db = JFactory::getDBO();
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?Itemid='.JRequest::getInt('Itemid',0))?>" name="adminForm" id="adminForm">
		<table  width="100%">
			<tr>
				<td width="100%">
					<fieldset id="filter-bar">
						<label>
							<?php echo JText::_('OS_FILTER')?>: &nbsp;
						</label>
						<input type="text" name="keyword" value="<?php echo OSPHelper::getStringRequest('keyword','','')?>" />
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%">
					<fieldset id="filter-bar">
						<?php echo $lists['company']?>
						<?php echo $lists['agent']?>
						<?php echo $lists['category']?>
						<?php echo $lists['type']?>
						<?php echo $lists['country']?>
						<?php echo $lists['states']?>
						<?php echo $lists['nbed']?>
						<?php echo $lists['nbath']?>
						<?php echo $lists['nrooms']?>
						<?php echo $lists['state']?>
					</fieldset>
				</td>
			</tr>
		</table>
		<table  width="100%" class="adminlist">
			<thead>
				<tr>
					<th width="2%">
				
					</th>
					<th width="3%">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
					</th>
					<th width="5%">
					 	<!-- Property image -->
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PROPERTY_NAME'), 'a.pro_name', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_CATEGORY'), 'b.category_name', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_AGENT'), 'c.name', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="10%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_COUNTRY'), 'a.country', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_STATE'), 'a.state', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="8%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PRICE'), 'a.price', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_BED'), 'a.bed_room', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_BATH'), 'a.bath_room', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_ROOMS'), 'a.rooms', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_HITS'), 'a.hits', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_FEATURED'), 'a.isFeatured', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_APPROVED'), 'a.approved', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_ACCESS'), 'a.access', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%">
						<?php echo JText::_('OS_PUBLISH');?>
					</th>
					<th width="5%">
						<?php echo JText::_('OS_PRINT');?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="18" style="text-align:center;">
						<?php
							echo $pageNav->getListFooter();
						?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$db = JFactory::getDBO();
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row = $rows[$i];
				$checked = JHtml::_('grid.id', $i, $row->id);
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=properties_edit&cid[]='. $row->id );
				$published 	= JHTML::_('grid.published', $row, $i ,'tick.png', 'publish_x.png', 'properties_');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center">
						<?php echo $checked; ?>
					</td>
					<td align="center">
						<?php
						$db->setQuery("Select * from #__osrs_photos where pro_id = '$row->id' order by ordering limit 1");
						$photo = $db->loadObjectList();
						if(count($photo) >0){
							$photo = $photo[0];
							?>
							<a href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id?>/<?php echo $photo->image?>" class="modal">
								<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id?>/thumb/<?php echo $photo->image?>" width="50" border="0" />
							</a>
							<?php
						}else{
							?>
							<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" width="50" />
							<?php
						}
						?>
					</td>
					<td align="left">
						<a href="<?php echo $link?>">
							<?php echo OSPHelper::getLanguageFieldValue($row,'pro_name');?>
						</a>
					</td>
				 	<td align="left">
						<?php echo OSPHelper::getLanguageFieldValue($row,'category_name');?>
					</td>
					<td align="left">
						<?php echo $row->agent_name?>
					</td>
					<td align="left">
						<?php echo $row->country_name?>
					</td>
					<td align="left">
						<?php 
						$db->setQuery("Select state_name from #__osrs_states where id = '$row->state'");
						echo $db->loadResult();
						?>
					</td>
					<td align="center">
						<?php
						if($row->price_call == 0){
							/*
							if($row->price_original != ""){
								?>
								<span style="text-decoration:line-through;"><?php echo HelperOspropertyCommon::loadCurrency($row->curr)." ".HelperOspropertyCommon::showPrice($row->price_original)?></span>
								<?php
							}
							*/
							if($row->price != ""){
								?>
								<BR />
								<?php echo OSPHelper::generatePrice($row->curr,$row->price);?>
								<?php
								if($row->rent_time != ""){
									echo "/".JText::_($row->rent_time);
								}
							}
						}elseif($row->price_call == 1){
							echo JText::_('OS_CALL_FOR_PRICE');
						}
						?>
					</td>
					<td align="center">
						<?php echo $row->bed_room ?>
					</td>
					<td align="center">
						<?php echo OSPHelper::showBath($row->bath_room); ?>
					</td>
					<td align="center">
						<?php echo $row->rooms ?>
					</td>
					<td align="center">
						<?php echo $row->hits ?>
					</td>
					<td align="center">
						<?php
							if($row->isFeatured == 1){
								?>
								<a href="index.php?option=com_osproperty&task=properties_changeType&type=isFeatured&cid[]=<?php echo $row->id?>&v=0" title="Change featured status">
									<img src="<?php echo JURI::root()?>administrator/templates/bluestork/images/admin/tick.png" />
								</a>
								<?php
							}else{
								?>
								<a href="index.php?option=com_osproperty&task=properties_changeType&type=isFeatured&cid[]=<?php echo $row->id?>&v=1" title="Change featured status">
									<img src="<?php echo JURI::root()?>administrator/templates/bluestork/images/admin/publish_x.png" />
								</a>
								<?php
							}
						?>
					</td>
					<td align="center">
						<?php
							if($row->approved == 1){
								?>
								<a href="index.php?option=com_osproperty&task=properties_changeType&type=approved&cid[]=<?php echo $row->id?>&v=0" title="Change approval status">
									<img src="<?php echo JURI::root()?>administrator/templates/bluestork/images/admin/tick.png" />
								</a>
								<?php
							}else{
								?>
								<a href="index.php?option=com_osproperty&task=properties_changeType&type=approved&cid[]=<?php echo $row->id?>&v=1" title="Change approval status">
									<img src="<?php echo JURI::root()?>administrator/templates/bluestork/images/admin/publish_x.png" />
								</a>
								<?php
							}
						?>
					</td>
					<td align="center">
						<?php
						switch ($row->access){
							case "0":
								echo JText::_('OS_PUBLIC');
							break;
							case "1":
								echo JText::_('OS_REGISTERED');
							break;
							case "2":
								echo JText::_('OS_SPECIAL');
							break;
						}
						?>
					</td>
					<td align="center">
						<?php echo $published?>
					</td>
					<td>
						<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/print.png" />
					</td>
				</tr>
			<?php
				$k = 1 - $k;	
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="properties_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		</form>
		</form>
		<?php
	}
	
	
	/**
	 * Edit Properties
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	static function editListing($option,$row,$lists,$amenities,$amenitylists,$groups,$configs,$neighborhoods,$translatable){
		global $mainframe,$configs,$configClass,$languages;
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root()."components/com_osproperty/templates/default/style/style.css");
		$document->addScript(JURI::root()."components/com_osproperty/js/ajax.js");
		JHTML::_('behavior.tooltip');
		
		if($row->id > 0){
			$edit = JText::_('OS_EDIT');
		}else{
			$edit = JText::_('OS_ADDNEW');
		}
		
		//jimport('joomla.html.pane');
		//$pane = &JPane::getInstance('sliders',array('startOffset'=>0,'useCookie' => 0));
		?>
		<style>
		fieldset label, fieldset span.faux-label {
		    clear: right;
		}
		</style>
		<script language="javascript">
		function check_file(id){
            str=document.getElementById(id).value.toUpperCase();
			var elementspan = document.getElementById(id + 'div');
			//suffix=".JPG";
			var blnValid = false;
			var _validFileExtensions = [".jpg", ".jpeg"];
			for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (str.substr(str.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
			if(!blnValid){
				alert('<?php echo JText::_('OS_ALLOW_FILE')?>: *.jpg, *.jpeg');
				document.getElementById(id).value='';
				if(elementspan != null){
					elementspan.innerHTML = elementspan.innerHTML;
				}
	        }else{
	        	//clientWidth,clientHeight;
	        	clientWidth = document.getElementById(id).clientWidth;
	        	clientHeight = document.getElementById(id).clientHeight;
	        	<?php
	        	if((intval($max_width) > 0) and (intval($max_height) > 0)){
	        		?>
	        		var max_width = <?php echo $max_width?>;
	        		max_width = parseInt(max_width);
	        		var max_height = <?php echo $max_height?>;
	        		max_height = parseInt(max_height);
	        		if((clientWidth > max_width) || (clientHeight > max_height)){
	        			alert("<?php echo JText::_('OS_YOUR_PHOTO_IS_OVER_LIMIT_SIZE')?>");
	        			document.getElementById(id).value='';
	        			if(elementspan != null){
				        	elementspan.innerHTML = elementspan.innerHTML;
				        }
	        		}
	        		<?php
	        	}
	        	?>
	        	
	        }
	    }
		function loadState(country_id,state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoStateCityAddProperty(country_id,state_id,city_id,'country','state',live_site);
		}
		function loadCity(state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoCityAddProperty(state_id,city_id,'state',live_site);
		}
		function addPhoto(){
			var current_number_photo = document.getElementById('current_number_photo');
			current_number = parseInt(current_number_photo.value);
			current_number++;
			var temp = document.getElementById('div_' + current_number);
			if(temp != null){
				if(temp.style.display == "none"){
					temp.style.display = "block";
				}
			}
			current_number_photo.value = current_number;
		}
		function submitftForm(save) {
			var form = document.ftForm1;
			var temp1,temp2;
			var cansubmit = 1;
			var require_field = document.getElementById('require_field');
			require_field = require_field.value;
			var require_label = document.getElementById('require_label');
			require_label = require_label.value;
			var require_fieldArr = require_field.split(",");
			var require_labelArr = require_label.split(",");
			for(i=0;i<require_fieldArr.length;i++){
				temp1 = require_fieldArr[i];
				pos   = temp1.indexOf("@");
				if(pos > 0){
					temp1a = temp1.substring(0,pos);
					temp1b = temp1.substring(pos+1);
					temp2  = document.getElementById(temp1a);
					temp3  = document.getElementById(temp1b);
					if((temp2!= null) && (temp3!= null)){
						if((temp2.value == "") && (temp3.value == "") && (cansubmit == 1)){
							alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY_FIELD')?>");
							temp2.focus();
							cansubmit = 0;
						}
					}
				}else{
					if((temp1 == "category_id") && (cansubmit == 1)){
						//temp2 = document.ftForm1.category_id;
						if (jQuery('#categoryIds option:selected').length == 0){
							alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY_FIELD')?>");
							jQuery('#categoryIds').focus();
							cansubmit = 0;
							return false;
						}
					}else{
						temp2 = document.getElementById(temp1);
					}
					if(temp2 != null){
						if((temp2.value == "") && (cansubmit == 1)){
							alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY_FIELD')?>");
							temp2.focus();
							cansubmit = 0;
						}
					}else{
						temp2 = document.getElementsByName(temp1);
						if(temp2.length > 0){
							if(cansubmit == 1){
								cansubmit1 = 0;
								//if(temp2.type == "checkbox"){
									for(var j=0; j < temp2.length; j++) {
						   				if(temp2[j].checked == true){
						   					cansubmit1 = 1;
						   				}
									}
								//}
								if(cansubmit1 == 0){
									alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY_FIELD')?>");
									temp2.focus();
									cansubmit = 0;
									//return false;
								}
							}
						}else{
							temp2 = document.getElementsByName(temp1 + "[]");
							if(temp2.length > 0){
								if(cansubmit == 1){
									cansubmit1 = 0;
									//if(temp2.type == "checkbox"){
										for(var j=0; j < temp2.length; j++) {
							   				if(temp2[j].checked == true){
							   					cansubmit1 = 1;
							   				}
										}
									//}
									
									if(cansubmit1 == 0){
										alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY_FIELD')?>");
										temp2.focus();
										cansubmit = 0;
										//return false;
									}
								}
							}
						}
					}
				}
			}
			
			if(cansubmit == 1){
				var task = form.task;
				if(save == 1){
					task.value = "property_save";
				}else if(save == 0){
					task.value = "property_apply";
				}
				form.submit();
			}
		}
		function showPriceFields(){
			var price_call = document.getElementById('price_call');
			var pricediv   = document.getElementById('pricediv');
			if(price_call.value == 0){
				pricediv.style.display = "block";
			}else{
				pricediv.style.display = "none";
			}
		}
		function gotoDefaultPage(){
			<?php
			if(HelperOspropertyCommon::isAgent()){
				$need = array();
			 	$needs = array();
				$needs[] = "agent_editprofile";
				$needs[] = "agent_default";
				$needs[] = "aeditdetails";
				$itemid = OSPRoute::getItemid($needs);
				?>
				location.href = "<?php echo JRoute::_('index.php?option=com_osproperty&task=agent_editprofile&Itemid='.$itemid)?>";
				<?php
			}elseif(HelperOspropertyCommon::isCompanyAdmin()){
				$needs = array();
				$needs[] = "company_properties";
				$itemid = OSPRoute::getItemid($needs);
				?>
				location.href = "<?php echo JRoute::_('index.php?option=com_osproperty&task=company_properties&Itemid='.$itemid)?>";
				<?php 
			}
			?>
		}
		</script>
		<?php
		//$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
		jimport('joomla.filesystem.file');
		if(JFile::exists(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/propertyedit.php')){
			$tpl = new OspropertyTemplate(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/');
		}else{
			$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
		}
		$tpl->set('mainframe',$mainframe);
		$tpl->set('lists',$lists);
		$tpl->set('neighborhoods',$neighborhoods);
		$tpl->set('configClass',$configClass);
		$tpl->set('row',$row);
		$tpl->set('edit',$edit);
		$tpl->set('lists',$lists);
		$tpl->set('amenities',$amenities);	
		$tpl->set('amenitylists',$amenitylists);
		$tpl->set('groups',$groups);
		$tpl->set('configs',$configs);
		$tpl->set('configClass',$configClass);
		$tpl->set('languages',$languages);
		$tpl->set('translatable',$translatable);
		$body = $tpl->fetch("propertyedit.php");
		echo $body;
	}
	
	
	/**
	 * Update Form step 1
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $coupon
	 */
	function updateFormStep1($option,$rows,$coupon){
		global $mainframe,$configs,$configClass;
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		JHTML::_('behavior.modal','a.osmodal');
		
		$amount = $configClass['general_featured_upgrade_amount'];
		?>
		<script language="javascript">
		function removeItem(id){
			var answer = confirm("<?php echo JText::_('OS_DO_YOU_WANT_TO_REMOVE_THE_PROPERTY')?>");
			if(answer == 1){
				document.ftForm.task.value = "property_removeUpgrade";
				document.ftForm.remove_value.value = id;
				document.ftForm.submit();
			}
		}
		
		function checkCouponCode(){
			var coupon_code = document.getElementById('coupon_code');
			checkCouponcode('<?php echo $coupon->id?>',coupon_code.value,'<?php echo JURI::root()?>');
			
		}
		</script>
		<form method="POST" action="<?php echo JRoute::_('index.php?Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm">
		<table  width="100%" style="border:0px !important;">
			<tr>
				<td width="100%">
					<font style="font-size:18px;font-weight:bold;">
						<?php echo JText::_('OS_UPGRADE_PROPERTIES_TO_FEATURE')?>
					</font>
				</td>
			</tr>
			<tr>
				<td width="100%" style="border:0px;padding-top:10px;">
					<table  width="100%" style="border:0px !important;">
						<tr>
							<td width="80%" class="header_td" style="text-align:left;padding-left:20px;">
								<?php
									echo JText::_('OS_PROPERTY');
								?>
							</td>
							<td width="10%" class="header_td">
								<?php
									echo JText::_('OS_REMOVE');
								?>
							</td>
							<?php
							if($configClass['general_featured_upgrade_amount'] > 0){
							?>
							<td width="10%" class="header_td" style="text-align:right;">
								<?php
									echo JText::_('OS_TOTAL');
								?>
							</td>
							<?php
							}	
							?>
						</tr>
						<?php
						$total = 0;
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							$total = $total + $amount;
							$link = JURI::root()."index.php?option=com_osproperty&task=property_details&id=".$row->id;
							?>
							<input type="hidden" name="cid[]" value="<?php echo $row->id?>" />
							<tr>
								<td class="data_td" width="80%">
									<table  width="100%" style="border:0px !important;">
										<tr>
											<td width="70">
												<?php
												if($row->image != ""){
													?>
													<a href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id?>/<?php echo $row->image?>" class="osmodal">
														<?php
														OSPHelper::showPropertyPhoto($row->image,'thumb',$row->id,'width:70px;','img-rounded','');
														?>
													</a>
													<?php
												}else{
													OSPHelper::showPropertyPhoto($row->image,'thumb',$row->id,'width:70px;','img-rounded','');
												}
												?>
											</td>
											<td align="left" style="padding-left:20px;">
												<a href="<?php echo $link?>" class="osmodal" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {}}">
												<strong>
												<?php
													echo $row->pro_name;
												?>
												</strong>
												</a>
											</td>
										</tr>
									</table>
									
									
								</td>
								<td class="data_td" style="text-align:center;">
									<a href="javascript:removeItem('<?php echo $row->id?>')">
										<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/delete.png" /> 	
									</a>
								</td>
								<?php
								if($configClass['general_featured_upgrade_amount'] > 0){
								?>
								<td class="data_td" style="text-align:right;">
									<?php echo HelperOspropertyCommon::loadDefaultCurrency(1)?>&nbsp;
									<?php echo HelperOspropertyCommon::showPrice($amount)?>
								</td>
								<?php } ?>
							</tr>
							<?php	
						}
						?>
						<?php
						if($configClass['general_featured_upgrade_amount'] > 0){
						?>
						<tr>
							<td class="data_td" width="90%" colspan="2" style="text-align:right;font-weight:bold;background-color:#efefef;">
								<?php echo JText::_('OS_TOTAL')?>
							</td>
							<td class="data_td" style="text-align:right;background-color:#efefef;">
							    <?php echo HelperOspropertyCommon::loadDefaultCurrency(1)?>&nbsp;
								<?php echo HelperOspropertyCommon::showPrice($total)?>
							</td>
						</tr>
						<?php } ?>
					</table>
				</td>
			</tr>
		</table>
		<BR />
		<?php
		$number_check = "";
		$number_check = $_COOKIE['u'.$user->id];
		
		if($coupon->id > 0){ // there are available coupon

			//check to see whether if user is already use the coupon
			if(!HelperOspropertyCommon::isAlreadyUsed($coupon->id)){
				//show the form
			
				?>
				<table  width="100%"  style="border-collapse:separate;border:0px !important;">
					<tr>
						<td width="100%" style="border:1px solid #CCC;padding:10px;">
							<!-- Coupon -->
							<div id="coupon_code_div">
							<?php
							if($_COOKIE['coupon_code_awarded'] == $coupon->id){
								?>
								<font style="font-size:15px;font-weight:bold;color:#0E8247;">
									<?php echo JText::_('Congratulation, you got '.$coupon->discount.'% discount of the coupon ['.$coupon->coupon_name .']')?>
								</font>
								<?php
							}else{
								if($number_check < 4){
									echo JText::_('OS_PLEASE_ENTER_COPON_CODE_INTO_UNPUTBOX');
									?>
									<BR /><BR />
									<input type="text" name="coupon_code" id="coupon_code" class="input-mini" size="10" />  
									<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_CHECK_COUPON_CODE')?>" onclick="javascript:checkCouponCode(<?php echo $coupon->id?>)" />
								<?php
								}else{
									?>
									<font style="font-size:15px;font-weight:bold;color:#C53535;">
										<?php
											echo JText::_('OS_WRONG_COUPON_CODE_YOU_HAVE_ENTERED4_TIMES');
										?>
									</font>
									<?php
								}
							}
							?>
							</div>
						</td>
					</tr>
				</table>
				<?php
			}
		}
		?>
		<BR />
		<BR />
		<table  width="100%" style="border-collapse:separate;border:0px !important;">
			<tr>
				<td width="100%" style="text-align:right;border:0px;">
					<input type="button" value="<?php echo JText::_('OS_BACK')?>" class="btn btn-warning" onclick="javascript:history.go(-1);"/>
					<input type="button" value="<?php echo JText::_('OS_CONFIRM')?>" class="btn btn-info" onclick="javascript:document.ftForm.submit();" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="property_confirmupgrade" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
		<input type="hidden" name="remove_value" id="remove_value" value="" />
		<input type="hidden" name="live_site" value="<?php echo JURI::root()?>" />
		</form>
		<?php
	}
	
	
	/**
	 * Upgrade normal properties to featured properties in case you are integrating OS Property with OS Membership
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $coupon
	 */
	function updateFormStep1WithMembership($option,$rows,$coupon){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		JHTML::_('behavior.modal','a.osmodal');
		
		$amount = $configClass['general_featured_upgrade_amount'];
		?>
		<script language="javascript">
		function removeItem(id){
			var answer = confirm("<?php echo JText::_('OS_DO_YOU_WANT_TO_REMOVE_THE_PROPERTY')?>");
			if(answer == 1){
				document.ftForm.task.value = "property_removeUpgrade";
				document.ftForm.remove_value.value = id;
				document.ftForm.submit();
			}
		}
		
		function checkCouponCode(){
			var coupon_code = document.getElementById('coupon_code');
			checkCouponcode('<?php echo $coupon->id?>',coupon_code.value,'<?php echo JURI::root()?>');
			
		}
		</script>
		<form method="POST" action="<?php echo JRoute::_('index.php?Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm">
		<table  width="100%" style="border:0px !important;">
			<tr>
				<td width="100%">
					<font style="font-size:18px;font-weight:bold;">
						<?php echo JText::_('OS_UPGRADE_PROPERTIES_TO_FEATURE')?>
					</font>
				</td>
			</tr>
			<tr>
				<td width="100%" style="border:0px;padding-top:10px;">
					<table  width="100%" style="border:0px !important;">
						<tr>
							<td width="20%" class="header_td" style="text-align:left;padding-left:20px;">
								<?php
									echo JText::_('OS_PROPERTY');
								?>
							</td>
							<td width="70%" class="header_td">
								<?php
									echo JText::_('OS_FEATURED_SUBSCRIPTION_PLANS');
								?>
							</td>
							<td width="5%" class="header_td">
								<?php
									echo JText::_('OS_REMOVE');
								?>
							</td>
						</tr>
						<?php
						$total = 0;
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							$total = $total + $amount;
							$link = JURI::root()."index.php?option=com_osproperty&task=property_details&id=".$row->id;
							?>
							<input type="hidden" name="cid[]" value="<?php echo $row->id?>" />
							<tr>
								<td class="data_td" width="20%" style="padding-left:5px;">
									<a href="<?php echo $link?>" class="osmodal" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {}}">
										<strong>
										<?php
											echo $row->pro_name;
										?>
										</strong>
									</a>
								</td>
								<td class="data_td" width="70%">
									<?php
									$agentAcc = HelperOspropertyCommon::returnAccountValue(false);
									HelperOspropertyCommon::generateMembershipFormUpgradeProperties($agentAcc,'property',$row->id);
									?>
								</td>
								<td class="data_td" style="text-align:center;">
									<a href="javascript:removeItem('<?php echo $row->id?>')">
										<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/delete.png" /> 	
									</a>
								</td>
							</tr>
							<?php	
						}
						?>
						
					</table>
				</td>
			</tr>
		</table>
		<table  width="100%" style="border-collapse:separate;border:0px !important;">
			<tr>
				<td width="100%" style="text-align:right;border:0px;">
					<input type="button" value="<?php echo JText::_('OS_BACK')?>" class="btn btn-warning" onclick="javascript:history.go(-1);"/>
					<input type="button" value="<?php echo JText::_('OS_CONFIRM')?>" class="btn btn-info" onclick="javascript:document.ftForm.submit();" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="property_confirmupgradewithMembership" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
		<input type="hidden" name="remove_value" id="remove_value" value="" />
		<input type="hidden" name="live_site" value="<?php echo JURI::root()?>" />
		</form>
		<?php
	}
	
	/**
	 * Confirmation Upgrade
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $coupon
	 * @param unknown_type $awarded
	 */
	function confirmUpgrade($option,$rows,$coupon,$awarded){
		global $mainframe,$configs,$configClass;
		$db = JFactory::getDBO();
		JHTML::_('behavior.modal','a.osmodal');
		
		$amount = $configClass['general_featured_upgrade_amount'];
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm">
		<table  width="100%" style="border:0px !important;">
			<tr>
				<td width="100%">
					<font style="font-size:18px;font-weight:bold;">
						<?php echo JText::_('OS_CONFIRM_UPGRADE_PROPERTIES_TO_FEATURE')?>
					</font>
				</td>
			</tr>
			<tr>
				<td width="100%" style="border:0px;">
					<table  width="100%" style="border-collapse:separate;border:0px !important;">
						<tr>
							<td width="80%" class="header_td" style="text-align:left;padding-left:20px;">
								<?php
									echo JText::_('OS_PROPERTY');
								?>
							</td>
							<td width="10%" class="header_td" style="text-align:right;">
								<?php
									echo JText::_('OS_TOTAL');
								?>
							</td>
						</tr>
						<?php
						$total = 0;
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							$total = $total + $amount;
							
							$link = JURI::root()."index.php?option=com_osproperty&task=property_details&id=".$row->id;
							?>
							<input type="hidden" name="cid[]" value="<?php echo $row->id?>" />
							<tr>
								<td class="data_td" width="80%">
									<table  width="100%" style="border:0px !important;">
										<tr>
											<td width="70">
												<?php
												if($row->image != ""){
													?>
													<a href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id?>/<?php echo $row->image?>" class="osmodal">
													<?php
													OSPHelper::showPropertyPhoto($row->image,'thumb',$row->id,'width:70px;','img-rounded','');
													?>
													</a>
													<?php
												}
												?>
											</td>
											<td align="left" style="padding-left:20px;">
												<a href="<?php echo $link?>" class="osmodal" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {}}">
												<strong>
												<?php
													echo $row->pro_name;
												?>
												</strong>
												</a>
											</td>
										</tr>
									</table>
								</td>
							
								<td class="data_td" style="text-align:right;">
								    <?php echo HelperOspropertyCommon::loadDefaultCurrency(1)?>&nbsp;
									<?php echo HelperOspropertyCommon::showPrice($amount)?>
								</td>
							</tr>
							<?php	
						}
						?>
						<?php
						if($awarded == 1){
							?>
							<tr>
								<td class="data_td" width="80%" colspan="1" style="text-align:right;font-weight:bold;background-color:#FBECEC;">
									<?php echo JText::_('OS_COUPON')?> [<?php echo $coupon->coupon_name?>]
								</td>
								<td class="data_td" style="text-align:right;background-color:#FBECEC;">
									- <?php 
									  $discount = ($coupon->discount/100)*$total;
									  echo HelperOspropertyCommon::showPrice($discount);
									  $total = $total - $discount;
									  echo $configs[18]->fieldvalue?>
								</td>
							</tr>
							<?php
						}
						?>
						<tr>
							<td class="data_td" width="80%" colspan="1" style="text-align:right;font-weight:bold;background-color:#efefef;">
								<?php echo JText::_('OS_TOTAL')?>
							</td>
							<td class="data_td" style="text-align:right;background-color:#efefef;">
								<?php echo HelperOspropertyCommon::loadDefaultCurrency(1)?>&nbsp;
								<?php echo HelperOspropertyCommon::showPrice($total);?> 
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<BR />
		<table  width="100%" style="border-collapse:separate;border:0px !important;">
			<tr>
				<td width="100%" style="text-align:right;border:0px;">
					<input type="button" value="<?php echo JText::_('OS_BACK')?>" class="btn btn-warning" onclick="javascript:history.go(-1);" />
					<input type="button" value="<?php echo JText::_('OS_PAY')?>" class="btn btn-info" onclick="javascript:document.ftForm.submit();" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="property_paymentprocess" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
		<input type="hidden" name="live_site" value="<?php echo JURI::root()?>" />
		</form>
		<?php
	}
	
	static function favorites($option,$countFav){
		global $mainframe;
		$needs = array();
		$needs[] = "property_favorites";
		$itemid = OSPRoute::getItemid($needs);
		$itemid = OSPRoute::confirmItemid($itemid,'property_favorites');
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_favorites&Itemid='.$itemid);?>" name="ftForm" id="ftForm">
		<div class="componentheading">
			<?php //echo JText::_('OS_MY_FAVORITES')?>
			<?php 
			OSPHelper::generateHeading(2,JText::_('OS_MY_FAVORITES'));
			?>
		</div>
		<?php
		if($countFav > 0){
			$filterParams = array();
			//show cat
			$filterParams[0] = 1;
			//agent
			$filterParams[1] = 1;
			//keyword
			$filterParams[2] = 1;
			//bed
			$filterParams[3] = 0;
			//bath
			$filterParams[4] = 0;
			//rooms
			$filterParams[5] = 0;
			//price
			$filterParams[6] = 0;
			OspropertyListing::listProperties($option,'',JRequest::getVar('catIds',null,array()),JRequest::getInt('agent_id',0),JRequest::getInt('property_type',0),JRequest::getVar('keyword'),'','','',0,'',JRequest::getVar('orderby','a.created'),JRequest::getVar('ordertype','desc'),JRequest::getInt('limitstart',0),JRequest::getInt('limit',20),1,'',$filterParams,0,0,0,0,1);
		}else{
			?>
			<div style="width:100%;padding:10px;text-align:center;">
				<h1 style="border:0px;">
					<?php echo JText::_('OS_NO_ITEMS_IN_FAV_LIST');?>
				</h1>
				<?php
				printf(JText::_('CLICK_HERE_TO_GO_BACK'),"<a href='javascript:history.go(-1)'>","</a>");
				?>
			</div>
			<?php
		}
		?>
		
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="property_favorites" />
		<input type="hidden" name="Itemid" value="<?php echo $itemid;?>" />
		</form>
		<?php
	}
	
	
	/**
	 * Property details
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 * @param unknown_type $amenities
	 * @param unknown_type $amenitylists
	 * @param unknown_type $groups
	 */
	function printPropertyEmail($option,$row,$lists,$amenities,$amenitylists,$groups,$configs){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$document =& JFactory::getDocument();
		//JHTML::_('behavior.tooltip');
		
		//require(JPATH_COMPONENT."/helpers/layouts/propertyprint.php");
		
	}
	
	/**
	 * Generic the pdf layout page
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 * @param unknown_type $amenities
	 * @param unknown_type $amenitylists
	 * @param unknown_type $groups
	 * @param unknown_type $configs
	 */
	static function printPropertyPdf($option,$row,$lists,$amenities,$amenitylists,$groups,$configs){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$document =& JFactory::getDocument();
		//$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
		jimport('joomla.filesystem.file');
		if(JFile::exists(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/propertypdf.php')){
			$tpl = new OspropertyTemplate(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/');
		}else{
			$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
		}
		$tpl->set('document',$document);
		$tpl->set('row',$row);
		$tpl->set('lists',$lists);
		$tpl->set('amenities',$amenities);	
		$tpl->set('amenitylists',$amenitylists);
		$tpl->set('groups',$groups);
		$tpl->set('configs',$configs);
		$tpl->set('configClass',$configClass);
		$body = $tpl->fetch("propertypdf.php");
		echo $body;
	}
	
	/**
	 * Thank you page
	 *
	 * @param unknown_type $option
	 * @param unknown_type $configs
	 * @param unknown_type $property
	 * @param unknown_type $expired
	 */
	static function thankyouPage($option,$configs,$property,$expired,$photos,$amenities,$groups){
		global $mainframe,$configClass;
		echo JHTML::_('behavior.modal','a.osmodal');
		$pane =& JPane::getInstance('tabs');
		$db = JFactory::getDBO();
		$document =& JFactory::getDocument();
		jimport('joomla.filesystem.file');
		if(JFile::exists(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/propertyinformation.php')){
			$tpl = new OspropertyTemplate(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/');
		}else{
			$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
		}
		$tpl->set('pane',$pane);
		$tpl->set('document',$document);
		$tpl->set('property',$property);
		$tpl->set('lists',$lists);
		$tpl->set('amenities',$amenities);	
		$tpl->set('expired',$expired);
		$tpl->set('groups',$groups);
		$tpl->set('configs',$configs);
		$tpl->set('configClass',$configClass);
		$tpl->set('photos',$photos);
		$body = $tpl->fetch("propertyinformation.php");
		echo $body;
	}
	
	/**
	 * Approval details
	 *
	 * @param unknown_type $option
	 * @param unknown_type $property
	 * @param unknown_type $expired
	 */
	function approvalDetails($option,$property,$expired){
		global $mainframe;
		?>
		<table  width="100%" style="font-size:12px;">
			<tr>
				<td width="100%" valign="top" style="padding:10px;">
					<font style="font-size:18px;font-weight:bold;">
						<?php echo JText::_('OS_APPROVAL_INFO');
							  echo ' ['.$property->pro_name.']';?>
					</font>
					<BR />
					<BR />
					<?php
					$current_time = time();
					$expired_time = strtotime($expired->expired_time);
					echo "<strong>".JText::_('Status').": ";
					if($expired_time < $current_time){
						echo "<font color='red'>".JText::_('OS_UNAPPROVED')."</font>";
					}else{
						echo "<font color='green'>".JText::_('OS_APPROVED')."</font>";
					}
					?>
				</td>
			</tr>
		</table>
		<?php
	}
	
	
	/**
	 * Confirm approval
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 */
	function confirmApproval($option,$rows){
		global $mainframe;
		$db = JFactory::getDbo();
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm">
		<table  width="100%" style="border:0px !important;">
			<tr>
				<td width="100%">
					<font style="font-size:18px;font-weight:bold;">
						<?php echo JText::_('OS_CONFIRM_REQUEST_APPROVAL_PROPERTIES');?>
					</font>
					<BR />
					<BR />
					<?php
					echo JText::_('OS_PLEASE_CHECK_PROPERTY_LISTS_YOU_WANT_TO_SEND_REQUEST_APPROVAL');
					?>
					<BR /><BR />
					<div style="width:400px;border:1px solid #CCC;background-color:#efefef;padding:10px;">
					<?php
					for($i=0;$i<count($rows);$i++){
						$row = $rows[$i];
						?>
						<input type="checkbox" value="<?php echo $row->id?>" name="cid[]" id="id<?php echo $row->id?>" checked /> &nbsp; <?php echo $row->property?>
						<BR />
						<?php
					}
					?>
					</div>
					<BR />
					<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_BACK')?>" onclick="javascript:history.go(-1)" />
					<input type="submit" class="btn btn-info" value="<?php echo JText::_('OS_SUBMIT')?>" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="property_processrequestapproval" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
		</form>
		<?php
	}
	
	/**
	 * Generate photo by manually
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 * @param unknown_type $photoIds
	 * @param unknown_type $save
	 * @param unknown_type $pro_name
	 */
	static function generatePhotoCrop($option,$id,$photoIds,$save,$pro_name){
		global $mainframe,$configClass;
		//JToolBarHelper::title(JText::_('OS_CREATE_PHOTO_BY_MANUAL')." [".$pro_name."]");
		//JToolBarHelper::apply('properties_savephoto');
		//JToolBarHelper::custom('properties_completesaving','forward.png','forward.png',JText::_('OS_SKIP'));		
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_photos where id in ($photoIds)");
		$photos = $db->loadObjectList();
		//jimport('joomla.html.pane');
        
		?>
		<script type="text/javascript">
		//enable passthrough of errors from YUI Event:
		if ((typeof YAHOO !== "undefined") && (YAHOO.util) && (YAHOO.util.Event)) {
			YAHOO.util.Event.throwErrors = true;
		}
		function showDiv(photo_id){
			var div = document.getElementById('div_' + photo_id);
			var href = document.getElementById('link_' + photo_id);
			if(div.style.display == "block"){
				div.style.display = "none";
				href.innerHTML = "[+]";
			}else{
				div.style.display = "block";
				href.innerHTML = "[-]";
			}
		}
		function submitForm(task){
			var form  = document.adminForm;
			form.task.value = task;
			form.submit();
		}
		</script>
		<form method="POST" action="index.php" name="adminForm">
		<table width="100%">
			<tr>
				<td width="50%" align="left">
					<div class="componentheading">
						<?php echo JText::_('OS_CREATE_PHOTO_BY_MANUAL')." [".$pro_name."]" ?>
					</div>
				</td>
				<td width="50%" align="right">
					<div class="btn-toolbar">
						<div class="btn-group pull-right">
							<button type="button" class="btn hasTooltip" title="<?php echo JText::_('OS_SAVE');?>" onclick="javascript:submitForm('property_savephoto');">
			                    <i class="osicon-save"></i> <?php echo JText::_('OS_SAVE');?>
			                </button>
			                <button type="button" class="btn hasTooltip" title="<?php echo JText::_('OS_SKIP');?>" onclick="javascript:submitForm('property_skip');">
			                    <i class="osicon-cancel"></i> <?php echo JText::_('OS_SKIP');?>
			                </button>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<table width="100%" class="admintable">
			<?php
			if($photos > 0){
			
				for($i=0;$i<count($photos);$i++){
					
				$photo = $photos[$i];
				$photo_id = $photo->id;
				
				if($i == 0){
					$display = "block";
				}else{
					$display = "none";
				}
				
				 $medium_width = $configClass['images_large_width'];
				 $medium_height = $configClass['images_large_height'];
				 $original_info = getimagesize(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS.$photo->image);
				
				 $origin_width  = $original_info[0];
				 $origin_height = $original_info[1];
				?>
				<tr>
					<td width="100%">
						<table  width="100%" style="border:1px solid #CCC !important;">
							<tr>
								<td style="padding:3px;background-color:#7A7676;color:white;font-weight:bold;text-align:center;">
									<?php echo JText::_('OS_PHOTO')?> <?php echo $i+1;?> &nbsp;&nbsp;
									<?php
									if($display == "block"){
										?>
										<a href="javascript:showDiv(<?php echo $photo_id?>)" id="link_<?php echo $photo_id?>">[-]</a>
										<?php
									}else{
										?>
										<a href="javascript:showDiv(<?php echo $photo_id?>)" id="link_<?php echo $photo_id?>">[+]</a>
										<?php
									}
									?>
								</td>
							</tr>
							<tr>
								<td width="100%" valign="top">
									<div id="div_<?php echo $photo_id?>" style="display:<?php echo $display?>;">
									<?php
									//$pane =& JPane::getInstance('tabs');
									//ul
									?>
									<table width="100%"> 
										<tr>
											<td width="100%" valign="top">
												<div class="row-fluid">
													<ul class="nav nav-tabs">
														<li class="active"><a href="#tab1<?php echo $i;?>" data-toggle="tab"><?php echo Jtext::_('OS_THUMBNAIL_PHOTO');?></a></li>
														<?php
														if(($medium_height < $origin_height) or ($medium_width < $origin_width)){
														?>
														<li><a href="#tab2<?php echo $i?>" data-toggle="tab"><?php echo Jtext::_('OS_MEDIUM_PHOTO');?></a></li>
														<?php
														}
														?>
													</ul>
												<?php
												//echo $pane->startPane( 'pane' );
												//echo $pane->startPanel( JText::_('OS_THUMBNAIL_PHOTO'), 'tab1' );
												?>
												<div class="tab-content">	
												<div class="tab-pane active" id="tab1<?php echo $i;?>">
												<table width="100%" class="admintable">
													<tr>
														<td class="key" style="font-size:12px;text-align:left;">
															<input type="radio" name="tb_<?php echo $photo_id?>" id="tb_<?php echo $photo_id?>" value="0">
															&nbsp;
															<?php echo JText::_('OS_THUMBNAIL_PHOTO_IS_CREATED_BY_OSPROPERTY')?>
															
														</td>
													</tr>
													<tr>
														<td width="100%" style="text-align:center;">
															<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $id?>/thumb/<?php echo $photo->image?>" width="<?php echo $configClass['images_thumbnail_width']?>" height="<?php echo $configClass['images_thumbnail_height']?>">
														</td>
													</tr>
													<tr>
														<td class="key" style="font-size:12px;text-align:left;">
															<input type="radio" name="tb_<?php echo $photo_id?>" id="tb_<?php echo $photo_id?>" value="1">
															&nbsp;
															<?php echo JText::_('OS_CREATE_THUMBNAIL_PHOTO_MANUALLY')?>
														</td>
													</tr>
													<tr>
														<td width="100%" style="text-align:center;">
															<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $id?>/<?php echo $photo->image?>" id="tb_yui_img_<?php echo $photo_id?>" width="<?php echo $origin_width?>"  height="<?php echo $origin_height?>" />
															<input type="hidden" name="tb_h_<?php echo $photo_id?>" id="tb_h_<?php echo $photo_id?>" value="" />
															<input type="hidden" name="tb_w_<?php echo $photo_id?>" id="tb_w_<?php echo $photo_id?>" value="" />
															<input type="hidden" name="tb_t_<?php echo $photo_id?>" id="tb_t_<?php echo $photo_id?>" value="" />
															<input type="hidden" name="tb_l_<?php echo $photo_id?>" id="tb_l_<?php echo $photo_id?>" value="" />
														</td>
														<td width="30%" valign="top">
														</td>
													</tr>
												</table>
												<script>
												(function() {
											    var Dom = YAHOO.util.Dom,
											        Event = YAHOO.util.Event,
											        results = null;
											    
											   	 	Event.onDOMReady(function() {
											            var crop = new YAHOO.widget.ImageCropper('tb_yui_img_<?php echo $photo_id?>', {
											                initialXY: [20, 20],
											                initHeight:<?php echo $configClass['images_thumbnail_height']?>,
											                initWidth:<?php echo $configClass['images_thumbnail_width']?>,
											                useKeys:false,
											                keyTick: 5,
											                ratio:true,
											                shiftKeyTick: 50
											            });
											            crop.on('moveEvent', function() {
											                var region = crop.getCropCoords();
											                Dom.get('tb_t_<?php echo $photo_id?>').value = region.top;
											                Dom.get('tb_l_<?php echo $photo_id?>').value = region.left;
											                Dom.get('tb_h_<?php echo $photo_id?>').value = region.height;
											                Dom.get('tb_w_<?php echo $photo_id?>').value = region.width;
											            });
											            
											    });
											})();
											</script>
											</div>
											<?php
											 //echo $pane->endPanel();
											 if(($medium_height < $origin_height) or ($medium_width < $origin_width)){
											 
											 	if(($medium_height > $origin_height) and ($medium_width < $origin_width)){
											 		$height = $origin_height;
											 		$width = round($medium_width*$height/$medium_height);
											 	}elseif(($medium_height < $origin_height) and ($medium_width > $origin_width)){
											 		$width = $origin_width;
											 		$height = round($medium_height*$width/$medium_width);
											 	}else{
											 		$width = $medium_width;
											 		$height = $medium_height;
											 	}
											 	
	        								    //echo $pane->startPanel( JText::_('OS_MEDIUM_PHOTO'), 'tab2' );
												?>
												
												<div class="tab-pane" id="tab2<?php echo $i;?>">
												<table width="100%" class="admintable">
													<tr>
														<td class="key" style="font-size:12px;text-align:left;">
															<input type="radio" name="me_<?php echo $photo_id?>" id="me_<?php echo $photo_id?>" value="0">
															&nbsp;
															<?php echo JText::_('OS_MEDIUM_PHOTO_IS_CREATED_BY_OSPROPERTY')?>
															
														</td>
													</tr>
													<tr>
														<td width="100%" style="text-align:center;">
															<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $id?>/medium/<?php echo $photo->image?>" width="<?php echo $configClass['images_large_width']?>" height="<?php echo $configClass['images_large_height']?>">
														</td>
													</tr>
													<tr>
														<td class="key" style="font-size:12px;text-align:left;">
															<input type="radio" name="me_<?php echo $photo_id?>" id="me_<?php echo $photo_id?>" value="1">
															&nbsp;
															<?php echo JText::_('OS_CREATE_MEDIUM_PHOTO_MANUALLY')?>
														</td>
													</tr>
													<tr>
														<td width="100%" style="text-align:center;">
															<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $id?>/<?php echo $photo->image?>" id="me_yui_img_<?php echo $photo_id?>" width="<?php echo $origin_width?>"  height="<?php echo $origin_height?>" />
															<input type="hidden" name="me_h_<?php echo $photo_id?>" id="me_h_<?php echo $photo_id?>" value="" />
															<input type="hidden" name="me_w_<?php echo $photo_id?>" id="me_w_<?php echo $photo_id?>" value="" />
															<input type="hidden" name="me_t_<?php echo $photo_id?>" id="me_t_<?php echo $photo_id?>" value="" />
															<input type="hidden" name="me_l_<?php echo $photo_id?>" id="me_l_<?php echo $photo_id?>" value="" />
														</td>
														<td width="30%" valign="top">
														</td>
													</tr>
												</table>
												<script>
												(function() {
											    var Dom = YAHOO.util.Dom,
											        Event = YAHOO.util.Event,
											        results = null;
											    
											   	 	Event.onDOMReady(function() {
											            var crop = new YAHOO.widget.ImageCropper('me_yui_img_<?php echo $photo_id?>', {
											                initialXY: [0, 0],
											                initHeight:<?php echo $height?>,
											                initWidth:<?php echo $width?>,
											                useKeys:false,
											                keyTick: 5,
											                ratio:true,
											                shiftKeyTick: 50
											            });
											            crop.on('moveEvent', function() {
											                var region = crop.getCropCoords();
											                Dom.get('me_t_<?php echo $photo_id?>').value = region.top;
											                Dom.get('me_l_<?php echo $photo_id?>').value = region.left;
											                Dom.get('me_h_<?php echo $photo_id?>').value = region.height;
											                Dom.get('me_w_<?php echo $photo_id?>').value = region.width;
											            });
											            
											    });
												})();
												</script>
												<?php
												 //echo $pane->endPanel();
												 echo "</div>";
											 }
        									 //echo $pane->endPane();
        									 echo "</div>";
											?>
											</td>
										</tr>
									</table>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
				}
			}
			?>
		</table>
		<input type="hidden" name="isNew" id="isNew" value="<?php echo JRequest::getInt('isNew',0)?>" />
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="pid" id="pid" value="<?php echo $id?>" />
		<input type="hidden" name="photoIds" id="photoIds" value="<?php echo $photoIds?>" />
		<input type="hidden" name="save" id="save" value="<?php echo $save?>" />
		</form>
		<?php
	}
	
	/**
	 * Show Report form
	 *
	 * @param unknown_type $lists
	 * @param unknown_type $id
	 */
	static function reportForm($lists,$id,$item_type){
		global $mainframe;
		$user = JFactory::getUser();
		?>
		<form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_doreport&type=property&id=<?php echo $id?>&tmpl=component" name="reportForm" id="reportForm" class="form-horizontal">
			<H2>
				<?php echo JText::_('OS_REPORT_A_PROBLEM')?>
			</H2>
			<div class="clearfix"></div>
			<div class="control-group" style="margin-left:0px;">
				<label class="control-label" ><?php echo JText::_('OS_PROBLEM')?></label>
				<div class="controls">
					<?php echo $lists['report_reason']; ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="control-group" style="margin-left:0px;">
				<label class="control-label" ><?php echo JText::_('OS_DETAILS')?></label>
				<div class="controls">
					<textarea class="input-large" cols="50" rows="4" name="report_details"></textarea>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="control-group" style="margin-left:0px;">
				<label class="control-label" ><?php echo JText::_('OS_YOUR_EMAIL')?></label>
				<div class="controls">
					<input type="text" class="input-large" name="your_email" id="your_email" value="<?php echo $user->email;?>" placeholder = '<?php echo JText::_('OS_EMAIL');?>' />
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="control-group" style="margin-left:0px;">
				<input type="submit" class="btn btn-info" value="<?php echo JText::_('OS_SUBMIT')?>" />
				<input type="reset" class="btn btn-warning" value="<?php echo JText::_('OS_RESET')?>" />
			</div>
		<?php
		?>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="property_doreportproperty" />
		<input type="hidden" name="id" value="<?php echo $id?>" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
		<input type="hidden" name="item_type" id="item_type" value="<?php echo $item_type?>" />
		</form>
		<?php
	}
}
?>