<?php

namespace App\Http\Controllers;

use App\Mail\ResetLinkMail;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function companyByUser()
    {
        /*
         * Or Auth::user()->companies()->get(). In that case we don't need $user_id param :)
         * */
        return response()->json(Auth::user()->companies()->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', Rule::unique('companies')->where(function ($query) use ($request) {
                    return $query->where('user_id', Auth::id())
                    ->where('title', $request->title);
                })
            ],
            'phone' => 'required',
            'description' => 'required',
        ]);

        try {
            Auth::user()->companies()->create([
                'title' => $request->title,
                'phone' => $request->phone,
                'description' => $request->description,
            ]);
            return response()->json('Successfully created .');
        }catch (\Exception $e) {
            Log::error($e);
            return response()->json("Can't create company .");
        }
    }
}
