<?php
/*------------------------------------------------------------------------
# listing.html.tpl.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// No direct access.
defined('_JEXEC') or die;
$document = JFactory::getDocument();
echo JHTML::_('behavior.tooltip');
$ncolumns = $params->get('ncolumns',1);
$color    = $params->get('themeBackgroundColor','#88C354');
$user = JFactory::getUser();
?>
<link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename;?>/font/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename;?>/style/layout<?php echo $ncolumns;?>.css">
<script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename;?>/js/modernizr.custom.js"></script>
<style>
.cat-price {
	background:<?php echo $color?>;
}
.grid li:hover .property-info { background:<?php echo $color?>;} 
.pimage figcaption i, .feat-thumb figcaption i, .feat-medium figcaption i{
	background:<?php echo $color?>;
}
.agent-info label {
    color: <?php echo $color?>;
}
.property-mask h4.os-featured , .feat-thumb h4.os-featured {
	background:<?php echo $color?>; 
}
</style>
<script language="javascript">
function loadStateInListPage(){
	var country_id = document.getElementById('country_id');
	loadStateInListPageAjax(country_id.value,"<?php echo JURI::root()?>");
}
function changeCity(state_id,city_id){
	var live_site = '<?php echo JURI::root()?>';
	loadLocationInfoCity(state_id,city_id,'state_id',live_site);
}
</script>
<div id="notice" style="display:none;">
	
</div>
<?php
HelperOspropertyCommon::filterForm($lists);
?>
<div id="listings">
	<?php
	if(count($rows) > 0){
	
		$db = JFactory::getDbo();
		$db->setQuery("Select id as value, currency_code as text from #__osrs_currencies where id <> '$row->curr' order by currency_code");
		$currencies   = $db->loadObjectList();
		$currenyArr[] = JHTML::_('select.option','',JText::_('OS_SELECT'));
		$currenyArr   = array_merge($currenyArr,$currencies);
		?>
		<input type="hidden" name="currency_item" id="currency_item" value="" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
		<div class="clearfix"></div>
		
		<div class="agent-properties property-list row-fluid">
			<div class="grid cs-style-3">
				<ul style="margin:0px;">
					<?php
					if($ncolumns == 1){
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							$needs = array();
							$needs[] = "property_details";
							$needs[] = $row->id;
							$itemid = OSPRoute::getItemid($needs);
							
							
							?>
							<li class="span12" style="margin:0px;margin-bottom:50px;">
								<div class="property-mask property-image span5">
									<figure class="pimage">
										<a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>" class="property_mark_a" id="property_mark_a">
				                        	<img alt="<?php echo $row->pro_name?>" title="<?php echo $row->pro_name?>" src="<?php echo $row->photo?>" class="ospitem-imgborder" id="picture_<?php echo $i?>" />
				                        </a>
										<figcaption><a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>"><i class="fa fa-link fa-lg"></i></a></figcaption>
											<?php
											if(OSPHelper::isSoldProperty($row,$configClass)){
											?>
												<h4 class="os-sold"><a rel="tag" href="#"><?php echo JText::_('OS_SOLD')?></a></h4>
											<?php
											}
											?>
                                            <?php
											if($row->isFeatured == 1){
											?>
												<h4 class="os-featured"><a rel="tag" href="#"><?php echo JText::_('OS_FEATURED')?></a></h4>
											<?php 
											}
											?>	
										<h4> <a rel="tag" href="#"><?php echo $row->type_name;?></a></h4>
										<div class="property-price clear">
											<div class="cat-price">
												<span class="pcategory">
													
													<a rel="tag" href="<?php echo JRoute::_('index.php?option=com_osproperty&task=category_details&id='.$row->category_id);?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS');?>">
														<?php echo $row->category_name_short;?>
													</a>
													
													
												</span>
												<span class="price">
												<?php 
												if($row->price_call == 0){
													echo OSPHelper::generatePrice($row->curr,$row->price);
													if($row->rent_time != ""){
														echo " /".JText::_($row->rent_time);
													}
												}else{
													echo JText::_('OS_CALL_FOR_PRICE');
												}
												?>
												</span>
											</div>
											<span class="picon"><i class="fa fa-tag"></i></span>
										</div>
									</figure>
								</div>
				                <div class="agent-property-desc span7">
				                    <div class="property-desc">
				                        <h4><a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>" title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>"><?php echo $row->pro_name?></a>
				                        <?php
										if($configClass['show_compare_task'] == 1){
											$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_COMPARE_LIST');
											$msg = str_replace("'","\'",$msg);
											?>
												
											<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST');?>" style="font-size:14px;padding-left:2px;padding-right:2px;">
												<i class="osicon-bookmark"></i> 
											</a>
												
											<?php
										}
										if(intval($user->id) > 0){
											if($configClass['property_save_to_favories'] == 1){
												//if($task != "property_favorites"){
												$db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
												$count = $db->loadResult();
												if($count == 0){
													$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS');	
													$msg = str_replace("'","\'",$msg);
													?>
													<a title ="<?php echo JText::_('OS_ADD_TO_FAVORITES');?>" onclick="javascript:osConfirm('<?php echo $msg; ?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" style="font-size:14px;padding-left:2px;padding-right:2px;">
														<i class="osicon-ok"></i> 
													</a>
													<?php
												}
												if($count > 0){
													$msg = JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS');	
													$msg = str_replace("'","\'",$msg);
													?>
													<a title="<?php echo JText::_('OS_REMOVE_FAVORITES');?>" onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" style="font-size:14px;padding-left:2px;padding-right:2px;">
														<i class="osicon-remove"></i> 
													</a>
													<?php
												}
											}
										}
										if(HelperOspropertyCommon::isAgent()){
											$my_agent_id = HelperOspropertyCommon::getAgentID();
											if($my_agent_id == $row->agent_id){
												$link = JURI::root()."index.php?option=com_osproperty&task=property_edit&id=".$row->id;
												?>
													<a href="<?php echo $link?>" title="<?php echo JText::_('OS_EDIT_PROPERTY')?>" style="font-size:14px;padding-left:2px;padding-right:2px;">
													<i class="osicon-edit"></i> 
													</a>
												<?php
											}
										}
				                    	?>
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
											<span class="badge badge-warning"><strong><?php echo JText::_('OS_SOLD')?></strong></span> <span style="font-family:x-small;font-size:small;"><?php echo JText::_('OS_ON');?>: <?php echo $row->soldOn;?></span>
											<?php
										}
										?>
				                        </h4><label></label>
				                        <div class="clearfix"></div>
				                        <?php 
				                        if($row->show_address == 1){
				                        	?>
				                        	<span style="font-size:small;font-style:italic;"><?php echo OSPHelper::generateAddress($row);?></span>
				                        	<div class="clearfix"></div>
				                        	<?php 
				                        }
				                        
			                        	$small_desc = $row->pro_small_desc;
			                        	$small_desc_arr = explode(" ",$small_desc);
			                        	if(count($small_desc_arr) > 30){
			                        		for($k=0;$k<30;$k++){
			                        			echo $small_desc_arr[$k]." ";
			                        		}
			                        		echo "..";
			                        	}else{
			                        		echo $small_desc;
			                        	}
			                        	?>
			                        	<?php if($configClass['use_open_house'] == 1){?>
			                        	<div class="span12" style="margin:0px;">
			                        		<div class="clearfix"></div>
			                        		<div class="row-fluid img-polaroid inspectiontimes img-rounded">
			                        		<strong><?php echo Jtext::_('OS_OPEN_FOR_INSPECTION_TIMES')?></strong>
			                        		<div class="clearfix"></div>
			                        		<div class="span12" style="margin-left:0px;font-size:x-small;">
				                        		<?php 
				                        		if(count($row->openInformation) > 0){
				                        			foreach ($row->openInformation as $info){
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
			                        	</div>
			                        	<?php }?>
				                    </div>
				                    <div class="property-info-agent noleftmargin">
				                        <ul>
                                            
                                            <?php
                                            if($configClass['use_squarefeet'] == 1){
                                            ?><li class="property-icon-square meta-block">
                                            <i class="ospico-square"></i>
                                            <span>
                                                <?php
                                                echo OSPHelper::showSquare($row->square_feet);
                                                echo "&nbsp;";
												echo OSPHelper::showSquareSymbol();
                                                ?>
                                            </span></li>
                                            <?php
                                            }
                                            ?>
                                            
                                            
                                            <?php
                                            if(($configClass['listing_show_nbedrooms'] == 1) and ($row->bed_room > 0)){
                                            ?>
                                            <li class="property-icon-bed meta-block">
                                            <i class="ospico-bed"></i>
                                                <span><?php echo $row->bed_room;?></span></li>
                                            <?php 
                                            }
                                            ?>
                                            
                                            
                                            <?php
                                            if(($configClass['listing_show_nbathrooms'] == 1) and ($row->bath_room > 0)){
                                            ?>
                                            <li class="property-icon-bath meta-block">
                                            <i class="ospico-bath"></i>
                                                <span> <?php echo OSPHelper::showBath($row->bath_room);?></span>
                                            </li>
                                            <?php 
                                            }
                                            ?>
                                            
                                           
                                            <?php
                                            if($row->parking != ""){
                                            ?>
                                             <li class="property-icon-parking meta-block">
                                            <i class="ospico-parking"></i>
                                                <span><?php echo $row->parking;?></span>
                                            </li>
                                            <?php 
                                            }
                                            ?>
                                            
										</ul>
				                    </div>
				                    <?php
				                    if($configClass['listing_show_agent'] == 1){
				                    ?>
				                    <div class="agent-info">
				                    	<?php
				                    	if($configClass['show_agent_image'] == 1){
				                    	?>
				                        <?php
			                        	if($row->agent_photo != ""){
			                        		if(file_exists(JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."thumbnail".DS.$row->agent_photo)){
				                        		?>
				                        		<img src="<?php echo JURI::root()?>images/osproperty/agent/thumbnail/<?php echo $row->agent_photo?>" width="24"  class="" />
				                        		<?php
											}else{
												?>
												<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png" height="70" width="24"  class="" />
												<?php
											}
			                        	}else{
			                        		?>
			                        		<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png" height="70" width="24"  class="" />
			                        		<?php
			                        	}
			                        	?>
				                        <?php
				                    	}
				                        ?>
				                        <span><?php echo JText::_('OS_AGENT')?>:</span>
				                        <a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=agent_info&id='.$row->agent_id);?>" title="<?php echo JText::_('OS_AGENT_DETAILS');?>">
				                        	<?php echo $row->agent_name;?>
				                        </a>
				                    </div>
				                    <?php
				                    }
				                    ?>
				                    
				                    <a class="view-profile" href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>" title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>"><?php echo JText::_('OS_VIEW_MORE_DETAILS');?></a>
				                </div>
				                
							</li>
						<?php
						}
					}elseif($ncolumns == 2){
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							$needs = array();
							$needs[] = "property_details";
							$needs[] = $row->id;
							$itemid = OSPRoute::getItemid($needs);
							?>
							<li class="span6">
								<div class="property-mask property-image">
									<figure class="pimage">
										<a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>" class="property_mark_a" id="property_mark_a">
				                        	<img alt="<?php echo $row->pro_name?>" title="<?php echo $row->pro_name?>" src="<?php echo $row->photo?>" class="ospitem-imgborder" id="picture_<?php echo $i?>" />
				                        </a>
										<figcaption><a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>"><i class="fa fa-link fa-lg"></i></a></figcaption>
										<?php
                                            if(OSPHelper::isSoldProperty($row,$configClass)){
											?>
												<h4 class="os-sold"><a rel="tag" href="#"><?php echo JText::_('OS_SOLD')?></a></h4>
											<?php 
											}
											?>	
                                        <?php
											if($row->isFeatured == 1){
											?>
												<h4 class="os-featured"><a rel="tag" href="#"><?php echo JText::_('OS_FEATURED')?></a></h4>
											<?php 
											}
											?>
										<h4 > <a rel="tag" href="#"><?php echo $row->type_name;?></a></h4>
										<div class="property-price clear hidden-phone">
											<div class="cat-price">
												<span class="pcategory"> 
													<a rel="tag" href="<?php echo JRoute::_('index.php?option=com_osproperty&task=category_details&id='.$row->category_id);?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS');?>"><?php echo $row->category_name_short;?>
													</a>
												</span>
												<span class="price">
												<?php 
												if($row->price_call == 0){
													echo OSPHelper::generatePrice($row->curr,$row->price);
													if($row->rent_time != ""){
														echo " /".JText::_($row->rent_time);
													}
												}else{
													echo JText::_('OS_CALL_FOR_PRICE');
												}
												?>
												</span>
											</div>
											<span class="picon"><i class="fa fa-tag"></i></span>
										</div>
									</figure>
								</div>
				                
			                     <div class="property-info noleftmargin">
			                        <ul>
										
										<?php
										if($configClass['use_squarefeet'] == 1){
										?><li class="property-icon-square meta-block">
                                        <i class="ospico-square"></i>
										<span>
											<?php
											echo OSPHelper::showSquare($row->square_feet);
											echo "&nbsp;";
											echo OSPHelper::showSquareSymbol();
											?>
										</span></li>
										<?php
										}
										?>
										
										
										<?php
										if(($configClass['listing_show_nbedrooms'] == 1) and ($row->bed_room > 0)){
										?><li class="property-icon-bed meta-block"><i class="ospico-bed"></i>
											<span><?php echo $row->bed_room;?></span></li>
										<?php 
										}
										?>
										
										
										<?php
										if(($configClass['listing_show_nbathrooms'] == 1) and ($row->bath_room > 0)){
										?><li class="property-icon-bath meta-block"><i class="ospico-bath"></i>
											<span> <?php echo OSPHelper::showBath($row->bath_room);?></span></li>
										<?php 
										}
										?>
										
										
										<?php
										if($row->parking != ""){
										?><li class="property-icon-parking meta-block"><i class="ospico-parking"></i>
											<span><?php echo $row->parking;?></span></li>
										<?php 
										}
										?>
										
									</ul>
			                    </div>
				                    
			                    <div class="property-desc ">
			                    	<h4><a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>" title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>"><?php echo $row->pro_name?></a>
			                    	<?php
									if($configClass['show_compare_task'] == 1){
										$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_COMPARE_LIST');
										$msg = str_replace("'","\'",$msg);
										?>
											
										<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST');?>" style="font-size:14px;padding-left:2px;padding-right:2px;">
											<i class="osicon-bookmark"></i> 
										</a>
											
										<?php
									}
									if(intval($user->id) > 0){
										if($configClass['property_save_to_favories'] == 1){
											//if($task != "property_favorites"){
											$db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
											$count = $db->loadResult();
											if($count == 0){
												$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS');	
												$msg = str_replace("'","\'",$msg);
												?>
												<a title ="<?php echo JText::_('OS_ADD_TO_FAVORITES');?>" onclick="javascript:osConfirm('<?php echo $msg; ?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" style="font-size:14px;padding-left:2px;padding-right:2px;">
													<i class="osicon-ok"></i> 
												</a>
												<?php
											}
											if($count > 0){
												$msg = JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS');	
												$msg = str_replace("'","\'",$msg);
												?>
												<a title="<?php echo JText::_('OS_REMOVE_FAVORITES');?>" onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" style="font-size:14px;padding-left:2px;padding-right:2px;">
													<i class="osicon-remove"></i> 
												</a>
												<?php
											}
										}
									}
									if(HelperOspropertyCommon::isAgent()){
										$my_agent_id = HelperOspropertyCommon::getAgentID();
										if($my_agent_id == $row->agent_id){
											$link = JURI::root()."index.php?option=com_osproperty&task=property_edit&id=".$row->id;
											?>
												<a href="<?php echo $link?>" title="<?php echo JText::_('OS_EDIT_PROPERTY')?>" style="font-size:14px;padding-left:2px;padding-right:2px;">
												<i class="osicon-edit"></i> 
												</a>
											<?php
										}
									}
			                    	?>
			                    	</h4><label></label>
			                    	<p>
			                        	<?php 
			                        	$small_desc = $row->pro_small_desc;
			                        	$small_desc_arr = explode(" ",$small_desc);
			                        	if(count($small_desc_arr) > 30){
			                        		for($k=0;$k<30;$k++){
			                        			echo $small_desc_arr[$k]." ";
			                        		}
			                        		echo "..";
			                        	}else{
			                        		echo $small_desc;
			                        	}
			                        	?>
			                        </p>
				                </div>
							</li>
						<?php
							if($i % 2 == 1){
								echo "</ul><div class='clearfix'></div><ul style='margin:0px;'>";
							}
						}
					}elseif($ncolumns == 3){
						$l = 0;
						for($i=0;$i<count($rows);$i++){
							$l++;
							$row = $rows[$i];
							$needs = array();
							$needs[] = "property_details";
							$needs[] = $row->id;
							$itemid = OSPRoute::getItemid($needs);
							?>
							<li class="span4">
								<div class="property-mask property-image">
									<figure class="pimage">
										<a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>" class="property_mark_a" id="property_mark_a">
				                        	<img alt="<?php echo $row->pro_name?>" title="<?php echo $row->pro_name?>" src="<?php echo $row->photo?>" class="ospitem-imgborder" id="picture_<?php echo $i?>" />
				                        </a>
										<figcaption><a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>"><i class="fa fa-link fa-lg"></i></a></figcaption>
										<?php
                                            if(OSPHelper::isSoldProperty($row,$configClass)){
											?>
												<h4 class="os-sold"><a rel="tag" href="#"><?php echo JText::_('OS_SOLD')?></a></h4>
											<?php 
											}
											?>	
                                        <?php
											if($row->isFeatured == 1){
											?>
												<h4 class="os-featured"><a rel="tag" href="#"><?php echo JText::_('OS_FEATURED')?></a></h4>
											<?php 
											}
											?>
										<h4 > <a rel="tag" href="#"><?php echo $row->type_name;?></a></h4>
										<div class="property-price clear hidden-phone">
											<div class="cat-price">
												<span class="pcategory"> 
													<a rel="tag" href="<?php echo JRoute::_('index.php?option=com_osproperty&task=category_details&id='.$row->category_id);?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS');?>"><?php echo $row->category_name_short;?>
													</a>
												</span>
												<span class="price">
												<?php 
												if($row->price_call == 0){
													echo OSPHelper::generatePrice($row->curr,$row->price);
													if($row->rent_time != ""){
														echo " /".JText::_($row->rent_time);
													}
												}else{
													echo JText::_('OS_CALL_FOR_PRICE');
												}
												?>
												</span>
											</div>
											
										</div>
									</figure>
								</div>
				                
			                     <div class="property-info noleftmargin">
			                        <ul>
										
										<?php
										if($configClass['use_squarefeet'] == 1){
										?><li class="property-icon-square meta-block">
                                        <i class="ospico-square"></i>
										<span>
											<?php
											echo OSPHelper::showSquare($row->square_feet);
											echo "&nbsp;";
											echo OSPHelper::showSquareSymbol();
											?>
										</span></li>
										<?php
										}
										?>
										
										
										<?php
										if(($configClass['listing_show_nbedrooms'] == 1) and ($row->bed_room > 0)){
										?><li class="property-icon-bed meta-block"><i class="ospico-bed"></i>
											<span><?php echo $row->bed_room;?></span></li>
										<?php 
										}
										?>
										
										
										<?php
										if(($configClass['listing_show_nbathrooms'] == 1) and ($row->bath_room > 0)){
										?><li class="property-icon-bath meta-block"><i class="ospico-bath"></i>
											<span> <?php echo OSPHelper::showBath($row->bath_room);?></span></li>
										<?php 
										}
										?>
										
										
										<?php
										if($row->parking != ""){
										?><li class="property-icon-parking meta-block"><i class="ospico-parking"></i>
											<span><?php echo $row->parking;?></span></li>
										<?php 
										}
										?>
										
									</ul>
			                    </div>
				                    
			                    <div class="property-desc ">
			                    	<h4><a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);?>" title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>"><?php echo $row->pro_name?></a>
			                    	<?php
									if($configClass['show_compare_task'] == 1){
										$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_COMPARE_LIST');
										$msg = str_replace("'","\'",$msg);
										?>
											
										<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST');?>" style="font-size:14px;padding-left:2px;padding-right:2px;">
											<i class="osicon-bookmark"></i> 
										</a>
											
										<?php
									}
									if(intval($user->id) > 0){
										if($configClass['property_save_to_favories'] == 1){
											//if($task != "property_favorites"){
											$db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
											$count = $db->loadResult();
											if($count == 0){
												$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS');	
												$msg = str_replace("'","\'",$msg);
												?>
												<a title ="<?php echo JText::_('OS_ADD_TO_FAVORITES');?>" onclick="javascript:osConfirm('<?php echo $msg; ?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" style="font-size:14px;padding-left:2px;padding-right:2px;">
													<i class="osicon-ok"></i> 
												</a>
												<?php
											}
											if($count > 0){
												$msg = JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS');	
												$msg = str_replace("'","\'",$msg);
												?>
												<a title="<?php echo JText::_('OS_REMOVE_FAVORITES');?>" onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" style="font-size:14px;padding-left:2px;padding-right:2px;">
													<i class="osicon-remove"></i> 
												</a>
												<?php
											}
										}
									}
									if(HelperOspropertyCommon::isAgent()){
										$my_agent_id = HelperOspropertyCommon::getAgentID();
										if($my_agent_id == $row->agent_id){
											$link = JURI::root()."index.php?option=com_osproperty&task=property_edit&id=".$row->id;
											?>
												<a href="<?php echo $link?>" title="<?php echo JText::_('OS_EDIT_PROPERTY')?>" style="font-size:14px;padding-left:2px;padding-right:2px;">
												<i class="osicon-edit"></i> 
												</a>
											<?php
										}
									}
			                    	?>
			                    	</h4><label></label>
			                    	
			                    	<p>
			                        	<?php 
			                        	$small_desc = $row->pro_small_desc;
			                        	$small_desc_arr = explode(" ",$small_desc);
			                        	if(count($small_desc_arr) > 30){
			                        		for($k=0;$k<30;$k++){
			                        			echo $small_desc_arr[$k]." ";
			                        		}
			                        		echo "..";
			                        	}else{
			                        		echo $small_desc;
			                        	}
			                        	?>
			                        </p>
				                </div>
							</li>
							<?php
							
							if($l == 3){
								echo "</ul><div class='clearfix'></div><ul style='margin:0px;'>";
								$l = 0;
							}
						}
					}
					?>
				</ul>
			</div>
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
	<?php
	}
	?>
</div>
