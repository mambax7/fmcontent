<?php
/**
 * ****************************************************************************
 *  - TDMAssoc By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - GNU Licence Copyright (c)  (http://www.)
 *
 * La licence GNU GPL, garanti � l'utilisateur les droits suivants
 *
 * 1. La libert� d'ex�cuter le logiciel, pour n'importe quel usage,
 * 2. La libert� de l' �tudier et de l'adapter � ses besoins,
 * 3. La libert� de redistribuer des copies,
 * 4. La libert� d'am�liorer et de rendre publiques les modifications afin
 * que l'ensemble de la communaut� en b�n�ficie.
 *
 * @copyright           (http://www.)
 * @license            http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author             TDM ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return mixed
 */
function fmcontent_search($queryarray, $andor, $limit, $offset, $userid)
{
    $pageHandler = Helper::getInstance()->getHandler('Page');

    return $pageHandler->getSearchedContent($queryarray, $andor, $limit, $offset, $userid);
}
