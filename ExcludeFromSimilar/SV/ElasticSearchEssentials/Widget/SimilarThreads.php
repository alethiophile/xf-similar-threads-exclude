<?php

namespace QQ\ExcludeFromSimilar\SV\ElasticSearchEssentials\Widget;

use SV\ElasticSearchEssentials\Repository\MobileDetect as MobileDetectRepo;
use SV\ElasticSearchEssentials\Service\SimilarContentFinder;
use SV\ElasticSearchEssentials\XF\Entity\Thread as ExtendedThreadEntity;
use XF\Entity\Thread as ThreadEntity;
use XF\Mvc\Entity\AbstractCollection;

class SimilarThreads extends XFCP_SimilarThreads
{
    public function render()
    {
        $thread = $this->contextParams['thread'] ?? null;
        if (!($thread instanceof ExtendedThreadEntity))
        {
            return '';
        }
        $forum = $thread->Forum;
        if (!$forum)
        {
            return '';
        }

        $showToBots = $this->options['showToBots'] ?? false;
        if (!$showToBots && $this->isRobot())
        {
            return '';
        }

        $showToMobile = $this->options['mobile'] ?? false;
        $repo = MobileDetectRepo::get();
        if (!$repo->canViewOnMobile($thread->canViewSimilarContents(), $showToMobile))
        {
            return '';
        }

        $similarContentFinder = SimilarContentFinder::get($forum, $thread);

        $similarContentFinder->setCacheTime($this->options['cacheTime']);
        $similarContentFinder->setSort($this->options['sort']);
        $similarContentFinder->setLimit($this->options['limit']);
        $similarContentFinder->setDateLimit($this->options['date_limit']);
        $similarContentFinder->setLastUpdatedDateLimit($this->options['last_update_date_limit']);
        $similarContentFinder->setCutOffFrequency($this->options['cutoff_frequency']);

        $similarContents = $similarContentFinder->getSimilarContents() ?? [];

        // Filter out threads marked as excluded from similar thread listings
        $excludeFilter = function ($entity) {
            if ($entity instanceof ThreadEntity)
            {
                return !$entity->qq_exclude_from_similar;
            }
            return true;
        };

        if ($similarContents instanceof AbstractCollection)
        {
            $similarContents = $similarContents->filter($excludeFilter);
        }
        else if (is_array($similarContents))
        {
            $similarContents = array_filter($similarContents, $excludeFilter);
        }

        return $this->renderer('svElasticSearchEssentials_widget_similar_threads', [
            'class'          => $this->options['class'],
            'similarThreads' => $similarContents,
            'thread'         => $thread,
            'forum'          => $forum,
        ]);
    }
}
