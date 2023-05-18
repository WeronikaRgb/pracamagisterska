<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Form\Type\NumberType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RegistrationFormType.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder bulider
     * @param array                $options options
     *
     * @return void return none
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email');

        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Podane hasła muszą być identyczne.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'label.password'],
                'second_options' => ['label' => 'Powtórz hasło'],
//                'constraints' => [
//                    new NotBlank(['message' => 'To pole nie może być puste.']),
//                    new Length([
//                        'min' => 6,
//                        'minMessage' => 'Twoje hasło powinno zawierać co najmniej {{ limit }} znaków.',
//                        'max' => 4096,
//                    ]),
//                ],

            ]);
        $builder
            ->add('isOver13', CheckboxType::class, [
                'label' => 'Mam ukończone 13 lat',
                'mapped' => false, // Nie jest mapowane na pole encji User
                'required' => true, // Wymagane zaznaczenie
                'constraints' => [
                    new IsTrue([
                        'message' => 'Musisz mieć ukończone 13 lat.',
                    ]),
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver resolver
     *
     * @return void return noene
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}