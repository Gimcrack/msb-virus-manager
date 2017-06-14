<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Promote the user to an admin
     * @method promoteToAdmin
     *
     * @return   void
     */
    public function promoteToAdmin()
    {
        $this->admin_flag = 1;
        $this->save();
    }

    /**
     * Is the user an admin
     * @method isAdmin
     *
     * @return   bool
     */
    public function isAdmin()
    {
        return !! $this->admin_flag;
    }

    /**
     * Get the users who are admins
     * @method scopeAdmins
     *
     * @return   Builder
     */
    public function scopeAdmins(Builder $query)
    {
        return $query->whereAdminFlag(1);
    }
}
