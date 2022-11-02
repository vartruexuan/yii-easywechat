<?php

namespace Vartruexuan\EasyWechat;

use EasyWeChat\Factory;

use EasyWeChat\Kernel\ServiceContainer;
use yii\base\Component;
use yii\base\StaticInstanceTrait;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * easywehcat
 *
 * @method \EasyWeChat\OfficialAccount\Application  officialAccount($config = 'default') 公众号实例
 * @method \EasyWeChat\Payment\Application  payment($config = 'default')
 * @method \EasyWeChat\MiniProgram\Application  miniProgram($config = 'default')
 * @method \EasyWeChat\OpenPlatform\Application  openPlatform($config = 'default')
 * @method \EasyWeChat\Work\Application  work($config = 'default')
 * @method \EasyWeChat\OpenWork\Application  openWork($config = 'default')
 * @method \EasyWeChat\MicroMerchant\Application  microMerchant($config = 'default')
 */
class EasyWechat
{
    use StaticInstanceTrait;

    const APP_OFFICIAL_ACCOUNT = 'officialAccount'; // 公众号
    const APP_PAYMENT = 'payment'; // 微信支付
    const APP_MINI_PROGRAM = 'miniProgram'; // 小程序
    const APP_OPEN_PLATFORM = 'openPlatform'; // 小程序
    const APP_WORK = 'work'; // 企业微信
    const APP_OPEN_WORK = 'openWork'; // 企业微信开放平台
    const APP_MICRO_MERCHANT = 'microMerchant'; // 小微商户

    protected array $appInstances = [];


    public function __call($name, $arguments)
    {
        return $this->getApp($name, $arguments[0] ?? 'default');
    }

    /**
     * 获取app实例
     *
     * @param string       $appName
     * @param string|array $config
     *
     * @return ServiceContainer
     * @throws \Exception
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function getApp(string $appName = self::APP_OFFICIAL_ACCOUNT, $config = 'default')
    {
        $appConfig = is_array($config) ? $config : $this->getAppConfig($appName, $config);
        $isConfigKey = is_string($config);
        $app = $this->appInstances[$appName][$config] ?? null;

        if (!$app) {
            $app = Factory::{$appName}($appConfig);
        }
        if ($isConfigKey) {
            $this->appInstances[$appName][$config] = $app;
        }

        return $app;
    }


    /**
     * 获取app配置
     *
     * @param $appName
     * @param $configKey
     *
     * @return array
     * @throws \Exception
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function getAppConfig($appName = self::APP_OFFICIAL_ACCOUNT, $configKey = 'default')
    {
        $appConfig = $this->getConfig("{$appName}.{$configKey}");
        $commonConfig = $this->commonConfig();
        return array_merge($commonConfig ?? [], $appConfig);
    }

    /**
     * 获取配置
     *
     * @param $key   key1.key2
     *
     * @return mixed
     * @throws \Exception
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function getConfig($key = null)
    {
        $config = \Yii::$app->params['easywechat'];
        if ($key) {
            $config = ArrayHelper::getValue($config, $key);
        }
        return $config;
    }


    /**
     * 获取公共配置
     *
     * @return array
     * @throws \Exception
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function commonConfig()
    {
        return $this->getConfig('common');
    }
}
