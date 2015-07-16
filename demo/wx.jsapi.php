<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>BeeCloud微信扫码示例</title>
</head>
<body>
<?php
require_once("../beecloud.php");

$data = array();
$appSecret = "39a7a518-9ac8-4a9e-87bc-7885f33cf18c";
$data["app_id"] = "c5d1cba1-5e3f-4ba0-941d-9b0a371fe719";
$data["timestamp"] = time() * 1000;
$data["app_sign"] = md5($data["app_id"] . $data["timestamp"] . $appSecret);
$data["channel"] = "WX_JSAPI";
$data["total_fee"] = 1;
$data["bill_no"] = "bcdemo" . $data["timestamp"];
$data["title"] = "白开水";

/**
 * 微信用户的openid获取请参考官方demo sdk和文档
 * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=11_1
 * 微信获取openid php代码, 运行环境是微信内置浏览器访问时,以下注释代码为逻辑示例
 */
//    include_once('../dependency/WxPayPubHelper.php');
//    $jsApi = new JsApi_pub();
//    //网页授权获取用户openid============
//    //通过code获得openid
//    if (!isset($_GET['code'])){
//        //触发微信返回code码
//        $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
//        Header("Location: $url");
//    } else {
//        //获取code码，以获取openid
//        $code = $_GET['code'];
//        $jsApi->setCode($code);
//        $openid = $jsApi->getOpenId();
//    }
$data["openid"] = "o3kKrjlUsMnv__cK5DYZMl0JoAkY";

//选填 optional
$data["optional"] = json_decode(json_encode(array("tag"=>"msgtoreturn")));
//选填 return_url
//$data["return_url"] = "http://payservice.beecloud.cn";

try {
    $result = BCRESTApi::bill($data);
    if ($result->result_code != 0) {
        echo json_encode($result);
        exit();
    }
?>
</br></br></br></br>
<div align="center">
    <button style="width:210px; height:30px; background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >贡献一下</button>
</div>
<script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall() {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo json_encode($result);?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                alert(res.err_code+res.err_desc+res.err_msg);
            }
        );
    }
    function callpay() {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }
</script>
<?php
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
</body>
</html>