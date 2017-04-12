<?php

namespace App\Controller\Traits;

trait ToggleTrait
{

    public function setToggleoutcomeMessages($modelClass)
    {
        $this->toggleTraitMessages = [
            'enable' => [
               'success' => __('The {0} has been enabled.', $modelClass),
               'failure' => __('The {0} could not be enabled. Please, try again.', $modelClass)
            ],
            'disable' => [
               'success' => __('The {0} has been disabled.', $modelClass),
               'failure' => __('The {0} could not be deleted. Please, try again.', $modelClass)
            ],

        ];
    }

    public function setToggleModelClass()
    {
        $this->modelClass = explode(".", $this->modelClass);
        $this->modelClass = $this->modelClass[count($this->modelClass) - 1];
    }

    /**
     * Disable method
     * @param string $id
     * @throws \Exception
     * @return void Redirects
     */
    public function disable($id = null)
    {
        $this->setToggleModelClass();
        $this->setToggleoutcomeMessages($this->modelClass);

        $this->request->allowMethod(['post', 'put', 'delete']);
        if (empty($id)) {
            throw new \Exception(__('Id required'));
        }

        $conditions = [$this->{$this->modelClass}->primaryKey() => $id];
        $recordEntity = $this->{$this->modelClass}->find()
            ->where($conditions)
            ->first();
        $recordEntity = $this->{$this->modelClass}->patchEntity($recordEntity, ['active' => 0]);

        if ($this->{$this->modelClass}->save($recordEntity)) {

            $this->Flash->success($this->toggleTraitMessages['disable']['success']);
        } else {
            $this->Flash->error(
                $this->toggleTraitMessages['disable']['failure'],
                ['params' => ['errors' => $recordEntity->errors()]]
            );
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Enable method
     * @param string $id
     * @throws \Exception
     * @return void Redirects
     */
    public function enable($id = null)
    {
        $this->setToggleModelClass();
        $this->setToggleoutcomeMessages($this->modelClass);
        $this->request->allowMethod(['post', 'put', 'delete']);
        if (empty($id)) {
            throw new \Exception(__('Id required'));
        }
        $conditions = [$this->{$this->modelClass}->primaryKey() => $id];
        $recordEntity = $this->{$this->modelClass}->find()
            ->where($conditions)
            ->first();
        $recordEntity = $this->{$this->modelClass}->patchEntity($recordEntity, ['active' => 1]);

        if ($this->{$this->modelClass}->save($recordEntity)) {
            $this->Flash->success($this->toggleTraitMessages['enable']['success']);
        } else {
            $this->Flash->error(
                $this->toggleTraitMessages['disable']['failure'],
                ['params' => ['errors' => $recordEntity->errors()]]
            );
        }
        return $this->redirect(['action' => 'index']);
    }
}
