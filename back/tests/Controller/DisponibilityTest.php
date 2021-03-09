<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DisponibilityTest extends WebTestCase
{
    /**
     * create a client with a default Authorization header
     * 
     * @param string $email
     * @param string $password
     * 
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($email = 'fabio@gmail.com', $password = 'fabio')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            array(),
            array(),
            array('CONTENT_TYPE'=>'application/json'),
            json_encode(array(
                'email' => $email,
                'password'=> $password,
            ))
            );
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testCreateDisponibility()
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'POST',
            '/api/v0/users/1/disponibilities',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "startDate": "2020-12-12",
                "endDate": "2020-12-25",
                "trip": "4"
            }'
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

}
