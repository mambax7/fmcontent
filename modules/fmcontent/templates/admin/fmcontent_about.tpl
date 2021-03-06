<{include file="$xoops_rootpath/modules/fmcontent/templates/admin/fmcontent_header.tpl"}>

<div id="about">
    <div class="floatleft width50">
        <fieldset>
            <legend class="label"><{$module_name}></legend>
            <div class="floatleft width20 modulelogo">
                <img src="../<{$module_icon}>" alt="<{$name}>">
            </div>
            <div class="floatright width75 line140 marg5 pad5">
                <div class="line140 marg2 pad2"><span
                            class="bold modulename"><{$module_name}> <{$module_version}></span></div>
                <div class="line140 marg2 pad2"><span
                            class="bold"><{$smarty.const._FMCONTENT_ABOUT_AUTHOR}></span> <{$module_author}></div>
                <div class="line140 marg2 pad2"><span
                            class="bold"><{$smarty.const._FMCONTENT_ABOUT_CREDITS}></span> <{$module_credits}></div>
                <div class="line140 marg2 pad2"><span class="bold"><{$smarty.const._FMCONTENT_ABOUT_LICENSE}></span> <a
                            class="tooltip" title="<{$module_license}>" href="<{$module_license_url}>"
                            target="_blank"><{$module_license}></a></div>
                <div class="line140 marg2 pad2">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input name="cmd" value="_s-xclick" type="hidden">
                        <input name="hosted_button_id" value="6KJ7RW5DR3VTJ" type="hidden">
                        <input src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit"
                               alt="PayPal - The safer, easier way to pay online!" type="image">
                        <img alt="" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" height="1" width="1" border="0">
                    </form>
                </div>
            </div>
            <div class="endline"></div>
        </fieldset>
        <fieldset>
            <legend class="label"><{$smarty.const._FMCONTENT_ABOUT_MODULE_INFO}></legend>
            <div class="aboutposition">
                <div class="line140 marg2 pad2"><span
                            class="bold"><{$smarty.const._FMCONTENT_ABOUT_DESCRIPTION}></span> <{$module_description}>
                </div>
                <div class="line140 marg2 pad2"><span
                            class="bold"><{$smarty.const._FMCONTENT_ABOUT_RELEASEDATE}></span> <{$module_release_date}>
                </div>
                <div class="line140 marg2 pad2"><span
                            class="bold"><{$smarty.const._FMCONTENT_ABOUT_UPDATEDATE}></span> <{$module_last_update}>
                </div>
                <div class="line140 marg2 pad2"><span
                            class="bold"><{$smarty.const._FMCONTENT_ABOUT_MODULE_STATUS}></span> <{$module_status}>
                </div>
                <div class="line140 marg2 pad2">
                    <span class="bold"><{$smarty.const._FMCONTENT_ABOUT_WEBSITE}></span>
                    <a class="tooltip" href="<{$module_website_url}>" rel="external"
                       title="<{$module_website_name}> - <{$module_website_url}>"><{$module_website_name}></a>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="floatright width50">
        <fieldset>
            <legend class="label"><{$smarty.const._FMCONTENT_ABOUT_CHANGELOG}></legend>
            <div class="txtchangelog"><{$module_changelog}></div>
        </fieldset>
    </div>
    <div class="endline"></div>
</div>
