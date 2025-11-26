<?php

namespace Bright\Hibp\Contracts;

use Bright\Hibp\Responses\Breach;
use Bright\Hibp\Responses\Breaches;

/**
 * Interface HibpContract
 *
 * Contract for Have I Been Pwned API v3.
 *
 * @see https://haveibeenpwned.com/API/V3
 */
interface HibpContract
{
    /**
     * Get all breaches for an account (email, username, or phone).
     *
     * API: `GET /breachedaccount/{account}`
     * Docs: https://haveibeenpwned.com/API/V3#BreachesForAccount
     */
    public function breachedaccount(string $account): Breaches;

    /**
     * Get all breached email addresses for a verified domain.
     *
     * API: `GET /breacheddomain/{domain}`
     * Docs: https://haveibeenpwned.com/API/V3#BreachesForDomain
     *
     * @return array<mixed>
     */
    public function breacheddomain(string $domain);

    /**
     * Get the list of domains subscribed under your API key.
     *
     * API: `GET /subscribeddomains`
     * Docs: https://haveibeenpwned.com/API/V3#SubscribedDomains
     *
     * @return array<mixed>
     */
    public function subscribeddomains();

    /**
     * Get all breaches in the system.
     *
     * API: `GET /breaches`
     * Docs: https://haveibeenpwned.com/API/V3#AllBreaches
     */
    public function breaches(): Breaches;

    /**
     * Get a single breach by its name.
     *
     * API: `GET /breach/{name}`
     * Docs: https://haveibeenpwned.com/API/V3#SingleBreach
     */
    public function breach(string $name): Breach;

    /**
     * Get the most recently added breach.
     *
     * API: `GET /latestbreach`
     * Docs: https://haveibeenpwned.com/API/V3#LatestBreach
     */
    public function latestbreach(): Breach;

    /**
     * Get all data classes in the system.
     *
     * API: `GET /dataclasses`
     * Docs: https://haveibeenpwned.com/API/V3#AllDataClasses
     *
     * @return array<int, string>
     */
    public function dataclasses(): array;

    /**
     * Get all pastes for an account.
     *
     * API: `GET /pasteaccount/{account}`
     * Docs: https://haveibeenpwned.com/API/V3#PastesForAccount
     *
     * @return array<mixed>
     */
    public function pasteaccount(string $account);

    /**
     * Get current subscription status.
     *
     * API: `GET /subscription/status`
     * Docs: https://haveibeenpwned.com/API/V3#SubscriptionStatus
     *
     * @return mixed
     */
    public function subscriptionStatus();

    /**
     * Check if a password has been pwned using the k-anonymity range API.
     *
     * Note: This is the Pwned Passwords API (no API key required).
     * API: `GET /range/{first5Hash}`
     * Docs: https://haveibeenpwned.com/API/v3#PwnedPasswords
     *
     * @return mixed
     */
    public function range(string $hashPrefix);
}
