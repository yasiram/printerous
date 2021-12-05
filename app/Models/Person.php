<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $fillable = ['name', 'email', 'phone', 'avatar', 'fk_organization_id'];

    public function organization()
    {
        return $this->hasOne(Organization::class, 'id', 'fk_organization_id');
    }
}
