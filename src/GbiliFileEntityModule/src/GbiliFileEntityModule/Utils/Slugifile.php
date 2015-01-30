<?php
namespace GbiliFileEntityModule\Utils;

/**
 * From some text input, try to make a valid filename
 * that can be used as a slug
 *
 * @author Gbili
 */
class Slugifile
{
    /**
     * @var 
     */
    protected $input;
    protected $basename;
    protected $extension='';

    /**
     * @param string $text
     */
    public function __construct($text=null)
    {
        if (null !== $text) {
            $this->setInput($text);
        }
    }

    /**
     * @param string $text
     * @return self
     */
    public function setInput($input)
    {
        if (!is_string($input)) {
            throw new \Exception('Bad input, expecting string.');
        }
        $lastDotPos = strrpos($input, '.');
        if (preg_match('/\.\w+$/', $input) && false !== $lastDotPos) {
            $this->setBasename(substr($input, 0, $lastDotPos));
            $this->setExtension(substr($input, $lastDotPos+1));
        } else {
            $this->setBasename($input);
        }
        return $this;
    }

    /**
     * Contains only alnum chars
     * @param string $extension
     * @return bool
     */
    public function isValidExtension($extension)
    {
        return is_string($extension) && !preg_match('/[^a-zA-Z0-9]/', $extension);
    }

    /**
     * @return self
     */
    public function setExtension($extension)
    {
        if (!$this->isValidExtension($extension)) {
            throw new \Exception('Bad Extension: ' . print_r($extension, true));
        }
        $this->extension = $extension;
        return $this;
    }

    public function filterBasename($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text)) {
            $text = 'n-a';
        }
        return $text;
    }

    /**
     * @return self
     */
    public function setBasename($basename)
    {
        $this->basename = $this->filterBasename($basename);
        return $this;
    }

    /**
     * @return string
     */
    public function getBasename()
    {
        if (null === $this->basename) {
            throw new \Exception('Basename not set');
        }
        return $this->basename;
    }

    /**
     * @return string
     */
    public function getExtension($withDot=null)
    {
        return (($withDot === null || $this->extension === '')? '' : '.') . $this->extension;
    }

    /**
     * Try to create a valid filename
     * @return string valid basename: somefile-is.bz2
     */
    public function getOutput()
    {
        $out = $this->getBasename() . $this->getExtension('.');
        if (0 === preg_match('#^[a-zA-Z0-9][a-zA-Z0-9-_]*?(?:\\.[a-zA-Z0-9]+)?$#', $out)) {
            throw new \Exception('The generated output is not acceptable');
        }
        return $out;
    }
}
