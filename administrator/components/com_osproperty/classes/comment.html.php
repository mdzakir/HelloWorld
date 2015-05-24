<?php
/*------------------------------------------------------------------------
# comment.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OspropertyComment{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function comment_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_COMMENTS'),"logo48.png");
		JToolBarHelper::addNew('comment_add');
		JToolBarHelper::editList('comment_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'comment_remove');
		JToolBarHelper::publish('comment_publish');
		JToolBarHelper::unpublish('comment_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		
		<form method="POST" action="index.php?option=com_osproperty&task=comment_list" name="adminForm" id="adminForm">
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
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="2%">
						#
					</th>
					<th width="3%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="20%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_COMMENT_TITLE'), 'c.title', @$lists['order_Dir'], @$lists['order'] ,'comment_list'); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_AUTHOR'), 'c.name', @$lists['order_Dir'], @$lists['order'] ,'comment_list'); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_RATE'), 'c.rate', @$lists['order_Dir'], @$lists['order'] ,'comment_list'); ?>
					</th>
					<th width="10%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_CREATED'), 'c.created_on', @$lists['order_Dir'], @$lists['order'] ,'comment_list'); ?>
					</th>
					<th width="20%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PROPERTY'), 'p.pro_name', @$lists['order_Dir'], @$lists['order'] ,'comment_list'); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_IP_ADDRESS'), 'c.ip_address', @$lists['order_Dir'], @$lists['order'] ,'comment_list'); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISH'), 'c.published', @$lists['order_Dir'], @$lists['order'] ,'comment_list'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="9" style="text-align:center;" align="center">
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
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=comment_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i, 'comment_');
				
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center">
						<?php echo $checked; ?>
					</td>
					<td align="left" style="padding-left: 10px;">
						<a href="<?php echo $link; ?>">
							<?php echo $row->title; ?>
						</a>
					</td>
					<td style="padding-left: 10px;">
						<?php echo $row->name?>
					</td>
					<td style="text-align:center;">
						<?php echo $row->rate?>/5
					</td>
					<td align="center">
						<?php echo date('M d,Y H:i',strtotime($row->created_on))?>
					</td>
					<td style="padding-left: 10px;">
						<?php echo $row->pro_name?>
					</td>
					<td style="text-align:left;">
						<?php echo $row->ip_address;?>
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
		<input type="hidden" name="option" value="com_osproperty">
		<input type="hidden" name="task" value="comment_list">
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
	function editHTML($option,$row,$lists){
		global $mainframe;
		JRequest::setVar( 'hidemainmenu', 1 );
		$db = JFactory::getDBO();
		JHtml::_('behavior.tooltip');
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('Comment').$title);
		JToolBarHelper::save('comment_save');
		JToolBarHelper::apply('comment_apply');
		JToolBarHelper::cancel('comment_cancel');
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
			<table  width="100%" class="admintable" style="background-color:white;">
				<tr>
					<td class="key">
						<?php echo JText::_('OS_COMMENT_TITLE'); ?>
					</td>
					<td>
						<input type="text" name="title" id="title" size="40" value="<?php echo $row->title; ?>" class="input-large">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_RATE'); ?>
					</td>
					<td>
						<?php
						echo $lists['rate'];
						?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_AUTHOR'); ?>
					</td>
					<td>
						<?php
						if($row->id == 0){
							$user = JFactory::getUser();
							$row->user_id = $user->id;
						}
						echo OspropertyAgent::getUserInput($row->user_id);
						?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_PROPERTY'); ?>
					</td>
					<td>
						<?php
						echo OspropertyComment::getPropertyInput($row->pro_id);
						?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_NAME'); ?>
					</td>
					<td>
						<input type="text" name="name" id="name" size="40" value="<?php echo $row->name; ?>" class="input-large">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_CREATED'); ?>
					</td>
					<td>
						<?php
						echo JHtml::_('calendar',$row->created_on,'created_on','created_on','%Y-%m-%d','class="input-small"');
						?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_IP_ADDRESS'); ?>
					</td>
					<td>
						<input type="text" name="ip_address" id="ip_address" size="40" value="<?php echo $row->ip_address; ?>" class="input-small">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_PUBLISHED')?>
					</td>
					<td>
						<?php
						echo $lists['published'];
						?>
					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
						<?php echo JText::_('OS_REVIEW')?>
					</td>
					<td>
						<textarea rows="5" style="width: 550px;" name="content" id="content"><?php echo $row->content; ?></textarea>
					</td>
				</tr>
			</table>
			<input type="hidden" name="option" value="com_osproperty">
			<input type="hidden" name="task" value="">
			<input type="hidden" name="id" value="<?php echo $row->id?>">
		</form>
		<script type="text/javascript">
			Joomla.submitbutton = function(pressbutton)
			{
				form = document.adminForm;
				if (pressbutton == 'comment_cancel'){
					submitform( pressbutton );
					return;
				}else if (form.title.value == ''){
					alert('<?php echo JText::_('OS_PLEASE_ENTER_COMMENT_TITLE'); ?>');
					return;
				}else if (form.pro_id.value == ''){
					alert('<?php echo JText::_('OS_PLEASE_SELECT_PROPERTY'); ?>');
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