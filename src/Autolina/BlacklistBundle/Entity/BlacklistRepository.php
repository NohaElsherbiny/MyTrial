<?php

namespace Autolina\BlacklistBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BlacklistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlacklistRepository extends EntityRepository
{
	public function getSorted($sSortDir_0)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT e FROM AutolinaBlacklistBundle:Blacklist e ORDER BY e.email '.$sSortDir_0.''
			)
			->getResult();
	}
	public function getPage($iDisplayStart, $iDisplayLength, $sSortDir_0,$bSearchable_0,$sSearch){
		if($bSearchable_0 == "true" ){
			if($sSearch != ""){
				return $this->getEntityManager()
					->createQuery(
						'SELECT e FROM AutolinaBlacklistBundle:Blacklist e WHERE e.email LIKE \'%'.$sSearch.'%\' ORDER BY e.email '.$sSortDir_0.''
					)
					->setFirstResult($iDisplayStart)
        			->setMaxResults($iDisplayLength)
					->getResult();
			}
		}
		return $this->getEntityManager()
			->createQuery(
				'SELECT e FROM AutolinaBlacklistBundle:Blacklist e ORDER BY e.email '.$sSortDir_0.''
			)
			->setFirstResult($iDisplayStart)
        	->setMaxResults($iDisplayLength)
			->getResult();
	}
  	public function getCount($sSortDir_0){
  		$List = $this->getSorted($sSortDir_0);
    	$count = count($List);
    	return $count;
  	}

  	public function getFilteredTotal($bSearchable_0,$sSearch,$sSortDir_0){
  		$count=0;
		if($bSearchable_0 == "true" ){
			if($sSearch != ""){
				$Q= $this->getEntityManager()
					->createQuery(
						'SELECT e FROM AutolinaBlacklistBundle:Blacklist e WHERE e.email LIKE \'%'.$sSearch.'%\'' 
					)
					->getResult();
				$count= count($Q);
			}
			else{
				$count= $this->getCount($sSortDir_0);
			} 
		}
		else {
			$count= $this->getCount($sSortDir_0);
		}
		return $count;
  	}
}
