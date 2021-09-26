<?php
namespace App\Providers;

use App\Helpers\HttpHelper;
use App\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Auth\Authenticatable;
use \Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;

class UserProvider implements IlluminateUserProvider
{

    private $httpHelper;

    public function __construct()
    {
    }

    /**
     * @param $credentials
     *
     * @return Authenticatable
     */
    public function fetchUsers($credentials)
    {
        try {
            $result = HttpHelper::getInstance()->post("token", [
                'Username' => $credentials['username'],
                'Password' => $credentials['password'],
                'TenantCode' => $credentials['tenantCode'],
                'TenantId' => 0,
                'Language' => 'en'
            ]);
        } catch (ClientException $exception) {
            $errors = ['username' => $exception->getMessage()];
            session()->put('error', $errors);
            return null;
        }

        $meta = $result->meta;
        if($meta->status_code != 0) {
            session()->forget('error');
            $errors = ['username' => $meta->message];

            if(strpos($meta->message, 'Mã khách hàng') !== false) {
                $errors = ['tenantCode' => $meta->message];
            }

            session()->put('error', $errors);
            return null;
        }
        $data = $result->data;
        $token = $data->token;
        $account = $data->Acount;

        //create user to store in session
        $user = new User($credentials);
        /* Set any  user specific fields returned by the api request*/
        $user->username = $credentials['username'];
        $user->email = $account->Email;
        $user->display_name = $account->DisplayName;
        $user->name = $account->DisplayName;
        $user->avatar = $account->Avatar;
        $user->photo = $account->Avatar;
        $user->type = $account->IsSystemAccount ? 'admin' : 'user';
        $user->permissions = $account->Permissions;
        $user->token = $token;

        // $ptoken = $user->createToken('hosco')->accessToken;
        // $this->updateRememberToken($user, $ptoken);

        session()->put('authenticated',true);
        session()->put('token', $token);
        session()->put('user', $user);
        session()->put('tenant_code', $credentials['tenantCode']);
        return $user;
    }

    public function retrieveById($identifier)
    {
        if( ($user = session()->get('user')) ){
            return $user;
        }

        return null;

        // $user = $this->fetchUsers(["username" => $identifier]);
        // return $user;
    }

    public function retrieveByCredentials(array $credentials)
    {
        $user = $this->fetchUsers($credentials);

        return $user;
    }

    public function retrieveByToken($identifier, $token)
    {
        dd(session()->get('user'));
        $authentication = new Authentication;
        $authentication->email = $identifier;
        $authentication->token = $token;
        if( $authentication->save() == false ){
            return null;
        }

        $user = new Users($authentication->toArray());
        $user->setToken($authentication->getResponse()->getHeader('TOKEN-HEADER'));
        session()->set('user', $user);

        return $user;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if( $user->username ){
            return true;
        }

        return false;
    }
}
