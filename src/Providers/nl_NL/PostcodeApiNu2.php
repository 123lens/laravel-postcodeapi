<?php

namespace nickurt\postcodeapi\Providers\nl_NL;

use \nickurt\PostcodeApi\Providers\Provider;
use \nickurt\PostcodeApi\Entity\Address;

class PostcodeApiNu2 extends Provider {
    
    protected $apiKey;
    protected $requestUrl;

    /**
     * @return mixed
     */
    protected function request()
    {
        $client = $this->getHttpClient();
        $client->setDefaultOption('headers', ['X-Api-Key' => $this->getApiKey()]);
        $response = $client->get($this->getRequestUrl())->json();

        return $response;
    }
    
    /**
     * @param $postCode
     * @return Address
     */
    public function find($postCode)
    {
        $this->setRequestUrl(sprintf($this->getRequestUrl(), $postCode, ''));
        $response = $this->request();

        $address = new Address();
        $address
            ->setStreet($response['_embedded']['addresses'][0]['street'])
            ->setTown($response['_embedded']['addresses'][0]['city']['label'])
            ->setMunicipality($response['_embedded']['addresses'][0]['municipality']['label'])
            ->setProvince($response['_embedded']['addresses'][0]['province']['label'])
            ->setLatitude($response['_embedded']['addresses'][0]['geo']['center']['wgs84']['coordinates'][0])
            ->setLongitude($response['_embedded']['addresses'][0]['geo']['center']['wgs84']['coordinates'][1]);

        return $address;
    }

    public function findByPostcode($postCode) {}

    /**
     * @param $postCode
     * @param $houseNumber
     * @return Address
     */
    public function findByPostcodeAndHouseNumber($postCode, $houseNumber)
    {
        $this->setRequestUrl(sprintf($this->getRequestUrl(), $postCode, $houseNumber));
        $response = $this->request();

        $address = new Address();
        $address
            ->setHouseNo($response['_embedded']['addresses'][0]['number'])
            ->setStreet($response['_embedded']['addresses'][0]['street'])
            ->setTown($response['_embedded']['addresses'][0]['city']['label'])
            ->setMunicipality($response['_embedded']['addresses'][0]['municipality']['label'])
            ->setProvince($response['_embedded']['addresses'][0]['province']['label'])
            ->setLatitude($response['_embedded']['addresses'][0]['geo']['center']['wgs84']['coordinates'][0])
            ->setLongitude($response['_embedded']['addresses'][0]['geo']['center']['wgs84']['coordinates'][1]);

        return $address;
    }
}