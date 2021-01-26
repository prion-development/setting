<?php

namespace PrionDevelopment\Setting\Providers;

interface ProviderInterface
{
    public function boot(): void;

    public function register(): void;
}
