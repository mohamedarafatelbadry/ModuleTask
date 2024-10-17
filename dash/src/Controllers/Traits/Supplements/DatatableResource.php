<?php
namespace Dash\Controllers\Traits\Supplements;

trait DatatableResource
{
    protected $registerForeignKeyNames       = [];
    protected $resourcesRelatedWithRelations = [];

    /**
     * Register ForeginKey In List To Use In Resources
     * to help  resourcesRelatedWithRelations method to make a url
     * @return void
     */
    public function registerForeignKeyNames($q, $resource)
    {

        // $q->getForeignKeyName() // like user_id
        // q->getParent() // for the same table query like article
        // $q->getRelated() // for the other table like category
        // $q->getQualifiedParentKeyName() "articles.id"
        // $q->getQualifiedRelatedKeyName() "categories.id"
        $type = '';
        foreach ($this->fields() as $fetchField) {
            if (in_array($fetchField['type'], $this->relationTypes)) {
                $attribute = explode('.', $fetchField['attribute']);
                if (!empty($attribute) && count($attribute) > 0) {

                    if ($attribute[0] == $resource) {
                        $type = $fetchField['type'];
                    }
                }
            }
        }

        if (in_array($type, $this->relationTypes)) {
            if (method_exists($q, 'getForeignKeyName')) {
                $keyRelation  = $q->getForeignKeyName();
                $relationType = $type;
            } elseif (method_exists($q, 'getQualifiedParentKeyName')) {
                $keyRelation  = $q->getQualifiedParentKeyName();
                $relationType = $type;
            } elseif (method_exists($q, 'getQualifiedRelatedKeyName')) {
                $keyRelation  = $q->getQualifiedRelatedKeyName();
                $relationType = $type;
            }
            //articles_categories
            if (isset($keyRelation)) {
                //dd($resource,$keyRelation);
                $this->registerForeignKeyNames[$resource] = [
                    $resource      => $keyRelation,
                    'relationType' => $relationType,
                ];
            }
        }
    }

    /**
     * this can make a url by forgien key (parent,related,foreignkey)
     * to append list in $this->resourcesRelatedWithRelations propery
     * @return void
     */
    public function resourcesRelatedWithRelations()
    {
        foreach ($this->resource['fields'][0]::$input as $input) {
            if (isset($input['resource'])) {
                $resourceName = resourceShortName($input['resource']);
                //dd($resourceName , request('loadByResourceRelation.main_resource'));
                // if($resourceName == request('loadByResourceRelation.main_resource')){
                // 	return false;
                // }

                $attribute = explode('.', $input['attribute'])[0];
                $foregin   = $this->registerForeignKeyNames[$attribute]??null;

                $url = '';
                // check url scope by relationship
                if (in_array($input['type'], $this->relationOneTypes)) {
                    $url = url(app()['dash']['DASHBOARD_PATH'].'/resource/'.$resourceName).'/';
                } elseif (!empty($foregin)) {

                    if ($foregin['relationType'] == 'belongsToMany') {
                        $manyType = 'loadByResourceToMany';
                    } elseif ($foregin['relationType'] == 'belongsTo') {
                        $manyType = 'loadByResourcebelongsTo';
                    } elseif ($foregin['relationType'] == 'hasOneThrough') {
                        $manyType = 'loadByResourcehasOneThrough';
                    } elseif ($foregin['relationType'] == 'hasOne') {
                        $manyType = 'loadByResourcehasOne';
                    } elseif ($foregin['relationType'] == 'hasManyThrough') {
                        $manyType = 'loadByResourcehasManyThrough';
                    } elseif ($foregin['relationType'] == 'hasMany') {
                        $manyType = 'loadByResourcehasMany';
                    } elseif ($foregin['relationType'] == 'morphTo') {
                        $manyType = 'loadByResourcemorphTo';
                    } elseif ($foregin['relationType'] == 'morphMany') {
                        $manyType = 'loadByResourcemorphMany';
                    } elseif ($foregin['relationType'] == 'morphToMany') {
                        $manyType = 'loadByResourcemorphToMany';
                    } elseif ($foregin['relationType'] == 'morphedByMany') {
                        $manyType = 'loadByResourcemorphedByMany';
                    } else {
                        $manyType = 'loadByResourcehasMany';
                    }

                    $url = url(app()['dash']['DASHBOARD_PATH'].'/resource/'.$resourceName.'?'.$manyType.'['.array_values($foregin)[0].']=');
                }


                if (isset($foregin)) {
                    $this->resourcesRelatedWithRelations[$attribute] = [
                        'getForeignKeyName' => $foregin,
                        'resourceName'      => $resourceName,
                        'url'               => $url,
                    ];
                }
            }

        }
    }

    /**
     * searchable can make serach with main column from table
     * @return query to renderable with datatable query
     */
    public function searchable($q)
    {
        $i = 0;
        if (!empty(request('search.value')) && count($this->search) > 1) {
            foreach ($this->search as $search) {
                $q = $q->orWhere($search, 'LIKE', '%'.request('search.value').'%');
                $i++;
            }

        }
        return $q;
    }

    /**
     * the method can handel the orderable request
     * 				from datatable server side
     * @return $query to continue handling with datatable
     */
    public function orderable($q)
    {
        if (request()->has('order.0.column')) {

            $multiSelectRecord = $this->resource['multiSelectRecord']?1:0;
            if (isset($this->search[request('order.0.column')-$multiSelectRecord])) {
                $column = $this->search[request('order.0.column')-$multiSelectRecord];
                return $q->orderBy($column, request('order.0.dir'));
            }

        }
        return $q;
    }

    /**
     * search with Relationship to related model and metho
     * @return $query \Model
     */
    public function searchWithRelation($table)
    {

        $searchValue = !is_null(request('search.value'))?trim(request('search.value')):'';
        if (!empty($searchValue) && !empty($this->searchWithRelation) && count($this->searchWithRelation) > 0) {
            $index = 0;
            foreach ($this->searchWithRelation as $key => $value) {
                if(!empty($value)) {
                    if($index == 0) {
                        $table->whereHas($key, function ($table) use ($value, $key, $searchValue) {
                            $i = 0;
                            foreach ($value as $col) {
                                if($i == 0) {
                                    $table = $table->where($col, 'LIKE', '%'.$searchValue.'%');
                                } else {
                                    $table = $table->orWhere($col, 'LIKE', '%'.$searchValue.'%');
                                }
                                $i++;
                            }
                            return $table;
                        });
                    } else {
                        $table->orWhereHas($key, function ($table) use ($value, $key, $searchValue) {
                            $i = 0;
                            foreach ($value as $col) {
                                if($i == 0) {
                                    $table = $table->where($col, 'LIKE', '%'.$searchValue.'%');
                                } else {
                                    $table = $table->orWhere($col, 'LIKE', '%'.$searchValue.'%');
                                }
                                $i++;
                            }
                            return $table;
                        });
                    }
                    $index++;
                }
            }

        }
        return $table;
    }

    /**
     * filters from datatable and search with scope dropdowns
     * 	 setted in resource
     * @return $query renderable with datatable query
     */
    public function filters($table)
    {
        if (!empty(request('filters'))) {
            $decode = json_decode(request('filters'));
            foreach ($decode as $filter) {
                $date_range = dash_check_range_date_input($filter->value);

                if (!empty($filter->name) && !empty($filter->value)) {
                    if(strtotime($filter->value) !== false) {
                        $table = $table->whereDate($filter->name, $filter->value);
                    } elseif($date_range !== false && is_array($date_range) && count($date_range) > 1 && $date_range['multiple'] === false) {
                        $table = $table->whereBetween($filter->name,$date_range['dates']);
                    } elseif($date_range !== false && is_array($date_range) && count($date_range) > 1 && $date_range['multiple'] === true) {
                        $table = $table->whereIn(\DB::raw('DATE('.$filter->name.')'), $date_range['dates']);
                    } else {
                        $table = $table->where($filter->name, $filter->value);
                    }
                }
            }
        }
        return $table;
    }

    /**
     * Datatable method can make everything with main or sub resource
     * searchable,orderable,searchWithRelation,Get Relation source
     * and with Trashed data scope
     * @return json To renderable with Datatable JS , server side
     */
    public function datatable()
    {

        // If Resource Need Custom Data By Resource ID LIKE USER ID
        if (!empty(request('loadByResourceRelation'))) {
            $masterResource = request('loadByResourceRelation');
            $model          = str_replace('._.', '\\', $masterResource['model']);
            $table          = $model::find($masterResource['record_id']);
            $table          = $table->{$masterResource['attribute']}();

        } else {
            $table = app($this->resource['model']);
            //app($this->resource['resourceNameFull'])->query();
        }



        if (request('withTrashed') == "true") {
            $table = $table->withTrashed();
        }

        $table        = $this->filters($table);


        // Get The Relation methods With Paginate in Datatable
        if (!empty(request('loadRelationResources'))) {
            $getRelationResources = explode(',', request('loadRelationResources'));
            if (!empty($getRelationResources) && count($getRelationResources) > 0) {
                foreach ($getRelationResources as $resource) {
                    if (!empty($resource)) {
                        $table = $table->with([$resource => function ($q) use ($resource) {

                            $this->registerForeignKeyNames($q, $resource);
                        }]);
                    }
                }
            }
        }

        // If Resource Need Custom Data By Resource ID LIKE USER ID
        if (!empty(request('loadByResourcehasMany'))) {
            foreach (request('loadByResourcehasMany') as $k => $v) {
                $table = $table->where($k, $v);
            }
        }

        // If Resource Need Custom Data By many to many like articles.id
        if (!empty(request('loadByResourceToMany'))) {
            $relatedResource = request('loadByResourceToMany');
            foreach ($relatedResource as $key => $value) {
                $methodNameOrTable = explode('.', $key)[0]??null;
                //dd($methodNameOrTable);
                //"article_category.article_id"
                $PivotKeyName = $table->{ $methodNameOrTable}()->getQualifiedRelatedPivotKeyName();
                $columnName   = explode('.', $PivotKeyName)[1]??null;

                //"article_category.category_id"
                //$getForeignKeyName = $table->{ $methodNameOrTable}()->getQualifiedForeignPivotKeyName();

                //dd($columnName);
                //$table = $table->whereHas('articles.id', 1);
                $table = $table->whereHas($methodNameOrTable, function ($q) use ($columnName, $value) {
                    // get by many to many
                    if (!empty($columnName) && !empty($value)) {
                        //dd($columnName, $value);
                        $q->where($columnName, $value);
                    }
                });
            }

        }


        $table = $this->searchWithRelation($table);
        $table = $this->searchable($table);
        $table = $this->orderable($table);

        $table                  = app($this->resource['resourceNameFull'])->query($table);
        $totalRecordswithFilter = $table->count();
        $totalRecords           = $table->count();

        if ($this->resource['paging']) {
            $table = $table->skip(request('start'));
            $table = $table->take(request('length'));
        }

        $output = [
            'draw'            => request('draw'),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalRecordswithFilter,
            'data'            => $table->get()->toArray(),
            //'sql'             => $sql->toSql(), // to test query
        ];

        // register resources to dt
        $this->resourcesRelatedWithRelations();
        $output['ForeignKeyNames'] = $this->registerForeignKeyNames;
        $output['resources']       = $this->resourcesRelatedWithRelations;
        return response()->json($output);
    }

    /**
     *
     * Sub Resource Datatable By Custom Model And Record ID
     * to paginate sub data
     * @return json
     */
    public function SubDatatable()
    {
        return $this->datatable();
    }
}
