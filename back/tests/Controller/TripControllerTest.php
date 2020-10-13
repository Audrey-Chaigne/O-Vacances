<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TripControllerTest extends WebTestCase
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

    public function testNoConnected()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v0/users/1/trips'
        );
        $this->assertResponseStatusCodeSame(401);
    }

    public function testConnected()
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'GET', 
            '/api/v0/users/{id}/trips'
        );

        $this->assertResponseIsSuccessful();
        
    }

    // public function testCreateTrip()
    // {
    //     $client = $this->createAuthenticatedClient();

    //     $photo = new UploadedFile(
    //         'public/images/autres.jpg',
    //         'autres.jpg',
    //         'image/jpeg',
    //         null
    //     );

    //     $client->request(
    //         'POST',
    //         '/api/v0/users/1/trips',
    //         [],
    //         ['image'=> $photo],
    //         ['CONTENT_TYPE'=>'multipart/formdata'],
    //         '{
    //             "title": "La fete à la maison",
    //             "description": "faire la fete tout en week end pour décompresser !",
    //             "startDate": "2020-07-30",
    //             "endDate": "2020-08-31",
    //             "password": "fete"
    //         }'

    //     );

    //     $this->assertEquals(201, $client->getResponse()->getStatusCode());
    // }

}
