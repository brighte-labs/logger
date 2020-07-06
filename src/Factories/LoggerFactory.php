<?php

declare(strict_types=1);

namespace BrighteCapital\Logger\Factories;

use BGalati\MonologSentryHandler\SentryHandler;
use BrighteCapital\Logger\Config;
use Monolog\ErrorHandler as MonologErrorHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Sentry\ClientBuilder;
use Sentry\ClientInterface;

class LoggerFactory
{
    protected $config;

    /**
     * LoggerFactory constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Logger
     * @throws \Exception
     */
    public function create()
    {
        $logger = new Logger($this->config->getName());

        $handler = new MonologErrorHandler($logger);
        $handler->registerErrorHandler([], false);
        $handler->registerExceptionHandler();
        $handler->registerFatalHandler();

        if ($this->config->isSentry()) {
            $ravenHandler = new SentryHandler(new Hub($this->getSentry()));
            $logger->pushHandler(
                new FingersCrossedHandler(
                    $ravenHandler,
                    Logger::ERROR,
                    1000,
                    true,
                    false
                )
            );

            $logger->pushProcessor(new ProcessIdProcessor());
            $logger->pushProcessor(new UidProcessor());
            $logger->pushProcessor(new WebProcessor());
            $logger->pushProcessor(new MemoryUsageProcessor());
            $logger->pushProcessor(new IntrospectionProcessor());
        }

        $logger->pushHandler(new StreamHandler($this->config->getPath(), $this->config->getLevel()));

        return $logger;
    }

    /**
     * @return ClientInterface
     */
    private function getSentry(): ClientInterface
    {
        return ClientBuilder::create($this->config->getSentryConfig())->getClient();
    }
}
