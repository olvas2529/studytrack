<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'message' => 'ユーザー情報を更新しました',
            'user'    => $user,
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        // トークンを全て削除（ログアウト扱い）
        $user->tokens()->delete();

        // アカウント削除
        $user->delete();

        return response()->json(['message' => 'アカウントを削除しました']);
    }
}

