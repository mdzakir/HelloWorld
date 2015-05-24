<?php
/*------------------------------------------------------------------------
# amenities.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OspropertyAmenities{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function amenities_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_CONVENIENCE'),"logo48.png");
		JToolBarHelper::addNew('amenities_add');
		JToolBarHelper::editList('amenities_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'amenities_remove');
		JToolBarHelper::publish('amenities_publish');
		JToolBarHelper::unpublish('amenities_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=amenities_list" name="adminForm" id="adminForm">
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
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="2%">
				
					</th>
					<th width="3%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="40%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_AMENITY_NAME'), 'amenities', @$lists['order_Dir'], @$lists['order'] ,'amenities_list'); ?>
					</th>
					<th width="25%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_CATEGORY'), 'category_id', @$lists['order_Dir'], @$lists['order'] ,'amenities_list'); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_ORDERING'), 'ordering', @$lists['order_Dir'], @$lists['order']  ,'amenities_list'); ?>
						<?php echo JHTML::_('grid.order',  $rows ,"filesave.png","amenities_saveorder"); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISH'), 'published', @$lists['order_Dir'], @$lists['order']  ,'amenities_list'); ?>
					</th>
					<th width="5%">
						<?php echo JHTML::_('grid.sort',   JText::_('ID'), 'id', @$lists['order_Dir'], @$lists['order']  ,'amenities_list'); ?>
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
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=amenities_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i, 'amenities_');
				
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center">
						<?php echo $checked; ?>
					</td>
					<td align="left">
						<a href="<?php echo $link; ?>">
							<?php echo $row->amenities; ?>
						</a>
					</td>
					<td align="left">
						<?php echo OspropertyAmenities::returnAmenityCategory($row->category_id);?>
					</td>
					<td align="center" class="order" style="text-align:center;">
						<?php
						if($lists['order'] == "ordering"){
				 		?>
			 			<span><?php echo $pageNav->orderUpIcon($i,true, 'amenities_orderup', 'JLIB_HTML_MOVE_UP', $lists['order']); ?></span>
						<span><?php echo $pageNav->orderDownIcon($i, $n,  true, 'amenities_orderdown', 'JLIB_HTML_MOVE_DOWN', $lists['order']); ?></span>
						<?php } ?>
						<input type="text" name="order[]"  value="<?php echo $row->ordering; ?>" class="text-area-order input-mini" style="text-align: center;width:40px;" />
						
					</td>
					<td align="center">
						<?php echo $published?>
					</td>
					<td align="center">
						<?php echo $row->id;?>
					</td>
				</tr>
			<?php
				$k = 1 - $k;	
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="amenities_list">
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
		global $mainframe,$languages;
		JRequest::setVar( 'hidemainmenu', 1 );
		$db = JFactory::getDBO();
		JHtml::_('behavior.tooltip');
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('Convenience').$title);
		JToolBarHelper::save('amenities_save');
		JToolbarHelper::apply('amenities_apply');
		JToolBarHelper::cancel('amenities_cancel');
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
						<?php echo JText::_('OS_CONVENIENCE_NAME'); ?>
					</td>
					<td>
						<input type="text" name="amenities" id="amenities" size="40" value="<?php echo $row->amenities?>">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_CATEGORY'); ?>
					</td>
					<td>
						<?php 
							echo OspropertyAmenities::makeAmenityCategoryDropdown($row->category_id);
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
											<?php echo JText::_('OS_CONVENIENCE_NAME'); ?>
										</td>
										<td>
											<input type="text" name="amenities_<?php echo $sef; ?>" id="amenities_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'amenities_'.$sef}?>">
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
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<script type="text/javascript">
				Joomla.submitbutton = function(pressbutton)
				{
				form = document.adminForm;
				if (pressbutton == 'amenities_cancel'){
					submitform( pressbutton );
					return;
				}else if (form.amenities.value == ''){
					alert('<?php echo JText::_('OS_PLEASE_ENTER_AMENINTY_NAME'); ?>');
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