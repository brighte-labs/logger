<?php

declare(strict_types=1);

namespace BrighteCapital\Logger\Factories;

use BGalati\MonologSentryHandler\SentryHandler;
use BrighteCapital\Logger\Config;
use Monolog\ErrorHandler as MonologErrorHandler;
use Monolog\Formatter\FormatterInterface;
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
    public function create(FormatterInterface $formatter)
    {
        $logger = new Logger($this->config->getName());

        $handler = new MonologErrorHandler($logger);
        $handler->registerErrorHandler([], false);
        $handler->registerExceptionHandler();
        $handler->registerFatalHandler();

        if ($this->config->hasSentry()) {
            $ravenHandler = new SentryHandler(new Hub($this->getSentryClient()));
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

        $stream = new StreamHandler($this->config->getPath(), $this->config->getLevel());
        $stream->setFormatter($formatter);
        $logger->pushHandler($stream);

        return $logger;
    }

    /**
     * @return ClientInterface
     */
    private function getSentryClient(): ClientInterface
    {
        return ClientBuilder::create($this->config->getSentryConfig())->getClient();
    }
}
