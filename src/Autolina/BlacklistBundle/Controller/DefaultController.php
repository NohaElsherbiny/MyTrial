<?php

namespace Autolina\BlacklistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Autolina\BlacklistBundle\Entity\BlacklistRepository;
use Autolina\BlacklistBundle\Entity\Blacklist;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\Session\Session;
class DefaultController extends Controller
{
    /*public function BlacklistAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$blacklist = $em->getRepository('AutolinaBlacklistBundle:Blacklist')
						->getSorted("asc");	
		return $this->render('AutolinaBlacklistBundle:Default:blacklist.html.twig', array('mails' => $blacklist));
    }*/
    public function BlacklistAction()
    {
    	$token = $this->get('form.csrf_provider')->generateCsrfToken('');
        return $this->render('AutolinaBlacklistBundle:Default:blacklist.html.twig',array ('token'=>$token));
    }
    public function updateAction(Request $request){
    	
    	$em = $this->getDoctrine()->getManager();
    	$blacklist = $em->getRepository('AutolinaBlacklistBundle:Blacklist')
						->getSorted("asc");	
		$get = $request->query->all();

    	$Columns = array( 'id','email' );
    	$iColumns = $get['iColumns'];
    	$iDisplayStart = $get['iDisplayStart'];
    	$iDisplayLength = $get['iDisplayLength'];
    	$bSearchable_0 = $get['bSearchable_0'];
    	$sSearch = $get['sSearch'];
    	$sSortDir_0 = $get['sSortDir_0'];

    	$iTotal = $em->getRepository('AutolinaBlacklistBundle:Blacklist')
    				 ->getCount($sSortDir_0);
    	$iFilteredTotal = $em->getRepository('AutolinaBlacklistBundle:Blacklist')
    						 ->getFilteredTotal($bSearchable_0,$sSearch,$sSortDir_0);
    	$output = array(
        	"sEcho" => (int)$get['sEcho'],
        	"iTotalRecords" => $iTotal,
        	"iTotalDisplayRecords" => $iFilteredTotal,
        	"aaData" => $this-> getBlacklist($iDisplayStart, $iDisplayLength, $sSortDir_0,$bSearchable_0,$sSearch)
    	);
    	return new Response(
      		json_encode($output)
    	);
    }

    public function getBlacklist($iDisplayStart, $iDisplayLength, $sSortDir_0,$bSearchable_0,$sSearch){
    	$em = $this->getDoctrine()->getManager();
    	$Page = $em->getRepository('AutolinaBlacklistBundle:Blacklist')
				   ->getPage($iDisplayStart, $iDisplayLength, $sSortDir_0,$bSearchable_0,$sSearch);
		$data = array();
		$id = 0;
		foreach($Page as $Mail){
			$id = $Mail->getId();
			$email=$Mail->getEmail();
			$data[]=array($email,
    				'<td class="table-action-hide">
                     <a href="#" data-toggle="modal" data-target=".make-modal-lg" data-type="editRow" data-email="'.$email.'" data-id="'.$id.'" style="opacity: 1;"><i class="fa fa-pencil"></i></a>
                     <a href="javascript:void(0)" class="delete-row" data-type="delRow"  data-id="'.$id.'" style="opacity: 1;"><i class="fa fa-trash-o"></i></a>
                     </td>');
    	}
		return $data; 
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
        $token = $request->get('token');
        $intention="";
        if(!$this->get('form.csrf_provider')->isCsrfTokenValid($intention,$token)){
            //$this->get('session')->setFlash('notice', 'Woops! Token is invalid');

            return $this->redirect('invalid');
        }
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
    public function invalidAction(){
        return $this->render('AutolinaBlacklistBundle:Default:invalid.html.twig');
    }
}
