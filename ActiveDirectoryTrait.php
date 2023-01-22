<?php

namespace common\traits;

use Yii;

trait ActiveDirectoryTrait
{
    static function getFilesDir(): string
    {
        return yii::$app->params['files_directory'];
    }

    static function getActiveDir(): string
    {
        return yii::$app->params['active_directory'];
    }

    static function getFilesActivePath(): string
    {
        return self::getFilesDir() . '/' . self::getActiveDir();
    }

    static function getFilePath(string $path): string
    {
        return Yii::getAlias( "@frontend/web/" . self::getFilesActivePath() . '/' . $path );

    }

    static function getFileUrl(string $path): string
    {
        return Yii::$app->urlManagerFrontend->baseUrl . '/' . self::getFilesActivePath() . '/' . $path;
    }

    static function getFolderName($id): string
    {
        $val             = (int) ( $id / 100 );
        $folderNameStart = $val * 100 + 1;
        $folderNameEnd   = $val * 100 + 100;
        $folderName      = $folderNameStart . '-' . $folderNameEnd;

        return $folderName;
    }

    static function createFolders(array $arrPath = []): void
    {
        foreach ( $arrPath as $path ) {
            if ( !file_exists( $path ) && !is_dir( $path ) ) {
                mkdir( $path, 0777, TRUE );
                chmod( $path, 0777 );
            }
        }
    }

}