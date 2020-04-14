<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProfileAPIRequest;
use App\Http\Requests\API\UpdateProfileAPIRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use App\Repositories\ProfileRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Response;

/**
 * Class ProfileController
 * @package App\Http\Controllers\API
 */
class ProfileAPIController extends AppBaseController
{
    /** @var  ProfileRepository */
    private $profileRepository;

    public function __construct(ProfileRepository $profileRepo)
    {
        $this->profileRepository = $profileRepo;
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the Profile.
     * GET|HEAD /profiles
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        return $this->sendResponse(new UserProfileResource($user->profile),
            'Profiles retrieved successfully');
    }

    /**
     * Store a newly created Profile in storage.
     * POST /profiles
     *
     * @param CreateProfileAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProfileAPIRequest $request)
    {
        $user = Auth::guard('api')->user();
        $profile=Profile::create([
            'avatar'=>$request->input('avatar'),
            'user_id'=>$user->id
        ]);
        return $this->sendResponse($profile, 'Profile saved successfully');
    }

    /**
     * Display the specified Profile.
     * GET|HEAD /profiles/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Profile $profile */
        $user = Auth::guard('api')->user();
        if (empty($user->profile)) {
            return $this->sendError('Profile not found');
        }
        return $this->sendResponse(new UserProfileResource($user->profile),
            'Profile retrieved successfully');
    }

    /**
     * Update the specified Profile in storage.
     * PUT/PATCH /profiles/{id}
     *
     * @param int $id
     * @param UpdateProfileAPIRequest $request
     *
     * @return Response
     */
    public function update(UpdateProfileAPIRequest $request)
    {
        $input = $request->all();
        $user = Auth::guard('api')->user();
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars');
            if ($user->profile) {
                $user->profile->avatar = $path;
                $user->profile->save();
            } else {
                $user->profie()->save(
                    Profile::make(['avatar' => $path])
                );
            }
        }
        $user=$user->save($input);

        return $this->sendResponse(new UserResource($user), 'Profile updated successfully');
    }

    /**
     * Remove the specified Profile from storage.
     * DELETE /profiles/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Profile $profile */
        $profile = $this->profileRepository->find($id);

        if (empty($profile)) {
            return $this->sendError('Profile not found');
        }

        $profile->delete();

        return $this->sendSuccess('Profile deleted successfully');
    }
}
