<?php

namespace App\Http\Services\PhoneUtil;

interface PhoneUtilInterface
{
    public function formatPhone($phoneNumber, $country): ?string;

    public function parsePhone($phoneNumber, $country): ?int;

    public function isPossiblePhone($phoneNumber, $country): bool;
}
