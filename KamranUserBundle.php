<?php

namespace Kamran\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KamranUserBundle extends Bundle{

    public function getParent(){
        return 'FOSUserBundle';
    }

}//@
