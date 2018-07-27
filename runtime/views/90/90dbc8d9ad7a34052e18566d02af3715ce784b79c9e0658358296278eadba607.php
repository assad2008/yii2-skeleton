<?php

/* site/index.html */
class __TwigTemplate_749fa69e3ec0fa71a8c6418d6c7a0e30ed26bf0391a1d4923b3403ce98106951 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
    </head>
    <body>
    ";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "post_title", array()), "html", null, true);
        echo "
    <br>
    ";
        // line 9
        echo twig_escape_filter($this->env, ($context["hello"] ?? null), "html", null, true);
        echo "
    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "site/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  36 => 9,  31 => 7,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
    </head>
    <body>
    {{ post.post_title }}
    <br>
    {{ hello }}
    </body>
</html>", "site/index.html", "/www/wwwroot/yii2-lite.yeedev.xyz/views/site/index.html");
    }
}
