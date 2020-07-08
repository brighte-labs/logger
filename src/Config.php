<?php

declare(strict_types=1);

namespace BrighteCapital\Logger;

use Monolog\Logger;

class Config
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $path;

    /** @var int */
    protected $level;

    /** @var bool */
    protected $sentry;

    /** @var string */
    protected $sentryDsn;

    /** @var string */
    protected $sentryPublicKey;

    /** @var string */
    protected $sentryHost;

    /** @var string */
    protected $sentryProjectId;

    /** @var string */
    protected $sentryEnvironment;

    /**
     * MonologConfig constructor.
     *
     * @param array|null $config
     */
    public function __construct(array $config)
    {
        $this->name = $config['name'] ?: 'brighte-capital';
        $this->path = $config['path'] ?: 'php://stderr';
        $this->level = (int) $config['level'] ?: Logger::ERROR;
        if ($sentry = $config['sentry']) {
            $this->sentry = $sentry['app_log_sentry'] == "true" ? true : false;
            $this->sentryDsn = $sentry['sentry_dsn'] ?: null;
            $this->sentryEnvironment = $sentry['sentry_environment'] ?: null;
        }
    }

    /**
     * @return array
     */
    public function getSentryConfig(): array
    {
        return [
            'dsn' => $this->sentryDsn,
            'environment' => $this->sentryEnvironment,
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return \BrighteCapital\Logger\Config
     */
    public function setName(string $name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int|string $level
     * @return \BrighteCapital\Logger\Config
     */
    public function setLevel(int $level = 0)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSentry(): bool
    {
        return $this->sentry;
    }
}
