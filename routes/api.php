<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

// ログインAPIエンドポイント
Route::post('/login', function (Request $request) {
    // ① 入力バリデーション：emailとpasswordが必須
    $request->validate([
        'email' => 'required|email',         // email形式で必須
        'password' => 'required|string',     // 文字列で必須
    ]);

    // ② ユーザーをemailで検索
    $user = User::where('email', $request->email)->first();

    // ③ ユーザーが存在しない、またはパスワードが一致しない場合は認証失敗
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    // ④ Personal Access Tokenを発行（Passport使用）
    $token = $user->createToken('Personal Access Token')->accessToken;

    // ⑤ 成功時、アクセストークンを返す
    return response()->json(['token' => $token]);
});

Route::middleware('auth:api')->get('/me', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware('auth:api')->post('/logout', function (Request $request) {
    $request->user()->token()->revoke(); // 現在のアクセストークンを無効化
    return response()->json(['message' => 'ログアウトしました']);
});

Route::post('/register', function (Request $request) {
    // バリデーション
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // ユーザー作成
    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    // Personal Access Token 発行
    $token = $user->createToken('Personal Access Token')->accessToken;

    return response()->json([
        'message' => 'ユーザー登録に成功しました',
        'token' => $token,
        'user' => $user,
    ]);
});

Route::middleware('auth:api')->post('/change-password', function (Request $request) {
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = $request->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['error' => '現在のパスワードが正しくありません'], 403);
    }

    $user->password = bcrypt($request->new_password);
    $user->save();

    return response()->json(['message' => 'パスワードを変更しました']);
});

Route::middleware('auth:api')->put('/user', [App\Http\Controllers\UserController::class, 'update']);
Route::middleware('auth:api')->delete('/user', [App\Http\Controllers\UserController::class, 'destroy']);

