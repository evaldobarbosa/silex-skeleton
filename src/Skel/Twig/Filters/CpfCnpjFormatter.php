<?php
// src/AppBundle/Twig/AppExtension.php
namespace Skel\Twig\Filters;

class CpfCnpjFormatter extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('cpf_cnpj', array($this, 'myFilter')),
        );
    }

    public function myFilter($value)
    {
        $value = preg_replace("[^0-9]", "", $value);

        switch (strlen($value)) {
            case 11:
                $value = $this->cpf($value);
                break;

            case 14:
                $value = $this->cnpj($value);
                break;
        }

        return $value;
    }

    private function cnpj($value)
    {
        $matches = array();
        preg_match("/(([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2}))/", $value, $matches);

        unset($matches[0], $matches[1]);

        $value = "-{$matches[6]}";
        unset($matches[6]);

        $value = implode(".", $matches) . $value;

        unset($matches);

        return $value;
    }

    private function cpf($value)
    {
        $matches = array();
        preg_match("/(([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2}))/", $value, $matches);

        unset($matches[0], $matches[1]);

        $value = "-{$matches[5]}";
        unset($matches[5]);

        $value = implode(".", $matches) . $value;

        unset($matches);

        return $value;
    }

    public function getName()
    {
        return 'cpf_cnpj';
    }
}
