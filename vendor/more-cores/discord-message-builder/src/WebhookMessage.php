<?php

namespace DiscordMessageBuilder;

use DiscordMessageBuilder\Embed\Embed;

class WebhookMessage extends Jsonable
{
    /** @var string */
    protected $content;

    /** @var string */
    protected $fileContents;

    /** @var Embed[] */
    protected $embeds;

    public function __construct(array $attributes = null)
    {
        if (isset($attributes['content'])) {
            $this->setContent($attributes['content']);
        }

        if (isset($attributes['file'])) {
            $this->setFile($attributes['file']);
        }

        if (isset($attributes['embeds'])) {
            foreach ($attributes['embeds'] as $embed) {
                if ($embed instanceof Embed) {
                    $this->addEmbed($embed);
                } else {
                    $this->addEmbed(new Embed($embed));
                }
            }
        }
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function content() : string
    {
        return (string) $this->content;
    }

    public function hasContent() : bool
    {
        return $this->content != null;
    }

    /**
     * Determine if the given message has mentions
     */
    public function hasMentions() : bool
    {
        return preg_match('#\<@\&.+?\>#', $this->content());
    }

    public function getMentionedRoleIds(): ?array
    {
        preg_match_all('#\<@\&(.+?)\>#', $this->content(), $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * Mention the given role in the included message
     */
    public function mention(int $roleId)
    {
        $this->content .= '<@&'.$roleId.'> ';
    }

    public function isMentioned(int $roleId): bool
    {
        return strpos($this->content, '<@&'.$roleId.'>') !== false;
    }

    public function setFile(string $fileContents)
    {
        $this->fileContents = $fileContents;
    }

    public function file() : string
    {
        return (string) $this->fileContents;
    }

    public function hasFile() : bool
    {
        return $this->fileContents != null;
    }

    public function addEmbed(Embed $embed)
    {
        $this->embeds[] = $embed;
    }

    public function setEmbeds(array $embeds)
    {
        $this->embeds[] = $embeds;
    }

    public function embeds() : array
    {
        return (array) $this->embeds;
    }

    public function hasEmbeds() : bool
    {
        return $this->embeds != null;
    }

    public function jsonSerialize()
    {
        $jsonData = [
            'content' => $this->content(),
        ];

        if ($this->hasFile()) {
            $jsonData['file'] = $this->file();
        }

        if ($this->embeds != null && count($this->embeds) > 0) {
            $jsonData['embeds'] = [];

            foreach ($this->embeds as $embed) {
                $jsonData['embeds'][] = $embed->jsonSerialize();
            }
        }

        return $jsonData;
    }
}
