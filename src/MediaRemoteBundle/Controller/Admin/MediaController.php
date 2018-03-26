<?php

namespace MediaRemoteBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MediaRemoteBundle\Form\MediaType;
use MediaRemoteBundle\Entity\MediaRemote;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use MediaRemoteBundle\Entity\MediaRemoteFile;

class MediaController extends Controller
{
    /**
     * @Route("/media/{media_name}", name="media")
     */
    public function indexAction(Request $request)
    {

        $mediaRemote = $this->getMediaRemote($request->get("media_name")); //on recherche la donnée dans SGBDR
        
   
        /**
         * *****contruction formulaire coté back *******
         */
        $form = $this->get("form.factory")-> // création d'un formulaire du service form.factory
        create(MediaType::class, // dans la classe généré précédement (MediaType)
            $mediaRemote[0]->getMedia()); // on lui passe toujours l'objet complet
        
            $mediaremoteid = $mediaRemote[0]->getMediaRemoteId();
            
            $files = $this->getDoctrine()
            ->getManager()
            ->getRepository(MediaRemoteFile::class)
            ->findByMediaRemote($mediaremoteid); // le media sera extrait dans findByMediaName
            
           // dump($files);
            
        //  exit;  
        return $this->render('@MediaRemote/Media/index.html.twig', array(
            "mediaRemote" => $mediaRemote,
            "files" => $files,
            "form" => $form->createView()
        ));
    }
    
    private function getMediaRemote(string $name): array
    {
        
        if (! ($mediaRemote = $this->getDoctrine()
            ->getManager()
            ->getRepository(MediaRemote::class)
            ->findByMediaName($name))) // le media sera extrait dans findByMediaName
            
        {
            throw new NotFoundHttpException("media not found");
        }
        return $mediaRemote;
    }
    
    /**
     * @Route("/media/{media_name}",
     * name="media_post",
     * methods ="POST"
     * )
     */
    
    public function postAction(Request $request) // dans request on retrouve tout
    {   /*******methode traitement, traité en POST **************/
        

        $mediaRemote = $this->getMediaRemote($request->get("media_name")); //on recherche la donnée dans SGBDR
        
        /**
         * *****contruction formulaire coté back *******
         */
        $form = $this->get("form.factory")-> // création d'un formulaire du service form.factory
        create(MediaType::class, // dans la classe généré précédement (MediaType)
            $mediaRemote[0]->getMedia()); // on lui passe toujours l'objet complet
            
            $form->handleRequest($request); // pour que le formulaire se valide et se traite
            
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    // nous sommes dans $mediaRemote, il va modifier les données en cours dans la BDD
                    $this->getDoctrine()
                    ->getManager()
                    ->flush();
                    $response = $this->redirectToRoute("media", [ // avec remote_get on accède à getAction()
                        "media_name" => $mediaRemote[0]->getMeia()->getMediaName() // celui qu'on vient de modifier et envoyer en bdd
                    ]);
                    
                    //$response->setEtag(null); //netoyage du cache client
                    return $response;
                    
                } catch (\Error $e) {
                    $form->addError(new FormError("name.exists")); // ajoute le ->add au formulaire en cas d'erreur
                }
            }
            
            /**
             * *****envoi a la vue et ses arguement*******
             */
            return $this->render('@MediaRemote/Media/index.html.twig', array(
                "mediaRemote" => $mediaRemote,
                "form" => $form->createView()
            ));
    }
    
    /**
     * @Route("/remote/{remote_name}/{media_name}/detail",
     * name="detail",
     * requirements={
     * "remote_name"="^[A-Z]{1}[a-z]{2,15}$",
     * "media_name"="^[A-Z]{1}[A-Za-z]{2,15}$"
     * },
     * methods ="GET"
     * )
     */
    public function toogleAction(Request $request)
    {
         //passage par la methode créée pour cette requette (dans le repo cutomisé)
//          $mediaRemoteid= $this->getDoctrine()
//          ->getManager()
//          ->getRepository(MediaRemote::class)
//          ->findByRemoteNameAndMediaName($request->get("remote_name"),$request->get("media_name"));
         
//          $mediaRemote = $this->getMediaRemote($request->get("media_name")); //on recherche la donnée dans SGBDR
         
         
//          /**
//           * *****contruction formulaire coté back *******
//           */
//          $form = $this->get("form.factory")-> // création d'un formulaire du service form.factory
//          create(MediaType::class, // dans la classe généré précédement (MediaType)
//              $mediaRemote[0]->getMedia()); // on lui passe toujours l'objet complet
             
//              $mediaremoteid = $mediaRemote[0]->getMediaRemoteId();
             
//              $files = $this->getDoctrine()
//              ->getManager()
//              ->getRepository(MediaRemoteFile::class)
//              ->findByMediaRemote($mediaremoteid); // le media sera extrait dans findByMediaName
             
//              // dump($files);
             
//              //  exit;
//              return $this->render('@MediaRemote/Media/index.html.twig', array(
//                  "mediaRemote" => $mediaRemote,
//                  "files" => $files,
//                  "form" => $form->createView()
//              ));

    }

}
