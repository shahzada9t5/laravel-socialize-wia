<?php
namespace ShahzadaSaeed\SocializeWia;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class SocialiteWiaProvider extends AbstractProvider implements ProviderInterface
{
    protected $fields = [
        'id', 'username', 'url', 'first_name', 'last_name', 'bio', 'image'
    ];

    /**
     * @return string
     */
    public function getWiaUrl()
    {
        return config('services.wia.base_uri') . '/oauth2';
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getWiaUrl() . '/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->getWiaUrl() . '/token';
//        return 'https://api.pinterest.com/v1/oauth/token?' . http_build_query([
//                'grant_type' => 'authorization_code',
//            ]);
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post($this->getWiaUrl() . '/userInfo', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);

//        $url = 'https://api.pinterest.com/v1/me';
//
//        $response = $this->getHttpClient()->get($url, [
//            'query' => [
//                'access_token' => $token,
//                'fields' => implode(',', $this->fields)
//            ],
//        ]);
//
//        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array $user
     * @return \Laravel\Socialite\User
     */
    protected function mapUserToObject(array $user)
    {
        $user = $user['data'];

        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => null,
            'avatar' => $user['image']['60x60']['url'],
            'avatar_original' => null,
        ]);
    }
}
