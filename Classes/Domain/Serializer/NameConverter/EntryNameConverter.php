<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Serializer\NameConverter;

/**
 * @author Sebastian Schreiber
 */
class EntryNameConverter extends BaseMappingNameConverter
{

    /**
     * Mapping of names, left side incoming names in xml|array, right side name for object
     * @var array
     */
    protected $mapNames = [
        'index' => 'external_id',
        'titel' => 'title',
        'untertitel' => 'subtitle',
        'ansprechpartner' => 'contact_person',
        'mapy' => 'latitude',
        'mapx' => 'longitude',
        'bundesland' => 'state',
        'kurztext' => 'teaser',
        'plz' => 'zip',
        'ort' => 'city',
        'logo' => 'image',
        'strasse' => 'street',
        'telefon' => 'telephone',
        'fax' => 'telefax',
        'email' => 'email',
        'website' => 'website',
        'beratertelefon' => 'hotline',
        'hinweistext' => 'notice',
        'angebot' => 'description',
        'kontaktemail' => 'contact_email',
        'suchcontent' => 'keywords',
        'beratungsart' => 'categories',
        'verband' => 'association',
        'traeger' => 'institution',
    ];
}
