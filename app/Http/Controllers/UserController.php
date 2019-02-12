<?php

namespace App\Http\Controllers;

use App\Author;
use App\Reviewer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function checkIfUserLoggedIn()
    {
        return response(json_encode(Auth::check() ? true : false), 200);
    }

    public function getCurrentUser()
    {
        return response(json_encode(Auth::user()), 200);
    }

    public function login(Request $request)
    {
        $email = $request->json('email');
        $password = $request->json('password');
        $remember = $request->json('remember');
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $user = User::where('email', $email)->first();
            Auth::login($user, $remember);
            return response(json_encode('User logged in successfully.'), 200);
        } else
            return response(json_encode('Incorrect email or password!'), 200);
    }

    public function logout()
    {
        Auth::logout();
        return response(json_encode('User successfully logged out.'), 200);
    }

    public function register(Request $request)
    {
        $rules = array(
            'username' => 'required|string|min:6|max:50|unique:users',
            'email' => 'required|string|email|max:190|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password',
            'first_name' => 'required|string|max:20|alpha',
            'last_name' => 'required|string|max:30|alpha',
            'description' => 'nullable|string|max:150',
            'dob' => 'required|date|after:1 January 1900',
            'picture' => 'nullable|string|max:190',
            'role' => 'required',
            'organization_type' => 'bail|nullable|required_if:role,0',
            'organization_name' => 'bail|nullable|required_if:role,0|string|max:190',
            'organization_country' => 'nullable|required_if:role,0',
            'organization_address' => 'nullable|required_if:role,0|string|max:190',
            'organization_email' => 'nullable|string|email|max:190',
            'organization_telephone' => 'nullable|string|regex:/\+\d+/',

        );
        $validator = Validator::make($request->json()->all(), $rules);
        if ($validator->fails())
            return response(json_encode($validator->getMessageBag()->toArray()), 200);
        else {
            $user = new User(array(
                'email' => $request->json('email'),
                'username' => $request->json('username'),
                'password' => bcrypt($request->json('password')),
                'first_name' => $request->json('first_name'),
                'last_name' => $request->json('last_name'),
                'description' => $request->json('description'),
                'dob' => $request->json('dob'),
                'picture' => $request->json('picture')));
            $user->save();
            if ($request->json('role') == 0) {
                $author = new Author(array(
                    'user_id' => $user->getAttribute('id'),
                    'organization_type' => $request->json('organization_type'),
                    'organization_name' => $request->json('organization_name'),
                    'organization_country' => $request->json('organization_country'),
                    'organization_address' => $request->json('organization_address'),
                    'organization_email' => $request->json('organization_email'),
                    'organization_telephone' => $request->json('organization_telephone')));
                $author->save();
            } else {
                $reviewer = new Reviewer(array('user_id' => $user->getAttribute('id')));
                $reviewer->save();
            }
            return response(json_encode('User account successfully created.'), 200);

        }
    }

    public function uploadProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(),
            array('fileToUpload' => 'required|image|mimes:jpeg,png,jpg,gif,svg',));
        if (!$validator->fails()) {
            $file = $request->file('fileToUpload');
            $name = $request->get('username') . $file->getClientOriginalName();
            $destinationPath = public_path('/images/profile_pictures');
            $file->move($destinationPath, $name);
            return response(json_encode('Image uploaded successfully.'), 200);
        } else
            return response(json_encode('Invalid image file!'), 500);
    }

    public function getRole($id)
    {
        $user = User::find($id);
        if ($user->author)
            $role = 0;
        else
            $role = 1;
        return response(json_encode($role), 200);
    }

    public function getAuthorInfo($id)
    {
        $user = User::find($id);
        return response(json_encode($user->author()->first()), 200);
    }

    public function deleteAccount($id)
    {
        $user = User::find($id);
        $user->delete();
        return response(json_encode('User account successfully deleted.'), 200);
    }
}
