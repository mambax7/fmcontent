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
    <div class="floatright bold marg5">
        <{if $topic_title}>
            <{$smarty.const._FMCONTENT_ALL_ITEMS_FROM}><{$topic_title}>
        <{else}>
            <{$smarty.const._FMCONTENT_ALL_ITEMS}>
        <{/if}>
    </div>
</div>

<table id="xo-content-sort" class="outer" cellspacing="1" width="100%">
    <thead>
    <th><{$smarty.const._FMCONTENT_CONTENT_ID}></th>
    <th><{$smarty.const._FMCONTENT_CONTENT_NUM}></th>
    <th><{$smarty.const._FMCONTENT_CONTENT_TITLE}></th>
    <th><{$smarty.const._FMCONTENT_CONTENT_TOPIC}></th>
    <th><{$smarty.const._FMCONTENT_CONTENT_TYPE}></th>
    <th><{$smarty.const._FMCONTENT_CONTENT_OWNER}></th>
    <th><{$smarty.const._FMCONTENT_CONTENT_ACTIF}></th>
    <th><{$smarty.const._FMCONTENT_CONTENT_DEFAULT}></th>
    <th><{$smarty.const._FMCONTENT_FORMDISPLAY}></th>
    <th><{$smarty.const._FMCONTENT_CONTENT_ACTION}></th>
    </thead>
    <tbody class="xo-content">
    <{foreach item=item from=$contents}>
        <{include file="$xoops_rootpath/modules/fmcontent/templates/admin/fmcontent_level.tpl" data=$item level='level1'}>

        <{foreach item=child from=$item.child}>
            <{include file="$xoops_rootpath/modules/fmcontent/templates/admin/fmcontent_level.tpl" data=$child level='level2' }>

            <{foreach item=sub from=$child.child}>
                <{include file="$xoops_rootpath/modules/fmcontent/templates/admin/fmcontent_level.tpl" data=$sub level='level3'}>
            <{/foreach}>

        <{/foreach}>

    <{/foreach}>
    </tbody>
</table>

<{foreach item=item from=$contents}>
    <{if $item.content_type == 'content'}>
        <div id="dialog<{$item.content_id}>" title="<{$item.content_title}>" style='display:none;'>
            <div class="ui-state-active ui-corner-all pad5 bold">
                <div class="bold"><{$smarty.const._FMCONTENT_CONTENT_TITLE}> : <a
                            href="<{$item.url}>"><{$item.content_title}></a></div>
                <div class="bold"><{$smarty.const._FMCONTENT_CONTENT_MENU}> :<a
                            href="<{$item.url}>"><{$item.content_menu}></a></div>
                <div class="bold"><{$smarty.const._FMCONTENT_CONTENT_TOPIC}> :
                    <{if $item.topic}>
                        <a href="content.php?topic=<{$item.content_topic}>"><{$item.topic}></a>
                    <{else}>
                        <a href="content.php?topic=0"><{$smarty.const._FMCONTENT_STATIC}></a>
                    <{/if}>
                </div>
            </div>
            <div class="pad5"><img class="ui-state-highlight left" width="200" src="<{$item.imgurl}>"
                                   alt="<{$item.content_title}>"></div>
            <div class="pad5"><{$item.content_short}></div>
        </div>
    <{else}>
        <div id="dialog<{$item.content_id}>" title="<{$item.content_menu}>" style='display:none;'>
            <div class="pad5"><a href="<{$item.content_link}>"><{$item.content_menu}></a></div>
            <div class="pad5 ui-state-highlight ui-corner-all">
                <{if $item.topic}>
                    <a href="content.php?topic=<{$item.content_topic}>"><{$item.topic}></a>
                <{else}>
                    <a href="content.php?topic=0"><{$smarty.const._FMCONTENT_STATIC}></a>
                <{/if}>
            </div>
        </div>
    <{/if}>
<{/foreach}>

<div class="pagenav"><{$content_pagenav}></div>
