<?php

namespace App\Controller\PropertyManager;

use App\Entity\Document;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/gestion/document")
 */
class DocumentManagerController extends AbstractController {

    /**
     * @Route("/{id}", name="document.manager.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Document $document): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete'.$document->getId(), $data['_token'])) {

            // consolelog($document);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();

            return new JsonResponse(['success' => 1]);
        }
        
        return new JsonResponse(['error' => 'Token invalide'], 400);
    }



}