<?php

namespace Autolina\BlacklistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Autolina\BlacklistBundle\Entity\BlacklistRepository;
use Autolina\BlacklistBundle\Entity\Blacklist;
use Doctrine\ORM\PersistentCollection;

class DefaultController extends Controller
{
    public function BlacklistAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$blacklist = $em->getRepository('AutolinaBlacklistBundle:Blacklist')
						->findOnebyOne();	
        return $this->render('AutolinaBlacklistBundle:Default:blacklist.html.twig', array('mails' => $blacklist));
    }

    public function updateAction(Request $request){
    	//isn't complete UPDATEME
    	$Columns = array( 'id','email' );
    	print_r(request)

    	return new Response();
    }
    public function delAction(Request $request)
    {
    	$data = $request->request->get('request');
    	$id = $request->get('id');
    	$em = $this->getDoctrine()->getManager();
		$mail = $em->getRepository('AutolinaBlacklistBundle:Blacklist')
						->find($id);	
        $em->remove($mail);
        $em->flush();
        return new Response();
    }

    public function editAction(Request $request)
    {
    	$data = $request->request->get('request');
    	$id = $request->get('id');
    	$em = $this->getDoctrine()->getManager();
		$mail = $em->getRepository('AutolinaBlacklistBundle:Blacklist')
						->find($id);	

     	$edittedmail = $request->get('mail');
    	$mail->setEmail($edittedmail);

    	$validator = $this->get('validator');
		$errors = $validator->validate($mail);
		if(count($errors) > 0){
			$errorsString = (string) $errors;
			return new Response($errorsString);
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->persist($mail);
			$em->flush();
			$successString = $id;
			return new Response($successString);
		}
    }
    public function addAction(Request $request)
    {
    	$mail = $request->get('mail');
    	$newmail = new Blacklist();
    	$newmail->setEmail($mail);
    	$validator = $this->get('validator');
		$errors = $validator->validate($newmail);
		if(count($errors) > 0){
			$errorsString = (string) $errors;
			return new Response($errorsString);
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->persist($newmail);
			$em->flush();
			$id=$newmail->getId();
			$successString = $id;
			return new Response($successString);
		}
    }
}
