<?php

namespace PagOnline\Tests\Unit\PayByMail;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\PayByMail\IgfsCgPayByMailInit;
use PagOnline\PayByMail\Requests\IgfsCgPayByMailInitRequest;
use PagOnline\Tests\Unit\IgfsCgBaseTestCase;
use PagOnline\XmlEntities\Level3Info;
use PagOnline\XmlEntities\Level3InfoProduct;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgPayByMailInitTest extends IgfsCgBaseTestCase
{
    protected $igfsCgClass = IgfsCgPayByMailInit::class;
    protected $igfsCgRequest = IgfsCgPayByMailInitRequest::CONTENT;

    public function testChecksFieldsAndRaiseExceptionMissingTrType(): void
    {
        /* @var \PagOnline\PayByMail\IgfsCgPayByMailInit $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing trType');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = null;
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingLangId(): void
    {
        /* @var \PagOnline\PayByMail\IgfsCgPayByMailInit $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing langID');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->langID = null;
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingShopUserRef(): void
    {
        /* @var \PagOnline\PayByMail\IgfsCgPayByMailInit $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing shopUserRef');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->langID = 'EN';
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingLevel3InfoProductCode(): void
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

    public function testChecksFieldsAndRaiseExceptionMissingLevel3InfoProductDescription(): void
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

    public function testCheckFieldsAndPass(): void
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

    public function testRaiseExceptionForMissingShopId(): void
    {
        $this->expectException(IgfsMissingParException::class);
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }

    protected function setIgfsRequiredParamenters(&$class): void
    {
        $class->mailID = 'mail@example.org';
        $class->shopUserRef = 'shopUserRef';
    }
}
