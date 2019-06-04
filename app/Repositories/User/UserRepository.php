<?php

namespace App\Repositories\User;

use App\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Exceptions\GeneralException;
class UserRepository extends BaseRepository
{
    const MODEL = User::class;

    public function findByEmail($email)
    {
        return $this->query()->where('email', $email)->first();
    }

    public function findByToken($token)
    {
        return $this->query()->where('confirmation_code', $token)->first();
    }

    public function confirmAccount($token)
    {
        $user = $this->findByToken($token);

        if ($user->confirmed == 1) {
            throw new GeneralException('Email sudah di konfirmasi sebelumnya.!!!');
        }

        if ($user->confirmation_code == $token) {
            $user->confirmed = 1;

           // event(new UserConfirmed($user));

            return $user->save();
            
        }

        throw new GeneralException("Terjadi kesalahan pada kode konfirmasi anda!!!");
    }
}
