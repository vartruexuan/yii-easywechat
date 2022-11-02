<?php

return [
    'easywechat' => [
        'rebind' => [
            'cache' => '',
        ],
    ],

    // 公共配置
    'appCommon' => [


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

