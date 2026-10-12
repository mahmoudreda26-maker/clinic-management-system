<?php

namespace App\Support\Tenancy;

use LogicException; 

class TenantContext
{
    private ?string $clinicId = null;

    public function setClinicId(string $clinicId): void
    {
        $this->clinicId = $clinicId;
    }

    public function clinicId(): string
    {
        if ($this->clinicId === null) {
            throw new LogicException(
                'Tenant context has not been initialized.'
            );
        }

        return $this->clinicId;
    }

    public function hasClinic(): bool
    {
        return $this->clinicId !== null;
    }

    public function reset(): void
    {
        $this->clinicId = null;
    }
}
