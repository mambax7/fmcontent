<?xml version="1.0" encoding="<{$channel_charset}>"?>
<rss version="2.0">
    <channel>
        <title><{$channel_title}></title>
        <link><{$channel_link}></link>
        <description><{$channel_desc}></description>
        <lastBuildDate><{$channel_lastbuild}></lastBuildDate>
        <docs>http://backend.userland.com/rss/</docs>
        <generator><{$channel_generator}></generator>
        <category><{$channel_category}></category>
        <managingEditor><{$channel_editor}></managingEditor>
        <webMaster><{$channel_webmaster}></webMaster>
        <language><{$channel_language}></language>
        <{if $image_url != ""}>
            <image>
                <title><{$channel_title}></title>
                <url><{$image_url}></url>
                <link><{$channel_link}></link>
                <width><{$image_width}></width>
                <height><{$image_height}></height>
            </image>
        <{/if}>
        <{foreach item=content from=$contents}>
            <item>
                <title><{$content.content_title}></title>
                <link><{$content.url}></link>
                <description><{if $content.content_short}><{$content.content_short}><{else}><{$content.content_text|strip_tags|truncate:300}><{/if}></description>
                <pubDate><{$content.content_create}></pubDate>
                <guid><{$content.url}></guid>
            </item>
        <{/foreach}>
    </channel>
</rss>
