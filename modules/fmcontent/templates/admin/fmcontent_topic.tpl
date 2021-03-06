<{include file="$xoops_rootpath/modules/fmcontent/templates/admin/fmcontent_header.tpl"}>

<div class="moduleicons">
    <div class="floatleft">
        <div class="xo-buttons">
            <a class="ui-corner-all tooltip" href="content.php?topic=0" title="<{$smarty.const._FMCONTENT_STATICS}>">
                <img src="<{xoAdminIcons add.png}>" alt="<{$smarty.const._FMCONTENT_STATICS}>">
                <{$smarty.const._FMCONTENT_STATICS}>
            </a>
        </div>
    </div>
</div>

<table id="xo-topic-sort" class="outer" cellspacing="1" width="100%">
    <thead>
    <th><{$smarty.const._FMCONTENT_TOPIC_ID}></th>
    <th><{$smarty.const._FMCONTENT_TOPIC_NUM}></th>
    <th><{$smarty.const._FMCONTENT_TOPIC_NAME}></th>
    <th><{$smarty.const._FMCONTENT_TOPIC_SHOWTYPE}></th>
    <th><{$smarty.const._FMCONTENT_TOPIC_ONLINE}></th>
    <th><{$smarty.const._FMCONTENT_TOPIC_MENU}></th>
    <th><{$smarty.const._FMCONTENT_TOPIC_SHOW}></th>
    <th><{$smarty.const._FMCONTENT_TOPIC_ACTION}></th>
    </thead>
    <tbody class="xo-topic">
    <{foreach item=topic from=$topics}>
        <tr class="odd" id="mod_<{$topic.topic_id}>">
            <td class="width5"><img src="../assets/images/icons/puce.png" alt=""><{$topic.topic_id}></td>
            <td class="width5"><img src="../assets/images/icons/puce.png" alt=""><{$topic.topic_weight}></td>
            <td class="txtcenter width35 bold">
                <a href="content.php?topic=<{$topic.topic_id}>"><{$topic.topic_title}></a>
            </td>
            <td class="txtcenter width10 bold">
                <{if $topic.topic_showtype == 0}><{$smarty.const._FMCONTENT_SHOWTYPE_0}><{/if}>
                <{if $topic.topic_showtype == 1}><{$smarty.const._FMCONTENT_SHOWTYPE_1}><{/if}>
                <{if $topic.topic_showtype == 2}><{$smarty.const._FMCONTENT_SHOWTYPE_2}><{/if}>
                <{if $topic.topic_showtype == 3}><{$smarty.const._FMCONTENT_SHOWTYPE_3}><{/if}>
                <{if $topic.topic_showtype == 4}><{$smarty.const._FMCONTENT_SHOWTYPE_4}><{/if}>
            </td>
            <td class="txtcenter width5 bold">
                <img class="cursorpointer" id="topic_online<{$topic.topic_id}>"
                     onclick="fmcontent_setOnline( { op: 'topic_online', topic_id: <{$topic.topic_id}> }, 'topic_online<{$topic.topic_id}>', 'backend.php' )"
                     src="<{if $topic.topic_online}>../assets/images/icons/ok.png<{else}>../assets/images/icons/cancel.png<{/if}>"
                     class="tooltip" alt="">
            </td>
            <td class="txtcenter width5 bold">
                <img class="cursorpointer" id="topic_asmenu<{$topic.topic_id}>"
                     onclick="fmcontent_setAsmenu( { op: 'topic_asmenu', topic_id: <{$topic.topic_id}> }, 'topic_asmenu<{$topic.topic_id}>', 'backend.php' )"
                     src="<{if $topic.topic_asmenu}>../assets/images/icons/ok.png<{else}>../assets/images/icons/cancel.png<{/if}>"
                     class="tooltip" alt="">
            </td>
            <td class="txtcenter width5 bold">
                <img class="cursorpointer" id="topic_show<{$topic.topic_id}>"
                     onclick="fmcontent_setShow( { op: 'topic_show', topic_id: <{$topic.topic_id}> }, 'topic_show<{$topic.topic_id}>', 'backend.php' )"
                     src="<{if $topic.topic_show}>../assets/images/icons/ok.png<{else}>../assets/images/icons/cancel.png<{/if}>"
                     class="tooltip" alt="">
            </td>
            <td class="txtcenter width10 xo-actions">
                <img class="tooltip"
                     onclick="display_dialog(<{$topic.topic_id}>, true, true, 'slide', 'slide', <{if $topic.topic_img}>300, 700<{else}>280, 520<{/if}>);"
                     src="<{xoAdminIcons display.png}>" alt="<{$smarty.const._PREVIEW}>"
                     title="<{$smarty.const._PREVIEW}>">
                <a href="<{$topic.topicurl}>"><img class="tooltip" src="../assets/images/icons/display.png"
                                                   alt="<{$smarty.const._FMCONTENT_CONTENT_VIEW}>"
                                                   title="<{$smarty.const._FMCONTENT_CONTENT_VIEW}>"></a>
                <a href="topic.php?op=edit_topic&amp;topic_id=<{$topic.topic_id}>"><img class="tooltip"
                                                                                        src="<{xoAdminIcons edit.png}>"
                                                                                        alt="<{$smarty.const._EDIT}>"
                                                                                        title="<{$smarty.const._EDIT}>"></a>
                <a href="topic.php?op=delete_topic&amp;topic_id=<{$topic.topic_id}>"><img class="tooltip"
                                                                                          src="<{xoAdminIcons delete.png}>"
                                                                                          alt="<{$smarty.const._DELETE}>"
                                                                                          title="<{$smarty.const._DELETE}>"></a>
            </td>
        </tr>
    <{/foreach}>
    </tbody>
</table>

<{foreach item=topic from=$topics}>
    <div id="dialog<{$topic.topic_id}>" title="<{$topic.topic_title}>" style='display:none;'>
        <div class="marg5 pad5 ui-state-default ui-corner-all">
            <{$smarty.const._FMCONTENT_TOPIC_NAME}> : <span
                    class="bold"> <{if $topic.contentcount == 0}><{$topic.topic_title}><{else}><a
                    href="content.php?topic=<{$topic.topic_id}>"><{$topic.topic_title}></a><{/if}></span>
        </div>
        <div class="marg5 pad5 ui-state-highlight ui-corner-all">
            <div class="pad5"><span class="bold"><{$smarty.const._FMCONTENT_TOPIC_DESC}> : </span><img
                        class="ui-state-highlight right" width="200" src="<{$topic.imgurl}>"
                        alt="<{$topic.topic_title}>"><{$topic.topic_desc}></div>
            <div class="pad5"><span class="bold"><{$smarty.const._FMCONTENT_TOPIC_SHOWTYPE}> : </span>
                <{if $topic.topic_showtype == 0}><{$smarty.const._FMCONTENT_SHOWTYPE_0}><{/if}>
                <{if $topic.topic_showtype == 1}><{$smarty.const._FMCONTENT_SHOWTYPE_1}><{/if}>
                <{if $topic.topic_showtype == 2}><{$smarty.const._FMCONTENT_SHOWTYPE_2}><{/if}>
                <{if $topic.topic_showtype == 3}><{$smarty.const._FMCONTENT_SHOWTYPE_3}><{/if}>
                <{if $topic.topic_showtype == 4}><{$smarty.const._FMCONTENT_SHOWTYPE_4}><{/if}>
            </div>
            <div class="pad5"><span class="bold"><{$smarty.const._FMCONTENT_TOPIC_WEIGHT}>
                    : </span><{$topic.topic_weight}></div>
            <div class="pad5"><span class="bold"><{$smarty.const._FMCONTENT_TOPIC_PERPAGE}>
                    : </span><{$topic.topic_perpage}></div>
            <div class="pad5"><span class="bold"><{$smarty.const._FMCONTENT_TOPIC_COLUMNS}>
                    : </span><{$topic.topic_columns}></div>
            <div class="clear"></div>
        </div>
    </div>
<{/foreach}>

<div class="pagenav"><{$topic_pagenav}></div>
