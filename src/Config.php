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
        $this->name = isset($config['name']) ? $config['name'] : null;
        $this->path = isset($config['path']) ? $config['path'] : null;
        $this->level = (int) isset($config['level']) ? $config['level'] : null;
        $this->sentry = (isset($config['sentry']) && $config['sentry'] == "true") ? true : false;
        $this->sentryDsn = isset($config['sentry_dsn']) ? $config['sentry_dsn'] : null;
        $this->sentryPublicKey = isset($config['sentry_public_key']) ? $config['sentry_public_key'] : null;
        $this->sentryHost = isset($config['sentry_host']) ? $config['sentry_host'] : null;
        $this->sentryProjectId = isset($config['sentry_project_id']) ? $config['sentry_project_id'] : null;
        $this->sentryEnvironment = isset($config['sentry_environment']) ? $config['sentry_environment'] : null;
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
    public function isSentry()
    {
        return $this->sentry;
    }
}
