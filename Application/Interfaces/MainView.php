<?php
namespace Application\Interfaces;

abstract class MainView extends \Framework\Interfaces\ViewInterface {
    public $MasterView = "Master";

    public abstract function HeadContent();
    public abstract function HeaderContent();
    public abstract function LeftColumn();
    public abstract function RightColumn();
    public abstract function CenterColumn();
    public abstract function FooterContent();
    public abstract function Javascript();
}

?>