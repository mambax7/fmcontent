<{include file="db:fmcontent_header.tpl"}>
<div class="fmcontent">

    <{if $type == type1 || $type == type3}>
        <{if $default}>
            <div class="ui-widget-content ui-corner-all" id="default">
                <div class="ui-widget-header ui-corner-all"><h2><{$default.content_title}></h2></div>
                <div class="ui-widget-content ui-corner-all">
                    <{if $default.content_short}>
                        <{if $default.content_img}>
                            <div class="itemImg gallery">
                                <{if $img_lightbox}>
                                    <a href="<{$default.imgurl}>">
                                        <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                             src="<{$default.imgurl}>" alt="<{$default.content_title}>">
                                    </a>
                                <{else}>
                                    <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                         src="<{$default.imgurl}>" alt="<{$default.content_title}>">
                                <{/if}>
                            </div>
                        <{/if}>
                        <{$default.content_short}>
                        <div class="clear spacer"></div>
                        <a class="itemMore" href="<{$default.url}>"
                           title="<{$smarty.const._FMCONTENT_MORE}>"><{$smarty.const._FMCONTENT_MORE}></a>
                    <{else}>
                        <{if $default.content_img}>
                            <div class="gallery">
                                <{if $img_lightbox}>
                                    <a href="<{$default.imgurl}>">
                                        <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                             src="<{$default.imgurl}>" alt="<{$default.content_title}>">
                                    </a>
                                <{else}>
                                    <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                         src="<{$default.imgurl}>" alt="<{$default.content_title}>">
                                <{/if}>
                            </div>
                        <{/if}>
                        <{$default.content_text}>
                        <div class="clear"></div>
                    <{/if}>
                </div>

                <div class="ui-state-default ui-corner-all right">
                    <span class="left"><{$smarty.const._FMCONTENT_DATE}>: <{$default.content_create}></span>
                    <{if $xoops_isadmin}>
                        <span>
                    &nbsp;<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=edit_content&amp;content_id=<{$default.content_id}>"
                             title="<{$smarty.const._FMCONTENT_EDIT}>"><img
                                        src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/edit.png"
                                        alt="<{$smarty.const._CONTENT_EDIT}>"></a>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=delete&amp;content_id=<{$default.content_id}>"
                       title="<{$smarty.const._FMCONTENT_DELETE}>"><img
                                src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/delete.png"
                                alt="<{$smarty.const._CONTENT_DELETE}>"></a>
                </span>
                    <{/if}>
                </div>
            </div>
        <{/if}>
        <{if $advertisement}>
            <div class="itemAde"><{$advertisement}></div>
        <{/if}>
        <{if $default}>
            <div class="related">
                <h2><{$smarty.const._FMCONTENT_RELATED}></h2>
            </div>
        <{else}>
            <div class="topic_header">
                <{if !$content_topic}>
                    <h2><{$modname}></h2>
                <{else}>
                    <h2><{$topic_title}></h2>
                <{/if}>
            </div>
        <{/if}>

        <{if $topic_img || $topic_desc}>
            <div class="itemBody">
                <{if $topic_img}>
                    <div class="itemImg gallery">
                        <{if $img_lightbox}>
                            <a href="<{$topic_imgurl}>" title="<{$topic_title}>">
                                <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img" src="<{$topic_imgurl}>"
                                     alt="<{$topic_title}>">
                            </a>
                        <{else}>
                            <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img" src="<{$topic_imgurl}>"
                                 alt="<{$topic_title}>">
                        <{/if}>
                    </div>
                <{/if}>
                <{$topic_desc}>
            </div>
        <{/if}>

        <{if $showtype == 1 && ($content_limit != 0 || !$content_topic)}>
            <{foreach item=content from=$contents}>
                <div class="ui-widget-content ui-corner-all">
                    <div class="ui-widget-header ui-corner-all">
                        <h2>
                            <a href="<{$content.url}>" title="<{$content.content_title}>"><{$content.content_title}></a>
                        </h2>
                    </div>
                    <div class="ui-widget-content ui-corner-all" id="content_<{$content.content_id}>">
                        <{if $content.content_img}>
                            <div class="itemImg gallery">
                                <{if $img_lightbox}>
                                    <a href="<{$content.imgurl}>" title="<{$content.content_title}>">
                                        <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                             src="<{$content.imgurl}>" alt="<{$content.content_title}>">
                                    </a>
                                <{else}>
                                    <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                         src="<{$content.imgurl}>" alt="<{$content.content_title}>">
                                <{/if}>
                            </div>
                        <{/if}>
                        <{if $content.content_short}>
                            <{$content.content_short}>
                        <{else}>
                            <{$content.content_text|truncate:300}>
                        <{/if}>
                        <div class="clear"></div>
                    </div>
                    <div class="ui-state-default ui-corner-all right">
                        <{if $info.author}>
                            <{$smarty.const._FMCONTENT_AUTHOR}>: <a
                            href="<{$xoops_url}>/user.php?id=<{$content.content_uid}>"
                            title="<{$content.owner}>"><{$content.owner}></a><{if $alluserpost}> (
                            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?user=<{$content.content_uid}>"
                               title="<{$smarty.const._FMCONTENT_AUTHOR_ALL_DESC}><{$content.owner}>"><{$smarty.const._FMCONTENT_AUTHOR_ALL}></a>
                            )
                        <{/if}>
                            <{if $info.date || $info.hits || $info.coms}> &bull;<{/if}>
                        <{/if}>
                        <{if $info.date}>
                            <{$smarty.const._FMCONTENT_DATE}>: <{$content.content_create}><{if $content.content_update != $content.content_create}> &bull; <{$smarty.const._FMCONTENT_UPDATE}>: <{$content.content_update}><{/if}>
                            <{if $info.hits || $info.coms}> &bull;<{/if}>
                        <{/if}>
                        <{if $info.hits}>
                            <{$content.content_hits}> <{$smarty.const._FMCONTENT_HITS}>
                        <{/if}>
                        <{if $info.coms}> &bull;<{/if}>
                        <{if $info.coms}>
                            <{if $content.content_comments}><{$content.content_comments}> <{$smarty.const._FMCONTENT_COMS}><{else}><{$smarty.const._FMCONTENT_NOCOMS}><{/if}>
                        <{/if}>
                        <{if $info.showtopic && $content.content_topic}>
                            &bull; <{$smarty.const._FMCONTENT_PUBTOPIC}>:
                            <a href="<{$content.topicurl}>"
                               title="<{$smarty.const._FMCONTENT_PUBTOPIC}> <{$content.topic}>"><{$content.topic}></a>
                        <{/if}>
                        <{if $xoops_isadmin}>
                            <span>
              &nbsp;<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=edit_content&amp;content_id=<{$content.content_id}>"
                       title="<{$smarty.const._FMCONTENT_EDIT}>"><img
                                            src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/edit.png"
                                            alt="<{$smarty.const._CONTENT_EDIT}>"></a>
              <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=delete&amp;content_id=<{$content.content_id}>"
                 title="<{$smarty.const._FMCONTENT_DELETE}>"><img
                          src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/delete.png"
                          alt="<{$smarty.const._CONTENT_DELETE}>"></a>
          </span>
                        <{/if}>
                    </div>
                </div>
            <{/foreach}>
        <{/if}>

        <{if $showtype == 2 && ($content_limit != 0 || !$content_topic)}>
            <table class="ui-widget-content ui-corner-all"
                   summary="<{$topic_title}> <{$smarty.const._FMCONTENT_RELATED}>">
                <thead>
                <tr>
                    <!--<th><{$smarty.const._FMCONTENT_ID}></th>-->
                    <th class="ui-widget-header"><{$smarty.const._FMCONTENT_TITLE}></th>
                    <{if !$content_topic}>
                        <th class="ui-widget-header"><{$smarty.const._FMCONTENT_TOPIC}></th><{/if}>
                    <{if $info.author}>
                        <th class="ui-widget-header"><{$smarty.const._FMCONTENT_AUTHOR}></th><{/if}>
                    <{if $info.date}>
                        <th class="ui-widget-header"><{$smarty.const._FMCONTENT_DATE}></th><{/if}>
                    <{if $info.hits}>
                        <th class="ui-widget-header"><{$smarty.const._FMCONTENT_HITS}></th><{/if}>
                    <{if $info.coms}>
                        <th class="ui-widget-header"><{$smarty.const._FMCONTENT_COMS}></th><{/if}>
                </tr>
                </thead>
                <tbody>
                <{foreach item=content from=$contents}>
                    <tr>
                        <!--<td><{$content.content_id}></td>-->
                        <td class="ui-widget-content"><a href="<{$content.url}>"
                                                         title="<{$content.content_title}>"><{$content.content_title}></a>
                        </td>
                        <{if !$content_topic}>
                            <td class="ui-widget-content"><a href="<{$content.topicurl}>"
                                                             title="<{$content.topic}>"><{$content.topic}></a>
                            </td><{/if}>
                        <{if $info.author}>
                            <td class="ui-widget-content"><a title="<{$content.owner}>"
                                                             href="<{$xoops_url}>/user.php?id=<{$content.content_uid}>"><{$content.owner}></a>
                            </td><{/if}>
                        <{if $info.date}>
                            <td class="ui-widget-content"><{$content.content_create}></td><{/if}>
                        <{if $info.hits}>
                            <td class="ui-widget-content"><{$content.content_hits}></td><{/if}>
                        <{if $info.coms}>
                            <td class="ui-widget-content"><{$content.content_comments}></td><{/if}>
                    </tr>
                <{/foreach}>
                </tbody>
            </table>
        <{/if}>

        <{if $showtype == 3 && ($content_limit != 0 || !$content_topic)}>
            <{foreach item=content from=$contents}>
                <div class="ui-widget-content ui-corner-all">
                    <div class="ui-widget-content ui-corner-all" id="content_<{$content.content_id}>">
                        <div class="center gallery">
                            <{if $img_lightbox}>
                                <a href="<{$content.imgurl}>" title="<{$content.content_title}>">
                                    <img width="<{$imgwidth}>" class="content_img ui-state-highlight pad5 ui-corner-all"
                                         src="<{$content.imgurl}>" alt="<{$content.content_title}>">
                                </a>
                            <{else}>
                                <img width="<{$imgwidth}>" class="content_img ui-state-highlight pad5 ui-corner-all"
                                     src="<{$content.imgurl}>" alt="<{$content.content_title}>">
                            <{/if}>
                        </div>
                        <{if $content.content_short}>
                            <{$content.content_short}>
                        <{else}>
                            <{$content.content_text|truncate:300}>
                        <{/if}>
                    </div>
                    <div class="ui-widget-header ui-corner-all">
                        <h2>
                            <a href="<{$content.url}>" title="<{$content.content_title}>"><{$content.content_title}></a>
                        </h2>
                    </div>
                    <div class="ui-state-default ui-corner-all">
                        <{if $info.author}>
                            <{$smarty.const._FMCONTENT_AUTHOR}>: <a
                            href="<{$xoops_url}>/user.php?id=<{$content.content_uid}>"
                            title="<{$content.owner}>"><{$content.owner}></a><{if $alluserpost}> (
                            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?user=<{$content.content_uid}>"
                               title="<{$smarty.const._FMCONTENT_AUTHOR_ALL_DESC}><{$content.owner}>"><{$smarty.const._FMCONTENT_AUTHOR_ALL}></a>
                            )
                        <{/if}>
                            <{if $info.date || $info.hits || $info.coms}> &bull;<{/if}>
                        <{/if}>
                        <{if $info.date}>
                            <{$smarty.const._FMCONTENT_DATE}>: <{$content.content_create}><{if $content.content_update != $content.content_create}> &bull; <{$smarty.const._FMCONTENT_UPDATE}>: <{$content.content_update}><{/if}>
                            <{if $info.hits || $info.coms}> &bull;<{/if}>
                        <{/if}>
                        <{if $info.hits}>
                            <{$content.content_hits}> <{$smarty.const._FMCONTENT_HITS}>
                        <{/if}>
                        <{if $info.coms}> &bull;<{/if}>
                        <{if $info.coms}>
                            <{if $content.content_comments}><{$content.content_comments}> <{$smarty.const._FMCONTENT_COMS}><{else}><{$smarty.const._FMCONTENT_NOCOMS}><{/if}>
                        <{/if}>
                        <{if $info.showtopic && $content.content_topic}>
                            &bull; <{$smarty.const._FMCONTENT_PUBTOPIC}>:
                            <a href="<{$content.topicurl}>"
                               title="<{$smarty.const._FMCONTENT_PUBTOPIC}> <{$content.topic}>"><{$content.topic}></a>
                        <{/if}>
                        <{if $xoops_isadmin}>
                            <span>
              &nbsp;<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=edit_content&amp;content_id=<{$content.content_id}>"
                       title="<{$smarty.const._FMCONTENT_EDIT}>"><img
                                            src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/edit.png"
                                            alt="<{$smarty.const._CONTENT_EDIT}>"></a>
              <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=delete&amp;content_id=<{$content.content_id}>"
                 title="<{$smarty.const._FMCONTENT_DELETE}>"><img
                          src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/delete.png"
                          alt="<{$smarty.const._CONTENT_DELETE}>"></a>
          </span>
                        <{/if}>
                    </div>
                </div>
            <{/foreach}>
        <{/if}>

        <{if $showtype == 4 && ($content_limit != 0 || !$content_topic)}>
            <div class="itemList">
                <ul class="ui-widget ui-helper-clearfix">
                    <{foreach item=content from=$contents}>
                        <li class="ui-state-default ui-corner-all">
                            <h3><a href="<{$content.url}>"
                                   title="<{$content.content_title}>"><{$content.content_title}></a></h3>
                            <{if $info.author || $info.date || $info.hits}>
                                <div>
                                    <{if $info.author}>
                                        <span class="itemPoster">
                      <a href="<{$xoops_url}>/user.php?id=<{$content.content_uid}>"
                         title="<{$content.owner}>"><{$content.owner}></a><{if $alluserpost}> (<a
                                                href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?user=<{$content.content_uid}>"
                                                title="<{$smarty.const._FMCONTENT_AUTHOR_ALL_DESC}><{$content.owner}>"><{$smarty.const._FMCONTENT_AUTHOR_ALL}></a>)<{/if}>
                      </span>
                                        <{if $info.date || $info.hits}> &bull;<{/if}>
                                    <{/if}>
                                    <{if $info.date}>
                                        <span class="itemPostDate"><{$content.content_create}></span>
                                        <{if $info.hits}> &bull;<{/if}>
                                    <{/if}>
                                    <{if $info.hits}>
                                        <span class="itemStats"><{$content.content_hits}> <{$smarty.const._FMCONTENT_HITS}></span>
                                    <{/if}>
                                </div>
                            <{/if}>
                        </li>
                    <{/foreach}>
                </ul>
            </div>
        <{/if}>

        <{if $content_pagenav}>
            <div class="pagenave"><{$content_pagenav}></div>
        <{/if}>
    <{/if}>

    <{if $type == type2}>
        <{if $advertisement}>
            <div class="itemAde"><{$advertisement}></div>
        <{/if}>
        <table id="xo-content-data" class="outer" cellspacing="1" width="100%">
            <thead>
            <th class="ui-widget-header"><{$smarty.const._FMCONTENT_TOPIC_ID}></th>
            <th class="ui-widget-header"><{$smarty.const._FMCONTENT_TOPIC_NAME}></th>
            <th class="ui-widget-header"><{$smarty.const._FMCONTENT_TOPIC_DESC}></th>
            <th class="ui-widget-header"><{$smarty.const._FMCONTENT_TOPIC_IMG}></th>
            </thead>
            <tbody>
            <{foreach item=topic from=$contents}>
                <tr class="odd">
                    <td class="ui-widget-content width5"><img src="assets/images/icons/puce.png"
                                                              alt="<{$topic.topic_title}>"><{$topic.topic_id}></td>
                    <td class="ui-widget-content top"><a title="<{$topic.topic_title}>"
                                                         href="<{$topic.topicurl}>"><{$topic.topic_title}></a></td>
                    <td class="ui-widget-content top"
                        <{if !$topic.topic_img}>colspan="2"<{/if}>><{$topic.topic_desc}></td>
                    <{if $topic.topic_img}>
                        <td class="ui-widget-content top"><img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                                               src="<{$topic.imgurl}>" alt="<{$topic.topic_title}>">
                        </td><{/if}>
                </tr>
            <{/foreach}>
            </tbody>
        </table>
    <{/if}>

    <{if $type == type4}>
        <{if $contents}>
            <{if $advertisement}>
                <div class="itemAde"><{$advertisement}></div>
            <{/if}>
            <div class="ui-widget-content ui-corner-all" id="default">
                <div class="ui-widget-header ui-corner-all"><h2><{$contents.content_title}></h2></div>
                <div class="ui-widget-content ui-corner-all">
                    <{if $contents.content_short}>
                        <{if $contents.content_img}>
                            <div class="itemImg gallery">
                                <{if $img_lightbox}>
                                    <a href="<{$contents.imgurl}>">
                                        <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                             src="<{$contents.imgurl}>" alt="<{$contents.content_title}>">
                                    </a>
                                <{else}>
                                    <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                         src="<{$contents.imgurl}>" alt="<{$contents.content_title}>">
                                <{/if}>
                            </div>
                        <{/if}>
                        <{$contents.content_short}>
                        <div class="clear spacer"></div>
                        <a class="itemMore" href="<{$contents.url}>"
                           title="<{$smarty.const._FMCONTENT_MORE}>"><{$smarty.const._FMCONTENT_MORE}></a>
                    <{else}>
                        <{if $contents.content_img}>
                            <div class="gallery">
                                <{if $img_lightbox}>
                                    <a href="<{$contents.imgurl}>">
                                        <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                             src="<{$contents.imgurl}>" alt="<{$contents.content_title}>">
                                    </a>
                                <{else}>
                                    <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                         src="<{$contents.imgurl}>" alt="<{$contents.content_title}>">
                                <{/if}>
                            </div>
                        <{/if}>
                        <{$contents.content_text}>
                        <div class="clear"></div>
                    <{/if}>
                </div>

                <div class="ui-state-default ui-corner-all right">
                    <span class="left"><{$smarty.const._FMCONTENT_DATE}>: <{$contents.content_create}></span>
                    <{if $xoops_isadmin}>
                        <span>
                    &nbsp;<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=edit_content&amp;content_id=<{$contents.content_id}>"
                             title="<{$smarty.const._FMCONTENT_EDIT}>"><img
                                        src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/edit.png"
                                        alt="<{$smarty.const._CONTENT_EDIT}>"></a>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/content.php?op=delete&amp;content_id=<{$contents.content_id}>"
                       title="<{$smarty.const._FMCONTENT_DELETE}>"><img
                                src="<{$xoops_url}>/modules/<{$xoops_dirname}>/assets/images/icons/delete.png"
                                alt="<{$smarty.const._CONTENT_DELETE}>"></a>
                </span>
                    <{/if}>
                </div>
            </div>
        <{/if}>
    <{/if}>

</div>
