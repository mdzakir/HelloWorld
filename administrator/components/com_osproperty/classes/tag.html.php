<?php
/*------------------------------------------------------------------------
# tag.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class HTML_OspropertyTag{
	/**
	 * List tags
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $lists
	 */
	function listTags($option,$rows,$lists,$pageNav){
		global $mainframe,$configClass;
		JHtml::_('behavior.multiselect');
		
		JToolBarHelper::title(JText::_('OS_MANAGE_TAGS'),"logo48.png");
		JToolBarHelper::addNew('tag_add');
		JToolBarHelper::editList('tag_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'tag_remove');
		JToolBarHelper::publish('tag_publish');
		JToolBarHelper::unpublish('tag_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=tag_list" name="adminForm" id="adminForm">
		<table  width="100%">
			<tr>
				<td width="50%">
                    <DIV class="btn-wrapper input-append">
                        <input type="text" name="keyword" placeholder="<?php echo JText::_('OS_SEARCH');?>" value="<?php echo JRequest::getVar('keyword','')?>" class="input-medium" />
                        <button class="btn hasTooltip" title="" type="submit" data-original-title="<?php echo Jtext::_('OS_SEARCH');?>">
                            <i class="icon-search"></i>
                        </button>
                    </DIV>
				</td>
				<td width="50%" style="text-align:right;">
					<?php echo $lists['status'];?>
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
					<th width="35%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_TAG'), 'a.keyword', @$lists['order_Dir'], @$lists['order'] ,'tag_list'); ?>
					</th>
					<th width="35%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_ITEM'), 'count_tag', @$lists['order_Dir'], @$lists['order'] ,'tag_list'); ?>
					</th>
					<th width="10%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISH'), 'a.published', @$lists['order_Dir'], @$lists['order'] ,'tag_list'); ?>
					</th>
					<th width="10%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('ID'), 'a.id', @$lists['order_Dir'], @$lists['order'] ,'tag_list'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="6" style="text-align:center;">
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
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=tag_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i, 'tag_');
				
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $checked; ?>
					</td>
					<td align="left" style="padding-left: 10px;">
						<a href="<?php echo $link; ?>">
							<?php echo $row->keyword; ?>
						</a>
						<BR />
					</td>
					<td align="left" style="text-align:center;">
						<?php echo $row->count_tag; ?>
					</td>
					<td align="center" style="text-align:center;"> 
						<?php echo $published?>
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
		<input type="hidden" name="task" value="tag_list">
		<input type="hidden" name="boxchecked" value="0">
		<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>">
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>">
		</form>
		<?php
	}
	
	/**
	 * Add/edit tag
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editHTML($option,$row,$lists,$translatable){
		global $mainframe,$configClass;
		global $mainframe,$languages;
		JRequest::setVar( 'hidemainmenu', 1 );
		$db = JFactory::getDBO();
		JHtml::_('behavior.tooltip');
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('OS_TAG').$title);
		JToolBarHelper::save('tag_save');
		JToolBarHelper::apply('tag_apply');
		JToolBarHelper::cancel('tag_cancel');
		
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
					<?php echo JText::_('OS_TAG'); ?>
				</td>
				<td>
					<input type="text" name="keyword" id="keyword" size="40" value="<?php echo $row->keyword;?>">
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
											<?php echo JText::_('OS_TAG'); ?>
										</td>
										<td>
											<input type="text" name="keyword_<?php echo $sef; ?>" id="keyword_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'keyword_'.$sef};?>">
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
		Joomla.submitbutton = function(pressbutton)
		{
			form = document.adminForm;
			if (pressbutton == 'tag_cancel'){
				submitform( pressbutton );
				return;
			}else if (form.keyword.value == ''){
				alert('<?php echo JText::_('OS_PLEASE_ENTER_PROPERTY_TAG'); ?>');
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