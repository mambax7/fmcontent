<div class="itemBlock">
    <div class="itemHead">
        <div class="itemTitle">
            <h2><{$block.content_title}></h2>
        </div>
    </div>
    <{if $block.content_short}>
        <div class="itemBody">
            <{if $block.content_img}>
                <div class="itemImg">
                    <img width="<{$block.width}>" class="<{$block.float}> content_img"
                         src="<{$block.imgurl}><{$block.content_img}>" alt="<{$block.content_title}>">
                </div>
            <{/if}>
            <div class="itemText"><{$block.content_short}></div>
            <div class="itemMore">
                <a href="<{$block.link}>" title="<{$block.content_title}>"><{$smarty.const._MI_FMCONTENT_MORE}></a>
            </div>
            <div class="clear"></div>
        </div>
    <{else}>
        <div class="itemBody">
            <{if $block.content_img}>
                <div class="itemImg">
                    <img width="<{$block.width}>" class="<{$block.float}> content_img"
                         src="<{$block.imgurl}><{$block.content_img}>" alt="<{$block.content_title}>">
                </div>
            <{/if}>
            <div class="itemText"><{$block.content_text}></div>
            <div class="clear"></div>
        </div>
    <{/if}>
</div>
