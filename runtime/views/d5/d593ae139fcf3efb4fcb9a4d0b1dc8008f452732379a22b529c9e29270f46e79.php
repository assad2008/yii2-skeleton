<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* site/index.html */
class __TwigTemplate_378072a4d44cad7b57c0656e9d41a35cd450fb65807251d62ba8c0f32bfd51c1 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
    </head>
    <body>
    ";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "post_title", [], "any", false, false, false, 7), "html", null, true);
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
        return array (  50 => 9,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
    </head>
    <body>
    {{ post.post_title }}
    <br>
    {{ hello }}
    </body>
</html>", "site/index.html", "/www/wwwroot/yii.yeedev.xyz/views/site/index.html");
    }
}
