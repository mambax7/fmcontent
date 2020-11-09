<{if $header}><{include file="$xoops_rootpath/modules/fmcontent/templates/admin/fmcontent_header.tpl"}><{/if}>

<div id="tools" class="marg5 pad5">
    <!-- Display clone form -->
    <{if $folder}><{$folder}><{/if}>
    <{if $purge}><{$purge}><{/if}>
    <{if $alias}><{$alias}><{/if}>
    <{if $meta}><{$meta}><{/if}>

    <{if $messages}>
        <div id="xo-module-log">
            <h4><{$smarty.const._FMCONTENT_LOG_TITLE}></h4>
            <br><br>
            <div class="xo-logger">
                <{foreach item=log from=$messages}>
                    <li><{$log}></li>
                <{/foreach}>
            </div>
        </div>
    <{/if}>
</div>
