<?php

namespace One23\LaravelSoftDeletesBoolean;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Date;

class SoftDeletingBooleanScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var string[]
     */
    protected $extensions = ['Restore', 'RestoreOrCreate', 'WithTrashed', 'WithoutTrashed', 'OnlyTrashed'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where([
            $model->getQualifiedIsDeletedColumn() => 0,
        ]);
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function(Builder $builder) {
            $column = $this->getIsDeletedColumn($builder);
            $columnAt = $this->getDeletedAtColumn($builder);

            $fields = [
                $column => 1,
            ];

            if ($columnAt) {
                $fields[$columnAt] = Date::now();
            }

            return $builder->update($fields);
        });
    }

    /**
     * Get the "deleted" column for the builder.
     *
     * @return string
     */
    protected function getIsDeletedColumn(Builder $builder)
    {
        if (count((array)$builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedIsDeletedColumn();
        }

        return $builder->getModel()->getIsDeletedColumn();
    }

    protected function getDeletedAtColumn(Builder $builder)
    {
        if (count((array)$builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedDeletedAtColumn();
        }

        return $builder->getModel()->getDeletedAtColumn();
    }

    /**
     * Add the restore extension to the builder.
     *
     * @return void
     */
    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function(Builder $builder) {
            $builder->withTrashed();

            $fields = [
                $builder->getModel()->getIsDeletedColumn() => 0,
            ];

            $columnAt = $builder->getModel()->getDeletedAtColumn();
            if ($columnAt) {
                $fields[$columnAt] = null;
            }

            return $builder->update($fields);
        });
    }

    /**
     * Add the restore-or-create extension to the builder.
     *
     * @return void
     */
    protected function addRestoreOrCreate(Builder $builder)
    {
        $builder->macro('restoreOrCreate', function(Builder $builder, array $attributes = [], array $values = []) {
            $builder->withTrashed();

            return tap($builder->firstOrCreate($attributes, $values), function($instance) {
                $instance->restore();
            });
        });
    }

    /**
     * Add the with-trashed extension to the builder.
     *
     * @return void
     */
    protected function addWithTrashed(Builder $builder)
    {
        $builder->macro('withTrashed', function(Builder $builder, $withTrashed = true) {
            if (! $withTrashed) {
                return $builder->withoutTrashed();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the without-trashed extension to the builder.
     *
     * @return void
     */
    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function(Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where([
                $model->getQualifiedIsDeletedColumn() => 0,
            ]);

            return $builder;
        });
    }

    /**
     * Add the only-trashed extension to the builder.
     *
     * @return void
     */
    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function(Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where([
                $model->getQualifiedIsDeletedColumn() => 1,
            ]);

            return $builder;
        });
    }
}
