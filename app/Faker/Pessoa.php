<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 06/03/2016
 * Time: 17:46
 */

namespace CodeProject\Faker;


use Faker\Provider\pt_BR\Person;

class Pessoa extends Person
{
    protected static $maleNameFormats = array(
        '{{firstNameMale}} {{lastName}}',
        '{{firstNameMale}} {{firstNameMale}} {{lastName}}',
        '{{firstNameMale}} {{lastName}} {{lastName}}',
        '{{firstNameMale}} {{lastName}} {{suffix}}',
        '{{firstNameMale}} {{firstNameMale}} {{lastName}} {{suffix}}',
        '{{firstNameMale}} {{lastName}} {{lastName}} {{suffix}}',
    );

    protected static $femaleNameFormats = array(
        '{{firstNameFemale}} {{lastName}}',
        '{{firstNameFemale}} {{firstNameFemale}} {{lastName}}',
        '{{firstNameFemale}} {{lastName}} {{lastName}}',
        '{{firstNameFemale}} {{lastName}} {{suffix}}',
        '{{firstNameFemale}} {{firstNameFemale}} {{lastName}} {{suffix}}',
        '{{firstNameFemale}} {{lastName}} {{lastName}} {{suffix}}',
    );
}