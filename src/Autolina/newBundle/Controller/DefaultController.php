<?php

namespace Autolina\newBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Autolina\newBundle\Entity\Black_listRepository;
use Autolina\newBundle\Entity\Black_list;
use Doctrine\ORM\PersistentCollection;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AutolinanewBundle:Default:index.html.twig', array('name' => $name));
    }

    public function blacklistAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$blacklist = $em->getRepository('AutolinanewBundle:Black_list')
						->findOnebyOne();	
		
        return $this->render('AutolinanewBundle:Default:blacklist.html.twig',array("mails"=>$blacklist));
    }
}
