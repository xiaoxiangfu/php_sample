<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Sprinkle\Admin\Sprunje;

use Illuminate\Database\Capsule\Manager as Capsule;
use UserFrosting\Sprinkle\Core\Facades\Debug;
use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;

/**
 * PermissionSprunje
 *
 * Implements Sprunje for the permissions API.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class PermissionSprunje extends Sprunje
{
    protected $name = 'permissions';

    protected $sortable = [
        'name',
        'properties'
    ];

    protected $filterable = [
        'name',
        'properties',
        'info'
    ];

    protected $excludeForAll = [
        'info'
    ];

    /**
     * {@inheritDoc}
     */
    protected function baseQuery()
    {
        return $this->classMapper->createInstance('permission')->newQuery();
    }

    /**
     * Filter LIKE the slug, conditions, or description.
     *
     * @param Builder $query
     * @param mixed $value
     * @return $this
     */
    protected function filterInfo($query, $value)
    {
        return $this->filterProperties($query, $value);
    }

    /**
     * Filter LIKE the slug, conditions, or description.
     *
     * @param Builder $query
     * @param mixed $value
     * @return $this
     */
    protected function filterProperties($query, $value)
    {
        // Split value on separator for OR queries
        $values = explode($this->orSeparator, $value);
        $query->where(function ($query) use ($values) {
            foreach ($values as $value) {
                $query->orLike('slug', $value)
                        ->orLike('conditions', $value)
                        ->orLike('description', $value);
            }
        });
        return $this;
    }

    /**
     * Sort based on slug.
     *
     * @param Builder $query
     * @param string $direction
     * @return $this
     */
    protected function sortProperties($query, $direction)
    {
        $query->orderBy('slug', $direction);
        return $this;
    }
}
