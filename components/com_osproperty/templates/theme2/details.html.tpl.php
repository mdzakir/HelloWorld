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
$document->addStyleSheet(JURI::root()."components/com_osproperty/templates/".$themename."/style/style.css");
$extrafieldncolumns = $params->get('extrafieldncolumns',3);
$show_request = $params->get('show_request_more_details','top');
$show_location = $params->get('show_location',1);
?>
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
<div class="row-fruild">
	<div class="name-property clearfix" style="text-align:center;">
        <!--------- title -------->
        <h1>
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
        <!------------- end title --------------->
        <!----- location ----->
        <div class="subtitle">
        	<?php echo $row->subtitle; ?>
        </div>
        <!------ end location ----->
    </div>
    <!------------- social ------------------>
    <div class="clearfix">
        <?php echo $facebook_like;?>
    </div>
    <!----------------- end social --------------->
</div>

<!--- wrap content -->

<div class="lightGrad detailsView clearfix">
	<div class="row-fluid">
		<!-- content -->
		<?php
		if($show_request == "top"){
			$span = "8";
		}else{
			$span = "12";
		}
		?>
		<div class="span<?php echo $span;?>">
	    	<!-- tab1 -->
	        <div class="row-fluid">    
	            <div class="span6">
	            	<script src="<?php echo JURI::root()?>components/com_osproperty/js/jquery.bxslider.js"></script>
	            	<div>
		   				<div id="slides">
		   					<?php
		   					if(count($photos) > 0){
		   					?>
			   				<ul class="bxslider" style="padding:0px;margin:0px;">
				                <?php
				                for($i=0;$i<count($photos);$i++){
				                	if($photos[$i]->image != ""){
				                		if(JPATH_ROOT.'/images/osproperty/properties/'.$row->id.'/medium/'.$photos[$i]->image){
				                			?>
				                			<li class="propertyinfoli"><a class="osmodal" href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/<?php echo $photos[$i]->image?>"><img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/medium/<?php echo $photos[$i]->image?>" alt="<?php echo $photos[$i]->image_desc;?>" title="<?php echo $photos[$i]->image_desc;?>"/></a></li>
				                			<?php
				                		}else{
				                			?>
				                			<li class="propertyinfoli"><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" alt="<?php echo $photos[$i]->image_desc;?>" title="<?php echo $photos[$i]->image_desc;?>"/></li>
				                			<?php
				                		}
				                	}else{
				                		?>
				                		<li class="propertyinfoli"><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" alt="<?php echo $photos[$i]->image_desc;?>" title="<?php echo $photos[$i]->image_desc;?>"/></li>
				                		<?php
				                	}
				                }
				                ?>
			                </ul>
			                <div id="bx-pager" style="text-align: center;padding-top: 5px;">
			                <?php
				                for($i=0;$i<count($photos);$i++){
				                	if($photos[$i]->image != ""){
										if(JPATH_ROOT.'/images/osproperty/properties/'.$row->id.'/thumb/'.$photos[$i]->image){
											?>
											<a data-slide-index="<?php echo $i;?>" href=""><img class="detailwidth" alt="<?php echo $photos[$i]->image_desc;?>" title="<?php echo $photos[$i]->image_desc;?>" src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/thumb/<?php echo $photos[$i]->image?>" /></a>
											<?php
				                		}else{
				                			?>
											<a data-slide-index="<?php echo $i;?>" href=""><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" /></a>
											<?php
				                		}
				                	}else{
				                		?>
				                		<a data-slide-index="<?php echo $i;?>" href=""><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" /></a>
				                		<?php
				                	}
				                }
				                ?>

							</div>
			                <script>
						    jQuery(document).ready(function(){
								jQuery('.bxslider').bxSlider({
								  pagerCustom: '#bx-pager',
								  mode: 'fade',
								  captions: true
								});
								  });
						   </script>
						   <?php
		   					}else{
							   	?>
							   	<ul class="bxslider" style="padding:0px;margin:0px;">
							   		<li class="propertyinfoli"><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" alt="" title=""/></li>
							   	</ul>
							   	<?php
						   }
						   ?>
		               </div>
		            </div>
	            </div>
	            <div class="span6">
	            	<div class="descriptionWrap">
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
						<div class="detailsView-price"> <?php echo $row->price1;?> </div>
	                    <ul class="attribute-list">
	                    	<?php
	                    	if($configClass['show_feature_group'] == 1){
		                    	if(($row->bed_room > 0) and ($configClass['use_bedrooms'] == 1)){
		                    	?>
		                        <li class="propertyinfoli"><strong><?php echo JText::_('OS_BEDS')?>: </strong><span><?php echo $row->bed_room?></span></li>
		                        <?php
		                    	}
		                        ?>
		                        <?php
		                    	if(($row->bath_room > 0) and ($configClass['use_bathrooms'] == 1)){
		                    	?>
		                        <li class="propertyinfoli"><strong><?php echo JText::_('OS_BATHS')?>: </strong><span><?php echo OSPHelper::showBath($row->bath_room)?></span></li>
		                        <?php
		                    	}
		                        ?>
		                        <?php
		                    	if(($row->square_feet > 0) and ($configClass['use_squarefeet'] == 1)){
		                    	?>
		                        <li class="propertyinfoli"><strong><?php echo OSPHelper::showSquareLabels();?>: </strong><span><?php echo OSPHelper::showSquare($row->square_feet);?></span></li>
		                        <?php
		                    	}
		                        ?>
		                        <?php
		                    	if(($row->lot_size > 0) and ($configClass['use_squarefeet'] == 1)){
		                    	?>
		                        <li class="propertyinfoli"><strong><?php echo JText::_('OS_LOT_SIZE');?>: </strong><span><?php echo OSPHelper::showLotsize($row->lot_size);?> <?php echo OSPHelper::showSquareSymbol();?></span></li>
		                        <?php
		                    	}
		                        ?>
		                        <?php
		                    	if(($configClass['use_nfloors'] == 1) and ($row->number_of_floors != "")){
		                    	?>
		                        <li class="propertyinfoli"><strong><?php echo JText::_('OS_FLOORS')?>: </strong><span><?php echo $row->number_of_floors;?></span></li>
		                        <?php
		                    	}
		                        ?>
								<?php
		                    	if(($configClass['use_rooms'] == 1) and ($row->rooms > 0)){
		                    	?>
		                        <li class="propertyinfoli"><strong><?php echo JText::_('OS_ROOMS')?>: </strong><span><?php echo $row->rooms;?></span></li>
		                        <?php
		                    	}
		                        ?>
		                        <?php
								if($configClass['use_parking'] == 1){
								?>
								<li class="propertyinfoli"><strong><?php echo JText::_('OS_PARKING')?>: </strong><span><?php echo $row->parking;?></span></li>
		                        <?php
		                    	}
	                    	}
	                        ?>
	                        <?php 
							if($configClass['listing_show_view'] == 1){
							?>
	                        <li class="propertyinfoli"><strong><?php echo JText::_('OS_TOTAL_VIEWING')?>: </strong><span><?php echo $row->hits?></span></li>
	                        <?php 
							} 
							if($configClass['show_rating'] == 1){
							?>
	                        <li class="propertyinfoli"><span><?php echo $row->ratingvalue?></span></li>
	                        <?php
							}
	                        ?>
	                    </ul>
	                    <ul class="attribute-list">
	                    	<?php
	                    	if($row->ref != ""){
	                    	?>
	                        <li><strong>Ref #: </strong><span><?php echo $row->ref;?></span></li>
	                        <?php } ?>
	                        <li><strong><?php echo JText::_('OS_TYPE')?>: </strong><span><?php echo $row->type_name?></span></li>
	                        <li><strong><?php echo JText::_('OS_CATEGORY')?>: </strong><span><?php echo $row->category_name?></span></li>
	                    </ul>
	                    <div class="clearfix"></div>
	                    <?php
			        	if($configClass['show_agent_details'] == 1){
			        	?>
	                    <div class="listing-agent mediaBox">
	                        <div class="photoF media">
	                            <div class="mat">
	                            	<?php echo $row->agentphoto;?>
	                            </div>
	                        </div>
	                        <div class="details">
	                            <ul>
	                                <li class="propertyinfoli">
	                                	<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=agent_info&id=".$row->agent_id);?>"><?php echo $row->agent_name;?></a>
	                            	</li>
	                            	<?php
	                               if(($row->agent_phone != "") and ($configs[46]->fieldvalue == 1)){
									?>
									<li class="propertyinfoli">
										<?php echo $row->agent_phone;?>
									</li>
									<?php
									}    
									if(($row->agent_mobile != "") and ($configs[45]->fieldvalue == 1)){
									?>
									<li class="propertyinfoli">
										<?php echo $row->agent_mobile;?>
									</li>
									<?php
									}
									?>                                              
	                          	</ul>
	                        </div>
	                    </div>
	                    <?php } ?>
	              	</div>
	            </div>                    
	        </div>
	        <div class="row-fluid clearfix">
				<strong><?php echo JText::_('OS_DESCRIPTION')?>: </strong><br />
				<span class="">
					<?php 
					$row->pro_small_desc = OSPHelper::getLanguageFieldValue($row,'pro_small_desc');
					echo stripslashes($row->pro_small_desc);
					?>
				</span>
			</div>
	    </div>
	    <!-- end content -->
	    <!-- sidebar -->
	    <?php
            if(($configClass['show_request_more_details'] == 1) and ($show_request == "top")){
        ?>
	    <div class="span4">
	    	<!-- request -->
	            <?php 
	           // if (version_compare(JVERSION, '3.0', 'lt')) {
	            	//echo JHtml::_('sliders.start', 'slide-details1');
					//echo JHtml::_('sliders.panel', JText::_('OS_REQUEST_MORE_INFOR'), 'slide1');
					?>
					<div class="accordion">
		            	<div class="accordion-group">
		                    <div class="accordion-heading">
		                    	<strong>
		                        	<span class="accordion-toggle"><?php echo JText::_('OS_REQUEST_MORE_INFOR')?></span>
		                        </strong>
		                    </div>
		                    <div class="accordion-body collapse in">
		                    	<div class="accordion-inner">
					<?php
	           // }else{
		          //  echo JHtml::_('bootstrap.startAccordion', 'slide-details1', array('active' => 'slide1'));
		          //  echo JHtml::_('bootstrap.addSlide', 'slide-details1', JText::_('OS_REQUEST_MORE_INFOR'), 'slide1'); 
	            //}
		        ?>
	            
	        	<div class="leadFormWrap">
	               <form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_requestmoredetails&Itemid=<?php echo $itemid?>" name="requestdetails_form" id="requestdetails_form">
	                
	                    <div class="_leadError ajax-error"></div>
		                    <select name='subject' id='subject' class='input-medium' onchange="javascript:updateRequestForm(this.value)">
							<option value='1'><?php echo JText::_('OS_REQUEST_1')?></option>
							<option value='2'><?php echo JText::_('OS_REQUEST_2')?></option>
							<option value='3'><?php echo JText::_('OS_REQUEST_3')?></option>
							<option value='4'><?php echo JText::_('OS_REQUEST_4')?></option>
							<option value='5'><?php echo JText::_('OS_REQUEST_5')?></option>
							<option value='6'><?php echo JText::_('OS_REQUEST_6')?></option>
						</select>
	                    
	                    <input class="input-medium" type="text" id="requestyour_name" name="requestyour_name" size="30" maxlength="30"  value="<?php echo $user->name?>" placeholder="<?php echo JText::_('OS_YOUR_NAME')?>"/>
	                    <input class="input-medium" type="text" id="your_phone" name="your_phone" maxlength="30" placeholder="<?php echo JText::_('OS_PHONE')?>"/>
	                    <input class="input-medium" type="text" id="requestyour_email" name="requestyour_email" size="30" maxlength="30"  value="<?php echo $user->email;?>" placeholder="<?php echo JText::_('OS_YOUR_EMAIL')?>"/>
	                    
	                    
	                    <textarea class="input-medium" id="requestmessage" name="requestmessage" rows="3" cols="60"><?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo ($row->ref != "")? $row->ref.", ":""?><?php echo $row->pro_name?></textarea>
	                	<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" />
						<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
						<input type="text" class="input-mini" id="request_security_code" name="request_security_code" maxlength="5" style="width: 50px; margin: 0;" />
						<input class="btn btn-info" type="button" id="requestbutton" name="requestbutton" value="<?php echo JText::_("OS_REQUEST_BUTTON1")?>" onclick="javascript:submitForm('requestdetails_form');"/>
	                    <input type="hidden" name="csrqt<?php echo intval(date("m",time()))?>" id="csrqt<?php echo intval(date("m",time()))?>" value="<?php echo $row->ResultStr?>" />
						<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
						<input type="hidden" name="option" value="com_osproperty" />
						<input type="hidden" name="task" value="property_requestmoredetails" />
						<input type="hidden" name="id" value="<?php echo $row->id?>" />
						<input type="hidden" name="Itemid" value="<?php echo $itemid;?>" />
						<input type="hidden" name="require_field" id="require_field" value="requestmessage,requestyour_name,requestyour_email,request_security_code" />
						<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_('OS_REQUEST_DETAILS');?>,<?php echo JText::_('OS_YOUR_NAME');?>,<?php echo JText::_('OS_YOUR_EMAIL');?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
	              </form> 
	              <script language="javascript">
					function updateRequestForm(subject){
						var message = document.getElementById('requestmessage');
						var requestbutton = document.getElementById('requestbutton');
						if(subject == 1){
							message.value = "<?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo $row->pro_name?>";
							requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON1')?>";
						}else if(subject == 2){
							message.value = "<?php printf(JText::_('OS_REQUEST_MSG2'),$row->pro_name);?>";
							requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON2')?>";
						}else if(subject == 3){
							message.value = "<?php echo JText::_('OS_REQUEST_MSG3')?> <?php echo $row->pro_name?>";
							requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON3')?>";
						}else if(subject == 4){
							message.value = "<?php echo JText::_('OS_REQUEST_MSG4')?> <?php echo $row->pro_name?>";
							requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON4')?>";
						}else if(subject == 5){
							message.value = "<?php echo JText::_('OS_REQUEST_MSG5')?> <?php echo $row->pro_name?>";
							requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON5')?>";
						}else if(subject == 6){
							message.value = "<?php echo JText::_('OS_REQUEST_MSG6')?> <?php echo $row->pro_name?>";
							requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON6')?>";
						}
					}
				 </script>
	        </div>
	   		<?php 
	   		//if (version_compare(JVERSION, '3.0', 'lt')) {
	   			//echo JHtml::_('sliders.panel', JText::_('OS_SHARING'), 'slide2');
	   		//}else{
		   		//echo JHtml::_('bootstrap.endSlide');
		   		//echo JHtml::_('bootstrap.addSlide', 'slide-details1', JText::_('OS_SHARING'), 'slide2'); 
	   		?>
	   		
	   		<?php
		   		//echo JHtml::_('bootstrap.endSlide');
		   		//echo JHtml::_('bootstrap.endAccordion');
	   		//}
	   		?>
	        <?php
	       // }
	        //if (version_compare(JVERSION, '3.0', 'lt')) {
	   					?>
	   					</div>
	   				</div>
	   			</div>
	   		</div>
	        <!-- end request -->
	    </div>
	    <?php
        }
        ?>
    <!-- end sidebar -->
    </div>
</div>
<?php

?>
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
<!-- end -->

<?php
if($row->pro_full_desc != ""){
?>
<div class="detailsBar clearfix">
	<div class="row-fluid">
		<div class="span12">
			<div class="shell">
		    	<fieldset><legend><span><?php echo JText::_('OS_ABOUT_PROPERTY')?></span></legend></fieldset>
		    	<?php
		    	//echo stripslashes($row->pro_full_desc);
		    	if($configClass['use_open_house'] == 1){
		    		?>
		    		<div style="float:right;">
		    			<?php echo $row->open_hours;?>
		    		</div>
		    		<?php 
		    	}
		    	$pro_full_desc = OSPHelper::getLanguageFieldValue($row,'pro_full_desc');
		    	$row->pro_full_desc =  JHtml::_('content.prepare', $pro_full_desc);
				echo stripslashes($row->pro_full_desc);
		    	?>
	    	</div>
		</div>
	</div>
</div>
<?php
}
?>
<?php
if(count($middlePlugin) > 0){
	for($i=0;$i<count($middlePlugin);$i++){
		echo $middlePlugin[$i];
	}
}
?>
<!-- description list -->
<?php
$fieldok = 0;
if(count($row->extra_field_groups) > 0){
	$extra_field_groups = $row->extra_field_groups;
	for($i=0;$i<count($extra_field_groups);$i++){
		$group = $extra_field_groups[$i];
		$group_name = $group->group_name;
		$fields = $group->fields;
		if(count($fields)> 0){
			$fieldok = 1;
		}
	}
}

if(($configClass['show_amenity_group'] == 1) or ($fieldok == 1) or ($configClass['show_neighborhood_group'] == 1) or ($row->pro_pdf != "") or ($row->pro_pdf_file != "") or (count($tagArr) > 0)){
?>
<div class="shell">
    <fieldset><legend><span><?php echo JText::_('OS_FEATURES')?></span></legend></fieldset>
    <div class="listing-features">
       	<div class="row-fluid">
			<div class="span12">
        		<?php
                if(($configClass['show_amenity_group'] == 1) and ($row->amens_str1 != "")){
        		?>
                <!--<li class="zebra"><strong><?php echo JText::_('OS_AMENITIES')?></strong></li> -->
                <h4>
                	<?php echo JText::_('OS_AMENITIES')?>
                </h4>
                <div class="clearfix"></div>
               	<div class="row-fluid">
               		<?php echo $row->amens_str1;?>
               	</div>
                <?php
        		}
        		?>
        	</div>
        	<div class="clearfix"></div>
        	<?php
        	if(($configClass['show_neighborhood_group'] == 1) and ($row->neighborhood != "")){
        	?>
            <h4>
            	<?php echo JText::_('OS_NEIGHBORHOOD')?>
            </h4>
            <div class="clearfix"></div>
           	<div class="row-fluid">
            <?php 
            echo $row->neighborhood;
            ?>
           	</div>
	        <div class="clearfix"></div>
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
            <div class="span12" style="margin-left:0px;">
            	<strong><?php echo JText::_('OS_PROPERTY_DOCUMENT')?></strong>: 
            	<a href="<?php echo $row->pro_pdf?>" title="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" alt="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" target="_blank">
					<?php echo $row->pro_pdf?>
				</a>
            </div>
            <div class="clearfix"></div>
            <?php
			}
			if($row->pro_pdf_file != ""){
            ?>
            <div class="span12" style="margin-left:0px;">
            	<strong><?php echo JText::_('OS_PROPERTY_DOCUMENT')?></strong>: 
            	<a href="<?php echo JURI::root()."components/com_osproperty/document/";?><?php echo $row->pro_pdf_file?>" title="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" alt="<?php echo JText::_('OS_PROPERTY_DOCUMENT')?>" target="_blank">
					<img src="<?php echo JURI::root()."components/com_osproperty/images/assets"; ?>/pdf.png" />
				</a>
            </div>
            <div class="clearfix"></div>
            <?php
			}
			if(count($tagArr) > 0){
            ?>
            <div class="span12" style="margin-left:0px;">
            	<h4><?php echo JText::_('OS_TAGS')?></h4>
            	<?php echo implode(" ",$tagArr);?>
            </div>
            <div class="clearfix"></div>
            <?php
			}
            ?>
        </div>
	</div>
</div>
<?php 
} 
?>
<!-- end des -->
<?php
if(($configClass['goole_use_map'] == 1) and ($row->lat_add != "") and ($row->long_add != "")){

$address = OSPHelper::generateAddress($row);
?>
<div class="row-fluid">
	<div class="span12">
		<div class="shell">
	    	<fieldset><legend><span><?php echo JText::_('OS_LOCATION')?></span></legend></fieldset>
	    	<?php
            if($show_location == 1){
                OSPHelper::showLocationAboveGoogle($address);
            }
			?>	
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
			   var centerPlace = new google.maps.LatLng(<?php echo $row->lat_add?>, <?php echo $row->long_add?>);
			   var propertyListing = new google.maps.LatLng(<?php echo $row->lat_add?>, <?php echo $row->long_add?>);
			   var citymap = {};
			   citymap['chicago'] = {
			     center: new google.maps.LatLng(<?php echo $row->lat_add?>, <?php echo $row->long_add?>),population: <?php echo $population;?>
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
		   <?php 
		   if(($configClass['show_streetview'] == 1) and ($row->show_address == 1)){
		   ?>
		   <div id="toggle">
			   <input type="button" id="togglebtn" class="btn btn-info btn-small" value="<?php echo JText::_('OS_TOGGLE_STREET_VIEW');?>" onclick="toggleStreetView();" />
		   </div>
		   <?php } ?>
		   <div id="googlemapdiv" style="position:relative;width: 100%; height: 300px"></div>
		</div>
	</div>
</div>
<?php
}
?>

<!-- tabs bottom -->
<div class="detailsBar clearfix">
<div class="row-fluid">
	<div class="span12">
		<div class="shell">
	    	<fieldset><legend><span><?php echo JText::_('OS_INFORMATION')?></span></legend></fieldset>
	    	<div class="row-fluid">
				<div class="span12">
					<div class="tabs clearfix">
					    <div class="tabbable">
					        <ul class="nav nav-tabs">
					        	<?php
								$agent_div = "";
								$walkscore_div = "";
								$gallery_div = "";
								$comment_div = "";
								$video_div = "";
								$energy_div = "";
								$sharing_div = "";
								$request_div =  "";
								$education_div =  "";
								
								if($configClass['show_agent_details'] == 1){
									$agent_div = "active";
								}elseif($configClass['show_gallery_tab'] == 1){
									$gallery_div = "active";
								}elseif(($configClass['show_walkscore'] == 1) and ($configClass['ws_id'] != "")){
									$walkscore_div = "active";
								}elseif($configClass['comment_active_comment'] == 1){
									$comment_div = "active";
								}elseif($row->pro_video != ""){
									$video_div = "active";
								}elseif(($configClass['energy'] == 1) and (($row->energy > 0) or ($row->climate > 0))){
									$energy_div = "active";
								}elseif($configClass['property_mail_to_friends'] == 1){
									$sharing_div = "active";
								}elseif(($configClass['show_request_more_details'] == 1) and ($show_request == "bottom")){
									$request_div = "active";
								}elseif($configClass['integrate_education'] == 1){
									$education_div = "active";
								}elseif(($configClass['use_property_history'] == 1) and (($row->price_history != "") or ($row->tax != ""))){
									$history_div = "active";
								}


					        	if($configClass['show_agent_details'] == 1){
					        	?>
					            <li class="<?php echo $agent_div?>"><a href="#agenttab" data-toggle="tab"><?php 
								if($row->agenttype == 0){
									echo JText::_('OS_AGENT');
								}else{
									echo JText::_('OS_OWNER');
								}
								?></a></li>
					            <?php
					        	}
								?>
								<?php
								if($configClass['show_gallery_tab'] == 1){
								?>
								<li class="<?php echo $gallery_div?>"><a href="#gallery" data-toggle="tab"><?php echo JText::_('OS_GALLERY');?></a></li>
								<?php
								}
								if($configClass['show_walkscore'] == 1){
									if($configClass['ws_id'] != ""){
								?>
					            	<li class="<?php echo $walkscore_div?>"><a href="#walkscore" data-toggle="tab"><?php echo JText::_('OS_WALK_SCORE');?></a></li>
					            <?php
									}
								}
								$user = JFactory::getUser();
								if($configClass['comment_active_comment'] == 1){
								?>
								<li class="<?php echo $comment_div?>"><a href="#comments" data-toggle="tab"><?php echo JText::_('OS_COMMENTS');?></a></li>
								<?php
								}
								?>
								<?php
								if($row->pro_video != ""){
								?>
								<li class="<?php echo $video_div?>"><a href="#tour" data-toggle="tab"><?php echo JText::_('OS_VIRTUAL_TOUR');?></a></li>
								<?php
								}
								?>
								<?php
								if(($configClass['energy'] == 1) and (($row->energy > 0) or ($row->climate > 0))){
								?>
								<li class="<?php echo $energy_div?>"><a href="#epc" data-toggle="tab"><?php echo JText::_('OS_EPC');?></a></li>
								<?php
								}
								
								if($configClass['property_mail_to_friends'] == 1){
								?>
								<li class="<?php echo $sharing_div?>"><a href="#tellafriend" data-toggle="tab"><?php echo JText::_('OS_SHARING');?></a></li>
								<?php
								}
								
								if(($configClass['show_request_more_details'] == 1) and ($show_request == "bottom")){
								?>
								<li class="<?php echo $request_div?>"><a href="#requestmoredetailsform" data-toggle="tab"><?php echo JText::_('OS_REQUEST_MORE_INFOR');?></a></li>
								<?php
								}
								if($configClass['integrate_education'] == 1){
									?>
									<li class="<?php echo $education_div?>"><a href="#educationtab" data-toggle="tab"><?php echo JText::_('OS_EDUCATION');?></a></li>
									<?php
								}
								?>
								<?php 
								if(($configClass['use_property_history'] == 1) and (($row->price_history != "") or ($row->tax != ""))){
								?>
									<li class="<?php echo $history_div?>">
										<a href="#historytab" data-toggle="tab">
											<?php echo JText::_('OS_HISTORY_TAX');?>
										</a>
									</li>
								<?php 
								}
								?>
					        </ul>            
					    </div>
					    <div class="tab-content">
					        <!-- tab1 -->
					        <?php
					        $agent_div = "";
					        $walkscore_div = "";
					        $gallery_div = "";
					        $comment_div = "";
					        $video_div = "";
					        $energy_div = "";
					        $sharing_div = "";
					        $request_div =  "";
					        $education_div =  "";
					        $history_div = "";
					        
					        if($configClass['show_agent_details'] == 1){
					        	$agent_div = " active";
					        }elseif($configClass['show_gallery_tab'] == 1){
					        	$gallery_div = " active";
					        }elseif(($configClass['show_walkscore'] == 1) and ($configClass['ws_id'] != "")){
					        	$walkscore_div = " active";
					        }elseif($configClass['comment_active_comment'] == 1){
					        	$comment_div = " active";
					        }elseif($row->pro_video != ""){
					        	$video_div = " active";
					        }elseif(($configClass['energy'] == 1) and (($row->energy > 0) or ($row->climate > 0))){
					        	$energy_div = " active";
					        }elseif($configClass['property_mail_to_friends'] == 1){
					        	$sharing_div = " active";
					        }elseif(($configClass['show_request_more_details'] == 1) and ($show_request == "bottom")){
					        	$request_div = " active";
					        }elseif($configClass['integrate_education'] == 1){
					        	$education_div = " active";
					        }elseif(($configClass['use_property_history'] == 1) and (($row->price_history != "") or ($row->tax != ""))){
					        	$history_div = " active";
					        }
					       
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
					        ?>
					        <?php
							if($configClass['show_gallery_tab'] == 1){
							?>
					        <div class="tab-pane<?php echo $gallery_div?>" id="gallery">
					        	<?php
								HelperOspropertyCommon::slimboxGallery($row->id,$photos);
								?>
					        </div>
					        <?php
							}
					        if($configClass['comment_active_comment'] == 1){
					        ?>
					        <div class="tab-pane<?php echo $comment_div?>" id="comments">
								<?php
								echo $row->comments;
								?>
								<?php
								if(($owner == 0) and ($can_add_cmt == 1)){
								?>
								<div class="block_caption" id="comment_form_caption">
									<strong><?php echo JText::_(OS_ADD_COMMENT)?></strong>
								</div>	
								
								<div class="span11">
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
											<div class="clearfix"></div>
											<input id="comment_message_counter" class="counter" type="text" readonly="" size="3" maxlength="3"/>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('OS_SECURITY_CODE');?>
										</label>
										<div class="controls">
											<table>
												<tr>
													<td colspan="2">
													<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span>
													</td>
												</tr>
												<tr>
													<td valign="bottom">
														<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" /> 
													</td>
													<td valign="bottom">
														<input type="text" class="input-mini" id="comment_security_code" name="comment_security_code" maxlength="5" style="width: 50px; margin: 0;"/>
													</td>
												</tr>
											</table>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="span12">
										<input onclick="javascript:submitForm('commentForm')" style="margin: 0; width: 100px;" class="btn btn-warning" type="button" name="finish" value="<?php echo JText::_('OS_SUBMIT')?>" />
										<span id="comment_loading" class="reg_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
									</div>
									
									<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
									<input type="hidden" name="option" value="com_osproperty" />
									<input type="hidden" name="task" value="property_submitcomment" />
									<input type="hidden" name="id" value="<?php echo $row->id?>" />
									<input type="hidden" name="Itemid" value="<?php echo $itemid?>" />
									<input type="hidden" name="require_field" id="require_field" value="comment_author,comment_title,comment_message,comment_security_code" />
									<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_(OS_AUTHOR);?>,<?php echo JText::_(OS_TITLE);?>,<?php echo JText::_(OS_MESSAGE);?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
									<script type="text/javascript">
										var comment_textcounter = new textcounter({
											textarea: 'comment_message',
											min: 0,
											max: <?php echo $configClass['max_character'];?>
										});
										comment_textcounter.init();
									</script>
									</form>
								</div>
								<?php
								}
								?>
							</div>
					        <?php
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
							if($configClass['property_mail_to_friends'] == 1){
								?>
								<div class="tab-pane<?php echo $sharing_div?>" id="tellafriend">
						        	<div class="leadFormWrap">
							   			<form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_submittellfriend&Itemid=<?php echo $itemid?>" name="tellfriend_form" id="tellfriend_form" class="form-horizontal">
							   				<div class="control-group">
											    <label class="control-label"><?php echo JText::_(OS_FRIEND_NAME);?></label>
											    <div class="controls">
											     	<input class="input-large" type="text" id="friend_name" name="friend_name" maxlength="30" placeholder="<?php echo JText::_(OS_FRIEND_NAME);?>"/>
											    </div>
											</div>
											
											<div class="control-group">
											    <label class="control-label"><?php echo JText::_(OS_FRIEND_EMAIL);?></label>
											    <div class="controls">
											     	<input class="input-large" type="text" id="friend_email" name="friend_email" maxlength="30" placeholder="<?php echo JText::_(OS_FRIEND_EMAIL);?>"/>
											    </div>
											</div>
											
											<div class="control-group">
											    <label class="control-label"><?php echo JText::_(OS_YOUR_NAME);?></label>
											    <div class="controls">
											     	<input class="input-large" type="text" id="your_name" name="your_name" maxlength="30" placeholder="<?php echo JText::_(OS_YOUR_NAME);?>"/>
											    </div>
											</div>
											
											<div class="control-group">
											    <label class="control-label"><?php echo JText::_(OS_YOUR_EMAIL);?></label>
											    <div class="controls">
											     	<input type="text" id="your_email" name="your_email" maxlength="30" class="input-large" placeholder="<?php echo JText::_(OS_YOUR_EMAIL);?>"/>
											    </div>
											</div>
											
											<div class="control-group">
											    <label class="control-label"><?php echo JText::_(OS_MESSAGE);?></label>
											    <div class="controls">
											     	<textarea id="message" name="message" rows="3" cols="50" class="input-large"></textarea>
											    </div>
											</div>
											
											<div class="control-group">
											    <label class="control-label"><?php echo JText::_(OS_SECURITY_CODE);?></label>
											    <div class="controls">
											     	<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" /> 
													<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
													<input type="text" class="input-mini" id="sharing_security_code" name="sharing_security_code" maxlength="5" style="width: 50px; margin: 0;" />
											    </div>
											</div>
											
											
											
													
											<div class="clear"></div>		
										
											<input class="btn btn-primary" type="button" name="finish" value="<?php echo JText::_('OS_SEND');?>" onclick="javascript:submitForm('tellfriend_form');"/>
											<span class="reg_loading" id="tf_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />	
											<input type="hidden" name="option" value="com_osproperty" />
											<input type="hidden" name="task" value="property_submittellfriend" />
											<input type="hidden" name="id" value="<?php echo $row->id?>" />
											<input type="hidden" name="Itemid" value="<?php echo $itemid?>" />
											<input type="hidden" name="require_field" id="require_field" value="friend_name,friend_email,sharing_security_code" />
											<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_(OS_FRIEND_NAME);?>,<?php echo JText::_(OS_FRIEND_EMAIL);?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
										</form>
							   		</div>
						        </div>
								<?php
							}
							
							if(($configClass['show_request_more_details'] == 1) and ($show_request == "bottom")){
								?>
								<div class="tab-pane<?php echo $request_div?>" id="requestmoredetailsform">
					        		<div class="leadFormWrap" style="padding-top:15px;">
					                <form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_requestmoredetails&Itemid=<?php echo $itemid?>" name="requestdetails_form" id="requestdetails_form" class="form-horizontal">
					                
					                    <div class="_leadError ajax-error"></div>
					                    <div class="control-group">
											<label class="control-label">
											<?php echo JText::_('OS_SUBJECT');?>
											</label>
											<div class="controls">
							                    <select name='subject' id='subject' class='input-medium' onchange="javascript:updateRequestForm(this.value)">
													<option value='1'><?php echo JText::_('OS_REQUEST_1')?></option>
													<option value='2'><?php echo JText::_('OS_REQUEST_2')?></option>
													<option value='3'><?php echo JText::_('OS_REQUEST_3')?></option>
													<option value='4'><?php echo JText::_('OS_REQUEST_4')?></option>
													<option value='5'><?php echo JText::_('OS_REQUEST_5')?></option>
													<option value='6'><?php echo JText::_('OS_REQUEST_6')?></option>
												</select>
					                    	 </div>
										</div>
										<div class="control-group">
											<label class="control-label">
											<?php echo JText::_('OS_YOUR_NAME');?>
											</label>
											<div class="controls">
					                   			<input class="input-medium" type="text" id="requestyour_name" name="requestyour_name" size="30" maxlength="30"  value="<?php echo $user->name?>" placeholder="<?php echo JText::_('OS_YOUR_NAME')?>"/>
					                   		 </div>
										</div>
										<div class="control-group">
											<label class="control-label">
											<?php echo JText::_('OS_PHONE');?>
											</label>
											<div class="controls">
					                    		<input class="input-medium" type="text" id="your_phone" name="your_phone" maxlength="30" placeholder="<?php echo JText::_('OS_PHONE')?>"/>
					                    	</div>
										</div>
										<div class="control-group">
											<label class="control-label">
											<?php echo JText::_('OS_YOUR_EMAIL');?>
											</label>
											<div class="controls">
					                   			<input class="input-medium" type="text" id="requestyour_email" name="requestyour_email" size="30" maxlength="30"  value="<?php echo $user->email;?>" placeholder="<?php echo JText::_('OS_YOUR_EMAIL')?>"/>
					                   		</div>
										</div>
					                    
					                    <div class="control-group">
											<label class="control-label">
											<?php echo JText::_('OS_MESSAGE');?>
											</label>
											<div class="controls">
					                    		<textarea class="input-medium" id="requestmessage" name="requestmessage" rows="3" cols="60"><?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo ($row->ref != "")? $row->ref.", ":""?><?php echo $row->pro_name?></textarea>
					                    	</div>
										</div>
										<div class="control-group">
											<label class="control-label">
											<?php echo JText::_('OS_SECURITY_CODE');?>
											</label>
											<div class="controls">
							                	<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" />
							                	<div class="clearfix"></div>
												<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
												<input type="text" class="input-mini" id="request_security_code" name="request_security_code" maxlength="5" style="width: 50px; margin: 0;" />
												
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="control-group">
											<input class="btn btn-info" type="button" id="requestbutton" name="requestbutton" value="<?php echo JText::_("OS_REQUEST_BUTTON1")?>" onclick="javascript:submitForm('requestdetails_form');"/>
                                            <input type="hidden" name="csrqt<?php echo intval(date("m",time()))?>" id="csrqt<?php echo intval(date("m",time()))?>" value="<?php echo $row->ResultStr?>" />
											<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
						                </div>
										<input type="hidden" name="option" value="com_osproperty" />
										<input type="hidden" name="task" value="property_requestmoredetails" />
										<input type="hidden" name="id" value="<?php echo $row->id?>" />
										<input type="hidden" name="Itemid" value="<?php echo $itemid;?>" />
										<input type="hidden" name="require_field" id="require_field" value="requestmessage,requestyour_name,requestyour_email,request_security_code" />
										<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_('OS_REQUEST_DETAILS');?>,<?php echo JText::_('OS_YOUR_NAME');?>,<?php echo JText::_('OS_YOUR_EMAIL');?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
					              </form> 
					              <script language="javascript">
									function updateRequestForm(subject){
										var message = document.getElementById('requestmessage');
										var requestbutton = document.getElementById('requestbutton');
										if(subject == 1){
											message.value = "<?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo $row->pro_name?>";
											requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON1')?>";
										}else if(subject == 2){
											message.value = "<?php printf(JText::_('OS_REQUEST_MSG2'),$row->pro_name);?>";
											requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON2')?>";
										}else if(subject == 3){
											message.value = "<?php echo JText::_('OS_REQUEST_MSG3')?> <?php echo $row->pro_name?>";
											requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON3')?>";
										}else if(subject == 4){
											message.value = "<?php echo JText::_('OS_REQUEST_MSG4')?> <?php echo $row->pro_name?>";
											requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON4')?>";
										}else if(subject == 5){
											message.value = "<?php echo JText::_('OS_REQUEST_MSG5')?> <?php echo $row->pro_name?>";
											requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON5')?>";
										}else if(subject == 6){
											message.value = "<?php echo JText::_('OS_REQUEST_MSG6')?> <?php echo $row->pro_name?>";
											requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON6')?>";
										}
									}
								 </script>
					        	</div>
					        </div>
					   		<?php
		            		}
		            		if($configClass['integrate_education'] == 1){
		            		?>
			            		<div class="tab-pane<?php echo $education_div?>" id="educationtab">
						        	<?php
									echo stripslashes($row->education);
									?>
						        </div>
					        <?php
		            		}
		            		if(($configClass['use_property_history'] == 1) and (($row->price_history != "") or ($row->tax != ""))){
		            			?>
		            			<div class="tab-pane<?php echo $history_div?>" id="historytab">
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
		            			<?php 
		            		}
							?>
					    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
?>
<?php
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