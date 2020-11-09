<?php

namespace XoopsModules\Fmcontent;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * FmContent page class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Zoullou (http://www.zoullou.net)
 * @author      Hossein Azizabadi (AKA Voltan)
 */
defined('XOOPS_ROOT_PATH') || die('XOOPS Root Path not defined');

/**
 * Class Permission
 * @package XoopsModules\Fmcontent
 */
class Permission
{
    /**
     * @return \XoopsModules\Fmcontent\Permission
     */
    public static function &getHandler()
    {
        static $permHandler;
        if (!isset($permHandler)) {
            $permHandler = new self();
        }

        return $permHandler;
    }

    /**
     * @param $user
     * @return array|string
     */
    public static function getUserGroup($user)
    {
        if ($user instanceof \XoopsUser) {
            return $user->getGroups();
        }

        return XOOPS_GROUP_ANONYMOUS;
    }

    /**
     * @param $user
     * @param $perm
     * @param $forMods
     * @return mixed
     */
    public static function getAuthorizedTopic($user, $perm, $forMods)
    {
        static $authorizedCat;
        $userId = $user ? $user->getVar('uid') : 0;
        if (!isset($authorizedCat [$perm] [$userId])) {
            $groupPermHandler                = xoops_getHandler('groupperm');
            $moduleHandler                   = xoops_getHandler('module');
            $dirname                         = $forMods->getVar('dirname');
            $module                          = $moduleHandler->getByDirname($dirname);
            $authorizedCat [$perm] [$userId] = $groupPermHandler->getItemIds($perm, self::getUserGroup($user), $module->getVar('mid'));
        }

        return $authorizedCat [$perm] [$userId];
    }

    /**
     * @param $user
     * @param $perm
     * @param $topic_id
     * @param $forMods
     * @return bool
     */
    public function isAllowed($user, $perm, $topic_id, $forMods)
    {
        $autorizedCat = self::getAuthorizedTopic($user, $perm, $forMods);

        return in_array($topic_id, $autorizedCat, true);
    }

    /**
     * @param $forMods
     * @param $gperm_name
     * @param $groups_action
     * @param $id
     * @param $new
     */
    public static function setPermission($forMods, $gperm_name, $groups_action, $id, $new)
    {
        $grouppermHandler = xoops_getHandler('groupperm');

        if (!$new) {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('gperm_itemid', $id, '='));
            $criteria->add(new \Criteria('gperm_modid', $forMods->getVar('mid'), '='));
            $criteria->add(new \Criteria('gperm_name', $gperm_name, '='));
            $grouppermHandler->deleteAll($criteria);
        }

        if (isset($groups_action)) {
            foreach ($groups_action as $onegroup_id) {
                $grouppermHandler->addRight($gperm_name, $id, $onegroup_id, $forMods->getVar('mid'));
            }
        }
    }

    /**
     * @param $permtype
     * @param $forMods
     * @return mixed
     */
    public static function getItemIds($permtype, $forMods)
    {
        global $xoopsUser;
        $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $grouppermHandler = xoops_getHandler('groupperm');
        $categories       = $grouppermHandler->getItemIds($permtype, $groups, $forMods->getVar('mid'));

        return $categories;
    }
}
