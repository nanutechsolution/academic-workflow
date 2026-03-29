<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'role', 'organization_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Helper untuk Filament Authorization nantinya
    public function isWarek(): bool
    {
        return $this->role === 'warek';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Atur ke true sementara agar semua user bisa login panel
    }

    public function incomingDispositions()
    {
        return $this->hasMany(Disposition::class, 'to_org_id');
    }

    public function outgoingDispositions()
    {
        return $this->hasMany(Disposition::class, 'from_user_id');
    }
}
