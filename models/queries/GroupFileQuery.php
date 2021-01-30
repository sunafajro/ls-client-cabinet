<?php

namespace app\models\queries;

use app\models\GroupFile;
use yii\db\ActiveQuery;

/**
 * Class GroupFileQuery
 * @package app\models\queries
 *
 * @method GroupFile one($db = null)
 * @method GroupFile[] all($db = null)
 * @method GroupFileQuery|ActiveQuery byId(int $id)
 * @method GroupFileQuery|ActiveQuery byEntityId(int $id)
 * @method GroupFileQuery|ActiveQuery byEntityIds(array $id)
 * @method GroupFileQuery|ActiveQuery byUserId(int $id)
 */
class GroupFileQuery extends FileQuery
{

}