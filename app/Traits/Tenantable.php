<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Tenantable
{
    protected static function bootTenantable()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenantId = self::getTenantId();

            if (!$tenantId) {
                // throw new \Exception('Tenant ID não definido');
                return;
            }
            
            $builder->where($builder->getModel()->getTable() . '.company_id', $tenantId);
        });

        static::creating(function ($model) {
            $tenantId = self::getTenantId();
            
            if ($tenantId && empty($model->company_id)) {
                $model->company_id = $tenantId;
            }
        });
    }
    
    protected static function getTenantId()
    {
        if (Auth::check()) {
            return Auth::user()->company_id;
        }

        if (app()->bound('tenant_id')) {
            return app('tenant_id');
        }
        
        return null;
    }
}