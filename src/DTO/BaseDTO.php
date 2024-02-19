<?php
declare(strict_types=1);

namespace DCore\DTO;

use ArrayAccess;
use DCore\Http\Component\Request;
use Hyperf\Contract\Arrayable;
use Hyperf\Contract\Jsonable;
use Hyperf\Database\Model\Concerns\HasAttributes;
use Hyperf\Database\Model\Model;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Stringable\Str;
use Hyperf\Tappable\HigherOrderTapProxy;
use JsonException;
use JsonSerializable;

abstract class BaseDTO implements Jsonable, Arrayable, ArrayAccess, JsonSerializable
{
    use HasAttributes;


    /**
     * 默认接收参数
     * @var array
     */
    protected array $commonFields = ['id', 'keyword', 'page', 'limit'];

    /**
     * 接受的字段
     * @var array
     */
    protected array $accessFields = [];

    /**
     * 过滤验证接口
     * @var array
     */
    protected array $skipValidator = [];

    /**
     * 是否验证
     * @var bool
     */
    protected bool $isValidator = true;

    /**
     * @var Model
     */
    protected Model $model;

    /**
     * 是否鉴权
     * @var bool
     */
    protected bool $auth = false;

    /**
     * @var mixed
     */
    private array $relations;

    /**
     * Specify Model class name
     * @return string
     */
    abstract public function model(): string;

    public function __construct(protected Request $request)
    {
        $this->init();
    }

    /**
     * 获取可用字段
     *
     * @return array
     */
    public function getFillFields(): array
    {
        return $this->model->getFillable();
    }

    public function getAccessFields(): array
    {
        $action = $this->getAction();


        return [];
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return HigherOrderTapProxy|mixed|null
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param $value
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset(string $key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @return string
     * @throws JsonException
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return !is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param $offset
     * @return HigherOrderTapProxy|mixed|null
     */
    public function offsetGet($offset): mixed
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param $offset
     * @param $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset], $this->relations[$offset]);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setAttribute($key, $value): mixed
    {
        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the model, such as "json_encoding" an listing of data for storage.
        if ($this->hasSetMutator($key)) {
            return $this->setMutatedAttributeValue($key, $value);
        }

        // If an attribute is listed as a "date", we'll convert it from a DateTime
        // instance into a form proper for storage on the database tables using
        // the connection grammar's date format. We will auto set the values.
        if ($value && $this->isDateAttribute($key)) {
            $value = $this->fromDateTime($value);
        }

        if ($this->isClassCastable($key)) {
            $this->setClassCastableAttribute($key, $value);

            return $this;
        }

        if (!is_null($value) && $this->isJsonCastable($key)) {
            $value = $this->castAttributeAsJson($key, $value);
        }

        // If this attribute contains a JSON ->, we'll set the proper value in the
        // attribute's underlying array. This takes care of properly nesting an
        // attribute in the array's value in the case of deeply nested items.
        if (Str::contains($key, '->')) {
            return $this->fillJsonAttribute($key, $value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getAttributes();
    }

    /**
     * @param  int  $options
     * @return string
     * @throws JsonException
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR | $options);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    protected function init()
    {
        $this->attributes = [];
        $this->initData($this->request->all());
    }

    protected function initData(array $data): void
    {

    }

    protected function getAction(): void
    {
        $route = $this->request->getAttribute(Dispatched::class);
        var_dump($route);
    }
}
