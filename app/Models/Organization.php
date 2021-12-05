<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;
use App\Models\Person;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organization';

    protected $fillable = ['name', 'email', 'phone', 'website', 'logo', 'fk_accountmanager_id'];

    public function account_manager()
    {
        return $this->hasOne(Users::class, 'id', 'fk_accountmanager_id');
    }

    public function persons()
    {
        return $this->hasMany(Person::class, 'fk_organization_id', 'id');
    }
}
