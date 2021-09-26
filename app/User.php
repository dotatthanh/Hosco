<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatablee;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatablee implements Authenticatable
{
    use Notifiable;

    protected $remember_token_field='remember_token';
    protected $attributes = [];

    protected $fillable = [
        'name', 'username', 'email', 'display_name', 'password', 'type', 'avatar', 'tenantCode', 'permissions'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get($attribute)
    {
        return $this->attributes[$attribute];
    }

    public function getKey()
    {
        return $this->attributes[$this->getAuthIdentifierName()];
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
        // Return the name of unique identifier for the user (e.g. "id")
        return "username";
    }

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        // Return the unique identifier for the user (e.g. their ID, 123)
        return $this->attributes['username'];
    }

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        // Returns the (hashed) password for the user
        return $this->attributes['password'];
    }

    /**
     * @return string
     */
    public function getRememberToken()
    {
        // Return the token used for the "remember me" functionality
        return isset($this->attributes[$this->getRememberTokenName()])? $this->attributes[$this->getRememberTokenName()] : null;
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // Save a new token user for the "remember me" functionality
        $this->attributes[$this->getRememberTokenName()] = $value;
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        // Return the name of the column / attribute used to store the "remember me" token
        return $this->remember_token_field;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function isAdmin()
    {
        return auth()->user()->type === 'admin';
    }

    public function isUser()
    {
        return auth()->user()->type === 'user';
    }
}
