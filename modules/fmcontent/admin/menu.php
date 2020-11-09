<?php
/**
 * Menu configuration file
 *
 * LICENSE
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      Andricq Nicolas (AKA MusS)
 * @since       2.5.0
 */

use Xmf\Module\Admin;
use XoopsModules\Fmcontent\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */


require XOOPS_TRUST_PATH . '/modules/fmcontent/preloads/autoloader.php';
//include dirname(__DIR__) . '/preloads/autoloader.php';

//require_once __DIR__ . '/header.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}

$adminmenu[] = [
    'title' => _FMCONTENT_HOME,
    'link'  => 'admin/index.php',
    'icon'  => 'assets/images/admin/home.png',
];

$adminmenu[] = [
    'title' => _FMCONTENT_TOPIC,
    'link'  => 'admin/topic.php',
    'icon'  => 'assets/images/admin/category.png',
];

$adminmenu[] = [
    'title' => _FMCONTENT_CONTENT,
    'link'  => 'admin/content.php',
    'icon'  => 'assets/images/admin/content.png',
];

$adminmenu[] = [
    'title' => _FMCONTENT_FILE,
    'link'  => 'admin/file.php',
    'icon'  => 'assets/images/admin/file.png',
];

$adminmenu[] = [
    'title' => _FMCONTENT_TOOLS,
    'link'  => 'admin/tools.php',
    'icon'  => 'assets/images/admin/administration.png',
];

$adminmenu[] = [
    'title' => _FMCONTENT_PERM,
    'link'  => 'admin/permissions.php',
    'icon'  => 'assets/images/admin/permissions.png',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link' => 'admin/blocksadmin.php',
    'icon' => $pathIcon32 . '/block.png',
];

if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link' => 'admin/migrate.php',
        'icon' => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _FMCONTENT_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => 'assets/images/admin/about.png',
];
