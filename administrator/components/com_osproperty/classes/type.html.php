<?php
/*------------------------------------------------------------------------
# type.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OspropertyType{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function type_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion;
		
		JHtml::_('behavior.multiselect');
		
		JToolBarHelper::title(JText::_('OS_MANAGE_PROPERTY_TYPE'),"logo48.png");
		JToolBarHelper::addNew('type_add');
		JToolBarHelper::editList('type_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'type_remove');
		JToolBarHelper::publish('type_publish');
		JToolBarHelper::unpublish('type_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=type_list" name="adminForm" id="adminForm">
		<table  width="100%">
			<tr>
				<td width="100%">
                    <DIV class="btn-wrapper input-append">
                        <input type="text" name="keyword" placeholder="<?php echo JText::_('OS_SEARCH');?>" value="<?php echo JRequest::getVar('keyword','')?>" class="input-medium" />
                        <button class="btn hasTooltip" title="" type="submit" data-original-title="<?php echo Jtext::_('OS_SEARCH');?>">
                            <i class="icon-search"></i>
                        </button>
                    </DIV>
				</td>
			</tr>
		</table>
        <?php
        if(count($rows) > 0) {
        ?>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="5%">
				
					</th>
					<th width="5%" style="text-align:center;">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						
					</th>
					<th width="40%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_TYPE_NAME'), 'type_name', @$lists['order_Dir'], @$lists['order'] ,'type_list'); ?>
					</th>
					<th width="15%" style="text-align:center;">
						<?php echo JText::_('OS_ICON');?>
					</th>
					<th width="10%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISH'), 'published', @$lists['order_Dir'], @$lists['order'] ,'type_list'); ?>
					</th>
					<th width="15%" style="text-align:center;">
						
						<?php echo JHTML::_('grid.sort',   'Ordering', 'ordering', @$lists['order_Dir'], @$lists['order'],'type_list' ); ?>
						<?php echo JHTML::_('grid.order',  $rows ,"filesave.png","type_saveorder"); ?>
					</th>
					<th width="10%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('ID'), 'id', @$lists['order_Dir'], @$lists['order'] ,'type_list'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="7" style="text-align:center;">
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
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=type_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i, 'type_');
				
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center" style="text-align:center;">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $checked; ?>
					</td>
					<td align="left" style="padding-left: 10px;">
						<a href="<?php echo $link; ?>">
							<?php echo $row->type_name; ?>
						</a>
						<BR />
						(<?php echo JText::_('OS_ALIAS')?>: <?php echo $row->type_alias;?>)
					</td>
					<td align="center" style="text-align:center;"> 
						<?php 
						if($row->type_icon == ""){
							?>
							<img src="<?php echo JUri::root()?>components/com_osproperty/images/assets/googlemapicons/1.png" />
							<?php 
						}else{
							?>
							<img src="<?php echo JUri::root()?>components/com_osproperty/images/assets/googlemapicons/<?php echo $row->type_icon;?>" />
							<?php 
						}
						?>
					</td>
					<td align="center" style="text-align:center;"> 
						<?php echo $published?>
					</td>
					<td align="center" class="order" style="text-align:center;">
						<?php
						$ordering = $lists['order'];
				 		?>
			 			<span><?php echo $pageNav->orderUpIcon($i,$row->ordering != 0, 'type_orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $pageNav->orderDownIcon($i, $n,  $row->ordering != 0 , 'type_orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<input type="text" name="order[]" <?php echo ($lists['order'] == "ordering") ? "": "readonly";?> value="<?php echo $row->ordering; ?>" class="text-area-order input-mini" style="text-align: center;width:40px;" />
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $row->id?>
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
		<input type="hidden" name="task" value="type_list">
		<input type="hidden" name="boxchecked" value="0">
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
		global $mainframe,$languages,$configClass;
		JRequest::setVar( 'hidemainmenu', 1 );
		$db = JFactory::getDBO();
		JHtml::_('behavior.tooltip');
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('Type').$title);
		JToolBarHelper::save('type_save');
		JToolBarHelper::apply('type_apply');
		JToolBarHelper::cancel('type_cancel');
		
		$editor =& JFactory::getEditor();
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
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
						<?php echo JText::_('OS_PROPERTY_TYPE_NAME'); ?>
					</td>
					<td>
						<input type="text" name="type_name" id="type_name" size="40" value="<?php echo $row->type_name?>">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_ALIAS'); ?>
					</td>
					<td>
						<input type="text" name="type_alias" id="type_alias" size="40" value="<?php echo $row->type_alias;?>">
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
				<?php
				if(file_exists(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."oscalendar.php")){
					if($configClass['integrate_oscalendar'] == 1){
						?>
						<tr>
							<td class="key" valign="top">
								<?php echo JText::_('OS_PRICE_TYPE')?>
							</td>
							<td>
								<?php echo $lists['price_type']?> 
								<BR />
								<?php echo JText::_('OS_PRICE_TYPE_EXPLAIN');?>
								<BR />
								<?php echo JText::_('OS_PRICE_TYPE_EXPLAIN1');?>
							</td>
						</tr>
						<?php
					}
				}
				?>
				<tr>
					<td class="key" valign="top">
						<?php echo JText::_('OS_ICON')?>
					</td>
					<td>
						<?php 
						if($row->type_icon == ""){
							$row->type_icon = "1.png";
						}
						$k = 0;
						for($i=1;$i<=20;$i++){
							$k++;
							if($row->type_icon == $i.".png"){
								$selected = "checked";
							}else{
								$selected = "";
							}
							?>
							<input type="radio" name="type_icon" value="<?php echo $i.".png"?>" <?php echo $selected?> />
							<img src="<?php echo JUri::root()?>components/com_osproperty/images/assets/googlemapicons/<?php echo $i?>.png" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php 
							if($k == 10){
								echo "<BR /><BR />";
								$k = 0;
							}
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_DESCRIPTION')?>
					</td>
					<td>
						<?php
						// parameters : areaname, content, width, height, cols, rows, show xtd buttons
						echo $editor->display( 'type_description',  htmlspecialchars($row->type_description, ENT_QUOTES), '550', '300', '60', '20', false ) ;
						?>
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
											<?php echo JText::_('OS_PROPERTY_TYPE_NAME'); ?>
										</td>
										<td>
											<input type="text" name="type_name_<?php echo $sef;?>" id="type_name_<?php echo $sef;?>" size="40" value="<?php echo $row->{'type_name_'.$sef};?>">
										</td>
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('OS_ALIAS'); ?>
										</td>
										<td>
											<input type="text" name="type_alias_<?php echo $sef;?>" id="type_alias_<?php echo $sef;?>" size="40" value="<?php echo $row->{'type_alias_'.$sef};?>">
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
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="id" value="<?php echo $row->id?>">
		</form>
		<script type="text/javascript">
		<?php if ($_jversion == "1.5"){?>
			function submitbutton(pressbutton)
		<?php }else{?>
			Joomla.submitbutton = function(pressbutton)
		<?php }?>
			{
				form = document.adminForm;
				if (pressbutton == 'type_cancel'){
					submitform( pressbutton );
					return;
				}else if (form.type_name.value == ''){
					alert('<?php echo JText::_('OS_PLEASE_ENTER_PROPERTY_TYPE_NAME'); ?>');
					return;
				}else{
					submitform( pressbutton );
					return;
				}
			}
		</script>
		<?php
	}
}
?>