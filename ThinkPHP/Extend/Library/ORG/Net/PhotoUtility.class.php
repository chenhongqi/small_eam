<?php
/**
 * Project: site
 * File: PhotoUtility.class.php
 * Author: chenhongqi
 * DateTime: 13-7-24 下午1:36
 * To change this template use File | Settings | File Templates.
 */

class PhotoUtility
{

    public static function imageSizeFormate($srcImage, $width, $fileDir)
    {
        $srcSize = getimagesize($srcImage);
        if ($srcSize[0] > $width) {
            $height = ($width / $srcSize[0]) * $srcSize[1];
            $tmpImage = imagecreatetruecolor($width, $height);
            $srcImageSource = imagecreatefromjpeg($srcImage);
            imagecopyresampled($tmpImage, $srcImageSource, 0, 0, 0, 0,$width, $height, $srcSize[0], $srcSize[1]);
            $fileName = mktime();
            $dstPath = realpath("g:/"). $fileName . ".jpg";
            if (imagejpeg($tmpImage, $dstPath)) {
                //return unlink($srcImage);
            }

            return false;

        }
    }
}