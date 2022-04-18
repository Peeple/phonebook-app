<?php

namespace App\Http\Services\ContactManager;

use App\Http\Services\UserManager\UserManagerInterface;
use App\Models\Contact;
use Illuminate\Support\Collection;

class EloquentContactManager implements ContactManagerInterface
{
    /**
     * @var UserManagerInterface $userManager
     */
    protected UserManagerInterface $userManager;

    public final function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public final function addContact($contactData): bool
    {
        if (!isset($contactData['name']) || !isset($contactData['phone'])) return false;
        $user = $this->userManager->userDetails();
        if (!$user) return false;
        $contact = new Contact($contactData);
        $contact->user_id = $user->id;
        return $contact->save();
    }

    public final function getContact($contactIdentifier): ?array
    {
        return $this
            ->userManager
            ->userDetails()
            ->contacts()
            ->where('id', $contactIdentifier)
            ->first()
            ?->toArray();
    }

    public final function markAsFavourite($contactIdentifier): bool
    {
        /** @var Contact $contact */
        if ($contact = $this
            ->userManager
            ->userDetails()
            ->contacts()
            ->firstWhere('id', $contactIdentifier)) {
            $contact->favourite = true;
            return $contact->save();
        }

        return false;
    }

    public final function updateContact($contactIdentifier, $contactData): bool
    {
        /** @var Contact $contact */
        if ($contact = $this
            ->userManager
            ->userDetails()
            ->contacts()
            ->firstWhere('id', $contactIdentifier)) {
            return $contact->update($contactData);
        }
        return false;
    }

    public final function deleteContact($contactIdentifier): bool
    {
        /* @var Contact $contact */
        if ($contact = $this
            ->userManager
            ->userDetails()
            ->contacts()->firstWhere('id', $contactIdentifier)) {
            return $contact->delete();
        }
        return false;
    }

    public function getContactCollection(): Collection
    {
        return $this->userManager->userDetails()->contacts()->get();
    }
}
