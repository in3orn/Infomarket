<?php

namespace AppBundle\Logic\Common\Product\ItemsUpdater;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Logic\Common\Product\ItemUpdater\ItemUpdater;

class ItemsUpdater {

	/**
	 *
	 * @var ObjectManager
	 */
	private $em;

	/**
	 *
	 * @var ItemUpdater
	 */
	private $itemUpdater;

	/**
	 *
	 * @var integer
	 */
	private $duration;
	
	/**
	 * 
	 * @var integer
	 */
	private $packSize;

	public function __construct(ObjectManager $em, ItemUpdater $itemUpdater, $duration, $packSize) {
		$this->em = $em;
		$this->itemUpdater = $itemUpdater;
		$this->duration = $duration;
		$this->packSize = $packSize;
	}

	public function update(\DateTime $start, array $items) {
		$total = count($items);
		$done = 0;
		
		foreach ($items as $item) {
			$this->itemUpdater->update($item);
			$done ++;
			
			if ($this->shouldFinish($start)) {
				break;
			}
			if ($this->shouldFlush($done)) {
				$this->em->flush();
				$this->em->clear();
			}
		}
		
		$this->em->flush();
		$this->em->clear();
		return ['total' => $total, 'done' => $done];
	}

	private function shouldFinish(\DateTime $start) {
		$end = new \DateTime();
		$interval = $end->getTimestamp() - $start->getTimestamp();
		return $interval > $this->duration;
	}
	
	private function shouldFlush($count) {
		return $count % $this->packSize == 0;
	}
}