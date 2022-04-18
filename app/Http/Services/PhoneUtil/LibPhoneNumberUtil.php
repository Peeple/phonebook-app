<?php

namespace App\Http\Services\PhoneUtil;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

class LibPhoneNumberUtil implements PhoneUtilInterface
{
    protected PhoneNumberUtil $phoneNumberUtil;

    public final function __construct()
    {
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * @param int $phoneNumber
     * @param string $country
     * @return string
     */
    public final function formatPhone($phoneNumber, $country = 'RU'): string
    {
        $phoneObject = $this->getPhoneObject($phoneNumber, $country);
        return $this->phoneNumberUtil->format($phoneObject, $country);
    }

    /**
     * @param string $phoneNumber
     * @param string $country
     * @return int|null
     */
    public final function parsePhone($phoneNumber, $country = 'RU'): ?int
    {
        return $this->getPhoneObject($phoneNumber, $country)?->getNationalNumber();
    }

    /**
     * @param string $phoneNumber
     * @param string $country
     * @return bool
     */
    public final function isPossiblePhone($phoneNumber, $country = 'RU'): bool
    {
        return $this
            ->phoneNumberUtil
            ->isValidNumberForRegion($this->getPhoneObject($phoneNumber, $country), $country);
    }

    /**
     * @param string $phoneString
     * @param string $country
     * @return PhoneNumber|null
     */
    private function getPhoneObject($phoneString, $country = 'RU'): ?PhoneNumber
    {
        try {
            return $this->phoneNumberUtil->parse($phoneString, $country);
        } catch (\Exception $exception) {
            return null;
        }
    }
}
