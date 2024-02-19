<?php

namespace DCore\Services;

use DCore\DTO\BaseDTO;
use DCore\Exceptions\RepositoryException;
use DCore\Repositories\BaseRepository;
use Hyperf\Di\Exception\NotFoundException;

class BaseService
{

    /** @var string|null */
    protected ?string $primaryKey = null;

    /** @var array */
    protected array $uses = [];

    /**
     * @param  BaseRepository|null  $repository
     * @param  BaseDTO|null  $baseDTO
     * @throws NotFoundException
     * @throws RepositoryException
     */
    public function __construct(protected ?BaseRepository $repository, protected ?BaseDTO $baseDTO = null)
    {
        $this->primaryKey = $this->repository->makeModel()->getKeyName();
    }
}
