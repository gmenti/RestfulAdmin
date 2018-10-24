<?php

namespace RestfulAdmin\Exception;

class ValidationException extends \Exception
{
    /** @var array */
    protected $data;

    public function __construct($data, $code = 422)
    {
        parent::__construct('Validation failed', $code);
        $this->setData($data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}