<?php

require_once 'Isbn.php';

class IsbnText extends PHPUnit_Framework_TestCase {
    protected $uncleanedValidIsbn = ' 978-067-1552343  ';

    protected $validButUnusedIsbn = '8165270273';

    protected $validIsbns = array(
        '0671552341' => '9780671552343',
        '067180300X' => '9780671803001'
    );

    protected $validIsbn13Only = '9791090636071';

    public function testClean() {
        $this->assertEquals('9780671552343', Isbn::clean($this->uncleanedValidIsbn));
        $this->assertEquals('0671552341', Isbn::to10($this->uncleanedValidIsbn));
    }

    public function testNonIsbns() {
        $foo = 'foo';
        $bar = 'foofooofoo';
        $foobar = '978barbarbarr';

        $this->assertFalse(Isbn::validate($foo));
        $this->assertFalse(Isbn::to10($foo));
        $this->assertFalse(Isbn::to13($foo));
        $this->assertFalse(Isbn::validate($bar));
        $this->assertFalse(Isbn::to13($bar, true));
        $this->assertFalse(Isbn::validate($foobar));
        $this->assertFalse(Isbn::to10($foobar, true));
    }

    public function testValidateAndConvertValidIsbns() {
        foreach ($this->validIsbns as $isbn10 => $isbn13) {
            $this->assertTrue(Isbn::validate($isbn10));
            $this->assertTrue(Isbn::validate($isbn13));
            $this->assertTrue(Isbn::validate10($isbn10));
            $this->assertTrue(Isbn::validate13($isbn13));
            $this->assertFalse(Isbn::validate10($isbn13));
            $this->assertFalse(Isbn::validate13($isbn10));
            $this->assertEquals($isbn10, Isbn::to10($isbn10));
            $this->assertEquals($isbn10, Isbn::to10($isbn13));
            $this->assertEquals($isbn13, Isbn::to13($isbn13));
            $this->assertEquals($isbn13, Isbn::to13($isbn10));
        }
    }

    public function testValidateUncleanIsbnFails() {
        $this->assertFalse(Isbn::validate($this->uncleanedValidIsbn));
    }

    public function testValidateUnusedIsbn() {
        // unfortunately
        $this->assertTrue(Isbn::validate($this->validButUnusedIsbn));
    }

    public function testValidateAndConvertIsbn13Only() {
        $this->assertTrue(Isbn::validate($this->validIsbn13Only));
        $this->assertFalse(Isbn::to10($this->validIsbn13Only));
    }
}
