# kdniao
快递鸟物流查询接口

#使用方法
>配置config
```
    //配置文件
     $config=[
        'AppKey'=>'',//电商加密私钥，快递鸟提供，注意保管，不要泄漏
        'EBusinessID'=>'',//电商ID
        'URL'=>'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx'//请求url
    ];
```
>控制器
```
  use static7\kdniao\Kdniao;
  控制器省略多余代码....
  $express = new Kdniao($config);
  //$number 货单号 $type 文本输出 0-json输出 1 文本输出
  $result = $express->kdniaoApiOrder($number='',$type=0);
  ```
