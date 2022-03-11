<?php

namespace DiscordMessageBuilder;

use DiscordMessageBuilder\Embed\Embed;

class Message extends Jsonable
{
    /** @var string */
    private $content;

    /** @var Embed */
    private $embed;

    public function __construct(array $attributes = null)
    {
        if (isset($attributes['content'])) {
            $this->setContent($attributes['content']);
        }

        if (isset($attributes['embed'])) {
            if ($attributes['embed'] instanceof Embed) {
                $this->setEmbed($attributes['embed']);
            } else {
                $this->setEmbed(new Embed($attributes['embed']));
            }
        }
    }

    /**
     * Set the content of a message.  Will override any roles you have mentioned
     */
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
        $this->content .= '<@&'.$roleId.'>';
    }

    public function isMentioned(int $roleId): bool
    {
        return strpos($this->content, '<@&'.$roleId.'>') !== false;
    }

    public function setEmbed(Embed $embed)
    {
        $this->embed = $embed;
    }

    public function embed() : Embed
    {
        return $this->embed;
    }

    public function hasEmbed() : bool
    {
        return $this->embed != null;
    }

    public function jsonSerialize()
    {
        $jsonData = [
            'content' => $this->content(),
        ];

        if ($this->embed != null) {
            $jsonData['embed'] = $this->embed->jsonSerialize();
        }

        return $jsonData;
    }
}
