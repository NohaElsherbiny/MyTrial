<?php

namespace Autolina\SampleBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Autolina\SampleBundle\Entity\MailRepository;
use Autolina\SampleBundle\Entity\Mail;
use Doctrine\ORM\PersistentCollection;


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

    public function editAction(Request $request)
    {
    	$data = $request->request->get('request');
    	$id = $request->get('id');
    	$em = $this->getDoctrine()->getManager();
		$mail = $em->getRepository('AutolinaSampleBundle:Mail')
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
			$this->forward('AutolinaSampleBundle:Default:update');
			return new Response($successString);
		}
    }
    
    public function updateAction(Request $request)
    {
   	    $get = $request->query->all();
   	    /* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	 */
    	$columns = array( 'email', 'id' );
    	$get['columns'] = &$columns;
    	$get['iDisplayLength'] = 5;
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$rResult = $em->getRepository('AutolinaSampleBundle:Mail')->ajaxTable($get, true)->getArrayResult();
    	
    	/* Data set length after filtering */
    	$iFilteredTotal = count($rResult);

    	/*
     	 * Output
     	 */
    	$output = array(
      		"sEcho" => intval($get['sEcho']),
      		"iTotalRecords" => $em->getRepository('AutolinaSampleBundle:Mail')->getCount(),
      		"iTotalDisplayRecords" => $em->getRepository('AutolinaSampleBundle:Mail')->getFilteredCount($get),
      		"aaData" => array()
    	);	
    	foreach($rResult as $aRow){
   			$row = array();
   			$toData= array();
   			for ( $i=0 ; $i<count($columns) ; $i++ ){
       			if ( $columns[$i] == "version" ){
       				/* Special output formatting for 'version' column */
       				$row[] = ($aRow[ $columns[$i] ]=="0") ? '-' : $aRow[ $columns[$i] ];
       			}elseif ( $columns[$i] != ' ' ){
       	  			/* General output */
       				$row[] = $aRow[ $columns[$i] ];
       				for ($i=0; $i<2; $i++){
    			 		if($i==0)
    			 			$toData[]=$row[$i];
    			 		elseif ($i==1)
    			 			$toData[]='<td class="table-action-hide">
                          <a href="#" id="editRow" data-toggle="modal" data-target=".make-modal-lg" data='.$row[0].' data-id='.$aRow[ $columns[1]].' style="opacity: 0;"><i class="fa fa-pencil"></i></a>
                          <a href="#" class="delete-row" id="delRow" data-type="Edit" data-id='.$aRow[ $columns[1]].' style="opacity: 1;"><i class="fa fa-trash-o"></i></a>
                        </td>';
    			 	}
    			}
   			}
   			//print_r($toData);
       		$output['aaData'][] = $toData;
       		//$output['aaData'][] = $row;
   		}

   		unset($rResult);
    	return new Response(
      		json_encode($output)
    	);
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
			$this->forward('AutolinaSampleBundle:Default:update');
			return new Response($successString);
		}
    }
}
