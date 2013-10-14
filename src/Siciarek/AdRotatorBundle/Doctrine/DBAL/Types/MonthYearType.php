<?php
namespace Siciarek\AdRotatorBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class MonthYearType extends \Doctrine\DBAL\Types\DateTimeType
{
    protected $name = 'month_year';
    public static $pattern = '(0[1-9]|10|11|12|)\-20\d\d';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value !== null and !$this->isValid($value)) {
            $msg = sprintf('Value "%s" is not a proper %s value.', $value, $this->name);
            throw new \InvalidArgumentException($msg);
        }

        if ($value !== null) {
            list($month, $year) = explode('-', $value);

            $value = new \DateTime();
            $value->setDate($year, $month, 1);
        }

        return ($value !== null)
            ? $value->format('Y-m-d 00:00:00') : null;
    }


    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return \DateTime|null|string
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return $value;
        }

        $val = \DateTime::createFromFormat($platform->getDateTimeFormatString(), $value);
        if (!$val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }
        return $val->format('m-Y');
    }

    /**
     * @return bool
     */
    protected function isValid($value)
    {
        return preg_match('/^' . self::$pattern . '$/', $value) > 0;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
