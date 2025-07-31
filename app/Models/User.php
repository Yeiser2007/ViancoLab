<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //Scopes
    public function scopeOfName($query, $name)
    {
        if (!empty($name)) {
            return $query->where(function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%')
                    ->orWhere('first_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%');

                if (method_exists($this, 'company')) {
                    $q->orWhereHas('company', function (Builder $query) use ($name) {
                        $query->where('name', 'like', '%' . $name . '%')
                            ->orWhere('rfc', 'like', '%' . $name . '%');
                    });
                }
            });
        }
        return $query;
    }
    public function scopeOfRole($query, $role)
    {
        if (!empty($role)) {
            return $query->whereHas('roles', function (Builder $query) use ($role) {
                $query->where('id', $role);
            });
        }
        return $query;
    }
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setSurnamesAttribute($value)
    {
        $this->attributes['surnames'] = mb_strtoupper($value);
    }
    public function getCanPermissions()
    {
        $permissions = [];
        if (auth()->user()->id == $this->id) {
            foreach ($this->getAllPermissions() as $permission) {
                $permissions[$permission->name] = true;
            }
        }
        return $permissions;
    }

}
