<?php

use Gelf\Publisher;
use Gelf\Transport\IgnoreErrorTransportWrapper;
use Gelf\Transport\UdpTransport;
use Monolog\Handler\GelfHandler;
use Monolog\Level;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\MonologConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $containerConfigurator, MonologConfig $monologConfig): void
{
    $services = $containerConfigurator->services();

    $services->defaults()->autowire();

    $monologConfig
        ->handler('gelf')
        ->type('service')
        ->id('monolog.gelf_handler_info')
        ->level('info')
    ;

    $services->set('monolog.gelf_handler_info', GelfHandler::class)
        ->arg('$publisher', service('gelf.publisher'))
        ->arg('$level', Level::Info)
    ;

    $services->set('gelf.publisher', Publisher::class) //  hub for pushing out a GELF message to GELF endpoints
        ->call('addTransport', [inline_service(IgnoreErrorTransportWrapper::class) // #1 Graylog
            ->arg('$transport', inline_service(UdpTransport::class)
                 ->arg('$host', env('GRAYLOG_HOST'))
                 ->arg('$port', env('GRAYLOG_PORT'))
            )
        ])
    ;
};
