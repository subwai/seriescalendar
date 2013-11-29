<?php

abstract class MainView extends ViewInterface {
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