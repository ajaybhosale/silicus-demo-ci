<?php
/**
 * VERICHECK INC CONFIDENTIAL
 *
 * Vericheck Incorporated
 * All Rights Reserved.
 *
 * NOTICE:
 * All information contained herein is, and remains the property of
 * Vericheck Inc, if any.  The intellectual and technical concepts
 * contained herein are proprietary to Vericheck Inc and may be covered
 * by U.S. and Foreign Patents, patents in process, and are protected
 * by trade secret or copyright law. Dissemination of this information
 * or reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Vericheck Inc.
 *
 * @category QueryMapper
 * @package  QueryMapper
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use JohannesSchobel\DingoQueryMapper\Exceptions\EmptyColumnException;
use JohannesSchobel\DingoQueryMapper\Exceptions\UnknownColumnException;
use JohannesSchobel\DingoQueryMapper\Operators\CollectionOperator;
use JohannesSchobel\DingoQueryMapper\Parser\UriParser;

/**
 * Common QueryMapper class which covers all token management functions.
 * Class also verifies user identity for every user request and gives valid user response
 *
 * @name     QueryMapper
 * @category QueryMapper
 * @package  QueryMapper
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class QueryMapper
{

    /**
     * The model behind the QueryMapper
     *
     * @var Model
     */
    protected $model;

    /**
     * The builder used to create the query
     *
     * @var Builder
     */
    //protected $builder;

    /**
     * The uri parser to extract the query parameters
     *
     * @var UriParser
     */
    protected $uriParser;
    protected $wheres = [];
    // ok
    protected $sort = [];
    // ok
    protected $limit;
    // ok
    protected $page = 1;
    // ok
    protected $offset = 0;
    protected $columns = ['*'];
    protected $relationColumns = [];
    protected $rels = [];
    protected $groupBy = [];
    protected $excludedParameters = [];
    protected $query;
    // ok
    protected $result;
    // ok
    protected $operator;

    /**
     * QueryMapper constructor.
     *
     * @param Request $request the request with query parameters
     */
    public function __construct(Request $request)
    {
        $this->uriParser = new UriParser($request);
        $this->sort = config('dingoquerymapper.defaults.sort');
        $this->limit = config('dingoquerymapper.defaults.limit');
        $this->excludedParameters = array_merge($this->excludedParameters, config('dingoquerymapper.excludedParameters'));
    }

    /**
     * Function Create the Query from an existing builder
     *
     * @param object Builder $builder object
     *
     * @name   createFromBuilder
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function createFromBuilder(Builder $builder)
    {
        $this->model = $builder->getModel();
        $this->query = $builder;

        $this->build();

        return $this;
    }

    /**
     * Function Create the Query from an empty model
     *
     * @param Model $model object
     *
     * @name   createFromModel
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function createFromModel($model)
    {
        $this->model = $model;
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Function Create the Query from collection
     *
     * @param Collection $collection collection object
     *
     * @name   createFromCollection
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function createFromCollection(Collection $collection)
    {
        $this->operator = new CollectionOperator($collection);
        return $this;
    }

    /**
     * Function build the Query
     *
     * @name   build
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function build()
    {
        $this->prepare();
        if (config('dingoquerymapper.allowFilters')) {
            if ($this->_hasWheres()) {
                array_map([$this, '_addWhereToQuery'], $this->wheres);
            }
        }
        $this->checkHasLimit();
        $this->checkHasOffset();
        array_map([$this, '_addSortToQuery'], $this->sort);

        $this->query->with($this->rels);

        $this->query->select($this->columns);

        return $this;
    }

    /**
     * Function check query limit
     *
     * @name   checkHasLimit
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function checkHasLimit()
    {
        if ($this->_hasLimit()) {
            $this->query->take($this->limit);
        }
    }

    /**
     * Function check query offset
     *
     * @name   checkHasOffset
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function checkHasOffset()
    {
        if ($this->_hasOffset()) {
            $this->query->skip($this->offset);
        }
    }

    /**
     * Function gets the Query
     *
     * @name   get
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function get()
    {
        return $this->query->get();
    }

    /**
     * Function applies the pagination
     *
     * @name   paginate
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function paginate()
    {
        if (!$this->_hasLimit()) {
            throw new Exception("You can't use unlimited option for pagination", 1);
        }

        return $this->query->paginate($this->limit);
    }

    /**
     * Function lists the records
     *
     * @param array $value value to search
     * @param array $key   key to search
     *
     * @name   lists
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function lists($value, $key)
    {
        return $this->query->lists($value, $key);
    }

    /**
     * Function prepares the query string
     *
     * @name   prepare
     * @access protected
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    protected function prepare()
    {

        $this->_setWheres($this->uriParser->whereParameters());
        $constantParameters = $this->uriParser->predefinedParameters();
        array_map([$this, '_prepareConstant'], $constantParameters);

        if ($this->_hasRels() && $this->_hasRelationColumns()) {
            $this->_fixRelationColumns();
        }

        return $this;
    }

    /**
     * Function prepares the query with the given parameters
     *
     * @param array $parameter parameters array
     *
     * @name   _prepareConstant
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _prepareConstant($parameter)
    {
        if (!$this->uriParser->hasQueryParameter($parameter)) {
            return;
        }

        $callback = [$this, $this->_setterMethodName($parameter)];

        $callbackParameter = $this->uriParser->queryParameter($parameter);

        call_user_func($callback, $callbackParameter['value']);
    }

    /**
     * Function sets relations
     *
     * @param array $rels relations array
     *
     * @name   _setRels
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _setRels($rels)
    {
        $this->rels = array_filter(explode(',', $rels));
    }

    /**
     * Function sets page limit
     *
     * @param array $page page number
     *
     * @name   _setPage
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _setPage($page)
    {
        $this->page = (int) $page;

        $this->offset = ($page - 1) * $this->limit;
    }

    /**
     * Function sets columns from table
     *
     * @param array $columns column array
     *
     * @name   _setColumns
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _setColumns($columns)
    {
        $columns = array_filter(explode(',', $columns));

        $this->columns = $this->relationColumns = [];

        array_map([$this, '_setColumn'], $columns);

        if ($this->_hasColumns($columns) == 0) {
            throw new EmptyColumnException("Columns are empty");
        }
    }

    /**
     * Function sets column from table
     *
     * @param array $column column array
     *
     * @name   _setColumn
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _setColumn($column)
    {
        if ($this->_isRelationColumn($column)) {
            return $this->_appendRelationColumn($column);
        }

        if (!$this->_hasTableColumn($column)) {
            throw new UnknownColumnException("Unknown column '{$column}'");
        }

        $this->columns[] = $column;
    }

    /**
     * Function appends relation column from table
     *
     * @param array $keyAndColumn column array
     *
     * @name   _appendRelationColumn
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _appendRelationColumn($keyAndColumn)
    {
        list($key, $column) = explode('.', $keyAndColumn);

        $this->relationColumns[$key][] = $column;
    }

    /**
     * Function fix relation columns from table
     *
     * @name   _fixRelationColumns
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _fixRelationColumns()
    {
        $keys = array_keys($this->relationColumns);

        $callback = [$this, '_fixRelationColumn'];

        array_map($callback, $keys, $this->relationColumns);
    }

    /**
     * Function fix relation column from table
     *
     * @param array $key     key array
     * @param array $columns columns array
     *
     * @name   _fixRelationColumn
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _fixRelationColumn($key, $columns)
    {
        $index = array_search($key, $this->rels);

        unset($this->rels[$index]);

        $this->rels[$key] = $this->_closureRelationColumns($columns);
    }

    /**
     * Function closures relationship column from table
     *
     * @param array $columns columns array
     *
     * @name   _closureRelationColumns
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _closureRelationColumns($columns)
    {
        return function ($query) use ($columns) {
            $query->select($columns);
        };
    }

    /**
     * Function sets sorts column from table
     *
     * @param array $sort sorting array
     *
     * @name   _setSort
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _setSort($sort)
    {
        $this->sort = [];

        $orders = array_filter(explode(',', $sort));

        array_map([$this, '_appendSort'], $orders);
    }

    /**
     * Function appends sorted column from table
     *
     * @param array $sort sorting array
     *
     * @name   _appendSort
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _appendSort($sort)
    {
        $column = $sort;
        $direction = 'asc';

        if ($sort[0] == '-') {
            $column = substr($sort, 1);
            $direction = 'desc';
        }

        $this->sort[] = [
            'column' => $column,
            'direction' => $direction,
        ];
    }

    /**
     * Function sets group by query
     *
     * @param array $groups groups array
     *
     * @name   _setGroupBy
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _setGroupBy($groups)
    {
        $this->groupBy = array_filter(explode(',', $groups));
    }

    /**
     * Function sets limit to the query
     *
     * @param array $limit limit array
     *
     * @name   _setLimit
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _setLimit($limit)
    {
        $this->limit = (int) $limit;
    }

    /**
     * Function sets where parameters to the query
     *
     * @param array $parameters parameters array
     *
     * @name   _setWheres
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _setWheres($parameters)
    {
        $this->wheres = $parameters;
    }

    /**
     * Function adds where parameters to the query
     *
     * @param array $where where parameters array
     *
     * @name   _addWhereToQuery
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _addWhereToQuery($where)
    {
        extract($where);

        if ($this->_isExcludedParameter($key)) {
            return;
        }

        if ($this->_hasCustomFilter($key)) {
            return $this->_applyCustomFilter($key, $operator, $value);
        }

        $this->query->where($key, $operator, $value);
    }

    /**
     * Function adds sorting parameters to the query
     *
     * @param array $order order by string
     *
     * @name   _addSortToQuery
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _addSortToQuery($order)
    {
        extract($order);

        $this->query->orderBy($column, $direction);
    }

    /**
     * Function applies custom filters
     *
     * @param array $key      key array
     * @param array $operator operator string
     * @param array $value    value array
     *
     * @name   _applyCustomFilter
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    private function _applyCustomFilter($key, $operator, $value)
    {
        $callback = [$this, $this->_customFilterName($key)];

        $this->query = call_user_func($callback, $this->query, $value, $operator);
    }

    /**
     * Function applies custom filters
     *
     * @param array $column columns array
     *
     * @name   _isRelationColumn
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _isRelationColumn($column)
    {
        return (count(explode('.', $column)) > 1);
    }

    /**
     * Function checks excluded parameters
     *
     * @param array $key key array
     *
     * @name   _isExcludedParameter
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return boolean
     */
    private function _isExcludedParameter($key)
    {
        return in_array($key, $this->excludedParameters);
    }

    /**
     * Function checks where conditions present or not
     *
     * @name   _hasWheres
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _hasWheres()
    {
        return (count($this->wheres) > 0);
    }

    /**
     * Function checks relations
     *
     * @name   _hasRels
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _hasRels()
    {
        return (count($this->rels) > 0);
    }

    /**
     * Function checks group by condition
     *
     * @name   _hasGroupBy
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _hasGroupBy()
    {
        return (count($this->groupBy) > 0);
    }

    /**
     * Function checks limit condition
     *
     * @name   _hasLimit
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _hasLimit()
    {
        return ($this->limit);
    }

    /**
     * Function checks offset
     *
     * @name   _hasOffset
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _hasOffset()
    {
        return ($this->offset != 0);
    }

    /**
     * Function checks relation column condition
     *
     * @name   _hasRelationColumns
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _hasRelationColumns()
    {
        return (count($this->relationColumns) > 0);
    }

    /**
     * Function checks column present or not
     *
     * @param array $column column array
     *
     * @name   _hasTableColumn
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return string
     */
    private function _hasTableColumn($column)
    {
        return (Schema::hasColumn($this->model->getTable(), $column));
    }

    /**
     * Function checks custom filter
     *
     * @param array $key key array
     *
     * @name   _hasCustomFilter
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _hasCustomFilter($key)
    {
        $methodName = $this->_customFilterName($key);

        return (method_exists($this, $methodName));
    }

    /**
     * Function generates setter method name
     *
     * @param array $key key array
     *
     * @name   _setterMethodName
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return string
     */
    private function _setterMethodName($key)
    {
        return '_set' . studly_case($key);
    }

    /**
     * Function generates custom filter name
     *
     * @param array $key key array
     *
     * @name   _customFilterName
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return string
     */
    private function _customFilterName($key)
    {
        return 'filterBy' . studly_case($key);
    }

    /**
     * Function checks columns
     *
     * @param array $columns columns array
     *
     * @name   _hasColumns
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return string
     */
    private function _hasColumns($columns)
    {
        return (count($columns) > 0);
    }
}
