<?php
namespace GbiliFileEntityModuleTest\Utils;

class SlugifileTest extends \PHPUnit_Framework_TestCase
{
    protected $slugifile;

    public function setUp()
    {
        $this->slugifile = new \GbiliFileEntityModule\Utils\Slugifile();
    }

    public function testInputIsSetProperly()
    {
        $input = 'myinput.jk';
        $this->slugifile->setInput($input);
        $this->assertEquals($input, $this->slugifile->getOutput());
    }

    public function testInputIsSetOnConstruction()
    {
        $input = 'myinput.jk';
        $s = new \GbiliFileEntityModule\Utils\Slugifile($input);
        $this->assertEquals($input, $s->getOutput());
    } 

    /**
     * @expectedException \Exception
     */
    public function testSetInputThrowsOnArgInteger()
    {
        $this->slugifile->setInput(3);
    } 

    /**
     * @expectedException \Exception
     */
    public function testSetInputThrowsOnArgBoolean()
    {
        $this->slugifile->setInput(true);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetInputThrowsOnArgObj()
    {
        $this->slugifile->setInput(new \StdClass());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetInputThrowsOnArgArray()
    {
        $this->slugifile->setInput(array());
    }

    public function testBreaksPartsCorrectly()
    {
        $input = 'myinput.jk';
        $parts = explode('.', $input);
        $basename = $parts[0];
        $ext = $parts[1];
        $this->slugifile->setInput($input);
        $this->assertEquals($basename, $this->slugifile->getBasename());
        $this->assertEquals($ext, $this->slugifile->getExtension());
    }

    /**
     * @expectedException \Exception
     */
    public function testValidatesExtension()
    {
        $bad = '!ñç`^^';
        $this->assertEquals(false, $this->slugifile->isValidExtension($bad));
        $bad = 'asd-d';
        $this->assertEquals(false, $this->slugifile->isValidExtension($bad));
        $lcUc = 'asdF';
        $this->assertEquals(true, $this->slugifile->isValidExtension($lcUc));
        $uc = 'A';
        $this->assertEquals(true, $this->slugifile->isValidExtension($uc));
        $lc = 'A';
        $this->assertEquals(true, $this->slugifile->isValidExtension($lc));
        //Throw
        $this->slugifile->setExtension($bad);
    }

    public function testBasenameFilter() 
    {
        $dirty = '!ñç`^^';
        $this->assertEquals('nc', $this->slugifile->filterBasename($dirty));
        $dirty = '---------';
        $this->assertEquals('n-a', $this->slugifile->filterBasename($dirty));
        $clean = 'asd-d';
        $this->assertEquals($clean, $this->slugifile->filterBasename($clean));
        $clean = 'asdd';
        $this->assertEquals($clean, $this->slugifile->filterBasename($clean));
    }

    public function testBasenameTranslitWorksProperly()
    {
        $dirty = '!ñç`^^';
        $this->slugifile->setBasename($dirty);
        $this->assertEquals('nc', $this->slugifile->getBasename());
    }

    public function testBasenameSetAfterInputIsSetToOutput()
    {
        $input = 'input.ext';
        $this->slugifile->setInput($input);
        $this->assertEquals($input, $this->slugifile->getOutput());
        $this->slugifile->setBasename('basename');
        $this->assertEquals('basename.ext', $this->slugifile->getOutput());
    }

    public function testExtensionCanBeOmmited()
    {
        $noExtension = 'noExtension';
        $this->slugifile->setInput($noExtension);
        $this->assertEquals('noextension', $this->slugifile->getOutput());
    }

    public function testInputIsFiltered()
    {
        $noExtension = '.';
        $this->slugifile->setInput($noExtension);
        $this->assertEquals('n-a', $this->slugifile->getOutput());
    }
}
