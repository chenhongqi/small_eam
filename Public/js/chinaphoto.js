/**
 * Created with JetBrains PhpStorm.
 * User: dell
 * Date: 13-5-29
 * Time: 下午4:27
 * To change this template use File | Settings | File Templates.
 */


var chinaphoto;
if (!chinaphoto) chinaphoto = {};

chinaphoto.resizeImage280 = function (ImgD) {
    var image = new Image();
    image.src = ImgD.src;
    if (image.width > 0 && image.height > 0) {
        //flag = true;
        if (image.width / image.height >= 280 / 280) {
            if (image.width > 280) {
                ImgD.width = 280;
                ImgD.height = (image.height * 280) / image.width;
            }
            else {
                ImgD.width = image.width;
                ImgD.height = image.height;
            }
        }
        else {
            if (image.height > 280) {
                ImgD.height = 280;
                ImgD.width = (image.width * 280) / image.height;
            }
            else {
                ImgD.width = image.width;
                ImgD.height = image.height;
            }
        }
    }

}
