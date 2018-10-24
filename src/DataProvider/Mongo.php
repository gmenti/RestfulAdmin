<?php

namespace RestfulAdmin\DataProvider;

use Carbon\Carbon;
use MongoDB\BSON\ObjectId;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;
use RestfulAdmin\Entity\Entity;
use RestfulAdmin\Enum\ActionType;
use RestfulAdmin\Exception\NotFoundException;

class Mongo implements Contract
{
    /** @var string */
    protected $uri;

    /** @var string */
    protected $database;

    /**
     * Mongo constructor.
     *
     * @param string $uri
     * @param string $database
     */
    public function __construct($uri, $database)
    {
        $this->uri = $uri;
        $this->database = $database;
    }

    /**
     * Make connection, select db and get collection.
     *
     * @param string $resource
     * @return Collection
     */
    protected function collection($resource)
    {
        return (new Client($this->uri))
            ->selectDatabase($this->database)
            ->selectCollection($resource);
    }

    /**
     * @param  array|BSONDocument $result
     * @return array
     */
    protected function formatResult($result)
    {
        $formattedResult = !is_array($result) ? [$result] : $result;
        foreach ($formattedResult as $resultKey => $doc) {
            $data = get_object_vars($doc->jsonSerialize());
            foreach ($data as $key => $value) {
                if ($value instanceof ObjectId) {
                    $data[substr($key, 1)] = get_object_vars($value)['oid'];
                    unset($data[$key]);
                }
            }
            $formattedResult[$resultKey] = $data;
        }
        if (!is_array($result)) {
            return $formattedResult[0];
        }
        return $formattedResult;
    }


    /**
     * Execute mongodb query.
     *
     * @param  string $resource
     * @param  string $type
     * @param  array|Entity  $params
     * @return array
     *
     * @throws NotFoundException
     */
    public function execute($resource, $type, $params)
    {
        switch ($type)
        {
            case ActionType::GET_ONE:
                if (!isset($params['id'])) {
                    throw new NotFoundException();
                }

                $result = $this->collection($resource)
                    ->findOne(['_id' => new ObjectId($params['id'])]);

                if (!$result) {
                    throw new NotFoundException();
                }

                return $this->formatResult($result);

            case ActionType::GET_MANY:
                return $this->formatResult(
                    $this->collection($resource)
                        ->find()->toArray()
                );

            case ActionType::CREATE:
                if (!empty($params['id'])) {
                    unset($params['id']);
                }

                $params['_id'] = $this->collection($resource)
                    ->insertOne($params)
                    ->getInsertedId();

                return $this->formatResult(new BSONDocument($params));

            case ActionType::UPDATE:
                if (!isset($params['id'])) {
                    throw new NotFoundException();
                }

                $id = new ObjectId($params['id']);

                $data = $params;
                unset($data['id']);

                $result = $this->collection($resource)
                    ->replaceOne(['_id' => $id], $data);

                if ($result->getModifiedCount() !== 1) {
                    throw new NotFoundException();
                }

                $data['_id'] = $id;

                return $this->formatResult(new BSONDocument($data));

            case ActionType::DELETE:
                if (!isset($params['id']) || is_null($params['id'])) {
                    throw new NotFoundException();
                }

                $result = $this->collection($resource)
                        ->deleteOne(['_id' => new ObjectId($params['id'])]);

                if ($result->getDeletedCount() !== 1) {
                    throw new NotFoundException();
                }

                return null;
        }
    }
}