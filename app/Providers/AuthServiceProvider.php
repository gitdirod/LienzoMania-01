<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Definir un gate para verificar si un usuario es administrador
        Gate::define('admin', function ($user) {
            return $user->role == 'admin';  // Asume que el modelo User tiene una columna 'role' que indica el rol del usuario.
        });

        //
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject(('LienzoManía, Verificación de correo'))
                ->action('Confirmar cuenta', $url)
                ->line('Si no creaste esta cuenta, puedes ignorar este mensaje');
        });
    }
}
