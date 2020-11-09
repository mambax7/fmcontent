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
 * FmContent Admin page
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 * @author      Hossein Azizabadi (AKA Voltan)
 */

use Xmf\Module\Admin;
use Xmf\Request;
use Xmf\Yaml;
use XoopsModules\Fmcontent\{Common,
    Helper,
    Utility
};

/** @var Admin $adminObject */
/** @var Helper $helper */
/** @var Utility $utility */

if (!isset($forMods)) {
    exit('Module not found');
}

include __DIR__ . '/admin_header.php';

$adminObject = Admin::getInstance();
// Display Admin header
xoops_cp_header();
// Add module stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/admin.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');

$topicHandler   = Helper::getInstance()->getHandler('Topic');
$pageHandler = Helper::getInstance()->getHandler('Page');

$folder = [
    XOOPS_ROOT_PATH . '/uploads/fmcontent/',
    XOOPS_ROOT_PATH . '/uploads/fmcontent/img',
    XOOPS_ROOT_PATH . '/uploads/fmcontent/file',
];

//$adminObject = new ModuleAdmin();
$adminObject->addInfoBox(_FMCONTENT_ADMENU1);
$adminObject->addInfoBoxLine(sprintf(_FMCONTENT_INDEX_TOPICS, $topicHandler->getTopicCount($forMods)), '');

$adminObject->addInfoBox(_FMCONTENT_ADMENU2);
$adminObject->addInfoBoxLine(sprintf(_FMCONTENT_INDEX_CONTENTS, $pageHandler->getContentItemCount($forMods)), '');

foreach (array_keys($folder) as $i) {
    $adminObject->addConfigBoxLine($folder[$i], 'folder');
    $adminObject->addConfigBoxLine([$folder[$i], '777'], 'chmod');
}

$xoopsTpl->assign('navigation', 'index');
$xoopsTpl->assign('navtitle', _FMCONTENT_HOME);
$xoopsTpl->assign('renderindex', $adminObject->renderIndex());

// Call template file
$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $forMods->getVar('dirname') . '/templates/admin/fmcontent_index.tpl');

//------------- End Test Data ----------------------------

//$adminObject->displayIndex();

/**
 * @param $yamlFile
 * @return array|bool
 */
function loadAdminConfig($yamlFile)
{
    $config = Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
    return $config;
}

/**
 * @param $yamlFile
 */
function hideButtons($yamlFile)
{
    $app['displaySampleButton'] = 0;
    Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

/**
 * @param $yamlFile
 */
function showButtons($yamlFile)
{
    $app['displaySampleButton'] = 1;
    Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

$op = Request::getString('op', 0, 'GET');

switch ($op) {
    case 'hide_buttons':
        hideButtons($yamlFile);
        break;
    case 'show_buttons':
        showButtons($yamlFile);
        break;
}

echo $utility::getServerStats();

require __DIR__ . '/admin_footer.php';
