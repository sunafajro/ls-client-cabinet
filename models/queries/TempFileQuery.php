<?php

namespace app\models\queries;

use app\modules\school\models\TempFile;
use yii\db\ActiveQuery;

/**
 * Class TempFileQuery
 * @package app\models\queries
 *
 * @method TempFile one($db = null)
 * @method TempFile[] all($db = null)
 * @method TempFileQuery|ActiveQuery byId(int $id)
 * @method TempFileQuery|ActiveQuery byEntityId(int $id)
 * @method MessageFileQuery|ActiveQuery byEntityIds(array $id)
 * @method TempFileQuery|ActiveQuery byUserId(int $id)
 */
class TempFileQuery extends FileQuery
{

}