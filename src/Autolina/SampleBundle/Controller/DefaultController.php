<?php

namespace Autolina\SampleBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Autolina\SampleBundle\Entity\MailRepository;
use Autolina\SampleBundle\Entity\Mail;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AutolinaSampleBundle:Default:index.html.twig', array('name' => $name));
    }

 	public function samplePageAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$mails = $em->getRepository('AutolinaSampleBundle:Mail')
						->findOnebyOne();	
		
        return $this->render('AutolinaSampleBundle:Default:samplePage.html.twig',array("mails"=>$mails));
    }

    public function deleteAction(Request $request)
    {
    	$data = $request->request->get('request');
    	$id = $request->get('id');
    	
    	$em = $this->getDoctrine()->getManager();
		$mail = $em->getRepository('AutolinaSampleBundle:Mail')
						->find($id);	
        $em->remove($mail);
        $em->flush();
        return new Response();
    }
       
    public function addAction(Request $request)
    {
    	$mail = $request->get('mail');
    	$f = new Mail();
    	$f->setEmail($mail);
    	$validator = $this->get('validator');
		$errors = $validator->validate($f);
		if(count($errors) > 0){
			$errorsString = (string) $errors;
			return new Response($errorsString);
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->persist($f);
			$em->flush();
			$id=$f->getId();
			$successString = $id;
			return new Response($successString);
		}
    	
    }
}
