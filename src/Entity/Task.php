<?php

namespace RestfulAdmin\Entity;

use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Length;
use Respect\Validation\Rules\NotBlank;
use Respect\Validation\Rules\NotOptional;
use Respect\Validation\Rules\StringType;

class Task extends Entity
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $description;

    /** @var string */
    protected $parentId;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     *
     * @throws \Exception
     */
    public function setTitle($title)
    {
        (new AllOf(
            new NotOptional(),
            new StringType(),
            new Length(3, 100)
        ))->assert($title);

        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     *
     * @throws \Exception
     */
    public function setDescription($description)
    {
        (new AllOf(
            new StringType(),
            new Length(0, 1000)
        ))->assert($description);

        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     * @return $this
     */
    public function setParentId($parentId)
    {
        (new AllOf(
            new NotOptional(),
            new StringType(),
            new NotBlank()
        ))->assert($parentId);

        $this->parentId = $parentId;

        return $this;
    }
}