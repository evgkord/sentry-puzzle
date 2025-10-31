<?php

use Monolog\Level;
use Sentry\State\HubInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\MonologConfig;
use Symfony\Config\SentryConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $containerConfigurator, SentryConfig $sentryConfig, MonologConfig $monologConfig): void
{
    $sentryConfig
        ->dsn(env('SENTRY_DSN'))
        ->registerErrorListener(true)
        ->options()
            ->httpTimeout(2)
            ->httpConnectTimeout(2)
    ;

    $monologConfig
        ->handler('sentry')
            ->type('sentry')
            ->level(Level::Error->value)
            ->hubId(HubInterface::class)
            ->fillExtraContext(true)
    ;
};
