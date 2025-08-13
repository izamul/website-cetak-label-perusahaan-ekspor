<?php

namespace App\Providers;

use App\Models\Label;
use App\Policies\LabelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [ Label::class => LabelPolicy::class ];
}
