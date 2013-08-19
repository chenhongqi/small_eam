/**
 * Created with JetBrains WebStorm.
 * User: dell
 * Date: 13-4-19
 * Time: 下午6:54
 * To change this template use File | Settings | File Templates.
 */

    var PicInfo = {
            createNew: function(){
                var pic = {};
                pic.id = "";
                pic.pic  = "";
                pic.title = "";
                pic.wtitle = "";
                pic.wdesc = "";
                pic.wsheyingshi = "";
                pic.weditor = "";
                pic.wtranslater = "";
                pic.wdate = "";
                pic.wright = "";
                pic.toshow="";

                pic.group="";

                pic.wprice="";
                pic.wpagenum = "";
                pic.wsize = "";
                pic.wauthor ="";
                pic.wpublisher = "";
                pic.wlanguage = "";

                pic.wpaper="";
                pic.wisnb="";

                pic.vidio = "";

                //pic.makeSound = function(){ alert("喵喵喵"); };
                return pic;
            }
    };


var hongqi;
if(!hongqi) hongqi={};

//----------------------------------解析节点-----————---------------------------------------------------------------
hongqi.processNode = function(field){
    var picinfo = PicInfo.createNew();
    try{
    picinfo.id = field.attr("id");
    picinfo.pic = field.children("p").text();
    picinfo.title = field.children("t").text();
    picinfo.wtitle = field.children("wtitle").text();
    picinfo.wdesc = field.children("wdesc").text();
    picinfo.wsheyingshi = field.children("wsheyingshi").text();
    picinfo.weditor = field.children("weditor").text();
    picinfo.wtranslater = field.children("wtranslater").text();
    picinfo.wdate = field.children("wdate").text();
    picinfo.wright = field.children("wright").text();
    picinfo.toshow= field.children("toshow").text();


    picinfo.group = field.children("group").text();//全球资讯使用

    //以下为图书画册专有属性
    picinfo.wprice= field.children("wprice").text();
    picinfo.wpagenum = field.children("wpagenum").text();
    picinfo.wsize = field.children("wsize").text();
    picinfo.wpublisher = field.children("wpublisher").text();
    picinfo.wlanguage = field.children("wlanguage").text();

    picinfo.wpaper = field.children("wpaper").text();
    picinfo.wisnb = field.children("wisnb").text();
        picinfo.wauthor= field.children("wauthor").text();
    }
    catch(error){
     alert(error.message);
    }

    return picinfo;
}
//----------------------------------------------------------------------------------------------------------------------

//----------------------------------添加标签（效率问题?）---------------------------------------------------------------
hongqi.appendToParent = function(elements,selector){
    var parent = jQuery(selector);
    parent.children().remove();
    jQuery(elements).appendTo(parent);
}
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------解析url-----------------------------------------------------------------------------
hongqi.getXmlUrl= function(path){
    //var path = "#lm#/20130428_W/#id#.xml";//001.xml路径
    var href = window.location.href;//xx?id=001&lm=xyyl

    var paramsStr = href.split("?")[1];//参数部分
    var paramsAry = paramsStr.split("&");

    var id="",lm="";
    for(var i=0;i<paramsAry.length;i++)
    {

        var param = paramsAry[i].split("=");

        if("id" == param[0]){
            id = param[1];
        }
        else if("lm" == param[0]){
            lm = param[1];
        }
    }

    if(!id) return "";

    var xmlUrl="";
    if(lm != ""){
        xmlUrl = path.replace("#id#",id).replace("#lm#",lm);
    }else{
        xmlUrl = path.replace("#id#",id).replace("#lm#/",lm);
    }

    return xmlUrl;

}

//----------------------------------------------------------------------------------------------------------------------