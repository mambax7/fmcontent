<div id="help-template" class="outer">
    <{include file=$smarty.const._MI_FMCONTENT_HELP_HEADER}>

    <h4 class="odd">DESCRIPTION</h4> <br>

    <p class="even">
        fmContent is a XOOPS module to create scrolling texts (fmContents). You can create an unlimited number of
        fmContents
        and use them in 4 blocks. Texts can scroll from right to left and from top to bottom.</p>

    <p class="even">
        You can use a WYSIWYG editor to make your fmContents even more attractive</p>

    <!-- -----Help Content ---------- -->

    <h4 class="odd">Outline</h4>
    <ul>
        <li>This module provides content managment for Xoops.</li>
        <li>Also, it provides page content and dynamic menu.</li>
    </ul>
    <h4 class="odd">Home</h4>
    <ul>
        <li>View all created content page by using order and actions icons.</li>
        <li>
            <img src="<{$xoops_url}>/modules/fmcontent/assets/images/icons/up.png"
                 alt="<{$smarty.const._CONTENT_INDEX_UP}>" title="<{$smarty.const._CONTENT_INDEX_UP}>">&nbsp;
            <img src="<{$xoops_url}>/modules/fmcontent/assets/images/icons/down.png"
                 alt="<{$smarty.const._CONTENT_INDEX_DOWN}>" title="<{$smarty.const._CONTENT_INDEX_DOWN}>">&nbsp;:&nbsp;Reorder
            page and sub-page.
        </li>
        <li>
            <img src="<{xoModuleIcons16 search.png}>" alt="<{$smarty.const._PREVIEW}>"
                 title="<{$smarty.const._PREVIEW}>">&nbsp;View,
            <img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}>" title="<{$smarty.const._EDIT}>">&nbsp;edit
            and
            <img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}" title="<{$smarty.const._DELETE}">&nbsp;delete
            a page.
        </li>
    </ul>
    <h4 class="odd">Content</h4>
    <ul>
        <li>Add some different content for Xoops like page, link, section header and separator.</li>
        <li>All differnet content permit to create a dynamic menu link to the content page.</li>
        <li>
            "content": create your own page.<br>
            You must specify a page title, a menu title and the text of your page.<br>
            You can also define a page alias for choose an unique page name which is use for the url. (This is not
            mandatory, module can create the alias with the page name define)
        </li>
        <li>
            "link": add an internal or external link in your dynamic menu.<br>
            For exemple, you can create a simple link for redirect the the homepage of your site.
        </li>
        <li>"section header": create a non-clickable section for your menu.</li>
        <li>"separator": create a separator for your menu.</li>
    </ul>
    <h4 class="odd">Preferences</h4>
    <ul>
        <li>"Form Option": Choose your WYSIWYG editor. This setting need to have the xoops editor class made by phppp
            (DJ)
        </li>
        <li>"Use friendly url?": Use the standard mode for have url like 'index.php?page=My-Content', or use the
            friendly mode for have url like 'index.php/My-Content' and finally you can activate the
            url rewriting if your server permit this option for have url like 'content/My-Content.html' (You must copy
            the .htaccess in your modules directory or in the root path see the
            extras/readme.txt for more information)
        </li>
        <li>"Choose the url rewriting base": this setting work only if url rewriting module is activate on your server.
            This option permit to choose if you want to display the '/module/' in your
            url.
        </li>
        <li>"Groups access": Define the genral rule for group access.</li>
        <li>"Use module Tell a friend?": If Tell a friend module is installed on your Xoops, you can see this option and
            use it for send email.
        </li>
    </ul>

</div>
