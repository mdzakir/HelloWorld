<?php
/*------------------------------------------------------------------------
# property.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OspropertyProperties{
	/**
	 * generate photo crop
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 * @param unknown_type $photoIds
	 * @param unknown_type $save
	 */
	function generatePhotoCrop($option,$id,$photoIds,$save,$pro_name){
		global $mainframe,$configClass;
		JToolBarHelper::title(JText::_('OS_CREATE_PHOTO_BY_MANUAL')." [".$pro_name."]");
		JToolBarHelper::apply('properties_savephoto');
		JToolBarHelper::custom('properties_completesaving','forward.png','forward.png',JText::_('OS_SKIP'),false);
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_photos where id in ($photoIds)");
		$photos = $db->loadObjectList();
		jimport('joomla.html.pane');

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
		</script>
		<form method="POST" action="index.php" name="adminForm"  id="adminForm">
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
									?>
									<table width="100%"> 
										<tr>
											<td width="100%" valign="top">
												<div class="row-fluid">
													<ul class="nav nav-tabs">
														<li class="active"><a href="#tab1<?php  echo $i?>" data-toggle="tab"><?php echo JText::_('OS_THUMBNAIL_PHOTO');?></a></li>
														<?php
														if(($medium_height < $origin_height) or ($medium_width < $origin_width)){
														?>
														<li><a href="#tab2<?php  echo $i?>" data-toggle="tab"><?php echo JText::_('OS_MEDIUM_PHOTO');?></a></li>
														<?php
														}
														?>
													</ul>
													<?php
													//echo $pane->startPane( 'pane' );
													//echo $pane->startPanel( JText::_('OS_THUMBNAIL_PHOTO'), 'tab1' );
	
													?>
													<div class="tab-content">	
														<div class="tab-pane active" id="tab1<?php  echo $i?>">
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
																	<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $id?>/thumb/<?php echo $photo->image?>" width="<?php echo $configClass['images_thumbnail_width']?>" height="<?php echo $configClass['images_thumbnail_height']?>" />
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
																	<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $id?>/<?php echo $photo->image?>" id="tb_yui_img_<?php echo $photo_id?>"  width="<?php echo $origin_width?>"  height="<?php echo $origin_height?>" />
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
													?>
													<?php
		
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
														?>
														<div class="tab-pane" id="tab2<?php  echo $i?>">
														<?php
															//echo $pane->startPanel( JText::_('OS_MEDIUM_PHOTO'), 'tab2' );
															?>
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
														echo "</div>";
														?>
													</div>
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
		<input type="hidden" name="option" value="com_osproperty" /> 
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="pid" id="pid" value="<?php echo $id?>" />
		<input type="hidden" name="photoIds" id="photoIds" value="<?php echo $photoIds?>" />
		<input type="hidden" name="save" id="save" value="<?php echo $save?>" />
		</form>
		<?php
	}
	/**
	 * Update location form
	 *
	 * @param unknown_type $option
	 */
	function updateLocationForm($option,$country,$lists){
		global $mainframe,$langArr;
		JToolBarHelper::title(JText::_('OS_UPDATE_LOCATION_DATABASES')." [".$country->country_name."]");
		JToolBarHelper::save('properties_doimportlanguage',JText::_('OS_UPLOAD'));
		JToolBarHelper::cancel('');
		for($i=0;$i<count($langArr);$i++){
			if($country->id == $langArr[$i]->country_id){
				$file_name = $langArr[$i]->file_name;
			}
		}

		?>
		<script language="javascript">
		function check_file(){
			str=document.getElementById('filename').value;
			file_mandatory_name = document.getElementById('file_mandatory_name').value;
			if(str.indexOf(file_mandatory_name) >= 0){
			}else{
				alert('<?php echo JText::_('OS_FILE_UPLOAD_MUST_BE')?> <?php echo $file_name?>');
				document.getElementById('filename').value = '';
				document.getElementById('uploaddiv').innerHTML = document.getElementById('uploaddiv').innerHTML;
			}
		}

		</script>
		
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<table 	  width="100%" class="admintable">
			<tr>
				<td width="100%" style="padding:20px;">
					<table  width="100%" style="border-bottom:1px solid #CCC;border-right:1px solid #CCC;background-color:#FFF;">
						<tr>
							<td width="100%" style="text-align:center;padding:20px;color:red;background-color:pink;">
								<?php
								printf(JText::_('OS_YOU_HAVE_STATES_CITIES'),$country->country_name,$country->nstates,$country->ncities,$country->country_name,$country->country_name);
								?>
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
			<tr>
				<td width="100%" style="padding:20px;">
					
					<table  width="100%" style="border-bottom:1px solid #CCC;border-right:1px solid #CCC;background-color:#FFF;">
						<tr>
							<td width="100%" style="text-align:center;padding:20px;">
								<strong>
									<?php echo JText::_('OS_PLEASE_SELECT_DATABASE_FILE')?> (
									<?php
									echo $file_name;
									?>
									)
								</strong>
								<BR>
								<BR>
								<div id="uploaddiv">
									<input type="file" size="60" name="filename" id="filename" class="inputbox" onchange="javascript:check_file()">
									<BR /><BR />
									<strong>
									<?php echo Jtext::_('OS_SELECT_STATUS');?> <?php echo $lists['state'];?>
									</strong>
								</div>
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
			
		</table>
		<input type="hidden" name="MAX_FILE_SIZE" value="9000000000">
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="0">
		<input type="hidden" name="country_id" id="country_id" value="<?php echo $country->id?>">
		<input type="hidden" name="file_mandatory_name" id="file_mandatory_name" value="<?php echo $file_name?>">
		</form>
		<?php
	}
	/**
	 * List properties
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function listProperties($option,$rows,$pageNav,$lists){
		global $mainframe, $_jversion,$configClass;
		$db = JFactory::getDBO();
		JHtml::_('behavior.multiselect');
		JHTML::_('behavior.modal','a.osmodal');
		echo JHTML::_('behavior.tooltip');
		JToolBarHelper::title(JText::_('OS_MANAGE_PROPERTIES'));
		JToolBarHelper::custom('properties_copy','copy.png','copy.png',JText::_('OS_DUPLICATE'));
		if (version_compare(JVERSION, '3.0', 'lt')) {
			JToolBarHelper::custom('properties_approval','approval.png','approval.png',JText::_('OS_APPROVAL'));
			JToolBarHelper::custom('properties_unapproval','unapproval.png','unapproval.png',JText::_('OS_UNAPPROVAL'));
		}else{
			JToolBarHelper::custom('properties_approval','ok.png','ok.png',JText::_('OS_APPROVAL'));
			JToolBarHelper::custom('properties_unapproval','remove.png','remove.png',JText::_('OS_UNAPPROVAL'));
		}
		JToolBarHelper::addNew('properties_add');
		JToolBarHelper::editList('properties_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'properties_remove');
		JToolBarHelper::publish('properties_publish');
		JToolBarHelper::unpublish('properties_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		$tmpl = JRequest::getVar('tmpl','');
		if($tmpl == "component"){
			$tmpl_url = "&tmpl=component";
		}else{
			$tmpl_url = "";
		}
        if($lists['show_form'] == 1){
            $class = "btn-primary";
            $display = "block";
        }else{
            $class ="";
            $display = "none";
        }
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=properties_list<?php echo $tmpl_url;?>" name="adminForm" id="adminForm">

        <input type="hidden" name="open_search_from" id="open_search_from" value="<?php echo $lists['show_form'];?>" />
		<div class="row-fluid">
            <div class="span12 js-stools-container-bar">
                <div class="btn-wrapper input-append">
                    <input placeholder="<?php echo Jtext::_('OS_SEARCH');?>" type="text" id="keyword" name="keyword" value="<?php echo $mainframe->getUserState('pro_list.filter.keyword');?>" class="input-medium" />
                    <button class="btn hasTooltip" title="" type="submit" data-original-title="<?php echo Jtext::_('OS_SEARCH');?>">
                        <i class="icon-search"></i>
                    </button>
                </div>
                <div class="btn-wrapper hidden-phone">
                    <button type="button" id="filter_search_button" class="btn hasTooltip js-stools-btn-filter <?php echo $class;?>" title="Filter the list items">
                        <?php echo Jtext::_('OS_SEARCH_TOOLS');?> <i class="caret"></i>
                    </button>
                </div>
                <div class="btn-wrapper hidden-phone">
                    <button type="button" id="clear_search_button" class="btn hasTooltip js-stools-btn-clear" title="Clear">
                        <?php echo Jtext::_('OS_CLEAR');?>
                    </button>
                </div>
            </div>
        </div>
        <div class="row-fluid" ID="search_param_div" style="display:<?php echo $display;?>;">
            <div class="span12">
                <div class="js-stools-container-filters hidden-phone clearfix shown">
                    <div class="js-stools-field-filter">
                        <?php echo $lists['agentType']?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['company']?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['agent']?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['category']?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['type']?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['country'];?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['states']?>
                    </div>

                    <?php
                    if($configClass['use_bedrooms'] == 1){
                        ?>
                        <div class="js-stools-field-filter">
                            <?php echo $lists['nbed']?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if($configClass['use_bathrooms'] == 1){
                        ?>
                        <div class="js-stools-field-filter">
                            <?php echo $lists['nbath']?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if($configClass['use_rooms'] == 1){
                        ?>
                        <div class="js-stools-field-filter">
                            <?php echo $lists['nrooms']?>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['state']?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['request'];?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['state_approval'];?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['request_to_approval'];?>
                    </div>
                    <div class="js-stools-field-filter">
                        <?php echo $lists['isfeature'];?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if(count($rows) > 0) {
            ?>

            <table class="adminlist table table-striped">
                <thead>
                <tr>
                    <th width="1%">

                    </th>
                    <th width="2%" style="text-align:center;" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value=""
                               title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                    </th>
                    <th width="2%" class="hidden-phone">
                        <!-- Property image -->
                    </th>
                    <th width="20%">
                        <?php echo JHTML::_('grid.sort', JText::_('OS_PROPERTY_TITLE'), 'a.pro_name', @$lists['order_Dir'], @$lists['order'], 'properties_list'); ?>
                    </th>
                    <th width="7%" class="hidden-phone">
                        <?php echo JHTML::_('grid.sort', JText::_('OS_TYPE'), 'd.type_name', @$lists['order_Dir'], @$lists['order'], 'properties_list'); ?>
                    </th>
                    <th width="5%" class="hidden-phone">
                        <?php echo JHTML::_('grid.sort', JText::_('OS_AGENT') . '/' . JText::_('OS_OWNER'), 'c.name', @$lists['order_Dir'], @$lists['order'], 'properties_list'); ?>
                    </th>
                    <th width="17%" style="text-align:center;" class="hidden-phone">
                        <?php echo JText::_('OS_ADDRESS');?>
                    </th>
                    <?php
                    if ($tmpl != "component") {
                        ?>
                        <th width="8%" style="text-align:center;" class="hidden-phone">
                            <?php echo JHTML::_('grid.sort', JText::_('OS_PRICE'), 'a.price', @$lists['order_Dir'], @$lists['order'], 'properties_list'); ?>
                        </th>
                        <th width="7%" style="text-align:center;" class="hidden-phone">
                            <?php echo JText::_('OS_INFORMATION');?>
                        </th>
                        <th width="10%" style="text-align:center;" class="hidden-phone">
                            <?php echo JText::_('OS_STATUS');?>
                        </th>
                        <th width="5%" style="text-align:center;" class="hidden-phone">
                            <?php echo JText::_('OS_PRINT')?>
                        </th>
                        <?php
                        if (file_exists(JPATH_ROOT . DS . "components" . DS . "com_oscalendar" . DS . "oscalendar.php")) {
                            if ($configClass['integrate_oscalendar'] == 1) {
                                ?>
                                <th width="5%" style="text-align:center;" class="hidden-phone">
                                    <?php echo JText::_('OS_ROOMS');?>
                                </th>
                            <?php
                            }
                        }
                        ?>
                    <?php } ?>
                    <th width="5%" style="text-align:center;" class="hidden-phone">
                        <?php echo JHTML::_('grid.sort', JText::_('ID'), 'a.id', @$lists['order_Dir'], @$lists['order'], 'properties_list'); ?>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td width="100%" colspan="21" style="text-align:center;">
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
                for ($i = 0, $n = count($rows); $i < $n; $i++) {
                    $row = $rows[$i];

                    OSPHelper::createPhotoDirectory($row->id);
                    OSPHelper::movingPhoto($row->id);

                    $checked = JHtml::_('grid.id', $i, $row->id);
                    $link = JRoute::_('index.php?option=com_osproperty&task=properties_edit&cid[]=' . $row->id);
                    $published = JHTML::_('jgrid.published', $row->published, $i, 'properties_');

                    $db->setQuery("Select * from #__osrs_expired where pid = '$row->id'");
                    $expireds = $db->loadObjectList();
                    $expired_time = "";
                    $expired_feature_time = "";
                    if (count($expireds) > 0) {
                        $expired = $expireds[0];
                        $expired_time = $expired->expired_time;
                        $expired_feature_time = $expired->expired_feature_time;
                    } else {
                        OspropertyProperties::setexpired($option, $row->id);
                    }
                    ?>
                    <tr class="<?php echo "row$k"; ?>">
                        <td align="center">
                            <?php echo $pageNav->getRowOffset($i); ?>
                        </td>
                        <td align="center" style="text-align:center;" class="hidden-phone">
                            <?php echo $checked; ?>
                        </td>
                        <td align="center" class="hidden-phone">
                            <?php
                            $db->setQuery("Select * from #__osrs_photos where pro_id = '$row->id' order by ordering limit 1");
                            $photo = $db->loadObjectList();
                            if (count($photo) > 0) {
                                $photo = $photo[0];
                                ?>
                                <a href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id?>/<?php echo $photo->image?>"
                                   class="osmodal">
                                    <?php
                                    OSPHelper::showPropertyPhoto($photo->image, 'thumb', $row->id, 'width:50px;', 'img-rounded', '');
                                    ?>
                                </a>
                            <?php
                            } else {
                                ?>
                                <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png"
                                     style="width:50px;border:1px solid #efefef;padding:2px;" width="50"/>
                            <?php
                            }
                            ?>
                        </td>
                        <!--
					<td align="center" style="text-align:center;">
						<?php echo $row->ref; ?>
					</td>
					-->
                        <td align="left">
                            <?php
                            if ($tmpl == "component"){
                            ?>
                            <a class="pointer"
                               onclick="if (window.parent) window.parent.jSelectUser_pro_id('<?php echo $row->id?>', '<?php echo str_replace("'", "\'", $row->pro_name);?>');">
                                <?php
                                }else{
                                ?>
                                <a href="<?php echo $link?>" title="<?php echo $row->pro_name?>">
                                    <?php } ?>
                                    <?php
                                    if ($row->ref != "") {
                                        echo $row->ref . ", ";
                                    }
                                    $pro_name = $row->pro_name;
                                    $pro_nameArr = explode(" ", $pro_name);
                                    if (count($pro_nameArr) > 8) {
                                        for ($j = 0; $j <= 8; $j++) {
                                            echo $pro_nameArr[$j] . " ";
                                        }
                                        echo "...";
                                    } else {
                                        echo $pro_name;
                                    }
                                    ?>
                                </a>
                                <BR/>
                                <font style="color:gray;font-size:11px;">
                                    (<?php echo JText::_('OS_ALIAS'); ?>: <?php echo $row->pro_alias; ?>)
                                </font>
                                <BR/>
                                <font style="color:#0088CC;font-size:11px;">
                                    <strong><?php echo JText::_('OS_CATEGORY') ?>: </strong>
                                    <?php //echo $row->category_name
                                    $cat_name = OSPHelper::getCategoryNamesOfProperty($row->id);
                                    //echo $row->id;
                                    $cat_name_arr = explode(" ", $cat_name);
                                    if (count($cat_name_arr) > 10) {
                                        $j = 0;
                                        foreach ($cat_name_arr as $cat) {
                                            $j++;
                                            if ($j <= 10) {
                                                echo $cat . " ";
                                            }
                                        }
                                        echo "...";
                                    } else {
                                        echo $cat_name;
                                    }
                                    ?>
                                    <br/>
                                    <?php
                                    echo JText::_('OS_CREATED').": ";
                                    echo JHTML::_('date', $row->created , 'D, jS F Y');
                                    if(($row->modified != "") and ($row->modified != "0000-00-00") and ($row->modified != $row->created)){
                                        echo "<BR />";
                                        echo JText::_('OS_MODIFIED').": ";
                                        echo JHTML::_('date', $row->modified , 'D, jS F Y');
                                    }
                                    ?>
                                </font>
                        </td>
                        <td align="left" class="hidden-phone">
                            <?php echo $row->type_name ?>
                        </td>
                        <td align="left" class="hidden-phone">
                            <?php echo $row->agent_name ?>
                        </td>
                        <td align="left" style="font-size:11px;" class="hidden-phone">
                            <?php echo OSPHelper::generateAddress($row); ?>
                        </td>
                        <?php
                        if ($tmpl != "component") {
                            ?>
                            <td align="center" class="hidden-phone">
                                <?php
                                if ($row->price_call == 0) {
                                    if ($row->price > 0) {
                                        ?>
                                        <?php echo "<font color='red' style='font-size:11px;'>" . OSPHelper::generatePrice($row->curr, $row->price) . "</font>";
                                        if ($row->rent_time != "") {
                                            echo " / " . JText::_($row->rent_time);
                                        }
                                    }
                                } elseif ($row->price_call == 1) {
                                    echo "<font color='green'>" . JText::_('OS_CALL_FOR_PRICE') . "</font>";
                                }
                                ?>
                            </td>
                            <td align="center" style="text-align:left;font-size:11px;" class="hidden-phone">
                                <?php echo JText::_('OS_HITS');?>: <?php echo $row->hits; ?>
                                <br/>
                                <?php echo JText::_('OS_REQUEST_INFO');?>: <?php echo $row->total_request_info; ?>
                                <br/>
                                <?php echo JText::_('OS_ACCESS');?>:
                                <?php
                                switch ($row->access) {
                                    case "0":
                                        echo "<font color='green'>" . JText::_('OS_PUBLIC') . "</font>";
                                        break;
                                    case "1":
                                        echo "<font color='red'>" . JText::_('OS_REGISTERED') . "</font>";
                                        break;
                                    case "2":
                                        echo "<font color='blue'>" . JText::_('OS_SPECIAL') . "</font>";
                                        break;
                                }
                                ?>
                            </td>
                            <td align="center" style="text-align:center;" class="hidden-phone">
                                <div class="btn-group">
                                    <?php echo $published; ?>

                                    <?php
                                    if ($row->isFeatured == 1) {
                                        if ($configClass['general_use_expiration_management'] == 1) {
                                            ?>
                                            <span class="hasTip" title="<?php echo JText::_('OS_FEATURED')?>::<?php echo JText::_('Expired on')?>: <?php echo $expired_feature_time?>">
                                        <?php
                                        }
                                        ?>
                                        <a class="btn btn-micro active hasTooltip"
                                           href="index.php?option=com_osproperty&task=properties_changeType&type=isFeatured&cid[]=<?php echo $row->id?>&v=0&limitstart=<?php echo $pageNav->limitstart?>&limit=<?php echo $pageNav->limit?>"
                                           title="Change featured status" style="color:red;">
                                            <i class="osicon-star"></i>
                                        </a>
                                        <?php
                                        if ($configClass['general_use_expiration_management'] == 1) {
                                            ?>
                                            </span>
                                        <?php
                                        }
                                        ?>
                                    <?php
                                    } else {
                                        ?>
                                        <a class="btn btn-micro active hasTooltip"
                                           href="index.php?option=com_osproperty&task=properties_changeType&type=isFeatured&cid[]=<?php echo $row->id?>&v=1&limitstart=<?php echo $pageNav->limitstart?>&limit=<?php echo $pageNav->limit?>"
                                           title="Change featured status" style="color:black;">
                                            <i class="osicon-star"></i>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->approved == 1) {
                                        if ($configClass['general_use_expiration_management'] == 1) {
                                            ?>
                                            <span class="hasTip" title="<?php echo JText::_('Approval')?>::<?php echo JText::_('Expired on')?>: <?php echo $expired_time?>">
                                        <?php } ?>
                                        <a class="btn btn-micro active hasTooltip"
                                           href="index.php?option=com_osproperty&task=properties_changeType&type=approved&cid[]=<?php echo $row->id?>&v=0&limitstart=<?php echo $pageNav->limitstart?>&limit=<?php echo $pageNav->limit?>"
                                           title="Change approval status">
                                            <img style="width:14px;"
                                                 src="<?php echo JURI::root()?>components/com_osproperty/images/assets/tick.png">
                                        </a>
                                        <?php
                                        if ($configClass['general_use_expiration_management'] == 1) {
                                            ?>
                                            </span>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <a class="btn btn-micro active hasTooltip"
                                           href="index.php?option=com_osproperty&task=properties_changeType&type=approved&cid[]=<?php echo $row->id?>&v=1&limitstart=<?php echo $pageNav->limitstart?>&limit=<?php echo $pageNav->limit?>"
                                           title="Change approval status">
                                            <img style="width:14px;"
                                                 src="<?php echo JURI::root()?>components/com_osproperty/images/assets/publish_x.png">
                                        </a>
                                        <?php
                                        if ($row->request_to_approval == 1) echo JHTML::_('tooltip', JText::_('OS_REQUEST_TO_APPROVAL'));
                                    }
                                    ?>
                                </div>
                            </td>
                            <td style="text-align:center;" class="hidden-phone">
                                <?php $open_print = "window.open ('index.php?option=com_osproperty&tmpl=component&task=properties_print&cid[]=$row->id', 'mywindow','menubar=0,status=0,location=0,status=0,scrollbars=1,resizable=0,toolbar=0,directories=0, width=1000,height=700');";?>
                                <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/print.png"
                                     onclick="<?php echo $open_print;?>" style="cursor: pointer;">
                            </td>
                            <?php
                            if (file_exists(JPATH_ROOT . DS . "components" . DS . "com_oscalendar" . DS . "oscalendar.php")) {
                                if ($configClass['integrate_oscalendar'] == 1) {
                                    $db->setQuery("Select count(id) from #__oscalendar_rooms where pid = '$row->id'");
                                    $count_rooms = $db->loadResult();
                                    ?>
                                    <td class="hidden-phone" align="center" class="data_td"
                                        style="background-color:<?php echo $bgcolor?>;text-align:center;">
                                        <a href="index.php?option=com_oscalendar&task=room_manage&pid=<?php echo $row->id;?>"
                                           target="_blank"
                                           title="<?php echo JText::_('OS_MANAGE_RENTAL_INFORMATION')?>">
                                            <img
                                                src="<?php echo JURI::root()?>components/com_osproperty/images/assets/room.png">
                                        </a>
                                        (<?php echo intval($count_rooms);?>)
                                    </td>
                                <?php
                                }
                            }
                        } ?>
                        <td align="center" style="text-align:center;" class="hidden-phone">
                            <?php echo $row->id ?>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
                </tbody>
            </table>

        <?php
        }else{
            ?>
            <div class="alert alert-no-items"><?php echo Jtext::_('OS_NO_MATCHING_RESULTS');?></div>
            <?php
        }
        ?>
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="properties_list">
		<input type="hidden" name="boxchecked" value="0">
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		</form>
        <script language="javascript">
            jQuery( "#filter_search_button" ).click(function() {
                var open_search_from = jQuery("#open_search_from").val();
                if(open_search_from == 0){
                    jQuery('#search_param_div').slideDown('slow');
                    jQuery("#open_search_from").val("1");
                    jQuery("#filter_search_button").addClass('btn-primary');
                }else{
                    jQuery('#search_param_div').slideUp('slow');
                    jQuery("#open_search_from").val("0");
                    jQuery("#filter_search_button").removeClass('btn-primary');
                }
            });
            jQuery( "#clear_search_button" ).click(function() {
                jQuery("#company_id").val("");
                jQuery("#agent_id").val("");
                jQuery("#pro_type").val("");
                jQuery("#state_id").val("");
                jQuery("#nbed").val("");
                jQuery("#nbath").val("");
                jQuery("#nrooms").val("");
                jQuery("#isfeature").val("");
                jQuery("#request_to_approval").val("");
                jQuery("#state").val("");
                jQuery("#agentType").val("-1");
                var country_id = document.getElementById('country_id');
                if(country_id != null){
                    jQuery("#country_id").val("");
                }
                jQuery("#keyword").val("");
                document.getElementById('adminForm').submit();
            });

        </script>
		<?php
	}


	/**
	 * Edit Properties
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editProperty($option,$row,$lists,$amenities,$amenitylists,$groups,$neighborhoods,$translatable){
		global $mainframe,$configs,$_jversion,$configClass,$languages;
		$db = JFactory::getDBO();
		$document =& JFactory::getDocument();
		JHTML::_('behavior.modal','a.osmodal');
		JHTML::_('behavior.tooltip');
		
		if($row->id > 0){
			$edit = JText::_('OS_EDIT');
		}else{
			$edit = JText::_('OS_ADD');
		}
		JToolBarHelper::title(JText::_('OS_PROPERTY').' ['.$edit.']');
		JToolBarHelper::save('properties_save');
		JToolBarHelper::apply('properties_apply');
		JToolBarHelper::cancel('properties_gotolist');

		//jimport('joomla.html.pane');
		//$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));

		$max_width = $configClass['max_width_size'];
		$max_height = $configClass['max_height_size'];
		?> 
		<style>
		fieldset label, fieldset span.faux-label {
		    clear: right;
		}
		</style>
		<link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/js/tag/css/textext.core.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/js/tag/css/textext.plugin.tags.css" type="text/css" />
		<script src="<?php echo JURI::root()?>components/com_osproperty/js/tag/js/textext.core.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo JURI::root()?>components/com_osproperty/js/tag/js/textext.plugin.tags.js" type="text/javascript" charset="utf-8"></script>

		<script language="javascript">
		function loadStateBackend(country_id,state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
            loadLocationInfoStateCityBackend(country_id,state_id,city_id,'country','state',live_site);
		}
		function loadCityBackend(state_id,city_id){
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
		function check_file(id){
			str=document.getElementById(id).value.toUpperCase();
			var elementspan = document.getElementById(id + 'div');
			//suffix=".JPG";
			blnValid = false;
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
		
		Joomla.submitbutton = function(task) {
			var form = document.adminForm;
			category_name = form.category_name;
			if((task == "properties_save") || (task == "properties_apply")){
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
					if(temp1 == "category_id"){
						if (jQuery('#categoryIds option:selected').length == 0){
							alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY')?>");
							jQuery('#categoryIds').focus();
							cansubmit = 0;
							return false;
						}
					}
					temp2 = document.getElementById(temp1);
					if(temp2 != null){
						if(temp2.value == ""){
							if(temp1 == "state"){
								temp3 = document.getElementById('nstate');
								if(temp3 != null){
									if(temp3.value == ""){
										alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY')?>");
										temp2.focus();
										cansubmit = 0;
										return false;
									}
								}
							}else{
								alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY')?>");
								temp2.focus();
								cansubmit = 0;
								return false;
							}
						}
					}else{
						temp2 = document.getElementsByName(temp1);
						if(temp2.length > 0){
							cansubmit = 0;
							//if(temp2.type == "checkbox"){
								for(var j=0; j < temp2.length; j++) {
					   				if(temp2[j].checked == true){
					   					cansubmit = 1;
					   				}
								}
							//}
							if(cansubmit == 0){
								alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY')?>");
								temp2.focus();
								cansubmit = 0;
								return false;
							}
						}else{
							temp2 = document.getElementsByName(temp1 + "[]");
							if(temp2.length > 0){
								cansubmit = 0;
								//if(temp2.type == "checkbox"){
									for(var j=0; j < temp2.length; j++) {
						   				if(temp2[j].checked == true){
						   					cansubmit = 1;
						   				}
									}
								//}
								
								if(cansubmit == 0){
									alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY')?>");
									temp2.focus();
									cansubmit = 0;
									return false;
								}
							}
						}
					}
				}

				if(cansubmit == 1){
					Joomla.submitform(task);
				}
			}else{
				Joomla.submitform(task);
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
		
		function check_file_type(){
			str=document.getElementById('zip_file').value.toUpperCase();
			suffix=".ZIP";
			if(!(str.indexOf(suffix, str.length - suffix.length) !== -1)){
				alert('<?php echo JText::_('OS_ALLOW_FILE')?>: *.zip');
				document.getElementById('zip_file').value='';
			}
		}
		</script>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
		
		<?php 
		if ($translatable)
		{
		?>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('OS_GENERAL'); ?></a></li>
				<li><a href="#translation-page" data-toggle="tab"><?php echo JText::_('OS_TRANSLATION'); ?></a></li>									
			</ul>		
			<div class="tab-content">
				<div class="tab-pane active" id="general-page">			
		<?php	
		}
		?>
		<table  width="100%">
			<tr>
				<td width="60%" valign="top">
					<table  width="100%">
						<tr>
							<!-- General tab-->
							<td width="100%" valign="top" align="left">
							<div class="col width-100">
								<fieldset class="general">
									<legend><?php echo JText::_( 'OS_GENERAL' ); ?></legend>
									<table  width="100%" class="admintable">
										<?php
										$require_field = "";
										$require_field .= "pro_name,";
										$require_label = "";
										$require_label .= JText::_('OS_PROPERTY_NAME').",";
										?>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_PROPERTY_TITLE')?>
											</td>
											<td>
												<input type="text" name="pro_name" id="pro_name" value="<?php echo htmlspecialchars($row->pro_name);?>" size="50" class="input-large required" /> <span class="required">(*)</span>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_ALIAS')?>
											</td>
											<td>
												<input type="text" name="pro_alias" id="pro_alias" value="<?php echo $row->pro_alias?>" size="50" class="input-large" />
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_('Ref #')?>
											</td>
											<td>
												<input type="text" name="ref" id="ref" value="<?php echo $row->ref?>" class="input-small" />
											</td>
										</tr>
										<?php
										$require_field .= "category_id,";
										$require_label .= JText::_('OS_CATEGORY').",";
										?>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_CATEGORY')?>
											</td>
											<td>
												<?php echo $lists['category']; ?> <span class="required">(*)</span>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_AGENT').'/'.JText::_('OS_OWNER')?>
											</td>
											<td>
												<?php echo OspropertyProperties::getUserInput($row->agent_id);?> 
												<span class="required">(*)</span>
												<?php
												$require_field .= "agent_id,";
												$require_label .= JText::_('OS_AGENT').'/'.JText::_('OS_OWNER').",";
												?>
											</td>
										</tr>
										<?php
										$require_field .= "pro_type,";
										$require_label .= JText::_('OS_PROPERTY_TYPE').",";
										?>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_PROPERTY_TYPE')?>
											</td>
											<td>
												<?php echo $lists['type']; ?>
												<span class="required">(*)</span>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_APPROVED")?>
											</td>
											<td>
												<?php echo $lists['approved']; ?>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_FEATURED")?>
											</td>
											<td>
												<?php echo $lists['featured']; ?>
											</td>
										</tr>
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_("OS_ACCESS")?>
											</td>
											<td>
												<?php echo $lists['access']; ?>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_PUBLISH")?>
											</td>
											<td>
												<?php echo $lists['state']; ?>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_START_PUBLISHING")?>
											</td>
											<td>
												<?php
												if(($row->publish_up == "") or ($row->publish_up == "0000-00-00")){
													$row->publish_up = date("Y-m-d",time());
												}
												?>
												 <?php echo JHTML::_('calendar', $row->publish_up, 'publish_up', 'publish_up', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_END_PUBLISHING")?>
											</td>
											<td>
												<?php
												if(($row->publish_down == "") or ($row->publish_down == "0000-00-00")){
													$row->publish_down = "Never";
												}
												?>
												 <?php echo JHTML::_('calendar', $row->publish_down, 'publish_down', 'publish_down', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
											</td>
										</tr>
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_("OS_PRICE_INFO")?>
											</td>
											<td>
												<div>
													<table width="100%">
														<tr>
															<td class="key">
																<?php echo JText::_("OS_CALL_FOR_PRICE")?>
															</td>
															<td>
																<?php
																echo $lists['price_call'];
																?>
															</td>
														</td>
													</table>
												</div>
												<div class="clearfix"></div>
												<?php
												if($row->price_call == 0){
													$display = "block";
												}else{
													$display = "none";
												}
												?>
												<div id="pricediv" style="display:<?php echo $display;?>;">
													<table width="100%">
														<tr>
															<td class="key">
																<?php echo JText::_("OS_PRICE")?>
															</td>
															<td>
																<input type="text" name="price" id="price" value="<?php echo $row->price?>" size="10" class="input-small" />
															</td>
														</tr>
														<!--
														<tr>
															<td class="key">
																<?php echo JText::_("OS_ORIGINAL_PRICE")?>
															</td>
															<td>
																<input type="text" name="price_original" id="price_original" value="<?php echo $row->price_original?>" class="input-small" />
															</td>
														</tr>
														-->
													</table>
												</div>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_CURRENCY")?>
											</td>
											<td>
												<?php HelperOspropertyCommon::showCurrencySelectList($row->curr);?>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_PRICE_FOR")?>
											</td>
											<td>
												<?php echo $lists['time'];?>
											</td>
										</tr>
									</table>
								</fieldset>
							</div>
							<!-- End General tab-->
							</td>
						</tr>
						<?php
						$sold_property_types = explode("|",$configClass['sold_property_types']);
						if($configClass['use_sold'] == 1){
							
						if(in_array($row->pro_type,$sold_property_types)){
							$display = "block";
						}else{
							$display = "none";
						}
						?>
						<tr id="sold_information" style="display:<?php echo $display;?>;">
							<!-- Other information -->
							<td width="100%">
								<div class="col width-100">
								<fieldset class="general">
									<legend><?php echo JText::_( 'OS_SOLD_STATUS' ); ?></legend>
									<table  width="100%" class="admintable">
										<tr>
											<td class="key">
												<?php echo JText::_('OS_IS_SOLD')?>
											</td>
											<td>
												<?php echo $lists['property_sold'];?>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_SOLD_ON')?>
											</td>
											<td>
												<?php echo JHTML::calendar($row->soldOn,'soldOn','soldOn',"%Y-%m-%d");?>
											</td>
										</tr>
									</table>
								</fieldset>
								</div>	
							</td>
							<!-- End Other information -->
						</tr>
						<?php }?>
						<tr>
							<!-- Address -->
							<td width="100%">
								<div class="col width-100">
								<fieldset class="general">
									<legend><?php echo JText::_( 'OS_ADDRESS' ); ?></legend>
									<table  width="100%" class="admintable">
										<tr>
											<td class="key">
												<?php echo JText::_("OS_ADDRESS")?>
											</td>
											<td>
												<input type="text" name="address" id="address" value="<?php echo htmlspecialchars($row->address);?>" size="50"><span class="required">(*)</span>
												<?php

												$require_field .= "address,";
												$require_label .= JText::_('OS_ADDRESS').",";
												?>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_POSTCODE")?>
											</td>
											<td>
												<input type="text" name="postcode" id="postcode" value="<?php echo $row->postcode?>" size="10">
												<?php
												if($configClass['require_postcode']==1){
													?>
													<span class="required">(*)</span>
													<?php
													$require_field .= "postcode,";
													$require_label .= JText::_('OS_POSTCODE').",";
												}
												?>
											</td>
										</tr>
										
										<!--
										<tr>
											<td class="key">
												<?php echo JText::_("OS_PROVINCE")?>
											</td>
											<td>
												<input type="text" name="province" id="province" value="<?php echo $row->province?>" size="30">
												<?php
												//$require_field .= "province,";
												//$require_label .= JText::_('OS_PROVINCE').",";
												?>
											</td>
										</tr>
										-->
										<?php
										if(HelperOspropertyCommon::checkCountry()){
										?>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_COUNTRY")?>
											</td>
											<td>
												<?php
												echo $lists['country'];
												?><span class="required">(*)</span>
												<?php
												$require_field .= "country,";
												$require_label .= JText::_('OS_COUNTRY').",";
												?>
											</td>
										</tr>
										<?php
										}else{
											echo $lists['country'];
										}
										?>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_STATE")?>
											</td>
											<td>
												<div id="country_state">
												<?php
												echo $lists['states'];
												?>
												
												<?php
												if($configClass['require_state']==1){
													?>
													<span class="required">(*)</span>
													<?php
													$require_field .= "state,";
													$require_label .= JText::_('OS_STATE').",";
												}
												?>
												</div>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_CITY")?>
											</td>
											<td>
												<!--<input type="text" name="city" id="city" value="<?php echo $row->city?>" size="20">-->
												<div id="city_div">
													<?php
													echo $lists['city'];
													?>									
												</div>
												<?php
												if($configClass['require_city']==1){
													?>
													<!--<span class="required">(*)</span>-->
													<?php
													//$require_field .= "city,";
													//$require_label .= JText::_('OS_CITY').",";
												}
												?>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_REGION")?>
											</td>
											<td>
												<input type="text" name="region" id="region" value="<?php echo $row->region?>" size="30">
												
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_("OS_SHOW_ADDRESS")?>
											</td>
											<td>
												<div id="div_states">
												<?php
												echo $lists['show_address'];
												?>
												</div>
											</td>
										</tr>
										<tr>
											<td class="key">
												
												<?php echo JText::_("OS_LATITUDE")?>
											</td>
											<td>
												<input type="text" name="lat_add" id="lat_add" value="<?php echo $row->lat_add?>" size="30"><!--<span class="required">(*)</span> -->
												<?php
												//$require_field .= "lat_add,";
												//$require_label .= JText::_('OS_LATITUDE').",";


												?>
											</td>
										</tr>
										<tr>
											<td class="key">
												
												<?php echo JText::_("OS_LONGTITUDE")?>
											</td>
											<td>
												<input type="text" name="long_add" id="long_add" value="<?php echo $row->long_add?>" size="30">
												<!--<span class="required">(*)</span>-->
												<?php
												//$require_field .= "long_add,";
												//$require_label .= JText::_('OS_LONGTITUDE').",";


												?>
											</td>
										</tr>
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_('Drag and drop the map for coordinates')?>: 
											</td>
											<td>
												<?php
												include(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."googlemap.lib.php");
												if($row->lat_add == ""){
													$row->lat_add = $configClass['goole_default_lat'];
												}
												if($row->long_add == ""){
													$row->long_add = $configClass['goole_default_long'];
												}
												$geocode = array();
												$geocode[0]->lat = $row->lat_add;
												$geocode[0]->long = $row->long_add;
												HelperOspropertyGoogleMap::loadGMapinEditProperty($geocode,"map","lat_add","long_add");
												?>
												<body onload="initialize()">
												<div id="map" style="width: 500px; height: 300px;border:1px solid #CCC;"></div>
												</body>
												<BR>
												<div>
												<b><?php echo JText::_('Enter address to check lattitude and longtitude: ')?></b>
												<BR>
												<input type="text" name="add" id="add" value="" size="20" class="inputbox"><input type="button" class="btn btn-primary" value="<?php echo JText::_("Search")?>" onclick="javascript:showAddress(document.adminForm.add.value);">
												</div>
												<BR>
												<!--<div id="streetview" style="height: 300px;width:300px;"></div> -->
											</td>
										</tr>
									</table>
								</fieldset>
								</div>	
							</td>
							<!-- End Address -->
						</tr>
						<tr>
							<!-- Property information -->
							<td width="100%">
								<div class="col width-100">
								<fieldset class="general">
									<legend><?php echo JText::_( 'OS_PROPERTY_INFORMATION' ); ?></legend>
									<table  width="100%" class="admintable">
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_('OS_VIDEO_EMBED_CODE')?>
											</td>
											<td>
												<textarea name="pro_video" id="pro_video" cols="50" rows="3" class="inputbox"><?php echo stripslashes($row->pro_video);?></textarea>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_DOCUMENT_LINK')?>
											</td>
											<td>
												<input type="text" name="pro_pdf" id="pro_pdf" size="50" class="inputbox" value="<?php echo $row->pro_pdf;?>" />
											</td>
										</tr>
										<tr>
											<td class="key" >
												<?php echo JText::_('OS_UPLOAD_DOCUMENT')?>
											</td>
											<td>
												<?php
												if($row->pro_pdf_file != ""){
													?>
													<a href="<?php echo JURI::root()?>components/com_osproperty/document/<?php echo $row->pro_pdf_file?>" target="_blank" title="<?php echo JText::_('View document')?>"><?php echo $row->pro_pdf_file?></a>
													<BR>
													<input type="checkbox" name="remove_pdf" id="remove_pdf" onclick="javascript:changeValue('remove_pdf')" value="0"> <b>Remove document</b>
													<BR>
													<?php
												}
												?>
												<input type="file" name="pro_pdf_file" id="pro_pdf_file" size="40" class="inputbox" onchange="javascript:checkUploadDocumentFiles('pro_pdf_file')"> (Only allow: *.pdf, *.doc,*.docx)
											</td>
										</tr>
										<?php
										if($configClass['use_rooms']== 1){
										?>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_NUMBER_ROOMS')?>
											</td>
											<td>
												<?php echo $lists['nrooms'];?>
											</td>
										</tr>
										<?php
										}
										?>
										<?php
										if($configClass['use_bathrooms']== 1){
										?>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_NUMBER_BATHROOMS')?>
											</td>
											<td>
												<?php echo $lists['nbath'];?>
											</td>
										</tr>
										<?php
										}
										?>
										<?php
										if($configClass['use_bedrooms']== 1){
										?>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_NUMBER_BEDROOMS')?>
											</td>
											<td>
												<?php echo $lists['nbed'];?>
											</td>
										</tr>
										<?php
										}

                                        if($configClass['use_nfloors']== 1){
                                            ?>
                                            <tr>
                                                <td class="key">
                                                    <?php echo JText::_('OS_NUMBER_OF_FLOORS')?>
                                                </td>
                                                <td>
                                                    <?php echo $lists['nfloors'];?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
										if($configClass['use_parking']== 1){
										?>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_PARKING')?>
											</td>
											<td>
												<input type="text" name="parking" id="parking" size="20" class="inputbox" value="<?php echo $row->parking?>">
											</td>
										</tr>
										<?php
										}
										?>
										<?php
										if($configClass['use_squarefeet']== 1){
										?>
										<tr>
											<td class="key">
												<?php echo OSPHelper::showSquareLabels();?>:
											</td>
											<td>
												<input type="text" name="square_feet" id="square_feet" size="10" class="input-small" value="<?php echo $row->square_feet?>"/>
											</td>
										</tr>
										<tr>
											<td class="key">
												<?php echo JText::_('OS_LOT_SIZE');?>:
											</td>
											<td>
												<input type="text" name="lot_size" id="lot_size" size="10" class="input-small" value="<?php echo $row->lot_size;?>" />
												<?php echo OSPHelper::showSquareSymbol();?>
											</td>
										</tr>
										<?php
										}

										if($configClass['energy'] == 1){
										?>
										<tr>
											<td class="key" >
												<?php echo JText::_('OS_ENERGY')?>
											</td>
											<td>
												<input type="text" class="input-mini" name="energy" id="energy" size="5" value="<?php echo $row->energy;?>"> kWH/m
											</td>
										</tr>
										<tr>
											<td class="key" >
												<?php echo JText::_('OS_CLIMATE')?>
											</td>
											<td>
												<input type="text" class="input-mini" name="climate" id="climate" size="5" value="<?php echo $row->climate;?>"> kg/m
											</td>
										</tr>
										<?php
										}
										?>
									</table>
								</fieldset>
								</div>	
							</td>
							<!-- End Other information -->
						</tr>
						<?php 
						if($configClass['use_open_house'] == 1){
						?>
						<tr>
							<!-- Other information -->
							<td width="100%">
								<div class="col width-100">
								<fieldset class="general">
									<legend><?php echo JText::_( 'OS_PROPERTY_OPEN_HOUSE' ); ?></legend>
									<table  width="100%" class="admintable">
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_('OS_SELECT_OPENING_TIME')?>
											</td>
											<td>
												<table width="100%" id="property_open_table">
													<tr>
														<th>
															<?php echo JText::_('OS_FROM')?>
														</th>
														<th>
															<?php echo JText::_('OS_TO')?>
														</th>
													</tr>
													<?php 
													if(count($lists['open']) > 0){
														$j = 0;
														foreach ($lists['open'] as $cal){
															$j++;
															?>
															<tr>
																<td>
																	<?php echo JHTML::calendar($cal->start_from,'start_from[]','start_from'.$j,"%Y-%m-%d %H:%M:%S");?>
																</td>
																<td>
																	<?php echo JHTML::calendar($cal->end_to,'end_to[]','end_to'.$j,"%Y-%m-%d %H:%M:%S");?>
																</td>
															</tr>
															<?php 
														}
													}
													if($j < 5){
														for($i=$j+1;$i<=5;$i++){
														?>
														<tr id="history_table_tr">
															<td>
																<?php echo JHTML::calendar('','start_from[]','start_from'.$i,"%Y-%m-%d %H:%M:%S");?>
															</td>
															<td>
																<?php echo JHTML::calendar('','end_to[]','end_to'.$i,"%Y-%m-%d %H:%M:%S");?>
															</td>
														</tr>
														<?php 
														}
													}
													?>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>
								</div>	
							</td>
							<!-- End Other information -->
						</tr>
						<?php }?>
						<?php 
						if($configClass['use_property_history'] == 1){
						?>
						<tr>
							<!-- Other information -->
							<td width="100%">
								<div class="col width-100">
								<fieldset class="general">
									<legend><?php echo JText::_( 'OS_PROPERTY_HISTORY_TAX' ); ?></legend>
									<table  width="100%" class="admintable">
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_('OS_PROPERTY_HISTORY')?>
											</td>
											<td>
												<table width="100%" id="property_history_table">
													<tr>
														<th>
															<?php echo JText::_('OS_DATE')?>
														</th>
														<th>
															<?php echo JText::_('OS_EVENT')?>
														</th>
														<th>
															<?php echo JText::_('OS_PRICE')?>
														</th>
														<th>
															<?php echo JText::_('OS_SOURCE')?>
														</th>
														<th>
															&nbsp;
														</th>
													</tr>
													<?php 
													if(count($lists['history']) > 0){
														foreach ($lists['history'] as $his){
														?>
														<tr id="history_table_tr">
															<td>
																<input type="text" name="history_date[]" value="<?php echo $his->date?>" class="input-small" />
															</td>
															<td>
																<input type="text" name="history_event[]" value="<?php echo $his->event?>" class="input-medium" />
															</td>
															<td>
																<input type="text" name="history_price[]" value="<?php echo $his->price?>" class="input-small" />
															</td>
															<td>
																<input type="text" name="history_source[]" value="<?php echo $his->source?>" class="input-medium" />
															</td>
															<td>
																<input type="button" class="btn removehistory" value="<?php echo JText::_('OS_DELETE');?>" />
															</td>
														</tr>
														<?php 
														}
													}
													?>
													<tr id="history_table_tr">
														<td>
															<input type="text" name="history_date[]" value="" class="input-small" />
														</td>
														<td>
															<input type="text" name="history_event[]" value="" class="input-medium" />
														</td>
														<td>
															<input type="text" name="history_price[]" value="" class="input-small" />
														</td>
														<td>
															<input type="text" name="history_source[]" value="" class="input-medium" />
														</td>
														<td>
															<input type="button" class="btn addhistory" value="<?php echo JText::_('OS_ADD');?>" />
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_('OS_PROPERTY_TAX')?>
											</td>
											<td>
												<table width="100%" id="property_tax_table">
													<tr>
														<th>
															<?php echo JText::_('OS_YEAR')?>
														</th>
														<th>
															<?php echo JText::_('OS_TAX')?>
														</th>
														<th>
															<?php echo JText::_('OS_TAX_CHANGE')?>
														</th>
														<th>
															<?php echo JText::_('OS_TAX_ASSESSMENT')?>
														</th>
														<th>
															<?php echo JText::_('OS_TAX_ASSESSMENT_CHANGE')?>
														</th>
														<th>
															&nbsp;
														</th>
													</tr>
													<?php 
													if(count($lists['tax']) > 0){
														foreach ($lists['tax'] as $tax){
														?>
														<tr id="tax_table_tr">
															<td>
																<input type="text" name="tax_year[]" value="<?php echo $tax->tax_year?>" class="input-small" />
															</td>
															<td>
																<input type="text" name="tax_value[]" value="<?php echo $tax->property_tax?>" class="input-small" />
															</td>
															<td>
																<input type="text" name="tax_change[]" value="<?php echo $tax->tax_change?>" class="input-small" />
															</td>
															<td>
																<input type="text" name="tax_assessment[]" value="<?php echo $tax->tax_assessment?>" class="input-small" />
															</td>
															<td>
																<input type="text" name="tax_assessment_change[]" value="<?php echo $tax->tax_assessment_change?>" class="input-small" />
															</td>
															<td>
																<input type="button" class="btn removetax" value="<?php echo JText::_('OS_DELETE');?>" />
															</td>
														</tr>
														<?php 
														}
													}
													?>
													<tr id="tax_table_tr">
														<td>
															<input type="text" name="tax_year[]" value="" class="input-small" />
														</td>
														<td>
															<input type="text" name="tax_value[]" value="" class="input-small" />
														</td>
														<td>
															<input type="text" name="tax_change[]" value="" class="input-small" />
														</td>
														<td>
															<input type="text" name="tax_assessment[]" value="" class="input-small" />
														</td>
														<td>
															<input type="text" name="tax_assessment_change[]" value="" class="input-small" />
														</td>
														<td>
															<input type="button" class="btn addtax" value="<?php echo JText::_('OS_ADD');?>" />
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>
								<script language="javascript">
								jQuery(document).ready(function(){		
									jQuery('.removehistory').live('click',function(){
										jQuery(this).parent().parent().remove();
									});
									
									jQuery('.addhistory').live('click',function(){
										jQuery(this).val('<?php echo JText::_('OS_DELETE');?>');
										jQuery(this).attr('class','btn removehistory');
										var appendTxt = '<tr id="history_table_tr"><td><input type="text" name="history_date[]" value="" class="input-small" /></td><td><input type="text" name="history_event[]" value="" class="input-medium" /></td><td><input type="text" name="history_price[]" value="" class="input-small" /></td><td><input type="text" name="history_source[]" value="" class="input-medium" /></td><td><input type="button" class="btn addhistory" value="<?php echo JText::_('OS_ADD');?>" /></td></tr>';
										jQuery("#property_history_table>tbody>tr:last").after(appendTxt);			
									});  

									jQuery('.removetax').live('click',function(){
										jQuery(this).parent().parent().remove();
									});    

									jQuery('.addtax').live('click',function(){
										jQuery(this).val('<?php echo JText::_('OS_DELETE');?>');
										jQuery(this).attr('class','btn removetax');
										var appendTxt = '<tr id="tax_table_tr"><td><input type="text" name="tax_year[]" value="" class="input-small" /></td><td><input type="text" name="tax_value[]" value="" class="input-small" /></td><td><input type="text" name="tax_change[]" value="" class="input-small" /></td><td><input type="text" name="tax_assessment[]" value="" class="input-small" /></td><td><input type="text" name="tax_assessment_change[]" value="" class="input-small" /></td><td><input type="button" class="btn addtax" value="<?php echo JText::_('OS_ADD');?>" /></td></tr>';
										jQuery("#property_tax_table>tbody>tr:last").after(appendTxt);			
									}); 
								});
								</script>
								</div>	
							</td>
							<!-- End Other information -->
						</tr>
						<?php } ?>
						<tr>
							<!-- Other information -->
							<td width="100%">
								<div class="col width-100">
								<fieldset class="general">
									<legend><?php echo JText::_( 'OS_TAGS' ); ?></legend>
									<table  width="100%" class="admintable">
										<tr>
											<td>
												<table width="100%" id="property_tag_table">
													<tr>
														<th>
															<?php echo JText::_('OS_KEYWORD')?>
														</th>
														<?php 
														if($translatable){
															foreach ($languages as $language)
															{												
																$sef = $language->sef;
																?>
																<th>
																	<?php echo JText::_('OS_KEYWORD')?>
																	<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" />
																</th>
																<?php 
															}
														}
														?>
														<th>
															&nbsp;
														</th>
													</tr>
													<?php 
													if(count($lists['tags']) > 0){
														foreach ($lists['tags'] as $tag){
														?>
														<tr id="tag_table_tr">
															<td>
																<input type="text" name="keyword[]" value="<?php echo $tag->keyword?>" class="input-small" />
															</td>
															<?php 
															if($translatable){
																foreach ($languages as $language)
																{												
																	$sef = $language->sef;
																	?>
																	<td>
																		<input type="text" name="keyword_<?php echo $sef;?>[]" value="<?php echo $tag->{'keyword_'.$sef}?>" class="input-small" />
																	</td>
																	<?php 
																}
															}
															?>
															<td>
																<input type="button" class="btn removetag" value="<?php echo JText::_('OS_DELETE');?>" />
															</td>
														</tr>
														<?php 
														}
													}
													?>
													<tr id="tag_table_tr">
														<td>
															<input type="text" name="keyword[]" value="" class="input-small" />
														</td>
														<?php 
														if($translatable){
															foreach ($languages as $language)
															{												
																$sef = $language->sef;
																?>
																<td>
																	<input type="text" name="keyword_<?php echo $sef;?>[]" value="" class="input-small" />
																</td>
																<?php 
															}
														}
														?>
														<td>
															<input type="button" class="btn addtag" value="<?php echo JText::_('OS_ADD');?>" />
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>
								<script language="javascript">
								jQuery(document).ready(function(){		
									jQuery('.removetag').live('click',function(){
										jQuery(this).parent().parent().remove();
									});
									
									jQuery('.addtag').live('click',function(){
										jQuery(this).val('<?php echo JText::_('OS_DELETE');?>');
										jQuery(this).attr('class','btn removetag');
										<?php 
										$value  = '<tr id="tag_table_tr"><td><input type="text" name="keyword[]" value="" class="input-small" /></td>';
										//$value .= '<td><input type="text" name="history_event[]" value="" class="input-medium" /></td>';
										if($translatable){
											foreach ($languages as $language)
											{												
												$sef = $language->sef;
												$value .= '<td><input type="text" name="keyword_'.$sef.'[]" value="" class="input-small" /></td>';
											}
										}
										$value .= '<td><input type="button" class="btn addtag" value="'.JText::_('OS_ADD').'" /></td></tr>';
										?>
										var appendTxt = '<?php echo $value;?>';
										jQuery("#property_tag_table>tbody>tr:last").after(appendTxt);			
									}); 
								});
								</script>
								</div>	
							</td>
							<!-- End Other information -->
						</tr>
						<tr>
							<!-- Other information -->
							<td width="100%">
								<div class="col width-100">
								<fieldset class="general">
									<legend><?php echo JText::_( 'OS_OTHER_INFORMATION' ); ?></legend>
									<table  width="100%" class="admintable">
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_('OS_SMALL_DESCRIPTION')?>
											</td>
											<td>
												<textarea name="pro_small_desc" id="pro_small_desc" cols="50" rows="5" class="input-large"><?php echo stripslashes($row->pro_small_desc)?></textarea><span class="required">(*)</span>
												<?php
												$require_field .= "pro_small_desc,";
												$require_label .= JText::_('OS_SMALL_DESCRIPTION').",";
												?>
											</td>
										</tr>
										<?php
										$editor = &JFactory::getEditor();
										?>
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_('OS_FULL_DESCRIPTION')?>
											</td>
											<td>
												<?php
												echo $editor->display( 'pro_full_desc',  stripslashes($row->pro_full_desc) , '95%', '250', '75', '20' ,false) ;
												?>
											</td>
										</tr>
										<tr>
											<td class="key" valign="top">
												<?php echo JText::_('OS_AGENT_NOTE')?>
											</td>
											<td>
												<textarea name="note" id="note" cols="50" rows="5" class="inputbox"><?php echo $row->note?></textarea>
											</td>
										</tr>
										
									</table>
								</fieldset>
								</div>	
							</td>
							<!-- End Other information -->
						</tr>
					</table>
				</td>
				<td width="40%" valign="top">
					<table  width="100%">
						<tr>
							<td width="100%">
								<div class="col width-100">
									<fieldset class="general">
										<legend><?php echo JText::_( 'OS_PROPERTY_INFORMATION' ); ?></legend>
										<table  width="100%" style="padding-top:10px;font-size:12px;">
											<tr>
												<td width="30%" style="padding:5px;text-align:left;padding-left:20px;font-weight:bold;">
													<?php echo JText::_('OS_PROPERTY_ID')?>:
												</td>
												<td width="70%" style="padding:5px;">
													<?php
													if($row->id){
														echo $row->id;
													}else{
														echo JText::_('OS_NOT_SET');
													}
													?>
												</td>
											</tr>
											<tr>
												<td width="30%" style="padding:5px;text-align:left;padding-left:20px;font-weight:bold;">
													<?php echo JText::_('OS_REQUEST_INFO')?>:
												</td>
												<td width="70%" style="padding:5px;">
													<?php
													if($row->total_request_info){
														echo $row->total_request_info;
													}else{
														echo JText::_('OS_NOT_SET');
													}
													?>
												</td>
											</tr>
											<tr>
												<td width="30%" style="padding:5px;text-align:left;padding-left:20px;font-weight:bold;">
													<?php echo JText::_('OS_HITS')?>:
												</td>
												<td width="70%" style="padding:5px;">
													<?php
													if($row->id){
														echo $row->hits;
													}else{
														echo "0";
													}
													?>
												</td>
											</tr>
											<tr>
												<td width="30%" style="padding:5px;text-align:left;padding-left:20px;font-weight:bold;">
													<?php echo JText::_('OS_RATING')?>:
												</td>
												<td width="70%" style="padding:5px;">
													<?php
													if($row->number_votes > 0){
														$points = round($row->total_points/$row->number_votes);
														?>
														<table  width="100%">
															<tr>
																<td width="40%">
																	<?php
																	for($i=1;$i<=$points;$i++){
																		?>
																		<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star1.png">
																		<?php
																	}
																	for($i=$points+1;$i<=5;$i++){
																		?>
																		<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png">
																		<?php
																	}
																	?>
																</td>
																<td align="left" width="60%">
																	<?php
																	echo " <b>(".$points."/5)</b>";
																	?>
																</td>
															</tr>
														</table>
														<?php


													}else{
														for($i=1;$i<=5;$i++){
															?>
															<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png">
															<?php
														}
													}
													?>
												</td>
											</tr>
											<tr>
												<td width="30%" style="padding:5px;text-align:left;padding-left:20px;font-weight:bold;">
													<?php echo JText::_('OS_STATUS')?>:
												</td>
												<td width="70%" style="padding:5px;">
													<?php
													if($row->id){
														if($row->published == 0){
															echo JText::_('OS_UNPUBLISHED');
														}else{
															echo JText::_('OS_PUBLISHED');
														}
													}else{
														echo JText::_('OS_PUBLISHED');
													}
													?>
												</td>
											</tr>
											<?php 
											if($row->id > 0){
											?>
											<tr>
												<td width="30%" style="padding:5px;text-align:left;padding-left:20px;font-weight:bold;">
													<?php echo JText::_('OS_CREATED')?>:
												</td>
												<td width="70%" style="padding:5px;">
													<?php
													echo date("D, jS F Y",strtotime($row->created));
													?>
												</td>
											</tr>
											<?php } ?>
										</table>
									</fieldset>
								</div>
							</td>
						</tr>
						<tr>
							<td width="100%" valign="top">
								<div class="col width-100">
								<?php
								//echo $pane->startPane("menu-pane2");
								echo JHtml::_('sliders.start', 'menu-pane2');
								if($configClass['show_metatag'] ==1){
									echo JHtml::_('sliders.panel', JText::_('OS_META_INFORMATION'), 'metainfo');
									?>
									<?php 
									if ($translatable)
									{
									?>
										<ul class="nav nav-tabs">
											<li class="active"><a href="#meta-general-page" data-toggle="tab"><?php echo JText::_('OS_GENERAL'); ?></a></li>
											<li><a href="#meta-translation-page" data-toggle="tab"><?php echo JText::_('OS_TRANSLATION'); ?></a></li>									
										</ul>		
										<div class="tab-content">
											<div class="tab-pane active" id="meta-general-page">			
									<?php	
									}
									?>
                                        <table  width="100%" class="admintable" style="padding:5px;">
                                            <tr>
                                                <td class="key" valign="top">
                                                    <?php echo JText::_('OS_META_DESCRIPTION')?>
                                                </td>
                                                <td>
                                                    <textarea name="metadesc" id="metadesc" cols="40" rows="4"><?php echo $row->metadesc?></textarea>
                                                </td>
                                            </tr>
                                        </table>
									<?php 
									if ($translatable)
									{
										?>
										</div>
										<div class="tab-pane" id="meta-translation-page">
											<ul class="nav nav-tabs">
												<?php
													$i = 0;
													foreach ($languages as $language) {						
														$sef = $language->sef;
														?>
														<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#meta-translation-page-<?php echo $sef; ?>" data-toggle="tab"><?php echo $language->title; ?>
															<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" /></a></li>
														<?php
														$i++;	
													}
												?>			
											</ul>		
											<div class="tab-content">			
												<?php	
												$i = 0;
												foreach ($languages as $language)
												{												
													$sef = $language->sef;
												?>
												<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="meta-translation-page-<?php echo $sef; ?>">
													<table  width="100%" class="admintable" style="padding:5px;">
														<tr>
															<td class="key" valign="top">
																<?php echo JText::_('OS_META_DESCRIPTION')?>
															</td>
															<td>
																<textarea name="metadesc_<?php echo $sef; ?>" id="metadesc_<?php echo $sef; ?>" cols="40" rows="4"><?php echo $row->{'metadesc_'.$sef}?></textarea>
															</td>
														</tr>
													</table>
												</div>
												<?php
												$i++;	
												}
												?>
											</div>
										</div>
										</div>
										<?php
									}
									?>
									<?php
									}
									if(count($amenities) > 0){
										//echo $pane->startPanel(JText :: _('OS_CONVENIENCE'), "convenience");
										echo JHtml::_('sliders.panel', JText::_('OS_CONVENIENCE'), 'convenience');
										
										$optionArr = array();
										$optionArr[] = JText::_('OS_GENERAL_AMENITIES');
										$optionArr[] = JText::_('OS_ACCESSIBILITY_AMENITIES');
										$optionArr[] = JText::_('OS_APPLIANCE_AMENITIES');
										$optionArr[] = JText::_('OS_COMMUNITY_AMENITIES');
										$optionArr[] = JText::_('OS_ENERGY_SAVINGS_AMENITIES');
										$optionArr[] = JText::_('OS_EXTERIOR_AMENITIES');
										$optionArr[] = JText::_('OS_INTERIOR_AMENITIES');
										$optionArr[] = JText::_('OS_LANDSCAPE_AMENITIES');
										$optionArr[] = JText::_('OS_SECURITY_AMENITIES');
										?>
										<table  width="100%">
											<tr>
												<?php
												$j = 0;
												for($k = 0;$k<count($optionArr);$k++){
													$j++;
													$db->setQuery("Select * from #__osrs_amenities where category_id = '".$k."' and published = '1'");
													$tmpamenities = $db->loadObjectList();
													?>
													<td width="50%" style="text-align:left;padding:10px;vertical-align:top;">
														<table width="100%">
															<tr>
																<td width="100%" style="height:30px;background-color:orange;color:white;text-align:center;font-weight:bold;font-size:16px;">
																	<?php echo $optionArr[$k];?>
																</td>
															</tr>
															<tr>
																<td width="100%">
																<?php 
																for($i=0;$i<count($tmpamenities);$i++){
																	if(count($amenitylists) > 0){
																		if(in_array($tmpamenities[$i]->id,$amenitylists)){
																			$checked = "checked";
																		}else{
																			$checked = "";
																		}
																	}else{
																		$checked = "";
																	}
																	?>
																	<input type="checkbox" name="amenities[]" <?php echo $checked?> value="<?php echo $tmpamenities[$i]->id?>"> &nbsp; <?php echo $tmpamenities[$i]->amenities;?>
																	<BR />
																	<?php
																}
																?>
																</td>
															</tr>
														</table>
													</td>
													<?php 
													if($j % 2 == 0){
														echo "</tr><tr>";
													}
												}
												?>
											</tr>
										</table>
										<?php
										//echo $pane->endPanel();
									}
									//echo $pane->startPanel(JText :: _('OS_NEIGHBORHOOD'), "neiborhood");
									echo JHtml::_('sliders.panel', JText::_('OS_NEIGHBORHOOD'), 'neiborhood');
									?>
									
									<table  width="100%" class="admintable">
										<?php
										for($i=0;$i<count($neighborhoods);$i++){
											$neighborhood = $neighborhoods[$i];
											$db->setQuery("Select * from #__osrs_neighborhood where pid = '$row->id' and neighbor_id = '$neighborhood->id'");
											$neighbor_value = $db->loadObjectList();
											if(count($neighbor_value) > 0){
												$checked = "checked";
												$value = 1;
												$display = "block";

												$neighbor_value = $neighbor_value[0];
												$mins = $neighbor_value->mins;
												$traffic_type = $neighbor_value->traffic_type;
												$walk = "";
												$car = "";
												$train = "";
												switch ($traffic_type){
													case "1":
														$walk = "checked";
														break;
													case "2":
														$car = "checked";
														break;
													case "3":
														$train = "checked";
														break;
												}
											}else{
												$checked = "0";
												$value = 0;
												$display = "none";
											}
											?>
											<tr>
												<td class="key" width="30%">
													<?php echo JText::_($neighborhood->neighborhood)?>
												</td>
												<td width="5%">
													<input type="checkbox" value="<?php echo $value?>" name="nei_<?php echo $neighborhood->id?>" id="nei_<?php echo $neighborhood->id?>" <?php echo $checked?> onclick="javascript:showNeighborhood('<?php echo $neighborhood->id?>')">
												</td>
												<td width="65%">
													<div id="div_nei_<?php echo $neighborhood->id?>" style="display:<?php echo $display?>;">
														<input type="text" name="mins_nei_<?php echo $neighborhood->id?>" size="3" value="<?php echo $neighbor_value->mins;?>" class="input-mini" style="width:20px;" /> <?php echo JText::_('OS_MINS')?> <?php echo JText::_('OS_BY')?>
														&nbsp;&nbsp;&nbsp;
														<input type="radio" name="traffic_type_<?php echo $neighborhood->id?>" id="traffic_type_<?php echo $neighborhood->id?>" value="1" <?php echo $walk?>> <?php echo JText::_('OS_WALK')?>
														<input type="radio" name="traffic_type_<?php echo $neighborhood->id?>" id="traffic_type_<?php echo $neighborhood->id?>" value="2" <?php echo $car?>> <?php echo JText::_('OS_CAR')?>
														<input type="radio" name="traffic_type_<?php echo $neighborhood->id?>" id="traffic_type_<?php echo $neighborhood->id?>" value="3" <?php echo $train?>> <?php echo JText::_('OS_TRAIN')?>
													</div>
												</td>
											</tr>
											<?php
										}
										?>
									</table>
									<script language="javascript">
									function showNeighborhood(nid){
										var temp = document.getElementById('nei_' + nid);
										var div  = document.getElementById('div_nei_' + nid);
										if(temp.value == 0){
											div.style.display = "block";
											temp.value = 1;
										}else{
											div.style.display = "none";
											temp.value = 0;
										}
									}
									</script>
									<?php
									//echo $pane->endPanel();
									$fieldLists = array();
									if(count($groups) > 0){
										for($i=0;$i<count($groups);$i++){
											$group = $groups[$i];
											$fields = $group->fields;
											//if(count($fields) > 0){
												//echo $pane->startPanel($group->group_name,strtolower(str_replace(" ","",$group->group_name)));
												echo JHtml::_('sliders.panel', $group->group_name,strtolower(str_replace(" ","",$group->group_name)));
												?>
												
												<div class="row-fluid">
												<?php
												if(count($fields) > 0){
													for($j=0;$j<count($fields);$j++){
														$field = $fields[$j];
														$fieldLists[] = $field->id;
														if($field->required == 1){
															$require_field .= $field->field_name.",";
															$require_label .= $field->field_label.",";
														}
														if(intval($row->id) == 0){
															$display = "display:none;";
														}else{
															$display = "";
														}
														?>
														<div class="span12" id="extrafield_<?php echo $field->id?>" style="margin-left:0px;<?php echo $display;?>">
															<div class="span4" style="margin-left:0px;">
																<?php echo $field->field_label?>
															</div>
															<div class="span8" style="margin-left:0px;">
																	<?php
																	HelperOspropertyFields::showField($field,$row->id);
																	?>
															</div>
														</div>
														<div class="clearfix"></div>
														<?php
													}
												}
												?>
												</div>
												<?php
												//echo $pane->endPanel();
											//}
										}
									}
									//echo $pane->endPane();
									echo JHtml::_('sliders.end');
								?>
								</div>
							</td>
						</tr>
						<tr>
							<td width="100%" style="padding-top:5px;">
								<div class="col width-100">
									<fieldset id="photos<?php echo $row->id; ?>">
										<legend><?php echo JText::_('OS_PROPERTY_PHOTOS'); ?></legend>
										
										<ul class="nav nav-pills">
											<li class="active"><a href="#photo-file" data-toggle="tab"><?php echo JText::_('OS_PHOTOS_FILE'); ?></a></li>
											<li><a href="#zip-file" data-toggle="tab"><?php echo JText::_('OS_ZIP_FILE'); ?></a></li>
											<li><a href="#ajax-file" data-toggle="tab"><?php echo JText::_('OS_DRAGDROP'); ?></a></li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="photo-file">
												<?php echo sprintf(JText::_('OS_ACCORDING_TO_YOUR_CONFIG_NEW_PHOTOS_WILL_BE_RESIZE'), 100, 100); ?>
												<BR><BR>
												<?php
												$i = 0;
												if(count($row->photo) > 0){
													$photos = $row->photo;
													for($i=0;$i<count($photos);$i++){
														$photo = $photos[$i];
														?>
														<div style="display:block;padding:3px;border:1px dotted #efefef;" id="div_<?php echo $i?>">
														<table class="admintable">
															<tr>
																<td class="key">
																	<?php echo JText::_('OS_PHOTO'); ?> <?php echo $i + 1?>
																</td>
																<td>
																	<?php
																	OSPHelper::showPropertyPhoto($photo->image,'thumb',$row->id,'width:'.$configClass['images_thumbnail_width'].'px !important;max-width:150px !important;','img-rounded img-polaroid','');
																	?>
																	<span id="photo_<?php echo $i+1?>div">
																	<input type="file" name="photo_<?php echo $i+1?>" id="photo_<?php echo $i+1?>" size="30" onchange="javacript:check_file('photo_<?php echo $i+1?>');">
																	</span>
																</td>
															</tr>
															<tr>
																<td class="key">
																	<?php echo JText::_('OS_PHOTO_DESCRIPTION'); ?>
																</td>
																<td>
																	<textarea name="photodesc_<?php echo $i+1?>" id="photodesc_<?php echo $i+1?>" class="inputbox" cols="40" rows="3"><?php echo $photo->image_desc?></textarea>
																</td>
															</tr>
															<tr>
																<td class="key">
																	
																	<?php echo JText::_('OS_ORDERING'); ?>
																</td>
																<td>
																	<input type="text" name="ordering_<?php echo $i+1?>" id="ordering_<?php echo $i+1?>" class="input-mini" style="width:20px;" value="<?php echo $photo->ordering?>">
																</td>
															</tr>
															<tr>
																<td class="key">
																	
																	<?php echo JText::_('OS_REMOVE'); ?>
																</td>
																<td>
																	<input type="checkbox" name="remove_<?php echo $photo->id?>" id="remove_<?php echo $photo->id?>" value="0" onclick="javascript:changeValue('remove_<?php echo $photo->id?>')">
																</td>
															</tr>
														</table>
														</div>
														<?php
													}
												}
												?>
												
												<?php
												if(intval($row->id) > 0){
													$j = $i;
												}else{
													$j = 0;
												}
												$limit_photo = ($configClass['limit_upload_photos'] > 0) ? $configClass['limit_upload_photos']:24;
												for($i=$j;$i<$limit_photo;$i++){
													?>
													<div style="display:none;padding:3px;border:1px dotted #efefef;" id="div_<?php echo $i?>">
														<table class="admintable">
															<tr>
																<td class="key">
																	<?php echo JText::_('OS_PHOTO'); ?> <?php echo $i + 1?>
																</td>
																<td>
																	<span id="photo_<?php echo $i+1?>div">
																	<input type="file" name="photo_<?php echo $i+1?>" id="photo_<?php echo $i+1?>" size="30" onchange="javacript:check_file('photo_<?php echo $i+1?>');">
																	</span>
																</td>
															</tr>
															<tr>
																<td class="key">
																	<?php echo JText::_('OS_PHOTO_DESCRIPTION'); ?>
																</td>
																<td>
																	<textarea name="photodesc_<?php echo $i+1?>" id="photodesc_<?php echo $i+1?>" class="inputbox" cols="40" rows="3"></textarea>
																</td>
															</tr>
															<tr>
																<td class="key">
																	<?php echo JText::_('OS_ORDERING'); ?>
																</td>
																<td>
																	<?php echo JText::_('Ordering will increased automatically')?>
																</td>
															</tr>
														</table>
													</div>
													
													<?php
												}
												?>
												<BR>
												<div id="newphoto<?php echo $id; ?>" class="button2-left" style="display:block;">
													<div class="image">
														<a href="javascript:addPhoto()"><?php echo JText::_( 'OS_ADD_PHOTO' ); ?></a>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="zip-file"  style="text-align:center;">
												<input type="file" onchange="javascript:check_file_type()" class="inputbox" id="zip_file" name="zip_file" size="30">
											</div>
											<div class="tab-pane" id="ajax-file" style="text-align:center;">
												<?php
												if($row->id > 0){
													$link = JURI::root()."administrator/index.php?option=com_osproperty&task=upload_ajaxupload&tmpl=component&id=$row->id";
													?>
													<a href="<?php echo $link;?>" class="osmodal" rel="{handler:'iframe', size: {x:850, y: 550},onClose:function(){var js =window.location.reload();}}">
														<?php echo JText::_('OS_CLICK_HERE_TO_USE_DRAG_AND_DROP_FEATURE');?>
													</a>;
													<?php
												}else{
													echo JText::_('OS_AFTER_SAVING_PROPERTY_YOU_WILL_BE_ABLE_TO_USE_THIS_FEATURE');
												}
												?>
											</div>
										</div>
									</fieldset>
								</div>
								
								<div>
								<!-- jQuery Upload go here -->
								
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<?php 
		if ($translatable)
		{
		?>
		</div>
			<div class="tab-pane" id="translation-page">
				<ul class="nav nav-tabs">
					<?php
						$i = 0;
						foreach ($languages as $language) {						
							$sef = $language->sef;
							?>
							<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#translation-page-<?php echo $sef; ?>" data-toggle="tab"><?php echo $language->title; ?>
								<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" /></a></li>
							<?php
							$i++;	
						}
					?>			
				</ul>		
				<div class="tab-content">			
					<?php	
						$i = 0;
						foreach ($languages as $language)
						{												
							$sef = $language->sef;
						?>
							<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="translation-page-<?php echo $sef; ?>">													
								<table width="100%" class="admintable" style="background-color:white;">
									<tr>
										<td class="key">
											<?php echo JText::_('OS_PROPERTY_TITLE')?>
										</td>
										<td>
											<input type="text" name="pro_name_<?php echo $sef;?>" id="pro_name_<?php echo $sef;?>" value="<?php echo $row->{'pro_name_'.$sef}?>" size="50" class="input-large" />
										</td>
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('OS_ALIAS')?>
										</td>
										<td>
											<input type="text" name="pro_alias_<?php echo $sef;?>" id="pro_alias_<?php echo $sef;?>" value="<?php echo $row->{'pro_alias_'.$sef}?>" size="50" class="input-large" />
										</td>
									</tr>
									<tr>
										<td class="key" valign="top">
											<?php echo JText::_('OS_SMALL_DESCRIPTION')?>
										</td>
										<td>
											<textarea name="pro_small_desc_<?php echo $sef;?>" id="pro_small_desc_<?php echo $sef;?>" cols="50" rows="5" class="input-large"><?php echo stripslashes($row->{'pro_small_desc_'.$sef})?></textarea>
										</td>
									</tr>
									<?php
									//$editor = &JFactory::getEditor();
									?>
									<tr>
										<td class="key" valign="top">
											<?php echo JText::_('OS_FULL_DESCRIPTION')?>
										</td>
										<td>
											<?php
											echo $editor->display( 'pro_full_desc_'.$sef,  $row->{'pro_full_desc_'.$sef} , '95%', '250', '75', '20',false ) ;
											?>
										</td>
									</tr>
								</table>
							</div>										
						<?php				
							$i++;		
						}
					?>
				</div>	
		</div>
		<?php				
		}
		?>
		<?php
		if(intval($row->id) == 0){
			$j = -1;
		}else{
			$j = count($row->photo) - 1;
		}
		?>
		<input type="hidden" name="current_number_photo" id="current_number_photo" value="<?php echo $j?>" />
		<input type="hidden" name="newphoto" id="newphoto" value="<?php echo count($row->photo)?>" />
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="9000000000" />
		<input type="hidden" name="require_field" id="require_field" value="<?php echo substr($require_field,0,strlen($require_field)-1)?>" />
		<input type="hidden" name="require_label" id="require_label" value="<?php echo substr($require_label,0,strlen($require_label)-1)?>" />
		<?php 
		if(count($lists['types']) > 0){
			foreach ($lists['types'] as $type){
				?>
				<input type="hidden" name="type_id_<?php echo $type->id?>" id="type_id_<?php echo $type->id?>" value="<?php echo implode(",",$type->fields);?>"/>
				<?php 
			}
		}
		?>
		<input type="hidden" name="field_ids" id="field_ids" value="<?php echo implode(",",$fieldLists)?>" />
		
		<?php 
		if($configClass['use_sold'] == 1){
			?>
			<input type="hidden" name="sold_property_types" id="sold_property_types" value="<?php echo $configClass['sold_property_types']?>" />
			<?php 
		}
		?>
		</form>
		<script language="javascript">
		jQuery("#pro_type").change(function(){
			var fields = jQuery("#field_ids").val();
			var fieldArr = fields.split(",");
			if(fieldArr.length > 0){
				for(i=0;i<fieldArr.length;i++){
					jQuery("#extrafield_" + fieldArr[i]).hide("fast");
				}
			}
			var selected_value = jQuery("#pro_type").val();
			var selected_fields = jQuery("#type_id_" + selected_value).val();
			var fieldArr = selected_fields.split(",");
			if(fieldArr.length > 0){
				for(i=0;i<fieldArr.length;i++){
					jQuery("#extrafield_" + fieldArr[i]).show("slow");
				}
			}
			<?php 
			if($configClass['use_sold'] == 1){
				?>
				var selected_value = jQuery("#pro_type").val();
				var selected_fields = jQuery("#sold_property_types").val();
				var fieldArr = selected_fields.split("|");
				if(fieldArr.length > 0){
					var show = 0;
					for(i=0;i<fieldArr.length;i++){
						//jQuery("#extrafield_" + fieldArr[i]).show("slow");
						if(fieldArr[i] == selected_value)
						{
							show = 1;
							//jQuery("#sold_information").show("slow");
						}
					}
					if(show == 1){
						jQuery("#sold_information").show("slow");
					}else{
						jQuery("#sold_information").hide("slow");
					}
				}
				<?php 
			}
			?>
		});
		</script>
		<?php
	}

	/**
	 * Show photo in zip file
	 *
	 * @param unknown_type $id
	 * @param unknown_type $images
	 */
	function showPhotoinZipFile($property,$images){
		JToolBarHelper::title(JText::_('OS_SELECT_PHOTOS_AND_IMPORT')." [".$property->pro_name."]");
		JToolBarHelper::save('properties_save_photos');
		JToolBarHelper::cancel('properties_cancel_photos');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm" enctype="multipart/form-data">
			
			<div id="result_zipfile">
			<?php 
			if(count($images) > 0)
			{
				?>
				<div style="padding:10px;">
					<span class="label">
						<?php
						echo sprintf(JText::_('OS_THERE_ARE_PHOTOS_IN_ZIP_FILE'),count($images));
						?>
					</span>
					>
					<span class="label label-info">
						<input type="checkbox" name="select_all" id="select_all" class="checkAll" /> 
						&nbsp;&nbsp;
						<?php echo JText::_('OS_CHECKALL'); ?>
					</span>
				</div>
				<?php 
			}
			?>
			<ul>
			<?php 
			foreach ($images as $key => $image)
			{
				?>
					<li id="oswrapper-<?php echo $key; ?>">
						<img class="os-images-thumbnail-<?php echo $key; ?>" alt="<?php echo $image->name; ?>" src="<?php echo JURI::root().'tmp/osphotos_'.$property->id.'/'.$image->name; ?>" style="width: 210px; height: 120px;"><br>
						<textarea disabled="disabled" id="show_<?php echo $key; ?>" class="os-disabled" rows="5" cols="8" name="photodesc_<?php echo $key?>"></textarea><br>
						<div class="controls">
							<label class="checkbox">
								<input id="<?php echo $key; ?>" type="checkbox" class="check" value="<?php echo $image->name; ?>" name="newphotos[]" /> 
								<?php 
								$name = $image->name;
								
								if(strlen($name) > 20){
									for($i=0;$i<20;$i++){
										echo substr($name,$i,1);
									}
									echo "...";
								}else{
									echo $name;
								}
								?>
							</label>
						</div>
					</li>
				<?php 
			}
			?>
			<ul>
			</div>
		<input type="hidden" name="MAX_FILE_SIZE" value="9000000000" />
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="id" value="<?php echo $property->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="save" id="save" value="<?php echo JRequest::getVar('save',0)?>" />
		</form>
		<script language="javascript">
			(function($){
				$(document).ready(function(){
					$('#result_zipfile li').hover(function(){
						$(this).addClass('qick-photo-background');
					});
					$('#result_zipfile li').mouseleave(function(){
						$(this).removeClass('qick-photo-background');
					});
				});
				
				//set check all uncheck all
			    $('.checkAll').click(function(){
			        if($(this).attr('checked')){
			        	$('#result_zipfile ul li').addClass('active');
			            $('input:checkbox').attr('checked',true);
			            $('.os-disabled').attr('disabled', false);
			        }
			        else{
			        	$('#result_zipfile ul li').removeClass('active');
			            $('input:checkbox').attr('checked',false);
			            $('.os-disabled').attr('disabled', true);
			            $('.os-disabled').val(' ');
			        }
			    });
	
			    //check disabled textarea
			    $('.check').click(function(){
				    var ID = this.id;
				    if($('#show_'+ ID).is(':disabled')){
				    	$('#show_'+ ID).removeAttr('disabled');
				   }
				    else{
				    	$('#show_'+ ID).attr('disabled',true);
				    }
	
				    if($('#oswrapper-'+ID).hasClass('active')) {
				    	$('#oswrapper-'+ID).removeClass('active');
				    }
				    else{
				    	$('#oswrapper-'+ID).addClass('active');
				    }
				});
			})(jQuery);
			function check_file(){
				str=document.getElementById('photopackage').value.toUpperCase();
				suffix=".ZIP";
				if(!(str.indexOf(suffix, str.length - suffix.length) !== -1)){
					alert('<?php echo JText::_('OS_ALLOW_FILE')?>: *.zip');
					document.getElementById('photopackage').value='';
				}
		}
		</script>
		<?php
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 * @param unknown_type $amenities
	 * @param unknown_type $amenitylists
	 * @param unknown_type $groups
	 */
	function printProperties($option,$row,$lists,$amenities,$amenitylists,$groups){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$document =& JFactory::getDocument();
		JHTML::_('behavior.tooltip');
		?>
		<style>
		fieldset label, fieldset span.faux-label {
		    clear: right;
		}
		</style>
		<table  width="100%">
			<tr>
				<td width="60%" valign="top" style="padding-right: 10px;">
					<div class="col width-100">
						<fieldset class="general">
							<legend><?php echo JText::_( 'OS_GENERAL' ); ?></legend>
							<table  width="100%" class="admintable">
								<?php
								if($row->ref != ""){
									?>
									<tr>
										<td class="key" width="30%"><?php echo JText::_('Ref #')?></td>
										<td><?php echo $row->ref?></td>
									</tr>
									<?php
								}
								?>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_PROPERTY_NAME')?></td>
									<td><?php echo $row->pro_name?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_CATEGORY')?></td>
									<td><?php echo $lists['category']; ?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_AGENT').'/'.JText::_('OS_OWNER')?></td>
									<td><?php echo $row->agent->name; ?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_PROPERTY_TYPE')?></td>
									<td><?php echo $lists['type']; ?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_RENT_TIME_FRAME')?></td>
									<td><?php echo JText::_($row->rent_time);?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_APPROVED')?></td>
									<td><?php echo $row->approved? JText::_('OS_YES'):JText::_('OS_NO'); ?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_IS_FEATURED')?></td>
									<td><?php echo $row->isFeatured? JText::_('OS_YES'):JText::_('OS_NO'); ?></td>
								</tr>
								<?php if($row->isSold == 1) {?>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_IS_SOLD')?></td>
									<td><?php echo $row->isSold? JText::_('OS_YES'):JText::_('OS_NO'); ?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_SOLD_ON')?></td>
									<td><?php echo $row->soldOn; ?></td>
								</tr>
								<?php  } ?>
								<tr>
									<td class="key" valign="top"><?php echo JText::_('OS_ACCESS')?></td>
									<td><?php echo $lists['access'][$row->access]; ?></td>
								</tr>
								<?php
								if($row->price_call == 0){
									/*
									if($row->price_original > 0){
									?>
									<tr>
										<td class="key" width="30%"><?php echo JText::_('OS_ORIGINAL_PRICE')?></td>
										<td><?php echo HelperOspropertyCommon::loadCurrency($row->curr)?> <?php echo HelperOspropertyCommon::showPrice($row->price_original)?></td>
									</tr>
									<?php
									}
									*/
									if($row->price > 0){
									?>
									<tr>
										<td class="key" width="30%"><?php echo JText::_('OS_PRICE')?></td>
										<td><?php //echo HelperOspropertyCommon::loadCurrency($row->curr)?> <?php //echo HelperOspropertyCommon::showPrice($row->price)?> 
										<?php
										echo OSPHelper::generatePrice($row->curr,$row->price);
										if($row->rent_time != ""){
											echo " / ".JText::_($row->rent_time);
										}
										?>
										</td>
									</tr>
									<?php
									}
									?>
								<?php
								}else{
								?>
									<tr>
										<td class="key" width="30%"><?php echo JText::_('OS_CALL_FOR_PRICE')?></td>
										<td><?php echo $row->price_call? JText::_('OS_YES'):JText::_('OS_NO'); ?></td>
									</tr>
								<?php
								}
								?>
							</table>
						</fieldset>
					</div>
					<!-- End General tab-->
					
					<!-- Address -->
					<div class="col width-100">
						<fieldset class="general">
							<legend><?php echo JText::_( 'OS_ADDRESS' ); ?></legend>
							<table  width="100%" class="admintable">
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_ADDRESS')?>
									</td>
									<td>
										<?php echo $row->address?>
									</td>
								</tr>
								<?php if($row->postcode != "" ){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_POSTCODE')?>
									</td>
									<td>
										<?php echo $row->postcode?>
									</td>
								</tr>
								<?php } ?>
								<?php if($row->city != "" ){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_CITY')?>
									</td>
									<td>
										<?php echo HelperOspropertyCommon::loadCityName($row->city)?>
									</td>
								</tr>
								<?php } ?>
								<!--
								<?php if($row->province != "" ){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_PROVINCE')?>
									</td>
									<td>
										<?php echo $row->province?>
									</td>
								</tr>
								<?php } ?>
								-->
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_COUNTRY')?>
									</td>
									<td>
										<?php echo $lists['country']; ?>
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_STATE')?>
									</td>
									<td>
										<div id="div_states">
										<?php
										echo $lists['states'];
										?>
									</td>
								</tr>
								<?php
								if($row->region != ""){
								?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_REGION')?>
									</td>
									<td>
										<?php echo $row->region?>
									</td>
								</tr>
								<?php } ?>
								
							</table>
						</fieldset>
					</div>	
					<!-- End Address -->
					
					<!-- Property information -->
					<div class="col width-100">
						<fieldset class="general">
							<legend><?php echo JText::_( 'OS_PROPERTY_INFORMATION' ); ?></legend>
							<table  width="100%" class="admintable">
								<!--
								<tr>
									<td class="key" valign="top">
										<?php echo JText::_('OS_VIDEO_EMBED_CODE')?>
									</td>
									<td>
										<?php echo $row->pro_video?>
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_DOCUMENT_LINK')?>
									</td>
									<td>
										<?php echo $row->pro_pdf?>
									</td>
								</tr>
								-->
								<?php if($row->rooms > 0){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_NUMBER_ROOMS')?>
									</td>
									<td>
										<?php echo $row->rooms;?>
									</td>
								</tr>
								<?php } ?>
								<?php if($row->bath_room > 0){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_NUMBER_BATHROOMS')?>
									</td>
									<td>
										<?php echo $row->bath_room; ?>
									</td>
								</tr>
								<?php } ?>
								<?php if($row->bed_room > 0){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_NUMBER_BEDROOMS')?>
									</td>
									<td>
										<?php echo $row->bed_room;?>
									</td>
								</tr>
								<?php } ?>
								<?php if($row->parking != ""){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_PARKING')?>
									</td>
									<td>
										<?php echo $row->parking?>
									</td>
								</tr>
								<?php } ?>
								<?php if($row->square_feet != ""){?>
								<tr>
									<td class="key" width="30%">
										<?php echo OSPHelper::showSquareLabels();?>
									</td>
									<td>
										<?php echo $row->square_feet?>
									</td>
								</tr>
								<?php } ?>
								<?php if($row->lot_size != ""){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_LOT_SIZE');?>
									</td>
									<td>
										<?php echo $row->lot_size?> <?php echo OSPHelper::showSquareSymbol();?>
									</td>
								</tr>
								<?php } ?>
								<?php if($row->number_of_floors > 0){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_NUMBER_OF_FLOORS')?>
									</td>
									<td>
										<?php echo $row->number_of_floors?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</fieldset>
					</div>	
					<!-- End Other information -->

					<!-- Other information -->
					<div class="col width-100">
						<fieldset class="general">
							<legend><?php echo JText::_( 'OS_OTHER_INFORMATION' ); ?></legend>
							<table  width="100%" class="admintable">
								<tr>
									<td class="key" valign="top">
										<?php echo JText::_('OS_SMALL_DESCRIPTION')?>
									</td>
									<td>
										<?php echo $row->pro_small_desc?>
									</td>
								</tr>
								<?php if($row->pro_full_desc != ""){?>
								<tr>
									<td class="key" valign="top">
										<?php echo JText::_('OS_FULL_DESCRIPTION')?>
									</td>
									<td>
										<?php echo $row->pro_full_desc; ?>
									</td>
								</tr>
								<?php } ?>
								<?php if($row->note != ""){?>
								<tr>
									<td class="key" valign="top">
										<?php echo JText::_('OS_AGENT_NOTE')?>
									</td>
									<td>
										<?php echo $row->note?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</fieldset>
					</div>	
					<div class="col width-100">
						<fieldset class="fieldset_photo">
							<legend><?php echo JText::_('OS_AGENT_INFORMATION')?></legend>
								<table  width="100%" class="admintable">
									<tr>
										<td class="key" >
											<?php echo JText::_('OS_PHOTO')?>
										</td>
										<td>
											<?php
											if($row->agent->photo != ""){
												?>
												<img style="width: 70px;" src="<?php echo JURI::root()?>images/osproperty/agent/thumbnail/<?php echo $row->agent->photo?>">
												<?php
											}else{
												?>
												<img style="width: 70px;" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/user.png" style="border:1px solid #CCC;" width="120">
												<?php
											}
											?>
										</td>
									</tr>
									<tr>
										<td class="key" >
											<?php echo JText::_('OS_NAME')?>
										</td>
										<td>
											<b><?php echo $row->agent->name;?></b>
										</td>
									</tr>
									<tr>
										<td class="key" >
											<?php echo JText::_('OS_ADDRESS')?>
										</td>
										<td>
											<?php echo $row->agent->address;?>
										</td>
									</tr>
									<tr>
										<td class="key" >
											<?php echo JText::_('OS_STATE')?>
										</td>
										<td>
											<?php echo $row->agent->state_name;?>
										</td>
									</tr>
									<tr>
										<td class="key" >
											<?php echo JText::_('OS_COUNTRY')?>
										</td>
										<td>
											<?php echo $row->agent->country_name;?>
										</td>
									</tr>
									<?php if($row->agent->license != ""){?>
									<tr>
										<td class="key" >
											<?php echo JText::_('OS_LICENSE')?>
										</td>
										<td>
											<?php echo $row->agent->license;?>
										</td>
									</tr>
									<?php
									}
									if($row->agent->phone != ""){
									?>
									<tr>
										<td class="key" style="text-align:right;">
											<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/phone.png">
										</td>
										<td>
											<?php echo $row->agent->phone;?>
										</td>
									</tr>
									<?php
									}
									if($row->agent->mobile != ""){
									?>
									<tr>
										<td class="key" >
											<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/mobile.png">
										</td>
										<td>
											<?php echo $row->agent->mobile;?>
										</td>
									</tr>
									<?php
									}
									if($row->agent->fax != ""){
									?>
									<tr>
										<td class="key" >
											<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/fax.png">
										</td>
										<td>
											<?php echo $row->agent->fax;?>
										</td>
									</tr>
									<?php
									}
									if($row->agent->yahoo != ""){
									?>
									<tr>
										<td class="key" >
											<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/yahoo.png">
										</td>
										<td>
											<?php echo $row->agent->yahoo;?>
										</td>
									</tr>
									<?php
									}
									if($row->agent->gtalk != ""){
									?>
									<tr>
										<td class="key" >
											<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/gtalk.png">
										</td>
										<td>
											<?php echo $row->agent->gtalk;?>
										</td>
									</tr>
									<?php
									}
									if($row->agent->skype != ""){
									?>
									<tr>
										<td class="key" >
											<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/skype.png">
										</td>
										<td>
											<?php echo $row->agent->skype;?>
										</td>
									</tr>
									<?php
									}
									if($row->agent->aim != ""){
									?>
									<tr>
										<td class="key" >
											<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/aim.png">
										</td>
										<td>
											<?php echo $row->agent->aim;?>
										</td>
									</tr>
									<?php
									}
									if($row->agent->facebook != ""){
									?>
									<tr>
										<td class="key" >
											<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/facebook.png">
										</td>
										<td>
											<a href="<?php echo $row->agent->facebook;?>" target="_blank"><?php echo $row->agent->facebook;?></a>
										</td>
									</tr>
									<?php
									}
									?>
								</table>
						</fieldset>
					
					</div>
				<!-- End Other information -->
				</td>
				<td width="40%" valign="top">
					<div class="col width-100">
						<fieldset class="general">
							<legend><?php echo JText::_( 'OS_INFORMATION' ); ?></legend>
							<table  width="100%" class="admintable">
								<tr>
									<td class="key" style="width: 110px">
										<?php echo JText::_('OS_PROPERTY_ID')?>:
									</td>
									<td style="padding:5px;">
										<?php echo $row->id; ?>
									</td>
								</tr>
								<tr>
									<td class="key" style="width: 110px">
										<?php echo JText::_('OS_HITS')?>:
									</td>
									<td style="padding:5px;">
										<?php echo $row->hits; ?>
									</td>
								</tr>
								<tr>
									<td class="key" style="width: 110px">
										<?php echo JText::_('OS_RATING')?>:
									</td>
									<td style="padding:5px;">
										<?php
										if($row->number_votes > 0){
											$points = round($row->total_points/$row->number_votes);
											?>
											<table  width="100%">
												<tr>
													<td width="40%">
														<?php
														for($i=1;$i<=$points;$i++){
															?>
															<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star1.png">
															<?php
														}
														for($i=$points+1;$i<=5;$i++){
															?>
															<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png">
															<?php
														}
														?>
													</td>
													<td align="left" width="60%">
														<?php
														echo " <b>(".$points."/5)</b>";
														?>
													</td>
												</tr>
											</table>
											<?php


										}else{
											for($i=1;$i<=5;$i++){
												?>
												<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png">
												<?php
											}
										}
										?>
									</td>
								</tr>
								<tr>
									<td class="key" style="width: 110px">
										<?php echo JText::_('OS_STATUS')?>:
									</td>
									<td style="padding:5px;">
										<?php
										if($row->published == 0){
											echo JText::_('OS_UNPUBLISHED');
										}else{
											echo JText::_('OS_PUBLISHED');
										}
										?>
									</td>
								</tr>
								<tr>
									<td class="key" style="width: 110px">
										<?php echo JText::_('OS_CREATED')?>:
									</td>
									<td style="padding:5px;">
										<?php
										echo $row->created;
										?>
									</td>
								</tr>
							</table>
						</fieldset>
						<!--
						<fieldset>
							<legend><?php echo JText :: _('OS_META_INFORMATION')?></legend>
							<table  width="100%" class="admintable" ">
								<tr>
									<td class="key" style="width: 110px" valign="top"> <?php echo JText::_('OS_META_KEYWORDS')?></td>
									<td> <?php echo $row->metakey?> </td>
								</tr>
								<tr>
									<td class="key" style="width: 110px" valign="top"> <?php echo JText::_('OS_META_DESCRIPTION')?></td>
									<td> <?php echo $row->metadesc?> </td>
								</tr>
							</table>
						</fieldset>
						-->
						<?php 
						$query = $db->getQuery(true);
						$query->select("*")->from("#__osrs_property_price_history")->where("pid = '$row->id'")->order("`date` desc");
						$db->setQuery($query);
						$prices = $db->loadObjectList();
						if(($configClass['use_property_history'] == 1) and (count($prices) > 0)){ ?>
						<!-- History -->
						<fieldset>
							<legend><?php echo JText::_( 'OS_PROPERTY_HISTORY' ); ?></legend>
							<div class="row-fluid">
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
						</fieldset>
						<?php } 
						?>
						<!-- End History -->
						<?php 
						$query = $db->getQuery(true);
						$query->select("*")->from("#__osrs_property_history_tax")->where("pid = '$row->id'")->order("`tax_year` desc");
						$db->setQuery($query);
						$taxes = $db->loadObjectList();
						if(($configClass['use_property_history'] == 1) and (count($taxes) > 0)){ ?>
						<!-- tax -->
						<fieldset>
							<legend><?php echo JText::_( 'OS_PROPERTY_TAX' ); ?></legend>
							<div class="row-fluid">
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
						</fieldset>
						<?php }
						?>
						<?php				
						if(count($amenitylists) > 0){
						?>
						<fieldset>
							<legend><?php echo JText :: _('OS_CONVENIENCE'); ?></legend>
							<table  width="100%">
								<tr>
									<?php
									$j = 0;
									for($i=0;$i<count($amenities);$i++){
										if(count($amenitylists) > 0){
											if(in_array($amenities[$i]->id,$amenitylists)){
												$j++;
										?>
											<td align="left" style="padding:5px;">
												<?php echo $amenities[$i]->amenities?>
											</td>
										<?php
											}
										}else{
										?><td>&nbsp;</td>
										<?php 	break;
										}

										if($j==3){
											echo "</tr><tr>";
											$j = 0;
										}
									}
									?>
								</tr>
							</table>
						</fieldset>
							<?php
						}

						$db->setQuery("Select count(id) from #__osrs_neighborhood where pid = '$row->id'");
						$count = $db->loadResult();
						if($count > 0){
							?>
							<fieldset>
							<legend><?php echo JText :: _('OS_NEIGHBORHOOD'); ?></legend>
							<?php
							HelperOspropertyCommon::loadNeighborHood($row->id);
							?>
						</fieldset>
							<?php
						}
						if(count($groups) > 0){
							for($i=0;$i<count($groups);$i++){
								$group = $groups[$i];
								$fields = $group->fields;
								if(count($fields) > 0){
									if(HelperOspropertyFields::checkFieldData($row->id,$group->id) == 1){
									?>
										<fieldset>
											<legend><?php echo $group->group_name;?></legend>
											<table  width="100%" class="admintable" style="border:1px solid #CCC !important;">
											<?php
											$fields = HelperOspropertyFields::getFieldsData($row->id,$group->id);
											for($j=0;$j<count($fields);$j++){
												$field = $fields[$j];
												//if(HelperOspropertyFieldsPrint::showField($field,$row->id) != ""){
												?>
												<tr>
													<td class="key" style="width: 110px">
													<?php
													echo HelperOspropertyFieldsPrint::showField($field,$row->id);
													?>
														<?php echo $field->field_label?>
													</td>
													<td>
														<?php
														echo $field->value;
														?>
													</td>
												</tr>
												<?php
												//}
											}
											?>
											</table>
										</fieldset>
										<?php
									}
								}
							}
						}
					?>
					</div>
					<div class="col width-100">
						<fieldset id="photos<?php echo $row->id; ?>">
							<legend><?php echo JText::_('OS_PROPERTY_PHOTOS'); ?></legend>
							<?php
							$i = 0;
							if(count($row->photo) > 0){
								$photos = $row->photo;
								for($i=0;$i<count($photos);$i++){
									$photo = $photos[$i];
									?>
									<div style="display:block;padding:3px;border:1px dotted #efefef;" id="div_<?php echo $i?>">
									<table class="admintable">
										<tr>
											<td class="key" style="width: 110px">
												<?php echo JText::_('OS_PHOTO')?> <?php echo $i + 1?>
											</td>
											<td>
												<?php												
												OSPHelper::showPropertyPhoto($photo->image,'thumb',$row->id,'width: 100px;','img-polaroid','');
												?>
											</td>
										</tr>
										<?php if ($photo->image_desc != ""){?>
										<tr>
											<td class="key" style="width: 110px">
												<?php echo JText::_('OS_PHOTO_DESCRIPTION')?>
											</td>
											<td>
												<?php echo $photo->image_desc?>
											</td>
										</tr>
										<?php } ?>
									</table>
									</div>
									<?php
								}
							}
							?>
						</fieldset>
					</div>
				</td>
			</tr>
		</table>
		<script language="javascript">
		window.print();
		</script>
		<?php
	}

	/**
	 * Backup Form
	 *
	 * @param unknown_type $option
	 */
	function backupForm($option){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_BACKUP'),"logo48.png");
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php" name="adminform" id="adminForm">
			<table  width="100%" style="background-color:#efefef;border:1px solid #CCC;">
				<tr>
					<td width="100%" style="padding:5px;">
						<H2>
							<?php echo JText::_('OS_BACKUP')?>
						</H2>
						<?php echo JText::_("OS_BACKUP_INFOR")?>
						<BR><BR>
						<center>
							<input type="submit" class="btn btn-primary" value="<?php echo JText::_('OS_BACKUP')?>" style="color:red;font-weight:bold;border:1px solid pink;">
						</center>
						<BR>
					</td>
				</tr>
			</table>
			<input type="hidden" name="option" value="com_osproperty">
			<input type="hidden" name="task" value="properties_dobackup">
			<input type="hidden" name="boxchecked" value="0">
		</form>
		<?php
	}


	function restoreForm($option,$lists){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_RESTORE'),"logo48.png");
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php" name="adminform" id="adminForm">
			<table  width="100%" style="background-color:#efefef;border:1px solid #CCC;">
				<tr>
					<td width="100%" style="padding:5px;">
						<H2>
							<?php echo JText::_('OS_RESTORE')?>
						</H2>
						<?php echo JText::_("OS_RESTORE_INFOR")?>
						<BR><BR>
						<center>
							<?php
							echo $lists['bkfiles'];
							?>
							<BR>
							<BR>
							<input type="submit" class="btn btn-primary" value="<?php echo JText::_('OS_RESTORE')?>" style="color:red;font-weight:bold;border:1px solid pink;">
						</center>
						<BR>
					</td>
				</tr>
			</table>
			<input type="hidden" name="option" value="com_osproperty">
			<input type="hidden" name="task" value="properties_dorestore">
			<input type="hidden" name="boxchecked" value="0">
		</form>
		<?php
	}

	/**
	 * Upload photo packages
	 *
	 * @param unknown_type $option
	 */
	function uploadPhotoPackages($option){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_IMPORT_PHOTOS'));
		JToolBarHelper::save('properties_douploadphotopackages',JText::_('OS_UPLOAD'));
		JToolBarHelper::cancel('');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<script language="javascript">
		function check_file(){
			str=document.getElementById('photopackage').value.toUpperCase();
			suffix=".ZIP";
			if(!(str.indexOf(suffix, str.length - suffix.length) !== -1)){
				alert('<?php echo JText::_('OS_ALLOW_FILE')?>: *.zip');
				document.getElementById('photopackage').value='';
			}
		}
		</script>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<table 	  width="100%" class="admintable">
			<tr>
				<td width="100%" style="padding:20px;">
					
					<table  width="100%" style="border-bottom:1px solid #CCC;border-right:1px solid #CCC;background-color:#FFF;">
						<tr>
							<td width="100%" style="text-align:center;padding:20px;">
								<b>
									<?php echo JText::_('OS_PLEASE_SELECT_PHOTO_PACKAGE')?>
								</b>
								<BR>
								<BR>
								<input type="file" size="60" name="photopackage" id="photopackage" class="inputbox" onchange="javascript:check_file()">
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
		</table>
		<input type="hidden" name="MAX_FILE_SIZE" value="9000000000">
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="0">
		</form>
		<?php
	}

	/**
	 * Prepare install data form
	 *
	 * @param unknown_type $option
	 */
	function prepareInstallSampleForm($option,$lists){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_INSTALLSAMPLEDATA'));
		JToolBarHelper::cancel();
		?>
		<script language="javascript">
		function activeContinueButton(){
			checkbox = document.getElementById('agree');
			startbutton = document.getElementById('startbutton');
			if(checkbox.value == 0){
				checkbox.value = 1;
				startbutton.disabled = false;
			}else{
				checkbox.value = 0;
				startbutton.disabled = true;
			}
		}
		</script>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm">
		<table 	  width="100%" class="admintable">
			<tr>
				<td width="100%" style="padding:20px;">
					
					<table  width="100%" style="border-bottom:1px solid #CCC;border-right:1px solid #CCC;background-color:#FFF;">
						<tr>
							<td width="100%" style="text-align:left;padding:20px;">
								<b>
								<?php echo JText::_('OS_NOTICE')?>:
								</b>
								<br>
								<br>
								<?php
								if($lists['cuser'] == 0){
									echo JText::_('OS_ADD_NEW_COMPANY_USER');
									echo "<BR><BR>";
								}
								if($lists['ccompany'] == 0){
									echo JText::_('OS_ADD_NEW_COMPANY');
									echo "<BR><BR>";
								}
								if($lists['auser'] == 0){
									echo JText::_('OS_ADD_NEW_AGENT_USER');
									echo "<BR><BR>";
								}
								if($lists['cagent'] == 0){
									echo JText::_('OS_ADD_NEW_AGENT');
									echo "<BR><BR>";
								}
								echo JText::_('OS_REMOVE_DATA_FROM_SOME_TABLES');
								?>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:left;padding:20px;">
								<b>
								<?php echo JText::_('OS_SELECT_LOCATION_TO_INSTALL_SAMPLE_DATA')?>:
								</b>
								<br>
								<br>
								<table  width="100%"  class="admintable">
									<tr>
										<td width="33%" align="center" valign="top" style="font-size:1.0em;">
											<B>
												<?php echo JText::_('OS_COUNTRY')?>
											</B>
											<BR />
											<?php
											if(HelperOspropertyCommon::checkCountry()){
											?>
												<?php echo $lists['country'];?>
											<?php
											}else{
												$db = JFactory::getDbo();
												$db->setQuery("Select country_name from #__osrs_countries where id = '".HelperOspropertyCommon::getDefaultCountry()."'");
												echo $db->loadResult();
												?>
												<?php echo $lists['country'];?>
												<?php
											}
											?>
										</td>
										<td width="33%" align="center" valign="top" style="font-size:1.0em;">
											<B>
												<?php echo JText::_('OS_STATE')?>
											</B>
											<BR />
											<div id="country_state">
												<?php echo $lists['states'];?>
											</div>
										</td>
										<td width="33%" align="center" valign="top" style="font-size:1.0em;">
											<B>
												<?php echo JText::_('OS_CITY')?>
											</B>
											<BR />
											<div id="city_div">
												<?php echo $lists['city'];?>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding:20px;text-align:center;border:1px solid red;background-color:pink;font-weight:bold;">
								<input type="checkbox" name="agree" id="agree" value="0" onclick="javascript:activeContinueButton()">&nbsp;
								<?php
								echo JText::_('OS_READ_AND_ACCEPTED');
								?>
								<BR><BR>
								<input type="button" id="startbutton" class="btn btn-primary" value="<?php echo JText::_('OS_START_INSTALL')?>" disabled="true" onclick="javascript:checkSubmitInstallSampleData()">
								
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="properties_installdata">
		<input type="hidden" name="boxchecked" value="0">
		</form>
		<script language="javascript">
		function change_country_agent(country_id,state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoStateCity(country_id,state_id,city_id,'country','state',live_site);
		}
		function change_state(state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoCityInstallSampleData(state_id,city_id,'state_id',live_site);
		}
		function loadCity(state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoCityInstallSampleData(state_id,city_id,'state',live_site);
		}
		function checkSubmitInstallSampleData(){
			var country = document.getElementById('country');
			var state   = document.getElementById('state');
			var city    = document.getElementById('city');
			if((country.value == "") || (state.value == "") || (city.value == "")){
				alert("<?php echo JText::_('OS_PLEASE_SELECT_LOCATION_IF_YOU_WANT_TO_INSTALL_SAMPLE_DATA')?>");
				return false;
			}else{
				var answer = confirm("<?php echo JText::_('OS_ARE_YOU_SURE_TO_INSTALL_SAMPLE_DATA')?>");
				if(answer == 1){
					document.adminForm.submit();
				}
			}
		}
		</script>
		<?php
	}


	/**
	 * List cities
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function listCities($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion;
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=properties_listcity" name="adminForm" id="adminForm">
		<table width="100%">
			<tr>
				<td style="padding:5px;text-align:right;">
					<b>
						<?php echo JText::_('OS_FILTER')?>: <input type="text" class="input-medium search-query" name="keyword" value="<?php echo JRequest::getVar('keyword','')?>">
					</b>
					<input type="button" class="btn btn-primary" value="<?php echo JText::_('OS_SEARCH')?>">
				</td>
			</tr>
			<tr>
				<td style="padding:5px;text-align:LEFT;padding-top:0px;font-weight:bold;padding-bottom:10px;">
					<?php echo JText::_('OS_COUNTRY')?>: <?php echo $lists['country']?>
					&nbsp;&nbsp;&nbsp;
					<?php echo JText::_('OS_STATE')?>: <?php echo $lists['states']?>
				</td>
			</tr>
		</table>
		<table  width="100%" class="table table-striped">
		<thead>
			<tr>
				<th width="33%">
					<?php echo JText::_('OS_CITY')?>
				</th>
				<th width="33%">
					<?php echo JText::_('OS_STATE')?>
				</th>
				<th width="33%">
					<?php echo JText::_('OS_COUNTRY')?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3" style="text-align:center;">
					<?php echo $pageNav->getListFooter();?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$db = JFactory::getDBO();
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row = $rows[$i];
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="left" style="padding-left:30px;">
						<a class="pointer" onclick="if (window.parent) window.parent.jSelectCity_jform_request_id('<?php echo $row->id?>', '<?php echo $row->city?>');">
							<?php echo $row->city?>
						</a>
					</td>
					<td align="left" style="padding-left:30px;">
						<?php echo $row->state_name?>
					</td>
					<td align="left" style="padding-left:30px;">
						<?php echo $row->country_name?>
					</td>
				</tr>
			<?php
			$k = 1 - $k;
			}
			?>
		</tbody>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="properties_listcity" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="tmpl" value="component" />
		</form>
		<?php
	}
	
	/**
	 * Optimize sef form
	 *
	 * @param unknown_type $option
	 */
	function optimizeSefForm($option){
		global $mainframe;
		JToolbarHelper::title(JText::_('OS_SEF_URLS_OPTIMIZATION'));
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
		<div class="row-fluid">
			<div class="span12" style="text-align:center;">
				<div class="span3"></div>
				<div class="span6">
					<h3>
						<?php echo JText::_('OS_SEF_URLS_OPTIMIZATION');?>
					</h3>
					<div class="clearfix"></div>
					<div class="img-polaroid" style="text-align:left;">
						<?php echo JText::_('OS_SEF_URLS_OPTIMIZATION_EXPLAIN');?>
					</div>
					<div class="clearfix"></div>
					<br />
					<input type="submit" class="btn btn-info" value="<?php echo JText::_('OS_YES_I_AGREE');?>" />
					<input type="button" onclick="javascript:returnControlPanel();" class="btn btn-warning" value="<?php echo JText::_('OS_NO_I_DO_NOT_AGREE');?>" />
				</div>
				<div class="span3"></div>
			</div>
		</div>
		<input type="hidden" name="option" id="option" value="com_osproperty" />
		<input type="hidden" name="task" value="properties_doOptimizeSefUrls" id="task" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<script language="javascript">
		function returnControlPanel(){
			location.href = "index.php?option=com_osproperty&task=cpanel_list";
		}
		</script>
		<?php
	}
	
	function syncdatabaseForm($option){
		global $mainframe;
		JToolbarHelper::title(JText::_('OS_SYNCHONOUS_MULTIPLE_LANGUAGES_DATABASE'));
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
		<div class="row-fluid">
			<div class="span12" style="text-align:center;">
				<div class="span3"></div>
				<div class="span6">
					<h3>
						<?php echo JText::_('OS_SYNCHONOUS_MULTIPLE_LANGUAGES_DATABASE');?>
					</h3>
					<div class="clearfix"></div>
					<div class="img-polaroid" style="text-align:left;">
						<?php echo JText::_('OS_SYNCHONOUS_MULTIPLE_LANGUAGES_DATABASE_EXPLAIN');?>
						<BR />
						<strong>
						
						</strong>
					</div>
					<div class="clearfix"></div>
					<br />
					<input type="submit" class="btn btn-info" value="<?php echo JText::_('OS_YES_I_AGREE');?>" />
					<input type="button" onclick="javascript:returnControlPanel();" class="btn btn-warning" value="<?php echo JText::_('OS_NO_I_DO_NOT_AGREE');?>" />
				</div>
				<div class="span3"></div>
			</div>
		</div>
		<input type="hidden" name="option" id="option" value="com_osproperty" />
		<input type="hidden" name="task" value="properties_doSyncdatabase" id="task" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<script language="javascript">
		function returnControlPanel(){
			location.href = "index.php?option=com_osproperty&task=cpanel_list";
		}
		</script>
		<?php	
	}
	
	function reGeneratePicturesForm($option){
		global $mainframe;
		JToolbarHelper::title(JText::_('OS_RE_GENERATE_PICTURES_OF_PROPERTIES'));
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
		<div class="row-fluid">
			<div class="span12" style="text-align:center;">
				<div class="span2"></div>
				<div class="span8">
					<h3>
						<?php echo JText::_('OS_RE_GENERATE_PICTURES_OF_PROPERTIES');?>
					</h3>
					<div class="clearfix"></div>
					<div class="img-polaroid" style="text-align:left;">
						<?php echo JText::_('OS_RE_GENERATE_PICTURES_OF_PROPERTIES_EXPLAIN');?>
						<BR />
						<hr>
						<center>
						<strong>This function need quite a lot memory and time for processing. So please take care your PHP Setting</strong>
						</center>
						<div class="clearfix"></div>
						<table class="table-striped" width="100%">
							<tr>
								<td width="30%">
									<strong>
									Memory limit:
									</strong>
								</td>
								<td width="70%">
									<?php
									$memory_limit = ini_get('memory_limit');
									if($memory_limit != ""){
										echo $memory_limit;
										$memory_limit1 = intval(trim(str_replace("M","",$memory_limit)));
										if($memory_limit1 <  200){
											if(ini_set('memory_limit','999M')){
												?>
												&nbsp;<font style="color:green;">OS Property can change this value when we run the function</font>
												<?php
											}else{
												?>
												&nbsp;<font style="color:red;">OS Property cannot change this value when we run the function</font>
												<?php
											}
										}
									}
									?>
								</td>
							</tr>
							<tr>
								<td width="30%">
									<strong>Max execution time: </strong>
								</td>
								<td width="70%">
									<?php
									$max_execution_time = ini_get('max_execution_time');
									if($max_execution_time != ""){
										echo $max_execution_time/60;
										echo " seconds";
										$max_execution_time1 = intval(trim(str_replace("M","",$max_execution_time)));
										if($max_execution_time1 <  1000){
											if(ini_set('max_execution_time','3000')){
												?>
												&nbsp;<font style="color:green;">OS Property can change this value when we run the function</font>
												<?php
											}else{
												?>
												&nbsp;<font style="color:red;">OS Property cannot change this value when we run the function</font>
												<?php
											}
										}
									}
									?>
								</td>
							</tr>
						</table>
					</div>
					<div class="clearfix"></div>
					<br />
					<input type="submit" class="btn btn-info" value="<?php echo JText::_('OS_YES_I_AGREE');?>" />
					<input type="button" onclick="javascript:returnControlPanel();" class="btn btn-warning" value="<?php echo JText::_('OS_NO_I_DO_NOT_AGREE');?>" />
				</div>
				<div class="span2"></div>
			</div>
		</div>
		<input type="hidden" name="option" id="option" value="com_osproperty" />
		<input type="hidden" name="task" value="properties_doReGeneratePictures" id="task" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<script language="javascript">
		function returnControlPanel(){
			location.href = "index.php?option=com_osproperty&task=cpanel_list";
		}
		</script>
		<?php	
		
	}
		
}
?>