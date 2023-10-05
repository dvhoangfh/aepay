<?php

namespace Dvhoangfh\Aepay\Services;

use Illuminate\Support\Facades\Log;

class BytePayService extends BaseService
{
    public string $url;
    
    public function __construct()
    {
        $this->url = config('services.bytepay.endpoint');
    }
    
    public function createPayment(array $params = [])
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->request('POST', $this->url . '/payment/bank', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json'    => $params,
            ]);
            
            $body = $response->getBody();
            
            return json_decode($body, true);
        } catch (\Exception $exception) {
            Log::error('Create payment bytepay error: ' . $exception->getMessage());
            return [];
        }
        
    }
    
    public function sign($message)
    {
        $privateKey = '-----BEGIN RSA PRIVATE KEY-----
MIIEoQIBAAKCAQBhh6Bq8dPfySWEldDUGoDudbP+PHXBhey1UMBRr0Nfl63m3fo+
iwOscvBxD6CuyEHgGT8hu08o6rT54tjoS0+ZP3LBCr81QlJtPi6nJjTiTs27Qq4n
6NlP4+HcFznBUzmUTeF9p734xNgdGL90u6qKABCtBAiyCgMV2WJktqe7S2teo1F8
gw0+RePxyhrAoXhZxUKZR3vFICZjPEgJKzvGlCv6cDrYawsq0vPJnLeJuRlt9Cx0
hNKT9knu7tm0p5hCrywW+emZOCQWKrSJjtl80GMg95ukkW2lILjpHr2KJ2294BtQ
Mwb218u7Zfg12v6pqd921y31T1R5z9SAhyO7AgMBAAECggEAJVaJIahHVwto46N/
eVRUO/av3AtFgbtF7fxmRF92yagGGbat0TfjRigSyRYUjBV6stK3irBtE9sXQfuI
0xXR+XeC9Uip/zfO2qfNsIw9/s3wkVpr1jecyqsRkvMJV2wHRTGzvCl130FtA2aw
FX6rQX3Y5IQA2DhhCgJZWvlaqaczDPD4Unhp0y348gmWn3ylWiPxTDioCBFSdw62
D0hnBy4HMqKQri4WRBTXhUTQr/vOzhw1jEImc2t+vn/FRda+sNExXUKVLa+Cr2JP
SI3i9DfLUXw/1U4SZpSDKlT+B9P1icM36L4BH11QsdOzX62rTUjfyRxGPyOImlrm
oH8JYQKBgQCel5z5ecDckoQT04Jfu3j6PAJbknCBQNf/pgLIjpKI6uuBgeQCcKCr
NiKs1GDPRPnoXPzKnix54lz8XL+fmxBHmAKPu7G800vj491crrh+43UKdnEDNCGI
XhDAq37/Urz2TWgiVY1ZouFW8CR7tKAcWqaST28LA+qQMFpQEWJb8wKBgQCdbs6e
/YwhVFEwIYT2vTZR2LFBq77Vxw8B8TqWqSjTrQ9ZBYn2Uktr1KjP/JjrtYdZ8P/V
fyvkzzWTGJ4T3LKiUVXsBOGWNgJJOjroxfMSJ5+hoHhBshDDap8lAQkOtj4b9ay/
Skt2DZi1Px69/uc6RYBsYEd44X/JonT/RGpzGQKBgE2trTF0tUZUbWTYoG8LgEHw
yqbXn0tv21xYj9x0GMn/ZsLUQD4BtHCn32wPyis7ebyabyc/OkGo4iqpsyIV7W9j
wT1tja0TQYSpw272FV/xQQ6S3N/Y1OPaTxJP7bBZcjyhxXOru8Q/x2exz+zXl+TL
cfMP/S/EB0wAovu8yBSJAoGAXv9QGswwVKRGYV64lxLRxk2VmGUEQIdfXPKtQeBN
TH2vNy6Kc2JxNF4ch3SK6iCdzf4IwlF8sRL/5wUBzkZsXFnsGQFRBo0MkBddGLzp
0dirEKJ+LlY+m0ypP3ECgCgkDkcOuFciddf6f9PYvxkjehwaREtMlXqs24T2SFqG
SXECgYBV6jtYBOLQvoauzH5pGK+0oqPLvJoP4/0MbS7iXM4w52i9EDSwRGOtD66F
hoiK0sWV0MQAyVPVbcAPghsPJq6IJNKh8HUCHd9/2zgUGwsxM2sccBD/f1jYKwp9
/9gxP1d0osiBdnTGacJCsD7eK/8OPZOAuVrOuwXVUO4JAYT8Ww==
-----END RSA PRIVATE KEY-----';
        $algo = OPENSSL_ALGO_SHA256;
        openssl_sign($message, $binary_signature, $privateKey, $algo);
        return base64_encode($binary_signature);
    }
    
    function build_query_string($params)
    {
        $query = '';
        foreach ($params as $key => $value) {
            $query .= $key . '=' . $value . '&';
        }
        return rtrim($query, '&');
    }
    
}
