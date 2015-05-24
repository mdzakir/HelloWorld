<?php
/*------------------------------------------------------------------------
# cpanel.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class HTML_OspropertyCpanel{
	function cpanelHTML($option,$lists,$countries){
		global $mainframe,$configClass,$langArr,$languages;
		$db = JFactory::getDbo();
		//jimport('joomla.html.pane');
		
		$document = JFactory::getDocument();
		$document->addScript(JURI::root().'components/com_osproperty/js/ajax.js');
		$document->setTitle($configClass['general_bussiness_name']);
		//toolbar
		JToolBarHelper::title(JText::_('OS_CPANEL'),"logo48.png");
		JToolBarHelper::cancel();
		JToolBarHelper::preferences('com_osproperty');
		$options = array(
				    'onActive' => 'function(title, description){
				        description.setStyle("display", "block");
				        title.addClass("open").removeClass("closed");
				    }',
				    'onBackground' => 'function(title, description){
				        description.setStyle("display", "none");
				        title.addClass("closed").removeClass("open");
				    }',
				    'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
				    'useCookie' => true, // this must not be a string. Don't use quotes.
				);
		//html
		if (version_compare(JVERSION, '3.0', 'lt')) {
			$width1 = 50;
			$width2 = 50; 
		}else{
			$width1 = 40;
			$width2 = 60; 
		}
		?>
		<form method="POST" action="index.php" name="adminForm">
		<table  width="100%" class="table table-striped">
			<tr>
				<th>
					<?php echo JText::_('OS_SYSTEM_INFORMATION')?>
				</th>
				<th>
					<?php echo JText::_('OS_CONTROLPANEL')?>
				</th>
			</tr>
			<tr>
				<td width="<?php echo $width1?>%" valign="top">
					<div class="row-fluid">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('OS_SETUP');?></a></li>
						<li><a href="#statistic" data-toggle="tab"><?php echo JText::_('OS_STATISTIC');?></a></li>
						<?php
						if(file_exists(JPATH_ROOT.DS."components".DS."com_osproperty".DS."changelog.txt")){
						?>
						<li><a href="#log" data-toggle="tab"><?php echo JText::_('OS_CHANGELOG');?></a></li>
						<?php
						}
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="general-page">
						<div class="width-100 fltlft">
							<fieldset class="adminform">
								<table  width="100%">
									<tr>
										<td width="100%" style="padding:10px;font-weight:bold;font-size:14px;border-bottom:1px solid #CCC;background-color:#efefef;">
											<?php echo JText::_('OS_SETUP')?>
										</td>
									</tr>
									<tr>
										<td width="100%">
											<?php
											$yesimg = "<img src='".JURI::root()."components/com_osproperty/images/assets/tick.png'>";
											$noimg  = "<img src='".JURI::root()."components/com_osproperty/images/assets/publish_x.png'>";
											if (version_compare(JVERSION, '3.0', 'lt')) {
												//$slider = & JPane::getInstance('Sliders');
												echo JHtml::_('sliders.start', 'slide_pane');
												echo JHtml::_('sliders.panel', JText::_('OS_SETUP'), 'setup');
											}else{
											//echo $slider->startPane('slide_pane');
											//echo $slider->startPanel(JText::_('OS_SETUP'),'setup');
											?>
											<div class="accordion" id="accordion1">
												<div class="accordion-group">
													<div class="accordion-heading">
														<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
															<i class="icon-play"></i><?php echo JText::_('OS_SETUP');?>
														</a>
													</div>
												<div id="collapseOne" class="accordion-body collapse in">
												<div class="accordion-inner">
											<?php
											}
											?>
											<table  width="100%" class="table table-striped">
												<thead>
													<tr>
														<th width="30%">
															<?php echo JText::_('OS_ITEM_INSTALL')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_STATUS')?>
														</th>
														<th width="60%">
															<?php echo JText::_('OS_DESCRIPTION')?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													if(file_exists(JPATH_ROOT.DS."components".DS."com_osproperty".DS."version.txt")){
														
													$fh = fopen(JPATH_ROOT.DS."components".DS."com_osproperty".DS."version.txt","r");
													$version = fread($fh,filesize(JPATH_ROOT.DS."components".DS."com_osproperty".DS."version.txt"));
													@fclose($fh);
													?>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('Current version')?></b>
														</td>
														<td align="center" style="padding:5px;font-weight:bold;">
															<?php
															  //$arr = str_split($configClass['current_version']);
															  //echo implode(".",$arr);
															  echo $version;
															?>
														</td>
														<td align="left" style="padding:5px;">
															Please go to <a href="http://joomdonation.com/joomla-extensions/os-property-joomla-real-estate.html" target="_blank">http://joomdonation.com/components/ossolution-property.html</a> to check the latest version of OS Property
														</TD>
													</tr>
													<?php
													}
													?>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_OSPROPERTY_CRONJOB_PLUGIN')?></b>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['plugin'] == 1){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_SYSTEM_CRONJOB_MUST_BE_INSTALLED');
															?>
														</TD>
													</tr>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_PAYPAL_ID')?></b>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($configClass['general_paypal_account'] != ""){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_PAYPAL_ID_MUST_BE_ENTERED');
															?>
														</TD>
													</tr>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_GD_LIB')?></b>
															<font color='red'>(<?php echo JText::_('OS_REQUIRED')?>)</font>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['gd'] == 1){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_GD_LIB_EXPLAIN');
															?>
														</TD>
													</tr>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_GD_LIB_JPEG')?></b>
															<font color='red'>(<?php echo JText::_('OS_REQUIRED')?>)</font>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['gd_jpg'] == 1){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_GD_LIB_JPEG_EXPLAIN');
															?>
														</TD>
													</tr>
												</tbody>
											</table>
											<?php
											//echo $slider->endPanel();
											//echo $slider->startPanel(JText::_('OS_ITEM_DATA'),'itemdata');
											if (version_compare(JVERSION, '3.0', 'lt')) {
												echo JHtml::_('sliders.panel', JText::_('OS_ITEM_DATA'), 'itemdata');		
											}else{
											?>
											</div>
											</div>
											</div>
											<div class="accordion-group">
												<div class="accordion-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo">
														<i class="icon-play"></i><?php echo JText::_('OS_ITEM_DATA');?>
													</a>
												</div>
											<div id="collapseTwo" class="accordion-body collapse">
											<div class="accordion-inner">
											<?php
											}
											?>
											<table  width="100%" class="table table-striped">
												<thead>
													<tr>
														<th width="30%">
															<?php echo JText::_('OS_ITEM_DATA')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_STATUS')?>
														</th>
														<th width="60%">
															<?php echo JText::_('OS_DESCRIPTION')?>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_PROPERTY')?></b><font color='red'>(<?php echo JText::_('OS_REQUIRED')?>)</font>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['properties'] > 0){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_AT_LEAST_ONE_PROPERTY_MUST_BE_CREATED');
															?>
														</TD>
													</tr>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_CATEGORY')?></b><font color='red'>(<?php echo JText::_('OS_REQUIRED')?>)</font>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['categories'] > 0){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_AT_LEAST_ONE_CATEGORY_MUST_BE_CREATED');
															?>
														</TD>
													</tr>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_PROPERTY_TYPE')?></b>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['type'] > 0){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_YOU_HAVE_NOT_CREATED_ANY_PROPERTY_TYPE');
															?>
														</TD>
													</tr>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_AGENT')?></b><font color='red'>(<?php echo JText::_('OS_REQUIRED')?>)</font>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['agent'] > 0){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_AT_LEAST_ONE_AGENT_MUST_BE_CREATED');
															?>
														</TD>
													</tr>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_AMENITIES')?></b>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['amenities'] > 0){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_YOU_HAVE_NOT_CREATED_ANY_AMENITTY');
															?>
														</TD>
													</tr>
													<tr>
														<td align="left" style="padding:5px;">
															<b><?php echo JText::_('OS_PRICEGROUP')?></b>
														</td>
														<td align="center" style="padding:5px;">
															<?php
															if($lists['pricegroups'] > 0){
																echo $yesimg;
															}else{
																echo $noimg;
															}
															?>
														</td>
														<td align="left" style="padding:5px;">
															<?php
															echo JText::_('OS_YOU_HAVE_NOT_CREATED_ANY_PRICE_GROUP');
															?>
														</TD>
													</tr>
												</tbody>
											</table>
											<?php
											//echo $slider->endPanel();
											
											
											//echo $slider->startPanel(JText::_('OS_LOCATION'),'location');
											if (version_compare(JVERSION, '3.0', 'lt')) {
												echo JHtml::_('sliders.panel', JText::_('OS_LOCATION'), 'location');		
											}else{
											?>
											</div>
											</div>
											</div>
											<div class="accordion-group">
												<div class="accordion-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree">
														<i class="icon-play"></i><?php echo JText::_('OS_LOCATION');?>
													</a>
												</div>
											<div id="collapseThree" class="accordion-body collapse">
											<div class="accordion-inner">
											<?php } ?>
											<div id="location_div">
											
											</div>
											<script language="javascript">
												window.onload = function() {
												   initLocation();
												};
											</script>
											<!-- 
											
											-->
											<?php
											if (version_compare(JVERSION, '3.0', 'lt')) {
												echo JHtml::_('sliders.end');
											}else{
											?>
											</div>
											</div>
											</div>
											</div>
											<?php } ?>
										</td>
									</tr>
								</table>
							</fieldset>
							</div>
						</div>
						<div class="tab-pane" id="statistic">	
							<div class="width-100 fltlft">
								<fieldset class="adminform">
								<table  width="100%">
									<tr>
										<td width="100%" style="padding:10px;font-weight:bold;font-size:14px;border-bottom:1px solid #CCC;background-color:#efefef;">
											<?php echo JText::_('OS_STATISTIC')?>
										</td>
									</tr>
									<tr>
										<td width="100%">
										<?php
										//$slider = & JPane::getInstance('Sliders');
										//echo $slider->startPane('slide_pane1');
										//echo $slider->startPanel(JText::_('OS_CONTENT_STATISTIC'),'contentstatistic');
										echo JHtml::_('sliders.start', 'slide_pane1');
										echo JHtml::_('sliders.panel', JText::_('OS_CONTENT_STATISTIC'), 'contentstatistic');	
										?>
										<table  width="100%" class="table table-striped">
											<thead>
												<tr>
													<th colspan="2">
														<?php echo JText::_('OS_AGENT_ACCOUNT');?>
													</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_TOTAL_ACCOUNT')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['agent_active'] + $lists['agent_unactive'];?>
													</td>
												</tr>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_ACTIVE_ACCOUNT')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['agent_active']?>
													</td>
												</tr>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_UNACTIVE_ACCOUNT')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['agent_unactive']?>
													</td>
												</tr>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_REQUEST_APPROVAL')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['agent_request']?>
													</td>
												</tr>
											</tbody>
											<thead>
												<tr>
													<th colspan="2">
														<?php echo JText::_('OS_LISTING');?>
													</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_TOTAL_PROPERTIES')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['property_approved'] + $lists['property_unapproved']?>
													</td>
												</tr>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_ACTIVE_PROPERTY')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['property_approved']?>
													</td>
												</tr>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_UNACTIVE_PROPERTY')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['property_unapproved']?>
													</td>
												</tr>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_REQUEST_APPROVAL')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['property_request']?>
													</td>
												</tr>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_FEATURED')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['property_featured']?>
													</td>
												</tr>
												<tr>
													<td width="80%" align="left" style="padding:5px;">
														<?php echo JText::_('OS_REQUEST_TO_FEATURED')?>
													</td>
													<td width="20%" align="center" style="padding:5px;">
														<?php echo $lists['property_request_featured']?>
													</td>
												</tr>
											</tbody>
										</table>
										<?php
										//echo $slider->endPanel();
										$row_mostview = $lists['mostviewed'];
										if(count($row_mostview) > 0){
											//echo $slider->startPanel(JText::_('OS_MOST_VIEWED_PROPERTY'),'propertystatistic');
											echo JHtml::_('sliders.panel', JText::_('OS_MOST_VIEWED_PROPERTY'), 'propertystatistic');					
											?>
											<table  width="100%" class="table table-striped">
												<thead>
													<tr>
														<th width="5%">
															ID
														</th>
														<th width="5%">
															<?php echo JText::_('OS_EDIT')?>
														</th>
														<th width="60%">
															<?php echo JText::_('OS_PROPERTY')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_HITS')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_FAVORITES')?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													for($i=0;$i<count($row_mostview);$i++){
														$row = $row_mostview[$i];
														$link = "index.php?option=com_osproperty&task=properties_edit&id=$row->id";
														?>
														<tr>
															<td align="center">
																<?php echo $row->id?>
															</td>
															<td align="center">
																<a href="<?php echo $link?>" target="_blank">
																	<?php echo JText::_('OS_EDIT')?>
																</a>
															</td>
															<td align="left">
																<?php echo $row->pro_name?>
															</td>
															<td align="left">
																<?php echo $row->hits?>
															</td>
															<td align="center">
																<?php
																$db->setQuery("Select count(id) from #__osrs_favorites where pro_id = '$row->id'");
																$count = $db->loadResult();
																echo intval($count);
																?>
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
											<?php
											//echo $slider->endPanel();
										}
										$row_favorites = $lists['mostfavorites'];
										if(count($row_mostview) > 0){
											//echo $slider->startPanel(JText::_('OS_MOST_FAVORITES_PROPERTY'),'propertyfav');
											echo JHtml::_('sliders.panel', JText::_('OS_MOST_FAVORITES_PROPERTY'), 'propertyfav');					
											?>
											<table  width="100%" class="table table-striped">
												<thead>
													<tr>
														<th width="5%">
															ID
														</th>
														<th width="5%">
															<?php echo JText::_('OS_EDIT')?>
														</th>
														<th width="60%">
															<?php echo JText::_('OS_PROPERTY')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_HITS')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_FAVORITES')?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													for($i=0;$i<count($row_favorites);$i++){
														$row = $row_favorites[$i];
														$link = "index.php?option=com_osproperty&task=properties_edit&id=$row->pro_id";
														?>
														<tr>
															<td align="center">
																<?php echo $row->pro_id?>
															</td>
															<td align="center">
																<a href="<?php echo $link?>" target="_blank">
																	<?php echo JText::_('OS_EDIT')?>
																</a>
															</td>
															<td align="left">
																<?php echo $row->pro_name?>
															</td>
															<td align="left">
																<?php echo $row->hits?>
															</td>
															<td align="center">
																<?php
																$db->setQuery("Select count(id) from #__osrs_favorites where pro_id = '$row->pro_id'");
																$count = $db->loadResult();
																echo intval($count);
																?>
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
											<?php
											//echo $slider->endPanel();
										}
										$row_rate= $lists['mostrate'];
										if(count($row_rate) > 0){
											//echo $slider->startPanel(JText::_('OS_MOST_RATED_PROPERTY'),'propertyrate');
											echo JHtml::_('sliders.panel', JText::_('OS_MOST_RATED_PROPERTY'), 'propertyrate');				
											?>
											<table  width="100%" class="table table-striped">
												<thead>
													<tr>
														<th width="5%">
															ID
														</th>
														<th width="5%">
															<?php echo JText::_('OS_EDIT')?>
														</th>
														<th width="60%">
															<?php echo JText::_('OS_PROPERTY')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_HITS')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_FAVORITES')?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													for($i=0;$i<count($row_rate);$i++){
														$row = $row_rate[$i];
														$link = "index.php?option=com_osproperty&task=properties_edit&id=$row->id";
														?>
														<tr>
															<td align="center">
																<?php echo $row->id?>
															</td>
															<td align="center">
																<a href="<?php echo $link?>" target="_blank">
																	<?php echo JText::_('OS_EDIT')?>
																</a>
															</td>
															<td align="left">
																<?php echo $row->pro_name?>
															</td>
															<td align="left">
																<?php echo $row->hits?>
															</td>
															<td align="center">
																<?php
																$db->setQuery("Select count(id) from #__osrs_favorites where pro_id = '$row->id'");
																$count = $db->loadResult();
																echo intval($count);
																?>
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
											<?php
											//echo $slider->endPanel();
										}
										$row_comment= $lists['mostcomments'];
										if(count($row_comment) > 0){
											//echo $slider->startPanel(JText::_('OS_MOST_COMMENTED_PROPERTY'),'propertycomment');
											echo JHtml::_('sliders.panel', JText::_('OS_MOST_COMMENTED_PROPERTY'), 'propertycomment');				
											?>
											<table  width="100%" class="table table-striped">
												<thead>
													<tr>
														<th width="5%">
															ID
														</th>
														<th width="5%">
															<?php echo JText::_('OS_EDIT')?>
														</th>
														<th width="60%">
															<?php echo JText::_('OS_PROPERTY')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_HITS')?>
														</th>
														<th width="10%">
															<?php echo JText::_('OS_FAVORITES')?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													for($i=0;$i<count($row_comment);$i++){
														$row = $row_comment[$i];
														$link = "index.php?option=com_osproperty&task=properties_edit&id=$row->pro_id";
														?>
														<tr>
															<td align="center">
																<?php echo $row->pro_id?>
															</td>
															<td align="center">
																<a href="<?php echo $link?>" target="_blank">
																	<?php echo JText::_('OS_EDIT')?>
																</a>
															</td>
															<td align="left">
																<?php echo $row->pro_name?>
															</td>
															<td align="left">
																<?php echo $row->hits?>
															</td>
															<td align="center">
																<?php
																$db->setQuery("Select count(id) from #__osrs_favorites where pro_id = '$row->pro_id'");
																$count = $db->loadResult();
																echo intval($count);
																?>
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
											<?php
											//echo $slider->endPanel();
										}
										echo JHtml::_('sliders.end');	
										?>
										</td>
									</tr>
								</table>
							</fieldset>
							</div>
						</div>
						<?php
						if(file_exists(JPATH_ROOT.DS."components".DS."com_osproperty".DS."changelog.txt")){
						?>
						<div class="tab-pane" id="log">	
							<div class="width-100 fltlft">
								<fieldset class="adminform">
								<table  width="100%">
									<tr>
										<td width="100%" style="padding:10px;font-weight:bold;font-size:14px;border-bottom:1px solid #CCC;background-color:#efefef;">
											<?php echo JText::_('OS_CHANGELOG')?>
										</td>
									</tr>
									<tr>
										<td width="100%" style="font-size:11px;padding:5px;border-bottom:1px solid #CCC;background-color:#efefef;">
											<div style="width:100%;height:400px;overflow-y:scroll;">
												<?php
												 $file = fopen(JPATH_ROOT.DS."components".DS."com_osproperty".DS."changelog.txt",'r');
										         while(!feof($file)) { 
										             $name = fgets($file);
										             echo $name; 
										             echo "<BR />";
										         }
												?>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<?php
						}
						?>
						</div>
					</div>
				</td>
				<td width="<?php echo $width2?>%" valign="top">
					<table  width="100%">
						<tr>
							<td>
								<div id="cpanel">
									<?php
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=configuration_list', 'setting.png', JText::_('OS_CONFIGURATION'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=categories_list', 'categories.png', JText::_('OS_MANAGE_CATEGORIES'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=type_list', 'type.png', JText::_('OS_MANAGE_PROPERTY_TYPES'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=amenities_list', 'convenience.png', JText::_('OS_MANAGE_CONVENIENCE'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=properties_list', 'property.png', JText::_('OS_MANAGE_PROPERTIES'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=pricegroup_list', 'price.png', JText::_('OS_MANAGE_PRICELIST'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=agent_list', 'users.png', JText::_('OS_MANAGE_AGENTS'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=companies_list', 'company.png', JText::_('OS_MANAGE_COMPANIES'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=fieldgroup_list', 'group.png', JText::_('OS_MANAGE_FIELD_GROUPS'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=extrafield_list', 'fields.png', JText::_('OS_MANAGE_EXTRA_FIELDS'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=state_list', 'state.png', JText::_('OS_MANAGE_STATES'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=city_list', 'city.png', JText::_('OS_MANAGE_CITY'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=email_list', 'email.png', JText::_('OS_MANAGE_EMAIL_FORMS'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=comment_list', 'comment.png', JText::_('OS_MANAGE_COMMENTS'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=properties_backup', 'export.png', JText::_('OS_BACKUP'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=properties_restore', 'restore.png', JText::_('OS_RESTORE'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=theme_list', 'theme.png', JText::_('OS_MANAGE_THEMES'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=translation_list', 'translate.png', JText::_('OS_TRANSLATION_LIST'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=form_default', 'csv.png', JText::_('OS_CSV_FORM'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=csvexport_default', 'csvexport.png', JText::_('OS_EXPORT_CSV'));
                                    OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=xml_default', 'xmlexport.png', JText::_('OS_EXPORT_XML'));
                                    OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=xml_defaultimport', 'xmlimport.png', JText::_('OS_IMPORT_XML'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=transaction_list', 'order.png', JText::_('OS_MANAGE_TRANSACTION'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=properties_prepareinstallsample', 'install.png', JText::_('OS_INSTALLSAMPLEDATA'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&amp;task=properties_sefoptimize', 'icon-48-sef.png', JText::_('OS_OPTIMIZE_SEF_URLS'));
									$translatable = JLanguageMultilang::isEnabled() && count($languages);
									if($translatable){
										OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&amp;task=properties_syncdatabase', 'sync.png', JText::_('OS_SYNC_MULTILINGUAL_DATABASE'));
									}
									if($configClass['enable_report'] == 1){
										$db->setQuery("Select count(id) from #__osrs_report where is_checked = '0'");
										$count_report = $db->loadResult();
										if($count_report > 0){
											OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&amp;task=report_listing', 'notice_new.png', JText::_('OS_USER_REPORT'));
										}else{
											OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&amp;task=report_listing', 'notice.png', JText::_('OS_USER_REPORT'));
										}
									}
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&amp;task=tag_list', 'tag.png', JText::_('OS_MANAGE_TAGS'));
									OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&amp;task=properties_reGeneratePictures', 'image.png', JText::_('OS_REGENERATE_PICTURES'));
									?>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JUri::root();?>" />
		</form>
		<?php
	}
}
?>