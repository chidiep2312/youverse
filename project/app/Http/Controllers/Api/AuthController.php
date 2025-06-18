<?php

namespace App\Http\Controllers\Api;

use Laravel\Socialite\Facades\Socialite;
use App\Models\TwoFactorCode;
use App\Mail\Send2FACodeMail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }
      
      
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký tài khoản thành công!',
            'type' => 'Bearer',
            'user_id' => $user->id
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Đăng xuất thành công!'
            ]
        );
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Nhập mật khẩu không chính xác']);
        }
        $request->session()->put('2fa:user:id', $user->id);

        $code = rand(100000, 999999);
        $fa = new TwoFactorCode();
        $fa->code = $code;
        $fa->user_id = $user->id;
        $fa->expires_at = now()->addMinutes(5);
        $fa->save();
        Mail::to($user->email)->send(new Send2FACodeMail($code));
        return response()->json([
            'user' => $user->role,
            'is_block' => $user->is_block,
            'success' => true,
            'status' => 'verify_code_required',
            'message' => 'Mã xác thực 2FA đã được gửi. Vui lòng xác minh.'
        ]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::firstOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'avatar' => $googleUser->getAvatar(),
            'password' => bcrypt(str()->random(16)),
        ]);
        if ($user->is_block == 'yes') {
            return redirect()->route('login-fail');
        }
        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;
        return redirect()->route('google.success', [
            'token' => $token,
            'user_id' => $user->id,
            'role' => $user->role,
        ]);
    }
    public function googleSuccess()
    {
        return view('auth.google-success');
    }




    public function showForm()
    {
        return view('auth.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        $userId = session('2fa:user:id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên xác thực không hợp lệ!',
            ]);
        }
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại',
            ]);
        }
        $code = TwoFactorCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Mã không đúng hoặc đã hết hạn!',
            ]);
        }
        $code->delete();
        Auth::login($user);
        $request->session()->regenerate();
        $token = $user->createToken('auth_token')->plainTextToken;
        $request->session()->forget('2fa:user:id');
        return response()->json([
            'success' => true,
            'message' => 'Xác thực thành công.',
            'token' => $token,
            'user_id' => $user->id,
            'is_block' => $user->is_block,
            'role' => $user->role
        ]);
    }


    public function changePass(Request $request, $id)
    {
        $validator = Validator::make(request()->all(), [
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "error" => $validator->errors()->first()]);
        }
        $user = User::findOrFail($id);
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return response()->json(["success" => false, "error" => "Sai mật khẩu!"]);
        }
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        return response()->json(["success" => true, "message" => "Đổi mật khẩu thành công!"]);
    }
    public function  forgotFrm()
    {
        return view('auth.forgot-password');
    }

    public function postForgotFrm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->errors()]);
        }
        $user = User::where('email', $request->input('email'))->first();
    
        $id = $user->id;
        Mail::to($user->email)->queue(new ResetPasswordMail($id));
        return response()->json(["success" => true, "message" => "Hãy kiểm tra email!"]);
    }
    public function setPwdFrm($id)
    {
        return view('auth.set-forgot-pass', compact('id'));
    }

    public function postSetPwdFrm(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()]);
        }
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json(["success" => true, "message" => "Đặt mật khẩu thành công!"]);
    }
}