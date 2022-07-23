<?php

namespace bonavadeur\core;

use bonavadeur\core\db\DbModel;

abstract class UserModel extends DbModel {
    abstract public function getDisplayName() : string;
}