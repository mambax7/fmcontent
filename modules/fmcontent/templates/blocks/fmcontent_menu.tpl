<{if $block.type == mainmenu}>
    <div id="mainmenu">
        <{foreach item=item from=$block.menu}>
            <{if $item.content_type == 'link'}>
                <a class="menuMain<{if $item.content_link == $block.current_url|escape}> maincurrent<{/if}>"
                   href="<{$item.content_link}>" title="<{$item.content_menu}>"><{$item.content_menu}></a>
            <{else}>
                <a class="menuMain<{if $item.url == $block.current_url|escape}> maincurrent<{/if}>" href="<{$item.url}>"
                   title="<{$item.content_menu}>"><{$item.content_menu}></a>
            <{/if}>
            <{foreach item=sub from=$item.child}>

                <{if $item.content_type == 'link'}>
                    <a class="menuMain" href="<{$item.content_link}>"
                       title="<{$item.content_menu}>"><{$item.content_menu}></a>
                <{else}>
                    <a class="menuMain" href="<{$item.url}>" title="<{$item.content_menu}>"><{$item.content_menu}></a>
                <{/if}>
                <{foreach item=end from=$sub.child}>
                    <{if $item.content_type == 'link'}>
                        <a class="menuMain" href="<{$item.content_link}>"
                           title="<{$item.content_menu}>"><{$item.content_menu}></a>
                    <{else}>
                        <a class="menuMain" href="<{$item.url}>"
                           title="<{$item.content_menu}>"><{$item.content_menu}></a>
                    <{/if}>
                <{/foreach}>

            <{/foreach}>

        <{/foreach}>
    </div>
<{/if}>
<{if $block.type == 'vertical' || $block.type == 'horizontal'}>
    <div id="content-menu">
        <ul id="menu">
            <{foreach item=item from=$block.menu}>
                <li class="<{$item.content_type}>-main">
                    <{if $item.content_type == 'link'}>
                        <a class="menuMain" href="<{$item.content_link}>"
                           title="<{$item.content_menu}>"><{$item.content_menu}></a>
                    <{else}>
                        <a class="menuMain" href="<{$item.url}>"
                           title="<{$item.content_menu}>"><{$item.content_menu}></a>
                    <{/if}>
                    <{if $item.child_nb != 0}>
                        <ul>
                            <{foreach item=sub from=$item.child_array}>
                                <li class="<{$sub.content_type}>">
                                    <{if $item.content_type == 'link'}>
                                        <a class="menuMain" href="<{$item.content_link}>"
                                           title="<{$item.content_menu}>"><{$item.content_menu}></a>
                                    <{else}>
                                        <a class="menuMain" href="<{$item.url}>"
                                           title="<{$item.content_menu}>"><{$item.content_menu}></a>
                                    <{/if}>
                                    <{if $sub.child_nb != 0}>
                                        <ul>
                                            <{foreach item=end from=$sub.child_array}>
                                                <li class="<{$end.content_type}>">
                                                    <{if $item.content_type == 'link'}>
                                                        <a class="menuMain" href="<{$item.content_link}>"
                                                           title="<{$item.content_menu}>"><{$item.content_menu}></a>
                                                    <{else}>
                                                        <a class="menuMain" href="<{$item.url}>"
                                                           title="<{$item.content_menu}>"><{$item.content_menu}></a>
                                                    <{/if}>
                                                </li>
                                            <{/foreach}>
                                        </ul>
                                    <{/if}>
                                </li>
                            <{/foreach}>
                        </ul>
                    <{/if}>
                </li>
            <{/foreach}>
        </ul>
    </div>
    <script type="text/javascript">
        initMenu();
    </script>
<{/if}>
