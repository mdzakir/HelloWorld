<div class="row-fluid">
	<div class="span12">
        <?php if($configClass['show_alphabet'] == 1) { ?>
            <div class="block_caption">
                <strong><?php echo JText::_('OS_ALPHABIC')?></strong>
            </div>
            <div class="row-fluid">
                <div class="span12">
                <?php
                    HelperOspropertyCommon::alphabetList($option, $alphabet, 'ftForm');
                ?>
                </div>
            </div>
        <?php } ?>
        <div class="row-fluid">
            <div class="span12">
                <?php
                if(count($rows) > 0){
                ?>
                <div class="block_caption">
                    <strong><?php echo JText::_('OS_LIST_AGENTS')?></strong>
                </div>
                <?php
                }
                ?>
			    <div class="clearfix"></div>
                <?php
                for($i=0;$i<count($rows);$i++){
                    $row = $rows[$i];
                    ?>
                    <div class="row-fluid">
                        <div class="row-fluid ospitem-separator">
                            <div class="span12">
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div id="ospitem-watermark_box">
                                            <?php
                                            if($configClass['show_agent_image'] == 1){
                                                ?>
                                                <?php
                                                if(($row->photo != "") and (file_exists(JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS.$row->photo))){
                                                    ?>
                                                    <img src='<?php echo JURI::root()?>images/osproperty/agent/<?php echo $row->photo?>' border="0" />
                                                    <?php
                                                }else{
                                                    ?>
                                                    <img src='<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.jpg' border="0" />
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="span9 ospitem-leftpad">
                                        <div class="ospitem-leftpad">
                                            <div class="row-fluid ospitem-toppad">
                                                <div class="span12">
                                                    <span class="ospitem-propertyprice title-blue">
                                                        <?php echo $row->name?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    if($configClass['show_agent_address']==1){
                                    echo OSPHelper::generateAddress($row);
                                    echo "<BR />";
                                    }
                                    ?>
                                    <?php
                                    if($configClass['show_agent_email'] == 1){
                                    echo JText::_('OS_EMAIL');
                                    echo ":&nbsp;";
                                    echo "&nbsp;";
                                    echo "<a href='mailto:$row->email'>$row->email</a>";
                                    ?>
                                    <BR />
                                    <?php
                                    }
                                    if($row->phone != ""){
                                        echo JText::_('OS_PHONE');
                                        echo ":&nbsp;";
                                        echo "&nbsp;";
                                        echo $row->phone;
                                        echo "<BR />";
                                    }

                                    if($row->mobile != ""){
                                        echo JText::_('OS_MOBILE');
                                        echo ":&nbsp;";
                                        echo "&nbsp;";
                                        echo $row->mobile;
                                        echo "<BR />";
                                    }
                                    ?>
                                    <BR />
                                    <?php
                                    $bio = JHtml::_('content.prepare',OSPHelper::getLanguageFieldValue($row,'bio'));
                                    if($bio != ""){
                                        echo stripslashes(strip_tags($bio));
                                    ?>
                                    <BR />
                                    <?php
                                    }
                                    ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=agent_info&id='.$row->id);?>" class="btn" title="<?php echo JText::_('OS_VIEW_DETAILS');?>"><?php echo JText::_('OS_VIEW_DETAILS');?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                if($pageNav->total > $pageNav->limit){
                ?>
                    <div class="clearfix"></div>
                    <DIV class="pageNavdiv">
                        <?php echo $pageNav->getListFooter();?>
                    </DIV>
                <?php } ?>
            </div>
		</div>
	</div>
</div>