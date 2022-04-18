<?php

namespace App\Http\Services\ContactManager;

use Illuminate\Support\Collection;

interface ContactManagerInterface
{
    public function addContact($contactData): bool;

    public function getContactCollection(): Collection;

    public function getContact($contactIdentifier): ?array;

    public function markAsFavourite($contactIdentifier):bool;

    public function updateContact($contactIdentifier, $contactData): ?bool;

    public function deleteContact($contactIdentifier): bool;
}
