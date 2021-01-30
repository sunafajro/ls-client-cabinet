<?php

namespace app\models;

use app\models\queries\GroupFileQuery;
use yii\db\ActiveQuery;

/**
 * Class GroupFile
 * @package app\models
 *
 * @method static GroupFileQuery|ActiveQuery find()
 */
class GroupFile extends File
{
    const DEFAULT_ENTITY_TYPE = self::TYPE_GROUP_FILES;
    const DEFAULT_FIND_CLASS = GroupFileQuery::class;
}