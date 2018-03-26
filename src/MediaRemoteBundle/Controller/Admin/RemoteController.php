<?php
namespace MediaRemoteBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // pour la route
use Symfony\Component\HttpFoundation\Request;
use MediaRemoteBundle\Entity\Remote;
use MediaRemoteBundle\Entity\MediaRemote;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MediaRemoteBundle\Form\RemoteType;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security; // pour le @Security

use MediaRemoteBundle\Entity\Media;
use Symfony\Component\HttpFoundation\Response;

class RemoteController extends Controller
{

    private function getMediaRemote(string $name): array
    {

        if (! ($mediaRemote = $this->getDoctrine()
            ->getManager()
            ->getRepository(MediaRemote::class)
            ->findByRemoteName($name))) // le remote sera extrait dans findByRemoteName

        {
            throw new NotFoundHttpException("remote not found");
        }
        return $mediaRemote;
    }

    /**
     * @Route("/remote",
     * name="remote")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {  

        // je veux trouver un remote par defaut et faire une redirection
        return $this->redirectToRoute("remote_get", [ // avec remote_get on accède à getAction()
            "remote_name" => $this->getDoctrine()
                ->getManager()
                ->getRepository(MediaRemote::class)
                ->findDefaultRemoteName()
        ]); // redirection vers une methode correspondant au name route
    }

    /**
     * @Route("/remote/{remote_name}",
     * name="remote_get",
     * requirements={"remote_name"="^[A-Z]{1}[a-z]{2,15}$"},
     * methods ="GET"
     * )
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getAction(Request $request) // dans request on retrouve tout
    {
      
        //cache client
        $etag=md5($request->getUri());
        if( !$this->get("session")->getFlashBag()->get("toogle") //si pas passer par la methode toogle (qui avait enregistré toogle dans session au passage)
            &&'"'.$etag.'"' === current($request->getETags())){ //que l'etag match
            $response = new Response();
            $response->setNotModified(); // renvoi un 304 et ses regles associées, plus besoin de renvoyer le render en bas
            return $response;
                }
                
        //cache serveur
        $key = md5($request->get("remote_name"));//création de la clef id d'un item, nom en fonction du remote name recu et md5 pour normaliser et eviter certains caractère
        $cache = $this->get("cache.app"); //creation du cache (pool)
        $item = $cache->getItem($key); //création de son item (fichier dans le cache (dans le pool)) en fonction d'une clé 

        if(!$item->isHit()){ //si l'item n'existe pas      
            dump("pas de cache");
            $mediaRemote = $this->getMediaRemote($request->get("remote_name")); //on recherche la donnée dans SGBDR
            $item->set($mediaRemote); //on met la donnée dans l'item
            $cache->save($item); // on sauve le cache avec l'item
       
        }else{//sinon on récupère la donnée depuis l'item existant
             $mediaRemote = $item->get();
            
            //reveiller les proxy morts
            foreach ($mediaRemote as $value){
                $value->setMedia(
                    $this->getDoctrine()->getManager()->merge($value->getMedia()) //on reveil le proxy de media
                                );
            }
            dump("du cache");
        }

        /**
         * *****contruction formulaire coté back *******
         */
        $form = $this->get("form.factory")-> // création d'un formulaire du service form.factory
            create(RemoteType::class, // dans la classe généré précédement (RemoteType)
                $mediaRemote[0]->getRemote()); // on lui passe toujours l'objet complet
                                         
        /**
         * *****envoi a la vue et ses arguement*******
         */
        $response= $this->render('@MediaRemote/Remote/index.html.twig', array(
            "mediaRemote" => $mediaRemote, // on envoi les valeurs a la vue
            "form" => $form->createView() // creation du formulaire a la vue
        ));
        
        $response->setEtag($etag); //enregistrement du etag pour le tester par la suite 
        
        return $response;
        
    }

    /**
     * @Route("/remote/{remote_name}",
     * name="remote_post",
     * requirements={"remote_name"="^[A-Z]{1}[a-z]{2,15}$"},
     * methods ="POST"
     * )
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */

    public function postAction(Request $request) // dans request on retrouve tout
    {   /*******methode traitement, traité en POST **************/
        
//         dump("entre post");
//         exit();
        

        
        $key = md5($request->get("remote_name"));
        $this->get("cache.app")->deleteItem(($key)); //nettoyage du cache serveur
        
        $mediaRemote = $this->getMediaRemote($request->get("remote_name")); //recupère tout l'objet correspondant
   
        /**
         * *****contruction formulaire coté back *******
         */
        $form = $this->get("form.factory")-> // création d'un formulaire du service form.factory
                create(RemoteType::class, // dans la classe généré précédement (RemoteType)
                $mediaRemote[0]->getRemote()); // on lui passe toujours l'objet complet // on prend [0] car c'est toujours le meme objet
        
        $form->handleRequest($request); // pour que le formulaire se valide et se traite
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // nous sommes dans $mediaRemote, il va modifier les données en cours dans la BDD
                $this->getDoctrine()
                    ->getManager()
                    ->flush();
                $response = $this->redirectToRoute("remote_get", [ // avec remote_get on accède à getAction()
                    "remote_name" => $mediaRemote[0]->getRemote()->getRemoteName() // celui qu'on vient de modifier et envoyer en bdd
                ]); 
                
                $response->setEtag(null); //netoyage du cache client
                return $response;
                
            } catch (\Error $e) {
                $form->addError(new FormError("name.exists")); // ajoute le ->add au formulaire en cas d'erreur
            }
        }
        
        /**
         * *****envoi a la vue et ses arguement*******
         */
        return $this->render('@MediaRemote/Remote/index.html.twig', array(
            "mediaRemote" => $mediaRemote, // on envoi les valeurs a la vue
            "form" => $form->createView() // creation du formulaire a la vue
        ));
    }
    

}

