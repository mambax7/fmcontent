<!DOCTYPE html>
<html xml:lang="<{$xoops_langcode}>" lang="<{$xoops_langcode}>">
<head>
    <title><{if $xoops_pagetitle !=''}><{$xoops_pagetitle}> : <{/if}><{$xoops_sitename}></title>

    <meta http-equiv="content-type" content="text/html; charset=<{$xoops_charset}>">
    <meta name="keywords" content="<{$meta_keywords}>">
    <meta name="description" content="<{$meta_description}>">
    <meta name="author" content="voltan">
    <meta name="copyright" content="<{$meta_copyright}>">
    <meta name="generator" content="Bluefish 2.0.3">
    <meta name="robots" content="noindex,nofollow">

    <link rel="shortcut icon" type="image/ico" href="<{$xoops_url}>/favicon.ico">
    <link rel="stylesheet" type="text/css" media="all" href="<{$xoops_url}>/xoops.css">
    <link rel="stylesheet" type="text/css" media="all" href="<{$xoops_url}>/modules/<{$module}>/assets/css/style.css">
    <link rel="stylesheet" type="text/css" media="all" href="<{$xoops_url}>/modules/<{$module}>/assets/css/print.css">
    <link rel="stylesheet" type="text/css" media="all" href="<{$localstyle}>">

</head>
<!--  onload="window.print()" -->
<body onload="window.print()">
<div id="xo-print">
    <div id="xo-print-content">
        <{if $print_logo}>
        <div class="<{$print_logofloat}> spacer"><img src="<{$print_logourl}>" alt="<{$xoops_sitename}>"></div><{/if}>
        <div class="item">
            <{if $print_title}>
                <div class="itemTitle spacer"><{$content.title}></div><{/if}>
            <{if $print_author || $print_date}>
                <div class="itemInfo">
                    <{if $print_date}><span class="itemText"><{$content.date}></span> - <{/if}>
                    <{if $print_author}><span class="itemText"><{$content.author}></span><{/if}>
                </div>
            <{/if}>
            <div class="itemBody <{$print_columns}>">
                <{if $print_img && $content.img}>
                    <div class="itemImg">
                        <img width="<{$imgwidth}>" class="<{$imgfloat}> content_img" src="<{$content.imgurl}>"
                             alt="<{$content.title}>">
                    </div>
                <{/if}>
                <{if $print_short && $content.short}>
                    <div class="itemText spacer txtjustify"><{$content.short}></div><{/if}>
                <{if $print_text && $content.text}>
                    <div class="itemText spacer txtjustify"><{$content.text}></div><{/if}>
                <div class="clear"></div>
            </div>
            <{if $print_link}>
                <div class="itemLink"><{$content.link}></div>
            <{/if}>
        </div>
    </div>
</div>
</body>
</html>
