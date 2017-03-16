<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Factory\Admin\ErrorFactory;
use AppBundle\Factory\Admin\Import\NewsletterUser\ImportErrorFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Factory\Admin\Import\NewsletterUser\PreparedEntryFactory;

class PreparedEntryFactoryTest extends WebTestCase
{
	/** 
	 * 
	 * @var ImportErrorFactory $errorFactory
	 */
	protected $errorFactory;
	
	/**
	 * 
	 * @var PreparedEntryFactory $entryFactory
	 */
	protected $entryFactory;
	
	
	protected function setUp()
	{
		self::bootKernel();
	
		$translator = static::$kernel->getContainer()->get('translator');
		$this->errorFactory = new ImportErrorFactory($translator);
		$this->entryFactory = new PreparedEntryFactory($this->errorFactory);
	}
	
	public function testGetEntriesSubscribed01() { $this->getRowEntries(['test@wp.pl'], array(['mail' => 'test@wp.pl', 'subscribed' => true])); }
	public function testGetEntriesSubscribed02() { $this->getRowEntries(['test@wp.pl', 0], array(['mail' => 'test@wp.pl', 'subscribed' => true])); }
	public function testGetEntriesSubscribed03() { $this->getRowEntries(['test@wp.pl', '0'], array(['mail' => 'test@wp.pl', 'subscribed' => true])); }
	public function testGetEntriesSubscribed04() { $this->getRowEntries(['test@wp.pl', '-'], array(['mail' => 'test@wp.pl', 'subscribed' => true])); }
	public function testGetEntriesSubscribed05() { $this->getRowEntries(['test@wp.pl', 'false'], array(['mail' => 'test@wp.pl', 'subscribed' => true])); }
	
	public function testGetEntriesSubscribedList01() { $this->getRowEntries(['test@wp.pl;test@wp.pl'], array(
			['mail' => 'test@wp.pl', 'subscribed' => true], 
			['mail' => 'test@wp.pl', 'subscribed' => true]
	)); }
	
	public function testGetEntriesSubscribedList02() { $this->getRowEntries(['test1@wp.pl;test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => true],
			['mail' => 'test2@wp.pl', 'subscribed' => true]
	)); }
	
	public function testGetEntriesSubscribedList03() { $this->getRowEntries(['test1@wp.pl; test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => true],
			['mail' => 'test2@wp.pl', 'subscribed' => true]
	)); }
	
			

	public function testGetEntriesUnsubscribed01() { $this->getRowEntries(['test@wp.pl', 1], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesUnsubscribed02() { $this->getRowEntries(['test@wp.pl', '1'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesUnsubscribed03() { $this->getRowEntries(['test@wp.pl', '+'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesUnsubscribed04() { $this->getRowEntries(['test@wp.pl', 'true'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesUnsubscribed05() { $this->getRowEntries(['test@wp.pl', 'asd'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	
	public function testGetEntriesUnsubscribedList01() { $this->getRowEntries(['test@wp.pl;test@wp.pl', '1'], array(
			['mail' => 'test@wp.pl', 'subscribed' => false],
			['mail' => 'test@wp.pl', 'subscribed' => false]
	)); }
	
	public function testGetEntriesRezygnacja01() { $this->getRowEntries(['rezygnacja_test@wp.pl'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesRezygnacja02() { $this->getRowEntries(['rezygnacja-test@wp.pl'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesRezygnacja03() { $this->getRowEntries(['rezygnacja test@wp.pl'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	
	public function testGetEntriesRezygnacja11() { $this->getRowEntries(['test@wp.pl_rezygnacja'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesRezygnacja12() { $this->getRowEntries(['test@wp.pl-rezygnacja'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesRezygnacja13() { $this->getRowEntries(['test@wp.pl rezygnacja'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	
	public function testGetEntriesRez01() { $this->getRowEntries(['rez_test@wp.pl'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesRez02() { $this->getRowEntries(['rez-test@wp.pl'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesRez03() { $this->getRowEntries(['rez test@wp.pl'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	
	public function testGetEntriesRez11() { $this->getRowEntries(['test@wp.pl_rez'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesRez12() { $this->getRowEntries(['test@wp.pl-rez'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	public function testGetEntriesRez13() { $this->getRowEntries(['test@wp.pl rez'], array(['mail' => 'test@wp.pl', 'subscribed' => false])); }
	
	
	public function testGetEntriesRezList01() { $this->getRowEntries(['rez_test1@wp.pl;test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => true]
	)); }
	
	public function testGetEntriesRezList02() { $this->getRowEntries(['rez_test1@wp.pl; test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => true]
	)); }
	
	public function testGetEntriesRezList03() { $this->getRowEntries(['rez_test1@wp.pl;rez_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	
	public function testGetEntriesRezList04() { $this->getRowEntries(['rez_test1@wp.pl; rez_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	
	public function testGetEntriesRezList05() { $this->getRowEntries(['rez_test1@wp.pl; rezygnacja_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	
	public function testGetEntriesRezList06() { $this->getRowEntries(['test1@wp.pl_rez; rezygnacja_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	
	public function testGetEntriesRezList07() { $this->getRowEntries(['test1@wp.pl_rezygnacja; rezygnacja_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	
	public function testGetEntriesRezList08() { $this->getRowEntries(['test1@wp.pl_rezygnacja; test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => true]
	)); }
	
	public function testGetEntriesRezList09() { $this->getRowEntries(['test1@wp.pl; rezygnacja_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => true],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	
	
	
	public function testGetEntriesColon01() { $this->getRowEntries(['rez_test1@wp.pl,test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => true]
	)); }
	
	public function testGetEntriesColon02() { $this->getRowEntries(['rez_test1@wp.pl, test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => false],
			['mail' => 'test2@wp.pl', 'subscribed' => true]
	)); }
	
	public function testGetEntriesColon03() { $this->getRowEntries(['test1@wp.pl, rezygnacja_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => true],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	
	
	
	public function testGetEntriesDoubleColon() { $this->getRowEntries(['test1@wp.pl,, rezygnacja_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => true],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	
	public function testGetEntriesDoubleSemicolon() { $this->getRowEntries(['test1@wp.pl;; rezygnacja_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl', 'subscribed' => true],
			['mail' => 'test2@wp.pl', 'subscribed' => false]
	)); }
	

	
	public function testGetEntriesSpace01() { $this->getRowEntries(['test1@wp.pl test2@wp.pl'], array(
			['mail' => 'test1@wp.pl test2@wp.pl', 'subscribed' => true]
	)); }
	
	public function testGetEntriesSpace02() { $this->getRowEntries(['test1@wp.pl rezygnacja_test2@wp.pl'], array(
			['mail' => 'test1@wp.pl rezygnacja_test2@wp.pl', 'subscribed' => true]
	)); }
	
    protected function getRowEntries($fileEntry, $expected)
    {	
    	$result = $this->entryFactory->getRowEntries($fileEntry, 9);
    	
    	$this->assertEquals(count($expected), count($result));
    	
    	for ($i = 0; $i < count($result); $i++) {
    		$expectedEntry = $expected[$i];
    		$resultEntry = $result[$i];
    		
    		$this->assertEquals($expectedEntry['mail'], $resultEntry['mail']);
    		$this->assertEquals($expectedEntry['subscribed'], $resultEntry['subscribed']);
    	}
    }
}
