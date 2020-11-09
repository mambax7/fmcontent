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
 * Class TopicHandler
 * @package XoopsModules\Fmcontent
 */
class TopicHandler extends \XoopsPersistableObjectHandler
{
    /**
     * TopicHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'fmcontent_topic', Topic::class, 'topic_id', 'topic_alias');
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
        $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('topic_alias', $infos['topic_alias']));
        if ($infos['topic_id']) {
            $criteria->add(new \Criteria('topic_id', $infos['topic_id'], '!='));
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
        $criteria = new \Criteria('topic_alias', $alias);
        $criteria->setLimit(1);
        $obj_array = $this->getObjects($criteria, false, false);
        if (1 != count($obj_array)) {
            return 0;
        }

        return $obj_array [0] [$this->keyName];
    }

    /**
     * @param $forMods
     * @param $topic_limit
     * @param $topic_start
     * @param $topic_order
     * @param $topic_sort
     * @param $topic_menu
     * @param $topic_online
     * @param $topic_parent
     * @return array
     */
    public function getTopics(
        $forMods,
        $topic_limit,
        $topic_start,
        $topic_order,
        $topic_sort,
        $topic_menu,
        $topic_online,
        $topic_parent
    ) {
        $ret = [];
        $tab = [];
        if (!isset($criteria)) {
            $criteria = new \CriteriaCompo();
        }

        if (isset($criteria)) {
            $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));
            $criteria->add(new \Criteria('topic_asmenu', $topic_menu));
            $criteria->add(new \Criteria('topic_online', $topic_online));
            if (isset($topic_parent)) {
                $criteria->add(new \Criteria('topic_pid', $topic_parent));
            }
            $criteria->setSort($topic_sort);
            $criteria->setOrder($topic_order);
            $criteria->setLimit($topic_limit);
            $criteria->setStart($topic_start);
        }

        $topics = $this->getObjects($criteria, false);
        if ($topics) {
            foreach ($topics as $root) {
                $tab              = $root->toArray();
                $tab ['topicurl'] = fmcontent_TopicUrl($forMods->getVar('dirname'), $tab);
                $tab ['imgurl']   = XOOPS_URL . xoops_getModuleOption('img_dir', $forMods->getVar('dirname')) . $root->getVar('topic_img');
                $ret []           = $tab;
            }
        }

        return $ret;
    }

    /**
     * @param $forMods
     * @return int
     */
    public function getTopicCount($forMods)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));

        return $this->getCount($criteria);
    }

    /**
     * @param $topicid
     * @return array|int|mixed|string
     */
    public static function getTopicFromId($topicid)
    {
        $myts        = \MyTextSanitizer::getInstance();
        $topicid     = (int)$topicid;
        $topic_title = '';
        if ($topicid > 0) {
            $topicHandler = \XoopsModules\Fmcontent\Helper::getInstance()->getHandler('Topic');
            $topic         = $topicHandler->get($topicid);
            if (is_object($topic)) {
                $topic_title = $topic->getVar('topic_title');
            }
        }

        return $topic_title;
    }

    /**
     * @return int
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * @param $forMods
     * @param $id
     * @param $topics
     * @return array
     */
    public function getSubTopics($forMods, $id, $topics)
    {
        $ret = [];
        foreach ($topics as $root) {
            if ($root->getVar('topic_pid') == $id) {
                $ret [] = $root->getVar('topic_id');
            }
        }

        return $ret;
    }

    /**
     * @param $forMods
     * @return int
     */
    public function setorder($forMods)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));
        $criteria->setSort('topic_weight');
        $criteria->setOrder('DESC');
        $criteria->setLimit(1);
        $last  = $this->getObjects($criteria);
        $order = 1;
        foreach ($last as $item) {
            $order = $item->getVar('topic_weight') + 1;
        }

        return $order;
    }

    /**
     * @param $forMods
     * @param $topics
     * @param $topic
     * @return array
     */
    public function allVisible($forMods, $topics, $topic)
    {
        $topic_show = [];
        if ($topic) {
            $topic_show[] = $topic;
        }
        foreach (array_keys($topics) as $i) {
            if ($topics [$i]->getVar('topic_show')) {
                $topic_show[] = $topics [$i]->getVar('topic_id');
            }
        }

        return $topic_show;
    }
}
