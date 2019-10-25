<?php

namespace PagOnline\Tests\Unit\PayByMail;

use PagOnline\XmlEntities\Level3Info;
use PagOnline\Tests\Unit\IgfsCgBaseTest;
use PagOnline\PayByMail\IgfsCgPayByMailInit;
use PagOnline\XmlEntities\Level3InfoProduct;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\PayByMail\Requests\IgfsCgPayByMailInitRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgPayByMailInitTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgPayByMailInit::class;
    protected $igfsCgRequest = IgfsCgPayByMailInitRequest::CONTENT;

    protected function setIgfsRequiredParamenters(&$class): void
    {
        $class->mailID = 'mail@example.org';
        $class->shopUserRef = 'shopUserRef';
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingTrType(): void
    {
        /* @var \PagOnline\PayByMail\IgfsCgPayByMailInit $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing trType');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = null;
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingLangId(): void
    {
        /* @var \PagOnline\PayByMail\IgfsCgPayByMailInit $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing langID');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->langID = null;
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingShopUserRef(): void
    {
        /* @var \PagOnline\PayByMail\IgfsCgPayByMailInit $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing shopUserRef');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->langID = 'EN';
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingLevel3InfoProductCode(): void
    {
        /* @var \PagOnline\PayByMail\IgfsCgPayByMailInit $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing productCode[0]');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->langID = 'EN';
        $obj->shopUserRef = 'shopUserRef';
        $obj->level3Info = new Level3Info();
        $obj->level3Info->product = [new Level3InfoProduct()];
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingLevel3InfoProductDescription(): void
    {
        /* @var \PagOnline\PayByMail\IgfsCgPayByMailInit $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing productDescription[0]');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->langID = 'EN';
        $obj->shopUserRef = 'shopUserRef';
        $obj->level3Info = new Level3Info();
        $obj->level3Info->product = [new Level3InfoProduct()];
        $obj->level3Info->product[0]->productCode = 'productCode';
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldCheckFieldsAndPass(): void
    {
        /** @var \PagOnline\Mpi\IgfsCgMpiAuth $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('checkFields');
        $this->setIgfsRequiredParamenters($obj);
        $exception = null;
        try {
            $foo->invoke($obj);
        } catch (\Exception $exception) {
        }

        $this->assertNull($exception);
    }

    /** @test */
    public function shouldRaiseExceptionForMissingShopId(): void
    {
        $this->expectException(IgfsMissingParException::class);
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }
}
