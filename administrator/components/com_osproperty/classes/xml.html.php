<?php
/*------------------------------------------------------------------------
# xml.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class HTML_OspropertyXml{
    static function xmlImportForm($option, $lists){
        global $mainframe;
        $document = JFactory::getDocument();
        JToolbarHelper::title(JText::_('OS_IMPORT_XML'));
        JToolbarHelper::custom('xml_import','upload.png','upload.png',JText::_('OS_IMPORT'),false);
        JToolbarHelper::cancel();
        ?>
        <form method="POST" action="index.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<div class="row-fluid">
			<div class="span2" style="margin-left:0px;"></div>
			<div class="span9" style="text-align:center;margin-left:0px;">
				<h2>
					<?php echo JText::_('OS_PHP_INFORMATION');?>
				</h2>
				<i>
					<font color="gray">
						<?php echo JText::_('OS_SERVER_NOTE');?>
					</font>
				</i>
				<table width="100%" class="img img-polaroid" style="border:1px solid #CCC;padding:20px;">
					<tr>
						<td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
							<strong><?php echo JText::_('OS_MEMORY_LIMIT'); ?></strong>
						</td>
						<td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
							<?php
                            $memory_limit = ini_get('memory_limit');
                            if($memory_limit != ""){
                                echo $memory_limit;
                                $memory_limit1 = intval(trim(str_replace("M","",$memory_limit)));
                                if($memory_limit1 <  50){
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
						<td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
							<strong><?php echo JText::_('OS_MAX_EXECUTION_TIME'); ?></strong>
						</td>
						<td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
							<?php
                            $max_execution_time = ini_get('max_execution_time');
                            if($max_execution_time != ""){
                                echo $max_execution_time/60;
                                echo " seconds";
                                $max_execution_time1 = intval(trim(str_replace("M","",$max_execution_time)));
                                if($max_execution_time1 <  1000){
                                    if(ini_set('max_execution_time','3000')){
                                        ?>
                                        &nbsp;<font style="color:green;"><?php echo JText::_('OS_COMPONENT_CAN_CHANGE_THIS_VALUE');?></font>
                                    <?php
                                    }else{
                                        ?>
                                        &nbsp;<font style="color:red;"><?php echo JText::_('OS_COMPONENT_CANNOT_CHANGE_THIS_VALUE');?></font>
                                    <?php
                                    }
                                }
                            }
                            ?>
						</td>
					</tr>
                    <tr>
						<td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
							<strong><?php echo JText::_('OS_CURL_ENABLED'); ?></strong>
						</td>
						<td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
							<?php
                            if(function_exists('curl_version')){
                                echo "<strong style='color:green;'>".JText::_("OS_YES")."</strong>";
                            }else{
                                echo "<strong style='color:red;'>".JText::_("OS_NO")."</strong>";
                            }
                            ?>
						</td>
					</tr>
				</table>
				<BR /><BR />
				<h2>
					<?php echo JText::_('OS_SELECT_XML_FILE');?>
				</h2>
                <table width="100%" class="img img-polaroid" style="border:1px solid #CCC;padding:20px;">
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_PUBLISH_AND_APPROVE_PROPERTIES'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
                            <?php echo JHtml::_('select.genericlist',$lists['optionArr'],'publish_properties','class="input-mini"','value','text');?>
                        </td>
                    </tr>
					<tr>
						<td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
							<strong><?php echo JText::_('OS_XML_FILE'); ?></strong>
						</td>
						<td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
							<input type="file" class="input-large" name="xml_file" id="xml_file" />
						</td>
					</tr>
				</table>
			</div>
			<div class="span2" style="margin-left:0px;"></div>
		</div>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="xml_import" />
		<input type="hidden" name="MAX_UPLOAD_FILESIZE" value="900000000" />
		<input type="hidden" name="boxchecked" value="0" id="boxchecked" />
		</form>
		<script type="text/javascript">
			function checkFile(fieldObj)
		    {
		        var FileName  = fieldObj.value;
		        var FileExt = FileName.substr(FileName.lastIndexOf('.')+1);
		        if (FileExt != "xml")
		        {
		            var error = "File type : "+ FileExt+"\n\n";
		            error += "<?php echo JText::_('OS_PLEASE_MAKE_SURE_YOUR_FILE_IS_KYERO_XML')?>";
		            alert(error);
		            return true;
		        }
		        return false;
		    }
		</script>
        <?php
    }

	static function xmlExportForm($option,$lists){
		global $mainframe,$configClass;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_SELECT_OPTIONS_TO_EXPORT_XML'),"logo48.png");
		JToolBarHelper::custom('xml_export','download.png','download.png',JText::_('OS_EXPORTPROPERTIES'),false);
		JtoolBarHelper::cancel();
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm">

        <div class="row-fluid">
            <div class="span2" style="margin-left:0px;"></div>
            <div class="span9" style="text-align:center;margin-left:0px;">
                <h2>
                    <?php echo JText::_('OS_SELECT_OPTIONS_TO_EXPORT_PROPERTIES');?>
                </h2>
                <table width="100%" class="img img-polaroid" style="border:1px solid #CCC;padding:20px;">
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_CATEGORY'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
                            <?php echo $lists['category'];?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_TYPE'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
                            <?php echo $lists['type'];?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_AGENT'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
                            <?php echo $lists['agent'];?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_COMPANY'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
                            <?php echo $lists['company'];?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_COUNTRY'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;float:left;" width="70%">
                            <div style="float:left;">
                                <?php echo $lists['country'];?>
                            </div>
                            <div style="float:left;display:inline;" id="country_state">
                                <?php echo $lists['states'];?>
                            </div>
                            <div style="float:left;display:inline;" id="city_div">
                                <?php echo $lists['city'];?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_ADD_PROPERTY_IDS_INTO_XML_ELEMENTS'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
                            <?php echo JHtml::_('select.genericlist',$lists['optionArr'],'include_pids','class="input-mini"','value','text');?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_ADD_AGENT_IDS_INTO_XML_ELEMENTS'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
                            <?php echo JHtml::_('select.genericlist',$lists['optionArr'],'include_aids','class="input-mini"','value','text');?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;border-right:1px solid #CCC;" width="30%">
                            <strong><?php echo JText::_('OS_ADD_COMPANY_IDS_INTO_XML_ELEMENTS'); ?></strong>
                        </td>
                        <td style="text-align:left;padding:10px;border-bottom:1px solid #CCC;" width="70%">
                            <?php echo JHtml::_('select.genericlist',$lists['optionArr'],'include_cids','class="input-mini"','value','text');?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="span2" style="margin-left:0px;"></div>
        </div>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="" />
		</form>
        <script language="javascript">
            function loadState(country_id,state_id,city_id){
                var live_site = '<?php echo JURI::root()?>';
                loadLocationInfoStateCity(country_id,state_id,city_id,'country','state',live_site);
            }
            function loadCity(state_id,city_id){
                var live_site = '<?php echo JURI::root()?>';
                loadLocationInfoCity(state_id,city_id,'state',live_site);
            }
        </script>
		<?php 
	}
}
?>