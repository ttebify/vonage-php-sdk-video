<?php

namespace Vonage\Video\Archive;

class ArchiveLayout implements \JsonSerializable
{
    public const LAYOUT_BESTFIT = 'bestFit';
    public const LAYOUT_CUSTOM = 'custom';
    public const LAYOUT_HORIZONTAL = 'horizontalPresentation';
    public const LAYOUT_PIP = 'pip';
    public const LAYOUT_VERTICAL = 'verticalPresentation';

    /**
     * Type of layout that we are sending
     * @ignore
     * */
    private string $type;

    /**
     * Type of layout to use for screen sharing
     * @ignore
     */
    private string $screenshareType;

    /**
     * Custom stylesheet if our type is 'custom'
     * @ignore
     */
    private ?string $stylesheet;

    /** @ignore */
    private function __construct(string $type, ?string $stylesheet = null)
    {
        $this->type = $type;
        $this->stylesheet = $stylesheet;
    }

    /**
     * Returns a Layout object defining a custom layout type.
     */
    public static function createCustom(string $stylesheet): self
    {
        return new ArchiveLayout(static::LAYOUT_CUSTOM, $stylesheet);
    }

    /** @ignore */
    public static function fromData(array $layoutData): self
    {
        if (array_key_exists('stylesheet', $layoutData)) {
            return new ArchiveLayout($layoutData['type'], $layoutData['stylesheet']);
        }

        return new ArchiveLayout($layoutData['type']);
    }

    /**
     * Returns a Layout object defining the "best fit" predefined layout type.
     */
    public static function getBestFit(): self
    {
        return new ArchiveLayout(static::LAYOUT_BESTFIT);
    }

    /**
     * Returns a Layout object defining the "picture-in-picture" predefined layout type.
     */
    public static function getPIP(): self
    {
        return new ArchiveLayout(static::LAYOUT_PIP);
    }

    /**
     * Returns a Layout object defining the "vertical presentation" predefined layout type.
     */
    public static function getVerticalPresentation(): self
    {
        return new ArchiveLayout(static::LAYOUT_VERTICAL);
    }

    /**
     * Returns a Layout object defining the "horizontal presentation" predefined layout type.
     */
    public static function getHorizontalPresentation(): self
    {
        return new ArchiveLayout(static::LAYOUT_HORIZONTAL);
    }

    public function setScreenshareType(string $screenshareType): self
    {
        if ($this->type === ArchiveLayout::LAYOUT_BESTFIT) {
            $layouts = [
                ArchiveLayout::LAYOUT_BESTFIT,
                ArchiveLayout::LAYOUT_HORIZONTAL,
                ArchiveLayout::LAYOUT_PIP,
                ArchiveLayout::LAYOUT_VERTICAL
            ];

            if (!in_array($screenshareType, $layouts)) {
                throw new \RuntimeException('Screenshare type must be of a valid layout type');
            }

            $this->screenshareType = $screenshareType;
            return $this;
        }

        throw new \RuntimeException('Screenshare type cannot be set on a layout type other than bestFit');
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Return a json-encoded string representation of the layout
     */
    public function toJson(): string
    {
        return json_encode($this->jsonSerialize());
    }

    public function toArray(): array
    {
        $data = array(
            'type' => $this->type
        );

        // omit 'stylesheet' property unless it is explicitly defined
        if (isset($this->stylesheet)) {
            $data['stylesheet'] = $this->stylesheet;
        }

        // omit 'screenshareType' property unless it is explicitly defined
        if (isset($this->screenshareType)) {
            $data['screenshareType'] = $this->screenshareType;
        }

        return $data;
    }
}
