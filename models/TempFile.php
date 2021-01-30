<?php

namespace app\models;

use app\models\queries\TempFileQuery;
use yii\db\ActiveQuery;

/**
 * Class TempFile
 * @package app\models
 *
 * @method static TempFileQuery|ActiveQuery find()
 */
class TempFile extends File
{
    const DEFAULT_ENTITY_TYPE = self::TYPE_TEMP;
    const DEFAULT_FIND_CLASS = TempFileQuery::class;
}