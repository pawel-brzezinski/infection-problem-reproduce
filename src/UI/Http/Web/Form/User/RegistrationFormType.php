<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Form\User;

use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, PasswordType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\{Email, NotBlank};

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [new Email()],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new PasswordRequirements($this->buildPasswordConstraintOptions()),
                ],
            ])
        ;
    }

    private function buildPasswordConstraintOptions(): array
    {
        return [
            'minLength' => 8,
            'requireLetters' => true,
            'requireCaseDiff' => true,
            'requireNumbers' => true,
        ];
    }
}
