<?php

namespace QQ\ExcludeFromSimilar\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['qq_exclude_from_similar'] = [
            'type' => self::BOOL,
            'default' => false,
        ];

        return $structure;
    }
}
