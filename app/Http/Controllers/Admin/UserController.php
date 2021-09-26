<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Addresses\addressRequest;
use App\Http\Requests\Users\userRequest;
use App\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    private $user;
    private $paginate;

    public function __construct()
    {
        $this->middleware('checkRole');
        $this->user = new User();
        $this->paginate = 15;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = [];//$this->user->with('roles')->paginate($this->paginate);
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::all(['id', 'name']);
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Display the user profile.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $user = auth()->user();//$this->userRepo->find($id)->load('orders');
        $orders = [];//$user->orders;
        return view('admin.user.profile', compact('user', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        return view('admin.user.edit', compact('user'));

    }

    /**
     * Show the form for editing user Address.
     *
     * @param int $id
     * @return Response
     */
    public function changePassword($id)
    {
        $user = auth()->user();
        return view('admin.user.changePassword', compact('user'));

    }

    /**
     *  updating user password.
     *
     * @param int $id
     * @param  $request
     * @return RedirectResponse
     */
    public function updatePassword(PasswordRequest $request, $id)
    {
        try {
            $result = $this->httpHelper->post("Acount/ChangePwd", [
                'Username' => auth()->user()->username,
                'NewPassword' => $request->newPassword,
                'OldPassword' => $request->oldPassword
            ]);
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return view('admin.user.index', compact('result'));
    }
}
