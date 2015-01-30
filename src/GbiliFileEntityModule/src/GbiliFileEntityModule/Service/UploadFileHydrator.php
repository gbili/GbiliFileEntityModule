<?php
namespace GbiliFileEntityModule\Service;

class UploadFileHydrator implements \GbiliUploadModule\FileHydratorInterface
{
    public function getHydratedFile(array $fileData)
    {
        $persistableData = array_intersect_key($fileData, array_flip(array('name', 'type', 'date', 'tmp_name', 'size')));
        $persistableData['date'] = new \DateTime();

        $file = new \GbiliFileEntityModule\Entity\File();
        $file->hydrateWithFormData($persistableData);
        if ($file->getName() !== $file->getBasename()) {
            $file->move($file->getName());
        }
        return $file;
    }
}
