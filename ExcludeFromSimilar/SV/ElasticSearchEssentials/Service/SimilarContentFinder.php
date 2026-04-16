<?php

namespace QQ\ExcludeFromSimilar\SV\ElasticSearchEssentials\Service;

use XF\Entity\Thread as ThreadEntity;
use XF\Mvc\Entity\AbstractCollection;

/* We work by replacing the getSimilarContents() function (which is
the entry point for the widget) with a wrapper that calls the parent,
then filters manually on the criterion column. Arguably it would be
preferable to push the column into elasticsearch and run the filter
there, but that would require deeper integration with the addon; this
allows a patch of minimal scope. */
class SimilarContentFinder extends XFCP_SimilarContentFinder
{
    public function getSimilarContents(): ?AbstractCollection
    {
        /* temporarily boost entry limit; the extras after filtering
           for excluded threads will be discarded */
        $originalLimit = $this->getLimit();
        /* sets the value to a flat +5; if more than 5 results for a
           thread are excluded, then the widget will show fewer
           results than configured, but we consider this to be 1. an
           unlikely scenario, and 2. an acceptable result */
        $this->setLimit($originalLimit + 5);

        $result = parent::getSimilarContents();

        if ($result !== null)
        {
            $result = $result->filter(function ($entity) {
                if ($entity instanceof ThreadEntity)
                {
                    return !$entity->qq_exclude_from_similar;
                }
                return true;
            });
            $result = $result->slice(0, $originalLimit);
        }

        $this->setLimit($originalLimit);

        return $result;
    }
}
