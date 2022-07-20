<?php
declare(strict_types=1);

namespace Vonage\Video;

class ProjectDetails
{
    protected string $id;
    protected string $secret;
    protected string $status;
    protected string $name;
    protected string $environment;
    protected \DateTimeImmutable $createdAt;

    public function __construct(array $details)
    {
        $this->id = $details['id'];
        $this->secret = $details['secret'];
        $this->status = $details['status'];
        $this->name = $details['name'];
        $this->environment = $details['environment'];
        $this->createdAt = (new \DateTimeImmutable())->setTimestamp($details['createdAt']);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}