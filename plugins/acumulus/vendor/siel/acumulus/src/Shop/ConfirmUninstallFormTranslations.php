<?php

declare(strict_types=1);

namespace Siel\Acumulus\Shop;

use Siel\Acumulus\Helpers\TranslationCollection;

/**
 * Contains translations for the confirm uninstall form.
 */
class ConfirmUninstallFormTranslations extends TranslationCollection
{
    protected array $nl = [
        'uninstallHeader' => 'Bevestig verwijderen',
        'desc_uninstall' => '<p>De module is uitgeschakeld. Maak een keuze of u ook alle data en instellingen wilt verwijderen of dat u deze (voorlopig) wilt bewaren.</p>',
    ];

    protected array $en = [
        'uninstallHeader' => 'Confirm uninstall',
        'desc_uninstall' => '<p>The module has been disabled. Choose whether you also want to delete all data and settings or if you want to keep these for now.</p>',
    ];
}
