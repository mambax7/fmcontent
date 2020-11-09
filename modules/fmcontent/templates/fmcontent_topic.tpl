<div class="fmcontent">
    <{if $advertisement}>
        <div class="itemAde"><{$advertisement}></div>
    <{/if}>
    <table id="xo-content-data" class="outer" cellspacing="1" width="100%">
        <thead>
        <th class="txtcenter"><{$smarty.const._FMCONTENT_TOPIC_NAME}></th>
        <th class="txtcenter"><{$smarty.const._FMCONTENT_TOPIC_DESC}></th>
        <th class="txtcenter"><{$smarty.const._FMCONTENT_TOPIC_IMG}></th>
        </thead>
        <tbody>
        <{foreach item=topic from=$topics}>
            <tr class="odd">
                <td class="txtcenter bold top"><a title="<{$topic.topic_title}>"
                                                  href="<{$topic.topicurl}>"><{$topic.topic_title}></a></td>
                <td<{if !$topic.topic_img}>colspan
                ="2"<{/if}> class="top"><{$topic.topic_desc}></td>
                <{if $topic.topic_img}>
                    <td class="top txtcenter"><img width="<{$imgwidth}>" class="<{$imgfloat}> content_img"
                                                   src="<{$topic.imgurl}>" alt="<{$topic.topic_title}>"></td><{/if}>
            </tr>
        <{/foreach}>
        </tbody>
    </table>
    <{if $topic_pagenav}>
        <div class="pagenave"><{$topic_pagenav}></div>
    <{/if}>
</div>
