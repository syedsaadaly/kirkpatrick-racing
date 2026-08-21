<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();

        return view('admin.dashboard', compact('user', 'roleName'));
    }

    public function calendar()
    {
        return view('admin.pages.calendar');
    }


    public function loginView()
    {

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to authenticate the user
        if (auth()->attempt($request->only('email', 'password'))) {
            // Authentication passed, redirect to dashboard
            return redirect()->route('admin.dashboard')->with('success', 'Login successful');
        }

        // Authentication failed, redirect back with an error message
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    function adminLogout()
    {
        Auth::guard('web')->logout();
        return redirect('admin/login')->with('success', 'Admin Logout Successfully');
    }

    public function profileSettings()
    {
        return view('admin.settings.profile');
    }

    public function profileSettingsUpdate(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'password' => ['nullable','confirmed','min:6'],
            'profile_image' => [
                'nullable',
                'image',
                'max:2048'
            ],
        ]);
        $user->name  = $validated['user_name'];
        $user->email = $validated['user_email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        if ($request->hasFile('profile_image')) {

            $user
                ->addMediaFromRequest('profile_image')
                ->toMediaCollection('profile');
        }
        return back()->with('success', 'Profile Updated Successfully');
    }
}
