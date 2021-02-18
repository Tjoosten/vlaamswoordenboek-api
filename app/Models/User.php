<?php

namespace App\Models;

use App\Traits\AttributeHashable;
use App\Traits\ModelValidatable;
use App\Traits\QueryFilterable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static findOrFail(int $id)
 * @method static filter(\Illuminate\Http\Request $request)
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable;
    use Authorizable;
    use QueryFilterable;
    use ModelValidatable;
    use AttributeHashable;
    use HasFactory;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];
    protected $visible = ['id', 'name', 'email'];
    protected $filterable = ['name', 'email'];
    protected $hashable = ['password'];

    public function rules(): array
    {
        return [
            '*'      => ['name' => 'required',],
            'CREATE' => ['email' => 'required|unique:users,email', 'password' => 'required|min:6',],
            'UPDATE' => ['email' => 'required|unique:users,email,' . $this->id, 'password' => 'sometimes|min:6',],
        ];
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
