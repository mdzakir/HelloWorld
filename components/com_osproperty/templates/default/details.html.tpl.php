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
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_osproperty/templates/".$themename."/style/style.css");
$extrafieldncolumns = $params->get('extrafieldncolumns',3);
?>

<style>
#main ul{
	margin:0px;
}
</style>
<script language="javascript">
function showhideDiv(id){
	var temp1 = document.getElementById('fs_' + id);
	var temp2 = document.getElementById('fsb_' + id);
	if(temp1.style.display == "block"){
		temp1.style.display = "none";
		temp2.innerHTML = "[+]";
	}else{
		temp1.style.display = "block";
		temp2.innerHTML = "[-]";
	}
	
}
</script>
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
	<div class="span12">
		<h1 style="border:0px;">
			<div style="float:left;">
				<?php
				if($row->ref != ""){
					echo $row->ref.", ";
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
		        <?php
			    }
		        ?>
			</div>
		</h1>
	</div>
</div>
<div class="clearfix"></div>
<div class="row-fluid">
	<div class="span5" style="margin-left:0px;">
		<div style="padding-top:10px; display:inline;margin-top:10px;">
			<?php echo $facebook_like;?>
		</div>
	</div>
	<div class="span7" id="content_nav_icons" style="margin-left:0px;vertical-align:top;">
		<div class="pull-right">
		<?php
		if(HelperOspropertyCommon::isAgent()){
			$my_agent_id = HelperOspropertyCommon::getAgentID();
			if($my_agent_id == $row->agent_id){
				$link = JURI::root()."index.php?option=com_osproperty&task=property_edit&id=".$row->id;
				?>
				<span id="compare_3">
					<a href="<?php echo $link?>" title="<?php echo JText::_('OS_EDIT_PROPERTY')?>" class="btn">
						<i class="osicon-edit"></i>
					</a>
				</span>
				<?php
			}
		}
		if(($configClass['show_getdirection'] == 1) and ($row->show_address == 1)){
		?>
		<span id="compare_3">
			<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=direction_map&id=".$row->id)?>" title="<?php echo JText::_('OS_GET_DIRECTIONS')?>" class="btn">
				<img class="png" title="<?php echo JText::_('OS_GET_DIRECTIONS')?>" alt="<?php echo JText::_('OS_GET_DIRECTIONS')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/direction.gif" /></a>
		</span>
		<?php
		}
		if($configClass['show_compare_task'] == 1){
		?>
		<span id="compare_3">
			<?php
			$msg = JText::_(OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_COMPARE_LIST);
			$msg = str_replace("'","\'",$msg);
			?>
			<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST')?>" class="btn">
				<i class="osicon-copy"></i>
			</a>
		</span>
		<?php
		}
		$user = JFactory::getUser();
		if(($configClass['property_save_to_favories'] == 1) and ($user->id > 0)){
		?>
		<span id="favorite_3">
			<?php 
			if($inFav == 0){
	          	$msg = JText::_(OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS);
				$msg = str_replace("'","\'",$msg);
		        ?>
				<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_ADD_TO_FAVORITES')?>" class="btn">
					<i class="osicon-star"></i>
				</a>
			<?php }else{
				$msg = JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS');
				$msg = str_replace("'","\'",$msg);
				?>
				<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_REMOVE_FAVORITES')?>" class="btn">
					<i class="osicon-star"></i>
				</a>
				<?php 
			}?>
		</span>
		<?php
		}
		if($configClass['property_pdf_layout'] == 1){
		?>
			<a href="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_pdf&id=<?php echo $row->id?>" title="<?php echo JText::_('OS_EXPORT_PDF')?>"  rel="nofollow" target="_blank" title="<?php echo JText::_(OS_EXPORT_PDF)?>" class="btn">
			<img alt="<?php echo JText::_(OS_EXPORT_PDF)?>" title="<?php echo JText::_(OS_EXPORT_PDF)?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/tpdf.png" />
			</a>
		<?php
		}
		if($configClass['property_show_print'] == 1){
		?>
			<a target="_blank" href="<?php echo JURI::root()?>index.php?option=com_osproperty&tmpl=component&no_html=1&task=property_print&tmpl=component&id=<?php echo $row->id?>" title="<?php echo JText::_(OS_PRINT_THIS_PAGE)?>" class="btn">
			<i class="osicon-print"></i>
			</a>
		<?php
		}
		?>
		</div>
		
	</div>
</div>
<div class="clearfix"></div>
<div class="row-fluid" style="margin-left:0px;">
	<div class="span12">
		<div class="span8 pull-left" style="margin-left:0px;">
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
		<?php
		if($row->show_address == 1){
		?>
			<strong>
				<?php echo OSPHelper::generateAddress($row);?>
			</strong>
		<?php
		}
		?>
		</div>
		<div class="span4" style="margin-left:0px;">
			<div class="pull-right">
				<?php echo $row->price?>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>

<div class="property-details-main-div">
	<ul class="nav nav-tabs" style="margin-bottom:0px !important;">
		<li class="active"><a href="#tabphoto" id="aphoto" data-toggle="tab"><?php echo JText::_('OS_PHOTO');?></a></li>
		<?php
		if($configClass['goole_use_map'] == 1){
		?>
			<li><a href="#tabgoogle" data-toggle="tab"  id="agooglemap"><?php echo JText::_('OS_MAP');?></a></li>
			<?php 
		    if(($configClass['show_streetview'] == 1) and ($row->show_address == 1)){
		    ?>
			<li><a href="#tabstreet" data-toggle="tab" id="astreetview"><?php echo JText::_('OS_STREET_VIEW');?></a></li>
			<?php
			}
		}
		?>
		<?php
		if($row->pro_video != ""){
			?>
			<li><a href="#tabvideo" data-toggle="tab" id="avideo"><?php echo JText::_('OS_VIDEO');?></a></li>
			<?php
		}
		?>
	</ul>
	 
	<div class="tab-content">
		<div class="tab-pane active" id="tabphoto">
		  <?php
		 // HelperOspropertyCommon::propertyGallery($row,$photos);
		  ?>
		  <script language="javascript">
		  //var heightOfMainPhoto = <?php echo $configClass['images_large_height'];?>;
		  //jQuery('#thumbPhotos').css("height",heightOfMainPhoto);
		  </script>
		  <script type="text/javascript" src="<?php echo JUri::root()?>components/com_osproperty/js/colorbox/jquery.colorbox.js"></script>
		  <link rel="stylesheet" href="<?php echo JUri::root()?>components/com_osproperty/js/colorbox/colorbox.css" type="text/css" media="screen" />
		  <script type="text/javascript">
		  jQuery(document).ready(function(){
			  jQuery(".propertyphotogroup").colorbox({rel:'colorbox'});
		  });
		  </script>
		  <?php
	        $slidertype = 'slidernav';
			$animation = 'slide';
			$slideshow = 'true';
			$slideshowspeed = 5000;
			$arrownav = 'true';
			$controlnav = 'true' ;
			$keyboardnav = 'true';
			$mousewheel = 'false';
			$randomize =  'false';
			$animationloop =  'true';
			$pauseonhover =  'true' ;
			$target = 'self';
			$jquery = 'noconflict';
			
			if ($jquery != 0) {JHTML::script('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');}
			JHTML::script(Juri::root().'components/com_osproperty/templates/'.$themename.'/js/jquery.flexslider.js');
			JHTML::script(Juri::root().'components/com_osproperty/templates/'.$themename.'/js/jquery.mousewheel.js');
			JHTML::stylesheet(Juri::root().'components/com_osproperty/templates/'.$themename.'/style/favslider.css');
			
			if ($jquery == 1 || $jquery == 0) { $noconflict = ''; $varj = '$';}
			
			if ($jquery == "noconflict") {$noconflict = 'jQuery.noConflict();'; $varj = 'jQuery';}
			
			if ($slidertype == "slidernav") {
			
			echo '<style type= text/css>#carousel1 {margin-top: 3px;}</style><script type="text/javascript">
			'.$noconflict.'
			    '.$varj.'(window).load(function(){
			      '.$varj.'(\'#carousel1\').favslider({
			        animation: "slide",
			        controlNav: false,
					directionNav: '.$arrownav.',
					mousewheel: '.$mousewheel.',
			        animationLoop: false,
			        slideshow: false,
			        itemWidth: 120,
			        asNavFor: \'#slider1\'
			      });
			      
			      '.$varj.'(\'#slider1\').favslider({
					animation: "'.$animation.'",
					directionNav: '.$arrownav.',
					mousewheel: '.$mousewheel.',
					slideshow: '.$slideshow.',
					slideshowSpeed: '.$slideshowspeed.',
					randomize: '.$randomize.',
					animationLoop: '.$animationloop.',
					pauseOnHover: '.$pauseonhover.',
			        controlNav: false,
			        sync: "#carousel1",
			        start: function(slider){
			        '.$varj.'(\'body\').removeClass(\'loading\');
			        }
			      });
			    });
			</script>'; } 
          	?>

         <div id="slider1" class="favslider1" style="margin: 0!important;">
		    <ul class="favs">
		    <?php
               for($i=0;$i<count($photos);$i++){
               	if($photos[$i]->image != ""){
               		if(JPATH_ROOT.'/images/osproperty/properties/'.$row->id.'/medium/'.$photos[$i]->image){
               			?>
               			<li><a class="propertyphotogroup" href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/<?php echo $photos[$i]->image?>"><img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/medium/<?php echo $photos[$i]->image?>" alt="<?php echo $photos[$i]->image_desc;?>" title="<?php echo $photos[$i]->image_desc;?>"/></a></li>
               			<?php
               		}else{
               			?>
               			<li><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" alt="<?php echo $photos[$i]->image_desc;?>" title="<?php echo $photos[$i]->image_desc;?>"/></li>
               			<?php
               		}
               	}else{
               		?>
               		<li><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" alt="<?php echo $photos[$i]->image_desc;?>" title="<?php echo $photos[$i]->image_desc;?>"/></li>
               		<?php
               	}
               }
               ?>
		    </ul>
		</div>
		<?php if(count($photos) > 1){?>
		<div id="carousel1" class="favslider1">
		    <ul class="favs">
		    <?php 
		    for($i=0;$i<count($photos);$i++){
               	if($photos[$i]->image != ""){
					if(JPATH_ROOT.'/images/osproperty/properties/'.$row->id.'/thumb/'.$photos[$i]->image){
						?>
						<li <?php if ($i>0) {?>style="margin-left: 3px;width:120px !important;;"<?php }else{ ?>style="width:120px !important;; "<?php } ?>><img class="detailwidth" alt="<?php echo $photos[$i]->image_desc;?>" title="<?php echo $photos[$i]->image_desc;?>" src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/thumb/<?php echo $photos[$i]->image?>" /></li>
						<?php
               		}else{
               			?>
						<li <?php if ($i>0) {?>style="margin-left: 3px;width:120px !important;;"<?php }else{ ?>style="width:120px; !important;"<?php } ?>><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" /></li>
						<?php
               		}
               	}else{
               		?>
               		<li <?php if ($i>0) {?>style="margin-left: 3px;width:120px !important;;"<?php }else{ ?>style="width:120px !important;;"<?php } ?>><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" /></li>
               		<?php
               	}
               }
		    ?>
		    </ul>
		</div>
		<?php } ?>
		</div>
		<div class="tab-pane" id="tabgoogle">
			<div id="map_canvas" style="height: <?php echo $configClass['property_map_height']?>px; width: <?php echo $mapwidth?>px;"></div>
		</div>
		<?php 
	    if(($configClass['show_streetview'] == 1) and ($row->show_address == 1)){
	    ?>
		<div class="tab-pane" id="tabstreet">
			<div id="pano" style="height: <?php echo $configClass['property_map_height']?>px; width: <?php echo $mapwidth?>px;"></div>
		</div>
		<?php
		}
		if($row->pro_video != ""){
		?>
		<div class="tab-pane" id="tabvideo">
			<?php
			echo stripslashes($row->pro_video);
			?>
		</div>
		<?php } ?>
	</div>
</div>
<?php
if(count($middlePlugin) > 0){
	?>
	<div class="clearfix"></div>
	<?php
	for($i=0;$i<count($middlePlugin);$i++){
		echo $middlePlugin[$i];
	}
}
?>
<div class="clearfix"></div>
<div class="row-fluid">
	<div class="span12" style="margin-top:10px;">
		<ul class="nav nav-pills">
			<li class="active">
				<a href="#overviewtab" data-toggle="tab"><?php echo JText::_('OS_OVERVIEW')?></a>
			</li>
			<?php
			if($configClass['show_agent_details'] == 1){
			?>
			<li>
				<a href="#agenttab" data-toggle="tab"><?php echo JText::_('OS_AGENT_INFO')?></a>
			</li>
			<?php
			}
			if(($configClass['show_walkscore'] == 1) and ($configClass['ws_id'] != "")){
			?>
				<li>
					<a href="#walkscoretab" data-toggle="tab"><?php echo JText::_('OS_WALK_SCORE')?></a>
				</li>
			<?php
			}
			/*
			if($configClass['show_gallery_tab'] == 1){
			?>
			<li>
				<a href="#gallerytab" data-toggle="tab"><?php echo JText::_('OS_GALLERY')?></a>
			</li>
			<?php
			}
			*/
			if($configClass['property_mail_to_friends'] == 1){
			?>
			<li>
				<a href="#sharingtab" data-toggle="tab"><?php echo JText::_('OS_SHARING')?></a>
			</li>
			<?php
			}
			if($configClass['show_request_more_details'] == 1){
			?>
			<li>
				<a href="#request_more_details_tab" data-toggle="tab"><?php echo JText::_('OS_REQUEST_MORE_INFOR')?></a>
			</li>
			<?php
			}
			if($configClass['comment_active_comment'] == 1){
			?>
			<li>
				<a href="#reviewtab" data-toggle="tab"><?php echo JText::_('OS_REVIEW')?></a>
			</li>
			<?php
			}
			
			if($configClass['integrate_education'] == 1){
				?>
				<li><a href="#educationtab" data-toggle="tab"><?php echo JText::_('OS_EDUCATION');?></a></li>
				<?php
			}
			?>
		</ul>
		<div class="tab-content">
			<div id="overviewtab" class="tab-pane active">
				<div class="row-fluid" style="margin-left:0px;">
					<div class="span12" style="margin-top:10px;margin-left:0px;">
						<!-- Information -->
						<div class="tabbable tabs-left">
							<ul class="nav nav-tabs" style="margin-right:19px;">
								<li class="active">
									<a href="#summarytab" data-toggle="tab">
										<?php echo JText::_('OS_SUMMARY');?>
									</a>
								</li>
								<li>
									<a href="#featuretab" data-toggle="tab">
										<?php echo JText::_('OS_FEATURES');?>
									</a>
								</li>
								<li>
									<a href="#desctab" data-toggle="tab">
										<?php echo JText::_('OS_DESCRIPTION');?>
									</a>
								</li>
								<?php 
								if(($configClass['use_property_history'] == 1) and (($row->price_history != "") or ($row->tax != ""))){
								?>
									<li>
										<a href="#historytab" data-toggle="tab">
											<?php echo JText::_('OS_HISTORY_TAX');?>
										</a>
									</li>
								<?php 
								}
								?>
							</ul>
							<div class="tab-content">
								<div id="summarytab" class="tab-pane fade active in">
									<div class="row-fluid" style="margin-left:0px;">
										<div class="span6" style="margin-left:0px;">
											
											<?php
											if($row->ref != ""){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php echo JText::_('Ref #')?>: <?php echo $row->ref;?>
													</div>
												</div>
												<?php
											}
											?>
											<?php
											if($row->show_address == 1){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php echo JText::_('OS_LOCATION')?>: <?php echo OSPHelper::generateAddress($row);?>
													</div>
												</div>
												<?php
											}
											?>
											<div class="row-fluid">
												<div class="span12">
													<?php
													echo JText::_('OS_CATEGORY').": ";
													?>
													<?php
													$needs = array();
													$needs[] = "category_listing";
													$needs[] = "lcategory";
													$itemid  = OSPRoute::getItemid($needle);
													$link    = JRoute::_('index.php?option=com_osproperty&task=category_details&id='.$row->category_id.'&Itemid='.$itemid);
													//echo "<a href='$link' title='$row->category_name'>".$row->category_name."</a>";
													echo OSPHelper::getCategoryNamesOfPropertyWithLinks($row->id);
													?>
												</div>
											</div>
											<div class="row-fluid">
												<div class="span12">
													<?php
													echo JText::_('OS_PROPERTY_TYPE').":";
													?>
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
											if(count($tagArr) > 0){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_TAGS').":";
														?>
														<?php
														echo implode(" ",$tagArr);
														?>
													</div>
												</div>
												<?php
											}
											?>
											<div class="row-fluid">
												<div class="span12">
													<?php
													echo JText::_('OS_FEATURED').":";
													?>
													<?php
													if($row->isFeatured == 1){
														echo JText::_('OS_YES');
													}else{
														echo JText::_('OS_NO');
													}
													?>
												</div>
											</div>
											<?php
											if($configClass['listing_show_view'] == 1){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_TOTAL_VIEWING').":";
														?>
														<?php echo $row->hits;?>
													</div>
												</div>
												<?php
											}
											?>
											<?php
											if($configClass['listing_show_rating'] == 1){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_RATE').":";
														?>
														<?php 
														$points = 0;
														if($row->number_votes > 0){
															$points = round($row->total_points/$row->number_votes);
															for($i=1;$i<=$points;$i++){
																?>
																<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star1.jpg" />
																<?php
															}
														}
														for($i=$points+1;$i<=5;$i++){
															?>
															<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png" />
															<?php
														}
														echo " <strong>(".$points."/5)</strong>";
														?>
													</div>
												</div>
												<?php
											}
											
											?>
										</div>
										
										<div class="span6" style="margin-left:0px;">
											<?php echo $row->open_hours;?>
											<?php
											if(($configClass['use_bedrooms'] == 1) and ($row->bed_room > 0)){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_BEDS').":";
														?>
														<?php echo $row->bed_room;?>
													</div>
												</div>
												<?php
											}
											?>
											<?php
											if(($configClass['use_bathrooms'] == 1) and ($row->bath_room > 0)){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_BATHS').":";
														?>
														<?php echo OSPHelper::showBath($row->bath_room);?>
													</div>
												</div>
												<?php
											}
											?>
											<?php
											if(($configClass['use_nfloors'] == 1) and ($row->number_of_floors > 0)){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_FLOORS').":";
														?>
														<?php echo $row->number_of_floors;?>
													</div>
												</div>
												<?php
											}
											?>
											<?php
											if(($configClass['use_rooms'] == 1) and ($row->rooms > 0)){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_ROOMS').":";
														?>
														<?php echo $row->rooms;?>
													</div>
												</div>
												<?php
											}
											?>
											<?php
											if(($configClass['use_squarefeet'] == 1) and ($row->square_feet > 0)){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo OSPHelper::showSquareLabels().":";
														?>
														<?php echo OSPHelper::showSquare($row->square_feet);?>
													</div>
												</div>
												<?php
											}
											if(($configClass['use_squarefeet'] == 1) and ($row->lot_size > 0)){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_LOT_SIZE').":";
														?>
														<?php echo OSPHelper::showLotsize($row->lot_size);?> <?php echo OSPHelper::showSquareSymbol();?>
													</div>
												</div>
												<?php
											}
											?>
											<?php
											if($configClass['use_parking'] == 1){
												?>
												<div class="row-fluid">
													<div class="span12">
														<?php
														echo JText::_('OS_PARKING').":";
														?>
														<?php echo $row->parking;?>
													</div>
												</div>
												<?php
											}
											if(($row->pro_pdf != "") or ($row->pro_pdf_file != "")){
												?>
												<div class="row-fluid">
													<div class="span12">
														<strong><?php echo JText::_('OS_PROPERTY_DOCUMENT')?></strong>
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
										</div>
									</div>
									<div class="clearfix"></div>
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
											<div class="row-fluid">
												<strong>
												<?php
												echo JText::_('OS_DPE').":";
												?>
												</strong>
												<BR />
												<?php
												echo HelperOspropertyCommon::drawGraph($row->energy, $row->climate);
												?>
											</div>
											<?php
										}
									}
									?>
								</div>
								<div id="featuretab" class="tab-pane fade">
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
							            ?>
							        </div>
								</div>
								<div id="desctab" class="tab-pane fade">
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
								</div>
								<div id="historytab" class="tab-pane fade">
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
				</div>
			</div>
			<?php
			if($configClass['show_agent_details'] == 1){
			?>
			<div id="agenttab" class="tab-pane">
				<div class="row-fluid">
					<div class="span12" style="margin-top:10px;">
						<?php
						echo $row->agent;
						?>
					</div>
				</div>
			</div>
			<?php
			}
			if(($configClass['show_walkscore'] == 1) and ($configClass['ws_id'] != "")){
			?>
			<div id="walkscoretab" class="tab-pane">
				<div class="row-fluid">
					<div class="span12" style="margin-top:10px;">
						<?php
						echo $row->ws;
						?>
					</div>
				</div>
			</div>
			<?php
			}
			/*
			if($configClass['show_gallery_tab'] == 1){
			?>
			<div id="gallerytab" class="tab-pane">
				<div class="row-fluid">
					<div class="span12" style="margin-top:10px;">
						<?php
						echo $row->gallery;
						?>
					</div>
				</div>
			</div>
			<?php
			}
			*/
			if($configClass['property_mail_to_friends'] == 1){
			?>
			<div id="sharingtab" class="tab-pane">
				<div class="row-fluid">
					<div class="span12" style="margin-top:10px;">
						<div class="blue_middle"><?php echo JText::_(OS_FIELDS_MARKED);?> <span class="red">*</span> <?php echo JText::_(OS_ARE_REQUIRED);?></div>
						<form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_submittellfriend&Itemid=<?php echo $itemid?>" name="tellfriend_form" id="tellfriend_form">
						<table class="sTable">
						<tr>
							<td style="width: 20%;">
								<span class="grey_small"><?php echo JText::_(OS_FRIEND_NAME);?> <span class="red">*</span></span>
							</td>
							<td>
								<input class="input-large" type="text" id="friend_name" name="friend_name" maxlength="30" />
							</td>
					
						</tr>
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_(OS_FRIEND_EMAIL);?> <span class="red">*</span></span>
							</td>
							<td>
								<input class="input-large" type="text" id="friend_email" name="friend_email" maxlength="30"/>
							</td>
						</tr>
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_(OS_YOUR_NAME);?></span>
							</td>
							<td>
								<input class="input-large" type="text" id="your_name" name="your_name" maxlength="30" />
							</td>
					
						</tr>
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_(OS_YOUR_EMAIL);?></span>
							</td>
							<td>
								<input type="text" id="your_email" name="your_email" maxlength="30" class="input-large" />
							</td>
						</tr>
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_(OS_MESSAGE);?></span>
							</td>
							<td>
								<textarea id="message" name="message" rows="3" cols="50" class="input-large"></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_(OS_SECURITY_CODE)?> <span class="red">*</span></span>
							</td>
							<td>
								<table>
									<tr>
										<td valign="bottom">
											<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" /> 
										</td>
										<td valign="bottom">
											<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
											<input type="text" class="input-mini" id="sharing_security_code" name="sharing_security_code" maxlength="5" style="width: 50px; margin: 0;" />
										</td>
									</tr>
								</table>
								<div class="clear"></div>		
							</td>
						</tr>
						
						<tr>
							<td colspan="2" style="padding-top:10px;">
								<input class="btn btn-primary" type="button" name="finish" value="<?php echo JText::_('OS_SEND');?>" onclick="javascript:submitForm('tellfriend_form');"/>
								<span class="reg_loading" id="tf_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</td>
						</tr>
						</table>
						<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
						<input type="hidden" name="option" value="com_osproperty" />
						<input type="hidden" name="task" value="property_submittellfriend" />
						<input type="hidden" name="id" value="<?php echo $row->id?>" />
						<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
						<input type="hidden" name="require_field" id="require_field" value="friend_name,friend_email,sharing_security_code" />
						<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_(OS_FRIEND_NAME);?>,<?php echo JText::_(OS_FRIEND_EMAIL);?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
						</form>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			
			<div id="request_more_details_tab" class="tab-pane">
				<div class="row-fluid">
					<div class="span12" style="margin-top:10px;">
						<div class="blue_middle"><?php echo JText::_(OS_FIELDS_MARKED);?> <span class="red">*</span> <?php echo JText::_(OS_ARE_REQUIRED);?></div>
						<form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_requestmoredetails&Itemid=<?php echo $itemid?>" name="requestdetails_form" id="requestdetails_form">
						<table class="sTable">
						<tr>
							<td style="width: 20%;">
								<span class="grey_small"><?php echo JText::_('OS_SUBJECT');?> <span class="red">*</span></span>
							</td>
							<td>
								<select name='subject' id='subject' class='input-large' onchange="javascript:updateRequestForm(this.value)">
									<option value='1'><?php echo JText::_('OS_REQUEST_1')?></option>
									<option value='2'><?php echo JText::_('OS_REQUEST_2')?></option>
									<option value='3'><?php echo JText::_('OS_REQUEST_3')?></option>
									<option value='4'><?php echo JText::_('OS_REQUEST_4')?></option>
									<option value='5'><?php echo JText::_('OS_REQUEST_5')?></option>
									<option value='6'><?php echo JText::_('OS_REQUEST_6')?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td valign="top">
								<span class="grey_small"><?php echo JText::_(OS_MESSAGE);?> <span class="red">*</span></span> 
							</td>
							<td>
								<textarea class="input-large" id="requestmessage" name="requestmessage" rows="3" cols="60"><?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo ($row->ref != "")? $row->ref.", ":""?><?php echo $row->pro_name?></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_(OS_YOUR_NAME);?> <span class="red">*</span></span>
							</td>
							<td>
								<input class="input-large" type="text" id="requestyour_name" name="requestyour_name" size="30" maxlength="30"  value="<?php echo $user->name?>" />
							</td>
					
						</tr>
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_(OS_YOUR_EMAIL);?> <span class="red">*</span></span>
							</td>
							<td>
								<input class="input-large" type="text" id="requestyour_email" name="requestyour_email" size="30" maxlength="30"  value="<?php echo $user->email;?>"/>
							</td>
						</tr>
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_('OS_PHONE');?></span>
							</td>
							<td>
								<input class="input-large" type="text" id="your_phone" name="your_phone" maxlength="30" />
							</td>
						</tr>
						
						<tr>
							<td>
								<span class="grey_small"><?php echo JText::_(OS_SECURITY_CODE)?> <span class="red">*</span></span>
							</td>
							<td>
								
								<table>
									<tr>
										<td valign="bottom">
											<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" /> 
										</td>
										<td valign="bottom">
											<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
											<input type="text" class="input-mini" id="request_security_code" name="request_security_code" maxlength="5" style="width: 50px; margin: 0;" />
										</td>
									</tr>
								</table>
						
								<div class="clear"></div>		
							</td>
						</tr>
					
						<tr>
							<td style="padding-top:10px;" colspan="2">
								<input class="btn btn-info" type="button" id="requestbutton" name="requestbutton" value="<?php echo JText::_("OS_REQUEST_BUTTON1")?>" onclick="javascript:submitForm('requestdetails_form');"/>
								<span class="reg_loading" id="tf_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</td>
						</tr>
						</table>
                        <input type="hidden" name="csrqt<?php echo intval(date("m",time()))?>" id="csrqt<?php echo intval(date("m",time()))?>" value="<?php echo $row->ResultStr?>" />
						<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
						<input type="hidden" name="option" value="com_osproperty" />
						<input type="hidden" name="task" value="property_requestmoredetails" />
						<input type="hidden" name="id" value="<?php echo $row->id?>" />
						<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
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
			</div>
			<?php
			}
			if($configClass['comment_active_comment'] == 1){
			?>
			<div id="reviewtab" class="tab-pane">
				<div class="row-fluid">
					<div class="span12" style="margin-top:10px;">
						<?php
						echo $row->comments;
						?>
						<?php
						if(($owner == 0) and ($can_add_cmt == 1)){
						?>
						<div class="block_caption" id="comment_form_caption">
							<strong><?php echo JText::_(OS_ADD_COMMENT)?></strong>
						</div>	
						<div class="blue_middle"><?php echo JText::_(OS_FIELDS_MARKED);?> <span class="red">*</span> <?php echo JText::_(OS_ARE_REQUIRED);?></div>
						<form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_submitcomment&Itemid=<?php echo $itemid;?>" name="commentForm" id="commentForm">
						<table class="sTable">
							<?php
							if($configClass['show_rating'] == 1){
							?>
							<tr>
								<td style="width: 20%;">
									<span class="grey_small"><?php echo JText::_(OS_RATING);?>
								</td>
								<td style="font-weight:bold;font-size:11px;">
									<i>Worst
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
									&nbsp;&nbsp;Best</i>
								</td>
							</tr>
							<?php
							}
							?>
							<tr>
								<td style="width: 20%;">
									<span class="grey_small"><?php echo JText::_(OS_AUTHOR);?> <span class="red">*</span></span>
								</td>
								<td>
									<input class="input-large" type="text" id="comment_author" name="comment_author" maxlength="30" />
								</td>
							</tr>
							<tr>
								<td>
									<span class="grey_small"><?php echo JText::_(OS_TITLE);?> <span class="red">*</span></span>
								</td>
								<td>
									<input class="input-large" type="text" id="comment_title" name="comment_title" size="40"  />
								</td>
							</tr>
							<tr>
								<td>
									<span class="grey_small"><?php echo JText::_(OS_MESSAGE);?> <span class="red">*</span></span>
								</td>
								<td>
									<textarea id="comment_message" name="comment_message" rows="3" cols="50" class="input-large"></textarea>
								</td>
							</tr>
							<tr>
								<td>
								</td>
								<td align="left" class="grey_small">
									<input id="comment_message_counter" class="counter" type="text" readonly="" size="3" maxlength="3"/>
									<span style="float:left;"><?php echo JText::_(OS_CHARACTERS_LEFT)?></span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="grey_small"><?php echo JText::_(OS_SECURITY_CODE)?> <span class="red">*</span></span>
								</td>
								<td>
									<table>
										<tr>
											<td valign="bottom">
												<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" /> 
												<!--<img src="<?php echo JURI::root()?>index.php?option=com_ospropery&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>"> -->
											</td>
											<td valign="bottom">
												<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
												<input type="text" class="input-mini" id="comment_security_code" name="comment_security_code" maxlength="5" style="width: 50px; margin: 0;"/>
											</td>
										</tr>
									</table>
									<div class="clear"></div>		
								</td>
							</tr>
							<tr>
								<td style="padding-top:10px;" colspan="2">
									<input onclick="javascript:submitForm('commentForm')" style="margin: 0; width: 100px;" class="btn btn-warning" type="button" name="finish" value="<?php echo JText::_(OS_ADD)?>" />
									<span id="comment_loading" class="reg_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>	
							</tr>
						</table>
						<?php
						}
						?>
						<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
						<input type="hidden" name="option" value="com_osproperty" />
						<input type="hidden" name="task" value="property_submitcomment" />
						<input type="hidden" name="id" value="<?php echo $row->id?>" />
						<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
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
						<br />
						</form>
					</div>
				</div>
			</div>
			<?php
			}
			if($configClass['integrate_education'] == 1){
    		?>
        		<div class="tab-pane" id="educationtab">
		        	<?php
					echo stripslashes($row->education);
					?>
		        </div>
	        <?php
    		}
			?>
		</div>
	</div>
</div>
<?php
if(file_exists(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."oscalendar.php")){
	if(($configClass['integrate_oscalendar'] == 1) and (in_array($row->pro_type,explode("|",$configClass['show_date_search_in'])))){
		require_once(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."classes".DS."default.php");
		require_once(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."classes".DS."default.html.php");
		$otherlanguage =& JFactory::getLanguage();
		$otherlanguage->load( 'com_oscalendar', JPATH_SITE );
		?>
		<div class="detailsBar clearfix">
			<div class="row-fluid">
				<div class="span12">
					<h2><?php echo JText::_('OS_AVAILABILITY')?></h2>
					<?php
					OsCalendarDefault::calendarForm($row->id);
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

if($row->relate != ""){
	echo $row->relate;
}

?>

<div class="jwts_clr"></div><br />
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