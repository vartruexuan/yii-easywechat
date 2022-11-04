<?php

namespace Vartruexuan\EasyWechat;

use EasyWeChat\Factory;

use EasyWeChat\Kernel\ServiceContainer;
use yii\base\Component;
use yii\base\StaticInstanceTrait;
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
class EasyWechat extends Component
{
    use StaticInstanceTrait;

    const APP_OFFICIAL_ACCOUNT = 'officialAccount'; // 公众号
    const APP_PAYMENT = 'payment'; // 微信支付
    const APP_MINI_PROGRAM = 'miniProgram'; // 小程序
    const APP_OPEN_PLATFORM = 'openPlatform'; // 小程序
    const APP_WORK = 'work'; // 企业微信
    const APP_OPEN_WORK = 'openWork'; // 企业微信开放平台
    const APP_MICRO_MERCHANT = 'microMerchant'; // 小微商户

    /**
     * 配置key
     *
     * @var string
     */
    public string $configKey = 'easywechat';

    protected array $appInstances = [];


    public function __call($name, $arguments)
    {
        if (in_array($name, $this->getAppNames())) {
            return $this->getApp($name, $arguments[0] ?? 'default');
        }
        return parent::__call($name, $arguments);
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
        $wechatConfig = $this->getAppWechatConfig($appName, $config);
        $isConfigKey = is_string($config);
        $appConfig = !$isConfigKey ? $config : $this->getAppConfig($appName, $config);

        if ($isConfigKey) {
            $app = $this->appInstances[$appName][$config] ?? null;
        }
        if (!isset($app)) {
            $app = Factory::{$appName}($appConfig);
            // 绑定替换服务对象
            $this->rebind($app, $wechatConfig['rebind'] ?? []);
        }

        if ($isConfigKey) {
            $this->appInstances[$appName][$config] = $app;
        }

        return $app;
    }

    /**
     * 替换app对应模块
     *
     * @param ServiceContainer $app
     * @param                  $rebinds
     *
     * @return void
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function rebind(ServiceContainer &$app, $rebinds)
    {
        foreach ($rebinds as $id => $rebind) {
            if (is_callable($rebind)) {
                $rebind = call_user_func($rebind);
            }
            $app->rebind($id, $rebind);
        }
    }

    /**
     * app实例名集合
     *
     * @return string[]
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function getAppNames(): array
    {
        return [
            static::APP_OFFICIAL_ACCOUNT,
            static::APP_PAYMENT,
            static::APP_MINI_PROGRAM,
            static::APP_OPEN_PLATFORM,
            static::APP_WORK,
            static::APP_OPEN_WORK,
            static::APP_MICRO_MERCHANT,
        ];
    }

    /**
     * 获取app配置
     *
     * @param string $appName
     * @param string $configKey
     *
     * @return array
     * @throws \Exception
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function getAppConfig(string $appName = self::APP_OFFICIAL_ACCOUNT, string $configKey = 'default'): array
    {
        $appConfig = $this->getConfig("{$appName}.{$configKey}");
        $commonConfig = $this->getAppCommonConfig();
        return array_merge($commonConfig ?? [], $appConfig);
    }


    /**
     * 获取app公共配置
     *
     * @return ?array
     * @throws \Exception
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function getAppCommonConfig(): ?array
    {
        return $this->getConfig('appCommon');
    }

    /**
     * 获取当前组件独有配置
     *
     * @param string $appName
     * @param string|array $configKey
     *
     * @return array
     * @throws \Exception
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function getAppWechatConfig(string $appName = self::APP_OFFICIAL_ACCOUNT,  $configKey = 'default'): array
    {
        $wechatConfig = $this->getConfig('easywechat');
        $appConfig = $configKey;
        if(is_string($configKey)){
            $appConfig = $this->getAppConfig($appName, $configKey);
        }
        return array_merge($wechatConfig ?? [], $appConfig['easywechat'] ?? []);
    }

    /**
     * 获取配置
     *
     * @param ?string $key key1.key2
     *
     * @return mixed
     * @throws \Exception
     *
     * @date 2022/11/2
     * @author vartruexuan
     */
    protected function getConfig(?string $key = null)
    {
        $config = \Yii::$app->params[$this->configKey];
        if ($key) {
            $config = ArrayHelper::getValue($config, $key);
        }
        return $config;
    }


}
