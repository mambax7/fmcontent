<div class="moduleicons">
    <div class="floatright">
        <div class="xo-buttons">
            <a class="ui-corner-all tooltip" href="content.php?op=new_content"
               title="<{$smarty.const._FMCONTENT_ADD_CONTENT}>"><img src="<{xoAdminIcons add.png}>"
                                                                     alt="<{$smarty.const._FMCONTENT_ADD_CONTENT}>"><{$smarty.const._FMCONTENT_ADD_CONTENT}>
            </a>
            <a class="ui-corner-all tooltip" href="topic.php?op=new_topic"
               title="<{$smarty.const._FMCONTENT_ADD_TOPIC}>"><img src="<{xoAdminIcons folder_blue.png}>"
                                                                   alt="<{$smarty.const._FMCONTENT_ADD_TOPIC}>"><{$smarty.const._FMCONTENT_ADD_TOPIC}>
            </a>
            <a class="ui-corner-all tooltip" href="content.php?op=new_link"
               title="<{$smarty.const._FMCONTENT_ADD_MENU}>"><img src="<{xoAdminIcons colorize.png}>"
                                                                  alt="<{$smarty.const._FMCONTENT_ADD_MENU}>"><{$smarty.const._FMCONTENT_ADD_MENU}>
            </a>
            <a class="ui-corner-all tooltip" href="file.php?op=new_file"
               title="<{$smarty.const._FMCONTENT_ADD_FILE}>"><img src="<{xoAdminIcons attach.png}>"
                                                                  alt="<{$smarty.const._FMCONTENT_ADD_FILE}>"><{$smarty.const._FMCONTENT_ADD_FILE}>
            </a>
        </div>
    </div>
    <div class="navigation" id="<{$navigation}>"><{$navtitle}></div>
</div>

<{if $fmcontent_tips}>
    <div class="tips ui-corner-all">
        <img class="floatleft tooltip" src="<{xoAdminIcons tips.png}>" alt="<{$smarty.const._AM_SYSTEM_TIPS}>"
             title="<{$smarty.const._AM_SYSTEM_TIPS}>">
        <div class="floatleft"><{$fmcontent_tips}></div>
        <div class="clear">&nbsp;</div>
    </div>
<{/if}>
