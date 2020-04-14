<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateProfileAPIRequest;
use App\Http\Requests\API\UpdateProfileAPIRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends AppBaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

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
//        dd($user);
        $profile = Profile::updateOrCreate([
            'avatar' => $request->input('avatar'),
            'user_id' => $user->id
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileAPIRequest $request)
    {
        $input = $request->all();
        $user = Auth::guard('api')->user();
        $user->save($input);

        return $this->sendResponse(new UserResource($user), 'Profile updated successfully');
    }

    public function avatar(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('api')->user();
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('public/avatars');
            if ($user->profile && $user->profile->avatar) {
                Storage::delete($user->profile->avatar);
                $user->profile->avatar = $path;
                $user->profile->save();
            } else {
                $user->profie()->save(
                    Profile::make(['avatar' => $path])
                );
            }
        }
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
