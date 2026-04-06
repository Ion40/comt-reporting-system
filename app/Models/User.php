<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'require_password_change',
        'id_estado'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function getAvatarColorAttribute() {
        $colors = ['primary', 'success', 'info', 'danger', 'secondary', 'warning'];

        // Usamos el ID para determinar el índice.
        // Si el ID es nulo (usuario no guardado), devolvemos el primero por defecto.
        $index = $this->id ? ($this->id % count($colors)) : 0;

        return $colors[$index];
    }

    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->nombre);
        $initials = '';

        if (count($names) >= 2) {
            // Toma la primera letra del primer nombre y del primer apellido
            $initials = strtoupper(substr($names[0], 0, 1) . substr($names[1], 0, 1));
        } else {
            // Si solo tiene un nombre, toma las dos primeras letras
            $initials = strtoupper(substr($this->nombre, 0, 2));
        }

        return $initials;
    }
}
