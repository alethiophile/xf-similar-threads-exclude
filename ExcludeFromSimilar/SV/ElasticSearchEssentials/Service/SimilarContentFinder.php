<?php

namespace QQ\ExcludeFromSimilar\SV\ElasticSearchEssentials\Service;

use XF\Entity\Thread as ThreadEntity;
use XF\Mvc\Entity\AbstractCollection;

class SimilarContentFinder extends XFCP_SimilarContentFinder
{
    public function getSimilarContents(): ?AbstractCollection
    {
        $originalLimit = $this->getLimit();
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
