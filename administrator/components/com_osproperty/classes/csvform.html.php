<?php
/*------------------------------------------------------------------------
# csvform.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class HTML_OspropertyCsvform{
	/**
	 * Import CSV
	 *
	 * @param unknown_type $option
	 * @param unknown_type $log
	 */
	function completeImportCsv($option,$log){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_IMPORT_CSV_COMPLETE'));
		JToolbarHelper::custom('form_default','checkin.png', 'checkin.png',JText::_('OS_CSV_FORMS'),false);
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="50%">
						<?php echo JText::_('OS_IMPORT_COMPLETED')?> 
					</th>
					<th width="50%">
						<?php echo JText::_('OS_IMPORT_NOT_COMPLETED')?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="50%" style="text-align:left;border-right:1px solid #CCC;border-bottom:1px solid #CCC;border-left:1px solid #CCC;background-color:#E5FAE3;">
						<?php
						echo $log->log2;
						?>
					</td>
					<td width="50%" style="text-align:left;border-bottom:1px solid #CCC;border-right:1px solid #CCC;background-color:#F8E5E5;">
						<?php
						echo $log->log1;
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" id="boxchecked" value="0">
		</form>
		<?php
	}
	
	function updateOtherInformationForm($option,$properties,$row,$lists){
		global $mainframe,$configClass,$_jversion;
		JToolBarHelper::title(JText::_('OS_IMPORT_OTHER_INFORMATION')." [".$row->form_name."]");
		JToolBarHelper::save('form_saveotherinformation');
		JToolBarHelper::cancel('form_cancel');
		
		?>
		<script type="text/javascript" src="<?php echo JURI::root()?>components/com_osproperty/js/ajax.js"></script>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<div style="width:100%;">
			<fieldset>
				<legend><?php echo JText::_('OS_STEP3')?>: <?php echo JText::_('OS_IMPORT_OTHER_INFORMATION')?></legend>
				<div style="width:98%;padding:10px;">
				
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="2%">
								#
							</th>
							<th width="15%">
								<?php echo JText::_('OS_PROPERTY_NAME')?>
							</th>
							<th width="20%">
								<?php echo JText::_('OS_AGENT')?>
							</th>
							<th width="10%">
								<?php echo JText::_('OS_CATEGORY')?>
							</th>
							<th width="10%">
								<?php echo JText::_('OS_PROPERTY_TYPE')?>
							</th>
							<?php 
							if(HelperOspropertyCommon::checkCountry()) {
							?>
							<th width="10%">
								<?php echo JText::_('OS_COUNTRY')?>
							</th>
							<?php
							}
							?>
							<th width="10%">
								<?php echo JText::_('OS_STATE')?>
							</th>
							<th width="10%">
								<?php echo JText::_('OS_CITY')?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$proArr = array();
						for($i=0;$i<count($properties);$i++){
							$property = $properties[$i];
							$proArr[] = $property->id;
							$country_script = " onChange='javascript:changeCountryValue($property->id)'";
							$state_script = " onChange='javascript:changeStateValue($property->id)' ";
							?>
							<tr>
								<td style="text-align:center;">
									<?php echo $i + 1?>
								</td>
								<td style="text-align:left;">
									<?php echo $property->pro_name?>
								</td>
								<td style="text-align:center;">
									
									<table cellpadding="0" cellspacing="0" width="100%"><tr><td>
									<?php
									echo JHTML::_('select.genericlist',$lists['agentArr'],'agent_id'.$property->id,'class="input-small"','value','text');
									?>
									</td><td>
									<label> Or <?php echo JText::_('OS_AGENT_ID')?>:</label> <input type="text" class="input-mini" size="4" name="agent_id_value<?php echo $property->id?>" id="agent_id_value<?php echo $property->id?>">
									</td>
									</tr>
									</table>
									
								</td>
								<td style="text-align:center;">
									<?php
									$parentArr = OspropertyProperties::loadCategoryOptions($category_id,$onChangeScript);
									echo JHTML::_('select.genericlist',$parentArr,'category_id'.$property->id,'style="width:120px;" class="inputbox" ','value','text');
									?>
								</td>
								<td style="text-align:center;">
									<?php
									echo JHTML::_('select.genericlist',$lists['type'],'pro_type'.$property->id,'style="width:120px;" class="inputbox" ','value','text');
									?>
								</td>
								<?php 
								if(HelperOspropertyCommon::checkCountry()) {
								?>
								<td style="text-align:center;">
									
									<?php
									echo JHTML::_('select.genericlist',$lists['country'],'country'.$property->id,'style="width:140px;" class="input-small" '.$country_script,'value','text');
									
									$disabled = "disabled";
									?>
									
								</td>
								<?php
								}else{
									?>
									<input type="hidden" name="country<?php echo $property->id?>" id="country<?php echo $property->id?>" value="<?php echo HelperOspropertyCommon::getDefaultCountry();?>">
									<?php
									$disabled = "";
								}
								?>
								<td style="text-align:center;">
									
									<div id="div_state_<?php echo $property->id?>">
									<?php
									echo JHTML::_('select.genericlist',$lists['state'],'state'.$property->id,'style="width:140px;" class="input-small" '.$disabled.' '.$state_script,'value','text');
									?>
									</div>
									
								</td>
								<td style="text-align:center;">
									
									<div id="div_city_<?php echo $property->id?>">
									<?php
									echo JHTML::_('select.genericlist',$lists['city'],'city'.$property->id,'style="width:140px;"  class="input-small" disabled','value','text');
									?>
									</div>
									
								</td>
							</tr>
							<?php
						}
						
						$property_str = implode(",",$proArr);
						?>
					</tbody>
				</table>
			</fieldset>
		</div>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" id="boxchecked" value="1" />
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="current_item" id="current_item" value="" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
		<input type="hidden" name="property_str" id="property_str" value="<?php echo $property_str?>"  />
		</form>
		<script language="javascript">
		function changeCountryValue(pid){
			var current_item = document.getElementById('current_item');
			current_item.value = pid;
			var country = document.getElementById('country' + pid);
			if(country != null){
				changeCountryAjax(pid,country.value,"<?php echo JURI::root()?>");
			}
		}
		
		function changeStateValue(pid){
			var current_item = document.getElementById('current_item');
			current_item.value = pid;
			var state = document.getElementById('state' + pid);
			if(state != null){
				changeStateAjax(pid,state.value,"<?php echo JURI::root()?>");
			}
		}
		
		<?php if ($_jversion == "1.5"){?>
				function submitbutton(pressbutton)
			<?php }else{?>
				Joomla.submitbutton = function(pressbutton)
			<?php }?>{
					var form = document.adminForm;
					if(pressbutton == "form_saveotherinformation"){
						var canSubmit = 1;
						var property_str = form.property_str.value;
						var proArr = property_str.split(",");
						if(proArr.length > 0){
							for(i=0;i<proArr.length;i++){
								property_id = proArr[i];
								
								agent_id = document.getElementById('agent_id' + property_id);
								agent_id_value = document.getElementById('agent_id_value' + property_id);
								if((agent_id.value == "") && (agent_id_value.value == "")){
									canSubmit = 0;
								}
								country = document.getElementById('country' + property_id);
								state = document.getElementById('state' + property_id);
								city = document.getElementById('city' + property_id);
								category_id = document.getElementById('category_id' + property_id);
								pro_type = document.getElementById('pro_type' + property_id);
								
								if(category_id.value == ""){
									canSubmit = 0;
								}
								
								if(pro_type.value == ""){
									canSubmit = 0;
								}
								
								if(country.value == ""){
									canSubmit = 0;
								}
								
								if(state.value == ""){
									canSubmit = 0;
								}
								if(city.value == ""){
									canSubmit = 0;
								}
							}
							
							if(canSubmit == 0){
								alert("<?php echo JText::_('OS_PLEASE_FILL_ALL_INFORMATION')?>");
							}else{
								form.task.value = pressbutton;
								form.submit();		
							}
						}
					}else{
						form.submit();
					}
				}
		</script>
		<?php
	}
	
	/**
	 * Photo form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $form_id
	 */
	function importPhotoForm($option,$form_id){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_csv_forms where id = '$form_id'");
		$row = $db->loadObject();
		JToolBarHelper::title(JText::_('OS_IMPORT_PHOTO_PACK')." [".$row->form_name."]");
		if (version_compare(JVERSION, '3.0', 'lt')) {
			JToolBarHelper::custom('form_completeimport','forward.png','forward.png','Skip');
			JToolBarHelper::custom('form_doimportphoto','export.png','export.png',JText::_('OS_IMPORTPHOTO'));
		}else{
			JToolBarHelper::custom('form_completeimport','refresh.png','refresh.png','Skip');
			JToolBarHelper::custom('form_doimportphoto','upload.png','upload.png',JText::_('OS_IMPORTPHOTO'));
		}
		JToolBarHelper::cancel('form_cancel');
		?>
		<script language="javascript">
		function check_file(){
            str=document.getElementById('photopack').value.toUpperCase();
	        suffix=".ZIP";
	        if(!(str.indexOf(suffix, str.length - suffix.length) !== -1)){
	        	alert('<?php echo JText::_('OS_ALLOW_FILE')?>: *.zip');
	            document.getElementById('photopack').value='';
	        }
	    }

		</script>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<div style="width:100%;">
			<fieldset>
				<legend><?php echo JText::_('OS_STEP2')?>: <?php echo JText::_('OS_SELECT_PHOTO_PACKAGAE')?></legend>
				<div style="width:98%;padding:10px;">
				<div style="background-color:pink;border:1px solid red !important;padding:10px;">
				<b>
					<?php echo JText::_('OS_NOTICE')?>:
				</b>
				<br/>
				<?php echo JText::_('OS_IMPORTPHOTO_NOTICE')?>
				</div>
				<BR />
				<div class="row-fluid">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#uploaddiv" data-toggle="tab"><?php echo JText::_('OS_UPLOAD_PHOTO_PACKAGE');?></a></li>
						<li><a href="#directdiv" data-toggle="tab"><?php echo JText::_('OS_INSTALL_FROM_DIRECTORY');?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="uploaddiv">
							<div class="width-100 fltlft">
								<fieldset class="adminform">
									<input type="file" name="photopack" id="photopack" size="50" class="input-large" onchange="javascript:check_file()"> (Only allow: *.zip)
									<div class="clr">
				
									</div>
									<div>
										<i><?php echo JText::_('OS_ONLY_SUPPORT_ZIP_FILE')?></i>
									</div>
								</fieldset>
							</div>
						</div>
						<div class="tab-pane" id="directdiv">
							<div class="width-100 fltlft">
								<fieldset class="adminform">
									<input type="text" name="photodirectory" id="photodirectory" class="input-large" value="<?php echo JPATH_ROOT?>/tmp" style="width:300px;"> (Only allow: *.zip)
									<div class="clr">
				
									</div>
								</fieldset>
							</div>
						</div>
					</dov>
				</div>
			</fieldset>
		</div>
		<input type="hidden" name="option" value="com_osproperty" /> 
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" id="boxchecked" value="1" />
		<input type="hidden" name="id" value="<?php echo $form_id;?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="900000000" />
		</form>
		<?php
	}
	/**
	 * Import CSV Form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 */
	function importCsvForm($option,$row,$lists){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_IMPORT_CSV')." [".$row->form_name."]");
		if (version_compare(JVERSION, '3.0', 'lt')) {
			JToolBarHelper::custom('form_doimportcsv','export.png','export.png',JText::_('OS_IMPORTCSV'));
		}else{
			JToolBarHelper::custom('form_doimportcsv','upload.png','upload.png',JText::_('OS_IMPORTCSV'));
		}
		JToolBarHelper::cancel('form_cancel');
		?>
		<script language="javascript">
		function check_file(){
            str=document.getElementById('csv_file').value.toUpperCase();
	        suffix=".CSV";
	        if(!(str.indexOf(suffix, str.length - suffix.length) !== -1)){
	        	alert('<?php echo JText::_('OS_ALLOW_FILE')?>: *.csv');
	            document.getElementById('csv_file').value='';
	        }
	    }

		</script>
		<form method="POST" action="index.php?option=com_osproperty&task=form_default" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<div style="width:100%;">
			<fieldset>
				<legend><?php echo JText::_('OS_STEP1')?>: <?php echo JText::_('OS_SELECT_CSV_FILE')?></legend>
				<div style="width:98%;padding:10px;">
				<div style="background-color:pink;border:1px solid red !important;padding:10px;">
				<b>
					<?php echo JText::_('OS_NOTICE')?>:
				</b>
				<br/>
				<?php echo JText::_('OS_IMPORT_NOTICE')?>
				<Br />
				<span class="badge badge-success">4</span>
				<?php echo Jtext::_('OS_IMPORT_NON_UTF8_DATA');?> <?php echo $lists['utf'];?>
				<Br />
				<span class="badge badge-success">5</span>
				<?php echo Jtext::_('OS_REMOVE_ALL_PROPERTIES_BEFORE_IMPORTING');?> <?php echo $lists['removeproperties'];?>
				<Br />
				<span class="badge badge-success">6</span>
				<?php echo Jtext::_('OS_RENT_TIME_FRAME_NOTICE');?>
				<BR/><BR/>
				OS_NOT_APPLICABLE: <?php echo JText::_('OS_NOT_APPLICABLE');?><BR/>
				OS_PER_SQUARE_METRE: <?php echo JText::_('OS_PER_SQUARE_METRE');?><BR/>
				OS_PER_SQUARE_FEET: <?php echo JText::_('OS_PER_SQUARE_FEET');?><BR/>
				OS_PER_MONTH: <?php echo JText::_('OS_PER_MONTH');?><BR/>
				OS_PER_FORTHNIGHT: <?php echo JText::_('OS_PER_FORTHNIGHT');?><BR/>
				OS_PER_WEEK: <?php echo JText::_('OS_PER_WEEK');?><BR/>
				</div>
				
				
				<input type="file" name="csv_file" id="csv_file" size="50" class="inputbox" onchange="javascript:check_file()">
				<div class="clr">
				
				</div>
				<i><?php echo JText::_('OS_MAX_FILE_SIZE')?> <?php echo $row->max_file_size?> MB</i>
				
				<div class="clr">
				
				</div>
				<!--
				<label><?php echo JText::_('OS_SELECT_AGENT')?>: &nbsp;</label><?php echo $lists['agent'];?>
				<div class="clr">
				
				</div>
				<label><?php echo JText::_('OS_PHOTO_SOURCE')?>: &nbsp;</label> <?php echo $lists['photo'];?>
				</div>
				<div class="clr">
				-->
				</div>
				
			</fieldset>
		</div>
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" id="boxchecked" value="1">
		<input type="hidden" name="id" value="<?php echo $row->id?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $row->max_file_size*1024*1024?>"/>
		</form>
		<?php
	}
	/**
	 * Default listing csv form lists
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 */
	function defaultList($option,$rows,$pageNav){
		global $mainframe,$_jversion,$configClass;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_CSVFORMS'),"logo48.png");
		JToolBarHelper::addNew('form_add');
		JToolBarHelper::editList('form_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'form_remove');
		JToolBarHelper::publish('form_publish');
		JToolBarHelper::unpublish('form_unpublish');
		if (version_compare(JVERSION, '3.0', 'lt')) {
			JToolBarHelper::custom('form_importcsv','export.png','export.png',JText::_('OS_IMPORTCSV'));
		}else{
			JToolBarHelper::custom('form_importcsv','upload.png','upload.png',JText::_('OS_IMPORTCSV'));
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=form_default" name="adminForm" id="adminForm">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="5%">
					
					</th>
					<th width="5%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="20%">
						<?php echo JText::_('OS_FORM_NAME')?>
					</th>
					<th width="10%">
						<?php echo JText::_('OS_CSV_FORM')?>
					</th>
					<th width="15%">
						<?php echo JText::_('OS_MAX_FILE_SIZE')?>
					</th>
					<th width="10%">
						<?php echo JText::_('OS_IMPORT')?>
					</th>
					<th width="15%">
						<?php echo JText::_('OS_LAST_IMPORTED')?>
					</th>
					<th width="15%">
						<?php echo JText::_('OS_CREATED_ON')?>
					</th>
					<th width="10%">
						<?php echo JText::_('OS_PUBLISHED')?>
					</th>
					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td style="text-align:center;" colspan="9">
						<?php
							echo $pageNav->getListFooter();
						?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$checked = JHtml::_('grid.id', $i, $row->id);
					$link 		= JRoute::_( 'index.php?option=com_osproperty&task=form_edit&cid[]='. $row->id );
					$published 	= JHTML::_('jgrid.published', $row->published, $i , 'form_');
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center"><?php echo $checked; ?></td>
						<td>
							<a href="<?php echo $link?>">
								<?php echo $row->form_name?>
							</a>
						</td>
						<td style="text-align:center;">
							<a href="index.php?option=com_osproperty&task=form_downloadcsv&cid[]=<?php echo $row->id?>&no_html=1" title="<?php echo JText::_('OS_DOWNLOAD_CSV_FORM')?>">
								<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/csv16.png" border="0" >
							</a>
						</td>
						<td style="text-align:center;">
							<?php echo $row->max_file_size?> MB
						</td>
						<td style="text-align:center;">
							<a href="index.php?option=com_osproperty&task=form_importcsv&cid[]=<?php echo $row->id?>" title="<?php echo JText::_('OS_IMPORT_CSV')?>">
								<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/import16.png" border="0">
							</a>
						</td>
						<td>
							<?php echo $row->last_import?>
						</td>
						<td style="text-align:center;">
							<?php echo $row->created_on?>
						</td>
						<td style="text-align:center;">
							<?php echo $published?>
						</td>
						
					</tr>
			<?php
				$k = 1 - $k;	
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="form_default">
		<input type="hidden" name="boxchecked" value="0">
		</form>
		<?php
	}
	
	/**
	 * Edit HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editHTML($option,$row,$lists){
		global $mainframe,$_jversion,$configClass;
		JHtml::_('behavior.tooltip');
		JRequest::setVar( 'hidemainmenu', 1 );
		$db = JFactory::getDBO();
		JHtml::_('behavior.tooltip');
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('OS_CSV_FORM').$title);
		JToolBarHelper::save('form_save');
		JToolBarHelper::apply('form_apply');
		JToolBarHelper::cancel('form_cancel');
		
		if($_jversion != "1.5"){
			$functionvalue = "Joomla.submitbutton = function(task)";
			$submitvalue = "Joomla.submitform(task);";
		}else{
			$functionvalue = "function submitbutton(task)";
			$submitvalue = "submitform(task);";
		}
		
		$requirelabelArr = $lists['requirelabels'];
		$requirefieldArr = $lists['requirefields'];
		$require_fields = $lists['requireid'];
		$fields	= $lists['fields'];
		$labels	= $lists['labels'];
		
		$optionArr = array();
		$optionArr[0]->value = "";
		$optionArr[0]->text = "";
		for($i=0;$i<count($fields);$i++){
			$optionArr[$i+1]->value = $fields[$i];
			$optionArr[$i+1]->text = $labels[$i];
		}
		
		$optionArr1 = array();
		for($i=0;$i<count($fields);$i++){
			$optionArr1[$i+1]->value = $fields[$i];
			$optionArr1[$i+1]->text = $labels[$i];
		}
		?>
		<script language="javascript">
		<?php
		echo $functionvalue;
		?>{
			
			if(task == "form_cancel"){
				<?php
				echo $submitvalue;
				?>
			}else{
				var form = document.adminForm;
				var require_labels = form.require_labels;
				var require_str = "";
				if(require_labels.options.length > 0){
					for(i=0;i<require_labels.options.length;i++){
						if(require_labels.options[i].selected == false){
							require_str += require_labels.options[i].text + ", ";
						}
					}
					if(require_str != ""){
						require_str = require_str.substring(0,require_str.length-2);
						alert("<?php echo JText::_('OS_PLEASE_ENTER_FIELDS')?> " + require_str);
					}else{
						<?php
						echo $submitvalue;
						?>
					}
				}else{
					<?php
					echo $submitvalue;
					?>
				}
			}
		}
		
		function updateSelectList(fieldid){
			var fieldname = document.getElementById("fields" + fieldid);
			var itemvalue = fieldname.value;
			var form = document.adminForm;
			var require_labels = form.require_labels;
			if(require_labels.options.length > 0){
				for(i=0;i<require_labels.options.length;i++){
					//if(require_labels.options[i].value == itemvalue){
						require_labels.options[i].selected = false;
					//}
				}
			}
			for(i=1;i<=50;i++){
				fieldname = "fields" + i;
				fielditem = document.getElementById(fieldname);
				if(fielditem.value != ""){
					for(j=0;j<require_labels.options.length;j++){
						if(require_labels.options[j].value == fielditem.value){
							require_labels.options[j].selected = true;
						}
					}
				}
			}
			
			
			
			var selected_field = document.getElementById('selected_field');
			var length = selected_field.length;
			for(i=0;i<length;i++){
				if((selected_field.options[i].value == itemvalue) && (selected_field.options[i].selected == true)){
					alert("You cannot select this field again");
					document.getElementById("fields" + fieldid).value = "";
					selected_field.options[i].selected = false;
				}
			}
			
			
			for(i=1;i<=50;i++){
				fieldname = "fields" + i;
				fielditem = document.getElementById(fieldname);
				if(fielditem.value != ""){
					for(j=0;j<selected_field.options.length;j++){
						if(selected_field.options[j].value == fielditem.value){
							selected_field.options[j].selected = true;
						}
					}
				}
			}
		}
		
		function showDiv(type,div_name){
			var temp = document.getElementById(div_name);
			if(type == 0){
				temp.style.display = "none";
			}else if(type==1){
				temp.style.display = "block";
			}
		}
		</script>
		
		<form method="POST" action="index.php?option=com_osproperty&task=form_default" name="adminForm" id="adminForm">
		<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
			<tr>
				<td class="key">
					<?php
						echo JText::_('OS_FORM_NAME');
					?>
				</td>
				<td>
					<input type="text" class="input-large" name="form_name" id="form_name" size="40" value="<?php echo $row->form_name;?>">
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php
						echo JText::_('OS_MAX_FILE_SIZE');
					?>
				</td>
				<td>
					<input type="text" class="input-mini" name="max_file_size" id="max_file_size" size="2" value="<?php echo $row->max_file_size;?>"> MB
				</td>
			</tr>
			<tr>
				<td class="key" valign="top">
					<?php
						echo JText::_('OS_INFORMATION');
					?>
				</td>
				<td>
					<div style="border:1px solid #CCC;background-color:#E4EFFC;padding:10px;">
						<span class="label label-important"><?php echo JText::_('OS_PLEASE_READ_AND_ENTER_CONFIG_PARAMETERS');?></span>
						<BR /><BR />
						
						<span class="badge badge-warning">1</span> &nbsp;
						<?php echo JText::_('OS_FIEDS');?>: <?php echo JText::_('OS_CALL_FOR_PRICE')?>, <?php echo JText::_('OS_SHOW_ADDRESS')?> <?php echo JText::_('OS_ARE_YES_NO_FIELDS');?>
						<BR/>
						<span class="badge badge-warning">2</span> &nbsp;
						<?php echo JText::_('OS_PLEASE_ENTER_YES_NO_VALUE_THAT_YOU_WILL_ENTER_IN_CSV_FILE')?>
						<BR/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo JText::_('OS_YES')?>:<input type="text" class="input-mini" name="yes_value" id="yes_value" value="<?php echo $row->yes_value;?>" />
						&nbsp;&nbsp;&nbsp;
						<?php echo JText::_('OS_NO')?>:<input type="text" class="input-mini" name="no_value" id="no_value" value="<?php echo $row->no_value;?>"/>
						<BR/>
						<span class="badge badge-warning">3</span> &nbsp;
						<?php echo JText::_('OS_PHOTO_FIELD_ENTER_PHOTO_NAMES_SEPERATED_BY_SEMICOLON');?>
						<BR/>
						<span class="badge badge-warning">4</span> &nbsp;
						<?php echo JText::_('OS_WHEN_SYSTEM_CANNOT_FIND_PROPERTY_TYPE_THE_SYSTEM_WILL');?>:
						<BR />
						<?php
						$display   = "none";
						if($row->ftype == 0){
							$fchecked1 = "checked";
							$fchecked2 = "";
							$display   = "none";
						}else{
							$fchecked1 = "";
							$fchecked2 = "checked";
							$display   = "block";
						}
						?>
						<div style="padding-left:35px;">
							<input type="radio" name="ftype" id="ftype" value="0" onClick="javascript:showDiv(0,'type_div');" <?php echo $fchecked1;?> /> <?php echo JText::_('OS_CREATE_NEW_PROPERTY_TYPE')?>
							<BR />
							<input type="radio" name="ftype" id="ftype" value="1" onClick="javascript:showDiv(1,'type_div');" <?php echo $fchecked2;?> /> <?php echo JText::_('OS_SELECT_DEFAULT_PROPERTY_TYPE')?>
							<div style="display:<?php echo $display?>;" id="type_div">
								<?php echo $lists['type'];?>
							</div>
						</div>
						<BR/>
						<span class="badge badge-warning">5</span> &nbsp;
						<?php echo JText::_('OS_WHEN_SYSTEM_CANNOT_FIND_CATEGORY_THE_SYSTEM_WILL');?>:
						<BR />
						<?php
						$display   = "none";
						if($row->fcategory == 0){
							$cchecked1 = "checked";
							$cchecked2 = "";
							$display   = "none";
						}else{
							$cchecked1 = "";
							$cchecked2 = "checked";
							$display   = "block";
						}
						?>
						<div style="padding-left:35px;">
							<input type="radio" name="fcategory" id="fcategory" value="0" onClick="javascript:showDiv(0,'category_div');" <?php echo $cchecked1;?> /> <?php echo JText::_('OS_CREATE_NEW_CATEGORY')?>
							<BR />
							<input type="radio" name="fcategory" id="fcategory" value="1" onClick="javascript:showDiv(1,'category_div');" <?php echo $cchecked2;?> /> <?php echo JText::_('OS_SELECT_DEFAULT_CATEGORY')?>
							<div style="display:<?php echo $display;?>;" id="category_div">
								<?php echo $lists['category'];?>
							</div>
						</div>
						<BR/>
						<span class="badge badge-warning">6</span> &nbsp;
						<?php echo JText::_('OS_WHEN_SYSTEM_CANNOT_FIND_AGENT_PLEASE_SELECT_DEFAULT_AGENT');?>:
						<?php echo $lists['agent'];?>
						<BR/>
						<span class="badge badge-warning">7</span> &nbsp;
						<?php echo JText::_('OS_WHEN_SYSTEM_CANNOT_FIND_COUNTRY_PLEASE_ENTER_DEFAULT_COUNTRY_ID');?>:
						<?php echo $lists['country'];?>
						<BR/>
						<span class="badge badge-warning">8</span> &nbsp;
						<?php echo JText::_('OS_WHEN_SYSTEM_CANNOT_FIND_STATE_PLEASE_SELECT_DEFAULT_STATE');?>:
						<?php
						$display   = "none";
						if($row->fstate == 0){
							$schecked1 = "checked";
							$schecked2 = "";
							$display   = "none";
						}else{
							$schecked1 = "";
							$schecked2 = "checked";
							$display   = "block";
						}
						?>
						<div style="padding-left:35px;">
							<input type="radio" name="fstate" id="fstate" value="0" onClick="javascript:showDiv(0,'state_div');"  <?php echo $schecked1;?> /> <?php echo JText::_('OS_CREATE_NEW_STATE')?>
							<BR />
							<input type="radio" name="fstate" id="fstate" value="1" onClick="javascript:showDiv(1,'state_div');"  <?php echo $schecked2;?> /> <?php echo JText::_('OS_SELECT_DEFAULT_STATE')?>
							<div style="display:<?php echo $display;?>;" id="state_div">
								<?php echo OspropertyCsvform::getStateInput($row->state);?>
							</div>
						</div>
						<BR/>
						<span class="badge badge-warning">9</span> &nbsp;
						<?php echo JText::_('OS_WHEN_SYSTEM_CANNOT_FIND_CITY_PLEASE_SELECT_DEFAULT_CITY');?>:
						<?php
						$display   = "none";
						if($row->fcity == 0){
							$tchecked1 = "checked";
							$tchecked2 = "";
							$display   = "none";
						}else{
							$tchecked1 = "";
							$tchecked2 = "checked";
							$display   = "block";
						}
						?>
						<div style="padding-left:35px;">
							<input type="radio" name="fcity" id="fcity" value="0" onClick="javascript:showDiv(0,'city_div');" <?php echo $tchecked1;?> /> <?php echo JText::_('OS_CREATE_NEW_CITY')?>
							<BR />
							<input type="radio" name="fcity" id="fcity" value="1" onClick="javascript:showDiv(1,'city_div');" <?php echo $tchecked2;?>/> <?php echo JText::_('OS_SELECT_DEFAULT_CITY')?>
							<div style="display:<?php echo $display;?>;" id="city_div">
								<?php echo OspropertyCsvform::getCityInput($row->city);?>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php
						echo JText::_('OS_PUBLISHED');
					?>
				</td>
				<td>
					<?php echo $lists['published'];?>
				</td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding:10px;background-color:#FCEAF3;border:1px solid #FED2E8;">
					<div style="width:100%">
						<div style="float:left;margin-right:10px;">
							<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/notice.png">
						</div>
						<?php
						echo JText::_('OS_CSV_FORM_EXPLAIN');
						?>
					</div>
				</td>
			</tr>
		</table>
		<BR>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding:10px;background-color:#F6F5C7;border:1px solid #E5E47E;">
					<b>
						<?php
							echo JText::_('OS_REQUIRED_FIELDS').": ";
						?>
					</b>
					<div id="required_div" >
						
					</div>
				</td>
			</tr>
		</table>
		
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<?php
				$j = 0;
				$fieldArr = array();
				for($i=1;$i<=50;$i++){
					$j++;
					?>
					<td width="20%" style="padding:10px;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="40%" style="padding:2px;">
									<?php echo JText::_('OS_COLUMN')?> <?php echo $i;?>:
								</td>
								<td width="60%" style="padding:2px;text-align:left;">
									<?php
									$db->setQuery("Select `field` from #__osrs_form_fields where form_id = '$row->id' and column_number = '$i'");
									$fieldvalue = $db->loadResult();
									$fieldArr[] = $fieldvalue;
									echo JHTML::_('select.genericlist',$optionArr,'fields'.$i,'onChange="javascript:updateSelectList('.$i.')" style="width:130px;" class="inputbox"','value','text',$fieldvalue);
									?>
								</td>
							</tr>
							<tr>
								<td width="40%" style="padding:2px;">
									<?php echo JText::_('OS_HEADER')?>:
								</td>
								<td width="60%" style="padding:2px;text-align:left;">
									<?php
									$header_text = "";
									$db->setQuery("Select `header_text` from #__osrs_form_fields where form_id = '$row->id' and column_number = '$i'");
									$header_text = $db->loadResult();
									?>
									<input type="text" class="input-small" style="width:120px;" name="header<?php echo $i?>" value="<?php echo $header_text?>">
								</td>
							</tr>
						</table>
					</td>
					<?php
					if($j==5){
						$j = 0;
						echo "</tr><tr>";
					}
				}
				?>
			</tr>
		</table>
		<div style="display:none;">
		<?php
		echo JHTML::_('select.genericlist',$optionArr1,'selected_field[]','multiple','value','text');
		?>
		</div>
		<select name="require_labels[]" id="require_labels" style="display:none;" multiple>
		<?php
		for($i=0;$i<count($requirelabelArr);$i++){
			if(in_array($requirefieldArr[$i],$fieldArr)){
				$selected = "selected";
			}else{
				$selected = "";
			}
			?>
			<option value="<?php echo $requirefieldArr[$i]?>" <?php echo $selected?>><?php echo $requirelabelArr[$i]?></option>
			<?php
		}
		?>
		</select>
		<script language="javascript">
		function loadRequireFields(){
			var require_labels = document.getElementById('require_labels');
			var required_div = document.getElementById('required_div');
			if(require_labels.options.length > 0){
				var rqs = "";
				for(i=0;i<require_labels.options.length;i++){
					if(require_labels.options[i].selected == false){
						rqs += require_labels.options[i].text + ", ";
					}
				}
				rqs = rqs.substring(0,rqs.length - 2);
				required_div.innerHTML = rqs;
			}
		}
		loadRequireFields();
		</script>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		</form>
		<?php
	}
}
?>