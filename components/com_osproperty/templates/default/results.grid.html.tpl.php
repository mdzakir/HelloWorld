<?php
/*------------------------------------------------------------------------
# results.html.tpl.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// No direct access.
defined('_JEXEC') or die;

$show_kml_export = $params->get('show_kml_export',1);
$grid_view_columns = $params->get('grid_view_columns',2);
?>
<div id="listings">
	<?php
	if(count($rows) > 0){
	?>
        <div class="row-fluid">
            <div class="span12">
                <div class="span6 pull-left">
                    <a href="javascript:updateView(3)" title="<?php echo JText::_('OS_CHANGE_TO_GRID_VIEW');?>">
                        <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/gridview.png" style="border:1px solid #CCC !important;padding:1px;" />
                    </a>
                    <a href="javascript:updateView(2)" title="<?php echo JText::_('OS_CHANGE_TO_MAP_VIEW');?>">
                        <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/mapview.png" style="border:1px solid #CCC !important;padding:1px;" />
                    </a>
                    <?php
                    if($show_kml_export == 1){
                        ?>
                        <a href="javascript:updateView(4)" title="<?php echo JText::_('OS_CHANGE_TO_GOOGLE_EARTH_KML');?>">
                            <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/kml.png" style="border:1px solid #CCC !important;padding:1px;" />
                        </a>
                    <?php
                    }
                    ?>
                    <input type="hidden" name="listviewtype" id="listviewtype" value="<?php echo JRequest::getVar('listviewtype',$_COOKIE['viewtypecookie']); ?>"/>
                    <script language="javascript">
                        function updateView(view){
                            var listviewtype = document.getElementById('listviewtype');
                            listviewtype.value = view;
                            document.ftForm.submit();
                        }
                    </script>
                </div>
                <div class="span6 pull-right" style="text-align:right;">
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
            </div>
        </div>
		<?php
		$db = JFactory::getDbo();
		$db->setQuery("Select id as value, currency_code as text from #__osrs_currencies where id <> '$row->curr' order by currency_code");
		$currencies   = $db->loadObjectList();
		$currenyArr[] = JHTML::_('select.option','',JText::_('OS_SELECT'));
		$currenyArr   = array_merge($currenyArr,$currencies);
		?>
		<style>
		.photos_count{
			width:120px !important;
		}
		</style>
		<input type="hidden" name="currency_item" id="currency_item" value="" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
		<div class="clearfix"></div>
		<div class="latestproperties latestproperties_right row-fluid">
			<?php
			$ncolumns = $grid_view_columns;
			$col_width = round(12/$ncolumns);
			$j = 0;
			for($i=0;$i<count($rows);$i++){
				$j++;
				$row = $rows[$i];
				
				$needs = array();
				$needs[] = "property_details";
				$needs[] = $row->id;
				$itemid = OSPRoute::getItemid($needs);
				
				$lists['curr'] = JHTML::_('select.genericlist',$currenyArr,'curr'.$i,'onChange="javascript:updateCurrency('.$i.','.$row->id.',this.value)" class="input-mini"','value','text');
				
				if($j == 1){
					$extraCss = "margin-left:0px;";
				}else{
					$extraCss = "";
				}
				?>
				<div class="span<?php echo $col_width?> img-rounded gridelement" style="<?php echo $extraCss;?>">
					<div class="griditem<?php echo ($row->isFeatured == 1) ? " featured": "";?>">
						<div class="span12">
							<div class="span5">
								<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>">
									<img alt="<?php echo $row->pro_name?>" title="<?php echo $row->pro_name?>" src="<?php echo $row->photo?>"/>
								    <div class="photos_count"><strong><?php echo $row->count_photo?></strong> <?php echo JText::_('OS_PHOTOS')?></div>
								</a>
							</div>
							<div class="span7">
									<p class="gridpropertyaddress">
										<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>" class="grid_property_title">
											<strong>
												<?php
												if($row->ref!=""){
													?>
													<?php echo $row->ref?>,
													<?php
												}
												?>
										       <?php echo $row->pro_name?>
										    </strong>
									        <?php if($row->isFeatured == 1){?>
									       		<span class="badge badge-important"><strong><?php echo JText::_('OS_FEATURED')?></strong></span>
									        <?php }?>
									        <?php
				 						    $created_on = $row->created;
				 						    $modified_on = $row->modified;
				 						    $created_on = strtotime($created_on);
				 						    $modified_on = strtotime($modified_on);
				 						    if($created_on > time() - 3*24*3600){ //new
				 						    	if($configClass['show_just_add_icon'] == 1){
					 						    	?>
					 						    	<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/justadd.png" />
					 						    	<?php
				 						    	}
				 						    }elseif($modified_on > time() - 2*24*3600){
				 						    	if($configClass['show_just_update_icon'] == 1){
					 						    	?>
					 						    	<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/justupdate.png" />
					 						    	<?php
				 						    	}
				 						    }
				 						    ?>
			 						   		</a>
			 							</p>
										<?php 
										$sold_property_types = $configClass['sold_property_types'];
										$show_sold = 0;
										if($sold_property_types != ""){
											$sold_property_typesArr = explode("|",$sold_property_types);
											if(in_array($row->pro_type, $sold_property_typesArr)){
												$show_sold = 1;
											}
										}
										?>
										<?php if(($configClass['use_sold'] == 1) and ($row->isSold == 1) and ($show_sold == 1)){
											?>
											<span class="badge badge-warning"><strong><?php echo JText::_('OS_SOLD')?></strong></span> <?php echo JText::_('OS_ON');?>: <?php echo $row->soldOn;?>
											<?php
										}
										?> 
										<p class="gridprice"><strong class="sale"> <?php echo $row->type_name?>  </strong>
									
										<?php
										if($configClass['listing_show_price'] == 1){
										?><?php 
											if($row->price_call == 0){
												if($row->price > 0){
													?>
													<span id="currency_div<?php echo $i?>">
														<?php
														//echo JText::_('OS_PRICE');
														//echo ": ";
														echo OSPHelper::generatePrice($row->curr,$row->price);
														if($row->rent_time != ""){
															echo " /".JText::_($row->rent_time);
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
													</span>
													<?php
												}
											}else{
												echo JText::_('OS_CALL_FOR_PRICE');
											}
										}
										?>
									</p>
								</div>
								<div class="clearfix"></div>
								<div class="span12" style="margin-left:0px;">
									<?php
									if($configClass['listing_show_address'] == 1){
										if($row->show_address == 1){
											?>
											<p class="gridaddress">
											<?php
											echo OSPHelper::generateAddress($row);
											?>
											</p> 
										<?php
										}
									}
									?>
									<span class="field"> <?php echo JText::_('OS_CATEGORY')?> </span> <span>:   
									<?php echo $row->category_name_short;?>
									
									&nbsp;&nbsp;-&nbsp;&nbsp;
									<?php echo JText::_('OS_AGENT')?>:
									<a title="<?php echo $row->agent_name?>" href="index.php?option=com_osproperty&task=agent_info&id=<?php echo $row->agent_id?>&Itemid=<?php echo $itemid;?>">
										<?php echo $row->agent_name?>
									</a>
									<?php
									$fieldarr = $row->fieldarr;
									if(count($fieldarr) > 0){
										for($f=0;$f<count($fieldarr);$f++){
											$field = $fieldarr[$f];
											?>
											&nbsp;&nbsp;-&nbsp;&nbsp;
											<?php
											if($field->label != ""){
												?>
												<?php
												echo $field->label;
												?>
												:
												<?php
											}
											?>
											<?php echo $field->fieldvalue;?>
											<?php
										}
									}
									?>
									</span>
									<div class="clearfix"></div>
									<span class="propertylistinglinks">
				                    	<?php
										echo  $row->other_information;
										?>
				                    </span> 
									</div>
									<div class="clearfix"></div>
									<span style="width:100%;text-align:center;">
										<span id="compare_1">
											<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>" class="btn btn-small">
												<i class="osicon-search"></i>
											</a>
										</span>
										<?php
										if(($configClass['show_getdirection'] == 1) and ($row->show_address== 1)){
										?>
										<span id="compare_1">
											<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=direction_map&id=".$row->id)?>" class="btn btn-small" title="<?php echo JText::_('OS_GET_DIRECTIONS')?>">
												<i class="osicon-arrow-right"></i>
											</a>
										</span>
										<?php
										}
										$user = JFactory::getUser();
										$db   = JFactory::getDBO();
										//print_r($configClass);
										if(intval($user->id) > 0){
											
											if($configClass['show_compare_task'] == 1){
											?>
												<span id="compare_1">
													<?php
													$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_COMPARE_LIST');
													$msg = str_replace("'","\'",$msg);
													?>
													<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="btn btn-small" title="<?php echo JText::_('OS_COMPARE_LISTINGS');?>">
														<i class="osicon-bookmark"></i>
													</a>
												</span>
											<?php
											}
											if($configClass['property_save_to_favories'] == 1){
												if($task != "property_favorites"){
												$db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
												$count = $db->loadResult();
												if($count == 0){
													?>
													<span id="favorite_1">
														<?php
														$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS');	
														$msg = str_replace("'","\'",$msg);
														?>
														<a onclick="javascript:osConfirm('<?php echo $msg; ?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="btn btn-small" title="<?php echo JText::_('OS_ADD_TO_FAVORITES');?>">
															<img title="<?php echo JText::_('OS_ADD_TO_FAVORITES')?>" alt="<?php echo JText::_('OS_ADD_TO_FAVORITES')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/save24.png" width="16"/>
														</a>
													</span>
													<?php
													}
												}
												if($count > 0){
													?>
													<span id="favorite_1">
														<?php
														$msg = JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS');	
														$msg = str_replace("'","\'",$msg);
														?>
														<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="btn btn-small" title="<?php echo JText::_('OS_REMOVE_PROPERTY_OUT_OF_FAVORITES_LIST');?>">
															<i class="osicon-remove"></i></a>
													</span>
													<?php
												}
											}
											if(HelperOspropertyCommon::isAgent()){
												$my_agent_id = HelperOspropertyCommon::getAgentID();
												
												if($my_agent_id == $row->agent_id){
													$link = JURI::root()."index.php?option=com_osproperty&task=property_edit&id=".$row->id;
													?>
													<span id="favorite_1">
														<a href="<?php echo $link?>" title="<?php echo JText::_('OS_EDIT_PROPERTY')?>" class="btn btn-small">
															<i class="osicon-pencil"></i>
														</a>
													</span>
													<?php
												}
											}
										}
										?>
									</span>
								</div>
					</div>
				</span>
				</div>
				<?php
				if($j == $ncolumns){
					$j = 0;
					?>
					<div class="clearfix"></div>
					<?php 
				}
			}
			?>
		</div>
		<?php
	}
	?>
</div>
<div>
    <?php
    if((count($rows) > 0) and ($pageNav->total > $pageNav->limit)){
        ?>
        <div class="pageNavdiv">
			<?php
				echo $pageNav->getListFooter();
			?>
		</div>
		<?php
	}
	?>
</div>