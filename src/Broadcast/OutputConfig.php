<?php
declare(strict_types=1);

namespace Vonage\Video\Broadcast;

class OutputConfig
{
    protected bool $hlsDVREnabled = false;

    protected bool $hlsLowLatencyModeEnabled = false;

    /**
     * @var Stream[]
     */
    protected array $rtmpStreams = [];

    public function addRTMPStream(Stream $stream): static
    {
        if (count($this->rtmpStreams) === 5) {
            throw new \RuntimeException('Only 5 RTMP streams are supported');
        }

        $this->rtmpStreams[] = $stream;
        return $this;
    }

    public function disableHLSDVR()
    {
        $this->hlsDVREnabled = false;
    }

    public function disableHLSLowLatencyMode()
    {
        $this->hlsLowLatencyModeEnabled = false;
    }

    public function enableHLSDVR()
    {
        if ($this->isHLSLowLatencyModeEnabled()) {
            throw new \RuntimeException('Cannot enable both Low Latency and DVR');
        }

        $this->hlsDVREnabled = true;
    }

    public function enableHLSLowLatencyMode()
    {
        if ($this->isHLSDVREnabled()) {
            throw new \RuntimeException('Cannot enable both Low Latency and DVR');
        }

        $this->hlsLowLatencyModeEnabled = true;
    }

    public function isHLSDVREnabled(): bool
    {
        return (bool) $this->hlsDVREnabled;
    }

    public function isHLSLowLatencyModeEnabled(): bool
    {
        return (bool) $this->hlsLowLatencyModeEnabled;
    }

    /**
     * @return Stream[]
     */
    public function getRTMPStreams(): array
    {
        return $this->rtmpStreams;
    }

    /**
     * @var Stream[] $streams
     */
    public function setRTMPStreams(array $streams)
    {
        $this->rtmpStreams = [];
        foreach ($streams as $stream) {
            $this->addRTMPStream($stream);
        }

        return $this;
    }

    /**
     * @return array{hls: {lowLatency: bool, dvr: bool}, rtmp: {url: string, name: string, id?: string}}
     */
    public function toArray(): array
    {
        $data = [
            'hls' => [
                'lowLatency' => $this->isHLSLowLatencyModeEnabled(),
                'dvr' => $this->isHLSDVREnabled(),
            ],
            'rtmp' => []
        ];

        foreach ($this->getRTMPStreams() as $stream)
        {
            $data['rtmp'][] = $stream->toArray();
        }

        return $data;
    }
}