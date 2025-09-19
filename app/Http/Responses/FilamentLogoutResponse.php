<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\LogoutResponse as BaseLogoutResponse;
use Illuminate\Http\RedirectResponse;

class FilamentLogoutResponse extends BaseLogoutResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect('/'); // arahkan ke halaman root
    }
}

