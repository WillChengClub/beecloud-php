<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>BeeCloud京东订单查询示例</title>
</head>
<body>
<table border="1" align="center" cellspacing=0>
<?php
require_once("../../loader.php");
date_default_timezone_set("Asia/Shanghai");
$now = date('Y-m-d',time());
$refund_no = str_replace("-","",$now).time() * 1000;
$data = array();
$appSecret = "c37d661d-7e61-49ea-96a5-68c34e83db3b";
$data["app_id"] = "c37d661d-7e61-49ea-96a5-68c34e83db3b";
$data["timestamp"] = time() * 1000;
$data["app_sign"] = md5($data["app_id"] . $data["timestamp"] . $appSecret);
//选择渠道类型(WX、WX_APP、WX_NATIVE、WX_JSAPI、ALI、ALI_APP、ALI_WEB、ALI_QRCODE、UN、UN_APP、UN_WEB。。。)
$data["channel"] = "BD";
$data["limit"] = 10;


try {
    $result = \beecloud\rest\api::bills($data);
    if ($result->result_code != 0 || $result->result_msg != "OK") {
        echo json_encode($result->err_detail);
        exit();
    }
    $bills = $result->bills;
    echo "<tr><td>同意退款</td><td>是否支付</td><td>创建时间</td><td>总价(分)</td><td>渠道类型</td><td>订单号</td><td>订单标题</td></tr>";
    foreach($bills as $list) {
        echo "<tr>";
        foreach($list as $k=>$v) {
            if($k=="spay_result" && $v) {
                echo "<td><a href='jd.agree.refund.php?refund_no=".$refund_no."&bill_no=".$list->bill_no."&refund_fee=".$list->total_fee."'>同意退款</a></td>";
            } else if($k=="spay_result" && !$v){
                echo "<td></td>";
            }
            echo "<td>".($k=="spay_result"?($v?"支付":"未支付"):($k=="created_time"?date('Y-m-d H:i:s',$v/1000):$v))."</td>";
        }
        echo "</tr>";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
</table>
</body>
</html>