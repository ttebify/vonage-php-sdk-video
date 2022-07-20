<?php
declare(strict_types=1);

namespace Vonage\Video;

class Stream
{
    protected string $id;
    protected string $videoType;
    protected string $name;
    
    /**
     * @var string[]
     */
    protected array $layoutClassList = [];

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->videoType = $data['videoType'];
        $this->name = $data['name'];
        $this->layoutClassList = $data['layoutClassList'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getVideoType(): string
    {
        return $this->videoType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLayoutClassList(): array
    {
        return $this->layoutClassList;
    }
}