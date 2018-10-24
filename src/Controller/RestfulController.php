<?php

namespace RestfulAdmin\Controller;

use RestfulAdmin\Entity\Entity;
use RestfulAdmin\Enum\ActionType;
use RestfulAdmin\Exception\ValidationException;

class RestfulController extends Controller
{
    /**
     * Get many records.
     *
     * @param  \Klein\Request  $req
     * @param  \Klein\Response $res
     * @return Entity[]
     */
    public function index($req, $res)
    {
        $records = $this->dataProvider->execute(
            $this->module->getName(),
            ActionType::GET_MANY,
            []
        );

        foreach ($records as $key => $record) {
            $record[$key] = $this->entity($record);
        }

        return $records;
    }

    /**
     * Get record by id.
     *
     * @param  \Klein\Request  $req
     * @param  \Klein\Response $res
     * @return Entity
     */
    public function find($req, $res)
    {
        $record = $this->dataProvider->execute(
            $this->module->getName(),
            ActionType::GET_ONE,
            [
                'id' => $req->param('id')
            ]
        );

        return $this->entity($record);
    }

    /**
     * Create a new record.
     *
     * @param \Klein\Request $req
     * @param \Klein\Response $res
     * @return Entity
     */
    public function create($req, $res)
    {
        $entity = $this->entity($this->jsonBody($req));

        $record = $this->dataProvider->execute(
            $this->module->getName(),
            ActionType::CREATE,
            $entity->jsonSerialize()
        );

        return $this->entity($record);
    }

    /**
     * Update a existing record.
     *
     * @param \Klein\Request $req
     * @param \Klein\Response $res
     * @return Entity
     *
     * @throws ValidationException
     */
    public function update($req, $res)
    {
        $entity = $this->find($req, $res);
        $entity->fill($this->jsonBody($req));

        $record = $this->dataProvider->execute(
            $this->module->getName(),
            ActionType::UPDATE,
            $entity->jsonSerialize()
        );

        return $this->entity($record);
    }

    /**
     * Delete a existing record.
     *
     * @param \Klein\Request $req
     * @param \Klein\Response $res
     * @return null
     */
    public function delete($req, $res)
    {
        return $this->dataProvider->execute(
            $this->module->getName(),
            ActionType::DELETE,
            [
                'id' => $req->param('id')
            ]
        );
    }
}