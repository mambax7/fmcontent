<{include file="$xoops_rootpath/modules/fmcontent/templates/admin/fmcontent_header.tpl"}>

<table id="xo-file-sort" class="outer" cellspacing="1" width="100%">
    <thead>
    <th><{$smarty.const._FMCONTENT_FILE_ID}></th>
    <th><{$smarty.const._FMCONTENT_FILE_TITLE}></th>
    <th><{$smarty.const._FMCONTENT_FILE_CONTENT}></th>
    <th><{$smarty.const._FMCONTENT_FILE_TYPE}></th>
    <th><{$smarty.const._FMCONTENT_FILE_ONLINE}></th>
    <th><{$smarty.const._FMCONTENT_FILE_ACTION}></th>
    </thead>
    <tbody class="xo-file">
    <{foreach item=file from=$files}>
        <tr class="odd" id="mod_<{$file.file_id}>">
            <td class="width5"><img src="../assets/images/icons/puce.png" alt=""><{$file.file_id}></td>
            <td class="txtcenter bold"><{$file.file_title}></td>
            <td class="txtcenter bold"><{$file.content}></td>
            <td class="txtcenter width10 bold"><{$file.file_type}></td>
            <td class="txtcenter width5 bold">
                <img class="cursorpointer" id="file_status<{$file.file_id}>"
                     onclick="fmcontent_setFileOnline( { op: 'file_status', file_id: <{$file.file_id}> }, 'file_status<{$file.file_id}>', 'backend.php' )"
                     src="<{if $file.file_status}>../assets/images/icons/ok.png<{else}>../assets/images/icons/cancel.png<{/if}>"
                     class="tooltip" alt="">
            </td>
            <td class="txtcenter width10 xo-actions">
                <a href="<{$file.fileurl}>"><img class="tooltip" src="../assets/images/icons/display.png"
                                                 alt="<{$smarty.const._FMCONTENT_CONTENT_VIEW}>"
                                                 title="<{$smarty.const._FMCONTENT_CONTENT_VIEW}>"></a>
                <a href="file.php?op=edit_file&amp;file_id=<{$file.file_id}>"><img class="tooltip"
                                                                                   src="<{xoAdminIcons edit.png}>"
                                                                                   alt="<{$smarty.const._EDIT}>"
                                                                                   title="<{$smarty.const._EDIT}>"></a>
                <a href="file.php?op=delete_file&amp;file_id=<{$file.file_id}>"><img class="tooltip"
                                                                                     src="<{xoAdminIcons delete.png}>"
                                                                                     alt="<{$smarty.const._DELETE}>"
                                                                                     title="<{$smarty.const._DELETE}>"></a>
            </td>
        </tr>
    <{/foreach}>
    </tbody>
</table>


<div class="pagenav"><{$file_pagenav}></div>
