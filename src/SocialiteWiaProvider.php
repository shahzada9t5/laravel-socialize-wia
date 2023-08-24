<?php
namespace ShahzadaSaeed\SocializeWia;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class SocialiteWiaProvider extends AbstractProvider implements ProviderInterface
{

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'read_wia_user'
    ];
    protected $fields = [
        'id', 'name', 'email', 'avatar_image', 'primary_contact'
    ];

    /**
     * @return string
     */
    public function getWiaUrl()
    {
        return config('services.wia.base_uri') ;
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getWiaUrl() . '/oauth2/sso', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->getWiaUrl() . '/api/generate/token?' . http_build_query([
                'grant_type' => 'authorization_code',
            ]);
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post($this->getWiaUrl() . '/api/me', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
        return json_decode($response->getBody()->getContents(), true);

    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array $user
     * @return \Laravel\Socialite\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'name' => $user['fullname'],
            'email' => $user['email'],
            'avatar_image' => $user['profile_image'],
            'primary_contact' => $user['mobile'],
        ]);
    }
}
