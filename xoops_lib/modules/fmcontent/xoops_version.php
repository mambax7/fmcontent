<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * FmContent configuration file
 * Manage content page
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 */

//if (!isset($forMods)) {
//    exit('Module not found');
//}

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$modversion = [
    // Main setting
    'version'             => 1.11,
    'module_status'       => 'Beta 1',
    'release_date'        => '2017/07/23',
    'name'                => $moduleDirName,
    'description'         => _MI_FMCONTENT_DESC,
    'author'              => 'Andricq Nicolas (AKA MusS)',
    'credits'             => 'The XOOPS Project, Voltan, Mamba',
    'license'             => 'GNU GPL 2.0',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html',
    'image'               => 'assets/images/logoModule.png',
    'dirname'             => $moduleDirName,
    'module_website_url'  => 'https://xoops.org/',
    'module_website_name' => 'XOOPS',
    'help'                => 'page=help',
    // Admin things
    'system_menu'         => 1,
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // Modules scripts
    'onInstall'           => 'admin/action.module.php',
    'onUpdate'            => 'admin/action.module.php',
    'onUninstall'         => 'admin/action.module.php',
    // Main menu
    'hasMain'             => 1,
    // Recherche
    'hasSearch'           => 1,
    // Commentaires
    'hasComments'         => 1,
    // ------------------- Min Requirements -------------------
    'min_php'             => '5.5',
    'min_xoops'           => '2.5.9',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.5'],
];
// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_FMCONTENT_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_FMCONTENT_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_FMCONTENT_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_FMCONTENT_SUPPORT, 'link' => 'page=support'],
];
//Recherche
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'fmcontent_search';

// Commentaires
$modversion['comments']['itemName']            = 'id';
$modversion['comments']['pageName']            = 'content.php';
$modversion['comments']['callbackFile']        = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'fmcontent_com_approve';
$modversion['comments']['callback']['update']  = 'fmcontent_com_update';

// Templates
$modversion['templates'][] = ['file' => 'fmcontent_legacy_index.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_legacy_content.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_html5_index.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_html5_content.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_ui_content.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_ui_index.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_rss.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_bookmarkme.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_header.tpl', 'description' => ''];
$modversion['templates'][] = ['file' => 'fmcontent_topic.tpl', 'description' => ''];

// Menu
$modversion['sub'][] = [
    'name' => _FMCONTENT_SUBMIT,
    'url'  => 'submit.php',
];
$modversion['sub'][] = [
    'name' => _FMCONTENT_TOPIC,
    'url'  => 'topic.php',
];

// Blocks
$modversion['blocks'][] = [
    'file'        => 'menu.php',
    'name'        => _FMCONTENT_MENU,
    'description' => '',
    'show_func'   => 'fmcontent_menu_show',
    'edit_func'   => 'fmcontent_menu_edit',
    'options'     => $modversion['dirname'] . '|mainmenu|-1|content_title|ASC',
    'template'    => 'fmcontent_menu.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'page.php',
    'name'        => _FMCONTENT_PAGE,
    'description' => '',
    'show_func'   => 'fmcontent_page_show',
    'edit_func'   => 'fmcontent_page_edit',
    'options'     => '0|' . $modversion['dirname'],
    'template'    => 'fmcontent_page.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'list.php',
    'name'        => _FMCONTENT_LIST,
    'description' => '',
    'show_func'   => 'fmcontent_list_show',
    'edit_func'   => 'fmcontent_list_edit',
    'options'     => $modversion['dirname'] . '|news|10|100|1|1|1|content_create|180|left|DESC|0',
    'template'    => 'fmcontent_list.tpl',
];

// Settings
// Load class
xoops_load('xoopslists');

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_GENERAL',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'form_editor',
    'title'       => '_FMCONTENT_FORM_EDITOR',
    'description' => '_FMCONTENT_FORM_EDITOR_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => \XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor'),
    'default'     => 'dhtmltextarea',
];

// Get groups
$memberHandler = xoops_getHandler('member');
$xoopsgroups    = $memberHandler->getGroupList();
foreach ($xoopsgroups as $key => $group) {
    $groups[$group] = $key;
}
$modversion['config'][] = [
    'name'        => 'groups',
    'title'       => '_FMCONTENT_GROUPS',
    'description' => '_FMCONTENT_GROUPS_DESC',
    'formtype'    => 'select_multi',
    'valuetype'   => 'array',
    'options'     => $groups,
    'default'     => $groups,
];

$modversion['config'][] = [
    'name'        => 'editinplace',
    'title'       => '_FMCONTENT_EDITINPLACE',
    'description' => '_FMCONTENT_EDITINPLACE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'advertisement',
    'title'       => '_FMCONTENT_ADVERTISEMENT',
    'description' => '_FMCONTENT_ADVERTISEMENT_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => '',
];

$modversion['config'][] = [
    'name'        => 'tellafriend',
    'title'       => '_FMCONTENT_TELLAFRIEND',
    'description' => '_FMCONTENT_TELLAFRIEND_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
];

$modversion['config'][] = [
    'name'        => 'usetag',
    'title'       => '_FMCONTENT_USETAG',
    'description' => '_FMCONTENT_USETAG_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_SEO',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'friendly_url',
    'title'       => '_FMCONTENT_FRIENDLYURL',
    'description' => '_FMCONTENT_FRIENDLYURL_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_URL_STANDARD => 'none',
        _FMCONTENT_URL_REWRITE  => 'rewrite',
        _FMCONTENT_URL_SHORT    => 'short',
    ],
    'default'     => 'none',
];

$modversion['config'][] = [
    'name'        => 'rewrite_mode',
    'title'       => '_FMCONTENT_REWRITEBASE',
    'description' => '_FMCONTENT_REWRITEBASE_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [_FMCONTENT_REWRITEBASE_MODS => '/modules/', _FMCONTENT_REWRITEBASE_ROOT => '/'],
    'default'     => '/modules/',
];

$modversion['config'][] = [
    'name'        => 'lenght_id',
    'title'       => '_FMCONTENT_LENGHTID',
    'description' => '_FMCONTENT_LENGHTID_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
    'default'     => '1',
];

$modversion['config'][] = [
    'name'        => 'rewrite_name',
    'title'       => '_FMCONTENT_REWRITENAME',
    'description' => '_FMCONTENT_REWRITENAME_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => $modversion['dirname'],
];

$modversion['config'][] = [
    'name'        => 'rewrite_ext',
    'title'       => '_FMCONTENT_REWRITEEXT',
    'description' => '_FMCONTENT_REWRITEEXT_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '.html',
];

$modversion['config'][] = [
    'name'        => 'static_name',
    'title'       => '_FMCONTENT_STATICNAME',
    'description' => '_FMCONTENT_STATICNAME_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'static',
];

$modversion['config'][] = [
    'name'        => 'topic_name',
    'title'       => '_FMCONTENT_TOPICNAME',
    'description' => '_FMCONTENT_TOPICNAME_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'topic',
];

$modversion['config'][] = [
    'name'        => 'regular_expression',
    'title'       => '_FMCONTENT_REGULAR_EXPRESSION',
    'description' => '_FMCONTENT_REGULAR_EXPRESSION_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _FMCONTENT_REGULAR_EXPRESSION_CONFIG,
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_DISPLAY',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'homepage',
    'title'       => '_FMCONTENT_HOMEPAGE',
    'description' => '_FMCONTENT_HOMEPAGE_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_HOMEPAGE_1 => 'type1',
        _FMCONTENT_HOMEPAGE_2 => 'type2',
        _FMCONTENT_HOMEPAGE_3 => 'type3',
        _FMCONTENT_HOMEPAGE_4 => 'type4',
    ],
    'default'     => 'type1',
];

$modversion['config'][] = [
    'name'        => 'template',
    'title'       => '_FMCONTENT_TEMPLATE',
    'description' => '_FMCONTENT_TEMPLATE_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_TEMPLATE_1 => 'legacy',
        _FMCONTENT_TEMPLATE_2 => 'ui',
        _FMCONTENT_TEMPLATE_3 => 'html5',
    ],
    'default'     => 'legacy',
];
/*
$modversion['config'][] = array(
    'name' => 'disp_option',
    'title' => '_FMCONTENT_DISP_OPTION',
    'description' => '_FMCONTENT_DISP_OPTION_DESC',
    'formtype' => 'select',
    'valuetype' => 'int',
    'options' => array(_FMCONTENT_DISP_OPTION_MODULE => '0', _FMCONTENT_DISP_OPTION_TOPIC => '1'),
    'default' => '0');
*/
$modversion['config'][] = [
    'name'        => 'showtype',
    'title'       => '_FMCONTENT_SHOWTYPE',
    'description' => '_FMCONTENT_SHOWTYPE_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [
        _FMCONTENT_SHOWTYPE_1 => '1',
        _FMCONTENT_SHOWTYPE_2 => '2',
        _FMCONTENT_SHOWTYPE_3 => '3',
        _FMCONTENT_SHOWTYPE_4 => '4',
    ],
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_date',
    'title'       => '_FMCONTENT_DISPDATE',
    'description' => '_FMCONTENT_DISPDATE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_topic',
    'title'       => '_FMCONTENT_DISPTOPIC',
    'description' => '_FMCONTENT_DISPTOPIC_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_author',
    'title'       => '_FMCONTENT_DISPAUTHOR',
    'description' => '_FMCONTENT_DISPAUTHOR_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_navlink',
    'title'       => '_FMCONTENT_DISPNAV',
    'description' => '_FMCONTENT_DISPNAV_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_pdflink',
    'title'       => '_FMCONTENT_DISPPDF',
    'description' => '_FMCONTENT_DISPPDF_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_printlink',
    'title'       => '_FMCONTENT_DISPPRINT',
    'description' => '_FMCONTENT_DISPPRINT_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_hits',
    'title'       => '_FMCONTENT_DISHITS',
    'description' => '_FMCONTENT_DISHITS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_maillink',
    'title'       => '_FMCONTENT_DISPMAIL',
    'description' => '_FMCONTENT_DISPMAIL_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'disp_coms',
    'title'       => '_FMCONTENT_DISPCOMS',
    'description' => '_FMCONTENT_DISPCOMS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'perpage',
    'title'       => '_FMCONTENT_PERPAGE',
    'description' => '_FMCONTENT_PERPAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'columns',
    'title'       => '_FMCONTENT_COLUMNS',
    'description' => '_FMCONTENT_COLUMNS_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'showsort',
    'title'       => '_FMCONTENT_SHOWSORT',
    'description' => '_FMCONTENT_SHOWSORT_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_SHOWSORT_1 => 'content_id',
        _FMCONTENT_SHOWSORT_2 => 'content_create',
        _FMCONTENT_SHOWSORT_3 => 'content_update',
        _FMCONTENT_SHOWSORT_4 => 'content_title',
        _FMCONTENT_SHOWSORT_5 => 'content_order',
        _FMCONTENT_SHOWSORT_6 => 'RAND()',
    ],
    'default'     => 'content_id',
];

$modversion['config'][] = [
    'name'        => 'showorder',
    'title'       => '_FMCONTENT_SHOWORDER',
    'description' => '_FMCONTENT_SHOWORDER_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [_FMCONTENT_DESC => 'DESC', _FMCONTENT_ASC => 'ASC'],
    'default'     => 'DESC',
];

$modversion['config'][] = [
    'name'        => 'show_social_book',
    'title'       => '_FMCONTENT_SOCIAL',
    'description' => '_FMCONTENT_SOCIAL_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [
        _FMCONTENT_NONE          => 0,
        _FMCONTENT_SOCIALNETWORM => 1,
        _FMCONTENT_BOOKMARK      => 2,
        _FMCONTENT_BOTH          => 3,
    ],
    'default'     => 0,
];

$modversion['config'][] = [
    'name'        => 'multiple_columns',
    'title'       => '_FMCONTENT_MULTIPLE_COLUMNS',
    'description' => '_FMCONTENT_MULTIPLE_COLUMNS_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_MULTIPLE_COLUMNS_1 => 'onecolumn',
        _FMCONTENT_MULTIPLE_COLUMNS_2 => 'twocolumn',
        _FMCONTENT_MULTIPLE_COLUMNS_3 => 'threecolumn',
        _FMCONTENT_MULTIPLE_COLUMNS_4 => 'forcolumn',
    ],
    'default'     => 'onecolumn',
];

$modversion['config'][] = [
    'name'        => 'alluserpost',
    'title'       => '_FMCONTENT_ALLUSERPOST',
    'description' => '_FMCONTENT_ALLUSERPOST_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_RSS',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'rss_show',
    'title'       => '_FMCONTENT_RSS_SHOW',
    'description' => '_FMCONTENT_RSS_SHOW_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'rss_timecache',
    'title'       => '_FMCONTENT_RSS_TIMECACHE',
    'description' => '_FMCONTENT_RSS_TIMECACHE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 60,
];

$modversion['config'][] = [
    'name'        => 'rss_perpage',
    'title'       => '_FMCONTENT_RSS_PERPAGE',
    'description' => '_FMCONTENT_RSS_PERPAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'rss_logo',
    'title'       => '_FMCONTENT_RSS_LOGO',
    'description' => '_FMCONTENT_RSS_LOGO_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '/assets/images/logo.png',
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_FILE',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'file_dir',
    'title'       => '_FMCONTENT_FILE_DIR',
    'description' => '_FMCONTENT_FILE_DIR_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '/uploads/fmcontent/file/',
];

$modversion['config'][] = [
    'name'        => 'file_size',
    'title'       => '_FMCONTENT_FILE_SIZE',
    'description' => '_FMCONTENT_FILE_SIZE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '10485760',
];

$modversion['config'][] = [
    'name'        => 'file_mime',
    'title'       => '_FMCONTENT_FILE_MIME',
    'description' => '_FMCONTENT_FILE_MIME_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => 'image/gif|image/jpeg|image/pjpeg|image/x-png|image/png|application/x-zip-compressed|application/zip|application/rar|application/pdf|application/x-gtar|application/x-tar|application/x-gzip|application/msword|application/vnd.ms-excel|application/vnd.ms-powerpoint|application/vnd.oasis.opendocument.text|application/vnd.oasis.opendocument.spreadsheet|application/vnd.oasis.opendocument.presentation|application/vnd.oasis.opendocument.graphics|application/vnd.oasis.opendocument.chart|application/vnd.oasis.opendocument.formula|application/vnd.oasis.opendocument.database|application/vnd.oasis.opendocument.image|application/vnd.oasis.opendocument.text-master|video/mpeg|video/quicktime|video/x-msvideo|video/x-flv|video/mp4|video/x-ms-wmv|video/quicktime|audio/mpeg',
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_IMAGE',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'img_dir',
    'title'       => '_FMCONTENT_IMAGE_DIR',
    'description' => '_FMCONTENT_IMAGE_DIR_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '/uploads/fmcontent/img/',
];

$modversion['config'][] = [
    'name'        => 'img_size',
    'title'       => '_FMCONTENT_IMAGE_SIZE',
    'description' => '_FMCONTENT_IMAGE_SIZE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '5242880',
];

$modversion['config'][] = [
    'name'        => 'img_maxwidth',
    'title'       => '_FMCONTENT_IMAGE_MAXWIDTH',
    'description' => '_FMCONTENT_IMAGE_MAXWIDTH_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '400',
];

$modversion['config'][] = [
    'name'        => 'img_maxheight',
    'title'       => '_FMCONTENT_IMAGE_MAXHEIGHT',
    'description' => '_FMCONTENT_IMAGE_MAXHEIGHT_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '300',
];

$modversion['config'][] = [
    'name'        => 'img_mime',
    'title'       => '_FMCONTENT_IMAGE_MIME',
    'description' => '_FMCONTENT_IMAGE_MIME_DESC',
    'formtype'    => 'select_multi',
    'valuetype'   => 'array',
    'default'     => ['image/gif', 'image/jpeg', 'image/png'],
    'options'     => [
        'bmp'  => 'image/bmp',
        'gif'  => 'image/gif',
        'jpeg' => 'image/pjpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'jpe'  => 'image/jpeg',
        'png'  => 'image/png',
    ],
];

$modversion['config'][] = [
    'name'        => 'imgwidth',
    'title'       => '_FMCONTENT_IMAGE_WIDTH',
    'description' => '_FMCONTENT_IMAGE_WIDTH_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 180,
];

$modversion['config'][] = [
    'name'        => 'imgfloat',
    'title'       => '_FMCONTENT_IMAGE_FLOAT',
    'description' => '_FMCONTENT_IMAGE_FLOAT_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [_FMCONTENT_IMAGE_LEFT => 'left', _FMCONTENT_IMAGE_RIGHT => 'right'],
    'default'     => 'left',
];

$modversion['config'][] = [
    'name'        => 'img_lightbox',
    'title'       => '_FMCONTENT_IMAGE_LIGHTBOX',
    'description' => '_FMCONTENT_IMAGE_LIGHTBOX_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_PRINT',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'print_logo',
    'title'       => '_FMCONTENT_PRINT_LOGO',
    'description' => '_FMCONTENT_PRINT_LOGO_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'print_logofloat',
    'title'       => '_FMCONTENT_PRINT_LOGOFLOAT',
    'description' => '_FMCONTENT_PRINT_LOGOFLOAT_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_PRINT_LEFT   => 'txtleft',
        _FMCONTENT_PRINT_RIGHT  => 'txtright',
        _FMCONTENT_PRINT_CENTER => 'txtcenter',
    ],
    'default'     => 'txtcenter',
];

$modversion['config'][] = [
    'name'        => 'print_logourl',
    'title'       => '_FMCONTENT_PRINT_LOGOURL',
    'description' => '_FMCONTENT_PRINT_LOGOURL_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '/assets/images/logo.png',
];

$modversion['config'][] = [
    'name'        => 'print_title',
    'title'       => '_FMCONTENT_PRINT_TITLE',
    'description' => '_FMCONTENT_PRINT_TITLE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'print_img',
    'title'       => '_FMCONTENT_PRINT_IMG',
    'description' => '_FMCONTENT_PRINT_IMG_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'print_short',
    'title'       => '_FMCONTENT_PRINT_SHORT',
    'description' => '_FMCONTENT_PRINT_SHORT_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'print_text',
    'title'       => '_FMCONTENT_PRINT_TEXT',
    'description' => '_FMCONTENT_PRINT_TEXT_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'print_date',
    'title'       => '_FMCONTENT_PRINT_DATE',
    'description' => '_FMCONTENT_PRINT_DATE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'print_author',
    'title'       => '_FMCONTENT_PRINT_AUTHOR',
    'description' => '_FMCONTENT_PRINT_AUTHOR_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'print_link',
    'title'       => '_FMCONTENT_PRINT_LINK',
    'description' => '_FMCONTENT_PRINT_LINK_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'print_columns',
    'title'       => '_FMCONTENT_MULTIPLE_COLUMNS',
    'description' => '_FMCONTENT_MULTIPLE_COLUMNS_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_MULTIPLE_COLUMNS_1 => 'onecolumn',
        _FMCONTENT_MULTIPLE_COLUMNS_2 => 'twocolumn',
        _FMCONTENT_MULTIPLE_COLUMNS_3 => 'threecolumn',
        _FMCONTENT_MULTIPLE_COLUMNS_4 => 'forcolumn',
    ],
    'default'     => 'onecolumn',
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_BREADCRUMB',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'bc_show',
    'title'       => '_FMCONTENT_BREADCRUMB_SHOW',
    'description' => '_FMCONTENT_BREADCRUMB_SHOW_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'bc_modname',
    'title'       => '_FMCONTENT_BREADCRUMB_MODNAME',
    'description' => '_FMCONTENT_BREADCRUMB_MODNAME_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'bc_tohome',
    'title'       => '_FMCONTENT_BREADCRUMB_TOHOME',
    'description' => '_FMCONTENT_BREADCRUMB_TOHOME_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_ADMIN',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'admin_index_limit',
    'title'       => '_FMCONTENT_ADMIN_INDEX_LIMIT',
    'description' => '_FMCONTENT_ADMIN_INDEX_LIMIT_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 5,
];

$modversion['config'][] = [
    'name'        => 'admin_showorder',
    'title'       => '_FMCONTENT_ADMIN_SHOWORDER',
    'description' => '_FMCONTENT_ADMIN_SHOWORDER_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [_FMCONTENT_DESC => 'DESC', _FMCONTENT_ASC => 'ASC'],
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'admin_showsort',
    'title'       => '_FMCONTENT_ADMIN_SHOWSORT',
    'description' => '_FMCONTENT_ADMIN_SHOWSORT_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_SHOWSORT_1 => 'content_id',
        _FMCONTENT_SHOWSORT_2 => 'content_create',
        _FMCONTENT_SHOWSORT_3 => 'content_update',
        _FMCONTENT_SHOWSORT_4 => 'content_title',
        _FMCONTENT_SHOWSORT_5 => 'content_order',
        _FMCONTENT_SHOWSORT_6 => 'RAND()',
    ],
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'admin_perpage',
    'title'       => '_FMCONTENT_ADMIN_PERPAGE',
    'description' => '_FMCONTENT_ADMIN_PERPAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 50,
];

$modversion['config'][] = [
    'name'        => 'admin_showorder_topic',
    'title'       => '_FMCONTENT_ADMIN_SHOWORDER_TOPIC',
    'description' => '_FMCONTENT_ADMIN_SHOWORDER_TOPIC_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [_FMCONTENT_DESC => 'DESC', _FMCONTENT_ASC => 'ASC'],
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'admin_showsort_topic',
    'title'       => '_FMCONTENT_ADMIN_SHOWSORT_TOPIC',
    'description' => '_FMCONTENT_ADMIN_SHOWSORT_TOPIC_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _FMCONTENT_ADMIN_SHOWSORT_TOPIC_1 => 'topic_id',
        _FMCONTENT_ADMIN_SHOWSORT_TOPIC_2 => 'topic_weight',
        _FMCONTENT_ADMIN_SHOWSORT_TOPIC_3 => 'topic_date_created',
    ],
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'admin_perpage_topic',
    'title'       => '_FMCONTENT_ADMIN_PERPAGE_TOPIC',
    'description' => '_FMCONTENT_ADMIN_PERPAGE_TOPIC_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_FMCONTENT_BREAK_COMNOTI',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

// default admin editor
xoops_load('XoopsEditorHandler');
$editorHandler = \XoopsEditorHandler::getInstance();
$editorList    = array_flip($editorHandler->getList());

$modversion['config'][] = [
    'name'        => 'editorAdmin',
    'title'       => '_MI_FMCONTENT_EDITOR_ADMIN',
    'description' => '_MI_FMCONTENT_EDITOR_ADMIN_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtmltextarea',
    'options'     => $editorList,
];

$modversion['config'][] = [
    'name'        => 'editorUser',
    'title'       => '_MI_FMCONTENT_EDITOR_USER',
    'description' => '_MI_FMCONTENT_EDITOR_USER_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtmltextarea',
    'options'     => $editorList,
];

/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
