<?php

class AlidayuUtil{
    public $appkey;
    public $secretKey;
    public $client;
    public $req;
    public $TYPE_REGISTER =1;
    public $TYPE_FIND_PASSWORD =2;
    public $PRODUCT = "MySweetKiss";
    public function __construct(){
        $this->appkey = "23327967";
        $this->secretKey = "bbc7a035cf2c27eb62ef52e6d4c6167f" ;

        //TODO 其实应该引入TopSdk.php
        define("TOP_SDK_WORK_DIR", "/tmp/");
        define("TOP_SDK_DEV_MODE", true);

        $this->client = new TopClient;
        $this->client->appkey = $this->appkey;
        $this->client->secretKey = $this->secretKey;
        $this->req = new AlibabaAliqinFcSmsNumSendRequest;
        date_default_timezone_set('Asia/Shanghai');

    }

    public function send($phone, $code,$type) {
        $this->req->setExtend($code);
        $this->req->setSmsType("normal");
        $this->req->setRecNum($phone);
        $this->req->setSmsParam("{code:\"".$code."\",product:\"".$this->PRODUCT."\"}");
        switch($type) {
            case $this->TYPE_REGISTER:
                $this->req->setSmsFreeSignName("注册验证");
                $this->req->setSmsTemplateCode("SMS_6270025");
                break;
            case $this->TYPE_FIND_PASSWORD:
                $this->req->setSmsFreeSignName("变更验证");
                $this->req->setSmsTemplateCode("SMS_6270022");
                break;
        }
        return $this->client->execute($this->req);
    }

    public function generate_code($length = 6) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }

    public function isSuccess($res) {
        $resObject = json_decode($res);
        if (isset($resObject->result )){
            //TODO   stdClass Object ( [code] => 40 [msg] => client-check-error:Missing Required Arguments: recNum )
            return ($resObject->result->success == "true");
        } else {
            return false;
        }

    }
}
?>