<?php
/*------------------------------------------------------------------------
# default.php - mod_oscategorymenu
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$db = JFactory::getDbo();

if (!function_exists('osBuildCategorytree')) {
	function osBuildCategorytree($parent = 0, $level = 0, $params, $currentCat = null,$itemid) {
		$db = JFactory::getDbo();
		$menuClass = $params->get('moduleclass_sfx');
		$levelStart = (int) $params->get('level_start');
		$levelEnd = (int) $params->get('level_end');
		

		if ( (!$levelEnd || $level < $levelEnd) && $rows = modOspropertyCategoryMenuHelper::osGetCategories($parent, $params) ) {
			if ($level >= $levelStart) : ?>
			<ul class="level<?php echo $level . $menuClass ?>">
			<?php endif;
			foreach( $rows as $row ) {
				if ($level >= $levelStart) {
					$link = JRoute::_("index.php?option=com_osproperty&task=category_details&id=".$row->category_id."&Itemid=".$itemid); 
					
					$total = 0;
					$total = modOspropertyCategoryMenuHelper::countProperties($row->category_id,$total);
					?>
					<li<?php echo ($currentCat == $row->category_id ? ' id="current"' : '') ?>><span class="rightarrow"></span>&nbsp;&nbsp;&nbsp;</span><a class="level<?php echo $level . $menuClass . ($currentCat == $row->category_id ? ' active' : '') ?>" href="<?php echo $link ?>" target="_self"><span><?php echo htmlspecialchars(stripslashes(OSPHelper::getLanguageFieldValue($row,'category_name')), ENT_COMPAT, 'UTF-8') ?> (<?php echo $total?>)</span></a></li>
                <?php
				}
				
				
				osBuildCategorytree($row->category_id, $level + 1, $params, $currentCat,$itemid);
				//if ($level >= $levelStart) : ?>
                <?php
               // endif;
			} // end foreach
			if ($level >= $levelStart) : ?>
                </ul>
			<?php
            endif;
		}
	}
}
?>
<div class="oscategorymenu">
<?php
osBuildCategorytree(0, 0, $params,null,$itemid);
?>
</div>