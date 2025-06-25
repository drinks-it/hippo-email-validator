<?php

namespace Nrgone\EmailHippo\Validator\Tests;

use Nrgone\EmailHippo\Validator\ConfigInterface;
use Nrgone\EmailHippo\Validator\Validator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ValidatorTest extends TestCase
{
    private function getValidator(array $configValues, array $responseData = []): Validator
    {
        /** @var ConfigInterface&\PHPUnit\Framework\MockObject\MockObject $config */
        $config = $this->createConfiguredMock(ConfigInterface::class, $configValues);

        /** @var ResponseInterface&\PHPUnit\Framework\MockObject\MockObject $response */
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn($responseData);

        /** @var HttpClientInterface&\PHPUnit\Framework\MockObject\MockObject $httpClient */
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        /** @var LoggerInterface&\PHPUnit\Framework\MockObject\MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        return new Validator($config, $httpClient, $logger);
    }

    public function testIsValidReturnsTrueWhenDisabled(): void
    {
        $validator = $this->getValidator([
            'isEnabled' => false,
            'getApiUrl' => '',
            'getApiKey' => '',
            'getMinimumHippoScore' => 0.0,
            'isLoggingEnabled' => false,
        ]);

        $this->assertTrue($validator->isValid('test@example.com'));
    }

    public function testIsValidUsesHippoScore(): void
    {
        $validator = $this->getValidator([
            'isEnabled' => true,
            'getApiUrl' => 'http://api',
            'getApiKey' => 'key',
            'getMinimumHippoScore' => 0.5,
            'isLoggingEnabled' => false,
        ], [
            'hippoTrust' => ['score' => 0.6],
        ]);

        $this->assertTrue($validator->isValid('test@example.com'));
    }

    public function testIsValidFailsWithLowScore(): void
    {
        $validator = $this->getValidator([
            'isEnabled' => true,
            'getApiUrl' => 'http://api',
            'getApiKey' => 'key',
            'getMinimumHippoScore' => 0.9,
            'isLoggingEnabled' => false,
        ], [
            'hippoTrust' => ['score' => 0.1],
        ]);

        $this->assertFalse($validator->isValid('test@example.com'));
    }

    public function testIsExistReturnsTrueWhenDisabled(): void
    {
        $validator = $this->getValidator([
            'isEnabled' => false,
            'getApiUrl' => '',
            'getApiKey' => '',
            'getMinimumHippoScore' => 0.0,
            'isLoggingEnabled' => false,
        ]);

        $this->assertTrue($validator->isExist('test@example.com'));
    }

    public function testIsExistDetectsMissingMailbox(): void
    {
        $validator = $this->getValidator([
            'isEnabled' => true,
            'getApiUrl' => 'http://api',
            'getApiKey' => 'key',
            'getMinimumHippoScore' => 0.0,
            'isLoggingEnabled' => false,
        ], [
            'emailVerification' => [
                'mailboxVerification' => [
                    'reason' => 'MailboxDoesNotExist',
                ],
            ],
        ]);

        $this->assertFalse($validator->isExist('test@example.com'));
    }

    public function testIsExistReturnsTrueWhenMailboxExists(): void
    {
        $validator = $this->getValidator([
            'isEnabled' => true,
            'getApiUrl' => 'http://api',
            'getApiKey' => 'key',
            'getMinimumHippoScore' => 0.0,
            'isLoggingEnabled' => false,
        ], []);

        $this->assertTrue($validator->isExist('test@example.com'));
    }
}

