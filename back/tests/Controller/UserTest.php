<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserTest extends WebTestCase
{
     /**
     * create a client with a default Authorization header
     * 
     * @param string $email
     * @param string $password
     * 
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($email = 'test1@gmail.com', $password = 'testPassword')
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

    public function testEdit()
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'PATCH', 
            '/api/v0/users/13/edit',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "email": "test@gmail.com",
                "lastname": "Lastname",
                "firstname": "testFirstname",
                "password": "testPassword"
            }'
        );

        $this->assertResponseIsSuccessful();
    }

    public function testShow()
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'GET', 
            '/api/v0/users/13/profil'
        );

        $data = '{"id":13,"email":"test@gmail.com","roles":["ROLE_USER"],"lastname":"Lastname","firstname":"testFirstname","avatar":null,"trip":[]}';
        $this->assertEquals($data, $client->getResponse()->getContent());

    }

    public function testUploadAvatar()
    {
       
        $client = $this->createAuthenticatedClient();

        $photo = new UploadedFile(
            'public/uploads/Cecile.png',
            'Cecile.png',
            'image/png',
            null
        );

        $client->request(
            'PUT', 
            '/api/v0/users/13/upload',
            [],
            ['file'=>$photo],
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
