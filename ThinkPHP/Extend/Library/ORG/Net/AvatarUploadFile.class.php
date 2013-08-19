<?php

require 'BaseUploadFile.class.php';
class AvatarUploadFile extends BaseUploadFile
{

    protected function build_save_path($serverPath,$datePath){
        $savePath =C('MOUNT_DIR').'/'.$serverPath . '/avatar/' .$datePath;
        return $savePath;
    }

    public static function relative_path($absoluteFilePath)
    {
        $start = strlen(C('MOUNT_DIR')) ;
        return substr($absoluteFilePath, $start);
    }

    public function __construct($config = array())
    {
        $config['allowExts'] = array('jpg', 'png', 'jpeg'); //设置附件上传类型
        $config['maxSize'] = 1*1024*1024; // 设置附件上传大小1M
        $config['thumb'] = true;
        $config['thumbMaxWidth'] = 75;// 缩略图最大宽度
        $config['thumbMaxHeight'] = 100;//缩略图最大高度
        $config['thumbPrefix'] = '';// 缩略图前缀
        $config['thumbSuffix'] = '_thumb';// 缩略图后缀
        $config['thumbRemoveOrigin']=true;//删除原图
        parent::__construct($config);
    }


}
