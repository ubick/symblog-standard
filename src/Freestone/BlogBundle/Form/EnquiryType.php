<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Freestone\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EnquiryType extends AbstractType {
    
    protected $name = 'contact';
    
    public function buildForm(FormBuilder $builder, array $options) {
        $builder->add('name');
        $builder->add('age');
        $builder->add('email', 'email');
        $builder->add('subject');
        $builder->add('body', 'textarea');
    }
    
    public function getName() {
        return $this->name;
    }
    
}
