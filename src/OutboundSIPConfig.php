<?php
declare(strict_types=1);

namespace Vonage\Video;

class OutboundSIPConfig
{
    protected string $uri;

    protected ?string $from = null;

    protected array $headers = [];

    protected ?string $username = null;

    protected ?string $password = null;

    protected bool $secure = false;

    protected bool $video = false;

    protected bool $observeForceMute = false;

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function isSecure(): bool
    {
        return $this->isSecure();
    }

    public function includeVideo(): bool
    {
        return $this->video;
    }

    public function observesForceMute(): bool
    {
        return $this->observeForceMute;
    }

    public function setFrom(string $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Sets the header to send in the SIP connection
     * If a header value is null, this will remove the header from being sent.
     *
     * @return OutboundSIPConfig 
     */
    public function setHeader(string $key, ?string $value): self
    {
        $this->headers[$key] = $value;
        if ($this->headers[$key] === null) {
            unset($this->headers[$key]);
        }
        return $this;   
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setIsSecure(bool $secure): self
    {
        $this->secure = $secure;
        return $this;
    }

    public function setIncludeVideo(bool $include): self
    {
        $this->video = $include;
        return $this;
    }

    public function setObserveForceMute(bool $observe): self
    {
        $this->observeForceMute = $observe;
        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'sip' => [
                'uri' => $this->getUri(),
                'secure' => $this->isSecure(),
                'video' => $this->includeVideo(),
                'observeForceMute' => $this->observesForceMute(),
            ],
        ];

        if ($this->getFrom()) {
            $data['sip']['from'] = $this->getFrom();
        }

        if (!empty($this->getHeaders())) {
            $data['sip']['headers'] = $this->getHeaders();
        }

        if ($this->getUsername()) {
            $data['sip']['auth']['username'] = $this->getUsername();
        }

        if ($this->getPassword()) {
            $data['sip']['auth']['password'] = $this->getPassword();
        }

        return $data;
    }
}