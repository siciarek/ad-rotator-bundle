<?php
namespace Siciarek\AdRotatorBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class NipType extends \Doctrine\DBAL\Types\StringType
{
    protected $name = 'nip';

    /**
     * Nip validator:
     * http://pl.wikipedia.org/wiki/NIP/Implementacja
     *
     * @param $pNip
     * @return bool
     */
    public static function isValid($pNip) {
        if(!empty($pNip)) {
            $weights = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
            $nip = preg_replace('/[\s-]/', '', $pNip);
            if (strlen($nip) == 10 && is_numeric($nip)) {
                $sum = 0;
                for($i = 0; $i < 9; $i++)
                    $sum += $nip[$i] * $weights[$i];
                return ($sum % 11) == $nip[9];
            }
        }
        return false;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value !== null and !self::isValid($value)) {
            $msg = sprintf('Value "%s" is not a proper %s value.', $value, $this->name);
            throw new \InvalidArgumentException($msg);
        }

        if ($value !== null) {
            $value = preg_replace('/[\s-]/', '', $value);
        }

        return $value;
    }


    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return $value;
        }

        $val = preg_replace('/(\d{3})(\d{3})(\d{2})(\d{2})/', '$1-$2-$3-$4', $value);

        return $val;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
