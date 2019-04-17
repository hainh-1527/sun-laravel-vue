<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepository;
use App\Http\Requests\SaveUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = $this->userRepository->all();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveUserRequest $request
     */
    public function store(SaveUserRequest $request)
    {
        $this->userRepository->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SaveUserRequest $request
     * @param User $user
     */
    public function update(SaveUserRequest $request, User $user)
    {
        $user->update($request->except('password'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}
