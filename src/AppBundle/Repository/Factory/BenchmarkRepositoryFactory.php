<?php

namespace AppBundle\Repository\Factory;

use AppBundle\Entity\Main\BenchmarkMessage;
use AppBundle\Entity\Main\BenchmarkQuery;
use AppBundle\Entity\Main\Category;
use AppBundle\Entity\Main\Product;
use AppBundle\Entity\Main\Segment;
use AppBundle\Repository\Benchmark\BenchmarkMessageRepository;
use AppBundle\Repository\Benchmark\BenchmarkQueryRepository;
use AppBundle\Repository\Benchmark\CategoryRepository;
use AppBundle\Repository\Benchmark\CustomProductRepository;
use AppBundle\Repository\Benchmark\ProductRepository;
use AppBundle\Repository\Benchmark\SegmentRepository;
use Doctrine\Common\Persistence\ObjectManager;

class BenchmarkRepositoryFactory {

	/**
	 *
	 * @var ObjectManager
	 */
	protected $em;

	public function __construct(ObjectManager $em) {
		$this->em = $em;
	}

	public function getRepository($class) {
		if ($class == BenchmarkMessageRepository::class) {
			return new BenchmarkMessageRepository($this->em, 
					$this->em->getClassMetadata(BenchmarkMessage::class));
		}
		if ($class == BenchmarkQueryRepository::class) {
			return new BenchmarkQueryRepository($this->em, $this->em->getClassMetadata(BenchmarkQuery::class));
		}
		if ($class == CategoryRepository::class) {
			return new CategoryRepository($this->em, $this->em->getClassMetadata(Category::class));
		}
		if ($class == CustomProductRepository::class) {
			return new CustomProductRepository($this->em, $this->em->getClassMetadata(Product::class));
		}
		if ($class == ProductRepository::class) {
			return new ProductRepository($this->em, $this->em->getClassMetadata(Product::class));
		}
		if ($class == SegmentRepository::class) {
			return new SegmentRepository($this->em, $this->em->getClassMetadata(Segment::class));
		}
		
		return null;
	}
}