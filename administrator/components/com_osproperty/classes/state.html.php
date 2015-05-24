<?php
/*------------------------------------------------------------------------
# state.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OspropertyState{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function state_list($option,$rows,$pageNav,$lists,$modal,$keyword){
		global $mainframe,$_jversion;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_STATES'),"logo48.png");
		JToolBarHelper::addNew('state_add');
		JToolBarHelper::editList('state_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'state_remove');
		JToolBarHelper::publish('state_publish');
		JToolBarHelper::unpublish('state_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=state_list" name="adminForm" id="adminForm">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
						<?php echo JText::_("OS_FILTER")?>: &nbsp;
					<input type="text" name="keyword" value="<?php echo $keyword;?>" class="input-medium search-query"/><input type="submit" class="btn btn-primary" value="<?php echo JText::_('OS_SUBMIT')?>" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
						<?php echo JText::_("OS_COUNTRY")?>: &nbsp;
					
					<?php echo $lists['country']; ?>
				</td>
			</tr>
		</table>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="2%">
				
					</th>
					<th width="3%" style="text-align:center;">
						
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th >
						<?php echo JHTML::_('grid.sort',   JText::_('OS_STATE'), 'state_name', @$lists['order_Dir'], @$lists['order'] ,'state_list'); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_STATE_CODE'), 'state_code', @$lists['order_Dir'], @$lists['order'] ,'state_list'); ?>
					</th>
					<th >
						<?php echo JHTML::_('grid.sort',   JText::_('OS_COUNTRY'), 'country_name', @$lists['order_Dir'], @$lists['order'] ,'state_list'); ?>
					</th>
					<?php
					if($modal == 0){
					?>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISH'), 'published', @$lists['order_Dir'], @$lists['order'] ,'state_list'); ?>
					</th>
					<?php
					}
					?>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('ID'), 'id', @$lists['order_Dir'], @$lists['order'] ,'state_list'); ?>
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
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=state_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i, 'state_');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $checked; ?>
					</td>
					<td align="left">
					<?php
					if($modal == 0){
					?>
						<a href="<?php echo $link; ?>">
					<?php
					}else{
					?>
						<a class="pointer" onclick="if (window.parent) window.parent.jSelectState_state('<?php echo $row->id?>', '<?php echo $row->state_name; ?>');">
					<?php
					}
					?>
							<?php echo $row->state_name; ?>
						</a>
					</td>
					<td align="left" style="padding-left: 10px;">
						<?php echo $row->state_code?>
					</td>
					<td align="left">
						<?php echo $row->country_name?>
					</td>
					<?php
					if($modal == 0){
					?>
						<td align="center" style="text-align:center;">
							<?php echo $published;?>
						</td>
					<?php
					}
					?>
					<td style="text-align:center;">
						<?php echo $row->id;?>
					</td>
				</tr>
			<?php
				$k = 1 - $k;	
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="state_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>"  />
		<input type="hidden" name="modal" value="<?php echo $modal?>" />
		<?php
		if($modal == 1){
		?>
		<input type="hidden" name="tmpl" id="tmpl" value="component" />
		<?php
		}
		?>
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
		global $mainframe,$languages;
		JRequest::setVar( 'hidemainmenu', 1 );
		$db = JFactory::getDBO();
		JHtml::_('behavior.tooltip');
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('State').$title);
		JToolBarHelper::save('state_save');
		JToolBarHelper::apply('state_apply');
		JToolBarHelper::cancel('state_cancel');
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
			<table cellpadding="0" cellspacing="0" width="100%" class="admintable" style="background-color:white;">
				<tr>
					<td class="key">
						<?php echo JText::_('OS_COUNTRY'); ?>
					</td>
					<td>
						<?php echo $lists['country_id']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_STATE'); ?>
					</td>
					<td>
						<input type="text" name="state_name" id="state_name" size="40" value="<?php echo $row->state_name?>">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_STATE_CODE')?>
					</td>
					<td>
						<input type="text" name="state_code" id="state_code" size="40" value="<?php echo $row->state_code?>">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_PUBLISH')?>
					</td>
					<td>
						<?php echo $lists['published'];?>
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
											<?php echo JText::_('OS_STATE'); ?>
										</td>
										<td>
											<input type="text" name="state_name_<?php echo $sef; ?>" id="state_name_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'state_name_'.$sef}?>">
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
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		</form>
		<script type="text/javascript">
		<?php if ($_jversion == "1.5"){?>
			function submitbutton(pressbutton)
		<?php }else{?>
			Joomla.submitbutton = function(pressbutton)
		<?php }?>
			{
				form = document.adminForm;
				if (pressbutton == 'state_cancel'){
					submitform( pressbutton );
					return;
				}else if (form.state_name.value == ''){
					alert('<?php echo JText::_('OS_PLEASE_ENTER_STATE'); ?>');
					form.state_name.focus();
					return;
				}else if (form.country_id.value == '0'){
					alert('<?php echo JText::_('OS_PLEASE_SELECT_COUNTRY'); ?>');
					form.country_id.focus();
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