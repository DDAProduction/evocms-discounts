<?php


namespace EvolutionCMS\EvocmsDiscounts\Apply;


class AppliesLoader
{

    /**
     * @return Apply[]
     */
    public function loadApplies()
    {

        $dir = __DIR__;
        $namespace = 'EvolutionCMS\EvocmsDiscounts\Apply';
        $rules = [];
        $folders = glob(__DIR__ . '/*', GLOB_ONLYDIR);

        foreach ($folders as $folder) {
            if (preg_match('~/([^/]*)Apply$~ui', $folder, $matches)) {

                $ruleName = $matches[1];
                $ruleAlias = lcfirst($ruleName);


                $rules[$ruleAlias] = new Apply(
                    $namespace . '\\' . $ruleName . 'Apply\\' . $ruleName . 'ApplyController',
                    $namespace . '\\' . $ruleName . 'Apply\\' . $ruleName . 'ApplyModuleController',
                    'EvocmsDiscounts::apply.'.$this->parseView($ruleAlias)
                );
            }
        }

        return $rules;
    }

    /**
     * @param Apply[] $rules
     */
    public function initApplies($applies){

        foreach ($applies as $apply) {
            $apply->getModuleController()->init();
        }
    }

    private function parseView(string $applyAlias)
    {
        $parts = preg_split('/(?=[A-Z])/',$applyAlias);
        $parts = array_map('lcfirst',$parts);

        return implode('_',$parts);
    }

}