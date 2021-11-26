<?php

namespace Mitquinn\HeapHelper\Traits;

use InvalidArgumentException;

trait HasProperties
{
    protected ?array $properties;

    /**
     * @return array|null
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }

    /**
     * @param array|null $properties
     * @return HasProperties
     */
    public function setProperties(?array $properties): static
    {
        if (!is_null($properties)) {

            foreach ($properties as $key => $value) {

                //Validation that key is a string.
                if (!is_string($key)) {
                    throw new InvalidArgumentException('Properties key must be a string.');
                }

                //Validation that value is only a string or integer.
                if (!is_string($value) and !is_integer($value)
                ) {
                    throw new InvalidArgumentException(
                        'Properties value can only be a string or integer.'
                    );
                }

                if (strlen($key) > 1024) {
                    throw new InvalidArgumentException('Property key must be no larger than 1024');
                }
                if (strlen($value) > 1024) {
                    throw new InvalidArgumentException('Property value must be no larger than 1024');
                }
            }
        }

        $this->properties = $properties;
        return $this;
    }

}