<?php
;/**
; * @version	$Id: default.php $
; * @package	OS Property
; * @author		Dang Thuc Dam http://www.joomdonation.com
; * @copyright	Copyright (c) 2007 - 2015 Joomdonation. All rights reserved.
; * @license	GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
; */


// no direct access
defined('_JEXEC') or die;
$db = JFactory::getDbo();
?>
<div class="clr"></div>

<?php if($modLogo): ?>
<div id="osQuickIconsTitle">
	<a href="<?php echo JRoute::_('index.php?option=com_osproperty'); ?>" title="<?php echo JText::_('OS Property'); ?>">
		<span>OS Property</span>
	</a>
</div>
<?php endif; ?>

<div id="osQuickIcons" <?php if(!$modLogo): ?> class="osNoLogo"<?php endif; ?>>
    <?php
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=configuration_list', 'setting.png', JText::_('OS_CONFIGURATION'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=categories_list', 'categories.png', JText::_('OS_MANAGE_CATEGORIES'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=type_list', 'type.png', JText::_('OS_MANAGE_PROPERTY_TYPES'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=amenities_list', 'convenience.png', JText::_('OS_MANAGE_CONVENIENCE'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=properties_list', 'property.png', JText::_('OS_MANAGE_PROPERTIES'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=pricegroup_list', 'price.png', JText::_('OS_MANAGE_PRICELIST'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=agent_list', 'users.png', JText::_('OS_MANAGE_AGENTS'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=companies_list', 'company.png', JText::_('OS_MANAGE_COMPANIES'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=fieldgroup_list', 'group.png', JText::_('OS_MANAGE_FIELD_GROUPS'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=extrafield_list', 'fields.png', JText::_('OS_MANAGE_EXTRA_FIELDS'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=state_list', 'state.png', JText::_('OS_MANAGE_STATES'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=city_list', 'city.png', JText::_('OS_MANAGE_CITY'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=email_list', 'email.png', JText::_('OS_MANAGE_EMAIL_FORMS'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=comment_list', 'comment.png', JText::_('OS_MANAGE_COMMENTS'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=properties_backup', 'export.png', JText::_('OS_BACKUP'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=properties_restore', 'restore.png', JText::_('OS_RESTORE'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=theme_list', 'theme.png', JText::_('OS_MANAGE_THEMES'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=translation_list', 'translate.png', JText::_('OS_TRANSLATION_LIST'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=form_default', 'csv.png', JText::_('OS_CSV_FORM'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=csvexport_default', 'csvexport.png', JText::_('OS_EXPORT_CSV'));
	OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=xml_default', 'xmlexport.png', JText::_('OS_EXPORT_XML'));
    OspropertyCpanel::quickiconButton('index.php?option=com_osproperty&task=xml_defaultimport', 'xmlimport.png', JText::_('OS_IMPORT_XML'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=transaction_list', 'order.png', JText::_('OS_MANAGE_TRANSACTION'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&task=properties_prepareinstallsample', 'install.png', JText::_('OS_INSTALLSAMPLEDATA'));
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&amp;task=properties_sefoptimize', 'icon-48-sef.png', JText::_('OS_OPTIMIZE_SEF_URLS'));
    $translatable = JLanguageMultilang::isEnabled() && count($languages);
    if($translatable){
        ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&amp;task=properties_syncdatabase', 'sync.png', JText::_('OS_SYNC_MULTILINGUAL_DATABASE'));
    }
    if($configClass['enable_report'] == 1){
        $db->setQuery("Select count(id) from #__osrs_report where is_checked = '0'");
        $count_report = $db->loadResult();
        if($count_report > 0){
            ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&amp;task=report_listing', 'notice_new.png', JText::_('OS_USER_REPORT'));
        }else{
            ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&amp;task=report_listing', 'notice.png', JText::_('OS_USER_REPORT'));
        }
    }
    ModOsquickiconsHelper::quickiconButton('index.php?option=com_osproperty&amp;task=tag_list', 'tag.png', JText::_('OS_MANAGE_TAGS'));
    ?>
    <div style="clear: both;"></div>
</div>
