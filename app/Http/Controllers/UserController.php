<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string', // 例: "azarashi.svg"
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->name = $request->name;

        // DBに保存する形式を icons/ファイル名 に統一
        $user->icon_path = 'icons/'.$request->icon;

        $user->save();

        return redirect()->back()->with('success', 'プロフィールを更新しました。');
    }
}
