<?php

namespace WilokeEmailCreator\Shared\Twig;

use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigTemplate
{
	public static ?TwigTemplate $oSelf = null;

	/**
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Twig\Error\LoaderError
	 */
	public static function init(): ?TwigTemplate
	{
		if (self::$oSelf == null) {
			self::$oSelf = new self();
		}
		return self::$oSelf;
	}

	/**
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Twig\Error\LoaderError
	 */
	public function renderTemplate($template, $aSetting): string
	{
		$templateString = htmlspecialchars_decode($template);
		$twig = new Environment(new ArrayLoader(['template' => $templateString]));
		$content = $twig->render('template', $aSetting);

		/* Need to decode HTML Special characters again, for assigned variables */
		return htmlspecialchars_decode($content);
	}

}
