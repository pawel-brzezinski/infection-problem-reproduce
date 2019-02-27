<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Web\Form\User;

use App\Tests\Library\Reflection;
use App\UI\Http\Web\Form\User\RegistrationFormType;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, PasswordType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\{Email, NotBlank};

class RegistrationFormTypeTest extends TestCase
{
    /** @var ObjectProphecy|FormBuilderInterface */
    private $builderMock;

    protected function setUp()
    {
        $this->builderMock = $this->prophesize(FormBuilderInterface::class);
    }

    protected function tearDown()
    {
        $this->builderMock = null;
    }

    public function testBuildForm()
    {
        // Given
        $this->builderMock
            ->add('email', EmailType::class, [
                'constraints' => [new Email()],
            ])
            ->shouldBeCalledTimes(1)
            ->willReturn($this->builderMock)
        ;
        $this->builderMock
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new PasswordRequirements([
                        'minLength' => 8,
                        'requireLetters' => true,
                        'requireCaseDiff' => true,
                        'requireNumbers' => true,
                    ]),
                ],
            ])
            ->shouldBeCalledTimes(1)
            ->willReturn($this->builderMock)
        ;

        $formUnderTest = $this->buildForm();

        // When
        $formUnderTest->buildForm($this->builderMock->reveal(), []);
    }

    public function testBuildPasswordConstraintOptions()
    {
        // Given
        $expected = [
            'minLength' => 8,
            'requireLetters' => true,
            'requireCaseDiff' => true,
            'requireNumbers' => true,
        ];

        $formUnderTest = $this->buildForm();
        $methodRef = Reflection::getReflectionMethod(RegistrationFormType::class, 'buildPasswordConstraintOptions');
        $methodRef->setAccessible(true);

        // When
        $actual = $methodRef->invoke($formUnderTest);

        // Then
        $this->assertSame($expected, $actual);
    }

    private function buildForm(): RegistrationFormType
    {
        return new RegistrationFormType();
    }
}
