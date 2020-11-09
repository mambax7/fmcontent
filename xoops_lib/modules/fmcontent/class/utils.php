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
 * FmContent Utils class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 */
class Utils
{
    /**
     * Uploadimg function
     *
     * For manage all upload parts for images
     * Add topic , Edit topic , Add content , Edit content
     * @param mixed $forMods
     * @param mixed $type
     * @param mixed $obj
     * @param mixed $image
     */
    public static function uploadimg($forMods, $type, $obj, $image)
    {
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploader_img = new \XoopsMediaUploader(
            XOOPS_ROOT_PATH . xoops_getModuleOption('img_dir', $forMods->getVar('dirname')),
            xoops_getModuleOption('img_mime', $forMods->getVar('dirname')),
            xoops_getModuleOption('img_size', $forMods->getVar('dirname')),
            xoops_getModuleOption('img_maxwidth', $forMods->getVar('dirname')),
            xoops_getModuleOption('img_maxheight', $forMods->getVar('dirname'))
        );
        if ($uploader_img->fetchMedia($type)) {
            $uploader_img->setPrefix($type . '_');
            $uploader_img->fetchMedia($type);
            if (!$uploader_img->upload()) {
                $errors = $uploader_img->getErrors();
                fmcontent_Redirect('javascript:history.go(-1)', 3, $errors);
                xoops_cp_footer();
                exit();
            }
            $obj->setVar($type, $uploader_img->getSavedFileName());
        } elseif (isset($image)) {
            $obj->setVar($type, $image);
        }
    }

    /**
     * Deleteimg function
     *
     * For Deleteing uploaded images
     * Edit topic ,Edit content
     * @param mixed $forMods
     * @param mixed $type
     * @param mixed $obj
     */
    public static function deleteimg($forMods, $type, $obj)
    {
        if ($obj->getVar($type)) {
            $currentPicture = XOOPS_ROOT_PATH . xoops_getModuleOption('img_dir', $forMods->getVar('dirname')) . $obj->getVar($type);
            if (is_file($currentPicture) && is_file($currentPicture)) {
                if (!unlink($currentPicture)) {
                    trigger_error('Error, impossible to delete the picture attached to this article');
                }
            }
        }
        $obj->setVar($type, '');
    }

    /**
     * Uploadfile function
     *
     * For manage all upload parts for files
     * @param mixed $forMods
     * @param mixed $type
     * @param mixed $obj
     * @param mixed $file
     */
    public static function uploadfile($forMods, $type, $obj, $file)
    {
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploader = new \XoopsMediaUploader(XOOPS_ROOT_PATH . xoops_getModuleOption('file_dir', $forMods->getVar('dirname')), explode('|', xoops_getModuleOption('file_mime', $forMods->getVar('dirname'))), xoops_getModuleOption('file_size', $forMods->getVar('dirname')));
        if ($uploader->fetchMedia($type)) {
            $uploader->setPrefix($type . '_');
            $uploader->fetchMedia($type);
            if ($uploader->upload()) {
                $obj->setVar($type, $uploader->getSavedFileName());
                $obj->setVar('file_type', preg_replace('/^.*\./', '', $uploader->getSavedFileName()));
            } else {
                echo _AM_UPLOAD_ERROR . ' ' . $uploader->getErrors();
            }
        } else {
            $errors = $uploader->getErrors();
            fmcontent_Redirect('javascript:history.go(-1)', 3, $errors);
            xoops_cp_footer();
            exit();
        }
    }

    /**
     * @copyright   XOOPS Project (https://xoops.org)
     * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Gregory Mage (Aka Mage)
     * @package     TDMDownload
     * @param mixed $forMods
     * @param mixed $lasturl
     * @param mixed $breadcrumbtitle
     * @param mixed $topic_id
     * @param mixed $prefix
     * @param mixed $title
     * @return string
     * @return string
     */
    public static function breadcrumb(
        $forMods,
        $lasturl,
        $breadcrumbtitle,
        $topic_id,
        $prefix = ' &raquo; ',
        $title = 'topic_title'
    ) {
        $breadcrumb = '';
        //        include_once XOOPS_TRUST_PATH . '/modules/fmcontent/class/topic.php';
        require_once $GLOBALS ['xoops']->path('/class/tree.php');
        $topic_Handler = \XoopsModules\Fmcontent\Helper::getInstance()->getHandler('Topic');

        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));
        $topics_arr = $topic_Handler->getAll($criteria);
        $mytree     = new \XoopsObjectTree($topics_arr, 'topic_id', 'topic_pid');

        if (xoops_getModuleOption('bc_tohome', $forMods->getVar('dirname'))) {
            $breadcrumb = '<a title="' . _FMCONTENT_XHOME . '" href="' . XOOPS_URL . '">' . _FMCONTENT_XHOME . '</a>' . $prefix;
        }
        $breadcrumb .= self::PathTreeUrl($mytree, $topic_id, $topics_arr, $title, $prefix, true, 'ASC', $lasturl, xoops_getModuleOption('bc_modname', $forMods->getVar('dirname')), $forMods);
        if ($lasturl) {
            $breadcrumb .= $prefix . $breadcrumbtitle;
        }

        return $breadcrumb;
    }

    /**
     * @copyright   XOOPS Project (https://xoops.org)
     * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Gregory Mage (Aka Mage)
     * @package     TDMDownload
     * @param mixed $mytree
     * @param mixed $key
     * @param mixed $topic_array
     * @param mixed $title
     * @param mixed $prefix
     * @param mixed $link
     * @param mixed $order
     * @param mixed $lasturl
     * @param mixed $modname
     * @param mixed $forMods
     * @return string
     * @return string
     */
    public static function PathTreeUrl(
        $mytree,
        $key,
        $topic_array,
        $title,
        $prefix,
        $link,
        $order,
        $lasturl,
        $modname,
        $forMods
    ) {
        global $xoopsModule;
        $topic_parent = $mytree->getAllParent($key);
        if ('ASC' == $order) {
            $topic_parent = array_reverse($topic_parent);
            if (true === $link && $modname) {
                if ($key) {
                    $Path = '<a title="' . $xoopsModule->name() . '" href="' . XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/index.php">' . $xoopsModule->name() . '</a>' . $prefix;
                } else {
                    $Path = '<a title="' . $xoopsModule->name() . '" href="' . XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/index.php">' . $xoopsModule->name() . '</a>';
                }
            } elseif ($modname) {
                $Path = $xoopsModule->name() . $prefix;
            } else {
                $Path = '';
            }
        } else {
            if (array_key_exists($key, $topic_array)) {
                $first_category = $topic_array [$key]->getVar($title);
            } else {
                $first_category = '';
            }
            $Path = $first_category . $prefix;
        }
        foreach (array_keys($topic_parent) as $j) {
            if (true === $link) {
                $topic_info = [
                    'topic_id'    => $topic_parent [$j]->getVar('topic_id'),
                    'topic_title' => $topic_parent [$j]->getVar('topic_title'),
                    'topic_alias' => $topic_parent [$j]->getVar('topic_alias'),
                ];
                $Path       .= '<a title="' . $topic_parent [$j]->getVar($title) . '" href="' . fmcontent_TopicUrl($forMods->getVar('dirname'), $topic_info) . '">' . $topic_parent [$j]->getVar($title) . '</a>' . $prefix;
            } else {
                $Path .= $topic_parent [$j]->getVar($title) . $prefix;
            }
        }
        if ('ASC' == $order) {
            if (array_key_exists($key, $topic_array)) {
                if (true === $lasturl) {
                    $first_category = '<a title="' . $topic_array [$key]->getVar($title) . '" href="' . fmcontent_TopicUrl(
                            $forMods->getVar('dirname'),
                            [
                                'topic_id'    => $topic_array [$key]->getVar('topic_id'),
                                'topic_alias' => $topic_array [$key]->getVar('topic_alias'),
                            ]
                        ) . '">' . $topic_array [$key]->getVar($title) . '</a>';
                } else {
                    $first_category = $topic_array [$key]->getVar($title);
                }
            } else {
                $first_category = '';
            }
            $Path .= $first_category;
        } elseif (true === $link) {
            $Path .= '<a title="' . $xoopsModule->name() . '" href="' . XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/index.php">' . $xoopsModule->name() . '</a>';
        } else {
            $Path .= $xoopsModule->name();
        }

        return $Path;
    }

    /**
     * Homepage function
     *
     * For management module index page
     *
     * @param mixed $forMods
     * @param mixed $content_infos
     * @param mixed $type
     * @return array
     * @return array
     */
    public static function homepage($forMods, $content_infos, $type)
    {
        $pageHandler = Helper::getInstance()->getHandler('Page');
        $topicHandler   = Helper::getInstance()->getHandler('Topic');
        if (!$type) {
            $type = 'type1';
        }
        $contents = [];

        switch ($type) {
            // list all contents from all topics whit out topic list
            case 'type1':
                $contents ['content'] = $pageHandler->getContentList($forMods, $content_infos);
                $contents ['numrows'] = $pageHandler->getContentCount($forMods, $content_infos);
                if ($contents ['numrows'] > $content_infos ['content_limit']) {
                    if ($content_infos ['content_topic']) {
                        $content_pagenav = new \XoopsPageNav($contents ['numrows'], $content_infos ['content_limit'], $content_infos ['content_start'], 'start', 'limit=' . $content_infos ['content_limit'] . '&topic=' . $content_infos ['content_topic']);
                    } else {
                        $content_pagenav = new \XoopsPageNav($contents ['numrows'], $content_infos ['content_limit'], $content_infos ['content_start'], 'start', 'limit=' . $content_infos ['content_limit']);
                    }
                    $contents ['pagenav'] = $content_pagenav->renderNav(4);
                } else {
                    $contents ['pagenav'] = '';
                }
                break;
            // List all topics
            case 'type2':
                $topic_order          = xoops_getModuleOption('admin_showorder_topic', $forMods->getVar('dirname'));
                $topic_sort           = xoops_getModuleOption('admin_showsort_topic', $forMods->getVar('dirname'));
                $topic_parent         = $content_infos ['content_topic'];
                $contents ['content'] = $topicHandler->getTopics($forMods, null, 0, $topic_order, $topic_sort, null, 1, $topic_parent);
                $contents ['pagenav'] = null;
                break;
            // List all static pages
            case 'type3':
                if (!$content_infos ['content_topic']) {
                    $content_infos ['content_topic'] = 0;
                }
                $content_infos ['content_subtopic'] = null;
                $content_infos ['content_static']   = 0;
                $content_infos ['admin_side']       = 1;

                $contents ['content'] = $pageHandler->getContentList($forMods, $content_infos);
                $contents ['numrows'] = $pageHandler->getContentCount($forMods, $content_infos);
                if ($contents ['numrows'] > $content_infos ['content_limit']) {
                    if ($content_topic) {
                        $content_pagenav = new \XoopsPageNav($contents ['numrows'], $content_infos ['content_limit'], $content_infos ['content_start'], 'start', 'limit=' . $content_infos ['content_limit'] . '&topic=' . $content_infos ['content_topic']);
                    } else {
                        $content_pagenav = new \XoopsPageNav($contents ['numrows'], $content_infos ['content_limit'], $content_infos ['content_start'], 'start', 'limit=' . $content_infos ['content_limit']);
                    }
                    $contents ['pagenav'] = $content_pagenav->renderNav(4);
                } else {
                    $contents ['pagenav'] = '';
                }
                break;
            // Show selected static content
            case 'type4':
                if ($content_infos['id'] && $content_infos['title'] && $content_infos['alias']) {
                    $id    = $content_infos['id'];
                    $title = $content_infos['title'];
                    $alias = $content_infos['alias'];
                } else {
                    $id    = 0;
                    $title = xoops_getModuleOption('static_name', $forMods->getVar('dirname'));
                    $alias = fmcontent_Filter(xoops_getModuleOption('static_name', $forMods->getVar('dirname')));
                }
                $default_info         = ['id' => $id, 'title' => $title, 'alias' => $alias];
                $contents ['content'] = $pageHandler->contentDefault($forMods, $default_info);
                break;
        }

        return $contents;
    }

    /**
     * Verify that a field exists inside a mysql table
     * @copyright   XOOPS Project (https://xoops.org)
     * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     * @package     News
     * @param mixed $fieldname
     * @param mixed $table
     * @return bool
     * @return bool
     */
    public static function FieldExists($fieldname, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * Add a field to a mysql table
     * @copyright   XOOPS Project (https://xoops.org)
     * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     * @package     News
     * @param mixed $field
     * @param mixed $table
     * @return bool|\mysqli_result
     */
    public static function AddField($field, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF('ALTER TABLE ' . $table . ' ADD ' . $field);

        return $result;
    }

    /**
     * Verify that a mysql table exists
     * @copyright   XOOPS Project (https://xoops.org)
     * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     * @package     Oledrion
     * @param mixed $tablename
     * @return bool
     * @return bool
     */
    public static function TableExists($tablename)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * Add a table
     * @copyright   XOOPS Project (https://xoops.org)
     * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     * @package     Oledrion
     * @param mixed $query
     * @return bool|\mysqli_result
     */
    public static function AddTable($query)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF($query);

        return $result;
    }
}
