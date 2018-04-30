# kdniao
快递鸟物流查询接口

thinkphp5.1.x 专用

## 使用说明

* php版本 >=7.0.0

##使用方法
> 在config/你的模块名/config.php 配置
```
     //快递鸟
     'kuaidiniao'=>[
         'EBusinessID'=>'',
         'AppKey'=>''
     ],
```
>控制器
```
    $config=Config::get('config.kuaidiniao');
    $Kdniao=new \static7\Kdniao($config);
    $number='123456789';
    //直接输入快递单号
//  $data=$Kdniao->getBiscernByWaybill($number);
    或者输入快递公司的代号和快递单号
    $data=$Kdniao->getExpressInfo('ZTO',$number);
    dump($data); //返回的是json
```
### 具体可查看源码  
