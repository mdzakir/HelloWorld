<?php
/*------------------------------------------------------------------------
# extrafield.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OspropertyExtrafield{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function extrafield_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_EXTRA_FIELDS'),"logo48.png");
		JToolBarHelper::addNew('extrafield_add');
		JToolBarHelper::editList('extrafield_edit');
		JToolBarHelper::deleteList('OS_ARE_YOU_SURE_TO_REMOVE_ITEM','extrafield_remove');
		JToolBarHelper::publish('extrafield_publish');
		JToolBarHelper::unpublish('extrafield_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=extrafield_list" name="adminForm" id="adminForm">
		<table  width="100%">
			<tr>
				<td width="50%" align="left">
                    <DIV class="btn-wrapper input-append">
                        <input type="text" name="keyword" placeholder="<?php echo JText::_('OS_SEARCH');?>" value="<?php echo JRequest::getVar('keyword','')?>" class="input-medium" />
                        <button class="btn hasTooltip" title="" type="submit" data-original-title="<?php echo Jtext::_('OS_SEARCH');?>">
                            <i class="icon-search"></i>
                        </button>
                    </DIV>
				</td>
                <td width="50%">
                    <div class="btn-wrapper">
                        <?php
                        echo $lists['type'];
                        ?>
                    </div>
                    <div class="btn-wrapper">
                        <?php
                        echo $lists['group'];
                        ?>
                    </div>
                    <div class="btn-group">
                        <?php
                        echo $lists['fieldtype'];
                        ?>
                    </div>
				</td>
			</tr>
		</table>
        <?php
        if(count($rows) > 0) {
        ?>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="2%">
				
					</th>
					<th width="3%" style="text-align:center;">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="13%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_FIELD_TITLE'), 'a.field_label', @$lists['order_Dir'], @$lists['order'],'extrafield_list' ); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_FIELD_GROUP'), 'b.group_name', @$lists['order_Dir'], @$lists['order'],'extrafield_list' ); ?>
					</th>
					<th width="15%">
						<?php echo JText::_('OS_PROPERTY_TYPE');?>
					</th>
					<th width="5%">
						<?php echo JText::_('OS_ACCESS')?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_FIELD_NAME'), 'a.field_name', @$lists['order_Dir'], @$lists['order'],'extrafield_list' ); ?>
					</th>
					
					<th width="7%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_FIELD_TYPE'), 'a.field_type', @$lists['order_Dir'], @$lists['order'],'extrafield_list' ); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JText::_('OS_REQUIRED')?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JText::_('OS_SEARCHABLE')?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JText::_('OS_READONLY')?>
					</th>
					<th width="7%" style="text-align:center;">
						<?php echo JText::_('OS_SHOW_ON_LIST');?>
					</th>
					<th width="15%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_ORDERING'), 'a.ordering', @$lists['order_Dir'], @$lists['order'],'extrafield_list' ); ?>
						<?php echo JHTML::_('grid.order',  $rows ,"filesave.png","extrafield_saveorder"); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JText::_('OS_PUBLISH')?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="15" style="text-align:center;">
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
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=extrafield_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i , 'extrafield_');
				
				switch ($row->field_type){
					case "radio":
					case "singleselect":
					case "multipleselect":
					case "checkbox":
						$db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$row->id'");
						$count = $db->loadResult();
						if(($count == 0) and ($row->options != "")){
							HelperOspropertyFields::saveNewOption($row->options,$row->id);
						}
					break;
				}
				
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $checked; ?>
					</td>
					<td align="left">
						<a href="<?php echo $link?>" title="Edit">
						<?php
							echo $row->field_label;
						?>
						<a href="<?php echo $link?>" title="Edit">
					</td>
					<td align="left">
						<?php
							echo $row->group_name;
						?>
					</td>
					<td align="left">
						<?php
							echo $row->typeLists;
						?>
					</td>
					<td align="left">
						<?php
							echo HelperOspropertyCommon::loadAccessName($row->access);
						?>
					</td>
					<td align="left">
						<a href="<?php echo $link?>" title="Edit">
						<?php
							echo $row->field_name;
						?>
						</a>
					</td>
					<td align="center">
						<?php
						if($row->field_type == "text"){
							switch ($row->value_type){
								case "1":
									echo JText::_('OS_INTEGER');
								break;
								case "2":
									echo JText::_('OS_DECIMAL');
								break;
								default:
									echo $row->field_type;
								break;
							}
						}else{
							echo $row->field_type;
						}
						?>
					</td>
					<td align="center"  style="text-align:center;">
						<?php
							if($row->required == 1){
								?>
								<a href="index.php?option=com_osproperty&task=extrafield_changeType&type=required&cid[]=<?php echo $row->id?>&v=0" title="Change required status">
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/tick.png">
								</a>
								<?php
							}else{
								?>
								<a href="index.php?option=com_osproperty&task=extrafield_changeType&type=required&cid[]=<?php echo $row->id?>&v=1" title="Change required status">
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/publish_x.png">
								</a>
								<?php
							}
						?>
					</td>
					<td align="center" style="text-align:center;">
						<?php
							if($row->searchable == 1){
								?>
								<a href="index.php?option=com_osproperty&task=extrafield_changeType&type=searchable&cid[]=<?php echo $row->id?>&v=0" title="Change searchable status">
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/tick.png">
								</a>
								<?php
							}else{
								?>
								<a href="index.php?option=com_osproperty&task=extrafield_changeType&type=searchable&cid[]=<?php echo $row->id?>&v=1" title="Change searchable status">
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/publish_x.png">
								</a>
								<?php
							}
						?>
					</td>
					<td align="center" style="text-align:center;">
						<?php
							if($row->readonly == 1){
								?>
								<a href="index.php?option=com_osproperty&task=extrafield_changeType&type=readonly&cid[]=<?php echo $row->id?>&v=0" title="Change readonly status">
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/tick.png">
								</a>
								<?php
							}else{
								?>
								<a href="index.php?option=com_osproperty&task=extrafield_changeType&type=readonly&cid[]=<?php echo $row->id?>&v=1" title="Change readonly status">
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/publish_x.png">
								</a>
								<?php
							}
						?>
					</td>
					<td align="center" style="text-align:center;">
						<?php
							if($row->show_on_list == 1){
								?>
								<a href="index.php?option=com_osproperty&task=extrafield_changeType&type=show_on_list&cid[]=<?php echo $row->id?>&v=0" title="Change on list status">
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/tick.png">
								</a>
								<?php
							}else{
								?>
								<a href="index.php?option=com_osproperty&task=extrafield_changeType&type=show_on_list&cid[]=<?php echo $row->id?>&v=1" title="Change on list status">
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/publish_x.png">
								</a>
								<?php
							}
						?>
					</td>
					<td align="center" class="order">
						<?php
						$ordering = "ordering";
						?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="input-mini" style="text-align: center;width:30px;" />
						<span><?php echo $pageNav->orderUpIcon($i,$row->group_id == 0 || $row->group_id == @$rows[$i-1]->group_id, 'extrafield_orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $pageNav->orderDownIcon($i, $n,  $row->group_id == 0 || $row->group_id == @$rows[$i+1]->group_id, 'extrafield_orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $published?>
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
		<input type="hidden" name="option" value="com_osproperty" /> 
		<input type="hidden" name="task" value="extrafield_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>">
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>">
		</form>
		<?php
	}
	
	
	/**
	 * Edit Extra field
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editHTML($option,$row,$lists,$translatable){
		global $mainframe,$configClass,$languages;
		
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."components/com_osproperty/js/ajax.js");
		JHTML::_('behavior.formvalidation');
		$functionvalue = "Joomla.submitbutton = function(task)";
		$submitvalue = " Joomla.submitform(task);return true; ";
		
		JHtml::_('behavior.tooltip');
	
		if($row->id > 0){
			$edit = JText::_('OS_EDIT');
		}else{
			$edit = JText::_('OS_NEW');
		}
		JToolBarHelper::title(JText::_('OS_EXTRA_FIELD')." ".JText::_('['.$edit.']'));
		JToolBarHelper::save('extrafield_save');
		JToolBarHelper::apply('extrafield_apply');
		JToolBarHelper::cancel('extrafield_gotolist');
		?>
		<script language="javascript">
		function showDiv(){
			var div_select     = document.getElementById('select');
			var div_checkbox   = document.getElementById('checkbox');
			var div_textarea   = document.getElementById('textarea');
			var div_inputbox   = document.getElementById('inputbox');
			div_select.style.display   = "none";
			div_checkbox.style.display = "none";
			div_textarea.style.display = "none";
			div_inputbox.style.display = "none";
			
			var field_type = document.getElementById('field_type');
			if((field_type.value == "text") || (field_type.value == "date")){
				div_inputbox.style.display = "block";
			}else if((field_type.value == "checkbox") || (field_type.value == "radio")){
				div_checkbox.style.display = "block";
			}else if((field_type.value == "singleselect") || (field_type.value == "multipleselect")){
				div_select.style.display = "block";
			}else if(field_type.value == "textarea"){
				div_textarea.style.display = "block";
			}
		}
		
		<?php echo $functionvalue?>{
			var form 		 = document.adminForm;
			group_id 		 = form.group_id;
			field_name  	 = form.field_name;
			field_label 	 = form.field_label;
			field_type  	 = form.field_type;
			type_id			 = document.getElementById('type_id');
			
			if((task == "extrafield_save") || (task == "extrafield_apply")){
				if(group_id.value == ""){
					alert("Please select group name");
					group_id.focus();
				}else if (field_name.value == ""){
					alert("Please enter field name");
					field_name.focus();
				}else if( ! multiselect_validate(type_id)){
					alert("Please select property types");
				}else{
					<?php echo $submitvalue?>
				}
			}else{
				<?php echo $submitvalue?>
			}
		}
		
		function saveOption(fid,div_name,type){
			var str_value = "";
			var div = document.getElementById('div_name');
			div.value = div_name;
			var live_site = document.getElementById('live_site');
			live_site = live_site.value;
			var languages = document.getElementById('languages');
			languages = languages.value;
			languages = languages.split("|");
			if(languages.length > 0){
				for(i=0;i<languages.length;i++){
					lng = languages[i];
					var option_name = document.getElementById('option_name_' + lng + type);
					value = option_name.value;
					value = value.replace("+","@plus@");
					str_value += lng + "@@" + value + "||";
					option_name.value = "";
				}
			}
			//alert(str_value);
			str_value = str_value.substring(0,str_value.length-2);
			saveOptionAjax(fid,str_value,live_site,div_name,type);
		}
		
		function removeOption(oid,fid,div_name,type){
			var div = document.getElementById('div_name');
			div.value = div_name;
			var live_site = document.getElementById('live_site');
			live_site = live_site.value;
			var answer = confirm("<?php echo JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM')?>");
			if(answer == 1){
				removeOptionAjax(oid,fid,live_site,div_name,type);
			}
		}
		
		function saveChange(oid,fid,div_name,type){
			var str_value = "";
			var div = document.getElementById('div_name');
			div.value = div_name;
			var live_site = document.getElementById('live_site');
			live_site = live_site.value;
			var languages = document.getElementById('languages');
			languages = languages.value;
			languages = languages.split("|");
			if(languages.length > 0){
				for(i=0;i<languages.length;i++){
					lng = languages[i];
					var option_name = document.getElementById('option_' + lng + oid + type);
					value = option_name.value;
					value = value.replace("+","@plus@");
					str_value += lng + "@@" + value + "||";
					option_name.value = "";
				}
			}
			str_value = str_value.substring(0,str_value.length-2);
			var ordering	= document.getElementById('ordering_' + oid + type);
			ordering = ordering.value;
			saveChangeOptionAjax(oid,str_value,ordering,fid,live_site,div_name,type);
		}

		function multiselect_validate(select) {  
			var valid = false;  
			for(var i = 0; i < select.options.length; i++) {  
			    if(select.options[i].selected) {  
					valid = true;  
					break;
			    }  
			}  
			return valid;  
		}
		</script>
		<form class="form-validate" method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm">
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
				<table  width="100%" class="admintable" style="background-color:white;">
					<tr>
						<td class="key">
							<?php echo JText::_('OS_TYPE')?>
						</td>
						<td>
							<?php
								echo $lists['field_type'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_FIELD_GROUP')?>
						</td>
						<td>
							<?php
								echo $lists['group'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key" valign="top">
							<?php echo JText::_('OS_PROPERTY_TYPE')?>
						</td>
						<td>
							<?php
								echo $lists['type'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_FIELD_NAME')?>
						</td>
						<td>
							<input type="text" name="field_name" id="field_name" size="40" value="<?php echo $row->field_name?>" />
                            &nbsp;*<?php echo JText::_('OS_FIELD_NAME_EXPLAIN');?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_FIELD_TITLE')?>
						</td>
						<td>
							<input type="text" name="field_label" id="field_label" size="60" value="<?php echo $row->field_label?>">
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_REQUIRED')?>
						</td>
						<td>
							<?php
							echo $lists['required'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_READONLY')?>
						</td>
						<td>
							<?php
							echo $lists['readonly'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_SEARCHABLE')?>
						</td>
						<td>
							<?php
							echo $lists['searchable'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_DISPLAY');?>
						</td>
						<td>
							<?php
							echo $lists['displaytitle'];
							?>
                            &nbsp;*<?php echo JText::_('OS_DISPLAY_EXPLAIN');?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_SHOW_ON_LIST_PROPERTIES')?>
						</td>
						<td>
							<?php
							echo $lists['show_on_list'];
							?>
                            &nbsp;*<?php echo JText::_('OS_SHOW_ON_LIST_EXPLAIN');?>
						</td>
					</tr>
					<tr>
						<td class="key" valign="top">
							<?php echo JText::_('OS_DESCRIPTION')?>
						</td>
						<td>
							<textarea name="field_description" id="field_description" cols="50" rows="3"><?php echo $row->field_description?></textarea>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_SHOW_DESCRIPTION')?>
						</td>
						<td>
							<?php
							echo $lists['show_description'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_DEFAULT_VALUE')?>
						</td>
						<td>
							<input type="text" name="default_value" id="default_value" size="60" value="<?php echo $row->default_value?>">
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_ACCESS')?>
						</td>
						<td>
							<?php
							echo $lists['access'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('OS_PUBLISHED')?>
						</td>
						<td>
							<?php
							echo $lists['state'];
							?>
						</td>
					</tr>
					<tr>
						<td class="key" valign="top">
							<?php echo JText::_('OS_OTHER_INFORMATION')?>
						</td>
						<td>
							<table  width="90%">
								<tr>
									<td valign="top" width="100%">
										<?php
										switch ($row->field_type){
											case "singleselect":
											case "multipleselect":
												$div_select    = "block";
												$div_checbox   = "none";
												$div_textarea  = "none";
												$div_inputbox  = "none";
												$div_date	   = "none";
											break;
											case "checkbox":
											case "radio":
												$div_select    = "none";
												$div_checbox   = "block";
												$div_textarea  = "none";
												$div_inputbox  = "none";
												$div_date	   = "none";
											break;
											default:
											case "date":
												$div_select    = "none";
												$div_checbox   = "none";
												$div_textarea  = "none";
												$div_inputbox  = "none";
												$div_date	   = "block";
											break;
											case "text":
												$div_select    = "none";
												$div_checbox   = "none";
												$div_textarea  = "none";
												$div_inputbox  = "block";
												$div_date	   = "none";
											break;
											case "textarea":
												$div_select    = "none";
												$div_checbox   = "none";
												$div_textarea  = "block";
												$div_inputbox  = "none";
												$div_date	   = "none";
											break;
										}
										?>
										<!-- For select tags -->
										<div style="width:100%;font-size:12px;display:<?php echo $div_select?>;" id="select">
											<table  width="100%" class="admintable">
												<tr>
													<td style="padding:3px;border-bottom:1px solid #efefef;font-weight:bold;color:#519DC5;background-color:#DAECF5;" align="left" colspan="2">
														
														<?php echo JText::_('OS_SINGLE_SELECT_MULTIPLE_SELECT')?>
													</td>
												</tr>
												<tr>
													<td class="key">
														<?php echo JText::_('OS_SIZE')?>
													</td>
													<td>
														<input type="text" name="select_size" id="select_size" size="10" value="<?php echo $row->size?>" class="input-mini" /> px
													</td>
												</tr>
												<tr>
													<td class="key" valign="top">
														
														<span class="editlinktip hasTip" title="Options::Please enter options value of the tag. Each option in one line.">
														<?php echo JText::_('OS_OPTIONS')?></span>
													</td>
													<td style="font-size:11px !important;">
														<?php
														if($row->id == 0){
														?>
														<textarea name="select_options" id="select_options" cols="40" rows="7"><?php echo $row->options;?></textarea>
														<?php
														}else{
															?>
															<div id="option_div">
															<?php
															HelperOspropertyFields::manageFieldOptions($row->id,'option_div',0);
															?>
															</div>
															<?php
														}
														?>
													</td>
												</tr>
											</table>
										</div>
										<!-- For checkbox and radio tags -->
										<div style="width:100%;font-size:12px;display:<?php echo $div_checbox?>;" id="checkbox">
											<table  width="100%" class="admintable">
												<tr>
													<td style="padding:3px;border-bottom:1px solid #efefef;font-weight:bold;color:#519DC5;background-color:#DAECF5;" align="left" colspan="2">
														<?php echo JText::_('OS_CHECKBOX_RADIO')?>
														
													</td>
												</tr>
												<tr>
													<td class="key" valign="top">
														
														<span class="editlinktip hasTip" title="Options::Please enter options value of the tag. Each option in one line.">
														<?php echo JText::_('OS_OPTIONS')?></span>
													</td>
													<td style="font-size:11px !important;">
														<?php
														if($row->id == 0){
														?>
														<textarea name="checkbox_options" id="checkbox_options" cols="40" rows="7"><?php echo $row->options;?></textarea>
														<?php
														}else{
															?>
															<div id="option_div1">
															<?php
															HelperOspropertyFields::manageFieldOptions($row->id,'option_div1',1);
															?>
															</div>
															<?php
														}
														?>
													</td>
												</tr>
											</table>
										</div>
										<!-- For textarea tags -->
										<div style="width:100%;font-size:12px;display:<?php echo $div_textarea?>;" id="textarea">
											<table  width="100%" class="admintable">
												<tr>
													<td style="padding:3px;border-bottom:1px solid #efefef;font-weight:bold;color:#519DC5;background-color:#DAECF5;" align="left" colspan="2">
														<?php echo JText::_('OS_TEXTAREA')?>
														
													</td>
												</tr>
												<tr>
													<td class="key" valign="top">
													 	<?php echo JText::_('OS_NUMBER_COLUMNS')?>
													</td>
													<td>
														<input type="text" name="ncols" id="ncols" value="<?php echo $row->ncols?>" size="10">
													</td>
												</tr>
												<tr>
													<td class="key" valign="top">
													 	<?php echo JText::_('OS_NUMBER_ROWS')?>
													</td>
													<td>
														<input type="text" name="nrows" id="nrows" value="<?php echo $row->nrows?>" size="10">
													</td>
												</tr>
											</table>
										</div>
										<!-- For other fields -->
										<div style="width:100%;font-size:12px;display:<?php echo $div_inputbox?>;" id="inputbox">
											<table  width="100%" class="admintable">
											
												<tr>
													<td style="padding:3px;border-bottom:1px solid #efefef;font-weight:bold;color:#519DC5;background-color:#DAECF5;" align="left" colspan="2">
														<?php echo JText::_('OS_TEXTBOX_DATE_FIELD')?>
													</td>
												</tr>
												<tr>
													<td class="key" valign="top">
													 	<?php echo JText::_('OS_VALUE_TYPE');?>
													</td>
													<td>
														<?php
														$valueType = array();
														$valueType[] = JHTML::_('select.option','0',JText::_('OS_TEXT'));
														$valueType[] = JHTML::_('select.option','1',JText::_('OS_INTEGER'));
														$valueType[] = JHTML::_('select.option','2',JText::_('OS_DECIMAL'));
														echo JHTML::_('select.genericlist',$valueType,'value_type','class="input-small"','value','text',$row->value_type);
														?>
													</td>
												</tr>
												<tr>
													<td class="key" valign="top">
													 	<?php echo JText::_('OS_SIZE')?>
													</td>
													<td>
														<input type="text" name="text_size" id="text_size" value="<?php echo $row->size?>" size="10" class="input-mini">
													</td>
												</tr>
												<tr>
													<td class="key" valign="top">
													 	<?php echo JText::_('OS_MAXLENGTH')?>
													</td>
													<td>
														<input type="text" name="maxlength" id="maxlength" value="<?php echo $row->maxlength?>" size="10" class="input-mini">
													</td>
												</tr>
											</table>
										</div>
										<div style="width:100%;font-size:12px;display:<?php echo $div_date?>;" id="">
											<?php echo JText::_('OS_DATEFIELD_INFORMATION');?>
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
										<?php echo JText::_('OS_FIELD_TITLE')?>
									</td>
									<td>
										<input type="text" name="field_label_<?php echo $sef;?>" id="field_label_<?php echo $sef;?>" size="60" value="<?php echo $row->{'field_label_'.$sef}?>">
									</td>
								</tr>
								<tr>
									<td class="key" valign="top">
										<?php echo JText::_('OS_DESCRIPTION')?>
									</td>
									<td>
										<textarea name="field_description_<?php echo $sef;?>" id="field_description_<?php echo $sef;?>" cols="50" rows="3"><?php echo $row->{'field_description_'.$sef}?></textarea>
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
		<input type="hidden" name="div_name" id="div_name" value="">
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>">
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="0">
		<input type="hidden" name="id" value="<?php echo $row->id?>">
		</form>
		<?php
	}
}
?>