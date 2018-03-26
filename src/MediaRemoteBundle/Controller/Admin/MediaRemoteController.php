<?php

namespace MediaRemoteBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use MediaRemoteBundle\Entity\MediaRemote;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class MediaRemoteController extends Controller
{

    /**
     * @Route("/remote/{remote_name}/{media_name}/switch",
     * name="toogle",
     * requirements={
     * "remote_name"="^[A-Z]{1}[a-z]{2,15}$",
     * "media_name"="^[A-Z]{1}[A-Za-z]{2,15}$"
     * },
     * methods ="GET"
     * )
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function toogleAction(Request $request)
    {
        $session = $this->get("session"); //ouverture d'une sessions: comme le this init() (session start, token... tout)
        $session->getFlashBag()->add("toogle",true); // marque de passage flash sur la page d'apres uniquement
        
        
        $key = md5($request->get("remote_name"));
        $this->get("cache.app")->deleteItem(($key)); //nettoyage du cache serveur
        
        //passage par la methode créée pour cette requette (dans le repo cutomisé)
        $mediaRemote= $this->getDoctrine()
        ->getManager()
        ->getRepository(MediaRemote::class)
        ->findByRemoteNameAndMediaName($request->get("remote_name"),$request->get("media_name")); //accés a ma methode de requette perso
        
        $mediaRemote->setMediaRemoteActive(!$mediaRemote->getMediaRemoteActive());
        
        $this->getDoctrine()
        ->getManager()
        ->flush();

        
        return $this->redirectToRoute("remote_get", [
            "remote_name" => $mediaRemote->getRemote()->getRemoteName()]);
    }

}
