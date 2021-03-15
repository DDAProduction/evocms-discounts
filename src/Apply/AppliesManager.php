<?php


namespace EvolutionCMS\EvocmsDiscounts\Apply;


class AppliesManager
{
    /**
     * @var Apply[]
     */
    private array $applies;

    public function __construct(AppliesLoader $appliesLoader)
    {

        $this->applies = $appliesLoader->loadApplies();

    }

    public function getApplies(){
        return $this->applies;
    }

    public function getApplyById($applyId){
        if(!array_key_exists($applyId,$this->applies)){
            throw new \Exception('Apply not found');
        }

        return $this->applies[$applyId];
    }
}