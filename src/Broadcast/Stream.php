<?php
declare(strict_types=1);

namespace Vonage\Video\Broadcast;

class Stream
{
    protected ?string $id = null;

    protected string $url;

    protected string $name;

    public function __construct(string $name, string $url, ?string $id = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function toArray()
    {
        $data =  [
            'name' => $this->getName(),
            'url' => $this->getUrl(),
        ];

        if ($this->getId()) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}