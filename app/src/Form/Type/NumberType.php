<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType as SymfonyNumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberType extends AbstractType
{
public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults([
'attr' => ['class' => 'form-control'],
]);
}

public function getParent()
{
return SymfonyNumberType::class;
}
}