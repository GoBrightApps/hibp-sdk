<?php

declare(strict_types=1);

namespace Bright\Hibp;

use Bright\Hibp\Contracts\HibpContract;
use Bright\Hibp\Responses\Breach\BreachInfo;
use Bright\Hibp\Responses\Breach\BreachList;

class Client implements HibpContract
{
    /**
     * Get the http client factory
     */
    private Factory $factory;

    /**
     * Create a new HTTP client instance.
     */
    public function __construct(?Factory $factory = null)
    {
        $this->factory = $factory ?: new Factory;
    }

    /**
     * Set a new http client factory.
     */
    public function withFactory(Factory $factory): self
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * Get all breaches for an account (email, username, or phone).
     *
     * API: `GET /breachedaccount/{account}`
     * Docs: https://haveibeenpwned.com/API/V3#BreachesForAccount
     */
    public function breachedaccount(string $account): BreachList
    {
        $response = $this->factory->get("/breachedaccount/{$account}");

        // Special case: no breaches (HIBP returns 404 for no results)
        if ($response->status() === 404) {
            return new BreachList([]);
        }

        return BreachList::make($response);
    }

    /**
     * Get all breached email addresses for a verified domain.
     *
     * API: `GET /breacheddomain/{domain}`
     * Docs: https://haveibeenpwned.com/API/V3#BreachesForDomain
     *
     * @return array<mixed>
     */
    public function breacheddomain(string $domain)
    {
        return $this->factory->get("/breacheddomain/$domain")->json();
    }

    /**
     * Get the list of domains subscribed under your API key.
     *
     * API: `GET /subscribeddomains`
     * Docs: https://haveibeenpwned.com/API/V3#SubscribedDomains
     *
     * @return array<mixed>
     */
    public function subscribeddomains()
    {
        return $this->factory->get('/subscribeddomains')->json();
    }

    /**
     * Get all breaches in the system.
     *
     * API: `GET /breaches`
     * Docs: https://haveibeenpwned.com/API/V3#AllBreaches
     */
    public function breaches(): BreachList
    {
        return BreachList::make($this->factory->get('/breaches'));
    }

    /**
     * Get a single breach by its name.
     *
     * API: `GET /breach/{name}`
     * Docs: https://haveibeenpwned.com/API/V3#SingleBreach
     */
    public function breach(string $name): BreachInfo
    {
        return BreachInfo::make($this->factory->get("/breach/$name"));
    }

    /**
     * Get the most recently added breach.
     *
     * API: `GET /latestbreach`
     * Docs: https://haveibeenpwned.com/API/V3#LatestBreach
     */
    public function latestbreach(): BreachInfo
    {
        return BreachInfo::make($this->factory->get('/latestbreach'));
    }

    /**
     * Get all data classes in the system.
     *
     * API: `GET /dataclasses`
     * Docs: https://haveibeenpwned.com/API/V3#AllDataClasses
     *
     * @return array<int, string>
     */
    public function dataclasses(): array
    {
        // @phpstan-ignore-next-line
        return $this->factory->get('/dataclasses')->json();
    }

    /**
     * Get all pastes for an account.
     *
     * API: `GET /pasteaccount/{account}`
     * Docs: https://haveibeenpwned.com/API/V3#PastesForAccount
     *
     * @return array<mixed>
     */
    public function pasteaccount(string $account)
    {
        return $this->factory->get("/pasteaccount/$account")->json();
    }

    /**
     * Get current subscription status.
     *
     * API: `GET /subscription/status`
     * Docs: https://haveibeenpwned.com/API/V3#SubscriptionStatus
     *
     * @return mixed
     */
    public function subscriptionStatus()
    {
        return $this->factory->get('/subscription/status')->json();
    }

    /**
     * Check if a password has been pwned using the k-anonymity range API.
     *
     * Note: This is the Pwned Passwords API (no API key required).
     * API: `GET /range/{first5Hash}`
     * Docs: https://haveibeenpwned.com/API/v3#PwnedPasswords
     */
    public function range(string $hashPrefix): mixed
    {
        return $this->factory->get("/range/$hashPrefix")->json();
    }
}
