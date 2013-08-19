<?php

require 'BaseUploadFile.class.php';
class UnistuUploadFile extends BaseUploadFile
{
    /*电子版照片格式要求：
      蓝色背景，正面免冠近照，图像分辨率最低为640×480 （照片大小在30KB以上），格式为.jpg
    */
    const minHeight = 640;
    const minWidth = 480;
    const minFileSize = 30730; //30K = 30*1024 = 30730
    const maxFileSize = 4194304; //4M =4*1024*1024=4194304

    protected function build_save_path($serverPath, $datePath)
    {
        //保存位置
        $savePath = C('MOUNT_DIR') . '/' . $serverPath . '/unistu/' . $datePath;
        return $savePath;
    }

    public static function relative_path($absoluteFilePath)
    {
        $start = strlen(C('MOUNT_DIR'));
        return substr($absoluteFilePath, $start);
    }

    public function __construct($config = array())
    {
        $config['allowExts'] = array('jpg'); //设置附件上传类型
        $config['maxSize'] = UnistuUploadFile::maxFileSize; // 设置附件上传大小
        $config['thumb'] = true;
        $config['thumbMaxWidth'] = 180;// 缩略图最大宽度
        $config['thumbMaxHeight'] = 250;//缩略图最大高度
        $config['thumbPrefix'] = '';// 缩略图前缀
        $config['thumbSuffix'] = '_thumb';// 缩略图后缀
        parent::__construct($config);
    }

    /**
     * 检查文件大小是否合法
     * @access private
     * @param integer $size 数据
     * @return boolean
     */
    protected function checkFileSize($size)
    {
        if ($size <= UnistuUploadFile::minFileSize) {
            return false;
        }
        if ($size > UnistuUploadFile::maxFileSize) {
            return false;
        }
        return true;
    }

    /**
     * 检查图片大小规则
     * @param $size
     * @return bool
     */
    protected function checkImageSize($info)
    {
        if ($info[0] < UnistuUploadFile::minWidth) //宽度
        {
            return false;
        }
        if ($info[1] < UnistuUploadFile::minHeight) //高度
        {
            return false;
        }
        return true;

    }
}
