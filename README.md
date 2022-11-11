<h1 align="center"> yii-easywechat </h1>  

<a href="https://packagist.org/packages/vartruexuan/yii-easywechat" rel="nofollow"><img src="https://badgen.net/github/tag/vartruexuan/yii-easywechat" alt="Latest Version" data-canonical-src="https://badgen.net/github/tag/vartruexuan/yii-easywechat" style="max-width: 100%;"></a>
<a href="https://www.php.net" rel="nofollow"><img src="https://img.shields.io/badge/php-%3E=7.4-brightgreen.svg?maxAge=2592000" alt="Php Version" data-canonical-src="https://img.shields.io/badge/php-%3E=7.4-brightgreen.svg?maxAge=2592000" style="max-width: 100%;"></a>

# 概述

- 基于组件 [w7corp/easywechat](https://github.com/w7corp/easywechat)

# 安装

```shell
composer require vartruexuan/yii-easywechat
```

# 使用

## 组件方式

```php
    // 加入配置组件
    'components' => [
        'wechat'=>[
            'class'=>'Vartruexuan\EasyWechat\EasyWechat',
            //'configkey'=>'easywechat', // 可指定配置key,默认easywechat
        ]
    ],
```

```php    
<?php

use Yii;

// 使用
Yii::$app->wechat->officialAccount(); // 公众号
Yii::$app->wechat->payment(); // 微信支付
Yii::$app->wechat->openPlatform(); // 小程序
Yii::$app->wechat->work(); // 企业微信
Yii::$app->wechat->openWork(); // 企业微信开放平台
Yii::$app->wechat->microMerchant(); // 小微商户
```

## 单例

```php
<?php

use Vartruexuan\EasyWechat\EasyWechat;

  
$app = EasyWechat::instance()->openWork();
$app->getServer()->with(fn() => "您好！EasyWeChat！");
$response = $server->serve();
    

```
# 配置
```php
<?php

return [
    'easywechat' => [
        // 置换服务配置
        'rebind' => [
                // 支持配置方式: 1.置换服务对象 2.支持回调方式
                'cache' => function (){
                    // 先临时置换下缓存服务
                    $config= Yii::$app->components['redis'];
                    $connStr = 'redis://' . $config['hostname'] . ':' . $config['port'];
                    $adapter = RedisAdapter::createConnection($connStr);
                    $password = $config['password'] ?? '';
                    if ($password) {
                        $adapter->auth($config['password']);
                    }
                    $database = $config['database'] ?? 0;
                    $adapter->select($database);
                    return new RedisAdapter($adapter);
                },
            ],
        ],
    ],

    // 公共配置(此处可配置app里的一些公共配置,优先级app里的最高)
    'appCommon' => [
        'http' => [
            'max_retries' => 1,
            'retry_delay' => 500,
            'timeout' => 5.0,
        ],
        'response_type' => 'array',
    ],

    // 公众号配置
    'officialAccount' => [
        'default' => [
            'app_id' => 'wx3cf0f39249eb0exx',
            'secret' => 'f1c242f4f28f735d4687abb469072axx',
        ],
    ],
    // 小程序
    'miniProgram' => [
        'default' => [
            'app_id' => 'wx3cf0f39249eb0exx',
            'secret' => 'f1c242f4f28f735d4687abb469072axx',
        ],
    ],
    // 开放平台
    'openPlatform' => [
        'default' => [
            'app_id' => '开放平台第三方平台 APPID',
            'secret' => '开放平台第三方平台 Secret',
            'token' => '开放平台第三方平台 Token',
            'aes_key' => '开放平台第三方平台 AES Key',
        ],
    ],
    // 企业微信
    'work' => [
        'default' => [
            'corp_id' => 'xxxxxxxxxxxxxxxxx',
            'agent_id' => 100020, // 如果有 agend_id 则填写
            'secret' => 'xxxxxxxxxx',
        ],
    ],
    // 企业微信开放平台
    'openWork' => [
        'default' => [
            'corp_id' => '服务商的corpid',
            'secret' => '服务商的secret，在服务商管理后台可见',
            'suite_id' => '以ww或wx开头应用id',
            'suite_secret' => '应用secret',
            'token' => '应用的Token',
            'aes_key' => '应用的EncodingAESKey',
            'reg_template_id' => '注册定制化模板ID',
            'redirect_uri_install' => '安装应用的回调url（可选）',
            'redirect_uri_single' => '单点登录回调url （可选）',
            'redirect_uri_oauth' => '网页授权第三方回调url （可选）',

        ],
    ],
    // 小微商户
    'microMerchant' => [
        'default' => [
            // 必要配置
            'mch_id' => 'your-mch-id', // 服务商的商户号
            'key' => 'key-for-signature', // API 密钥
            'apiv3_key' => 'APIv3-key-for-signature', // APIv3 密钥
            // API 证书路径(登录商户平台下载 API 证书)
            'cert_path' => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
            'key_path' => 'path/to/your/key', // XXX: 绝对路径！！！！
            // 以下两项配置在获取证书接口时可为空，在调用入驻接口前请先调用获取证书接口获取以下两项配置,如果获取过证书可以直接在这里配置，也可参照本文档获取平台证书章节中示例
            // 'serial_no'     => '获取证书接口获取到的平台证书序列号',
            // 'certificate'   => '获取证书接口获取到的证书内容'

            // 以下为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'appid' => 'wx931386123456789e' // 服务商的公众账号 ID
        ],
    ],
];

```





