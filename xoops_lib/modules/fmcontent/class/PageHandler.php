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
 * Content handler class
 *
 **/
class PageHandler extends \XoopsPersistableObjectHandler
{
    /**
     * PageHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'fmcontent_content', Page::class, 'content_id', 'content_alias');
    }

    /**
     * Check if content alias already exist
     *
     * @param mixed $forMods
     * @param mixed $infos
     * @return bool
     */
    public function existAlias($forMods, $infos)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));

        $temp =   isset($infos['content_alias']) ?   $infos['content_alias']  : '';
        $criteria->add(new \Criteria('content_alias', $temp));

        if ($infos['content_id']) {
            $criteria->add(new \Criteria('content_id', $infos['content_id'], '!='));
        }
        if (0 == $this->getCount($criteria)) {
            return false;
        }

        return true;
    }

    /**
     * @param $alias
     * @return int|mixed
     */
    public function getId($alias)
    {
        $criteria = new \CriteriaCompo();
        $criteria = new \Criteria('content_alias', $alias);
        $criteria->setLimit(1);
        $obj_array = $this->getObjects($criteria, false, false);
        if (1 != count($obj_array)) {
            return 0;
        }

        return $obj_array [0] [$this->keyName];
    }

    /**
     * @param null $criteria
     * @return int|mixed
     */
    public function getDefault($criteria = null)
    {
        $obj_array = $this->getObjects($criteria, false, false);
        if (1 != count($obj_array)) {
            return 0;
        }

        return $obj_array [0] [$this->keyName];
    }

    /**
     * @param $forMods
     * @param $default_info
     * @return array
     */
    public function contentDefault($forMods, $default_info)
    {
        $contentdefault = [];
        $criteria       = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_default', 1));
        $criteria->add(new \Criteria('content_topic', $default_info ['id']));
        $default                           = $this->getDefault($criteria);
        $obj                               = $this->get($default);
        $contentdefault                    = $obj->toArray();
        $contentdefault ['content_create'] = formatTimestamp($contentdefault ['content_create'], _MEDIUMDATESTRING);
        $contentdefault ['imgurl']         = XOOPS_URL . xoops_getModuleOption('img_dir', $forMods->getVar('dirname')) . $contentdefault ['content_img'];
        $contentdefault ['topic']          = $default_info ['title'];
        $contentdefault ['topic_alias']    = $default_info ['alias'];
        $contentdefault ['url']            = fmcontent_Url($forMods->getVar('dirname'), $contentdefault);
        if (isset($contentdefault ['content_id'])) {
            return $contentdefault;
        }
    }

    /**
     * @param $forMods
     * @param $content_infos
     * @return array
     */
    public function getContentList($forMods, $content_infos)
    {
        $ret      = [];
        $tab      = [];
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_status', $content_infos ['content_status']));
        if ($content_infos ['content_static']) {
            $criteria->add(new \Criteria('content_topic', '0', '>'));
        }
        if (!$content_infos ['admin_side']) {
            $access_topic  = Permission::getItemIds('fmcontent_access', $forMods);
            $topicHandler = Helper::getInstance()->getHandler('Topic');
            $topic_show    = $topicHandler->allVisible($forMods, $content_infos ['topics'], $content_infos ['content_topic']);
            $topiclist     = array_intersect($access_topic, $topic_show);
            $criteria->add(new \Criteria('content_topic', '(' . implode(',', $topiclist) . ')', 'IN'));
            $criteria->add(new \Criteria('content_type', 'content'));
        }
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_topic', $content_infos ['content_topic']));
        if (isset($content_infos ['content_subtopic'])) {
            foreach ($content_infos ['content_subtopic'] as $subtopic) {
                $criteria->add(new \Criteria('content_topic', $subtopic), 'OR');
            }
        }
        $criteria->add(new \Criteria('content_uid', $content_infos ['content_user']));
        $criteria->setSort($content_infos ['content_sort']);
        $criteria->setOrder($content_infos ['content_order']);
        $criteria->setLimit($content_infos ['content_limit']);
        $criteria->setStart($content_infos ['content_start']);

        $obj = $this->getObjects($criteria, false);
        if ($obj) {
            foreach ($obj as $root) {
                $tab           = $root->toArray();
                $tab ['owner'] = \XoopsUser::getUnameFromId($root->getVar('content_uid'));
                if (is_array($content_infos ['topics'])) {
                    foreach (array_keys($content_infos ['topics']) as $i) {
                        $list [$i] ['topic_title'] = $content_infos ['topics'] [$i]->getVar('topic_title');
                        $list [$i] ['topic_id']    = $content_infos ['topics'] [$i]->getVar('topic_id');
                        $list [$i] ['topic_alias'] = $content_infos ['topics'] [$i]->getVar('topic_alias');
                    }
                }
                if ($root->getVar('content_topic')) {
                    $tab ['topic']       = $list [$root->getVar('content_topic')] ['topic_title'];
                    $tab ['topic_alias'] = $list [$root->getVar('content_topic')] ['topic_alias'];
                    $tab ['topicurl']    = fmcontent_TopicUrl(
                        $forMods->getVar('dirname'),
                        [
                            'topic_id'    => $list [$root->getVar('content_topic')] ['topic_id'],
                            'topic_alias' => $list [$root->getVar('content_topic')] ['topic_alias'],
                        ]
                    );
                }

                $tab ['url']            = fmcontent_Url($forMods->getVar('dirname'), $tab);
                $tab ['content_create'] = formatTimestamp($root->getVar('content_create'), _MEDIUMDATESTRING);
                $tab ['content_update'] = formatTimestamp($root->getVar('content_update'), _MEDIUMDATESTRING);
                $tab ['imgurl']         = XOOPS_URL . xoops_getModuleOption('img_dir', $forMods->getVar('dirname')) . $root->getVar('content_img');
                $ret []                 = $tab;
            }
        }

        return $ret;
    }

    /**
     * @param $forMods
     * @param $content_infos
     * @return array
     */
    public function getMenuList($forMods, $content_infos)
    {
        $ret      = [];
        $tab      = [];
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_display', '1'));
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        if ('-1' != $content_infos ['menu_id']) {
            $criteria->add(new \Criteria('content_topic', $content_infos ['menu_id']));
        }
        $criteria->setSort($content_infos ['menu_sort']);
        $criteria->setOrder($content_infos ['menu_order']);

        $obj = $this->getObjects($criteria, false);
        if ($obj) {
            foreach ($obj as $root) {
                $tab = $root->toArray();

                foreach (array_keys($content_infos ['topics']) as $i) {
                    $list [$i] ['topic_title'] = $content_infos ['topics'] [$i]->getVar('topic_title');
                    $list [$i] ['topic_id']    = $content_infos ['topics'] [$i]->getVar('topic_id');
                    $list [$i] ['topic_alias'] = $content_infos ['topics'] [$i]->getVar('topic_alias');
                }
                if ($root->getVar('content_topic')) {
                    $tab ['topic']       = $list [$root->getVar('content_topic')] ['topic_title'];
                    $tab ['topic_alias'] = $list [$root->getVar('content_topic')] ['topic_alias'];
                    $tab ['topicurl']    = fmcontent_TopicUrl(
                        $forMods->getVar('dirname'),
                        [
                            'topic_id'    => $list [$root->getVar('content_topic')] ['topic_id'],
                            'topic_alias' => $list [$root->getVar('content_topic')] ['topic_alias'],
                        ]
                    );
                }

                $tab ['url'] = fmcontent_Url($forMods->getVar('dirname'), $tab);
                $ret []      = $tab;
            }
        }

        return $ret;
    }

    /**
     * @param $forMods
     * @param $content_infos
     * @param $options
     * @return array
     */
    public function getContentBlockList($forMods, $content_infos, $options)
    {
        $ret      = [];
        $tab      = [];
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_type', 'content'));
        $criteria->add(new \Criteria('content_status', '1'));
        $access_topic = Permission::getItemIds('fmcontent_access', $forMods);
        $criteria->add(new \Criteria('content_topic', '(' . implode(',', $access_topic) . ')', 'IN'));
        if (!(1 == count($options) && 0 == $options [0])) {
            $criteria->add(new \Criteria('content_topic', '(' . implode(',', $options) . ')', 'IN'));
        }
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->setSort($content_infos ['content_sort']);
        $criteria->setOrder($content_infos ['content_order']);
        $criteria->setLimit($content_infos ['content_limit']);

        $obj = $this->getObjects($criteria, false);
        if ($obj) {
            foreach ($obj as $root) {
                $tab           = $root->toArray();
                $tab ['owner'] = \XoopsUser::getUnameFromId($root->getVar('content_uid'));

                foreach (array_keys($content_infos ['topics']) as $i) {
                    $list [$i] ['topic_title'] = $content_infos ['topics'] [$i]->getVar('topic_title');
                    $list [$i] ['topic_id']    = $content_infos ['topics'] [$i]->getVar('topic_id');
                    $list [$i] ['topic_alias'] = $content_infos ['topics'] [$i]->getVar('topic_alias');
                }
                if ($root->getVar('content_topic')) {
                    $tab ['topic']       = $list [$root->getVar('content_topic')] ['topic_title'];
                    $tab ['topic_alias'] = $list [$root->getVar('content_topic')] ['topic_alias'];
                    $tab ['topicurl']    = fmcontent_TopicUrl(
                        $forMods->getVar('dirname'),
                        [
                            'topic_id'    => $list [$root->getVar('content_topic')] ['topic_id'],
                            'topic_alias' => $list [$root->getVar('content_topic')] ['topic_alias'],
                        ]
                    );
                }

                $tab ['url']   = fmcontent_Url($forMods->getVar('dirname'), $tab);
                $tab ['title'] = mb_strlen($root->getVar('content_title'), 'utf-8') > $content_infos ['lenght_title'] ? mb_substr($root->getVar('content_title'), 0, $content_infos ['lenght_title'], 'utf-8') . '...' : $root->getVar('content_title');
                $tab ['date']  = formatTimestamp($root->getVar('content_create'), _MEDIUMDATESTRING);
                $ret []        = $tab;
            }
        }

        return $ret;
    }

    /**
     * @param $forMods
     * @param $content_infos
     * @return int
     */
    public function getContentCount($forMods, $content_infos)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_topic', $content_infos ['content_topic']));
        if (!$content_infos ['admin_side']) {
            $access_topic  = Permission::getItemIds('fmcontent_access', $forMods);
            $topicHandler = Helper::getInstance()->getHandler('Topic');
            $topic_show    = $topicHandler->allVisible($forMods, $content_infos ['topics'], $content_infos ['content_topic']);
            $topiclist     = array_intersect($access_topic, $topic_show);
            $criteria->add(new \Criteria('content_topic', '(' . implode(',', $topiclist) . ')', 'IN'));
            $criteria->add(new \Criteria('content_type', 'content'));
        }
        if (isset($content_infos ['content_subtopic'])) {
            foreach ($content_infos ['content_subtopic'] as $subtopic) {
                $criteria->add(new \Criteria('content_topic', $subtopic), 'OR');
            }
        }
        if ($content_infos ['content_static']) {
            $criteria->add(new \Criteria('content_topic', '0', '>'));
        }

        return $this->getCount($criteria);
    }

    /**
     * @param $forMods
     * @param $topic_id
     * @return int
     */
    public function getMenuItemCount($forMods, $topic_id)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_display', '1'));
        $criteria->add(new \Criteria('content_topic', $topic_id));
        $pageHandler = Helper::getInstance()->getHandler('Page');
        $getcount        = $pageHandler->getCount($criteria);

        return $getcount;
    }

    /**
     * @param        $forMods
     * @param string $topic_id
     * @return int
     */
    public function getContentItemCount($forMods, $topic_id = '')
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_topic', $topic_id));
        $pageHandler = Helper::getInstance()->getHandler('Page');
        $getcount        = $pageHandler->getCount($criteria);

        return $getcount;
    }

    /**
     * @param $forMods
     * @param $content_infos
     * @return array
     */
    public function getLastContent($forMods, $content_infos)
    {
        $ret      = [];
        $tab      = [];
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $obj = $this->getObjects($criteria, false);
        if ($obj) {
            foreach ($obj as $root) {
                $tab                 = $root->toArray();
                $tab ['topic']       = TopicHandler::getTopicFromId($root->getVar('content_topic'));
                $tab ['topic_alias'] = $tab ['topic'];
                $tab ['url']         = fmcontent_Url($forMods->getVar('dirname'), $tab);
                $ret []              = $tab;
            }
        }

        return $ret;
    }

    /**
     * @param $forMods
     * @param $topic_id
     * @return mixed
     */
    public function setNext($forMods, $topic_id)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_topic', $topic_id));
        $criteria->setSort('content_id');
        $criteria->setOrder('ASC');
        $criteria->setLimit(1);
        $previous = $this->getObjects($criteria);
        foreach ($previous as $item) {
            return $item->getVar('content_id');
        }
    }

    /**
     * @param $forMods
     * @param $topic_id
     * @return mixed
     */
    public function setPrevious($forMods, $topic_id)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_topic', $topic_id));
        $criteria->setSort('content_id');
        $criteria->setOrder('DESC');
        $criteria->setLimit(1);
        $previous = $this->getObjects($criteria);
        foreach ($previous as $item) {
            return $item->getVar('content_id');
        }
    }

    /**
     * @param $forMods
     * @param $topic_id
     * @param $next_id
     * @return mixed
     */
    public function resetNext($forMods, $topic_id, $next_id)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_topic', $topic_id));
        $criteria->setSort('content_id');
        $criteria->setOrder('DESC');
        $criteria->setLimit(1);
        $criteria->setStart(1);
        $next = $this->getObjects($criteria);
        foreach ($next as $item) {
            $item->setVar('content_next', $next_id);

            return $this->insert($item);
        }
    }

    /**
     * @param $forMods
     * @param $topic_id
     * @param $prev_id
     * @return mixed
     */
    public function resetPrevious($forMods, $topic_id, $prev_id)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_topic', $topic_id));
        $criteria->setSort('content_id');
        $criteria->setOrder('ASC');
        $criteria->setLimit(1);
        $criteria->setStart(0);
        $prev = $this->getObjects($criteria);
        foreach ($prev as $item) {
            $item->setVar('content_prev', $prev_id);

            return $this->insert($item);
        }
    }

    /**
     * @param $forMods
     * @return int
     */
    public function setorder($forMods)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->setSort('content_order');
        $criteria->setOrder('DESC');
        $criteria->setLimit(1);
        $last  = $this->getObjects($criteria);
        $order = 1;
        foreach ($last as $item) {
            $order = $item->getVar('content_order') + 1;
        }

        return $order;
    }

    /**
     * @copyright   XOOPS Project (https://xoops.org)
     * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     * @package     Oledrion
     * @param mixed $content_id
     * @return bool|\mysqli_result
     */
    public function updateHits($content_id)
    {
        $sql = 'UPDATE ' . $this->table . ' SET content_hits = content_hits + 1 WHERE content_id= ' . (int)$content_id;

        return $this->db->queryF($sql);
    }

    /**
     * @copyright   XOOPS Project (https://xoops.org)
     * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Zoullou (http://www.zoullou.net)
     * @package     ExtGallery
     * @param mixed $queryArray
     * @param mixed $condition
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $userId
     * @return array
     * @return array
     */
    public function getSearchedContent($queryArray, $condition, $limit, $start, $userId)
    {
        $ret  = [];
        $data = [];
//        include_once 'topic.php';
        $criteria = new \CriteriaCompo();
        if ($userId > 0) {
            $criteria->add(new \Criteria('content_uid', $userId));
        }
        $criteria->add(new \Criteria('content_status', 1));
        if ($queryArray && is_array($queryArray)) {
            $subCriteria = new \CriteriaCompo();
            foreach ($queryArray as $keyWord) {
                $keyWordCriteria = new \CriteriaCompo();
                $keyWordCriteria->add(new \Criteria('content_title', '%' . $keyWord . '%', 'LIKE'));
                $keyWordCriteria->add(new \Criteria('content_text', '%' . $keyWord . '%', 'LIKE'), 'OR');
                $keyWordCriteria->add(new \Criteria('content_short', '%' . $keyWord . '%', 'LIKE'), 'OR');
                $keyWordCriteria->add(new \Criteria('content_words', '%' . $keyWord . '%', 'LIKE'), 'OR');
                $keyWordCriteria->add(new \Criteria('content_desc', '%' . $keyWord . '%', 'LIKE'), 'OR');
                $subCriteria->add($keyWordCriteria, $condition);
                unset($keyWordCriteria);
            }
            $criteria->add($subCriteria);
        }
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('content_create');

        $contents = $this->getObjects($criteria);

        $ret = [];
        foreach ($contents as $content) {
            $data                 = $content->toArray();
            $data ['image']       = 'assets/images/forum.gif';
            $data ['topic']       = TopicHandler::getTopicFromId($content->getVar('content_topic'));
            $data ['topic_alias'] = $data ['topic'];
            $data ['link']        = fmcontent_Url('fmcontent', $data);
            $data ['title']       = $content->getVar('content_title');
            $data ['time']        = $content->getVar('content_create');
            $data ['uid']         = $content->getVar('content_uid');
            $ret []               = $data;
        }

        return $ret;
    }

    /**
     * Generate function for update user post
     *
     * @ Update user post count after send approve content
     * @ Update user post count after change status content
     * @ Update user post count after delete content
     * @param mixed $content_uid
     * @param mixed $content_status
     * @param mixed $content_action
     */
    public function updateposts($content_uid, $content_status, $content_action)
    {
        switch ($content_action) {
            case 'add':
                if ($content_uid && $content_status) {
                    $user           = new \XoopsUser($content_uid);
                    $memberHandler = xoops_getHandler('member');
                    $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') + 1);
                }
                break;
            case 'delete':
                if ($content_uid && $content_status) {
                    $user           = new \XoopsUser($content_uid);
                    $memberHandler = xoops_getHandler('member');
                    $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') - 1);
                }
                break;
            case 'status':
                if ($content_uid) {
                    $user           = new \XoopsUser($content_uid);
                    $memberHandler = xoops_getHandler('member');
                    if ($content_status) {
                        $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') - 1);
                    } else {
                        $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') + 1);
                    }
                }
                break;
        }
    }

    /**
     * @param      $action
     * @param      $id
     * @param null $previous
     * @return bool|\mysqli_result
     */
    public function contentfile($action, $id, $previous = null)
    {
        switch ($action) {
            case 'add':
                $sql = 'UPDATE ' . $this->table . ' SET content_file = content_file + 1 WHERE content_id= ' . (int)$id;
                break;
            case 'delete':
                $sql = 'UPDATE ' . $this->table . ' SET content_file = content_file - 1 WHERE content_id= ' . (int)$id;
                break;
        }

        return $this->db->queryF($sql);
    }

    /**
     * @param $forMods
     * @return array
     */
    public function getfile($forMods)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('content_file', '0', '>'));

        return $this->getAll($criteria);
    }
}
