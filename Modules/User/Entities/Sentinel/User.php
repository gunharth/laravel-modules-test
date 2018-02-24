<?php

namespace Modules\User\Entities\Sentinel;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laracasts\Presenter\PresentableTrait;
use Modules\User\Entities\UserInterface;
use Modules\User\Entities\UserToken;
use Modules\User\Presenters\UserPresenter;

class User extends EloquentUser implements UserInterface, AuthenticatableContract
{
    use PresentableTrait, Authenticatable;

    protected $fillable = [
        'email',
        'password',
        'permissions',
        'first_name',
        'last_name',
    ];

    /**
     * {@inheritDoc}
     */
    protected $loginNames = ['email'];

    protected $presenter = UserPresenter::class;

    public function __construct(array $attributes = [])
    {
        $this->loginNames = config('sorter.user.config.login-columns');
        $this->fillable = config('sorter.user.config.fillable');
        if (config()->has('sorter.user.config.presenter')) {
            $this->presenter = config('sorter.user.config.presenter', UserPresenter::class);
        }
        if (config()->has('sorter.user.config.dates')) {
            $this->dates = config('sorter.user.config.dates', []);
        }
        if (config()->has('sorter.user.config.casts')) {
            $this->casts = config('sorter.user.config.casts', []);
        }

        parent::__construct($attributes);
    }

    /**
     * @inheritdoc
     */
    public function hasRoleId($roleId)
    {
        return $this->roles()->whereId($roleId)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function hasRoleSlug($slug)
    {
        return $this->roles()->whereSlug($slug)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function hasRoleName($name)
    {
        return $this->roles()->whereName($name)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function isActivated()
    {
        if (Activation::completed($this)) {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function api_keys()
    {
        return $this->hasMany(UserToken::class);
    }

    /**
     * @inheritdoc
     */
    public function getFirstApiKey()
    {
        $userToken = $this->api_keys->first();

        if ($userToken === null) {
            return '';
        }

        return $userToken->access_token;
    }

    public function __call($method, $parameters)
    {
        #i: Convert array to dot notation
        $config = implode('.', ['sorter.user.config.relations', $method]);

        #i: Relation method resolver
        if (config()->has($config)) {
            $function = config()->get($config);
            $bound = $function->bindTo($this);

            return $bound();
        }

        #i: No relation found, return the call to parent (Eloquent) to handle it.
        return parent::__call($method, $parameters);
    }

    /**
     * @inheritdoc
     */
    public function hasAccess($permission)
    {
        $permissions = $this->getPermissionsInstance();

        return $permissions->hasAccess($permission);
    }
}
