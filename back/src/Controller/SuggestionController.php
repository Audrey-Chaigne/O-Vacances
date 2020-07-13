<?php

namespace App\Controller;

use App\Entity\Suggestion;
use App\Form\SuggestionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\SuggestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

class SuggestionController extends AbstractController
{
    /**
         * @Route("/api/v0/trips/{id}/suggestions", name="api_v0_suggestions_list", methods="GET")
         */
    public function list(SuggestionRepository $suggestionRepository, ObjectNormalizer $normalizer, $id)
    {
        $suggestions = $suggestionRepository->find($id);

        // On instancie un serializer en lui précisant un normalizer adapté aux objets PHP
        $serializer = new Serializer([$normalizer]);
        // Parce qu'on a précisé le normalizer, on peut normaliser selon un groupe
        $normalizedSuggestions = $serializer->normalize($suggestions, null, ['groups' => 'apiV0_list']);

        // dd($normalizedAnimes);

        return $this->json($normalizedSuggestions);
    }

    /**
     * @Route("api/v0/trips/{id}/suggestions/new", name="api_v0_suggestions_new", methods="POST")
     */
    public function new(Request $request, ObjectNormalizer $normalizer)
    {
        // Je créer un objet vide qui sera géré (et rempli) par le formulaire
        $newSuggestion = new Suggestion();
        // je crée un ofrmulaire a partir de mon modèle (du Type) AnimeCategoryType
        // je fourni en meme temps a ce nouveau formulaire l'objet qu'il doit gérer
        $form = $this->createForm(SuggestionType::class, $newSuggestion, ['csrf_protection' => false]);
        $jsonText = $request->getContent();
       
        $jsonArray = json_decode($jsonText, true);
      
        $form->submit($jsonArray);

        if ($form->isSubmitted() && $form->isValid()) {
            // on traite le formulaire
            // par exemple on l'envoi dans la BDD
            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($newSuggestion);
            $entitymanager->flush();
          
            $serializer = new Serializer([$normalizer]);

            $normalizerSuggestion = $serializer->normalize($newSuggestion, null, ['groups'=> 'apiV0_Suggestion']);
           
            return $this->json($normalizerSuggestion, 201);
        }
        return $this->json((string) $form->getErrors(true, false), 400);
    }

    /**
     * @Route("api/v0/trips/{id}/suggestions/update", name="api_v0_suggestions_update", methods="PATCH")
     */
    public function update(Request $request, Suggestion $suggestion)
    {
        $form = $this->createForm(SuggestionType::class, $suggestion);
        // je demande au form de verifier si des données ont été soumises
        $form->handleRequest($request);
        // Si des données ont été soumises ET qu'elles sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // on traite le formulaire
            // par exemple on l'envoi dans la BDD
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            // puis on redirige sur une autre page, sinon le type va re tomber sur le form en pensant qu'il n'a pas marcher
            $this->addFlash("succes", "");
            return $this->redirectToRoute('', ["id" => $suggestion->getId()]);
        }
    }

    /**
     * @Route("api/v0/trips/{id}/suggestions/delete", name="api_v0_suggestions_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Suggestion $suggestion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suggestion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($suggestion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('channel_index');
    }
}