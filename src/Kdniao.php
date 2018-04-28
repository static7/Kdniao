<?php
/**
 * Description of Kdniao.php.
 * 快递鸟快递查询类
 * User: static7 <static7@qq.com>
 * Date: 2016-12-20 15:11
 */

namespace static7;

class Kdniao {
    //配置文件
    protected $config = [
        'AppKey' => '',//电商加密私钥，快递鸟提供，注意保管，不要泄漏
        'EBusinessID' => '',//电商ID
        'URL' => 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx'//请求的url
    ];

    public function __construct($config = null) {
        if (!$config['AppKey'] || !$config['EBusinessID']) {
            throw new \Exception('缺少AppKey或者EBusinessID');
        } else {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * 快递信息详情
     * @param string $number 货单号
     * @param int    $type 文本输出 0-json输出 1 文本输出
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function kdniaoApiOrder($number = '', $type = 0) {
        $nameCode = $this->kdniaoApiName($number);
        if (!$nameCode) {
            return '快递公司参数异常：单号不存在或者已经过期';
        }
        $info = json_encode(['ShipperCode' => $nameCode['ShipperCode'], 'LogisticCode' => $number]);
        $data = $this->commonApiData($info, 1002);
        if ((int)$type === 1) {
            return $this->resolve($data, $nameCode);
        } else {
            return $data;
        }

    }

    /**
     * 快递鸟订单号查询快递名称
     * @author staitc7 <static7@qq.com>
     * @param string $number 运单号
     * @return mixed
     * @throws \Exception
     */
    public function kdniaoApiName($number = '') {
        $info = json_encode(['LogisticCode' => $number]);
        $data = $this->commonApiData($info, 2002);
        if ($data['Success']) {
            return $data['Shippers'][0];
        } else {
            return false;
        }
    }

    /**
     * 通用组装转换数组
     * @param null $requestData 请求内容需进行URL(utf-8)编码。请求内容JSON格式，须和DataType一致。
     * @param null $RequestType 请求指令类型
     * @return mixed|null
     * @throws \Exception
     */
    private function commonApiData($requestData = null, $RequestType = null) {
        $data = [
            'RequestData' => urlencode($requestData),
            'RequestType' => $RequestType,
            'EBusinessID' => $this->config['EBusinessID'],
            'DataType' => '2',
            'DataSign' => $this->encrypt($requestData, $this->config['AppKey']
        )];
        $info = $this->http($this->config['URL'], $data);
        return $info ? json_decode($info, true) : null;
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
     * 发送HTTP请求方法，目前只支持CURL发送请求
     * @param  string $url 请求URL
     * @param  array $param GET参数数组
     * @param array|string $data POST的数据，GET请求时该参数无效
     * @param  string $method 请求方法GET/POST
     * @return array 响应数据
     * @throws \Exception
     */
    protected function http($url, $param, $data = '', $method = 'GET') {
        $opts = [CURLOPT_TIMEOUT => 30, CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false];

        /* 根据请求类型设置特定参数 */
        $opts[CURLOPT_URL] = $url . '?' . http_build_query($param);

        if (strtoupper($method) == 'POST') {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $data;
            if (is_string($data)) { //发送JSON数据
                $opts[CURLOPT_HTTPHEADER] = ['Content-Type: application/json; charset=utf-8', 'Content-Length: ' . strlen($data)];
            }
        }

        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        //发生错误，抛出异常
        if ($error) {
            throw new \Exception('请求发生错误：' . $error);
        } else {
            return $data;
        }
    }

    /**
     * 解析返回状态
     * @param  array $data 解析快递数据
     * @param string $name 快递中文名称
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function resolve($data = [], $name = '') {
        if ($data['Success']) {
            $string = ['运单号：' . $data['LogisticCode'], '运送公司：' . $name['ShipperName'],];
            foreach ($data['Traces'] as $k => $v) {
                array_push($string, $v['AcceptTime'] . "：" . $v['AcceptStation']);
            }
            switch ($data['State']) {
                case 2:
                    array_push($string, '你的快递还在途中');
                    break;
                case 3:
                    array_push($string, '你的快递已签收');
                    break;
                case 4:
                    array_push($string, '你的快递出现问题了');
                    break;
            }
        } else {
            if (empty($data['Traces'])) {
                $string = ['运单号：' . $data['LogisticCode'], '运送公司：' . $name['ShipperName'], '你的快递还没有任何消息哦！'];
            }
        }
        /** @var TYPE_NAME $string */
        return implode("\n", $string);
    }
}
