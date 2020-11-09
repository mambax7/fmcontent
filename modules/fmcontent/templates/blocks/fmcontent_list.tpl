<{if $block.show == 'news'}>
    <div class="itemBlock">
        <{foreach item=content from=$block.contents}>
            <div class="item">
                <div class="itemHead">
                    <div class="itemTitle"><h3><a title="<{$content.title}>"
                                                  href="<{$content.url}>"><{$content.title}></a></h3></div>
                </div>
                <{if $block.date}>
                    <div class="itemInfo"><{$smarty.const._MI_FMCONTENT_DATE}> :<{$content.date}></div>
                <{/if}>
                <{if $block.img || $block.description}>
                    <div class="itemBody" id="content_<{$content.content_id}>">
                        <{if $block.img && $content.content_img}>
                            <div class="itemImg">
                                <img width="<{$block.width}>" class="<{$block.float}>"
                                     src="<{$block.imgurl}><{$content.content_img}>" alt="<{$content.title}>">
                            </div>
                        <{/if}>
                        <{if $block.description}>
                            <{if $content.content_short}>
                                <div class="itemText"><{$content.content_short}></div>
                            <{else}>
                                <div class="itemText"><{$content.content_text|truncate:300}></div>
                            <{/if}>
                        <{/if}>
                        <div class="clear"></div>
                    </div>
                <{/if}>
            </div>
        <{/foreach}>
    </div>
<{elseif $block.show == 'list'}>
    <div class="itemBlock">
        <{if $block.img || $block.description}>
            <ul class="itemList">
                <{foreach item=content from=$block.contents}>
                    <{if $block.img && $content.content_img}>
                        <div class="itemImg">
                            <img width="<{$block.width}>" class="<{$block.float}>"
                                 src="<{$block.imgurl}><{$content.content_img}>" alt="<{$content.title}>">
                        </div>
                    <{/if}>
                    <li>
                        <h3><a href="<{$content.url}>" title="<{$content.content_title}>"><{$content.content_title}></a>
                        </h3>
                        <{if $block.date}>
                            <div class="itemPostDate"><{$content.date}></div>
                        <{/if}>
                        <{if $block.description}>
                            <{if $content.content_short}>
                                <div class="itemText"><{$content.content_short}></div>
                            <{else}>
                                <div class="itemText"><{$content.content_text|truncate:130:""}></div>
                            <{/if}>
                        <{/if}>
                        <div class="clear"></div>
                    </li>
                <{/foreach}>
            </ul>
        <{else}>
            <ul>
                <{foreach item=content from=$block.contents}>
                    <li>
                        <h3><a href="<{$content.url}>" title="<{$content.content_title}>"><{$content.content_title}></a>
                        </h3>
                        <{if $block.date}>
                            <div class="itemPostDate"><{$content.date}></div>
                        <{/if}>
                    </li>
                <{/foreach}>
            </ul>
        <{/if}>
    </div>
<{elseif $block.show == 'spotlight'}>
    <div class="itemBlock">
        <{foreach item=content from=$block.contents}>
            <{if $content.content_default == 1}>
                <div class="itemBlockLeft">
                    <div class="item">
                        <div class="itemHead">
                            <div class="itemTitle"><h2><a title="<{$content.title}>"
                                                          href="<{$content.url}>"><{$content.title}></a></h2></div>
                        </div>
                        <{if $block.date}>
                            <div class="itemInfo"><{$smarty.const._MI_FMCONTENT_DATE}> :<{$content.date}></div>
                        <{/if}>
                        <{if $block.img || $block.description}>
                            <div class="itemBody" id="content_<{$content.content_id}>">
                                <{if $block.img && $content.content_img}>
                                    <div class="itemImg">
                                        <img width="<{$block.width}>" class="<{$block.float}>"
                                             src="<{$block.imgurl}><{$content.content_img}>" alt="<{$content.title}>">
                                    </div>
                                <{/if}>
                                <{if $block.description}>
                                    <{if $content.content_short}>
                                        <div class="itemText"><{$content.content_short|truncate:200}></div>
                                    <{else}>
                                        <div class="itemText"><{$content.content_text|truncate:200}></div>
                                    <{/if}>
                                    <div class="itemMore"><a title="<{$content.title}>"
                                                             href="<{$content.url}>"><{$smarty.const._MI_FMCONTENT_MORE}></a>
                                    </div>
                                <{/if}>
                                <div class="clear"></div>
                            </div>
                        <{/if}>
                    </div>
                </div>
            <{/if}>
        <{/foreach}>
        <{foreach item=content from=$block.contents}>
            <{if $content.content_default == 0}>
                <div class="itemBlockRight">
                    <h3><a href="<{$content.url}>"
                           title="<{$content.content_title}>"><{$content.content_title|truncate:80}></a></h3>
                </div>
            <{/if}>
        <{/foreach}>
        <div class="clear"></div>
    </div>
<{/if}>
