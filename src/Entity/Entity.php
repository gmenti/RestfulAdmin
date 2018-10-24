<?php

namespace RestfulAdmin\Entity;

use Carbon\Carbon;
use Respect\Validation\Rules\Date;
use Respect\Validation\Validator;
use RestfulAdmin\Exception\ValidationException;

class Entity implements \JsonSerializable
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $createdAt;

    /** @var string */
    protected $updatedAt;

    /**
     * Entity constructor.
     *
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct($data)
    {
        $this->fill($data, true);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        if ($id) {
            Validator::stringType()
                ->notBlank()
                ->assert($id);
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string|int|null $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = new Carbon($createdAt);
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = new Carbon($updatedAt);
        return $this;
    }

    /**
     * @param array $data
     * @param bool? $set
     * @throws ValidationException
     */
    public function fill($data, $set = false)
    {
        $errors = [];
        $toIterate = $set ? $this : $data;
        if (!$set) {
            $this->setUpdatedAt('now');
        }
        foreach ($toIterate as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (!method_exists($this, $method)) {
                continue;
            }
            $methods[] = $method;
            try {
                call_user_func([$this, $method], isset($data[$key]) ? $data[$key] : null);
            } catch (\Exception $exception) {
                $errors[$key] = $exception->getMessage();
            }
        }
        if (count($errors)) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key => $value) {
            $method = 'get' . ucfirst($key);
            if (!method_exists($this, $method)) {
                continue;
            }
            $value = call_user_func([$this, $method]);
            if ($value instanceof Carbon) {
                $value = $value->toISOString();
            }
            $data[$key] = $value;
        }
        return $data;
    }
}