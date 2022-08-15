<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function call_user_func_array;
use function explode;
use function method_exists;
use function property_exists;
use function strpos;
use function trim;

/**
 * @codeCoverageIgnore
 *
 * Searchable.php
 */
trait Searchable
{
    public function scopeSearch(Builder $builder, string $search): Builder
    {
        $words = '%' . $this->normalize($search) . '%';

        $builder->where(fn (Builder $query) => $query = $this->queryInColumns($query, $words));

        return $builder;
    }

    protected function normalize(string $search): string
    {
        return mb_strtolower(trim($search));
    }

    protected function queryInColumns(Builder $builder, string $word): Builder
    {
        foreach ($this->getColumns() as $column) {
            $builder = $this->queryInColumn($builder, $column, $word);
        }

        return $builder;
    }

    public function queryInColumn(Builder $builder, string $column, string $word): Builder
    {
        Assert::stringNotEmpty($column, 'Invalid column name');

        $method = 'search' . Str::studly($column);

        switch (true) {
            case method_exists($this, $method):
                $this->queryFromCustomSearch($builder, [$this, $method], $word);

                break;

            case strpos($column, '.') !== false:
                [$relation, $relationColumn] = explode('.', $column);

                $this->queryFromRelationship($builder, $relation, $relationColumn, $word);

                break;

            default:
                $table = $builder->getQuery()->from;

                $builder->orWhere($table . '.' . $column, $this->comparatorKeyword(), $word);

                break;
        }

        return $builder;
    }

    /**
     * @return string[]
     */
    protected function getColumns(): array
    {
        if (!property_exists($this, 'searchable')) {
            return [];
        }

        return $this->searchable;
    }

    /**
     * @param callable|array{0:self,1:string} $callable
     */
    protected function queryFromCustomSearch(
        Builder $builder,
        $callable,
        string $word
    ): Builder {
        Assert::isCallable($callable);

        $builder->orWhere(
            fn (Builder $query) => call_user_func_array($callable, [$query, $word])
        );

        return $builder;
    }

    protected function queryFromRelationship(
        Builder $builder,
        string $relation,
        string $column,
        string $word
    ): Builder {
        $compare = $this->comparatorKeyword();

        $builder->orWhereHas($relation, fn ($query) => $query->where($column, $compare, $word));

        return $builder;
    }

    /**
     * Returns database driver Ex: mysql, pgsql, sqlite.
     */
    protected function getDatabaseDriver(): string
    {
        $key = $this->connection ?: Config::get('database.default');

        return Config::get('database.connections.' . $key . '.driver');
    }

    protected function comparatorKeyword(): string
    {
        return $this->getDatabaseDriver() === 'pgsql' ? 'ILIKE' : 'LIKE';
    }
}