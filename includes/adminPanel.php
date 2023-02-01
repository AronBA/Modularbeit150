<?php

namespace adminPanel;

class adminPanel
{
    public float $lat;
    public float $lon;
    public string $key;
    public string $colour;
    public bool $metric;
    public string $lang;

    function __construct(){

    }

    function getAdminPanel(){
        return "
        <div class='adminPanel'>
            <form class='input' action=''>
            <div class='groupIn'>
            <label for='lon'></label>
                <input type='number' class='num'>
                <input type='number' class='num'>
            </div>
            <div class='groupIn'>
                <input type='radio' name='measurement' class='rad'>
                <input type='radio' name='measurement' class='rad'>
            </div>
            <div class='groupIn'>
                <input type='radio' name='measurement' class='rad'>
                <input type='radio' name='measurement' class='rad'>
            </div>
            </form>
            </div>
        </div>";
    }
}