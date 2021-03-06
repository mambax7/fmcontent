<tr class="odd <{$level}>" id="mod_<{$data.content_id}>">
    <td class="width5"><img src="../assets/images/icons/puce.png" alt=""><{$data.content_id}></td>
    <td class="width5 txtcenter"><span><img src="../assets/images/icons/puce.png" alt=""><{$data.content_order}></span>
    </td>
    <td class="width30 txtcenter">
        <span><{if $data.content_type == 'content'}><a href="<{$data.url}>"><{$data.content_title}></a><{else}> <span><a
                        href="<{$data.content_link}>"><{$data.content_menu}></a></span><{/if}></span>
    </td>
    <td class="txtcenter width10 bold">
        <{if $data.topic}>
            <a href="content.php?topic=<{$data.content_topic}>"><{$data.topic}></a>
        <{else}>
            <a href="content.php?topic=0"><{$smarty.const._FMCONTENT_STATIC}></a>
        <{/if}>
    </td>
    <td class="txtcenter width10 bold">
        <img src="../assets/images/icons/<{$data.content_type}>.png" alt=""><{$data.content_type}>
    </td>
    <td class="txtcenter width10">
        <a href="<{$xoops_url}>/userinfo.php?uid=<{$data.content_uid}>"><img class="tooltip"
                                                                             src="../assets/images/icons/user.png"
                                                                             alt="<{$data.owner}>"
                                                                             title="<{$data.owner}>">&nbsp;<{$data.owner}>
        </a>
    </td>
    <td class="txtcenter width5">
        <img class="cursorpointer" id="status<{$data.content_id}>"
             onclick="fmcontent_setStatus( { op: 'status', content_id: <{$data.content_id}> }, 'status<{$data.content_id}>', 'backend.php' )"
             src="<{if $data.content_status}>../assets/images/icons/ok.png<{else}>../assets/images/icons/cancel.png<{/if}>"
             alt="">
    </td>
    <td class="txtcenter width5">
        <{if $data.content_type == 'content'}>
            <img class="cursorpointer xo-defaultimg" id="default<{$data.content_id}>"
                 onclick="fmcontent_setDefault( { op: 'default', content_id: <{$data.content_id}> , topic_id: <{$data.content_topic}> }, 'default<{$data.content_id}>', 'backend.php' )"
                 src="<{if $data.content_default}>../assets/images/icons/ok.png<{else}>../assets/images/icons/cancel.png<{/if}>"
                 alt="">
        <{/if}>
    </td>
    <td class="txtcenter width5">
        <img class="cursorpointer" id="display<{$data.content_id}>"
             onclick="fmcontent_setDisplay( { op: 'display', content_id: <{$data.content_id}> }, 'display<{$data.content_id}>', 'backend.php' )"
             src="<{if $data.content_display}>../assets/images/icons/ok.png<{else}>../assets/images/icons/cancel.png<{/if}>"
             alt="">
    </td>
    <td class="txtcenter xo-actions width15">
        <img class="tooltip"
             onclick="display_dialog(<{$data.content_id}>, true, true, 'slide', 'slide', <{if $data.content_type == 'content'}>300, 700<{else}>110, 300<{/if}>);"
             src="<{xoAdminIcons display.png}>" alt="<{$smarty.const._PREVIEW}>" title="<{$smarty.const._PREVIEW}>">
        <{if $data.content_type == 'content'}>
            <a href="<{$data.url}>"><img class="tooltip" src="../assets/images/icons/display.png"
                                         alt="<{$smarty.const._FMCONTENT_CONTENT_VIEW}>"
                                         title="<{$smarty.const._FMCONTENT_CONTENT_VIEW}>"></a>
            <{if $data.content_file != 0}>
                <a href="file.php?content=<{$data.content_id}>"><img class="tooltip"
                                                                     src="../assets/images/icons/file.png"
                                                                     alt="<{$smarty.const._FMCONTENT_CONTENT_FILE}>"
                                                                     title="<{$smarty.const._FMCONTENT_CONTENT_FILE}>"></a>
            <{/if}>
            <{if $data.content_topic != 0}>
                <a href="<{$data.topicurl}>"><img class="tooltip" src="../assets/images/icons/section.png"
                                                  alt="<{$smarty.const._FMCONTENT_CONTENT_TOPIC}>"
                                                  title="<{$smarty.const._FMCONTENT_CONTENT_TOPIC}>"></a>
            <{/if}>
            <a href="content.php?op=edit_content&amp;content_id=<{$data.content_id}>"><img class="tooltip"
                                                                                           src="../assets/images/icons/edit.png"
                                                                                           alt="<{$smarty.const._EDIT}>"
                                                                                           title="<{$smarty.const._EDIT}>"></a>
            <a href="content.php?op=delete&amp;content_id=<{$data.content_id}>"><img class="tooltip"
                                                                                     src="../assets/images/icons/delete.png"
                                                                                     alt="<{$smarty.const._DELETE}>"
                                                                                     title="<{$smarty.const._DELETE}>"></a>
        <{else}>
            <a href="<{$data.content_link}>"><img class="tooltip" src="../assets/images/icons/display.png"
                                                  alt="<{$smarty.const._PREVIEW}>" title="<{$smarty.const._PREVIEW}>"></a>
            <a href="content.php?op=edit_link&amp;content_id=<{$data.content_id}>"><img class="tooltip"
                                                                                        src="<{xoAdminIcons edit.png}>"
                                                                                        alt="<{$smarty.const._EDIT}>"
                                                                                        title="<{$smarty.const._EDIT}>"></a>
            <a href="content.php?op=delete&amp;content_id=<{$data.content_id}>"><img class="tooltip"
                                                                                     src="<{xoAdminIcons delete.png}>"
                                                                                     alt="<{$smarty.const._DELETE}>"
                                                                                     title="<{$smarty.const._DELETE}>"></a>
        <{/if}>
    </td>
</tr>
