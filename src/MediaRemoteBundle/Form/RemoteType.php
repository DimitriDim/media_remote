<?php
namespace MediaRemoteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Range;

class RemoteType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('remoteName', TextType::class, // arg 2: on surcharge le type avec TextType::class
[
            "label" => "name.self", // arg 3: on surcharge avec des options
            "constraints" => [
                new Regex([
                    "pattern" => "/^[A-Z]{1}[a-z]{2,15}$/", // 1 maj au moins et entre 2 et 15 minuscule
                    "message" => "name.pattern.error" //message serveur (eviter d'en mettre)
                ])
            ],
            "attr" => [
                "pattern" => "^[A-Z]{1}[a-z]{2,15}$" // on bloque la validation client
            ]
        
        ])->add('remoteDuration', IntegerType::class, [
            "label" => "\\s",
            "constraints" => [
                new Range([ //pour intervalle
                    "min" => 1,
                    "max" => 24
                ])
            ],
            "attr" => [
                "min" => "1",
                "max" => "24"
            ]
        ]);
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MediaRemoteBundle\Entity\Remote'
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getBlockPrefix()
    {
        return 'mediaremotebundle_remote';
    }
}
