<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UploadType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
     /**
     * @Route("/api/v0/users/all", name="api_v0_user_list", methods="GET")
     */
    public function list(UserRepository $userRepository, ObjectNormalizer $normalizer)
    {
    
        $users = $userRepository->findAll();

        // On instancie un serializer en lui précisant un normalizer adapté aux objets PHP
        $serializer = new Serializer([$normalizer]);
        // Parce qu'on a précisé le normalizer, on peut normaliser selon un groupe
        $normalizedUsers = $serializer->normalize($users, null, ['groups' => 'apiV0_list']);

        return $this->json($normalizedUsers);        
    }

    /**
     * @Route("api/v0/users/{id}/edit", name="api_user_edit", methods={"PATCH"})
     */
    public function edit(UserPasswordEncoderInterface $passwordEncoder, Request $request, User $user, ObjectNormalizer $normalizer, UserRepository $userRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {             
        $oldPassword = $user->getPassword();  
          
        if (!empty($user)) {
           
            // On extrait de la requête le json reçu
            $jsonText = $request->getContent();

            try {
                $newUser = $serializer->deserialize($jsonText, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
                $errors = $validator->validate($newUser);
                $newPassword = $newUser->getPassword();
                if (!empty($newPassword)) {
                    $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
                    $newUser -> setPassword($encodedPassword);
                } else {
                    $newUser -> setPassword($oldPassword);
                }

                if(count($errors) > 0) {
                    return $this->json($errors, 400);
                }
                $em->flush();
                return $this->json($newUser, 201, [], ['groups' => 'apiV0_user']);

            } catch (NotEncodableValueException $e) {
                return $this->json([
                    'status' => 400,
                    'message'=>$e->getMessage()
                ], 400);
            }
                

                // On retourne une 201 avec l'objet qu'on vient de créer
                // On instancie un serializer en lui précisant un normalizer adapté aux objets PHP
                $serializer = new Serializer([$normalizer]);
                // Parce qu'on a précisé le normalizer, on peut normaliser selon un groupe
                $normalizedUser = $serializer->normalize($user, null, ['groups' => 'apiV0_user']);
                return $this->json($normalizedUser, 201);
        } else {
            return $this->json([
                'status' => 400,
                'message'=>"Cet utilisateur n'existe pas"
            ], 400);
        }

        
    }

     /**
     * @Route("api/v0/users/{id}/upload", name="api_user_upload", methods={"PUT"})
     */
    public function uploadAvatar(UserPasswordEncoderInterface $passwordEncoder, Request $request, User $user, UserRepository $userRepository, $id, ObjectNormalizer $normalizer): Response
    {
        $user = $userRepository->find($id);
        /*       $oldAvatar = $user->getAvatar();
        dd($oldAvatar); */
        
        
        
        if (!empty($user)) {
            $form = $this->createForm(UploadType::class, $user);

            // recuperation
            $avatar = $request->files->get('file'); 
            $form->submit($avatar);
           
            if ($form->isValid()) {
                // On génère un nouveau nom de fichier
                $fichier = '/uploads/'.md5(uniqid()).'.'.$avatar->guessExtension();
          
            
                // On copie le fichier dans le dossier uploads
                $avatar->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
            
                // On crée l'image dans la base de données
                $user->setAvatar($fichier);
        
                // On envoie ce tableau à la méthode submit()
                  
        
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
             }
          
            return $this->json($fichier, 201);
            
        } else {
            return $this->json([
                'status' => 400,
                'message'=>"Vous ne pouvez pas effectuer cette action"
            ], 400);
        }
    }

    /**
     * @Route("api/v0/users/{id}/profil", name="api_user_profil", methods="GET")
     */
    public function show(User $user, $id, UserRepository $userRepository, ObjectNormalizer $normalizer): Response
    {
        $user = $userRepository->find($id);

        if (!empty($user)) {
            // On instancie un serializer en lui précisant un normalizer adapté aux objets PHP
            $serializer = new Serializer([$normalizer]);
            // Parce qu'on a précisé le normalizer, on peut normaliser selon un groupe
            $normalizedUsers = $serializer->normalize($user, null, ['groups' => 'apiV0_user']);

            return $this->json($normalizedUsers);

        } else {
            return $this->json([
                'status' => 400,
                'message'=>"Vous ne pouvez pas effectuer cette action"
            ], 400);
        }
    }

    /**
     * @Route("api/v0/users/{id}/delete", name="api_user_delete", methods="DELETE")
     */
    public function delete(User $user, UserRepository $userRepository, ObjectNormalizer $normalizer): Response
    {
        if (!empty ($user)){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
                
            return $this->json(200);
        } else {
            return $this->json([
                'status' => 400,
                'message'=>"Vous ne pouvez pas effectuer cette action"
            ], 400);
        }
    }
}