<{include file="db:fmcontent_header.tpl"}>
<section class="fmcontent">

    <{if $type == type1 || $type == type3}>
        <{if $default}>
            <article id="default-content" class="item">
                <header>
                    <div class="itemTitle "><h2><{$default.content_title}></h2></div>
                    <div class="itemPostDate"><{$smarty.const._FMCONTENT_DATE}>:
                        <time datetime="<{$default.content_create}>" pubdate><{$default.content_create}></time>
                        <{if $xoops_isadmin}>
                            <span class="itemAdminLink">
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
                </header>
                <{if $default.content_short}>
                    <div class="itemShort">
                        <{if $default.content_img}>
                            <figure class="<{$imgfloat}>">
                                <div class="itemImg gallery">
                                    <{if $img_lightbox}>
                                        <a href="<{$default.imgurl}>" title="<{$content.content_title}>">
                                            <img width="<{$imgwidth}>" class="content_img" src="<{$default.imgurl}>"
                                                 alt="<{$default.content_title}>">
                                        </a>
                                    <{else}>
                                        <img width="<{$imgwidth}>" class="content_img" src="<{$default.imgurl}>"
                                             alt="<{$default.content_title}>">
                                    <{/if}>
                                </div>
                                <figcaption><{$default.content_title}></figcaption>
                            </figure>
                        <{/if}>
                        <{$default.content_short}><a class="itemMore" href="<{$default.url}>"
                                                     title="<{$smarty.const._FMCONTENT_MORE}>"><{$smarty.const._FMCONTENT_MORE}></a>
                        <div class="clear"></div>
                    </div>
                <{else}>
                    <div class="itemText">
                        <{if $default.content_img}>
                            <figure class="<{$imgfloat}>">
                                <div class="gallery">
                                    <{if $img_lightbox}>
                                        <a href="<{$default.imgurl}>">
                                            <img width="<{$imgwidth}>" class="content_img" src="<{$default.imgurl}>"
                                                 alt="<{$default.content_title}>">
                                        </a>
                                    <{else}>
                                        <img width="<{$imgwidth}>" class="content_img" src="<{$default.imgurl}>"
                                             alt="<{$default.content_title}>">
                                    <{/if}>
                                </div>
                                <figcaption><{$default.content_title}></figcaption>
                            </figure>
                        <{/if}>
                        <{$default.content_text}>
                        <div class="clear"></div>
                    </div>
                <{/if}>
            </article>
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
            <article class="itemBody">
                <{if $topic_img}>
                    <figure class="<{$imgfloat}>">
                        <div class="itemImg gallery">
                            <{if $img_lightbox}>
                                <a href="<{$topic_imgurl}>" title="<{$topic_title}>">
                                    <img width="<{$imgwidth}>" class="content_img" src="<{$topic_imgurl}>"
                                         alt="<{$topic_title}>">
                                </a>
                            <{else}>
                                <img width="<{$imgwidth}>" class="content_img" src="<{$topic_imgurl}>"
                                     alt="<{$topic_title}>">
                            <{/if}>
                        </div>
                    </figure>
                <{/if}>
                <{$topic_desc}>
            </article>
        <{/if}>

        <{if $showtype == 1 && ($content_limit != 0 || !$content_topic)}>
            <{foreach item=content from=$contents}>
                <article class="item">
                    <header>
                        <div class="itemHead">
                            <div class="itemTitle">
                                <h2>
                                    <a href="<{$content.url}>"
                                       title="<{$content.content_title}>"><{$content.content_title}></a>
                                </h2>
                            </div>
                        </div>
                        <div class="itemInfo">
                            <{if $info.author}>
                                <span class="itemPoster smallsmall">
                <{$smarty.const._FMCONTENT_AUTHOR}>: <a href="<{$xoops_url}>/user.php?id=<{$content.content_uid}>"
                                                        title="<{$content.owner}>"><{$content.owner}></a><{if $alluserpost}> (<a
                                        href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?user=<{$content.content_uid}>"
                                        title="<{$smarty.const._FMCONTENT_AUTHOR_ALL_DESC}><{$content.owner}>"><{$smarty.const._FMCONTENT_AUTHOR_ALL}></a>)<{/if}>
                </span>
                                <{if $info.date || $info.hits}> &bull;<{/if}>
                            <{/if}>
                            <{if $info.date}>
                                <span class="itemPostDate">
              <{$smarty.const._FMCONTENT_DATE}>: <time datetime="<{$content.content_create}>"
                                                       pubdate><{$content.content_create}></time><{if $content.content_update != $content.content_create}> &bull; <{$smarty.const._FMCONTENT_UPDATE}>: <time
                                    datetime="<{$content.content_update}>"><{$content.content_update}></time><{/if}>
            </span>
                                <{if $info.hits}> &bull;<{/if}>
                            <{/if}>
                            <{if $info.hits}>
                                <span class="itemStats"><{$content.content_hits}> <{$smarty.const._FMCONTENT_HITS}></span>
                            <{/if}>
                            <{if $info.showtopic && $content.content_topic}>
                                <span class="itemPermaLink"> &bull; <{$smarty.const._FMCONTENT_PUBTOPIC}>: <a
                                            href="<{$content.topicurl}>"
                                            title="<{$smarty.const._FMCONTENT_PUBTOPIC}> <{$content.topic}>"><{$content.topic}></a></span>
                            <{/if}>
                        </div>
                    </header>

                    <div class="itemBody" id="content_<{$content.content_id}>">
                        <{if $content.content_img}>
                            <figure class="<{$imgfloat}>">
                                <div class="itemImg gallery">
                                    <{if $img_lightbox}>
                                        <a href="<{$content.imgurl}>" title="<{$content.content_title}>">
                                            <img width="<{$imgwidth}>" class="content_img" src="<{$content.imgurl}>"
                                                 alt="<{$content.content_title}>">
                                        </a>
                                    <{else}>
                                        <img width="<{$imgwidth}>" class="content_img" src="<{$content.imgurl}>"
                                             alt="<{$content.content_title}>">
                                    <{/if}>
                                </div>
                            </figure>
                        <{/if}>
                        <{if $content.content_short}>
                            <div class="itemText"><{$content.content_short}></div>
                        <{else}>
                            <div class="itemText"><{$content.content_text|truncate:300}></div>
                        <{/if}>
                        <div class="clear"></div>
                    </div>

                    <footer class="itemFoot">
                        <{if $info.coms}>
                            <span class="itemPermaLink"><{if $content.content_comments}><{$content.content_comments}> <{$smarty.const._FMCONTENT_COMS}><{else}><{$smarty.const._FMCONTENT_NOCOMS}><{/if}></span>
                        <{/if}>
                        <{if $xoops_isadmin}>
                            <span class="itemAdminLink">
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
                    </footer>
                </article>
            <{/foreach}>
        <{/if}>

        <{if $showtype == 2 && ($content_limit != 0 || !$content_topic)}>
            <table summary="<{$topic_title}> <{$smarty.const._FMCONTENT_RELATED}>">
                <thead>
                <tr>
                    <!--<th><{$smarty.const._FMCONTENT_ID}></th>-->
                    <th><{$smarty.const._FMCONTENT_TITLE}></th>
                    <{if !$content_topic}>
                        <th><{$smarty.const._FMCONTENT_TOPIC}></th><{/if}>
                    <{if $info.author}>
                        <th><{$smarty.const._FMCONTENT_AUTHOR}></th><{/if}>
                    <{if $info.date}>
                        <th><{$smarty.const._FMCONTENT_DATE}></th><{/if}>
                    <{if $info.hits}>
                        <th><{$smarty.const._FMCONTENT_HITS}></th><{/if}>
                    <{if $info.coms}>
                        <th><{$smarty.const._FMCONTENT_COMS}></th><{/if}>
                </tr>
                </thead>
                <tbody>
                <{foreach item=content from=$contents}>
                    <tr class="<{cycle values="even,odd"}>">
                        <!--<td><{$content.content_id}></td>-->
                        <td><a href="<{$content.url}>" title="<{$content.content_title}>"><{$content.content_title}></a>
                        </td>
                        <{if !$content_topic}>
                            <td><a href="<{$content.topicurl}>" title="<{$content.topic}>"><{$content.topic}></a>
                            </td><{/if}>
                        <{if $info.author}>
                            <td><a title="<{$content.owner}>"
                                   href="<{$xoops_url}>/user.php?id=<{$content.content_uid}>"><{$content.owner}></a>
                            </td><{/if}>
                        <{if $info.date}>
                            <td><{$content.content_create}></td><{/if}>
                        <{if $info.hits}>
                            <td><{$content.content_hits}></td><{/if}>
                        <{if $info.coms}>
                            <td><{$content.content_comments}></td><{/if}>
                    </tr>
                <{/foreach}>
                </tbody>
            </table>
        <{/if}>

        <{if $showtype == 3 && ($content_limit != 0 || !$content_topic)}>
            <{foreach item=content from=$contents}>
                <article class="item">
                    <div class="itemBody" id="content_<{$content.content_id}>">
                        <figure>
                            <div class="itemImg center gallery">
                                <{if $img_lightbox}>
                                    <a href="<{$content.imgurl}>">
                                        <img width="<{$imgwidth}>" class="content_img" src="<{$content.imgurl}>"
                                             alt="<{$content.content_title}>">
                                    </a>
                                <{else}>
                                    <img width="<{$imgwidth}>" class="content_img" src="<{$content.imgurl}>"
                                         alt="<{$content.content_title}>">
                                <{/if}>
                            </div>
                            <figcaption><{$content.content_imgdesc}></figcaption>
                        </figure>
                        <{if $content.content_short}>
                            <div class="itemText"><{$content.content_short}></div>
                        <{else}>
                            <div class="itemText"><{$content.content_text|truncate:300}></div>
                        <{/if}>
                    </div>

                    <header>
                        <div class="itemHead">
                            <div class="itemTitle">
                                <h2>
                                    <a href="<{$content.url}>"
                                       title="<{$content.content_title}>"><{$content.content_title}></a>
                                </h2>
                            </div>
                        </div>
                        <div class="itemInfo">
                            <{if $info.author}>
                                <span class="itemPoster smallsmall">
                <{$smarty.const._FMCONTENT_AUTHOR}>: <a href="<{$xoops_url}>/user.php?id=<{$content.content_uid}>"
                                                        title="<{$content.owner}>"><{$content.owner}></a><{if $alluserpost}> (<a
                                        href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?user=<{$content.content_uid}>"
                                        title="<{$smarty.const._FMCONTENT_AUTHOR_ALL_DESC}><{$content.owner}>"><{$smarty.const._FMCONTENT_AUTHOR_ALL}></a>)<{/if}>
                </span>
                                <{if $info.date || $info.hits}> &bull;<{/if}>
                            <{/if}>
                            <{if $info.date}>
                                <span class="itemPostDate">
              <{$smarty.const._FMCONTENT_DATE}>: <time datetime="<{$content.content_create}>"
                                                       pubdate><{$content.content_create}></time><{if $content.content_update != $content.content_create}> &bull; <{$smarty.const._FMCONTENT_UPDATE}>: <time
                                    datetime="<{$content.content_update}>"><{$content.content_update}></time><{/if}>
            </span>
                                <{if $info.hits}> &bull;<{/if}>
                            <{/if}>
                            <{if $info.hits}>
                                <span class="itemStats"><{$content.content_hits}> <{$smarty.const._FMCONTENT_HITS}></span>
                            <{/if}>
                            <{if $info.showtopic && $content.content_topic}>
                                <span class="itemPermaLink"> &bull; <{$smarty.const._FMCONTENT_PUBTOPIC}>: <a
                                            href="<{$content.topicurl}>"
                                            title="<{$smarty.const._FMCONTENT_PUBTOPIC}> <{$content.topic}>"><{$content.topic}></a></span>
                            <{/if}>
                        </div>
                    </header>

                    <footer class="itemFoot">
                        <{if $info.coms}>
                            <span class="itemPermaLink"><{if $content.content_comments}><{$content.content_comments}> <{$smarty.const._FMCONTENT_COMS}><{else}><{$smarty.const._FMCONTENT_NOCOMS}><{/if}></span>
                        <{/if}>
                        <{if $xoops_isadmin}>
                            <span class="itemAdminLink">
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
                    </footer>

                </article>
            <{/foreach}>
        <{/if}>

        <{if $showtype == 4 && ($content_limit != 0 || !$content_topic)}>
            <nav class="itemList">
                <ul>
                    <{foreach item=content from=$contents}>
                        <li>
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
            </nav>
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
            <th><{$smarty.const._FMCONTENT_TOPIC_ID}></th>
            <th><{$smarty.const._FMCONTENT_TOPIC_NAME}></th>
            <th><{$smarty.const._FMCONTENT_TOPIC_DESC}></th>
            <th><{$smarty.const._FMCONTENT_TOPIC_IMG}></th>
            </thead>
            <tbody>
            <{foreach item=topic from=$contents}>
                <tr class="odd">
                    <td class="width5"><img src="assets/images/icons/puce.png"
                                            alt="<{$topic.topic_title}>"><{$topic.topic_id}></td>
                    <td class="txtcenter bold top"><a title="<{$topic.topic_title}>"
                                                      href="<{$topic.topicurl}>"><{$topic.topic_title}></a></td>
                    <td <{if !$topic.topic_img}>colspan="2"<{/if}> class="top"><{$topic.topic_desc}></td>
                    <{if $topic.topic_img}>
                        <td class="top"><img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                             src="<{$topic.imgurl}>" alt="<{$topic.topic_title}>"></td><{/if}>
                </tr>
            <{/foreach}>
            </tbody>
        </table>
    <{/if}>

    <{if $type == type4}>
        <{if $advertisement}>
            <div class="itemAde"><{$advertisement}></div>
        <{/if}>
        <article id="default-content" class="item">
            <header>
                <div class="itemTitle "><h2><{$contents.content_title}></h2></div>
                <div class="itemPostDate"><{$smarty.const._FMCONTENT_DATE}>:
                    <time datetime="<{$contents.content_create}>" pubdate><{$contents.content_create}></time>
                    <{if $xoops_isadmin}>
                        <span class="itemAdminLink">
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
            </header>
            <{if $contents.content_short}>
                <div class="itemShort">
                    <{if $contents.content_img}>
                        <figure class="<{$imgfloat}>">
                            <div class="itemImg gallery">
                                <{if $img_lightbox}>
                                    <a href="<{$contents.imgurl}>" title="<{$content.content_title}>">
                                        <img width="<{$imgwidth}>" class="content_img" src="<{$contents.imgurl}>"
                                             alt="<{$contents.content_title}>">
                                    </a>
                                <{else}>
                                    <img width="<{$imgwidth}>" class="content_img" src="<{$contents.imgurl}>"
                                         alt="<{$contents.content_title}>">
                                <{/if}>
                            </div>
                            <figcaption><{$contents.content_title}></figcaption>
                        </figure>
                    <{/if}>
                    <{$contents.content_short}><a class="itemMore" href="<{$contents.url}>"
                                                  title="<{$smarty.const._FMCONTENT_MORE}>"><{$smarty.const._FMCONTENT_MORE}></a>
                    <div class="clear"></div>
                </div>
            <{else}>
                <div class="itemText">
                    <{if $contents.content_img}>
                        <figure class="<{$imgfloat}>">
                            <div class="gallery">
                                <{if $img_lightbox}>
                                    <a href="<{$contents.imgurl}>">
                                        <img width="<{$imgwidth}>" class="content_img" src="<{$contents.imgurl}>"
                                             alt="<{$contents.content_title}>">
                                    </a>
                                <{else}>
                                    <img width="<{$imgwidth}>" class="content_img" src="<{$contents.imgurl}>"
                                         alt="<{$contents.content_title}>">
                                <{/if}>
                            </div>
                            <figcaption><{$contents.content_title}></figcaption>
                        </figure>
                    <{/if}>
                    <{$contents.content_text}>
                    <div class="clear"></div>
                </div>
            <{/if}>
        </article>
    <{/if}>

</section>
