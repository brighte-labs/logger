<?php

declare(strict_types=1);

namespace BrighteCapital\Logger;

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
        $this->name = $config['name'] ?: null;
        $this->path = $config['path'] ?: null;
        $this->level = (int) $config['level'] ?: null;
        if ($sentry = $config['sentry']) {
            $this->sentry = $sentry['active'] == "true" ? true : false;
            $this->sentryDsn = $sentry['sentry_dsn'] ?: null;
            $this->sentryPublicKey = $sentry['sentry_public_key'] ?: null;
            $this->sentryHost = $sentry['sentry_host'] ?: null;
            $this->sentryProjectId = $sentry['sentry_project_id'] ?? null;
            $this->sentryEnvironment = $sentry['sentry_environment'] ?: null;
        }
    }

    /**
     * @return array
     */
    public function getSentryConfig()
    {
        return [
            'dsn' => $this->sentryDsn,
            'public_key' => $this->sentryPublicKey,
            'host' => $this->sentryHost,
            'project_id' => $this->sentryProjectId,
            'environment' => $this->sentryEnvironment,
        ];
    }

    /**
     * @return string
     */
    public function getName()
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int|string $level
     * @return \BrighteCapital\Logger\Config
     */
    public function setLevel($level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSentry()
    {
        return $this->sentry;
    }
}
