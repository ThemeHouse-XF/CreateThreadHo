<?php

/**
 *
 * @see XenForo_ControllerPublic_Forum
 */
class ThemeHouse_CreateThreadHo_Extend_XenForo_ControllerPublic_Forum extends XFCP_ThemeHouse_CreateThreadHo_Extend_XenForo_ControllerPublic_Forum
{

    /**
     *
     * @see XenForo_ControllerPublic_Forum::actionIndex()
     */
    public function actionIndex()
    {
        $response = parent::actionIndex();

        /* @var $forumModel XenForo_Model_Forum */
        $forumModel = $this->_getForumModel();

        if ($response instanceof XenForo_ControllerResponse_View) {
            if ($this->_routeMatch->getResponseType() != 'rss') {
                $nodeIds = array();
                $nodePermissions = $response->params['nodeList']['nodePermissions'];
                foreach ($response->params['nodeList']['nodesGrouped'] as $nodes) {
                    foreach ($nodes as $nodeId => $node) {
                        if ($forumModel->canPostThreadInForum($node, $errorPhraseKey, $nodePermissions[$nodeId])) {
                            $response->params['canPostThread'] = true;
                            break 2;
                        }
                    }
                }
            }
        }

        return $response;
    }

    /**
     *
     * @see XenForo_ControllerPublic_Forum::actionCreateThread()
     */
    public function actionCreateThread()
    {
        $forumId = $this->_input->filterSingle('node_id', XenForo_Input::UINT);

        if ($forumId == 0) {
            throw $this->responseException($this->responseReroute(__CLASS__, 'choose-forum'));
        }

        return parent::actionCreateThread();
    }

    public function actionChooseForum()
    {
        /* @var $nodeModel XenForo_Model_Node */
        $nodeModel = $this->getModelFromCache('XenForo_Model_Node');

        $nodes = $nodeModel->getViewableNodeList(null, true);

        /* @var $forumModel XenForo_Model_Forum */
        $forumModel = $this->_getForumModel();

        $forums = $forumModel->getForums();

        foreach ($nodes as $nodeId => $node) {
            if ($node['node_type_id'] != 'Forum') {
                continue;
            }
            if ($forumModel->canPostThreadInForum($forums[$nodeId])) {
                $nodes[$nodeId]['canPostThread'] = true;
            }
        }

        return $this->responseView('ThemeHouse_CreateThreadHo_ViewPublic_Thread_ChooseForum',
            'th_thread_choose_forum_createthreadhome', array(
                'nodes' => $nodes
            ));
    }
}