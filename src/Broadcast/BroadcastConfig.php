<?php
declare(strict_types=1);

namespace Vonage\Video\Broadcast;

use Vonage\Video\Layout;
use Vonage\Video\Resolution;

class BroadcastConfig
{
    protected string $sessionId;

    protected bool $hasAudio = true;

    protected bool $hasVideo = true;

    protected ?Layout $layout = null;

    protected ?int $maxBitrate = null;

    protected ?int $maxDuration = null;

    protected ?string $multiBroadcastTag = null;

    protected ?OutputConfig $outputConfig = null;

    protected ?string $resolution = null;

    protected ?string $streamMode = null;

    public function __construct(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function disableAudio(): self
    {
        $this->hasAudio = false;
        return $this;
    }

    public function disableVideo(): self
    {
        $this->hasVideo = false;
        return $this;
    }

    public function enableAudio(): self
    {
        $this->hasAudio = true;
        return $this;
    }

    public function enableVideo(): self
    {
        $this->hasVideo = true;
        return $this;
    }

    public function getLayout(): ?Layout
    {
        return $this->layout;
    }

    public function getMaxBitrate(): ?int
    {
        return $this->maxBitrate;
    }

    public function getMaxDuration(): ?int
    {
        return $this->maxDuration;
    }

    public function getMultiBroadcastTag(): ?string
    {
        return $this->multiBroadcastTag;
    }

    public function getOutputConfig(): ?OutputConfig
    {
        return $this->outputConfig;
    }

    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    public function getStreamMode(): ?string
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

    public function setLayout(Layout $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    public function setMaxBitrate(int $bitrate): self
    {
        $this->maxBitrate = $bitrate;
        return $this;
    }

    public function setMaxDuration(int $duration): self
    {
        $this->maxDuration = $duration;
        return $this;
    }

    public function setMultiBroadcastTag(string $tag): self
    {
        $this->multiBroadcastTag = $tag;
        return $this;
    }

    public function setOutputConfig(OutputConfig $outputConfig): self
    {
        $this->outputConfig = $outputConfig;
        return $this;
    }

    public function setResolution(string $resolution): self
    {
        Resolution::isValid($resolution);

        $this->resolution = $resolution;
        return $this;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function setStreamMode(string $streamMode): self
    {
        $whitelist = [
            'manual',
            'auto'
        ];

        if (!in_array($streamMode, $whitelist)) {
            throw new \InvalidArgumentException('Invalid stream mode supplied for broadcast');
        }

        $this->streamMode = $streamMode;
        return $this;
    }

    public function toArray()
    {
        $data = [
            'sessionId' => $this->getSessionId(),
            'outputs' => $this->getOutputConfig()->toArray(),
        ];

        if ($this->getLayout()) {
            $data['layout'] = $this->getLayout()->toArray();
        }

        if ($this->getMaxBitrate()) {
            $data['maxBitrate'] = $this->getMaxBitrate();
        }

        if ($this->getMaxDuration()) {
            $data['maxDuration'] = $this->getMaxDuration();
        }

        if ($this->getResolution()) {
            $data['resolution'] = $this->getResolution();
        }

        if ($this->getStreamMode()) {
            $data['streamMode'] = $this->getStreamMode();
        }

        return $data;
    }
}