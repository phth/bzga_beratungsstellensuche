<?php


namespace BZga\BzgaBeratungsstellensuche\Property\TypeConverter;

use BZgA\BzgaBeratungsstellensuche\Domain\Model\ValueObject\ImageLink;
use BZgA\BzgaBeratungsstellensuche\Property\TypeConverterBeforeInterface;
use BZgA\BzgaBeratungsstellensuche\Property\TypeConverterInterface;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Core\Resource\File as FalFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\Exception\TypeConverterException;
use BZgA\BzgaBeratungsstellensuche\Domain\Model\ExternalIdInterface;

class ImageLinkConverter implements TypeConverterBeforeInterface
{

    /**
     * Folder where the file upload should go to (including storage).
     */
    const CONFIGURATION_UPLOAD_FOLDER = 1;

    /**
     * How to handle a upload when the name of the uploaded file conflicts.
     */
    const CONFIGURATION_UPLOAD_CONFLICT_MODE = 2;

    /**
     * Whether to replace an already present resource.
     * Useful for "maxitems = 1" fields and properties
     * with no ObjectStorage annotation.
     */
    const CONFIGURATION_ALLOWED_FILE_EXTENSIONS = 4;

    /**
     * @var string
     */
    private $defaultUploadFolder = '1:/user_upload/tx_bzgaberatungsstellensuche';

    /**
     * @var string
     */
    private $tempFolder = PATH_site.'typo3temp/tx_bzgaberatungsstellensuche/';

    /**
     * One of 'cancel', 'replace', 'changeName'
     *
     * @var string
     */
    private $defaultConflictMode = 'replace';

    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     * @inject
     */
    private $resourceFactory;

    /**
     * @var array
     */
    private static $imageMimeTypes = array(
        'bmp' => 'image/bmp',
        'gif' => 'image/gif',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'tif' => 'image/tiff',
        'tiff' => 'image/tiff',
    );

    /**
     * @param mixed|ImageLink $source
     * @param string $type
     * @return bool
     */
    public function supports($source, $type = TypeConverterInterface::CONVERT_BEFORE)
    {
        if (!$source instanceof ImageLink) {
            return false;
        }

        return true;
    }

    /**
     * @param ImageLink $source
     * @param array|AbstractEntity $configuration
     * @return int
     * @throws TypeConverterException
     */
    public function convert($source, array $configuration = null)
    {
        // First of all we delete the old references
        $entity = $configuration['entity'];
        /* @var $entity \BZgA\BzgaBeratungsstellensuche\Domain\Model\Entry|ExternalIdInterface */

        $fileReferenceData = array(
            'table_local' => 'sys_file',
            'tablenames' => $configuration['tableName'],
            'uid_foreign' => $configuration['tableUid'],
            'fieldname' => 'image',
            'pid' => $entity->getPid(),
        );

        if ($entity->getUid()) {
            $this->deleteOldFileReferences($fileReferenceData);
        }

        // Check if we have no image url, return 0 if not
        if ('' === $source->getExternalUrl()) {
            return 0;
        }


        // Download the file
        $pathToUploadFile = $this->downloadFile($source, $entity);


        $falFile = $this->importResource($pathToUploadFile);
        $fileReferenceUid = uniqid('NEW_');
        $fileReferenceData['uid_local'] = $falFile->getUid();

        $manager = $configuration['manager'];
        /* @var $manager \Bzga\BzgaBeratungsstellensuche\Domain\Manager\AbstractManager */
        $manager->addDataMap('sys_file_reference', $fileReferenceUid, $fileReferenceData);

        return $fileReferenceUid;

    }

    /**
     * @param ImageLink $source
     * @param ExternalIdInterface $entity
     * @return string
     * @throws TypeConverterException
     */
    private function downloadFile(ImageLink $source, ExternalIdInterface $entity)
    {
        // @TODO: Make this mockable via vfstream
        $imageContent = GeneralUtility::getUrl($source->getExternalUrl());

        $imageInfo = getimagesizefromstring($imageContent);
        $extension = $this->getExtensionFromMimeType($imageInfo['mime']);
        $pathToUploadFile = $this->tempFolder.GeneralUtility::stdAuthCode($entity->getExternalId()).'.'.$extension;

        if ($error = GeneralUtility::writeFileToTypo3tempDir($pathToUploadFile, $imageContent)) {
            throw new TypeConverterException($error, 1399312443);
        }

        return $pathToUploadFile;
    }

    /**
     * @param $tempFilePath
     * @return FalFile
     */
    private function importResource($tempFilePath)
    {
        if (!GeneralUtility::verifyFilenameAgainstDenyPattern($tempFilePath)) {
            throw new TypeConverterException('Uploading files with PHP file extensions is not allowed!', 1399312430);
        }

        $uploadFolder = $this->resourceFactory->retrieveFileOrFolderObject($this->defaultUploadFolder);

        return $uploadFolder->addFile($tempFilePath, null, $this->defaultConflictMode);

    }


    /**
     * @param $mimeType
     * @return mixed
     */
    private function getExtensionFromMimeType($mimeType)
    {
        return array_search($mimeType, self::$imageMimeTypes);
    }

    /**
     * @param array $fileReferenceData
     */
    private function deleteOldFileReferences(array $fileReferenceData)
    {
        if (isset($fileReferenceData['uid_local'])) {
            unset($fileReferenceData['uid_local']);
        }

        $databaseConnection = $this->getDatabaseConnection();

        $where = array();
        foreach ($fileReferenceData as $key => $value) {
            $where[] = $key.'='.$databaseConnection->fullQuoteStr($value, 'sys_file_reference');
        }
        $databaseConnection->exec_DELETEquery('sys_file_reference', implode(' AND ', $where));
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    private function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }


}