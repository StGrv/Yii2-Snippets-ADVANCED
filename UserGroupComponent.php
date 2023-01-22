<?php

namespace common\components;

use common\models\User;
use common\models\UserGroup;
use yii;
use yii\base\Component;

class UserGroupComponent extends Component
{
    /**
     * Singleton pattern vars
     */
    private static $current;
    private static $default;
    private static $userGroup = [];

    const USER_ID_ZERO = 0;

    public function getDefault() : ?array
    {
        if ( !isset( self::$default ) ) {
            return self::$default = UserGroup::find()->c_default_group( UserGroup::DEFAULT_GROUP_YES )->c_active( UserGroup::ACTIVE_YES )->asArray()->one();
        }

        return self::$default;
    }

    public function getCurrent()
    {
        if ( !isset( self::$current ) ) {
            if ( !$model = self::findGroupById( yii::$app->getUser()->identity->group_id ?? NULL ) ) {
                return $model = self::getDefault();
            }

            return self::$current = $model;
        }

        return self::$current;
    }

    public function findGroupById( ?int $gr_id = NULL )
    {
        if ( $gr_id ) {
            return UserGroup::h_array( $gr_id );
        }

        return NULL;
    }

    public function getUserGroup( array $config = [] )
    {
        $user_id = $config['user_id'] ?? yii::$app->getUser()->identity->id ?? 0;

        if ( !isset( self::$userGroup[ $user_id ] ) ) {

            if ( $user_id == self::USER_ID_ZERO ) {
                return self::$userGroup[ $user_id ] = yii::$app->userGroup->getDefault()['id'];
            }

            if ( User::h_array( $user_id )['group_id'] == UserGroup::USER_GROUP_B2B_ID && ( User::h_array( $user_id )['confirmed_at'] ?? NULL ) == NULL ) {
                return self::$userGroup[ $user_id ] = yii::$app->userGroup->getDefault()['id'];
            }

            return self::$userGroup[ $user_id ] = User::h_array( $user_id )['group_id'];
        }


        return self::$userGroup[ $user_id ];
    }

}