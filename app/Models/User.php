<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
 use Filament\Panel;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     */


    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin'); // only admins allowed
    }



// public function canAccessPanel(Panel $panel): bool
// {
//     return $this->role === 'admin';
// }


 protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'lab_id',
        'lab_code',
        'logo',
        'address',
        'website',
        'mobile',
        'reference_lab',
        'note',
        'digital_signature',
        'qualification',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
        {
            return $this->belongsTo(Role::class);
        }

         public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function getLogoUrlAttribute()
{
    return $this->logo ? asset('storage/' . $this->logo) : null;
}

public function getDigitalSignatureUrlAttribute()
{
    return $this->digital_signature ? asset('storage/' . $this->digital_signature) : null;
}


}
