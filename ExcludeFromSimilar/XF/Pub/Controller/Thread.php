<?php

namespace QQ\ExcludeFromSimilar\XF\Pub\Controller;

use XF\Entity\Thread as ThreadEntity;

class Thread extends XFCP_Thread
{
    protected function setupThreadEdit(ThreadEntity $thread)
    {
        $editor = parent::setupThreadEdit($thread);

        if ($thread->canEditModeratorFields())
        {
            $thread->qq_exclude_from_similar = $this->filter('qq_exclude_from_similar', 'bool');
        }

        return $editor;
    }
}
