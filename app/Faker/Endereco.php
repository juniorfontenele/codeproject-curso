<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 06/03/2016
 * Time: 17:52
 */

namespace CodeProject\Faker;


use Faker\Provider\pt_BR\Address;

class Endereco extends Address
{
    protected static $addressFormats = array(
        "{{streetAddress}}\n{{city}} - {{stateAbbr}}\n{{postcode}}",
    );
}