<?php

namespace app\models\queries;

use app\models\MessageFile;
use yii\db\ActiveQuery;

/**
 * Class MessageFileQuery
 * @package app\models\queries
 *
 * @method MessageFile one($db = null)
 * @method MessageFile[] all($db = null)
 * @method MessageFileQuery|ActiveQuery byId(int $id)
 * @method MessageFileQuery|ActiveQuery byEntityId(int $id)
 * @method MessageFileQuery|ActiveQuery byEntityIds(array $id)
 * @method MessageFileQuery|ActiveQuery byUserId(int $id)
 */
class MessageFileQuery extends FileQuery
{

}