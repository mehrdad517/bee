<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{

    protected $table = 'role';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];


    /**
     * @param $role_id
     * @param bool $has_mapping
     * @return array
     */
    public static function roleHasPermissions($role_id, $has_mapping = false)
    {
        $list = [];

        $parents = Permission::select(['parent'])->groupBy('parent')->pluck('parent');

        foreach ($parents as $parent) {
            $actions= [];

            $join = DB::select('call sp_role_has_permissions_with_access(?, ?)', [$role_id, $parent]);

            if ( $has_mapping ) {
                foreach ($join as $item) {
                    $actions[] = [
                        'id' => $item->id,
                        'parent' => $parent,
                        'title' => $item->title,
                        'access' => $item->access,
                        'method' => $item->method,
                        'url' => $item->url
                    ];
                }

                $list[] = [
                    'controller' => [
                        'key' =>  str_replace('_', ' ', $parent),
                        'value' => $parent
                    ],
                    'actions' => $actions
                ];
            } else {
                foreach ($join as $item) {
                    preg_match('/'.$parent.'_(.*)/', $item->id, $match);
                    $list[$parent][$match[1]] = [
                        'title' => $item->title,
                        'access' => $item->access,
                        'method' => $item->method,
                        'url' => $item->url
                    ];
                }
            }



        }

        return $list;
    }



    public function permissions() {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    public function user() {
        return $this->hasMany(User::class);
    }




}
