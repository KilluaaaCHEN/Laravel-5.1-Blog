<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 15/11/25
 * Time: 11:18
 */
namespace App\Services;

use Request;

class FileService
{
    public static function upload($name, $path)
    {
        try {
            if (Request::hasFile($name)) {
                $pic = Request::file($name);
                if ($pic->isValid()) {
                    $newName = md5(rand(1, 1000) . $pic->getClientOriginalName()) . "." . $pic->getClientOriginalExtension();
                    $pic->move($path, $newName);
                    $url = asset($path . '/' . $newName);
                    return ['success' => 1, 'url' => $url];
                } else {
                    return ['success' => 0, 'message' => 'The file is invalid'];
                }
            } else {
                return ['success' => 0, 'message' => 'Not File'];
            }
        } catch (\Exception $e) {
            return ['success' => 0, 'message' => $e->getMessage()];
        }
    }
}
