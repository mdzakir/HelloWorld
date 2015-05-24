<?php

/*------------------------------------------------------------------------
# details.html.tpl.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// No direct access.
defined('_JEXEC') or die;
$db = JFactory::getDbo();
$document = JFactory::getDocument();
//$document->addStyleSheet(JURI::root()."components/com_osproperty/templates/".$themename."/style/style.css");
JHTML::_('behavior.modal','osmodal');
$extrafieldncolumns = $params->get('extrafieldncolumns',3);
?>
<link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename;?>/font/css/font-awesome.min.css">
<script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename;?>/js/modernizr.custom.js"></script>
<style>
#main ul{
	margin:0px;
}
</style>
<div id="notice" style="display:none;">
</div>
<?php
if(count($topPlugin) > 0){
	for($i=0;$i<count($topPlugin);$i++){
		echo $topPlugin[$i];
	}
}
?>
<div class="row-fluid">
	<div class="span12 toppart1">
		<div class="row-fluid">
			<div class="span6">
				<div class="agent-properties property-list">
					<div class="grid cs-style-3">
            			<div class="property-mask property-image">
                            <figure class="pimage">
							<?php
							if(count($photos) > 0){
								if(($photos[0]->image != "") and (JPATH_ROOT.'/images/osproperty/properties/'.$row->id.'/'.$photos[0]->image)){
									?>
									<script type="text/javascript" src="<?php echo JUri::root()?>components/com_osproperty/js/colorbox/jquery.colorbox.js"></script>
									<link rel="stylesheet" href="<?php echo JUri::root()?>components/com_osproperty/js/colorbox/colorbox.css" type="text/css" media="screen" />
									<script type="text/javascript">
									 jQuery(document).ready(function(){
									     jQuery(".propertyphotogroup").colorbox({rel:'colorbox',maxWidth:'95%', maxHeight:'95%'});
									     jQuery(".propertyphotogroupsecond").colorbox({rel:'colorbox1',maxWidth:'95%', maxHeight:'95%'});
										 jQuery(".propertyphotogroupthird").colorbox({rel:'colorbox2',maxWidth:'95%', maxHeight:'95%'});
									 });
									</script>
									<?php
									$property_photo_link = JURI::root()."images/osproperty/properties/".$row->id."/";
									$title = $photos[0]->image_desc;
									$title = str_replace("\n","",$title);
									$title = str_replace("\r","",$title);
									$title = str_replace("'","\'",$title);
									?>
									<a href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/<?php echo $photos[0]->image?>" class="propertyphotogroupsecond" title="<?php echo $photos[0]->image_desc;?>" >
										<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/<?php echo $photos[0]->image?>" alt="<?php echo $photos[0]->image_desc;?>" title="<?php echo $photos[0]->image_desc;?>"/>
									</a>

									<h4>
									<div class="list-images"><?php echo count($photos)?> <?php echo JText::_('OS_PHOTO');?></div><i class="fa fa-expand"></i></h4>
									<figcaption><a href="<?php echo $property_photo_link?><?php echo $photos[0]->image?>" class="propertyphotogroup" title="<?php echo $title;?>" ><i class="photo"><?php echo count($photos)?> <?php echo JText::_('OS_PHOTO');?></i><i class="fa fa-expand"></i></a>
									<?php
									if(count($photos) > 1){
										for($i=1;$i<count($photos);$i++){
											$photo = $photos[$i];
											$title = $photo->image_desc;
											$title = str_replace("\n","",$title);
											$title = str_replace("\r","",$title);
											$title = str_replace("'","\'",$title);
											if(file_exists(JPATH_ROOT."/images/osproperty/properties/".$row->id."/".$photos[$i]->image)){
											?>
												<div style="display:none;">
													<a href="<?php echo $property_photo_link?><?php echo $photos[$i]->image?>" class="propertyphotogroup" title="<?php echo $title;?>" >
													</a>
													<a href="<?php echo $property_photo_link?><?php echo $photos[$i]->image?>" class="propertyphotogroupsecond" title="<?php echo $title;?>" >
													</a>
												</div>
											<?php
											}
										}
									}
									?>
									</figcaption>
									<?php 
								}else{
									?>
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" alt="" title=""/>
									<?php 
								}
							}else{
								?>
								<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" alt="" title=""/>
								<?php 
							}
							?> 
							</figure>
						</div>
		            </div>
				</div>
			</div>
			<div class="span6 toprightpart2">
				<div>
					<div class="detail-title">
						<h1 class="detail-title-h1">
						<?php
				        if($row->ref != ""){
				        	?>
				        	<font color="orange">
				        		<?php echo $row->ref?>
				        	</font>
				        	-
				        	<?php
				        }
				        ?>
				        <?php echo $row->pro_name?>
				        
				        <?php
				        if($row->isFeatured == 1){
				            ?>
				            <img src="<?php echo $feature_image;?>" />
				            <?php
				        }
				        $created_on = $row->created;
				        $modified_on = $row->modified;
				        $created_on = strtotime($created_on);
				        $modified_on = strtotime($modified_on);
				        if($created_on > time() - 3*24*3600){ //new
				            if($configClass['show_just_add_icon'] == 1){
				                ?>
				                <img src="<?php echo $justadd_image;?>" />
				                <?php
				            }
				        }elseif($modified_on > time() - 2*24*3600){
				            if($configClass['show_just_update_icon'] == 1){
				                ?>
				                <img src="<?php echo $justupdate_image;?>" />
				                <?php
				            }
				        }
				        if($configClass['enable_report'] == 1){
				        ?>
				        <a href="<?php echo JURI::root()?>index.php?option=com_osproperty&tmpl=component&item_type=0&task=property_reportForm&id=<?php echo $row->id?>" class="osmodal" rel="{handler: 'iframe', size: {x: 300, y: 370}}" title="<?php echo JText::_('OS_REPORT_LISTING');?>">
				        	<img src="<?php echo $report_image?>" border="0">
				        </a>
				        <?php } ?>
				        </h1>
					</div>
					<div class="detail-address">
						<div class="detail-address-street">
							<?php echo $row->subtitle;
							if(($configClass['listing_show_view'] == 1) or ($configClass['listing_show_rating'] == 1)){
								?>
								<div class="row-fluid" style='margin-bottom:5px;margin-top:5px;'>
									<div class="span12">
										<?php
										if($configClass['listing_show_view'] == 1){
										?>
											<img src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/img/hit.png">&nbsp;<?php echo $row->hits;?>
										<?php	
										}
										if($configClass['listing_show_rating'] == 1){
											?>
											&nbsp;&nbsp;
											<img src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/img/graph.png">&nbsp;
											<?php
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
										}
										?>
									</div>
								</div>
								<div class="clearfix"></div>
								<?php
							} ?>
						</div>
						<span>
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
								<div class="clearfix"></div>
								<?php
							}
							?>
						</span>
						<div class="detail-address-price">
							<?php echo $row->price1;?>
						</div>
					</div>
					<?php
		        	if($configClass['show_agent_details'] == 1){
		        	?>
					<div class="detail-broker">
						<?php echo $row->agentphoto;?>
						<div class="title">
							<?php echo JText::_('OS_AGENT');?>
						</div>
						<div class="broker-name">
							<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=agent_info&id=".$row->agent_id);?>"><?php echo $row->agent_name;?></a>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="detailsBar clearfix">
		<div class="row-fluid">
			<div class="span12">
				<ul class="listingActions-list">
					<?php
					$user = JFactory::getUser();
					
					if(HelperOspropertyCommon::isAgent()){
						$my_agent_id = HelperOspropertyCommon::getAgentID();
						if($my_agent_id == $row->agent_id){
							$link = JURI::root()."index.php?option=com_osproperty&task=property_edit&id=".$row->id;
							?>
							 <li class="propertyinfoli">
								<a href="<?php echo $link?>" title="<?php echo JText::_('OS_EDIT_PROPERTY')?>" class="edit has icon s_16">
									<?php echo JText::_('OS_EDIT_PROPERTY')?>
								</a>
							</li>
							<?php
						}
					}
					if(($configClass['show_getdirection'] == 1) and ($row->show_address == 1)){
					?>
					<li class="propertyinfoli">
						<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=direction_map&id=".$row->id)?>" title="<?php echo JText::_('OS_GET_DIRECTIONS')?>" class="direction has icon s_16">
						<?php echo JText::_('OS_GET_DIRECTIONS')?>
						</a>
					</li>
					<?php
					}
					if($configClass['show_compare_task'] == 1){
					?>
					<li class="propertyinfoli">
						<?php
						$msg = JText::_(OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_COMPARE_LIST);
						$msg = str_replace("'","\'",$msg);
						?>
						<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="compare has icon s_16">
							<?php echo JText::_('OS_ADD_TO_COMPARE_LIST')?>
						</a>
					</li>
					<?php
					}
					
					if(($configClass['property_save_to_favories'] == 1) and ($user->id > 0)){
					
						if($inFav == 0){
							?>
			                <li class="propertyinfoli">
			                    <?php
			                	$msg = JText::_(OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS);
								$msg = str_replace("'","\'",$msg);
			                	?>
			                    <a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="_saveListingLink save has icon s_16">
			                   	    <?php echo JText::_('OS_ADD_TO_FAVORITES');?>
			                   	</a>
			                </li class="propertyinfoli">
			                <?php
						}else{
							?>
							<li class="propertyinfoli">
			                    <?php
			                	$msg = JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS');
								$msg = str_replace("'","\'",$msg);
			                	?>
			                    <a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="_saveListingLink save has icon s_16">
			                   	    <?php echo JText::_('OS_REMOVE_FAVORITES');?>
			                   	</a>
			                </li class="propertyinfoli">
							<?php 
						}
					}
					if($configClass['property_pdf_layout'] == 1){
					?>
					<li class="propertyinfoli">
						<a href="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_pdf&id=<?php echo $row->id?>" title="<?php echo JText::_('OS_EXPORT_PDF')?>"  rel="nofollow" target="_blank" class="_saveListingLink pdf has icon s_16">
						PDF
						</a>
					</li>
					<?php
					}
					if($configClass['property_show_print'] == 1){
					?>
					<li class="propertyinfoli">
						<a target="_blank" href="<?php echo JURI::root()?>index.php?option=com_osproperty&tmpl=component&no_html=1&task=property_print&id=<?php echo $row->id?>" class="print has icon s_16">
	                    <?php echo JText::_(OS_PRINT_THIS_PAGE)?>
	                    </a>
	                </li>
	                <?php
					}
					if($configClass['social_sharing']== 1){
						
					$url = JRoute::_("index.php?option=com_osproperty&task=property_details&id=$row->id");
					$url = JUri::getInstance()->toString(array('scheme', 'user', 'pass', 'host')).$url;
					?>
	                <li class="propertyinfoli">
	                    <a href="http://www.facebook.com/share.php?u=<?php echo $url;?>" target="_blank" class="facebookic has icon s_16" title="<?php echo JText::_('OS_ASK_YOUR_FACEBOOK_FRIENDS');?>" id="link2Listing" rel="canonical"><?php echo JText::_('OS_SHARE')?></a>
	                </li>
	                 <li class="propertyinfoli">
	                    <a href="https://twitter.com/intent/tweet?original_referer=<?php echo $url;?>&tw_p=tweetbutton&url=<?php echo $url;?>" target="_blank" class="twitteric has icon s_16" title="<?php echo JText::_('OS_ASK_YOUR_TWITTER_FRIENDS');?>" id="link2Listing" rel="canonical"><?php echo JText::_('OS_TWEET')?></a>
	                </li>
	                <?php
					}
	                ?>
				</ul> 
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="detailsBar clearfix">
<div class="row-fluid">
	<div class="span8">
		<div class="shell">
	    	<div class="row-fluid">
				<div class="span12">
					<div class="tabs clearfix">
					    <div class="tabbable">
					        <ul class="nav nav-tabs">
					        	<li class="active"><a href="#detailstab" data-toggle="tab">
					            	<i class="osicon-key"></i>&nbsp;<?php echo JText::_('OS_DETAILS');?>
					            </a></li>
					        	<?php
					        	if($configClass['show_agent_details'] == 1){
					        	?>
					            <li><a href="#agenttab" data-toggle="tab">
					            	<i class="osicon-user"></i>&nbsp;<?php echo JText::_('OS_AGENT');?>
					            </a></li>
					            <?php
					        	}
								?>
								<?php
								if($configClass['show_walkscore'] == 1){
									if($configClass['ws_id'] != ""){
								?>
					            	<li><a href="#walkscore" data-toggle="tab"><i class="osicon-flag-2"></i>&nbsp;<?php echo JText::_('OS_WALK_SCORE');?></a></li>
					            <?php
									}
								}
								if($row->pro_video != ""){
								?>
									<li><a href="#tour" data-toggle="tab"><i class="osicon-camera"></i>&nbsp;<?php echo JText::_('OS_VIRTUAL_TOUR');?></a></li>
								<?php
								}
								?>
								<?php
								if(($configClass['energy'] == 1) and (($row->energy > 0) or ($row->climate > 0))){
								?>
								<li><a href="#epc" data-toggle="tab"><i class="osicon-chart"></i>&nbsp;<?php echo JText::_('OS_EPC');?></a></li>
								<?php
								}
								if($configClass['integrate_education'] == 1){
									?>
									<li><a href="#educationtab" data-toggle="tab"><i class="osicon-location"></i>&nbsp;<?php echo JText::_('OS_EDUCATION');?></a></li>
									<?php
								}
								?>
					        </ul>            
					    </div>
					    <div class="tab-content">
					        <!-- tab1 -->
					      	<div class="tab-pane active" id="detailstab">
					      		<div class="row-fluid">
									<div class="span12">
										<?php
										$row->pro_small_desc = OSPHelper::getLanguageFieldValue($row,'pro_small_desc');
										if($row->pro_small_desc != ""){
											echo stripslashes($row->pro_small_desc);
											echo "<BR />";
										}
										//echo $row->pro_full_desc;
										$pro_full_desc = OSPHelper::getLanguageFieldValue($row,'pro_full_desc');
										$row->pro_full_desc =  JHtml::_('content.prepare', $pro_full_desc);
										echo stripslashes($row->pro_full_desc);
										?>
										
									</div>
								</div>
								<div class="clearfix"></div>
								<?php
								if(($configClass['show_feature_group'] == 1) and ($row->amens_str != "")){
								?>
								<div class="row-fluid">
									<div class="span12">
										<h4><?php echo JText::_('OS_AMENITIES')?>: </h4>
										<div class="clearfix"></div>
										<?php echo $row->amens_str?>
									</div>
								</div>
								<div class="clearfix"></div>
								<?php
								}
								?>
					        	<?php
					        	if(($configClass['show_neighborhood_group'] == 1) and ($row->neighborhood != "")){
					        	?>
                                <div class="row-fluid"><div class="span12">
					            <h4>
					            	<?php echo JText::_('OS_NEIGHBORHOOD')?>
					            </h4>
					            </div></div>
					           	<div class="row-fluid">
                                    <?php
                                        echo $row->neighborhood;
                                    ?>
					           	</div>
						        <?php } ?>
                                <?php
                                if(count($row->extra_field_groups) > 0){
                                    if($extrafieldncolumns == 2){
                                        $span = "span6";
                                        $jump = 2;
                                    }else{
                                        $span = "span4";
                                        $jump = 3;
                                    }
                                    $extra_field_groups = $row->extra_field_groups;
                                    for($i=0;$i<count($extra_field_groups);$i++){
                                        $group = $extra_field_groups[$i];
                                        $group_name = $group->group_name;
                                        $fields = $group->fields;
                                        if(count($fields)> 0){
                                            ?>
                                            <div class="row-fluid">
                                                <h4>
                                                    <?php echo $group_name;?>
                                                </h4>
                                                <div class="span12">
                                                    <?php
                                                    $k = 0;
                                                    for($j=0;$j<count($fields);$j++){
                                                    $field = $fields[$j];
                                                    if($field->field_type != "textarea"){
                                                    $k++;
                                                    ?>
                                                    <div class="<?php echo $span; ?>">
                                                        <i class="osicon-ok"></i>&nbsp;
                                                        <?php
                                                        if(($field->displaytitle == 1) or ($field->displaytitle == 2)){
                                                            ?>
                                                            <?php
                                                            if($field->field_description != ""){
                                                                ?>
                                                                <span class="editlinktip hasTip" title="<?php echo $field->field_label;?>::<?php echo $field->field_description?>">
												<?php echo $field->field_label;?>
											</span>
                                                            <?php
                                                            }else{
                                                                ?>
                                                                <?php echo $field->field_label;?>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                        <?php
                                                        if($field->displaytitle == 1){
                                                            ?>
                                                            :&nbsp;
                                                        <?php } ?>
                                                        <?php if(($field->displaytitle == 1) or ($field->displaytitle == 3)){?>
                                                            <?php echo $field->value;?> <?php } ?>
                                                    </div>
                                                    <?php
                                                    if($k == $jump){
                                                    ?>
                                                </div><div class='span12' style='min-height:0px !important;'>
                                                    <?php
                                                    $k = 0;
                                                    }
                                                    }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            for($j=0;$j<count($fields);$j++) {
                                                $field = $fields[$j];
                                                if ($field->field_type == "textarea") {
                                                    ?>
                                                    <div class="row-fluid">
                                                        <div class="span12">
                                                            <?php
                                                            if (($field->displaytitle == 1) or ($field->displaytitle == 2)) {
                                                                ?>
                                                                <i class="osicon-ok"></i>&nbsp;
                                                                <?php
                                                                if ($field->field_description != "") {
                                                                    ?>
                                                                    <span class="editlinktip hasTip"
                                                                          title="<?php echo $field->field_label;?>::<?php echo $field->field_description?>">
													<strong><?php echo $field->field_label;?></strong>
												</span>
                                                                    <BR/>
                                                                <?php
                                                                } else {
                                                                    ?>
                                                                    <strong><?php echo $field->field_label;?></strong>
                                                                <?php
                                                                }
                                                            }
                                                            ?>
                                                            <?php if (($field->displaytitle == 1) or ($field->displaytitle == 3)) { ?>
                                                                <?php echo $field->value; ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            }
                                        }
                                    }
                                }
								if($row->pro_pdf != ""){
					            ?>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <strong><?php echo JText::_('OS_PROPERTY_DOCUMENT')?></strong>:
                                        <a href="<?php echo $row->pro_pdf?>" title="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" alt="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" target="_blank">
                                            <?php echo $row->pro_pdf?>
                                        </a>
                                    </div>
                                </div>

					            <?php
								}
								if($row->pro_pdf_file != ""){
					            ?>
                                <div class="row-fluid">
                                    <div class="span12" style="margin-left:0px;">
                                        <strong><?php echo JText::_('OS_PROPERTY_DOCUMENT')?></strong>:
                                        <a href="<?php echo JURI::root()."components/com_osproperty/document/";?><?php echo $row->pro_pdf_file?>" title="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" alt="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" target="_blank">
                                            <img src="<?php echo JURI::root()."components/com_osproperty/images/assets"; ?>/pdf.png" />
                                        </a>
                                    </div>
					            </DIV>
					            <?php
								}
								?>
								<?php
								if(count($tagArr) > 0){
									?>
									<div class="row-fluid">
										<div class="span12">
											<h4><?php echo JText::_('OS_TAGS')?></h4>
											<?php
											echo implode(" ",$tagArr);
											?>
										</div>
									</div>
									<div class="clearfix"></div>
									<?php
								}	
								?>									
								<div class="row-fluid">
									<div class="span12">
									    <?php echo $facebook_like;?>
									</div>
								</div>
					      	</div>
					      	<?php
				        	if($configClass['show_agent_details'] == 1){
				        	?>
						        <div class="tab-pane<?php echo $agent_div?>" id="agenttab">
						        	<?php
									echo $row->agent;
									?>
						        </div>
					        <?php
					        }
							if(($configClass['show_walkscore'] == 1) and ($configClass['ws_id'] != "")){
								if($configClass['ws_id'] != ""){
							?>
					        <div class="tab-pane<?php echo $walkscore_div?>" id="walkscore">
					        	<?php
								echo $row->ws;
								?>
					        </div>
					        <?php
								}
							}
					        if($row->pro_video != ""){
					        ?>
					        
					        <div class="tab-pane<?php echo $video_div?>" id="tour">
					        	<?php
								echo stripslashes($row->pro_video);
								?>
					        </div>
					        <?php
					        }
					        ?>
					        <?php
							if(($configClass['energy'] == 1) and (($row->energy > 0) or ($row->climate > 0))){
							?>
							<div class="tab-pane<?php echo $energy_div?>" id="epc">
					        	<?php
								echo HelperOspropertyCommon::drawGraph($row->energy, $row->climate);
								?>
					        </div>
							<?php
							}
							?>
							<?php 
						    if($configClass['integrate_education'] == 1){
						    ?>
						    <div class="tab-pane<?php echo $education_div?>" id="educationtab">
					        	<?php
								echo stripslashes($row->education);
								?>
					        </div>
					        <?php } ?>
					    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="row-fluid">
			<?php 
			if($configClass['use_open_house'] == 1){
			?>
			<div class="clearfix"></div>
			<div class="span12 feature_title noleftmargin">
				<?php echo JText::_('OS_OPEN_FOR_INSPECTION_TIMES');?>
			</div>
			<div class="span12 noleftmargin">
				<?php echo $row->open_hours;?>
			</div>
			<?php }
			?>
			<div class="span12 feature_title noleftmargin">
				<?php echo JText::_('OS_FEATURES');?>
			</div>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_CATEGORY').":";
					?>
				</div>
				<div class="span6">
					<?php
					/*
					$needs = array();
					$needs[] = "category_listing";
					$needs[] = "lcategory";
					$itemid  = OSPRoute::getItemid($needle);
					$link    = JRoute::_('index.php?option=com_osproperty&task=category_details&id='.$row->category_id.'&Itemid='.$itemid);
					echo "<a href='$link' title='$row->category_name'>".$row->category_name."</a>";
					*/
					?>
					<?php echo OSPHelper::getCategoryNamesOfPropertyWithLinks($row->id);?>
				</div>
			</div>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_PROPERTY_TYPE').":";
					?>
				</div>
				<div class="span6">
					<?php 
					$needs = array();
					$needs[] = "property_type";
					$needs[] = "ltype";
					$needs[] = "type_id=".$row->pro_type;
					$itemid  = OSPRoute::getItemid($needle);
					$link    = JRoute::_('index.php?option=com_osproperty&task=property_type&type_id='.$row->pro_type.'&Itemid='.$itemid);
					echo "<a href='$link' title='$row->type_name'>".$row->type_name."</a>";
					?>
				</div>
			</div>
			<?php
			if(($configClass['use_bedrooms'] == 1) and ($row->bed_room > 0)){
			?>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_BEDS').":";
					?>
				</div>
				<div class="span6">
					<?php echo $row->bed_room;?>
				</div>
			</div>
			<?php
			}
			?>
			<?php
			if(($configClass['use_bathrooms'] == 1) and ($row->bath_room > 0)){
			?>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_BATHS').":";
					?>
				</div>
				<div class="span6">
					<?php echo OSPHelper::showBath($row->bath_room);?>
				</div>
			</div>
			<?php
			}
			?>
			<?php
			if(($configClass['use_nfloors'] == 1) and ($row->number_of_floors > 0)){
			?>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_FLOORS').":";
					?>
				</div>
				<div class="span6">
					<?php echo $row->number_of_floors;?>
				</div>
			</div>
			<?php
			}
			?>
			<?php
			if(($configClass['use_rooms'] == 1) and ($row->rooms > 0)){
			?>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_ROOMS').":";
					?>
				</div>
				<div class="span6">
					<?php echo $row->rooms;?>
				</div>
			</div>
			<?php
			}
			?>
			<?php
			if(($configClass['use_squarefeet'] == 1) and ($row->square_feet > 0)){
			?>
			<div class="span12">
				<div class="span6">
					<?php
					echo OSPHelper::showSquareLabels().":";
					?>
				</div>
				<div class="span6">
					<?php echo OSPHelper::showSquare($row->square_feet);?>
				</div>
			</div>
			<?php
			}
			?>
			<?php
			if(($configClass['use_squarefeet'] == 1) and ($row->lot_size > 0)){
			?>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_LOT_SIZE').":";
					?>
				</div>
				<div class="span6">
					<?php echo OSPHelper::showLotsize($row->lot_size);?> <?php echo OSPHelper::showSquareSymbol();?>
				</div>
			</div>
			<?php
			}
			?>	
			<?php
			if(($configClass['use_parking'] == 1) and ($row->parking > 0)){
			?>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_PARKING').":";
					?>
				</div>
				<div class="span6">
					<?php echo $row->parking;?>
				</div>
			</div>
			<?php
			}
			?>
			<?php
			if(($row->pro_pdf != "") or ($row->pro_pdf_file != "")){
			?>
			<div class="span12">
				<div class="span6">
					<?php
					echo JText::_('OS_PROPERTY_DOCUMENT').":";
					?>
				</div>
				<div class="span6">
					<?php
					if($row->pro_pdf != ""){
						?>
						<a href="<?php echo $row->pro_pdf?>" title="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" alt="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" target="_blank">
							<img src="<?php echo JURI::root()."components/com_osproperty/images/assets"; ?>/pdf.png" />
						</a>
						&nbsp;&nbsp;
						<?php
					}
					if($row->pro_pdf_file != ""){
						?>
						<a href="<?php echo JURI::root()."components/com_osproperty/document/";?><?php echo $row->pro_pdf_file?>" title="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" alt="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" target="_blank">
							<img src="<?php echo JURI::root()."components/com_osproperty/images/assets"; ?>/pdf.png" />
						</a>
						<?php
					}
					?>
				</div>
			</div>
			<?php
			}
			?>
			<BR />
			<div class="span12 feature_title noleftmargin">
				<?php echo JText::_('OS_LOCATION');?>
			</div>
			<div class="span12 noleftmargin">
			<?php
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
			
			$db->setQuery("Select type_icon from #__osrs_types where id = '$row->pro_type'");
			$type_icon = $db->loadResult();
			if($type_icon == ""){
				$type_icon = "1.png";
			}
			?>
			<script type="text/javascript">
			// <![CDATA[
		    var map;
		    var panorama;
		    var centerPlace = new google.maps.LatLng(parseFloat(<?php echo $row->lat_add?>), parseFloat(<?php echo $row->long_add?>));
		    var propertyListing = new google.maps.LatLng(parseFloat(<?php echo $row->lat_add?>), parseFloat(<?php echo $row->long_add?>));
		    var citymap = {};
		    citymap['chicago'] = {
		      center: new google.maps.LatLng(parseFloat(<?php echo $row->lat_add?>), parseFloat(<?php echo $row->long_add?>)),population: <?php echo $population;?>
		    };
				 function initMapPropertyDetails() {
				     // Set up the map
				     var streetview = new google.maps.StreetViewService();
				     var mapOptions = {
				       center: centerPlace,
				       zoom: <?php echo $google_map_resolution;?>,
				       mapTypeId: google.maps.MapTypeId.<?php echo $google_map_overlay?>,
				       streetViewControl: false
				     };
					   //alert(<?php echo $row->long_add?>);
				     map = new google.maps.Map(document.getElementById('googlemapdiv'), mapOptions);
				     // Setup the markers on the map
				
				     <?php if($row->show_address == 1){?>
					     var propertyListingMarkerImage =
					         new google.maps.MarkerImage(
					            '<?php echo JURI::root()?>components/com_osproperty/images/assets/googlemapicons/<?php echo $type_icon;?>');
					     var propertyListingMarker = new google.maps.Marker({
					         position: propertyListing,
					         map: map,
					         icon: propertyListingMarkerImage,
					         title: 'Property ID <?php echo $row->id?>'
					     });
					 <?php }else {?>
						 for (var city in citymap) {
				    	    var populationOptions = {
				    	      strokeColor: '#1D86A0',
				    	      strokeOpacity: 0.8,
				    	      strokeWeight: 2,
				    	      fillColor: '#1D86A0',
				    	      fillOpacity: 0.35,
				    	      map: map,
				    	      center: citymap[city].center,
				    	      radius: Math.sqrt(citymap[city].population) * 100
				    	    };
				    	    // Add the circle for this city to the map.
				    	    cityCircle = new google.maps.Circle(populationOptions);
				    	  }
			    	 <?php } ?>
				
				     // We get the map's default panorama and set up some defaults.
				     // Note that we don't yet set it visible.
				     panorama = map.getStreetView();
				     streetview.getPanoramaByLocation(centerPlace, 25, function(data, status){
				     	switch(status){
		                     case google.maps.StreetViewStatus.OK:
							     panorama.setPosition(centerPlace);
							     panorama.setPov({
							         heading: 265,
							         zoom:1,
							         pitch:0}
							     );
				     	    break;
				     	    case google.maps.StreetViewStatus.ZERO_RESULTS:
		                         document.getElementById('togglebtn').style.display = "none";
		                    break;
		                    default:
		                         document.getElementById('togglebtn').style.display = "none";
				        }
				     });
				  }
				
				  function toggleStreetView() {
				     var toggle = panorama.getVisible();
				     if (toggle == false) {
				         panorama.setVisible(true);
				     } else {
				         panorama.setVisible(false);
				     }
				  }
				  
				  window.onload=function(){
					  if(window.initMapPropertyDetails) initMapPropertyDetails();
				  }
				
				  window.onunload=function(){
				      if(typeof(GUnload)!="undefined") GUnload();
				  }
				// ]]>
				</script>
			    <div id="googlemapdiv" style="position:relative;width: 100%; min-height: 150px"></div>
			    <?php 
			    if(($configClass['show_streetview'] == 1) and ($row->show_address == 1)){
			    ?>
			    <div id="toggle">
				   <input type="button" id="togglebtn" class="btn btn-inverse" value="<?php echo JText::_('OS_TOGGLE_STREET_VIEW');?>" onclick="toggleStreetView();" />
			    </div>
			    <?php } ?>
			</div>
			<div class="span12 noleftmargin" style="border-bottom:1px solid #CCC;"></div>
				<?php
				if($configClass['show_request_more_details'] == 1){
				?>
			<div class="span12 otherurls noleftmargin">
				<a href="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_showRequestMoreDetails&id=<?php echo $row->id;?>&tmpl=component&c=<?php echo $row->ResultStr;?>" class="osmodal" rel="{handler: 'iframe', size: {x: 500, y: 500}}">
					<?php echo JText::_('OS_REQUEST_MORE_INFOR')?> &#9658
				</a>
			</div>
			<?php }
			if($configClass['property_mail_to_friends'] == 1){
			?>
			<div class="span12 otherurls noleftmargin">
				<a href="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_showSharingForm&id=<?php echo $row->id;?>&tmpl=component&c=<?php echo $row->ResultStr;?>" class="osmodal" rel="{handler: 'iframe', size: {x: 500, y: 500}}">
					<?php echo JText::_('OS_SHARING')?> &#9658
				</a>
			</div>
			<?php	
			}
			?>
		</div>
	</div>
</div>

<!--- wrap content -->
<?php
if(count($middlePlugin) > 0){
	for($i=0;$i<count($middlePlugin);$i++){
		echo $middlePlugin[$i];
	}
}
//if($row->pagination == 1){
	//echo "<a href='$row->prev_link'>Prev</a>"; 
	//echo "<a href='$row->next_link'>Next</a>"; 
//}
?>
<!-- tabs bottom -->

<?php
	if(file_exists(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."oscalendar.php")){
		if(($configClass['integrate_oscalendar'] == 1) and (in_array($row->pro_type,explode("|",$configClass['show_date_search_in'])))){
			?>
			<div class="detailsBar clearfix">
				<div class="row-fluid">
					<div class="span12">
						<div class="shell">
						<fieldset><legend><span><?php echo JText::_('OS_AVAILABILITY')?></span></legend></fieldset>
						<?php
						require_once(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."classes".DS."default.php");
						require_once(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."classes".DS."default.html.php");
						$otherlanguage =& JFactory::getLanguage();
						$otherlanguage->load( 'com_oscalendar', JPATH_SITE );
						OsCalendarDefault::calendarForm($row->id);
						?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>
<?php
if(($configClass['relate_properties'] == 1) and ($row->relate != "")){
?>
	<div class="detailsBar clearfix">
		<div class="row-fluid">
			<div class="span12">
				<div class="shell">
			    	<fieldset><legend><span><?php echo JText::_('OS_RELATE_PROPERTY')?></span></legend></fieldset>
			    	<?php
			    	echo $row->relate;
			    	?>
		    	</div>
			</div>
		</div>
	</div>
<?php
}
if($configClass['comment_active_comment'] == 1){
?>
<div class="detailsBar clearfix">
	<div class="row-fluid">
		<div class="span12">
			<div class="shell">
		    	<fieldset><legend><span><?php echo JText::_('OS_COMMENTS')?></span></legend></fieldset>
		    	<?php
				echo $row->comments;
				?>
				<?php
				if(($owner == 0) and ($can_add_cmt == 1)){
				?>
				<div class="block_caption" id="comment_form_caption">
					<strong><?php echo JText::_(OS_ADD_COMMENT)?></strong>
				</div>	
				
				<div class="span12" style="margin-left:0px;">
					<form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_submitcomment&Itemid=<?php echo $itemid;?>" name="commentForm" id="commentForm" class="form-horizontal">
					
					<?php
					if($configClass['show_rating'] == 1){
					?>
					<div class="control-group">
						<label class="control-label">
							<?php echo JText::_('OS_RATING');?>
						</label>
						<div class="controls">
							<i><?php echo JText::_('OS_WORST');?>
							&nbsp;
							<?php
							for($i=1;$i<=5;$i++){
								if($i==3){
									$checked = "checked";
								}else{
									$checked = "";
								}
								?>
								<input type="radio" name="rating" id="rating<?php echo $i?>" value="<?php echo $i?>" <?php echo $checked?> />
								<?php
							}
							?>
							&nbsp;&nbsp;<?php echo JText::_('OS_BEST');?></i>
						</div>
					</div>
					<?php
					}
					?>
					<div class="control-group">
						<label class="control-label">
							<?php echo JText::_('OS_AUTHOR');?>
						</label>
						<div class="controls">
							<input class="input-large" type="text" id="comment_author" name="comment_author" maxlength="30" />
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">
							<?php echo JText::_('OS_TITLE');?>
						</label>
						<div class="controls">
							<input class="input-large" type="text" id="comment_title" name="comment_title" size="40"  />
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">
							<?php echo JText::_('OS_MESSAGE');?>
						</label>
						<div class="controls">
							<textarea id="comment_message" name="comment_message" rows="6" cols="50" class="input-large"></textarea>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">
							<?php echo JText::_('OS_SECURITY_CODE');?>
						</label>
						<div class="controls">
							<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr;?>" />
							<input type="text" class="input-mini" id="comment_security_code" name="comment_security_code" maxlength="5" style="width: 50px; margin: 0;"/>
							<div class="clearfix"></div>
							<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="span12" style="margin-left:0px;">
						<input onclick="javascript:submitForm('commentForm')" style="margin: 0; width: 100px;" class="btn btn-warning" type="button" name="finish" value="<?php echo JText::_('OS_SUBMIT')?>" />
						<input type="reset" class="btn btn-info" value="<?php echo JText::_('OS_RESET');?>" />
						<span id="comment_loading" class="reg_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
					</div>
					
					<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
					<input type="hidden" name="option" value="com_osproperty" />
					<input type="hidden" name="task" value="property_submitcomment" />
					<input type="hidden" name="id" value="<?php echo $row->id?>" />
					<input type="hidden" name="Itemid" value="<?php echo $itemid?>" />
					<input type="hidden" name="require_field" id="require_field" value="comment_author,comment_title,comment_message,comment_security_code" />
					<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_(OS_AUTHOR);?>,<?php echo JText::_(OS_TITLE);?>,<?php echo JText::_(OS_MESSAGE);?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
					</form>
				</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
}
if(($configClass['use_property_history'] == 1) and (($row->price_history != "") or ($row->tax != ""))){
?>
	<div class="detailsBar clearfix">
		<div class="row-fluid">
			<div class="span12">
				<div class="shell">
			    	<fieldset><legend><span><?php echo JText::_('OS_HISTORY_TAX')?></span></legend></fieldset>
			    	<?php
					if($row->price_history != ""){
						echo $row->price_history;
						echo "<BR />";
					}
					if($row->tax != ""){
						echo $row->tax;
					}
			    	?>
		    	</div>
			</div>
		</div>
	</div>
<?php	
}
if($integrateJComments == 1){
?>
	<div class="detailsBar clearfix">
		<div class="row-fluid">
			<div class="span12">
				<div class="shell">
			    	<fieldset><legend><span><?php echo JText::_('OS_JCOMMENTS')?></span></legend></fieldset>
			    	<?php
			    	$comments = JPATH_SITE . DS .'components' . DS . 'com_jcomments' . DS . 'jcomments.php';
				    if (file_exists($comments)) {
				    	require_once($comments);
				    	echo JComments::showComments($row->id, 'com_osproperty', $row->pro_name);
				    }
			    	?>
		    	</div>
			</div>
		</div>
	</div>
<?php
}
?>
<?php 
if(($configClass['show_twitter'] == 1) or ($configClass['google_plus'] == 1) or ($configClass['pinterest'] == 1)){
?>
<div class="row-fluid">
	<div class="span12">
		<?php echo $row->tweet_div;?>
		<?php echo $row->gplus_div;?>
		<?php echo $row->pinterest;?>
	</div>
</div>
<?php 
}
?>
<?php
if(count($bottomPlugin) > 0){
	for($i=0;$i<count($bottomPlugin);$i++){
		echo $bottomPlugin[$i];
	}
}
?>
</div>
<!-- end tabs bottom -->

<!-- end wrap content -->