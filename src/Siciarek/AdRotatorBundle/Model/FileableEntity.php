<?php

namespace Siciarek\AdRotatorBundle\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class FileableEntity
{

// STEP ONE:

    public function getAbsolutePath()
    {
        return null === $this->photo
            ? null
            : $this->getUploadRootDir() . '/' . $this->photo;
    }

    public function getWebPath()
    {
        return null === $this->photo
            ? null
            : $this->getUploadDir() . '/' . $this->photo;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/authors';
    }

// STEP TWO:

    /**
     * UploadedFile
     */
    protected $uploaded_file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setUploadedFile(UploadedFile $uploaded_file = null)
    {
        $this->uploaded_file = $uploaded_file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploaded_file;
    }


// STEP THREE:

    public function upload($file_setter)
    {
        if (null === $this->getUploadedFile()) {
            return;
        }

        $ext = preg_replace("/^.*\.(\w+)$/", "$1", $this->getUploadedFile()->getClientOriginalName());

        do {
            $filename = sha1(uniqid(mt_rand(), true));
            $filename = $filename . "." . $ext;
            $fullname = $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $filename;
        } while (file_exists($fullname));

        $this->getUploadedFile()->move(
            $this->getUploadRootDir(),
            $fullname
        );

        $this->$file_setter($filename);

        $this->uploaded_file = null;
    }
}