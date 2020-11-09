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
 * Class FileHandler
 * @package XoopsModules\Fmcontent
 */
class FileHandler extends \XoopsPersistableObjectHandler
{
    /**
     * FileHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'fmcontent_file', File::class, 'file_id', 'file_title');
    }

    /**
     * @param $forMods
     * @param $file
     * @param $content
     * @return array
     */
    public function getAdminFiles($forMods, $file, $content)
    {
        $ret      = [];
        $tab      = [];
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('file_modid', $forMods->getVar('mid')));
        if (isset($file['content'])) {
            $criteria->add(new \Criteria('file_content', $file['content']));
            $criteria->add(new \Criteria('file_status', 1));
        }
        $criteria->setSort($file['sort']);
        $criteria->setOrder($file['order']);
        if (isset($file['limit'])) {
            $criteria->setLimit($file['limit']);
        }
        $criteria->setStart($file['start']);
        $files = $this->getObjects($criteria, false);
        if ($files) {
            foreach ($files as $root) {
                $tab = $root->toArray();
                if (is_array($content)) {
                    foreach (array_keys($content) as $i) {
                        $list [$i] ['file_title'] = $content [$i]->getVar('content_title');
                        $list [$i] ['file_id']    = $content [$i]->getVar('content_id');
                    }
                    if ($root->getVar('file_content')) {
                        $tab ['content']   = $list [$root->getVar('file_content')] ['file_title'];
                        $tab ['contentid'] = $list [$root->getVar('file_content')] ['file_id'];
                    }
                } else {
                    $tab ['content']   = $content->getVar('content_title');
                    $tab ['contentid'] = $content->getVar('content_id');
                }
                $tab ['fileurl'] = XOOPS_URL . xoops_getModuleOption('file_dir', $forMods->getVar('dirname')) . $root->getVar('file_name');
                $ret []          = $tab;
            }
        }

        return $ret;
    }

    /**
     * @param $forMods
     * @param $file
     * @return array
     */
    public function getFiles($forMods, $file)
    {
        $ret      = [];
        $tab      = [];
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('file_modid', $forMods->getVar('mid')));
        $criteria->add(new \Criteria('file_content', $file['content']));
        $criteria->add(new \Criteria('file_status', 1));
        $criteria->setSort($file['sort']);
        $criteria->setOrder($file['order']);
        $criteria->setStart($file['start']);
        $files = $this->getObjects($criteria, false);
        if ($files) {
            foreach ($files as $root) {
                $tab             = $root->toArray();
                $tab ['fileurl'] = XOOPS_URL . xoops_getModuleOption('file_dir', $forMods->getVar('dirname')) . $root->getVar('file_name');
                $ret []          = $tab;
            }
        }

        return $ret;
    }

    /**
     * @param $forMods
     * @return int
     */
    public function getFileCount($forMods)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('file_modid', $forMods->getVar('mid')));

        return $this->getCount($criteria);
    }
}
