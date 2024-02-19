<?php

namespace DCore\Repositories;

use DCore\Exceptions\RepositoryException;
use Hyperf\Di\Container;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Di\Exception\NotFoundException;

abstract class BaseRepository
{
    protected Model $model;


    public function __construct(protected Container $app)
    {
        $this->boot();
    }

    public function boot(): void
    {

    }

    abstract public function model():string;


    /**
     * Returns the current Model instance
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @throws RepositoryException|NotFoundException
     */
    public function resetModel(): void
    {
        $this->makeModel();
    }

    /**
     * @return Model
     * @throws RepositoryException|NotFoundException
     */
    public function makeModel(): Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of hyperf\\DbConnection\\Model\\Model");
        }

        return $this->model = $model;
    }

}
