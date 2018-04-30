<?php
/**
 * Description of Kdniao.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/4/29 18:29
 */

namespace static7;

use think\facade\{
    Request,Config
};

class Kdniao
{
    /**
     * @var $AppKey
     */
    protected $AppKey;

    /**
     * 请求内容需进行URL(utf-8)编码。请求内容JSON格式，须和DataType一致。
     * @var $RequestData
     */
    protected $RequestData;
    /**
     * 商户ID，请在我的服务页面查看。
     * @var $EBusinessID
     */
    protected $EBusinessID;
    /**
     * 请求指令类型：2002
     * @var string $RequestTyp
     */
    protected $RequestType='2002';
    /**
     * 数据内容签名：把(请求内容(未编码)+AppKey)进行MD5加密，然后Base64编码，最后 进行URL(utf-8)编码。详细过程请查看Demo。
     * @var $DataSign
     */
    protected $DataSign;
    /**
     * 请求、返回数据类型：只支持JSON格式
     * @var string $DataType
     */
    protected $DataType='2';

    /**
     * 快递公司名称
     * @var $ShipperName
     */
    protected $ShipperName;
    /**
     * 快递公司编码
     * @var $ShipperCode
     */
    protected $ShipperCode;

    /**
     * 物流单号
     * @var $LogisticCode
     */
    protected $LogisticCode;
    /***
     * @var $OrderCode
     */
    protected $OrderCode;

    /**
     * 错误
     * @var $error
     */
    protected $error;


    /**
     * @var string API正式地址
     */
    protected $url="http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx";

    public function __construct(array $config = [])
    {
        if (empty($config)) {
            $config = Config::get('config.kuaidiniao');
        } else if (empty($config['AppKey']) || empty($config['EBusinessID'])) {
            throw new Exception('配置 AppKey 和 EBusinessID 不能为空');
        }
        $this->setAppKey($config['AppKey']);
        $this->setEBusinessID($config['EBusinessID']);
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getAppKey()
    {
        return $this->AppKey;
    }

    /**
     * @param mixed $AppKey
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setAppKey($AppKey)
    {
        $this->AppKey = $AppKey;
        return $this;
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getRequestData()
    {
        return $this->RequestData;
    }

    /**
     * @param mixed $RequestData
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setRequestData($RequestData)
    {
        $this->RequestData = $RequestData;
        return $this;
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getEBusinessID()
    {
        return $this->EBusinessID;
    }

    /**
     * @param mixed $EBusinessID
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setEBusinessID($EBusinessID)
    {
        $this->EBusinessID = $EBusinessID;
        return $this;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getRequestType()
    {
        return $this->RequestType;
    }

    /**
     * @param string $RequestType
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setRequestType(string $RequestType)
    {
        $this->RequestType = $RequestType;
        return $this;
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getDataSign()
    {
        return $this->DataSign;
    }

    /**
     * @param mixed $DataSign
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setDataSign($DataSign)
    {
        $this->DataSign = $DataSign;
        return $this;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getDataType()
    {
        return $this->DataType;
    }

    /**
     * @param string $DataType
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setDataType(string $DataType)
    {
        $this->DataType = $DataType;
        return $this;
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getShipperName()
    {
        return $this->ShipperName;
    }

    /**
     * @param mixed $ShipperName
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setShipperName($ShipperName)
    {
        $this->ShipperName = $ShipperName;
        return $this;
    }

    /**
     * @param mixed $LogisticCode
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setLogisticCode($LogisticCode)
    {
        $this->LogisticCode = $LogisticCode;
        return $this;
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getLogisticCode()
    {
        return $this->LogisticCode;
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getShipperCode()
    {
        return $this->ShipperCode;
    }

    /**
     * @param mixed $ShipperCode
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setShipperCode($ShipperCode)
    {
        $this->ShipperCode = $ShipperCode;
        return $this;
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getOrderCode()
    {
        return $this->OrderCode;
    }

    /**
     * @param mixed $OrderCode
     * @author staitc7 <static7@qq.com>
     * @return Kdniao
     */
    public function setOrderCode($OrderCode)
    {
        $this->OrderCode = $OrderCode;
        return $this;
    }

    /**
     * 公共参数
     * @author staitc7 <static7@qq.com>
     * @param null $param
     * @return mixed
     * @throws \Exception
     */
    private function commonParam($param=null)
    {
        $data             = [
            'EBusinessID' => $this->getEBusinessID(),
            'RequestType' => $this->getRequestType(),
            'DataType' => $this->getDataType(),
            'DataSign' => $this->encrypt($param, $this->getAppKey()),
            'RequestData' => urlencode($param),
        ];
        return $this->sendRequest($data,'post');
    }

    /**
     * Json方式 单号识别
     * @param string $number
     * @return bool
     * @throws \Exception
     */
    public function getBiscernByWaybill($number = '')
    {
        if (empty($number)) {
            $this->error='号码不能为空';
            return false;
        }
        $LogisticCode     = json_encode(['LogisticCode' => $number]);
        $result           = $this->commonParam($LogisticCode,$this->setRequestType(2002));

        //根据公司业务处理返回的信息......
        if ($this->analyze($result)===false){
            return false;
        };
        
        return $this->getExpressInfo();
    }

    /**
     * 查询快递
     * @author staitc7 <static7@qq.com>
     * @param string $shipperCode
     * @param string $logisticCode
     * @param string $orderCode
     * @return mixed
     * @throws \Exception
     */
    public function getExpressInfo($shipperCode='',$logisticCode='',$orderCode='')
    {
        $shipperCode && $this->setShipperCode($shipperCode);
        $logisticCode && $this->setLogisticCode($logisticCode);
        $this->setOrderCode($orderCode);
        if(empty($this->getShipperCode()) || empty($this->getLogisticCode())){
            throw new \Exception('快递公司编码或物流单号错误');
        }
        $param = json_encode([
            "OrderCode" => "",
            "ShipperCode" => $this->getShipperCode(),
            "LogisticCode" => $this->getLogisticCode()
        ]);

        return $this->commonParam($param,$this->setRequestType(1002));
    }

    /**
     * 请求
     * @author staitc7 <static7@qq.com>
     * @param string $url
     * @param array  $param
     * @param string $method
     * @return mixed
     * @throws \Exception
     */
    public function sendRequest($param = [], $method = 'get', $url = '')
    {
        if ($url) {
            $this->url = $url;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = http_build_query($param ?? '');
        $headers=[
            "application/x-www-form-urlencoded; charset=utf-8",
            'Content-Length: ' . strlen($data),
        ];
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_USERAGENT, Request::header('user-agent'));
        if (strtolower($method) == 'get') {
            curl_setopt($ch, CURLOPT_URL, $this->url . '?' . $data);
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = sprintf("curl[%s] error[%s]", $this->url, curl_errno($ch) . ':' . curl_error($ch));
            curl_close($ch);
            throw new \Exception($error);
        }
        curl_close($ch);
        return $result;
    }

    /**
     * 电商Sign签名生成
     * @param array $data
     * @param string $appkey
     * @return DataSign签名
     */
    protected static function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data . $appkey)));
    }

    /**
     * 解析
     * @author staitc7 <static7@qq.com>
     * @param string $json
     * @return mixed
     */
    private function analyze(string $json='')
    {
        $object = json_decode($json,true);
        if ($object['Success'] === false) {
            $this->error ='物流单号匹配结果为空';
            return false;
        }
        $this->setLogisticCode($object['LogisticCode']);
        $this->setShipperName($object['Shippers'][0]['ShipperName']); //快递公司名称
        $this->setShipperCode($object['Shippers'][0]['ShipperCode']); //快递公司编码
        return true;
    }

    /**
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getError()
    {
        return $this->error;
    }




}