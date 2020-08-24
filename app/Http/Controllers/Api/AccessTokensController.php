<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\User;
use CloudCreativity\LaravelJsonApi\Contracts\Store\StoreInterface;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Http\Requests\CreateResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AccessTokensController extends JsonApiController
{
    public function create(StoreInterface $store, CreateResource $request)
    {
        $email = $request->get('data.attributes.email');
        $password = $request->get('data.attributes.password');

        /** @var User $user */
        $user = User::where('email', $email)->first();
        if (! $user || ! Hash::check($password, $user->password)) {
            throw new AuthenticationException('The provided credentials are incorrect');
        }

        return $this->reply()->created($user->createToken('default'));
    }
}
