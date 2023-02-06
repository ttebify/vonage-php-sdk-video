<?php
declare(strict_types=1);

namespace Vonage\Video\Broadcast;

class Broadcast
{
    protected string $id;

    protected string $sessionId;

    protected string $applicationId;

    protected \DateTimeImmutable $createdAt;

    protected \DateTimeImmutable $updatedAt;

    protected int $maxBitrate;

    protected int $maxDuration;

    protected string $resolution;

    protected string $streamMode;

    protected ?string $hlsPath;

    protected array $rtmpStreams;

    protected bool $hasAudio;

    protected bool $hasVideo;

    protected array $settings = [];

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->sessionId = $data['sessionId'];
        $this->applicationId = $data['applicationId'];
        $this->createdAt = (new \DateTimeImmutable)->setTimestamp($data['createdAt']);
        $this->updatedAt = (new \DateTimeImmutable)->setTimestamp($data['updatedAt']);
        $this->resolution = $data['resolution'];
        $this->streamMode = $data['streamMode'];
        $this->hasAudio = (bool) $data['hasAudio'];
        $this->hasVideo = (bool) $data['hasVideo'];
        $this->maxDuration = (int) $data['maxDuration'];
        $this->maxBitrate = (int) $data['maxBitrate'];

        if (array_key_exists('hls', $data['broadcastUrls'])) {
            $this->hlsPath = $data['broadcastUrls']['hls'];
        }

        if (array_key_exists('rtmp', $data['broadcastUrls'])) {
            $this->rtmpStreams = $data['broadcastUrls']['rtmp'];
        }

        if (array_key_exists('settings', $data)) {
            $this->settings = $data['settings'];
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getApplicationId(): string
    {
        return $this->applicationId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getHLSPath(): ?string
    {
        return $this->hlsPath;
    }

    public function getMaxBitrate(): int
    {
        return $this->maxBitrate;
    }

    public function getMaxDuration(): int
    {
        return $this->maxDuration;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getResolution(): string
    {
        return $this->resolution;
    }

    public function getRTMPStreams(): array
    {
        return $this->rtmpStreams;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getStreamMode(): string
    {
        return $this->streamMode;
    }

    public function hasAudio(): bool
    {
        return $this->hasAudio;
    }

    public function hasVideo(): bool
    {
        return $this->hasVideo;
    }
}