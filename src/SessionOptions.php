<?php

declare(strict_types=1);

namespace Vonage\Video;

use Vonage\Video\Archive\ArchiveMode;

class SessionOptions
{
    /**
     * @var string
     */
    protected $archiveMode;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $mediaMode;

    /**
     * @param array{archiveMode: string, location: string, mediaMode: string} $data 
     */
    public function __construct($data = [])
    {
        $this->archiveMode = $data['archiveMode'] ?? ArchiveMode::MANUAL;
        $this->location = $data['location'] ?? null;
        $this->mediaMode = $data['mediaMode'] ?? MediaMode::RELAYED;
    }

    public function getArchiveMode(): string
    {
        return $this->archiveMode;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getMediaMode(): string
    {
        return $this->mediaMode;
    }

    public function setArchiveMode(string $archiveMode): self
    {
        $this->archiveMode = $archiveMode;
        return $this;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }
    
    public function setMediaMode(string $mediaMode): self
    {
        $this->mediaMode = $mediaMode;
        return $this;
    }
}