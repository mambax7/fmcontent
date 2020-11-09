<{include file="db:fmcontent_header.tpl"}>

<div class="fmcontent">
    <!-- Display error message -->
    <{if $content_error}>
        <div class="errorMsg"><{$content_error}></div>
    <{else}>
        <div class="item">
            <{if $content.content_titleview}>
                <!-- Display content header -->
                <div class="itemHead">
                    <div class="itemTitle">
                        <h2><{$content.content_title}></h2>
                    </div>
                </div>
            <{/if}>
            <div class="itemInfo">
                <{if $content.author}>
                    <span class="itemPoster"><{$smarty.const._FMCONTENT_AUTHOR}>: <a
                                href="<{$xoops_url}>/user.php?id=<{$content.content_uid}>"
                                title="<{$content.author}>"><{$content.author}></a><{if $alluserpost}> (<a
                            href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?user=<{$content.content_uid}>"
                            title="<{$smarty.const._FMCONTENT_AUTHOR_ALL_DESC}><{$content.author}>"><{$smarty.const._FMCONTENT_AUTHOR_ALL}></a>)<{/if}></span>
                    <{if $link.date || $link.hits || $content.content_comments || $link.topicshow}> &bull;<{/if}>
                <{/if}>
                <{if $link.date}>
                    <span class="itemPostDate"><{$smarty.const._FMCONTENT_DATE}>: <{$content.content_create}></span>
                    <{if $link.hits || $content.content_comments || $link.topicshow}> &bull;<{/if}>
                <{/if}>
                <{if $link.hits}>
                    <span class="itemStats"><{$content.content_hits}> <{$smarty.const._FMCONTENT_HITS}></span>
                    <{if $content.content_comments || $link.topicshow }> &bull;<{/if}>
                <{/if}>
                <{if $link.coms}>
                    <span class="itemPermaLink"><{if $content.content_comments > 1}><a href="#comm"
                                                                                       title="<{$smarty.const._FMCONTENT_COM}>"><{$content.content_comments}> <{$smarty.const._FMCONTENT_COMS}></a><{elseif $content.content_comments == 1}>
                            <a href="#comm"
                               title="<{$smarty.const._FMCONTENT_COM}>"><{$content.content_comments}> <{$smarty.const._FMCONTENT_COM}></a><{/if}></span>
                    <{if $link.topicshow && $content.content_comments}> &bull;<{/if}>
                <{/if}>
                <{if $link.topicshow}>
                    <span class="itemPermaLink"><{$smarty.const._FMCONTENT_PUBTOPIC}>: <a
                                title="<{$smarty.const._FMCONTENT_PUBTOPIC}> <{$link.topic}>"
                                href="<{$link.topicurl}>"><{$link.topic}></a></span>
                <{/if}>
            </div>
            <div class="itemBody">
                <!-- Display content body -->
                <{if $content.content_short}>
                    <div class="itemShort"><{$content.content_short}></div>
                <{/if}>
                <{if $advertisement && $content.content_short}>
                    <div class="itemAde"><{$advertisement}></div>
                <{/if}>
                <div class="itemText editable <{$multiple_columns}>" id="content_<{$content.content_id}>">
                    <{if $content.content_img}>
                        <div class="gallery">
                            <{if $img_lightbox}>
                                <a href="<{$content.imgurl}>" title="<{$content.content_title}>">
                                    <img class="<{$imgfloat}> content_img" src="<{$content.imgurl}>"
                                         alt="<{$content.content_title}>">
                                </a>
                            <{else}>
                                <img class="<{$imgfloat}> content_img" src="<{$content.imgurl}>"
                                     alt="<{$content.content_title}>">
                            <{/if}>
                        </div>
                    <{/if}>
                    <{$content.content_text}>
                    <div class="clear spacer"></div>
                    <{if $content.content_author}>
                        <div class="itemSource">
                            <{$smarty.const._FMCONTENT_SOURCE}><a href="<{$content.content_source}>"
                                                                  title="<{$smarty.const._FMCONTENT_SOURCE}><{$content.content_author}>"
                                                                  rel="external"><{$content.content_author}></a>
                        </div>
                    <{/if}>
                    <{if $link.date}>
                        <{if $content.content_update != $content.content_create}>
                            <div class="itemPostDate"><{$smarty.const._FMCONTENT_UPDATE}>
                                : <{$content.content_update}></div>
                        <{/if}>
                    <{/if}>
                </div>
                <{if $advertisement && !$content.content_short}>
                    <div class="itemAde"><{$advertisement}></div>
                <{/if}>
                <{if $content.content_file}>
                    <div class="itemPlayer">
                        <{foreach item=file from=$files}>
                            <{if $file.file_type == flv}>
                                <div class="itemVideo">
                                    <script type='text/javascript'
                                            src='<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/js/jwplayer/jwplayer.js'></script>
                                    <div id='mediaspace<{$file.file_id}>'></div>
                                    <script type='text/javascript'>
                                        jwplayer('mediaspace<{$file.file_id}>').setup({
                                            'flashplayer': '<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/js/jwplayer/player.swf',
                                            'file': '<{$file.fileurl}>',
                                            'title': '<{$file.file_title}>',
                                            'controlbar': 'bottom',
                                            'width': '<{$jwwidth}>',
                                            'height': '<{$jwheight}>'
                                        });
                                    </script>
                                </div>
                            <{elseif $file.file_type == mp3}>
                                <div class="itemMp3">
                                    <object type="application/x-shockwave-flash"
                                            data="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/js/audio/audio-player.swf"
                                            id="audioplayer1" height="35"
                                            width="400">
                                        <param name="movie"
                                               value="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/js/audio/audio-player.swf">
                                        <param name="FlashVars"
                                               value="playerID=audioplayer1&soundFile=<{$file.fileurl}>">
                                        <param name="quality" value="high">
                                        <param name="menu" value="false">
                                        <param name="wmode" value="transparent">
                                    </object>
                                </div>
                            <{/if}>
                        <{/foreach}>
                    </div>
                <{/if}>
            </div>

            <div class="itemFoot">
                <div class="itemIcons floatleft">
                    <{if $xoops_isadmin}>
                        <span class="itemAdminLink">
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=edit_content&amp;content_id=<{$content.content_id}>"
                       title="<{$smarty.const._FMCONTENT_EDIT}>"><img
                                src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/edit.png"
                                alt="<{$smarty.const._CONTENT_EDIT}>"></a>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=delete&amp;content_id=<{$content.content_id}>"
                       title="<{$smarty.const._FMCONTENT_DELETE}>"><img
                                src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/delete.png"
                                alt="<{$smarty.const._CONTENT_DELETE}>"></a>&nbsp;
                </span>
                    <{/if}>
                    <{if $link.print}>
                        <a href="<{$link.print}>" title="<{$smarty.const._FMCONTENT_PRINT}>"><img
                                    src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/printer.png"
                                    alt="<{$smarty.const._FMCONTENT_PRINT}>"></a>
                    <{/if}>
                    <{if $link.pdf}>
                        <a href="<{$link.pdf}>" title="<{$smarty.const._FMCONTENT_PDF}>"><img
                                    src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/pdf.png"
                                    alt="<{$smarty.const._FMCONTENT_PDF}>"></a>
                    <{/if}>
                    <{if $link.mail}>
                        <a href="<{$link.mail}>" title="<{$smarty.const._FMCONTENT_MAIL}>"><img
                                    src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/mail.png"
                                    alt="<{$smarty.const._FMCONTENT_MAIL}>"></a>
                    <{/if}>
                </div>
                <div class="floatright">
                    <{if $link.prev}>
                        <span class="floatleft"><a href="<{$link.prev}>"
                                                   title="<{$smarty.const._FMCONTENT_PREV}> : <{$link.prev_title}>"><img
                                        src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/prev.png"
                                        alt="<{$smarty.const._FMCONTENT_PREV}>"></a></span>
                    <{/if}>
                    <{if $link.next}>
                        <span class="floatright"><a href="<{$link.next}>"
                                                    title="<{$smarty.const._FMCONTENT_NEXT}> : <{$link.next_title}>"><img
                                        src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/next.png"
                                        alt="<{$smarty.const._FMCONTENT_NEXT}>"></a></span>
                    <{/if}>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <{if $tags}>
            <div class="tagbar"><{include file="db:tag_bar.tpl"}></div>
        <{/if}>

        <{if $content.content_file}>
            <ul>
                <{foreach item=file from=$files}>
                    <{if $file.file_type != flv && $file.file_type != mp3}>
                        <li><a title="<{$file.file_title}>" href="<{$file.fileurl}>"><{$file.file_title}></a></li>
                    <{/if}>
                <{/foreach}>
            </ul>
        <{/if}>
        <div class="bookmark"><{include file="db:fmcontent_bookmarkme.tpl"}></div>
        <{if $anon_canpost ==1 || $content.content_comments}>
            <div class="txtcenter comment_bar">
                <{$commentsnav}>
                <{$lang_notice}>
            </div>
        <{/if}>

        <{if $content.content_comments}>
            <div class="comments" id="comm">
                <!-- start comments loop -->
                <{if $comment_mode == "flat"}>
                    <{include file="db:system_comments_flat.tpl"}>
                <{elseif $comment_mode == "thread"}>
                    <{include file="db:system_comments_thread.tpl"}>
                <{elseif $comment_mode == "nest"}>
                    <{include file="db:system_comments_nest.tpl"}>
                <{/if}>
                <!-- end comments loop -->
            </div>
        <{/if}>

    <{/if}>
</div>
