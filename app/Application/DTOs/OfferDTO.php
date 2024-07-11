<?php

namespace App\Application\DTOs;

use App\Domains\Interfaces\DTOInterface;
use App\Domains\ValueObjects\Cpf;
use App\Domains\ValueObjects\Id;
use Illuminate\Http\Request;

class OfferDTO implements DTOInterface
{

    private Cpf $cpf;
    private Id $institutionId;
    private string $modalityCode;

    /**
     * @return Cpf
     */
    public function getCpf(): Cpf
    {
        return $this->cpf;
    }

    /**
     * @param Cpf $cpf
     * @return OfferDTO
     */
    public function setCpf(Cpf $cpf): OfferDTO
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * @return Id
     */
    public function getInstitutionId(): Id
    {
        return $this->institutionId;
    }

    /**
     * @param Id $institutionId
     * @return OfferDTO
     */
    public function setInstitutionId(Id $institutionId): OfferDTO
    {
        $this->institutionId = $institutionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getModalityCode(): string
    {
        return $this->modalityCode;
    }

    /**
     * @param string $modalityCode
     * @return OfferDTO
     */
    public function setModalityCode(string $modalityCode): OfferDTO
    {
        $this->modalityCode = $modalityCode;
        return $this;
    }

    public function __construct(Cpf $cpf, Id $institutionId, string $modalityCode)
    {
        $this->cpf = $cpf;
        $this->institutionId = $institutionId;
        $this->modalityCode = $modalityCode;
    }

    public function jsonSerialize(): array
    {
        return [
            'cpf' => $this->getCpf()->getValue(),
            'instituicao_id' => $this->getInstitutionId()->getValue(),
            'codModalidade' => $this->getModalityCode()
        ];
    }

    public static function createFromRequest(Request $request): DTOInterface
    {
        return new OfferDTO(
            cpf: new Cpf($request->input('cpf')),
            institutionId: new Id($request->integer('instituicao_id')),
            modalityCode: $request->input('codModalidade'),
        );
    }
}
