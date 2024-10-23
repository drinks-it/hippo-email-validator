<?php

namespace Nrgone\EmailHippo\Validator;

class Config implements ConfigInterface
{
    private bool $isEnabled;

    private string $apiUrl;

    private string $apiKey;

    private float $minimumHippoScore;

    private bool $isLoggingEnabled;

    public function __construct(
        bool $isEnabled,
        string $apiUrl,
        string $apiKey,
        float $minimumHippoScore,
        bool $isLoggingEnabled
    ) {
        $this->isEnabled = $isEnabled;
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
        $this->minimumHippoScore = $minimumHippoScore;
        $this->isLoggingEnabled = $isLoggingEnabled;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getMinimumHippoScore(): float
    {
        return $this->minimumHippoScore;
    }

    public function isLoggingEnabled(): bool
    {
        return $this->isLoggingEnabled;
    }
}
